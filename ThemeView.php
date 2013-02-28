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
	static function instance()
	{
		$instance = parent::instance();
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

			$themeconfig = include $themepath.$configname.EXT;

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
					('Theme Corruption: undefined target ['.$this->viewtarget.']');
			}
		}
	}

	/**
	 * @return \mjolnir\types\View
	 */
	function partial($path)
	{
		return \app\View::instance()
			->inherit($this)
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
