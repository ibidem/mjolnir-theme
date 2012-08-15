<?php namespace ibidem\theme;
	
$theme_resource_regex = array
	(
		'theme' => '[a-zA-Z0-9\-\._/]+',
		'style' => '[a-zA-Z0-9\-\._]+',
		'version' => '[a-f0-9-\.]*',
		'path' => '.+', # path is validated internally
		'target' => '[a-zA-Z0-9\-\._/]+',
	);

return array      
	(
		'\ibidem\theme\mockup' => array
			(
				'route' => \app\Route_Pattern::instance()
					->standard
						(
							'mockup/<target>', 
							array
							(
								'target' => '[a-zA-Z-/]+',
							)
						),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_testing',
			),
	
		'\ibidem\theme\mockup-form' => array
			(
				'route' => \app\Route_Path::instance()
					->path('form-mockup'),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_form',
			),
	
		'\ibidem\theme\Layer_Theme::script' => array
			(
				'route' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/<target>.js', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/<target>.js', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'script',
			),
	
		'\ibidem\theme\Layer_Theme::script-map' => array
			(
				'route' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/<target>.min.js.map', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/<target>.min.js.map', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'script-map',
			),
	
		'\ibidem\theme\Layer_Theme::script-src' => array
			(
				'route' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/src/<target>.js', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/src/<target>.js', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'script-src',
			),
	
		'\ibidem\theme\Layer_Theme::style' => array
			(
				'route' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/<target>.css', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/<target>.css', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'style',
			),
	
		'\ibidem\theme\Layer_Theme::resource' => array
			(
				'route' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/<path>', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/<path>', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'resource',
			),

	);