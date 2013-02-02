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
	 * @return \mjolnir\types\ThemeView
	 */
	static function instance()
	{
		$instance = parent::instance();
		return $instance;
	}

	/**
	 * @return \mjolnir\types\ThemeView
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

			if (isset($themeconfig['mapping'][$this->viewtarget]))
			{
				$composition = $themeconfig['mapping'][$this->viewtarget];
				$base = $this->compileview(\array_shift($composition), $themepath);

				foreach ($composition as $view)
				{
					if (\is_string($view))
					{
						$base->pass('view', $this->compileview($view, $themepath)->render());
					}
					else # assume array
					{
						$compiled = '';
						foreach ($view as $viewfile)
						{
							$compiled .= $this->compileview($viewfile, $themepath)->render();
						}

						$base->pass('view', $compiled);
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
	 * @return \mjolnir\types\Rendereable
	 */
	protected function compileview($file, $themepath)
	{
		return \app\View::instance()
			->inherit($this)
			->file_path($themepath.$file.EXT);
	}

} # class
