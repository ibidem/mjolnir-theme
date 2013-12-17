<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeView extends \app\View implements \mjolnir\types\ThemeView
{
	use \app\Trait_ThemeView;

	/**
	 * @return static
	 */
	static function instance($file = null, $ext = EXT)
	{
		$instance = parent::instance($file = null, $ext = EXT);
		return $instance;
	}

	/**
	 * @return static
	 */
	static function fortarget($viewtarget, \mjolnir\types\Theme $theme = null)
	{
		$theme !== null or $theme = \app\Theme::instance();
		return $theme->themeview($viewtarget);
	}

	// ------------------------------------------------------------------------
	// interface: Renderable

	/**
	 * @return string
	 */
	function render()
	{
		if ($this->filepath !== null)
		{
			return parent::render();
		}
		else # computed view
		{
			// get configuration
			$themepath = $this->themepath();
			$configname = '+theme';

			if (\file_exists($themepath.$configname.EXT))
			{
				$themeconfig = include $themepath.$configname.EXT;
			}
			else # missing theme configuration file
			{
				if ($themepath === null)
				{
					throw new \app\Exception('The [themepath] was not set. Please check your Theme objects for errors. A view must be initialized though a Theme object, or if initializing the [viewtarget] manually you must provide the [themepath].');
				}
				else # themepath was set
				{
					throw new \app\Exception('Missing theme configuration file. Expected path: '.$themepath.$configname.EXT);
				}
			}

			// run loaders
			if ($themeconfig['loaders'] !== null)
			{
				foreach ($themeconfig['loaders'] as $loadername => $config)
				{
					$loaderclass = '\app\ThemeLoader_'.\ucfirst($loadername);
					$loader = $loaderclass::instance()
						->channel_is($this->channel())
						->set('viewtarget', $this->viewtarget);

					$themeloaders = \app\CFS::config('mjolnir/theme-loaders');

					if (isset($themeloaders[$loadername]))
					{
						$loaderconfig = \app\CFS::config('mjolnir/theme-loaders')[$loadername];
					}
					else # no configuration
					{
						throw new \app\Exception('No default configuration for '.$loadername);
					}

					if ($config !== null)
					{
						$loaderconfig = \app\Arr::merge($loaderconfig, $config);
					}

					foreach ($loaderconfig as $key => $value)
					{
						$loader->set($key, $value);
					}

					$loader->run();
				}
			}

			if (isset($themeconfig['mapping'][$this->viewtarget]))
			{
				$composition = $themeconfig['mapping'][$this->viewtarget];
				$base = $this->compileview(\array_shift($composition), $themepath);

				foreach ($composition as $view)
				{
					if (\is_string($view))
					{
						$base
							->pass('theme', $this)
							->pass('entrypoint', $this->compileview($view, $themepath));
					}
					else # assume array
					{
						$compiled = [];
						foreach ($view as $viewfile)
						{
							$compiled[] = $this->compileview($viewfile, $themepath);
						}

						$composite = \app\ViewComposite::instance()
							->views_are($compiled)
							->inherit($base);

						$base
							->pass('theme', $this)
							->pass('entrypoint', $composite);
					}
				}

				return $base->render();
			}
			else # failed
			{
				throw new \app\Exception
					('Theme Corruption: undefined target ['.$this->viewtarget.'] for theme ['.\trim(\str_replace(\str_replace('\\', '/', \app\Env::key('sys.path')), '', \str_replace('\\', '/', $themepath)), '/').']');
			}
		}
	}
	
	/**
	 * Loads the view relative to the theme.
	 * The $theme variable is passed to the view by default.
	 *
	 * @return \mjolnir\types\View
	 */
	function view($path)
	{
		return \app\View::instance()
			->pass('theme', $this)
			->file_path($this->themepath().$path.EXT);
	}

	/**
	 * Same as view but inherits the current themeview context.
	 *
	 * @return \mjolnir\types\View
	 */
	function partial($path)
	{
		return \app\View::instance()
			->inheritcopy($this)
			->pass('theme', $this)
			->file_path($this->themepath().$path.EXT);
	}

	/**
	 * @return string resource url
	 */
	function resource($path)
	{
		return \app\URL::href
			(
				'mjolnir:theme/themedriver/resource.route',
				[
					'theme' => \app\Theme::instance()->themename(),
					'version' => \app\Theme::instance()->version(),
					'path' => $path
				]
			);
	}

	/**
	 * Shorthand for including a piece of code into the head of the page.
	 *
	 * @return \app\ThemeHeadInclude
	 */
	function headinclude($path)
	{
		return \app\ThemeHeadInclude::instance()
			->channel_is($this->channel())
			->inherit($this)
			->pass('theme', $this)
			->file_path($this->themepath().$path.EXT);
	}

	/**
	 * Shorthand for including a piece of code into the head of the page.
	 *
	 * @return \app\ThemeFooterInclude
	 */
	function footerinclude($path)
	{
		return \app\ThemeFooterInclude::instance()
			->channel_is($this->channel())
			->inherit($this)
			->pass('theme', $this)
			->file_path($this->themepath().$path.EXT);
	}

	/**
	 * @return \mjolnir\types\Rendereable
	 */
	protected function compileview($file, $themepath)
	{
		return \app\View::instance()
			->inherit($this)
			->pass('theme', $this)
			->file_path($themepath.$file.EXT);
	}

} # class
