<?php return array
	(
		'priority' => array
			(
				'source'     => 100,
				'default'    => 200,
				'source-map' => 300,
				'resource'   => 400,
			),

		'drivers' => array
			(
				# no type is understood as "default"

				'resource'                   => [ 'enabled' => true, 'type' => 'resource' ],
				'bootstrap'                  => [ 'enabled' => true ],

				'dart'                       => [ 'enabled' => true ],
				'dart-map'                   => [ 'enabled' => true, 'type' => 'source-map' ],
				'dart-javascript'            => [ 'enabled' => true ],
				'dart-javascript-map'        => [ 'enabled' => true, 'type' => 'source-map' ],
				'dart-resource'              => [ 'enabled' => true, 'type' => 'resource' ],

				'style'                      => [ 'enabled' => true ],
				'style-map'                  => [ 'enabled' => true, 'type' => 'source-map' ],
				'style-complete'             => [ 'enabled' => true ],
				'style-complete-map'         => [ 'enabled' => true, 'type' => 'source-map' ],
				'style-source'               => [ 'enabled' => true, 'type' => 'source' ],
				'style-resource'             => [ 'enabled' => true, 'type' => 'resource' ],

				'javascript'                 => [ 'enabled' => true ],
				'javascript-map'             => [ 'enabled' => true, 'type' => 'source-map' ],
				'javascript-source'          => [ 'enabled' => true, 'type' => 'source' ],
				'javascript-complete'        => [ 'enabled' => true ],
				'javascript-complete-source' => [ 'enabled' => true, 'type' => 'source' ],
				'javascript-complete-map'    => [ 'enabled' => true, 'type' => 'source-map' ],

			),

	);
