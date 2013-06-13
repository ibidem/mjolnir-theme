<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_JavascriptComplete extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
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
			$rootpath = $javascriptpath.'packages/'.VERSION.'/';
		}
		else # non-packaged mode
		{
			$rootpath = $javascriptpath.$javascriptconfig['root'];
		}
			
		$relaynode = $this->channel()->get('relaynode');
		$version = $relaynode->get('version');
		$theme = $relaynode->get('theme');

		$this->channel()->add('http:header', ['content-type', 'application/javascript']);
		
		// cache headers
		$this->channel()->add('http:header', ['Cache-Control', 'private']);
		$this->channel()->add('http:header', ['Expires', \date(DATE_RFC822, \strtotime("7 days"))]);
		
		$sourcemap_url = \app\URL::href
			(
				'mjolnir:theme/themedriver/javascript-complete-map.route',
				[
					'version' => $version,
					'theme' => $theme,
				]
			);
		
		// [!!] At this time (2013) non-relative urls simply will not work since
		// they cause mismatch between script and source map
		$this->channel()->add('http:header', ['X-SourceMap', \preg_replace('#.*/#', '', $sourcemap_url)]);
		
		return \app\Filesystem::gets($rootpath.'master.min.js', 'console.log("failed to load scripts");');
	}

} # class
