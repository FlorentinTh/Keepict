<?php

namespace Keepict\Model;

use Zend\Db\TableGateway\TableGateway;

class AlbumPictureTable {
	
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	public function insertPictureToAlbum($idAlbum, $idPicture) {
	    $idAlbum = (int) $idAlbum;
	    $idPicture = (int) $idPicture;
		$data = array(
			'album' => $idAlbum,
			'picture' => $idPicture
		);
	
		$this->tableGateway->insert($data);
	}
}