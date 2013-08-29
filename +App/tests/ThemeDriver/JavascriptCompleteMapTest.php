<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_JavascriptCompleteMap;

class ThemeDriver_JavascriptCompleteMapTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_JavascriptCompleteMap'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_JavascriptCompleteMap

} # test
