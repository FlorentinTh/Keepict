<?php

namespace Keepict\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Classes\SessionUtils;
use Keepict\Form\SearchForm;
use Keepict\Model\Search;

require_once(__DIR__ . '/../Classes/SessionUtils.php');

class FluxController extends AbstractActionController {
    
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

    public function indexAction() {
        
        $logged = SessionUtils::checkUserAllowed();
        if($logged === null){
            return $this->redirect()->toRoute('account', array('action' => 'signup'));
        }
        
        $user = $this->getUserTable()->getUserByCourriel($logged);
                
        $numPendingUser = $this->getUserTable()->countUserRequest()->current()->num;
        
        $paginator = $this->getPictureTable()->getPicturesFromFlux(true);
        $currentPage = (int) $this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(12);
        
        
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
        
        return array(
            'user' => $user,
            'numPendingUser' => $numPendingUser,
            'pictures' => $paginator,
            'currentPage' => $currentPage,
            'formSearch' => $formSearch
        );
    }
    
}