<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Bootstrap extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;
	
	/**
	 * ...
	 */
	function render()
	{
		$this->channel()->add('themedriver:type', 'dynamic');
		$this->channel()->add('http:header', ['content-type', 'application/json']);
		
		return "// Mjolnir Bootstrap\nvar mjb = ".\json_encode(\app\CFS::config('mjolnir/bootstrap')).';';
	}

} # class
