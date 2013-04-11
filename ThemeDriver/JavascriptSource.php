<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_JavascriptSource extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
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
		$srcpath = $javascriptpath.$javascriptconfig['sources'];

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$srcfile = $srcpath.$path.'.js';
		$this->channel()->add('http:header', ['content-type', 'application/javascript']);

		return \app\Filesystem::gets($srcfile);
	}

} # class
