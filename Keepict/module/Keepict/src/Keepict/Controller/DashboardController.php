<?php

namespace Keepict\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Classes\SessionUtils;
use Keepict\Form\CategoryForm;
use Keepict\Model\Category;
use Keepict\src\Keepict\Classes\Mailer;
use Keepict\Form\SearchForm;
use Keepict\Model\Search;

require_once(__DIR__ . '/../Classes/SessionUtils.php');
require_once(__DIR__ . '/../Classes/Mailer.php');

class DashboardController extends AbstractActionController {
    
    protected $userTable;
    
    public function getUserTable() {
    	if (!$this->userTable){
    		$sm = $this->getServiceLocator();
    		$this->userTable = $sm->get('Keepict\Model\UserTable');
    	}
    	return $this->userTable;
    }
    
    protected $albumTable;
    
    public function getAlbumTable() {
    	if (!$this->albumTable){
    		$sm = $this->getServiceLocator();
    		$this->albumTable = $sm->get('Keepict\Model\AlbumTable');
    	}
    	return $this->albumTable;
    }
    
    protected $categoryTable;
    
    public function getCategoryTable() {
    	if (!$this->categoryTable){
    		$sm = $this->getServiceLocator();
    		$this->categoryTable = $sm->get('Keepict\Model\CategoryTable');
    	}
    	return $this->categoryTable;
    }
    
    protected $pictureTable;
    
    public function getPictureTable() {
    	if (!$this->pictureTable){
    		$sm = $this->getServiceLocator();
    		$this->pictureTable = $sm->get('Keepict\Model\PictureTable');
    	}
    	return $this->pictureTable;
    }

    public function membersAction() { 

        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
            return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }

        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
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
                                        
        $usersEnabled = $this->getUserTable()->getEnabledUsers();
        
        return array(
    		'user' => $userLogged,
    		'usersEnabled' => $usersEnabled,
            'numPendingUser' => $numPendingUser,
            'formSearch' => $formSearch
        );
    }
    
    public function moveToBlacklistAction(){
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
            return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
        
        $id = (int)$this->params()->fromRoute('id', 0);
        
        $user = $this->getUserTable()->getUserById($id);
        
        $this->getUserTable()->moveToBlacklist($user);
        
        return $this->redirect()->toRoute('dashboard', array('action' => 'members'));
    }
    
    public function userRequestsAction(){
               
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
            return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }

        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
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
        
        $usersPending = $this->getUserTable()->getPendingUsers();
        
        return array(
    		'user' => $userLogged,
    		'usersPending' => $usersPending,
            'numPendingUser' => $numPendingUser,
            'formSearch' => $formSearch
        );
    }
    
    public function acceptRequestAction(){
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
            return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
    
    	$id = (int)$this->params()->fromRoute('id', 0);
    
    	$user = $this->getUserTable()->getUserById($id);
    	
    	$mailer = new Mailer();
    	 
    	$to = $user->courriel;
    	$subject = 'Keepict - Réponse à votre demande d\'adhésion';
    	$body = 'Cher ' . $user->firstname . ', Nous avons le plaisir de vous annoncer que votre demande d\'adhésion
                à la plateforme Keepict vient d\'être acceptée par un de nos administrateurs. 
    	        L\'équipe de Keepict.';
    	 
    	$mailer->sendCourriel($to, $subject, $body);
    
    	$this->getUserTable()->acceptRequest($user);
        	
    	return $this->redirect()->toRoute('dashboard', array('action' => 'user-requests'));
    }
    
    public function declineRequestAction(){

        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
    
    	$id = (int)$this->params()->fromRoute('id', 0);
    
    	$user = $this->getUserTable()->getUserById($id);
    	
    	$mailer = new Mailer();
    	
    	$to = $user->courriel;
    	$subject = 'Keepict - Réponse à votre demande d\'adhésion';
    	$body = 'Cher ' . $user->firstname . ', Nous avons le regret de vous annoncer que votre demande d\'adhésion
                à la plateforme Keepict vient d\'être refusée par un de nos administrateurs.
    	        L\'équipe de Keepict.';
    	
    	$mailer->sendCourriel($to, $subject, $body);
    
    	$this->getUserTable()->declineRequest($user);
    
    	return $this->redirect()->toRoute('dashboard', array('action' => 'user-requests'));
    }
    
    public function blacklistAction(){

        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
        
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        $numUserBlocked = $this->getUserTable()->countUserBlacklist()->current()->num;
        
        $formSearch = new SearchForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
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
        
        $usersDisabled = $this->getUserTable()->getDisabledUsers();
        
        return array(
    		'user' => $userLogged,
    		'usersDisabled' => $usersDisabled,
            'numPendingUser' => $numPendingUser,
            'numUserBlocked' => $numUserBlocked,
            'formSearch' => $formSearch
        );
    }
    
    public function removeFromBlacklistAction(){
        
    	$logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
    
    	$id = (int)$this->params()->fromRoute('id', 0);
    
    	$user = $this->getUserTable()->getUserById($id);
    
    	$this->getUserTable()->removeFromBlacklist($user);
    
    	return $this->redirect()->toRoute('dashboard', array('action' => 'blacklist'));
    }
    
    public function publishedAlbumsAction(){
    
    	$logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
        
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
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
        
        $published = 1;
        $publishedAlbums = $this->getAlbumTable()->getAlbumByVisibility($published);
        
        return array(
    		'user' => $userLogged,
            'numPendingUser' => $numPendingUser,
            'publishedAlbums' => $publishedAlbums,
            'nbPublishedAlbums' => $publishedAlbums->count(),
            'formSearch' => $formSearch
        );
    }
    
    public function moveToUnpublishedAction(){
    
    	$logged = SessionUtils::checkUserAllowed();
    	if($logged === null){
    		return $this->redirect()->toRoute('account', array('action' => 'signup'));
    	}
    	$userLogged = $this->getUserTable()->getUserByCourriel($logged);
    	if($userLogged->isAdmin != 1){
    		return $this->redirect()->toRoute('flux', array('action' => 'index'));
    	}
    
    	$id = (int)$this->params()->fromRoute('id', 0);
    
    	$album = $this->getAlbumTable()->getAlbumById($id);
    
    	$this->getAlbumTable()->setAlbumUnpublished($album);
    
    	return $this->redirect()->toRoute('dashboard', array('action' => 'published-albums'));
    }
    
    public function unpublishedAlbumsAction(){
    
    	$logged = SessionUtils::checkUserAllowed();
    	if($logged === null){
    		return $this->redirect()->toRoute('account', array('action' => 'signup'));
    	}
    	$userLogged = $this->getUserTable()->getUserByCourriel($logged);
    	if($userLogged->isAdmin != 1){
    		return $this->redirect()->toRoute('flux', array('action' => 'index'));
    	}
    
    	$numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
    	
    	$formSearch = new SearchForm();
    	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
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
    
    	$unpublished = 2;
    	$unpublishedAlbums = $this->getAlbumTable()->getAlbumByVisibility($unpublished);
    
    	return array(
			'user' => $userLogged,
			'numPendingUser' => $numPendingUser,
			'unpublishedAlbums' => $unpublishedAlbums,
			'nbUnpublishedAlbums' => $unpublishedAlbums->count(),
    	    'formSearch' => $formSearch
    	);
    }
    
    public function moveToPublishedAction(){
    
    	$logged = SessionUtils::checkUserAllowed();
    	if($logged === null){
    		return $this->redirect()->toRoute('account', array('action' => 'signup'));
    	}
    	$userLogged = $this->getUserTable()->getUserByCourriel($logged);
    	if($userLogged->isAdmin != 1){
    		return $this->redirect()->toRoute('flux', array('action' => 'index'));
    	}
    
    	$id = (int)$this->params()->fromRoute('id', 0);
    
    	$album = $this->getAlbumTable()->getAlbumById($id);
    
    	$this->getAlbumTable()->setAlbumPublished($album);
    
    	return $this->redirect()->toRoute('dashboard', array('action' => 'unpublished-albums'));
    }
    
    public function addAction(){
    
        $existingCategory = false;
        
    	$logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        $userLogged = $this->getUserTable()->getUserByCourriel($logged);
        if($userLogged->isAdmin != 1){
            return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }

        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        $form = new CategoryForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $postData = $request->getPost();
            
            if(isset($postData['submit'])){
                $category = new Category();
                $form->setData($request->getPost());
                
                if ($form->isValid()) {
                	$category->exchangeArray($form->getData());
                
                	$name = $this->getCategoryTable()->getCategoryByName($category)->count();
                
                	if($name === 0){
                		$this->getCategoryTable()->createCategory($category);
                		return $this->redirect()->toRoute('dashboard');
                	}else{
                		$existingCategory = true;
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
    		'user' => $userLogged,
            'numPendingUser' => $numPendingUser,
            'form' => $form,
            'existingCategory' => $existingCategory,
            'formSearch' => $formSearch
        );
    }
}