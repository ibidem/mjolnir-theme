<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_JavascriptCompleteMap extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;

	/**
	 * ...
	 */
	function render()
	{
		$javascriptconfig = $this->collectionfile('scripts');
		$this->channel()->set('scriptsconfig', $javascriptconfig);

		$javascriptpath = $this->channel()->get('scriptspath');

		if (\app\CFS::config('mjolnir/base')['theme']['packaged'])
		{
			if (isset($javascriptconfig['version']))
			{
				$rootpath = $javascriptpath.'packages/'.$javascriptconfig['version'].'/';
			}
			else # fallback to theme version
			{
				$theme = $this->channel()->get('theme');
				$rootpath = $javascriptpath.'packages/'.$theme->version().'/';
			}
		}
		else # non-packaged mode
		{
			$rootpath = $javascriptpath.$javascriptconfig['root'];
		}

		return \app\Filesystem::gets($rootpath.'master.min.js.map');
	}

} # class
