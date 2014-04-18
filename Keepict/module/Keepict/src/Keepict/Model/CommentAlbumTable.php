<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class CommentAlbumTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function postComment(CommentAlbum $comment, User $user, Album $album) {
		$data = array(
			'member' => $user->id,
		    'album' => $album->id,
			'body' => $comment->body,
		);
	
		$this->tableGateway->insert($data);
	}
	
	public function removeComment($id) {
		$id = (int) $id;
		$this->tableGateway->delete(array('id' => $id));
	}
	
	public function getCommentsByAlbum($id){
	    $id = (int) $id;
	    
	    $adapter = $this->tableGateway->getAdapter();
	    $sql = new Sql($adapter);
	    $select = $sql->Select();
	    $select->columns(array('id', 'body'))
        	   ->from('commentalbum')
        	   ->join('album', 'commentalbum.album = album.id', array())
        	   ->join('user', 'commentalbum.member = user.id', array('uid' => 'id', 'firstname', 'lastname', 'avatar'))
        	   ->where(array('album.id' => $id));
	    
	    	
	    $statement = $adapter->createStatement();
	    $select->prepareStatement($adapter, $statement);
	    $resultSet = new ResultSet();
	    $resultSet->initialize($statement->execute());
	    $resultSet->buffer();
	    return $resultSet;
	}
}