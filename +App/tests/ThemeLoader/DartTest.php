<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeLoader_Dart;

class ThemeLoader_DartTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeLoader_Dart'));
	}

	// @todo tests for \mjolnir\theme\ThemeLoader_Dart

} # test
