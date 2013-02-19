<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Resource extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;
	
	/**
	 * ...
	 */
	function render()
	{
		$resourcesconfig = $this->collectionfile('resources');
		$this->channel()->set('resourcesconfig', $resourcesconfig);

		$resourcespath = $this->channel()->get('resourcespath');
		$rootpath = $resourcespath.$resourcesconfig['root'];

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$resourcepath = $rootpath.$path;
		$mimetype = \app\Filesystem::mimetype($resourcepath);

		$this->channel()->add('http:header', ['content-type', $mimetype]);

		return \app\Filesystem::gets($resourcepath);
	}

} # class
