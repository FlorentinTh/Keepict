<?php

namespace Keepict\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Classes\SessionUtils;
use Keepict\Form\SearchForm;
use Keepict\Model\Search;

require_once(__DIR__ . '/../Classes/SessionUtils.php');

class SearchController extends AbstractActionController {
    
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
        
        $formSearch = new SearchForm();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$search = new Search();
        	$formSearch->setData($request->getPost());
        
        	if ($formSearch->isValid()) {
        		$search->exchangeArray($formSearch->getData());
        
        		$results = $this->getPictureTable()->searchPicture($search->predicate);
        	}
        }
        
        $results = $this->params()->fromRoute('results');
        $predicate = $this->params()->fromRoute('predicate');
        
        return array(
            'user' => $user,
            'numPendingUser' => $numPendingUser,
            'results' => $results,
            'nbResults' => $results->count(),
            'predicate' => $predicate,
            'formSearch' => $formSearch
        );
    }
    
}