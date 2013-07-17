<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_DartResource extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
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
		$rootpath = $dartspath.$dartsconfig['root'];

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$resourcepath = $rootpath.$path;
		$mimetype = \app\Filesystem::mimetype($resourcepath);

		$this->channel()->add('http:header', ['content-type', $mimetype]);

		// cache headers
		$this->channel()->add('http:header', ['Cache-Control', 'private']);
		$this->channel()->add('http:header', ['Expires', \date(DATE_RFC822, \strtotime("7 days"))]);

		return \app\Filesystem::gets($resourcepath);
	}

} # class
