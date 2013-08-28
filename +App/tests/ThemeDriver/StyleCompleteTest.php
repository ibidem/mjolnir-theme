<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_StyleComplete;

class ThemeDriver_StyleCompleteTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_StyleComplete'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_StyleComplete

} # test
