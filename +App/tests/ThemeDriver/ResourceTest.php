<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_Resource;

class ThemeDriver_ResourceTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_Resource'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_Resource

} # test
