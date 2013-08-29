<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeDriver_Dart;

class ThemeDriver_DartTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeDriver_Dart'));
	}

	// @todo tests for \mjolnir\theme\ThemeDriver_Dart

} # test
