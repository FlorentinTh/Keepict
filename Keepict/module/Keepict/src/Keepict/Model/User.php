<?php

namespace Keepict\Model;

class User{

	public $id;
	public $avatar;
	public $firstname;
	public $lastname;
	public $courriel;
	public $birth;
	public $password;
	public $isAdmin;
	public $state;
	public $dateRequest;
	public $dateAdded;
	public $dateBlocked;

	protected $inputFilter;

	public function exchangeArray($data){
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->avatar = (isset($data['avatar'])) ? $data['avatar'] : null;
		$this->firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
		$this->lastname = (isset($data['lastname'])) ? $data['lastname'] : null;
		$this->courriel = (isset($data['courriel'])) ? $data['courriel'] : null;
		$this->birth = (isset($data['birth'])) ? $data['birth'] : null;
		$this->password = (isset($data['password'])) ? $data['password'] : null;
		$this->isAdmin = (isset($data['isAdmin'])) ? $data['isAdmin'] : null;
		$this->state = (isset($data['state'])) ? $data['state'] : null;
		$this->dateRequest = (isset($data['dateRequest'])) ? $data['dateRequest'] : null;
		$this->dateAdded = (isset($data['dateAdded'])) ? $data['dateAdded'] : null;
		$this->dateBlocked = (isset($data['dateBlocked'])) ? $data['dateBlocked'] : null;
	}

	public function getArrayCopy(){
		return get_object_vars($this);
	}
}