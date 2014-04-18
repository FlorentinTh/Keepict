<?php

namespace Keepict\Model;

class Picture{

	public $id;
	public $title;
	public $description;
	public $pathThumbnail;
	public $pathPicture;
	public $slug;
	public $category;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->title = (isset($data['title'])) ? $data['title'] : null;
		$this->description = (isset($data['description'])) ? $data['description'] : null;
		$this->pathThumbnail = (isset($data['pathThumbnail'])) ? $data['pathThumbnail'] : null;
		$this->pathPicture = (isset($data['pathPicture'])) ? $data['pathPicture'] : null;
		$this->slug = (isset($data['slug'])) ? $data['slug'] : null;
		$this->category = (isset($data['category'])) ? $data['category'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}