<?php return array
	(
		'make:theme' => array
			(
				'description' => array
					(
						'Create theme scaffold.'
					),
				'flags' => array
					(
						'theme' => array
							(
								'description' => 'Name of theme.',
								'type' => '\mjolnir\base\Flags::text',
								'short' => 't',
								'default' => null,
							),
						'namespace' => array
							(
								'description' => 'Namespace for theme to go in.',
								'type' => '\mjolnir\base\Flags::text',
								'short' => 'n',
								'default' => null,
							),
						'forced' => array
							(
								'description' => 'Force file overwrites.'
							),
					),
			),
		'make:style' => array
			(
				'description' => array
					(
						'Create style scaffold.',
					),
				'flags' => array
					(
						'theme' => array
							(
								'description' => 'Name of theme.',
								'type' => '\mjolnir\base\Flags::text',
								'short' => 't',
								'default' => null,
							),
						'style' => array
							(
								'description' => 'Style name.',
								'type' => '\mjolnir\base\Flags::text',
								'short' => 's',
								'default' => null,
							),
						'forced' => array
							(
								'description' => 'Force file overwrites.'
							),
					),
			),
	);