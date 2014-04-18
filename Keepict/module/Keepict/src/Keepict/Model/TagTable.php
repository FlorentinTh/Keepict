<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class TagTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function getTagById($id) {
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
	
	public function getTagByName($tag) {
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'wording'))
		       ->from('tag')->where->like('wording', $tag);
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getTagsFromPicture($id){
		$id = (int) $id;
		 
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'wording'))
		->from('tag')
		->join('tagpicture', 'tagpicture.tag = tag.id', array())
		->join('picture', 'tagpicture.picture = picture.id', array())
		->where(array('picture.id' => $id));
		 
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function insertTag($wording) {
		$data = array(
			'wording' => $wording
		);
	
		$this->tableGateway->insert($data);
	}
	
	public function getLastInsert(){
		return $this->tableGateway->lastInsertValue;
	}
}