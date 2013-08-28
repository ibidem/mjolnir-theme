<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_Javascript;

class ThemeDriver_JavascriptTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_Javascript'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_Javascript

} # test
