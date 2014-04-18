<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Sql;

class AlbumTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	protected $idFlux = 1;
	protected $idPublished = 1;
	
	public function getAlbumsPublished($paginated=false){
		if ($paginated) {
			$select = new Select();
			$select->columns(array('id', 'name', 'pathCoverThumbnail', 'slug'))
        		   ->from('album')
			       ->where(array(
			           'album.id <> ' . $this->idFlux,
			           'album.visibility' => $this->idPublished,
			       ));
	
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Album());
	   			
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
	
	public function getAlbumById($id) {
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
	
	public function getAllAlbums() {
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'name'))
		       ->from('album');
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getAlbumByVisibility($visibility) {
	    $visibility = (int) $visibility;
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'name', 'slug', 'pathCoverThumbnail'))
		       ->from('album')->where(array(
		           'visibility' => $visibility,
		           'id <>' . $this->idFlux
		       ));
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getAlbumByName($name) {
		$adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->Select();
        $select->columns(array('name'))
    	       ->from('album')->where->like('name', $name);
        
	    $statement = $adapter->createStatement();
	    $select->prepareStatement($adapter, $statement);
	    $resultSet = new ResultSet();
	    $resultSet->initialize($statement->execute());
	    $resultSet->buffer();
	    return $resultSet;
	}
	
	public function getFirstPicturesFromAlbum($id){
        $id = (int) $id;
        
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->Select();
        $select->columns(array('id', 'pathThumbnail', 'slug'))
    	       ->from('picture')
    	       ->join('albumpicture', 'picture.id = albumpicture.picture', array())
    	       ->join('album', 'albumpicture.album = album.id', array())
    	       ->where(array('album.id' => $id))
               ->limit(4);
        
	    $statement = $adapter->createStatement();
	    $select->prepareStatement($adapter, $statement);
	    $resultSet = new ResultSet();
	    $resultSet->initialize($statement->execute());
	    $resultSet->buffer();
	    return $resultSet;
	}
	
	public function createAlbum(Album $album) {
		$data = array(
			'name' => $album->name,
			'pathCoverThumbnail' => $album->pathCoverThumbnail,
			'pathCover' => $album->pathCover,
		    'description' => $album->description,
		    'slug' => $album->slug,
		    'visibility' => 2
		);
		
		$this->tableGateway->insert($data);
	}
	
	public function setAlbumUnpublished(Album $album){
	   $id = (int) $album->id;	    
	    $data = array(
    		'visibility' => 2,
	    );
	    
	    $this->tableGateway->update($data, array('id' => $id));
	}
	
	public function setAlbumPublished(Album $album){
		$id = (int) $album->id;
		$data = array(
				'visibility' => 1,
		);
		 
		$this->tableGateway->update($data, array('id' => $id));
	}
	
	public function updateAlbum($album, $name, $description, $pathCoverThumbnail, $pathCover, $slug) {

		$data = array(
			'name' => $name,
			'description' => $description,
		    'pathCoverThumbnail' => $pathCoverThumbnail,
		    'pathCover' => $pathCover,
			'slug' => $slug,
		);
	
		$this->tableGateway->update($data, array('id' => $album));
	}
}