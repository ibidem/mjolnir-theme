<?php namespace app;

$mockup = \app\CFS::config('mjolnir/layer-stacks')['html'];

\app\Relay::process('\mjolnir\theme\mockup', $mockup);
\app\Relay::process('\mjolnir\theme\mockup-errors', $mockup);
\app\Relay::process('\mjolnir\theme\mockup-form', $mockup);
