<?php namespace mjolnir\theme;

return array
	(
		'mjolnir\theme' => array
			(
				 'extension=php_fileinfo' => function ()
					{
						if (\extension_loaded('fileinfo'))
						{
							return 'available';
						}

						return 'error';
					}
			),
	);
