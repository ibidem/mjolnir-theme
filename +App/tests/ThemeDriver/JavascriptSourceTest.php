<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_JavascriptSource;

class ThemeDriver_JavascriptSourceTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_JavascriptSource'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_JavascriptSource

} # test
