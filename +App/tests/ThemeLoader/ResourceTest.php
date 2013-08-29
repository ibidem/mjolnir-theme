<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeLoader_Resource;

class ThemeLoader_ResourceTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeLoader_Resource'));
	}

	// @todo tests for \mjolnir\theme\ThemeLoader_Resource

} # test
