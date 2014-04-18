<?php

namespace Keepict\Model;

class PictureEdit{

	public $id;
	public $title;
	public $description;
	public $tags;
	public $slug;
	public $category;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->title = (isset($data['title'])) ? $data['title'] : null;
		$this->description = (isset($data['description'])) ? $data['description'] : null;
		$this->tags = (isset($data['tags'])) ? $data['tags'] : null;
		$this->slug = (isset($data['slug'])) ? $data['slug'] : null;
		$this->category = (isset($data['category'])) ? $data['category'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}