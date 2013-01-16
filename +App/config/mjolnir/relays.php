<?php namespace mjolnir\theme;

	$theme =   [ 'theme' => '[a-zA-Z\-\._/]+' ];
	$style =   [ 'style' => '[a-zA-Z\-\._]+' ];
	$path =    [ 'path' => '.+' ]; # path is always validated internally
	$target =  [ 'target' => '[+a-zA-Z0-9\-\._/]+' ];
	$version = [ 'version' => '[0-9][a-z0-9-\.]*' ];

return array
	(

	// ---- Theme Drivers -----------------------------------------------------

	# javascripts

		'mjolnir:theme/themedriver/javascript.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>/<target>.js',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript',
			),

		'mjolnir:theme/themedriver/javascript-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>/<target>.min.js.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript-map',
			),

		'mjolnir:theme/themedriver/javascript-complete.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>-complete/master.js',
							$theme + $version
						),

			// Theme Driver
				'theme.driver' => 'javascript-complete',
			),

		'mjolnir:theme/themedriver/javascript-complete-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>-complete/master.js.map',
							$theme + $version
						),

			// Theme Driver
				'theme.driver' => 'javascript-complete-map',
			),



		'mjolnir:theme/themedriver/javascript-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/javascripts/<version>/src/<target>.js',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript-source',
			),

	# dart

		'mjolnir:theme/themedriver/dart.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<path>',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'dart',
			),

		'mjolnir:theme/themedriver/dart-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart-map',
			),

		'mjolnir:theme/themedriver/dart-javascript.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart.js',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart-javascript',
			),

		'mjolnir:theme/themedriver/dart-javascript-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<target>.dart.js.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'dart-javascript-map',
			),

	# css

		'mjolnir:theme/themedriver/style-css-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>.css/<version>/src/<target>.css',
							$theme + $style + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'style-css-source',
			),

		'mjolnir:theme/themedriver/style-css.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>.css/<version>/<target>.css',
							$theme + $style + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'style-css',
			),

		'mjolnir:theme/themedriver/style-css-complete.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>.css/<version>-complete/master.css',
							$theme + $style + $version
						),

			// Theme Driver
				'theme.driver' => 'style-css-complete',
			),
	
		'mjolnir:theme/themedriver/style-resource.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/<path>',
							$theme + $style + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'style-resource',
			),
	
	# misc
	
		'mjolnir:theme/themedriver/json-bootstrap.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/bootstrap.json',
							$theme
						),

			// Theme Driver
				'theme.driver' => 'json-bootstrap',
			),

	);
