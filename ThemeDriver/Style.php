<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Style extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;

	/**
	 * @return static
	 */
	function package(\mjolnir\types\Theme $theme, $parentversion)
	{
		$themeconfig = $theme->config();
		$options = $themeconfig['loaders']['style'];
		if (isset($options['enabled']))
		{
			$styles = $options['enabled'];
		}
		else #
		{
			$styles = [ $options['default.style'] ];
		}

		$stylebasepath = $theme->themepath().$themeconfig['default.styles.dir'].'/';

		foreach ($styles as $key => $path)
		{
			if (\is_int($key))
			{
				$name = $path;
			}
			else # non-int key
			{
				$name = $key;
			}

			$path = \str_replace('\\', '/', $path);
			$stylepath = $stylebasepath.\rtrim($path, '/').'/';

			// load configuration
			$styleconfig = include $stylepath.'+style'.EXT;

			// attempt to get version
			if (isset($styleconfig['version']))
			{
				$version = $styleconfig['version'];
			}
			else # no version specified
			{
				$version = $parentversion;
			}

			// cleanup any previous version
			\app\Filesystem::delete($stylepath.'packages/'.$version.'/');
			// copy compiled files
			\app\Filesystem::copy($stylepath.'root/', $stylepath.'packages/'.$version.'/');
			\app\Filesystem::delete($stylepath.'packages/'.$version.'/.gitignore');
		}

		return $this;
	}

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

		$target = $this->channel()->get('relaynode')->get('target');

		if ( ! isset($styleconfig['targeted-mapping'][$target]))
		{
			$style = $this->channel()->get('relaynode')->get('style');
			throw new \Exception("Missing target [$target] in style [$style].");
		}

		$common = isset($styleconfig['targeted-common']) ? $styleconfig['targeted-common'] : [];

		$this->channel()->add('http:header', ['content-type', 'text/css']);

		// cache headers
		$this->channel()->add('http:header', ['Cache-Control', 'private']);
		$this->channel()->add('http:header', ['Expires', \date(DATE_RFC822, \strtotime("7 days"))]);

		return $this->combine($rootpath, \array_merge($common, $styleconfig['targeted-mapping'][$target]), '.css');
	}

} # class
