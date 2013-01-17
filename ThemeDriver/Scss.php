<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Scss extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;
	
	/**
	 * ...
	 */
	function render()
	{
		$styleconfig = $this->collection_entityfile('style', 'styles');
		$this->channel()->set('styleconfig', $styleconfig);

		$stylepath = $this->channel()->get('stylepath');
		$rootpath = $stylepath.$styleconfig['root'];

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$resourcepath = $rootpath.$path.'.css';		

		$this->channel()->add('http:header', ['content-type', 'text/css']);

		return \app\Filesystem::gets($resourcepath);
	}

} # class
