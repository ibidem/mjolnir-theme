<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\Task_Theme_Packager;

class Task_Theme_PackagerTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\Task_Theme_Packager'));
	}

	// @todo tests for \mjolnir\theme\Task_Theme_Packager

} # test
