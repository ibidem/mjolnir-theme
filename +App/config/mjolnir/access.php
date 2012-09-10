<?php return array
	(
		'whitelist' => array
			(
				'+mockup' => array
					(
						\app\Protocol::instance()
							->relays(['\mjolnir\theme\mockup', '\mjolnir\theme\mockup-form', '\mjolnir\theme\mockup-errors']),
					),
			),
	);