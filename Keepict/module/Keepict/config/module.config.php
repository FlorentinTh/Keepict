<?php
return array(
	'controllers' => array(
		'invokables' => array(
		    'Keepict\Controller\flux' => 'Keepict\Controller\FluxController',
		    'Keepict\Controller\account' => 'Keepict\Controller\AccountController',
		    'Keepict\Controller\dashboard' => 'Keepict\Controller\DashboardController',
		    'Keepict\Controller\album' => 'Keepict\Controller\AlbumController',
		    'Keepict\Controller\picture' => 'Keepict\Controller\PictureController',
		    'Keepict\Controller\search' => 'Keepict\Controller\SearchController',
		)
	),
	'router' => array(
		'routes' => array(
		    'home' => array(
	    		'type' => 'Zend\Mvc\Router\Http\Literal',
	    		'options' => array(
    				'route'    => '/',
    				'defaults' => array(
    						'controller' => 'Keepict\Controller\flux',
    						'action'     => 'index',
    				),
	    		),
		    ),
		    'keepict' => array(
	    		'type'    => 'Literal',
	    		'options' => array(
    				'route'    => '/keepict',
    				'defaults' => array(
						'__NAMESPACE__' => 'Keepict\Controller',
						'controller'    => 'Account',
						'action'        => 'index',
    				),
	    		),
	    		'may_terminate' => true,
	    		'child_routes' => array(
    				'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action]]',
							'constraints' => array(
									'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
									'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(),
						),
    				),
	    		),
		    ),
		    'flux' => array (
	    		'type' => 'segment',
	    		'options' => array (
    				'route' => '/flux[/page/:page][/:action][/:id]',
    				'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
    				    'page' => '[0-9]+',
    				    'id' => '[0-9]+'
    				),
    				'defaults' => array (
						'controller' => 'Keepict\Controller\flux',
						'action' => 'index',
    				    'page' => 1,
    				)
	    		)
		    ),
		    'account' => array (
	    		'type' => 'segment',
	    		'options' => array (
    				'route' => '/account[/:action][/:id]',
    				'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
						'id' => '[0-9]+'
    				),
    				'defaults' => array (
						'controller' => 'Keepict\Controller\account',
						'action' => 'signup'
    				)
	    		)
		    ),
		    'dashboard' => array (
	    		'type' => 'segment',
	    		'options' => array (
    				'route' => '/dashboard[/:action][/:id]',
    				'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
						'id' => '[0-9]+'
    				),
    				'defaults' => array (
						'controller' => 'Keepict\Controller\dashboard',
						'action' => 'members'
    				)
	    		)
		    ),
		    'album' => array (
	    		'type' => 'segment',
	    		'options' => array (
    				'route' => '/album[/page/:page][/:action][/:id][/:slug][/:album]',
    				'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
    				    'page' => '[0-9]+',
    				    'id' => '[0-9]+',
    				    'album' => '[0-9]+',
    				    'slug' => '[a-zA-Z][a-zA-Z0-9_-]+',
    				),
    				'defaults' => array (
						'controller' => 'Keepict\Controller\album',
						'action' => 'index',
    				    'page' => 1
    				)
	    		)
		    ),
		    'picture' => array (
	    		'type' => 'segment',
	    		'options' => array (
    				'route' => '/picture[/page/:page][/:action][/:id][/:slug][/:picture]',
    				'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
    				    'page' => '[0-9]+',
    				    'id' => '[0-9]+',
    				    'picture' => '[0-9]+',
    				    'slug' => '[a-zA-Z][a-zA-Z0-9_-]+',
    				),
    				'defaults' => array (
						'controller' => 'Keepict\Controller\picture',
						'action' => 'index',
    				    'page' => 1
    				)
	    		)
		    ),
		    'search' => array (
	    		'type' => 'segment',
	    		'options' => array (
    				'route' => '/search[/:action][/page/:page]',
    				'constraints' => array (
						'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
						'page' => '[0-9]+',
    				),
    				'defaults' => array (
						'controller' => 'Keepict\Controller\search',
						'action' => 'index',
						'page' => 1
    				)
	    		)
		    ),
		)
	),
    'service_manager' => array(
    		'factories' => array(
    				'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
    		),
    ),
    'translator' => array(
    		'locale' => 'en_US',
    		'translation_file_patterns' => array(
    				array(
    						'type'     => 'gettext',
    						'base_dir' => __DIR__ . '/../language',
    						'pattern'  => '%s.mo',
    				),
    		),
    ),
	'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/keepict/account/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
		'template_map' => array (
			'pagination' => __DIR__ . '/../view/pagination/pagination.phtml',
		    'pagination-param' => __DIR__ . '/../view/pagination/pagination-param.phtml',
		)
    ),
);
