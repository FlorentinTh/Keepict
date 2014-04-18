<?php

namespace Keepict\Model;

class Search{

	public $predicate;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->predicate = (isset($data['predicate'])) ? $data['predicate'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}