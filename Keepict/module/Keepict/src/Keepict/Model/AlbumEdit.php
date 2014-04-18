<?php

namespace Keepict\Model;

class AlbumEdit{

	public $id;
	public $name;
	public $description;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->description = (isset($data['description'])) ? $data['description'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}