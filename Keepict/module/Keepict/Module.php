<?php
namespace Keepict;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Keepict\Model\User;
use Keepict\Model\UserTable;
use Keepict\Model\AlbumTable;
use Keepict\Model\Album;
use Keepict\Model\PictureTable;
use Keepict\Model\Picture;
use Keepict\Model\AlbumPictureTable;
use Keepict\Model\AlbumPicture;
use Keepict\Model\TagTable;
use Keepict\Model\Tag;
use Keepict\Model\CategoryTable;
use Keepict\Model\Category;
use Keepict\Model\CommentAlbum;
use Keepict\Model\CommentAlbumTable;
use Keepict\Model\CommentPictureTable;
use Keepict\Model\CommentPicture;
use Keepict\Model\TagPictureTable;
use Keepict\Model\TagPicture;

class Module implements AutoloaderProviderInterface {

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()
            ->getServiceManager()
            ->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getServiceConfig() {
        return array (
			'factories' => array (
			    //Table : User
			    'Keepict\Model\UserTable' => function ($sm) {
			    	$tableGateway = $sm->get('UserTableGateway');
			    	$table = new UserTable($tableGateway);
			    	return $table;
			    },
			    'UserTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new User());
			    	return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : Album
			    'Keepict\Model\AlbumTable' => function ($sm) {
			    	$tableGateway = $sm->get('AlbumTableGateway');
			    	$table = new AlbumTable($tableGateway);
			    	return $table;
			    },
			    'AlbumTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new Album());
			    	return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : Picture
			    'Keepict\Model\PictureTable' => function ($sm) {
			    	$tableGateway = $sm->get('PictureTableGateway');
			    	$table = new PictureTable($tableGateway);
			    	return $table;
			    },
			    'PictureTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new Picture());
			    	return new TableGateway('picture', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : AlbumPicture
			    'Keepict\Model\AlbumPictureTable' => function ($sm) {
			    	$tableGateway = $sm->get('AlbumPictureTableGateway');
			    	$table = new AlbumPictureTable($tableGateway);
			    	return $table;
			    },
			    'AlbumPictureTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new AlbumPicture());
			    	return new TableGateway('albumpicture', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : Tag
			    'Keepict\Model\TagTable' => function ($sm) {
			    	$tableGateway = $sm->get('TagTableGateway');
			    	$table = new TagTable($tableGateway);
			    	return $table;
			    },
			    'TagTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new Tag());
			    	return new TableGateway('tag', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : Category
			    'Keepict\Model\CategoryTable' => function ($sm) {
			    	$tableGateway = $sm->get('CategoryTableGateway');
			    	$table = new CategoryTable($tableGateway);
			    	return $table;
			    },
			    'CategoryTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new Category());
			    	return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : CommentAlbum
			    'Keepict\Model\CommentAlbumTable' => function ($sm) {
			    	$tableGateway = $sm->get('CommentAlbumTableGateway');
			    	$table = new CommentAlbumTable($tableGateway);
			    	return $table;
			    },
			    'CommentAlbumTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new CommentAlbum());
			    	return new TableGateway('commentalbum', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : CommentPicture
			    'Keepict\Model\CommentPictureTable' => function ($sm) {
			    	$tableGateway = $sm->get('CommentPictureTableGateway');
			    	$table = new CommentPictureTable($tableGateway);
			    	return $table;
			    },
			    'CommentPictureTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new CommentPicture());
			    	return new TableGateway('commentpicture', $dbAdapter, null, $resultSetPrototype);
			    },
			    //Table : TagPicture
			    'Keepict\Model\TagPictureTable' => function ($sm) {
			    	$tableGateway = $sm->get('TagPictureTableGateway');
			    	$table = new TagPictureTable($tableGateway);
			    	return $table;
			    },
			    'TagPictureTableGateway' => function ($sm) {
			    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
			    	$resultSetPrototype = new ResultSet();
			    	$resultSetPrototype->setArrayObjectPrototype(new TagPicture());
			    	return new TableGateway('tagpicture', $dbAdapter, null, $resultSetPrototype);
			    },
            )
    	);
    }
}