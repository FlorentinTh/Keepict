<?php
namespace Keepict\Controller;

use Keepict\Form\SignupForm;
use Keepict\Model\User;
use Keepict\Form\SigninForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use Keepict\Form\SettingsForm;
use PHPImageWorkshop\ImageWorkshop;
use Zend\Mvc\Controller\AbstractActionController;
use Classes\SessionUtils;
use Keepict\src\Keepict\Classes\FolderUtils;
use Keepict\src\Keepict\Classes\Mailer;
use Keepict\Form\SearchForm;
use Keepict\Model\Search;


require_once(__DIR__ . '/../Classes/SessionUtils.php');
require_once(__DIR__ . '/../Classes/FolderUtils.php');
require_once(__DIR__ . '/../Classes/Mailer.php');
require_once(__DIR__ . '/../../PHPImageWorkshop/ImageWorkshop.php');

class AccountController extends AbstractActionController{
    
    protected $userTable;
    
    public function getUserTable() {
    	if (!$this->userTable){
    		$sm = $this->getServiceLocator();
    		$this->userTable = $sm->get('Keepict\Model\UserTable');
    	}
    	return $this->userTable;
    }
    
    protected $pictureTable;
    
    public function getPictureTable() {
    	if (!$this->pictureTable){
    		$sm = $this->getServiceLocator();
    		$this->pictureTable = $sm->get('Keepict\Model\PictureTable');
    	}
    	return $this->pictureTable;
    }
    
    public function signupAction(){
        $wrongData = false;
        $existingCourriel = false;
        $validRequest = false;
        
        $form = new SignupForm();
         
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$user = new User();
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        		$user->exchangeArray($form->getData());
        		
        		$courriel = $this->getUserTable()->getUserByCourriel($user->courriel);
        		$birth = $user->birth;
        		$pattern = '/[0-9]{4}-[0-1]{1}[0-9]{1}-[0-9]{2}/';
        		
                if(empty($courriel)){
                    if(!preg_match_all($pattern, $birth)){
                        $wrongData = true;
                    }else{
                       
                       $mailer = new Mailer();
                       
                       $to = $user->courriel;
                       $subject = 'Keepict - Demande d\'adhésion';
                       $body = 'Cher ' . $user->firstname . ', Votre demande d\'adhésion a bien été prise en compte.
                                Néanmoins, votre demande doit être validée par un administrateur de la plateforme Keepict.
                                Merci de votre compréhension. L\'équipe de Keepict.';
                       
                       $mailer->sendCourriel($to, $subject, $body);
                        
                       $this->getUserTable()->createAccount($user);
                       
                       $validRequest = true;
                       
                       $form = new SignupForm();
                    }  
                }else{
                    $existingCourriel = true;
                }	        
        	}
        }
        
        return array(
            'validRequest' => $validRequest,
            'wrongData' => $wrongData,
            'existingCourriel' => $existingCourriel,
            'form' => $form
        );
    }
    
    public function signinAction() {
        
        $userNotAllowed = false;
        
    	$form = new SigninForm();

        $request = $this->getRequest();
        if ($request->isPost()){
            $post = $request->getPost();
            
            $sm = $this->getServiceLocator();
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

            $authAdapter = new AuthAdapter($dbAdapter);
            
            $user = $this->getUserTable()->getUserByCourriel($post->courriel);
            

            if(!empty($user) && $user->state != 1){
                $userNotAllowed = true;
            }else{
                $authAdapter->setTableName('user')
                ->setIdentityColumn('courriel')
                ->setCredentialColumn('password');
                
                $authAdapter->setIdentity($post->get('courriel'))
                ->setCredential(sha1($post->get('password')));
                
                $authService = new AuthenticationService();
                $authService->setAdapter($authAdapter);
                
                $result = $authService->authenticate();
                
                if ($result->isValid()){
                	return $this->redirect()->toRoute('flux', array('action' => 'index'));
                }else{
                	$userNotAllowed = true;
                }
            }
        }
        return array(
            'userNotAllowed' => $userNotAllowed,
            'form' => $form
        );
    }
    
    public function signoutAction(){
    	$auth = new AuthenticationService();
    	$auth->clearIdentity();
    	$session = new Container('user');
    	$session->offsetUnset('courriel');
    
    	return $this->redirect()->toRoute('account', array('action' => 'signup'));
    }
    
    public function settingsAction(){
                    
        $adapter = new \Zend\File\Transfer\Adapter\Http();
        $filesize  = new \Zend\Validator\File\Size(array('min' => 1));
        $extension = new \Zend\Validator\File\Extension(array('extension' => array('jpeg', 'jpg', 'png', 'gif')));
        
        $existingCourriel = false;
        $invalidBirth = false;
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        $oldAvatar = $user->avatar;
        
        $avatarFolder =  __PUBLIC__ . '/images/avatars/user_' . $user->id;
        
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        
        $formSearch = new SearchForm();
        
        $form = new SettingsForm();
        $form->bind($user);
        $form->get('password')->setAttribute('readonly','true');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $postData = $request->getPost();
            
            if(isset($postData['submit'])){
                
                $nonFile = $request->getPost()->toArray();
                $File    = $this->params()->fromFiles('avatar');
                $data = array_merge(
                		$nonFile,
                		array('avatar'=> $File['name'])
                );
                
                $form->setData($data);
                 
                if ($form->isValid()){
                	 
                	$adapter->setValidators(array($filesize, $extension), $File['name']);
                	 
                	$birth = $user->birth;
                	$pattern = '/[0-9]{4}-[0-1]{1}[0-9]{1}-[0-9]{2}/';
                
                	if(preg_match_all($pattern, $birth)){
                		if($logged === $form->getData()->courriel){
                			 
                			if ($adapter->isValid()){
                					
                				if(file_exists($avatarFolder)){
                					FolderUtils::rmdirRf($avatarFolder);
                					mkdir($avatarFolder, 0700);
                				}else{
                					mkdir($avatarFolder, 0700);
                				}
                
                				FolderUtils::rmdirRf($avatarFolder);
                				mkdir($avatarFolder, 0700);
                					
                				$adapter->setDestination($avatarFolder);
                				if ($adapter->receive($File['name'])) {
                					$newPath = $avatarFolder . '/avatar.' . explode('/', $File['type'], 2)[1];
                					$newName = '/avatar.' . explode('/', $File['type'], 2)[1];
                					rename($avatarFolder . '/'. $File['name'], $newPath);
                
                					$avatar = ImageWorkshop::initFromPath($newPath);
                
                					if($avatar->getHeight() > $avatar->getWidth()){
                						$avatar->resizeInPixel(42, null, true);
                					}else{
                						$avatar->resizeInPixel(null, 42, true);
                					}
                						
                					$avatar->cropInPixel(42, 42, 0, 0, 'MM');
                					$avatar->save($avatarFolder, $newName, null, null, 100);
                
                					$user->avatar = explode('/',$newPath,7)[6];
                					$this->getUserTable()->updateUserAvatar($user);
                				}
                			}
                			 
                			$this->getUserTable()->updateAccount($user);
                			$sm = $this->getServiceLocator();
                			$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                			 
                			$authAdapter = new AuthAdapter($dbAdapter);
                			 
                			$authAdapter->setTableName('user')
                			->setIdentityColumn('courriel')
                			->setCredentialColumn('password');
                			 
                			$authAdapter->setIdentity($user->courriel)
                			->setCredential($user->password);
                			 
                			$authService = new AuthenticationService();
                			$authService->setAdapter($authAdapter);
                			 
                			$result = $authService->authenticate();
                			return $this->redirect()->toRoute('flux', array('action' => 'index'));
                		}else{
                			$newCourriel = $this->getUserTable()->getUserByCourriel($form->getData()->courriel);
                			if(empty($newCourriel)){
                					
                				$adapter->setValidators(array($filesize, $extension), $File['name']);
                
                				if ($adapter->isValid()){
                					 
                					if(file_exists($avatarFolder)){
                						FolderUtils::rmdirRf($avatarFolder);
                						mkdir($avatarFolder, 0700);
                					}else{
                						mkdir($avatarFolder, 0700);
                					}
                
                					FolderUtils::rmdirRf($avatarFolder);
                					mkdir($avatarFolder, 0700);
                					 
                					$adapter->setDestination($avatarFolder);
                					if ($adapter->receive($File['name'])) {
                						$newPath = $avatarFolder . '/avatar.' . explode('/', $File['type'], 2)[1];
                						$newName = '/avatar.' . explode('/', $File['type'], 2)[1];
                						rename($avatarFolder . '/'. $File['name'], $newPath);
                
                						$avatar = ImageWorkshop::initFromPath($newPath);
                
                						if($avatar->getHeight() > $avatar->getWidth()){
                							$avatar->resizeInPixel(42, null, true);
                						}else{
                							$avatar->resizeInPixel(null, 42, true);
                						}
                						 
                						$avatar->cropInPixel(42, 42, 0, 0, 'MM');
                						$avatar->save($avatarFolder, $newName, null, null, 100);
                
                						$user->avatar = explode('/',$newPath,7)[6];
                						$this->getUserTable()->updateUserAvatar($user);
                					}
                				}
                				 
                				$this->getUserTable()->updateAccount($user);
                				$sm = $this->getServiceLocator();
                				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                
                				$authAdapter = new AuthAdapter($dbAdapter);
                
                				$authAdapter->setTableName('user')
                				->setIdentityColumn('courriel')
                				->setCredentialColumn('password');
                
                				$authAdapter->setIdentity($user->courriel)
                				->setCredential($user->password);
                
                				$authService = new AuthenticationService();
                				$authService->setAdapter($authAdapter);
                
                				$result = $authService->authenticate();
                				return $this->redirect()->toRoute('flux', array('action' => 'index'));
                			}else{
                				$user->avatar = $oldAvatar;
                				$this->getUserTable()->updateUserAvatar($user);
                				$existingCourriel = true;
                			}
                		}
                	}else{
                		$user->avatar = $oldAvatar;
                		$this->getUserTable()->updateUserAvatar($user);
                		$invalidBirth = true;
                	}
                }
                
            }else{
                $search = new Search();
                $formSearch->setData($request->getPost());
                
                if ($formSearch->isValid()) {
                	$search->exchangeArray($formSearch->getData());
                
                	$results = $this->getPictureTable()->searchPicture($search->predicate);
                
                	return $this->forward()->dispatch('Keepict\Controller\Search', array(
            			'action' => 'index',
            			'results' => $results,
            			'predicate' => $search->predicate
                	));
                }
            }
        }
        
        return array(
            'existingCourriel' => $existingCourriel,
            'invalidBirth' => $invalidBirth,
            'user' => $user,
            'form' => $form,
            'formSearch' => $formSearch,
            'numPendingUser' => $numPendingUser
        );
    } 
}