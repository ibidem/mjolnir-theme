<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Javascript extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
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
		$rootpath = $javascriptpath.$javascriptconfig['root'];

		$relaynode = $this->channel()->get('relaynode');
		$target = $relaynode->get('target');
		$version = $relaynode->get('version');
		$theme = $relaynode->get('theme');

		$this->channel()->add('http:header', ['content-type', 'application/javascript']);
		
		$sourcemap_url = \app\URL::href
			(
				'mjolnir:theme/themedriver/javascript-map.route',
				[
					'version' => $version,
					'theme' => $theme,
					'target' => $target
				]
			);
		
		// [!!] At this time (2013) non-relative urls simply will not work since
		// they cause mismatch between script and source map
		$this->channel()->add('http:header', ['X-SourceMap', \preg_replace('#.*/#', '', $sourcemap_url)]);
		
		return \app\Filesystem::gets($rootpath.$target.'.min.js', 'console.log("failed to load target ['.$target.']");');
	}

} # class
