<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Sql;

class PictureTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	protected $idFlux = 1;
	protected $idUncategorized = 1;
	
	public function getPicturesFromFlux($paginated=false){
	    if ($paginated) {
	    	$select = new Select();
            $select->columns(array('id', 'title', 'pathThumbnail', 'slug'))
    	           ->from('picture')
        	       ->join('albumpicture', 'picture.id = albumpicture.picture', array())
        	       ->join('album', 'albumpicture.album = album.id', array())
        	       ->where(array('album.id' => $this->idFlux));

	    	$resultSetPrototype = new ResultSet();
	    	$resultSetPrototype->setArrayObjectPrototype(new Picture());

	    	$paginatorAdapter = new DbSelect(
	            $select,
    			$this->tableGateway->getAdapter(),
    			$resultSetPrototype
	    	);
	    	$paginator = new Paginator($paginatorAdapter);
	    	return $paginator;
	    }
	    $resultSet = $this->tableGateway->select();
	    return $resultSet;
	}
	
	public function getAllPicturesFromAlbum($id, $paginated=false){
		$id = (int) $id;
		 
		if ($paginated) {
			$select = new Select();
			$select->columns(array('id', 'pathThumbnail', 'slug'))
    		       ->from('picture')
    		       ->join('albumpicture', 'picture.id = albumpicture.picture', array())
    		       ->join('album', 'albumpicture.album = album.id', array())
    		       ->where(array(
    			       'album.id' => $id,
                   ));
	
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Picture());
	
			$paginatorAdapter = new DbSelect(
					$select,
					$this->tableGateway->getAdapter(),
					$resultSetPrototype
			);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getPicturesByCategory($id, $paginated=false){
	    $id = (int) $id;
	    
		if ($paginated) {
			$select = new Select();
			$select->columns(array('id', 'title', 'pathThumbnail', 'slug'))
            	   ->from('picture')
            	   ->where(array(
        			     'category' => $id,
            	   ));
	
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Picture());
	
			$paginatorAdapter = new DbSelect(
					$select,
					$this->tableGateway->getAdapter(),
					$resultSetPrototype
			);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getPicturesByTag($id, $paginated=false){
		$id = (int) $id;
		 
		if ($paginated) {
			$select = new Select();
			$select->columns(array('id', 'title', 'pathThumbnail', 'slug'))
    			   ->from('picture')
    			   ->join('tagpicture', 'picture.id = tagpicture.picture', array())
    			   ->join('tag', 'tag.id = tagpicture.tag', array())
    			   ->where(array(
    					'tag.id' => $id,
    			   ));
	
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Picture());
	
			$paginatorAdapter = new DbSelect(
					$select,
					$this->tableGateway->getAdapter(),
					$resultSetPrototype
			);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getPictureById($id) {
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array(
			'id' => $id
		));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	
	public function searchPicture($predicate){

		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'title', 'pathThumbnail', 'slug'))
    		   ->from('picture')
    		   ->where->like('title', '%' . $predicate . '%')
    		   ->OR->like('description', '%' . $predicate . '%');

		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getCategoryFromPicture($id, $category){
		$category = (int) $category;
	    $id = (int) $id; 
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'wording'))
		->from('category')
		->join('picture', 'category.id = picture.category', array())
		->where(array(
			'category.id' => $category,
		    'picture.id' => $id
		));
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function isPicturePathExists($pathPicture) {
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('pathPicture'))
		       ->from('picture')->where->like('pathPicture', $pathPicture);
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function isPicturePathThumbExists($pathThumbnail) {
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('pathThumbnail'))
		->from('picture')->where->like('pathThumbnail', $pathThumbnail);
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function insertPicture($pathThumbnail, $pathPicture) {
		$data = array(
			'title' => 'Untitled',
	        'description' => null,
		    'pathThumbnail' => $pathThumbnail,
		    'pathPicture' => $pathPicture,
		    'slug' => null,
		    'category' => $this->idUncategorized
		);
	
		$this->tableGateway->insert($data);
	}
	
	public function updatePicture($picture, $title, $description, $slug, $category) {
	    $picture = (int) $picture;
	    $category = (int) $category;
	    
		$data = array(
			'title' => $title,
			'description' => $description,
			'slug' => $slug,
			'category' => $category
		);
	
		$this->tableGateway->update($data, array('id' => $picture));
	}
	
	public function getPicturePathFromAlbum(Album $album) {
	    $id = (int) $album->id;
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'pathThumbnail', 'pathPicture'))
		       ->from('picture')
		       ->join('albumpicture', 'picture.id = albumpicture.picture', array())
		       ->join('album', 'albumpicture.album = album.id', array())
    		   ->where(array(
    				'album.id' => $id,
    		   ));
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function updatePicturePath($id, $pathThumbnail, $pathPicture) {
		$id = (int) $id;
	 
		$data = array(
			'pathThumbnail' => $pathThumbnail,
			'pathPicture' => $pathPicture
		);
	
		$this->tableGateway->update($data, array('id' => $id));
	}
	
	public function getLastInsert(){
	    return $this->tableGateway->lastInsertValue;
	}
}