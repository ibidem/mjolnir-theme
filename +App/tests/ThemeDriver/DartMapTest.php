<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_DartMap;

class ThemeDriver_DartMapTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_DartMap'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_DartMap

} # test
