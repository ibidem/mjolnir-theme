<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Dart extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;

	/**
	 * ...
	 */
	function render()
	{
		$dartsconfig = $this->collectionfile('darts');
		$this->channel()->set('dartsconfig', $dartsconfig);

		$dartspath = $this->channel()->get('dartspath');

		if (\app\CFS::config('mjolnir/base')['theme']['packaged'])
		{
			if (isset($dartsconfig['version']))
			{
				$rootpath = $dartspath.'packages/'.$dartsconfig['version'].'/';
			}
			else # fallback to theme version
			{
				$theme = $this->channel()->get('theme');
				$rootpath = $dartspath.'packages/'.$theme->version().'/';
			}
		}
		else # non-packaged mode
		{
			$rootpath = $dartspath.$dartsconfig['root'];
		}

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$resourcepath = $rootpath.$path.'.dart';

		// Filesystem::mimetype won't correctly recognize dart mimetype at the
		// time this code was written (early 2013)
		$this->channel()->add('http:header', ['content-type', 'application/dart']);

		// cache headers
		$this->channel()->add('http:header', ['Cache-Control', 'private']);
		$this->channel()->add('http:header', ['Expires', \gmdate('D, d M Y H:i:s \G\M\T', \time() + 86400 * 7)]);

		return \app\Filesystem::gets($resourcepath);
	}

} # class
