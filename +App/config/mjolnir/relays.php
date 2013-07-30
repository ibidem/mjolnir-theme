<?php namespace mjolnir\theme;

	$theme =   [ 'theme' => '[a-zA-Z\-\._/]+' ];
	$style =   [ 'style' => '[a-zA-Z\-\._]+' ];
	$path =    [ 'path' => '.+' ]; # path is always validated internally
	$target =  [ 'target' => '[+a-zA-Z0-9\-\._]+' ];
	$version = [ 'version' => '[0-9\.]+' ];

return array
	(

	// ---- Theme Drivers -----------------------------------------------------

	# dart

		'mjolnir:theme/themedriver/dart.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<path>.dart',
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
							'media/themes/<theme>/darts/<version>/<path>.dart.map',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'dart-map',
			),

		'mjolnir:theme/themedriver/dart-javascript.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<path>.dart.js',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'dart-javascript',
			),

		'mjolnir:theme/themedriver/dart-javascript-map.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<path>.dart.js.map',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'dart-javascript-map',
			),

		'mjolnir:theme/themedriver/dart-resource.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/darts/<version>/<path>',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'dart-resource',
			),

	# javascripts

		'mjolnir:theme/themedriver/javascript.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/scripts/<version>/<target>.min.js',
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
							'media/themes/<theme>/scripts/<version>/<target>.min.js.map',
							$theme + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'javascript-map',
			),

		'mjolnir:theme/themedriver/javascript-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/scripts/<version>/src/<path>.js',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'javascript-source',
			),

		'mjolnir:theme/themedriver/javascript-complete.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/scripts/<version>-complete/master.min.js',
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
							'media/themes/<theme>/scripts/<version>-complete/master.min.js.map',
							$theme + $version
						),

			// Theme Driver
				'theme.driver' => 'javascript-complete-map',
			),



		'mjolnir:theme/themedriver/javascript-complete-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/scripts/<version>-complete/src/<path>.js',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'javascript-complete-source',
			),

	# style

		'mjolnir:theme/themedriver/style.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/styles/<style>/<version>/root/<target>.css',
							$theme + $style + $version + $target
						),

			// Theme Driver
				'theme.driver' => 'style',
			),

		'mjolnir:theme/themedriver/style-complete.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/styles/<style>/<version>-complete/root/master.css',
							$theme + $style + $version
						),

			// Theme Driver
				'theme.driver' => 'style-complete',
			),

		'mjolnir:theme/themedriver/style-resource.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/styles/<style>/<version>(-complete)/root/<path>',
							$theme + $style + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'style-resource',
			),

		'mjolnir:theme/themedriver/style-source.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/styles/<style>/<version>/src/<path>',
							$theme + $style + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'style-source',
			),

	# theme resources (images, embeds, etc)

		'mjolnir:theme/themedriver/resource.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/resources/<version>/root/<path>',
							$theme + $version + $path
						),

			// Theme Driver
				'theme.driver' => 'resource',
			),

	# misc

		'mjolnir:theme/themedriver/bootstrap.route' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/bootstrap.js',
							$theme
						),

			// Theme Driver
				'theme.driver' => 'bootstrap',
			),

	);
