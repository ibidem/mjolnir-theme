<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_JavascriptComplete;

class ThemeDriver_JavascriptCompleteTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_JavascriptComplete'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_JavascriptComplete

} # test
