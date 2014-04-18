<?php

namespace Keepict\Model;

class TagPicture{

	public $tag;
	public $picture;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->tag = (isset($data['tag'])) ? $data['tag'] : null;
		$this->picture = (isset($data['picture'])) ? $data['picture'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}