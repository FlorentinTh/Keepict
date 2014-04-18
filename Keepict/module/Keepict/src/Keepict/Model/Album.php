<?php

namespace Keepict\Model;

class Album{

	public $id;
	public $name;
	public $pathCoverThumbnail;
	public $pathCover;
	public $description;
	public $slug;
	public $visibility;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->name = (isset($data['name'])) ? $data['name'] : null;
		$this->pathCoverThumbnail = (isset($data['pathCoverThumbnail'])) ? $data['pathCoverThumbnail'] : null;
		$this->pathCover = (isset($data['pathCover'])) ? $data['pathCover'] : null;
		$this->description = (isset($data['description'])) ? $data['description'] : null;
		$this->slug = (isset($data['slug'])) ? $data['slug'] : null;
		$this->visibility = (isset($data['visibility'])) ? $data['visibility'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}