<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeHeadInclude;

class ThemeHeadIncludeTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeHeadInclude'));
	}

	// @todo tests for \mjolnir\theme\ThemeHeadInclude

} # test
