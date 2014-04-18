<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class UserTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	private function getRandomBeardAvatar(){
	    $beardAvatar = array(
	        'black184', 
	        'black185', 
	        'bonnet',
	        'buckle',
	        'buckled',
	        'buckled2',
	        'buckled3',
	        'buckled4',
	        'charlie',
	        'china1',
	        'china2',
	        'chinese6',
	        'chinese7',
	        'chinese8',
	        'chinese9',
	        'circle32',
	        'circular54',
	        'cowbot',
	        'cowboy2',
	        'cowboy3',
	        'cowboy4',
	        'fedora1',
	        'fedora2',
	        'fedora3',
	        'fedora4',
	        'hat4',
	        'magic6',
	        'magician2',
	        'magicians',
	        'male81',
	        'mexican4',
	        'moustache1',
	        'round34',
	        'round37',
	        'round38',
	        'round39',
	        'studded1',
	        'tall10',
	        'tall3',
	        'tall5',
	        'tall7',
	        'tall9'
	    );
	    
	    if (count($beardAvatar) === 0){
	    	trigger_error('Array is empty.',  E_USER_WARNING);
	    	return null;
	    }
	    
	    $rand = mt_rand(0, count($beardAvatar) - 1);
	    $array_keys = array_keys($beardAvatar);
	     
	    return 'beardfont-' . $beardAvatar[$array_keys[$rand]];
	}
	
	public function createAccount(User $user) {
		$data = array(
		    'avatar' => $this->getRandomBeardAvatar(),
		    'firstname' => $user->firstname,
		    'lastname' => $user->lastname,
		    'courriel' => $user->courriel,
		    'birth' => $user->birth,
		    'password'  => sha1($user->password),
		    'isAdmin' => 0,
		    'state' => 2,
		    'dateRequest' => date('Y-m-d'),
		    'dateAdded' => null,
		    'dateBlocked' => null
		);
		
		$this->tableGateway->insert($data);
	}
	
	public function updateAccount(User $user) {
		$data = array(
			'firstname' => $user->firstname,
			'lastname' => $user->lastname,
			'courriel' => $user->courriel,
			'birth' => $user->birth
		);
		
		$this->tableGateway->update($data, array('id' => $user->id));
	}
	
	public function updateUserAvatar(User $user){
	    $data = array(
	    		'avatar' => $user->avatar
	    );
	    
	    $this->tableGateway->update($data, array('id' => $user->id));
	}
	
	public function getUserByCourriel($courriel) {
		$rowset = $this->tableGateway->select(array(
	    		'courriel' => $courriel
	    ));
	    $row = $rowset->current();

	    return $row;
	}
	
	public function getUserById($id) {
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
	
	public function countUserRequest() {
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->Select();
		$select->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')))
		       ->from('user')
		       ->where(array('state' => 2));
				
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function countUserBlacklist() {
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')))
		->from('user')
		->where(array('state' => 3));
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
	
	public function getEnabledUsers() {
		$resultSet = $this->tableGateway->select($where = 'state = 1 and isAdmin != 1');
		return $resultSet;
	}
	
	public function moveToBlacklist(User $user) {
		$data = array(
			'state' => 3,
			'dateBlocked' => date('Y-m-d')
		);
	
		$this->tableGateway->update($data, array('id' => $user->id));
	}
	
	public function getPendingUsers() {
		$resultSet = $this->tableGateway->select($where = 'state = 2 and isAdmin != 1');
		return $resultSet;
	}
	
	public function acceptRequest(User $user) {
		$data = array(
			'state' => 1,
			'dateAdded' => date('Y-m-d')
		);
	
		$this->tableGateway->update($data, array('id' => $user->id));
	}
	
	public function declineRequest(User $user) {
		$data = array(
			'state' => 3,
			'dateBlocked' => date('Y-m-d')
		);
	
		$this->tableGateway->update($data, array('id' => $user->id));
	}
		
	public function getDisabledUsers() {
		$resultSet = $this->tableGateway->select($where = 'state = 3 and isAdmin != 1');
		return $resultSet;
	}
	
	public function removeFromBlacklist(User $user) {
		$data = array(
			'state' => 1,
		    'dateAdded' => date('Y-m-d')
		);
	
		$this->tableGateway->update($data, array('id' => $user->id));
	}
}