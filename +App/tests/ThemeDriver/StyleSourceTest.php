<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_StyleSource;

class ThemeDriver_StyleSourceTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_StyleSource'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_StyleSource

} # test
