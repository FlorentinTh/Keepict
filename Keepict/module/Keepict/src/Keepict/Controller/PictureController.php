<?php

namespace Keepict\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Classes\SessionUtils;
use PHPImageWorkshop\ImageWorkshop;
use Keepict\src\Keepict\Classes\PictureUtils;
use Keepict\Model\CommentPicture;
use Keepict\Form\CommentForm;
use Keepict\Form\SearchForm;
use Keepict\Model\Search;
use Keepict\Form\ImportForm;
use Keepict\Form\EditPictureForm;
use Keepict\Model\PictureEdit;

require_once(__DIR__ . '/../Classes/SessionUtils.php');
require_once(__DIR__ . '/../../PHPImageWorkshop/ImageWorkshop.php');
require_once(__DIR__ . '/../Classes/PictureUtils.php');

class PictureController extends AbstractActionController {
    
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
    
    protected $commentPictureTable;
    
    public function getCommentPictureTable() {
    	if (!$this->commentPictureTable){
    		$sm = $this->getServiceLocator();
    		$this->commentPictureTable = $sm->get('Keepict\Model\CommentPictureTable');
    	}
    	return $this->commentPictureTable;
    }
    
    protected $tagTable;
    
    public function getTagTable() {
    	if (!$this->tagTable){
    		$sm = $this->getServiceLocator();
    		$this->tagTable = $sm->get('Keepict\Model\TagTable');
    	}
    	return $this->tagTable;
    }
    
    protected $tagPictureTable;
    
    public function getTagPictureTable() {
    	if (!$this->tagPictureTable){
    		$sm = $this->getServiceLocator();
    		$this->tagPictureTable = $sm->get('Keepict\Model\TagPictureTable');
    	}
    	return $this->tagPictureTable;
    }
    
    protected $categoryTable;
    
    public function getCategoryTable() {
    	if (!$this->categoryTable){
    		$sm = $this->getServiceLocator();
    		$this->categoryTable = $sm->get('Keepict\Model\CategoryTable');
    	}
    	return $this->categoryTable;
    }
    
    protected $albumTable;
    
    public function getAlbumTable() {
    	if (!$this->albumTable){
    		$sm = $this->getServiceLocator();
    		$this->albumTable = $sm->get('Keepict\Model\AlbumTable');
    	}
    	return $this->albumTable;
    }
    
    protected $albumPictureTable;
    
    public function getAlbumPictureTable() {
    	if (!$this->albumPictureTable){
    		$sm = $this->getServiceLocator();
    		$this->albumPictureTable = $sm->get('Keepict\Model\AlbumPictureTable');
    	}
    	return $this->albumPictureTable;
    }
    
    public function indexAction() {  

        $id = (int) $this->params()->fromRoute('id', 0);
        
        $isLandscape = false;
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
            return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $formSearch = new SearchForm();
        
        $picture = $this->getPictureTable()->getPictureById($id);
        $img = ImageWorkshop::initFromPath(__PUBLIC__ . '/' . $picture->pathPicture);
        if($img->getWidth() > $img->getHeight()){
        	$isLandscape = true;
        }
        $category = $this->getPictureTable()->getCategoryFromPicture($id, $picture->category);
        $comments = $this->getCommentPictureTable()->getAllCommentsByPicture($id);
        $exifs = PictureUtils::printExifData(exif_read_data(__PUBLIC__ . '/' . $picture->pathPicture));
        $tags = $this->getTagTable()->getTagsFromPicture($id);
        
        $form = new CommentForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            
            if(isset($postData['submit'])){
                $comment = new CommentPicture();
                $form->setData($request->getPost());
                
                if ($form->isValid()) {
                	$comment->exchangeArray($form->getData());
                	$this->getCommentPictureTable()->postComment($comment, $user, $picture);
                	return $this->redirect()->toRoute('picture', array('action'=>'index', 'id' => $picture->id, 'slug' => $picture->slug));
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
            'picture' => $picture,
            'category' => $category->current(),
            'isLandscape' => $isLandscape,
            'comments' => $comments,
            'nbComments' => $comments->count(),
            'exifs' => $exifs,
            'tags' => $tags,
            'nbTags' => $tags->count(),
            'form' => $form,
            'formSearch' => $formSearch
        );
    }
    
    public function filterTagAction() {
    
    	$id = (int) $this->params()->fromRoute('id', 0);
       
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
        
    	$paginator = $this->getPictureTable()->getPicturesByTag($id, true);
        $currentPage = (int) $this->params()->fromRoute('page', 1);
    	$paginator->setCurrentPageNumber($currentPage);
    	$paginator->setItemCountPerPage(12);
    	 
    	$tag = $this->getTagTable()->getTagById($id);
    
    	return array(
			'user' => $user,
			'numPendingUser' => $numPendingUser,
    	    'pictures' => $paginator,
    	    'tag' => $tag,
    	    'id_tag' => $id,
    	    'formSearch' => $formSearch
    	);
    }
    
    public function filterCategoryAction() {
    
    	$id = (int) $this->params()->fromRoute('id', 0);
    	 
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
    	
    	$paginator = $this->getPictureTable()->getPicturesByCategory($id, true);
    	$currentPage = (int) $this->params()->fromRoute('page', 1);
    	$paginator->setCurrentPageNumber($currentPage);
    	$paginator->setItemCountPerPage(12);
    	
    	$category = $this->getCategoryTable()->getCategoryById($id);
        	    
    	return array(
			'user' => $user,
			'numPendingUser' => $numPendingUser,
    	    'pictures' => $paginator,
    	    'category' => $category,
    	    'id_category' => $id,
    	    'formSearch' => $formSearch
    	);
    }
    
    public function editAction(){
        $id = (int) $this->params()->fromRoute('id', 0);
        
        $isLandscape = false;
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
        
        $picture = $this->getPictureTable()->getPictureById($id);
        $img = ImageWorkshop::initFromPath(__PUBLIC__ . '/' . $picture->pathPicture);
        if($img->getWidth() > $img->getHeight()){
        	$isLandscape = true;
        }
        $category = $this->getPictureTable()->getCategoryFromPicture($id, $picture->category);
        $comments = $this->getCommentPictureTable()->getAllCommentsByPicture($id);
        $exifs = PictureUtils::printExifData(exif_read_data(__PUBLIC__ . '/' . $picture->pathPicture));
        $tags = $this->getTagTable()->getTagsFromPicture($id);
        
        $formEdit = new EditPictureForm();
        
        $formEdit->get('id')->setValue($id);
        $formEdit->get('title')->setValue($picture->title);
        $formEdit->get('description')->setValue($picture->description);
        
        $tagsTab = array();
        if($tags->count() !== 0){
            foreach($tags as $tag){
                array_push($tagsTab, $tag->wording);
            }
        }
        $tagValue = implode(' ', $tagsTab);
        $formEdit->get('tags')->setValue($tagValue);
        
        $categories = $this->getCategoryTable()->getAllCategories();
        $categoriesTab = array();
        foreach($categories as $cat): $categoriesTab[$cat->id] = $cat->wording; endforeach;
        $formEdit->get('category')->setValueOptions($categoriesTab);
        $formEdit->get('category')->setAttributes(array('value' => $picture->category, 'selected' => true));
       
               
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            
            if(isset($postData['save'])){
                $pictureEdit = new PictureEdit();
                $formEdit->setData($request->getPost());
                if($formEdit->isValid()){
                    $pictureEdit->exchangeArray($formEdit->getData());

                    $tagsPost = explode(' ', $pictureEdit->tags);
                                        
                    $newTagsTab = array();
                    
                    
                    $patternTitle = '/[-_#!?&$%:£=a-zA-Z0-9]+/';
                    $patternTag = '/[-_#a-zA-Z0-9]+/';
                    $patternDescription = '/[-_.#!)?*&($;%:£=a-zA-Z0-9]+/';
                    
                    foreach($tagsPost as $tagPost){
                        if(preg_match_all($patternTag, $tagPost)){
                            $isTagExist = $this->getTagTable()->getTagByName($tagPost)->current();
                            if($isTagExist === false){
                                $this->getTagTable()->insertTag($tagPost);
                                $idTag = $this->getTagTable()->getLastInsert();
                                $this->getTagPictureTable()->insertTagPicture($id, $idTag);
                            }else{
                                $idExistingTag = $this->getTagTable()->getTagByName($tagPost)->current()->id;
                                $isTagPictureExsits = $this->getTagPictureTable()->isTagPictureExists($id, $idExistingTag)->current();
                                if($isTagPictureExsits === false){
                                    $this->getTagPictureTable()->insertTagPicture($id, $idExistingTag);
                                }else{
                                    array_push($newTagsTab, $tagPost);
                                } 
                            }
                        }
                    }
                    
                    $deletedExistingTags = array_diff($tagsTab, $newTagsTab);
                    
                    if(count($deletedExistingTags) !== 0){
                        foreach($deletedExistingTags as $deletedExistingTag){
                            $idDeletedTag = $this->getTagTable()->getTagByName($deletedExistingTag)->current()->id;
                            $this->getTagPictureTable()->deleteTagPicture($id, $idDeletedTag);
                        }
                    }
                    $slug = str_replace(array(' ', '_', '\\', '/', '*', '"', '\'', '#', '$', '!', '.', '&', '£', '=', ':', '%', '?'), '-', strtolower($pictureEdit->title));
                    if(preg_match_all($patternTitle, $pictureEdit->title) && 
                        preg_match_all($patternDescription, $pictureEdit->description)){
                        $this->getPictureTable()->updatePicture($id, $pictureEdit->title, $pictureEdit->description, $slug, $pictureEdit->category);
                        return $this->redirect()->toRoute('picture', array('action'=>'index', 'id' => $picture->id, 'slug' => $slug));
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
            'picture' => $picture,
            'category' => $category->current(),
            'isLandscape' => $isLandscape,
            'comments' => $comments,
            'nbComments' => $comments->count(),
            'exifs' => $exifs,
            'tags' => $tags,
            'nbTags' => $tags->count(),
            'formEdit' => $formEdit,
            'formSearch' => $formSearch,
            'isFieldsValid' => $isFieldsValid
        );
    }
    
    public function deleteCommentAction(){
        $id = (int) $this->params()->fromRoute('id', null);
        $picture = (int) $this->params()->fromRoute('picture', null);

        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
        	return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
        
        if($user->isAdmin != 1){
        	return $this->redirect()->toRoute('flux', array('action' => 'index'));
        }
        
        $picture = $this->getPictureTable()->getPictureById($picture);
        
        $this->getCommentPictureTable()->removeComment($id);
        
        return $this->redirect()->toRoute('picture', array('action' => 'edit', 'id' => $picture->id, 'slug' => $picture->slug));
    }
    
    public function importAction() {
                   
    	$id = (int) $this->params()->fromRoute('id', null);
    
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
    	$form = new ImportForm('import-form');
    	
    	$invalidFile = false;

    	$albums =$this->getAlbumTable()->getAllAlbums();
    	$albumsTab = array();
    	foreach($albums as $album): $albumsTab[$album->id] = $album->name; endforeach;
    	
    	$form->get('select')->setValueOptions($albumsTab);
    	
    	if($id !== 0): $form->get('select')->setAttributes(array('value' => $id, 'selected' => true)); endif;
    	                        	
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	
    		$postData = $request->getPost();
    	
    		if(isset($postData['submit'])){
    		    
    		    $post = array_merge_recursive(
		    		$request->getPost()->toArray(),
		    		$request->getFiles()->toArray()
    		    );
    		    
    		    $album = $this->getAlbumTable()->getAlbumById($post['select']);
    		        		    
    		    if(!empty($album)){
    		        
    		        $idAlbum = (int) $album->id;
    		            		        
    		        if($idAlbum === 1){
    		            $destinationFolder = __PUBLIC__ . '/images/albums/' . $album->slug;
    		        }else{
    		            $destinationFolder = __PUBLIC__ . '/images/albums/' . $album->slug . '/pictures';
    		        }
    		        	
    		        $uploadFilter = $form->getInputFilter()->get('pictures');
    		        $filters = $uploadFilter->getFilterChain()->attachByName(
		        		'filerenameupload',
		        		array(
	        				'target'    => $destinationFolder . '/picture.jpg',
	        				'randomize' => true,
		        		)
    		        );
    		        
    		        $form->setData($post);
    		        if ($form->isValid()) {
    		        	$data = $form->getData();
    		        	
    		        	$pictures = array_diff(scandir($destinationFolder), array('..', '.', '.DS_Store'));
    		        	 
    		        	foreach($pictures as $k => $picture){
    		        		$basePicturePath = explode('/', $destinationFolder, 7)[6];
    		        		    		        		    		        		
    		        		$existingPath = $this->getPictureTable()->isPicturePathExists($basePicturePath . '/' . $pictures[$k])->current();
    		        		$existingPathThumb = $this->getPictureTable()->isPicturePathThumbExists($basePicturePath . '/' . $pictures[$k])->current();
    		        		    		        		   		        		    		        		
    		        		if($existingPath === false && 
    		        		    $existingPathThumb === false){
    		        		    
        		        		$pathPicture = $basePicturePath . '/' . $pictures[$k];
        		        		$pathThumbnail = $basePicturePath . '/' . 'thumb_' . $pictures[$k];
    		        		   
        		        		$pictureIW = ImageWorkshop::initFromPath(__PUBLIC__ . '/' . $pathPicture);
        		        		
        		        		$watermark = ImageWorkshop::initTextLayer('© Keepict', __PUBLIC__ . '/fonts/lato/lato-regular.ttf', 20, 'ffffff', 0);
        		        		$pictureIW->addLayerOnTop($watermark, 5, 5, "MB");
        		        		$pictureIW->save(__PUBLIC__ , '/' . $pathPicture, null, null, 100);
        		        		
    		        		    $thumb = ImageWorkshop::initFromPath(__PUBLIC__ . '/' . $pathPicture);
    		        		    if($thumb->getHeight() > $thumb->getWidth()){
    		        		    	$thumb->resizeInPixel(280, null, true);
    		        		    }else{
    		        		    	$thumb->resizeInPixel(null, 280, true);
    		        		    }
    		        		    $thumb->cropInPixel(280, 280, 0, 0, 'MM');
    		        		    $thumb->save(__PUBLIC__ , '/' . $pathThumbnail, null, null, 100);
    		        		     
    		        		    $this->getPictureTable()->insertPicture($pathThumbnail, $pathPicture);
    		        		     
    		        		    $idPicture = $this->getPictureTable()->getLastInsert();
    		        		     
    		        		    $this->getAlbumPictureTable()->insertPictureToAlbum($idAlbum, $idPicture);
    		        		}
    		        	}
    		        	if($idAlbum === 1): 
    		        	     return $this->redirect()->toRoute('flux'); 
    		        	else: 
    		        	     return $this->redirect()->toRoute('album', array('action' => 'record', 'id' => $idAlbum, 'slug' => $album->slug));
    		        	endif;
    		        	
    		        	
    		        }else{
    		        	$invalidFile = true;
    		        } 
    		    }else{
    		        $invalidFile = true;
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
			'formSearch' => $formSearch,
    	    'form' => $form,
    	    'invalidFile' => $invalidFile
    	);
    } 
}