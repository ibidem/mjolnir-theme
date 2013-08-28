<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_DartJavascriptMap;

class ThemeDriver_DartJavascriptMapTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_DartJavascriptMap'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_DartJavascriptMap

} # test
