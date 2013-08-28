<?php namespace mjolnir\theme\tests;

use \mjolnir\theme\Mockup;

class MockupTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\theme\Mockup'));
	}

	// @todo tests for \mjolnir\theme\Mockup

} # test
