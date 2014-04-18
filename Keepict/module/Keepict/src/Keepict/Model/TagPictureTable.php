<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class TagPictureTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function insertTagPicture($picture, $tag) {
	    $picture = (int) $picture;
	    $tag = (int) $tag;
	    
		$data = array(
			'tag' => $tag,
		    'picture' => $picture  
		);
	
		$this->tableGateway->insert($data);
	}
	
	public function deleteTagPicture($picture, $tag) {
		$picture = (int) $picture;
	    $tag = (int) $tag;
	    
		$data = array(
			'tag' => $tag,
		    'picture' => $picture  
		);
	
		$this->tableGateway->delete($data);
	}
	
	public function isTagPictureExists($picture, $tag) {
	    $picture = (int) $picture;
	    $tag = (int) $tag;
	    
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('picture', 'tag'))
		       ->from('tagpicture')->where->like('picture', $picture)->and->like('tag', $tag);
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
}