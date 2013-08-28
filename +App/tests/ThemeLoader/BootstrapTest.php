<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeLoader_Bootstrap;

class ThemeLoader_BootstrapTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeLoader_Bootstrap'));
	}

	// @todo tests for \mjolnir\theme\ThemeLoader_Bootstrap

} # test
