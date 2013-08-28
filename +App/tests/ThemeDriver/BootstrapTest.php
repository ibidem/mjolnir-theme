<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_Bootstrap;

class ThemeDriver_BootstrapTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_Bootstrap'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_Bootstrap

} # test
