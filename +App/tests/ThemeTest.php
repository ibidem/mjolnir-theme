<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\Theme;

class ThemeTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\Theme'));
	}

	// @todo tests for \mjolnir\theme\Theme

} # test
