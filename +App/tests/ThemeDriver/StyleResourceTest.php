<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_StyleResource;

class ThemeDriver_StyleResourceTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_StyleResource'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_StyleResource

} # test
