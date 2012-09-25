<?php namespace mjolnir\theme;
	
$theme_resource_regex = array
	(
		'theme' => '[a-zA-Z0-9\-\._/]+',
		'style' => '[a-zA-Z0-9\-\._]+',
		'version' => '[a-f0-9-\.]*',
		'path' => '.+', # path is validated internally
		'target' => '[+a-zA-Z0-9\-\._/]+',
	);

return array      
	(
		'\mjolnir\theme\mockup' => array
			(
				'matcher' => \app\Route_Pattern::instance()
					->standard
						(
							'mockup/<target>', 
							array
							(
								'target' => '[a-zA-Z0-9\-\._/]+',
							)
						),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_testing',
			),
	
		'\mjolnir\theme\mockup-errors' => array
			(
				'matcher' => \app\Route_Pattern::instance()
					->standard
						(
							'mockup-errors/<target>', 
							array
							(
								'target' => '[a-zA-Z0-9\-\._/]+',
							)
						),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_errortesting',
			),
	
		'\mjolnir\theme\mockup-form' => array
			(
				'matcher' => \app\Route_Path::instance()
					->path('form-mockup'),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_form',
			),
	
		'\mjolnir\theme\Layer_Theme::script' => array
			(
				'matcher' => \app\Route_Pattern::instance()
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
	
		'\mjolnir\theme\Layer_Theme::script-map' => array
			(
				'matcher' => \app\Route_Pattern::instance()
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
	
		'\mjolnir\theme\Layer_Theme::script-src' => array
			(
				'matcher' => \app\Route_Pattern::instance()
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
	
		'\mjolnir\theme\Layer_Theme::style-src' => array
			(
				'matcher' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/src/<target>.css', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/src/<target>.css', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'style-src',
			),
	
		'\mjolnir\theme\Layer_Theme::style' => array
			(
				'matcher' => \app\Route_Pattern::instance()
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
	
		'\mjolnir\theme\Layer_Theme::resource' => array
			(
				'matcher' => \app\Route_Pattern::instance()
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
	
		'\mjolnir\theme\Layer_Theme::jsbootstrap' => array
			(
				'matcher' => \app\Route_Pattern::instance()
					->standard
						(
							'media/themes/<theme>/<style>/<version>/js-bootstrap.js', 
							$theme_resource_regex
						)
					->canonical
						(
							'media/themes/<theme>/<style>/<version>/js-bootstrap.js', 
							$theme_resource_regex
						),
				'enabled' => true,
				'mode' => 'js-bootstrap',
			),

	);