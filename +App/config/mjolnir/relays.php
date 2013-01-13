<?php namespace mjolnir\theme;

$theme_resource_regex = array
	(
		'theme' => '[a-zA-Z\-\._/]+',
		'style' => '[a-zA-Z\-\._]+',
		'version' => '[0-9][a-z0-9-\.]*',
		'path' => '.+', # path is validated internally
		'target' => '[+a-zA-Z0-9\-\._/]+',
	);

return array
	(
		'\mjolnir\theme\mockup' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'mockup/<target>',
							[
								'target' => '[a-zA-Z0-9\-\._/]+',
							]
						),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_testing',
			),

		'\mjolnir\theme\mockup-errors' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'mockup-errors/<target>',
							[
								'target' => '[a-zA-Z0-9\-\._/]+',
							]
						),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_errortesting',
			),

		'\mjolnir\theme\mockup-form' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern('form-mockup'),
				'enabled' => false,
			// MVC
				'controller' => '\app\Controller_Mockup',
				'action' => 'action_form',
			),

		'\mjolnir\theme\Layer_Theme::script' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/<target>.js',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'script',
			),

		'\mjolnir\theme\Layer_Theme::complete-script' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>-complete/master.min.js',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'complete-script',
			),

		'\mjolnir\theme\Layer_Theme::complete-script-map' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>-complete/master.min.js.map',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'script-map',
			),

		'\mjolnir\theme\Layer_Theme::script-map' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/<target>.min.js.map',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'script-map',
			),

		'\mjolnir\theme\Layer_Theme::script-src' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/src/<target>.js',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'script-src',
			),

		'\mjolnir\theme\Layer_Theme::style-src' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/src/<target>.css',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'style-src',
			),

		'\mjolnir\theme\Layer_Theme::style' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/<target>.css',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'style',
			),

		'\mjolnir\theme\Layer_Theme::complete-style' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>-complete/master.css',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'style',
			),

		'\mjolnir\theme\Layer_Theme::resource' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/<path>',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'resource',
			),

		'\mjolnir\theme\Layer_Theme::js-bootstrap' => array
			(
				'matcher' => \app\URLRoute::instance()
					->urlpattern
						(
							'media/themes/<theme>/<style>/<version>/js-bootstrap.js',
							$theme_resource_regex
						),
				'enabled' => true,
			// Theme
				'mode' => 'js-bootstrap',
			),

	);