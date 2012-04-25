<?php namespace ibidem\theme;

use \ibidem\types\Enum_Requirement as Requirement;

return array
	(
		'ibidem\theme' => array
			(
				 'extension=php_fileinfo' => function ()
					{
						if (\extension_loaded('fileinfo'))
						{
							return Requirement::available;
						}
						
						return Requirement::error;
					}
			),
	);
