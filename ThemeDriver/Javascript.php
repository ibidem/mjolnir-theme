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
	 * @return static
	 */
	function package(\mjolnir\types\Theme $theme, $parentversion)
	{
		$themeconfig = $theme->config();
		$javascriptbasepath = $theme->themepath().$themeconfig['default.scripts.dir'].'/';
		$javascriptpath = \str_replace('\\', '/', $javascriptbasepath);

		// load configuration
		$javascriptconfig = include $javascriptpath.'+scripts'.EXT;

		// attempt to get version
		if (isset($javascriptconfig['version']))
		{
			$version = $javascriptconfig['version'];
		}
		else # no version specified
		{
			$version = $parentversion;
		}

		// cleanup any previous version
		\app\Filesystem::delete($javascriptpath.'packages/'.$version.'/');
		// copy compiled files
		\app\Filesystem::copy($javascriptpath.'root/', $javascriptpath.'packages/'.$version.'/');
		\app\Filesystem::delete($javascriptpath.'packages/'.$version.'/.gitignore');

		return $this;
	}

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

		$relaynode = $this->channel()->get('relaynode');
		$target = $relaynode->get('target');
		$version = $relaynode->get('version');
		$theme = $relaynode->get('theme');

		$this->channel()->add('http:header', ['content-type', 'application/javascript']);

		// cache headers
		$this->channel()->add('http:header', ['Cache-Control', 'private']);
		$this->channel()->add('http:header', ['Expires', \gmdate('D, d M Y H:i:s \G\M\T', \time() + 86400 * 7)]);

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

		$fallback_script = 'console.log("failed to load target ['.$target.']");';

		$file = \app\Filesystem::gets($rootpath.$target.'.min.js', null);

		if ($file !== null)
		{
			return $file;
		}
		else # failed to load script
		{
			\mjolnir\log('Theme', "Failed to load {$rootpath}{$target}.min.js");
			return $fallback_script;
		}
	}

} # class
