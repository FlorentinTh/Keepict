<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class CommentPictureTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function postComment(CommentPicture $comment, User $user, Picture $picture) {
		$data = array(
			'member' => $user->id,
		    'picture' => $picture->id,
			'body' => $comment->body,
		);
	
		$this->tableGateway->insert($data);
	}
	
	public function removeComment($id) {
	    $id = (int) $id;
		$this->tableGateway->delete(array('id' => $id));
	}
	
	public function getAllCommentsByPicture($id){
		$id = (int) $id;
		
		$adapter = $this->tableGateway->getAdapter();
		$sql = new Sql($adapter);
		$select = $sql->Select();
		$select->columns(array('id', 'body'))
    		   ->from('commentpicture')
        	   ->join('picture', 'commentpicture.picture = picture.id', array())
        	   ->join('user', 'commentpicture.member = user.id', array('uid' => 'id', 'firstname', 'lastname', 'avatar'))
        	   ->where(array('picture.id' => $id));
	
		$statement = $adapter->createStatement();
		$select->prepareStatement($adapter, $statement);
		$resultSet = new ResultSet();
		$resultSet->initialize($statement->execute());
		$resultSet->buffer();
		return $resultSet;
	}
}