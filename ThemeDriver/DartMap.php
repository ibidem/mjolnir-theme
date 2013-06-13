<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_DartMap extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;
	
	/**
	 * ...
	 */
	function render()
	{
		$this->channel()->add('themedriver:type', 'dynamic');
		
		$dartsconfig = $this->collectionfile('darts');
		$this->channel()->set('dartsconfig', $dartsconfig);

		$dartspath = $this->channel()->get('dartspath');
		
		if (\app\CFS::config('mjolnir/base')['theme']['packaged'])
		{
			$rootpath = $dartspath.'packages/'.VERSION.'/';
		}
		else # non-packaged mode
		{
			$rootpath = $dartspath.$dartsconfig['root'];
		}

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$resourcepath = $rootpath.$path.'.dart.map';
		
		$this->channel()->add('http:header', ['expires', strtotime('-1 day')]);

		return \app\Filesystem::gets($resourcepath);
	}
	
} # class
