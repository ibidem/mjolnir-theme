<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_JavascriptCompleteSource;

class ThemeDriver_JavascriptCompleteSourceTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_JavascriptCompleteSource'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_JavascriptCompleteSource

} # test
