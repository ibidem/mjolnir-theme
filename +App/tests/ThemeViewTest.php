<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeView;

class ThemeViewTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeView'));
	}

	// @todo tests for \mjolnir\theme\ThemeView

} # test
