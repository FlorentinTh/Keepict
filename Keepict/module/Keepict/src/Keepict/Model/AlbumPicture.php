<?php

namespace Keepict\Model;

class AlbumPicture{

	public $album;
	public $picture;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->album = (isset($data['album'])) ? $data['album'] : null;
		$this->picture = (isset($data['picture'])) ? $data['picture'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}