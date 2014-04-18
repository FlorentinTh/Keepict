<?php

namespace Keepict\Model;

class CommentAlbum{

	public $id;
	public $album;
	public $member;
	public $body;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->album = (isset($data['album'])) ? $data['album'] : null;
		$this->member = (isset($data['member'])) ? $data['member'] : null;
		$this->body = (isset($data['body'])) ? $data['body'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}