<?php namespace app;

$mockup_stack = function ($relay, $target)
	{
		\app\Layer::stack
			(
				\app\Layer_Access::instance()
					->relay_config($relay)
					->target($target),
				\app\Layer_HTTP::instance(),
				\app\Layer_HTML::instance(),
				\app\Layer_MVC::instance()
					->relay_config($relay)
			);
	};

\app\Relay::process('\ibidem\theme\mockup', $mockup_stack);
\app\Relay::process('\ibidem\theme\mockup-errors', $mockup_stack);
\app\Relay::process('\ibidem\theme\mockup-form', $mockup_stack);