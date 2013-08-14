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
	 * @var array
	 */
	protected $themeconfig = null;

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
		else # embeded instance
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

			return static::$maintheme = $instance;
		}
	}

	/**
	 * Package theme assets. Compile is assumed for dependencies that require
	 * compilation.
	 *
	 * @return static
	 */
	function package()
	{
		// compute version
		$config = $this->config();
		$parentversion = $this->version();

		// run though drivers
		foreach ($config['loaders'] as $driver => $info)
		{
			$driverclass = '\app\ThemeDriver_'.\ucfirst($driver);
			$driverclass::instance()->package($this, $parentversion);
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
		$this->themeconfig !== null or $this->themeconfig = \app\Arr::merge(include $this->themepath().'+theme'.EXT, \app\CFS::config('mjolnir/themes'));

		return $this->themeconfig;
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
		$config = $this->config();
		if (isset($config['version']))
		{
			return $config['version'];
		}
		else if (\defined('MJ_APP_VERSION'))
		{
			return MJ_APP_VERSION;
		}
		else # no usable version
		{
			throw new \app\Exception
				(
					'Unable to located usable version for packaging.'
				);
		}
	}

	/**
	 * @return bool
	 */
	function relativepathexists($path)
	{
		$path = \ltrim($path, '\\/');
		return \file_exists($this->themepath().$path);
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
	 * List of core themes. Core themes are themes defined in the
	 * environment.file under the key themes. Any themes located in the
	 * cascading file system are ancilary themes since they are used for various
	 * misc pages and may appear even outside of sys path.
	 *
	 * @return array or null
	 */
	static function corethemes()
	{
		$environment = \app\Env::key('environment.config');

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
