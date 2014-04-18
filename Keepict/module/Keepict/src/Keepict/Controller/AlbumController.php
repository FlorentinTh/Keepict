<?php

namespace Keepict\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Classes\SessionUtils;
use Keepict\Form\CommentForm;
use Keepict\Model\CommentAlbum;
use Keepict\Form\CreateAlbumForm;
use Keepict\Model\Album;
use PHPImageWorkshop\ImageWorkshop;
use Keepict\src\Keepict\Classes\FolderUtils;
use Keepict\Form\SearchForm;
use Keepict\Model\Search;
use Keepict\Form\EditAlbumForm;
use Keepict\Model\AlbumEdit;

require_once(__DIR__ . '/../Classes/SessionUtils.php');
require_once(__DIR__ . '/../Classes/FolderUtils.php');
require_once(__DIR__ . '/../../PHPImageWorkshop/ImageWorkshop.php');

class AlbumController extends AbstractActionController {
    
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
    
    protected $pictureTable;
    
    public function getPictureTable() {
    	if (!$this->pictureTable){
    		$sm = $this->getServiceLocator();
    		$this->pictureTable = $sm->get('Keepict\Model\PictureTable');
    	}
    	return $this->pictureTable;
    }
    
    protected $commentAlbumTable;
    
    public function getCommentAlbumTable() {
    	if (!$this->commentAlbumTable){
    		$sm = $this->getServiceLocator();
    		$this->commentAlbumTable = $sm->get('Keepict\Model\CommentAlbumTable');
    	}
    	return $this->commentAlbumTable;
    }   
        
    public function indexAction() {    
           
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
            return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        
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
        
        $paginator = $this->getAlbumTable()->getAlbumsPublished(true);
        $currentPage = (int) $this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(12);

        return array(
            'user' => $user,
            'numPendingUser' => $numPendingUser,
            'albums' => $paginator,
            'currentPage' => $currentPage,
            'formSearch' => $formSearch
        );
    }
    
    public function recordAction() {
        
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if($id === 1){
            return $this->redirect()->toRoute('album');
        }
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
            return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        
        $album = $this->getAlbumTable()->getAlbumById($id);
        $pictures = $this->getAlbumTable()->getFirstPicturesFromAlbum($id);
        $comments = $this->getCommentAlbumTable()->getCommentsByAlbum($id);
        
        $form = new CommentForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $postData = $request->getPost();
            
            if(isset($postData['submit'])){
                $comment = new CommentAlbum();
                $form->setData($request->getPost());
                
                if ($form->isValid()) {
                	$comment->exchangeArray($form->getData());
                	$this->getCommentAlbumTable()->postComment($comment, $user, $album);
                	return $this->redirect()->toRoute('album', array('action' => 'record', 'id' => $id, 'slug' => $album->slug));
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
    		'user' => $user,
    		'numPendingUser' => $numPendingUser,
            'album' => $album,
            'pictures' => $pictures,
            'nbPictures' => $pictures->count(),
            'comments' => $comments,
            'nbComments' => $comments->count(),
            'form' => $form,
            'formSearch' => $formSearch
        );
    }
    
    public function detailsAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if($id === 1): return $this->redirect()->toRoute('album'); endif;
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
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
        
        $album = $this->getAlbumTable()->getAlbumById($id);
               
        $paginator = $this->getPictureTable()->getAllPicturesFromAlbum($id, true);
        $currentPage = (int) $this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(12);

        if($paginator->count() === 0): return $this->redirect()->toRoute('album', array('action' => 'record', 'id' => $id, 'slug' => $album->slug)); endif;
        
        return array(
            'user' => $user,
            'numPendingUser' => $numPendingUser,
            'album' => $album,
            'pictures' => $paginator,
            'id_album' => $id,
            'formSearch' => $formSearch
        );
    }
    
    public function editAction(){
        
        $id = (int) $this->params()->fromRoute('id', null);
        
        $isFieldsValid = true;

    	$logged = SessionUtils::checkUserAllowed();
    	if($logged === null){
    		return $this->redirect()->toRoute('account', array('action' => 'signup'));
    	}
    
    	$user = $this->getUserTable()->getUserByCourriel($logged);
    
    	if($user->isAdmin != 1){
    		return $this->redirect()->toRoute('flux', array('action' => 'index'));
    	}
    	
    	$numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        
        $album = $this->getAlbumTable()->getAlbumById($id);
        $pictures = $this->getAlbumTable()->getFirstPicturesFromAlbum($id);
        $comments = $this->getCommentAlbumTable()->getCommentsByAlbum($id);
                
        $formEdit = new EditAlbumForm();
        
        $formEdit->get('id')->setValue($id);
        $formEdit->get('name')->setValue($album->name);
        $formEdit->get('description')->setValue($album->description);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $postData = $request->getPost();
            
            if(isset($postData['save'])){
                $albumEdit = new AlbumEdit();
                $formEdit->setData($request->getPost());
                if($formEdit->isValid()){
                    $albumEdit->exchangeArray($formEdit->getData());
                    
                    $patternTitle = '/[-_#!?&$%:£=a-zA-Z0-9]+/';
                    $patternDescription = '/[-_.#!)?*&($;%:£=a-zA-Z0-9]+/';
                    
                    $slug = str_replace(array(' ', '_', '\\', '/', '*', '"', '\'', '#', '$', '!', '.', '&', '£', '=', ':', '%', '?'), '-', strtolower($albumEdit->name));
                    
                    if(preg_match_all($patternTitle, $albumEdit->name) &&
                    preg_match_all($patternDescription, $albumEdit->description)){
                        $pathFolder = __PUBLIC__ . '/images/albums/' . $album->slug;
                        $newPath = __PUBLIC__ . '/images/albums/' . $slug;

                        if(rename($pathFolder, $newPath)){

                            $files = array_diff(scandir($newPath . '/cover'), array('.', '..', '.DS_Store'));
                            $patternThumb = '/^thumb_/';
                            
                            foreach($files as $file){
                                if(preg_match_all($patternThumb, $file)){
                                    $pathCoverThumbnail = 'images/albums/' . $slug . '/cover/' . $file;
                                }else{
                                    $pathCover = 'images/albums/' . $slug . '/cover/' . $file;
                                }
                            }
                            
                            $this->getAlbumTable()->updateAlbum($id, $albumEdit->name, $albumEdit->description, $pathCoverThumbnail, $pathCover, $slug);

                            $paths = $this->getPictureTable()->getPicturePathFromAlbum($album)->toArray();
                            
                            if(!empty($paths)){
                            	foreach($paths as $p){
                            	    $id = (int) $p['id'];
                            	    $pathPicture = implode('/', array_replace(explode('/', $p['pathPicture']), array(2 => $slug)));
                            		$pathPictureThumb = implode('/', array_replace(explode('/', $p['pathThumbnail']), array(2 => $slug)));
                        		    $this->getPictureTable()->updatePicturePath($id, $pathPictureThumb, $pathPicture);
                            	}
                            }
                            
                            return $this->redirect()->toRoute('album', array('action'=>'record', 'id' => $album->id, 'slug' => $slug));
                        }else{
                            $isFieldsValid = false;
                        }
                    }else{
                    	$isFieldsValid = false;
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
    		'user' => $user,
    		'numPendingUser' => $numPendingUser,
            'album' => $album,
            'pictures' => $pictures,
            'nbPictures' => $pictures->count(),
            'comments' => $comments,
            'nbComments' => $comments->count(),
            'formEdit' => $formEdit,
            'formSearch' => $formSearch
        );
    }
    
    public function deleteCommentAction(){
        $id = (int) $this->params()->fromRoute('id', null);
        $album = (int) $this->params()->fromRoute('album', null);
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        
        if($user->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
        
        $album = $this->getAlbumTable()->getAlbumById($album);
        
        $this->getCommentAlbumTable()->removeComment($id);
        
        return $this->redirect()->toRoute('album', array('action' => 'edit', 'id' => $album->id, 'slug' => $album->slug));
    }
    
    public function createAction(){
        
        $adapter = new \Zend\File\Transfer\Adapter\Http();
        $filesize  = new \Zend\Validator\File\Size(array('min' => 1));
        $extension = new \Zend\Validator\File\Extension(array('extension' => array('jpeg', 'jpg', 'png', 'gif')));
        
        $invalidFile = false;
        $existingAlbum = false;
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        
        if($user->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
        
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        $form = new CreateAlbumForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $postData = $request->getPost();
            
            if(isset($postData['submit'])){
                                
                $album = new Album();
                $form->setData($request->getPost());
                 
                $nonFile = $request->getPost()->toArray();
                $File    = $this->params()->fromFiles('cover');
                $data = array_merge(
                		$nonFile,
                		array('cover'=> $File['name'])
                );
                
                if ($form->isValid()) {
                	 
                	$album->exchangeArray($form->getData());
                
                	$adapter->setValidators(array($filesize, $extension), $File['name']);
                
                	$album->slug = str_replace(array(' ', '_', '\\', '/', '*', '"', '\'', '#'), '-', strtolower($album->name));
                
                	$albumFolder = __PUBLIC__ . '/images/albums/' . $album->slug;
                
                	if(!empty($this->getAlbumTable()->getAlbumByName($album->name)->current()->name)){
                		$existingAlbum = true;
                	}
                
                	if ($adapter->isValid() && $existingAlbum !== true){
                
                		if(!is_dir($albumFolder)) {
                			mkdir($albumFolder, 0700);
                			mkdir($albumFolder . '/cover', 0700);
                			mkdir($albumFolder . '/pictures', 0700);
                		}
                
                		if(count(glob($albumFolder . '/cover/*')) !== 0){
                			FolderUtils::rmdirRf($albumFolder . '/cover');
                			mkdir($albumFolder . '/cover', 0700);
                		}
                
                		$coverFolder = $albumFolder . '/cover';
                
                		$adapter->setDestination($coverFolder);
                
                		if ($adapter->receive($File['name'])) {
                
                			$name = explode('.', $File['name'], 2)[0];
                			$ext = explode('.', $File['name'], 2)[1];
                
                			$cover = ImageWorkshop::initFromPath($coverFolder . '/' . $name . '.' . $ext);
                			$watermark = ImageWorkshop::initTextLayer('© Keepict', __PUBLIC__ . '/fonts/lato/lato-regular.ttf', 12, 'ffffff', 0);

                			if($cover->getHeight() > $cover->getWidth()){
                				$cover->resizeInPixel(800, null, true);
                				$cover->cropInPixel(600, 800, 0, 0, 'MM');
                			}else{
                				$cover->resizeInPixel(null, 800, true);
                				$cover->cropInPixel(800, 600, 0, 0, 'MM');
                			}
                			$cover->addLayerOnTop($watermark, 5, 5, "MB");
                			$cover->save($coverFolder, 'cover.' . $ext, null, null, 100);
                
                			$coverThumb = ImageWorkshop::initFromPath($coverFolder . '/cover.' . $ext);
                			if($coverThumb->getHeight() > $coverThumb->getWidth()){
                				$coverThumb->resizeInPixel(280, null, true);
                			}else{
                				$coverThumb->resizeInPixel(null, 280, true);
                			}
                			$coverThumb->cropInPixel(280, 280, 0, 0, 'MM');
                			$coverThumb->save($coverFolder, 'thumb_cover.' . $ext, null, null, 100);
                			 
                			if(file_exists($coverFolder . '/' . $name . '.' . $ext)) {
                				unlink($coverFolder . '/' . $name . '.' . $ext);
                			}
                			 
                			$explode = explode('/', $coverFolder);
                			$slice = array_slice($explode, 6);
                			$path = implode('/', $slice);
                			 
                			$album->pathCoverThumbnail = $path . '/' . 'thumb_cover.' . $ext;
                			$album->pathCover = $path . '/' . 'cover.' . $ext;
                			 
                			if(empty($album->description)){
                				$album->description = 'Aucune description';
                			}
                			 
                			$this->getAlbumTable()->createAlbum($album);
                			 
                			return $this->redirect()->toRoute('album', array('action' => 'create'));
                		}
                	}else{
                		$invalidFile = true;
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
    		'user' => $user,
    		'numPendingUser' => $numPendingUser,
            'form' => $form,
            'invalidFile' => $invalidFile,
            'existingAlbum' => $existingAlbum,
            'formSearch' => $formSearch
        );
    }
}