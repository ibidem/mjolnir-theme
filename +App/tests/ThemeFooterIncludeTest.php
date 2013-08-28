<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\ThemeFooterInclude;

class ThemeFooterIncludeTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\ThemeFooterInclude'));
	}

	// @todo tests for \mjolnir\theme\ThemeFooterInclude

} # test
