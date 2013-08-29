<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_DartResource;

class ThemeDriver_DartResourceTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_DartResource'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_DartResource

} # test
