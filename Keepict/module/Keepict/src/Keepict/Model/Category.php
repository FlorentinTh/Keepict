<?php

namespace Keepict\Model;

class Category{

	public $id;
	public $wording;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->wording = (isset($data['wording'])) ? $data['wording'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}