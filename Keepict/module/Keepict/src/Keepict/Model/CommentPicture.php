<?php

namespace Keepict\Model;

class CommentPicture{

	public $id;
	public $picture;
	public $member;
	public $body;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->picture = (isset($data['picture'])) ? $data['picture'] : null;
		$this->member = (isset($data['member'])) ? $data['member'] : null;
		$this->body = (isset($data['body'])) ? $data['body'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}