<?php return array
	(
		'whitelist' => array
			(
				'+mockup' => array
					(
						\app\Allow::relays
							(
								'\mjolnir\theme\mockup',
								'\mjolnir\theme\mockup-form',
								'\mjolnir\theme\mockup-errors'
							)->all_parameters(),
					),
			),
	);