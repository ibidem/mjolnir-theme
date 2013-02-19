<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Theme extends \app\Instantiatable implements \mjolnir\types\Theme
{
	use \app\Trait_Theme;

	/**
	 * @var \mjolnir\types\Theme
	 */
	static $maintheme = null;

	/**
	 * @return string
	 */
	static function instance($themename = null, $themepath = null)
	{
		if ($themename === null && $themepath === null)
		{
			if (static::$maintheme !== null)
			{
				return static::$maintheme;
			}
			else # new default instance by detection
			{
				$instance = parent::instance();

				$themename = isset($_GET['theme']) ? $_GET['theme'] : null;

				if ($themename === null)
				{
					$themename = \app\Session::get('theme', null);
				}

				if ($themename === null)
				{
					$themedetectors = \app\CFS::config('mjolnir/theme-detectors');

					foreach ($themedetectors as $detector)
					{
						$themename = $detector();

						if ($themename !== null)
						{
							break;
						}
					}
				}

				if ($themename !== null)
				{
					$instance->themename_is($themename);
					$instance->themepath_for($themename);
				}
				else # use default, ie. first theme
				{
					$corethemes = static::corethemes();

					if ( ! empty($corethemes))
					{
						$themename = \key($corethemes);

						$instance->themename_is($themename);
						$instance->themepath_is($corethemes[$themename]);
					}
					else # empty core themes
					{
						throw new \app\Exception('Theme Corruption: No themes present in environment file.');
					}
				}
			}

			return static::$maintheme = $instance;
		}
		else # standalone instance
		{
			$instance = parent::instance();
			$instance->themename_is($themename);

			if ($themepath !== null)
			{
				$instance->themepath_is($themepath);
			}
			else # try core themes
			{
				$instance->themepath_for($themename);
			}

			// this is a standalone theme instance; we don't update maintheme
			return $instance;
		}
	}

	/**
	 * @return \mjolnir\types\ThemeView
	 */
	function themeview($viewtarget)
	{
		$themepath = $this->get('themepath', null);

		if ($themepath === null)
		{
			throw new \app\Exception('Corrupt Theme: Could not find theme path.');
		}

		return \app\ThemeView::instance()
			->channel_is($this->channel())
			->themepath_is($themepath)
			->viewtarget_is($viewtarget);
	}

	/**
	 * @return array
	 */
	function config()
	{
		static $config = null;

		$config !== null or $config = \app\Arr::merge(include $this->themepath().'+theme'.EXT, \app\CFS::config('mjolnir/themes'));

		return $config;
	}

	/**
	 * @return mixed
	 */
	function configvalue($key, $default = null)
	{
		$config = $this->config();
		if (isset($config[$key]))
		{
			return $config[$key];
		}
		else # no value set
		{
			return $default;
		}
	}

	/**
	 * @return string
	 */
	function version()
	{
		return $this->configvalue('version', '0.0');
	}

	/**
	 * @return mixed
	 */
	function loadrelative($path)
	{
		$path = \ltrim($path, '\\/');
		return include $this->themepath().$path;
	}

	/**
	 * List of core themes. Core themes are themes defined in ENVFILE under the
	 * key themes. Any themes located in the cascading file system are ancilary
	 * themes since they are used for various misc pages and may appear even
	 * outside of DOCROOT.
	 *
	 * @return array or null
	 */
	static function corethemes()
	{
		$environment = include ENVFILE;

		if (isset($environment['themes']))
		{
			return $environment['themes'];
		}
		else # environment not set; no core themes
		{
			return null;
		}
	}


} # class
