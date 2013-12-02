<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_StyleResource extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
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

		if (\app\CFS::config('mjolnir/base')['theme']['packaged'])
		{
			if (isset($styleconfig['version']))
			{
				$rootpath = $stylepath.'packages/'.$styleconfig['version'].'/';
			}
			else # fallback to theme version
			{
				$theme = $this->channel()->get('theme');
				$rootpath = $stylepath.'packages/'.$theme->version().'/';
			}
		}
		else # non-packaged mode
		{
			$rootpath = $stylepath.$styleconfig['root'];
		}

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$resourcepath = $rootpath.$path;
		$mimetype = \app\Filesystem::mimetype($resourcepath);

		$this->channel()->add('http:header', ['Content-Type', $mimetype]);

		// cache headers
		$this->channel()->add('http:header', ['Cache-Control', 'private']);
		$this->channel()->add('http:header', ['Expires', \gmdate('D, d M Y H:i:s \G\M\T', \time() + 86400 * 7)]);

		return \app\Filesystem::gets($resourcepath);
	}

} # class
