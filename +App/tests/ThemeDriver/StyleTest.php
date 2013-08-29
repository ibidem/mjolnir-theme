<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_Style;

class ThemeDriver_StyleTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_Style'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_Style

} # test
