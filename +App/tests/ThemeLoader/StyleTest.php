<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeLoader_Style;

class ThemeLoader_StyleTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeLoader_Style'));
	}

	// @todo tests for \mjolnir\theme\ThemeLoader_Style

} # test
