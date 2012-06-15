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
		__NAMESPACE__.'\Layer_Theme::script' => array
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
	
		__NAMESPACE__.'\Layer_Theme::style' => array
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
	
		__NAMESPACE__.'\Layer_Theme::resource' => array
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