<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeLoader_Javascript;

class ThemeLoader_JavascriptTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeLoader_Javascript'));
	}

	// @todo tests for \mjolnir\theme\ThemeLoader_Javascript

} # test
