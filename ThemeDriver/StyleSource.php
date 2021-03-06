<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_StyleSource extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
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
		$rootpath = $stylepath.$styleconfig['sources'];

		$path = $this->channel()->get('relaynode')->get('path');
		$this->security_pathcheck($path);

		$srcfile = $rootpath.$path;
		$mimetype = \app\Filesystem::mimetype($srcfile);

		$this->channel()->add('http:header', ['content-type', $mimetype]);

		return \app\Filesystem::gets($srcfile);
	}

} # class
