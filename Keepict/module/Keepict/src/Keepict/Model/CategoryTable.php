<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class CategoryTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function getCategoryById($id) {
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
	
	public function createCategory(Category $category) {
		$data = array(
			'wording' => $category->wording,
		);
	
		$this->tableGateway->insert($data);
	}
	
	public function getCategoryByName(Category $category) {
		$adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->Select();
        $select->columns(array('wording'))
    	       ->from('category')->where->like('wording', $category->wording);
        
	    $statement = $adapter->createStatement();
	    $select->prepareStatement($adapter, $statement);
	    $resultSet = new ResultSet();
	    $resultSet->initialize($statement->execute());
	    $resultSet->buffer();
	    return $resultSet;
	}
	
	public function getAllCategories() {
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'wording'))
		       ->from('category');
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function deleteCategory(Category $category){
		$id = (int) $category->id;
		$this->tableGateway->delete(array('id' => $id));
	}
}