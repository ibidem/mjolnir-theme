<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_DartJavascript;

class ThemeDriver_DartJavascriptTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_DartJavascript'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_DartJavascript

} # test
