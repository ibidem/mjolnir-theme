<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_JavascriptMap;

class ThemeDriver_JavascriptMapTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_JavascriptMap'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_JavascriptMap

} # test
