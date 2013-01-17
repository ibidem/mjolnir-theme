<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_JsonBootstrap extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;
	
	/**
	 * ...
	 */
	function render()
	{
		$this->channel()->add('themedriver:type', 'dynamic');
		$this->channel()->add('http:header', ['content-type', 'application/json']);
		
		// build configuration
		$bootstrap = \app\CFS::config('mjolnir/bootstrap');
		$config = [];
		
		foreach ($bootstrap as $key => $resolver)
		{
			$config[$key] = $resolver();
		}
		
		return \json_encode($config);
	}

} # class
