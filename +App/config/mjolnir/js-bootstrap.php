<?php return array
	(
		// place keys with function outputting json, the bootstrap will be 
		// included in all script calls and can be accessed via the Bootstrap 
		// variable
	
		'development' => function () 
			{
				return \app\CFS::config('mjolnir/base')['development'] ? 'true' : 'false';
			},
					
		'url_base' => function ()
			{
				return '"//'.\app\CFS::config('mjolnir/base')['domain'].\app\CFS::config('mjolnir/base')['path'].'"';
			},
					
		'domain' => function ()
			{
				return '"'.\app\CFS::config('mjolnir/base')['domain'].'"';
			},
					
		'base_path' => function ()
			{
				return '"'.\app\CFS::config('mjolnir/base')['path'].'"';
			},
	
	); # config
