<?php return array
	(
		'whitelist' => array
			(
				'+mockup' => array
					(
						\app\Protocol::instance()
							->relays(['\ibidem\theme\mockup', '\ibidem\theme\mockup-form', '\ibidem\theme\mockup-errors']),
					),
			),
	);