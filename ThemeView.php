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
	function instance()
	{
		$instance = parent::instance();
		$instance->set('theme', static::theme());
	}
	
	/**
	 * @return \mjolnir\types\ThemeView
	 */
	function for_target($viewtarget)
	{
		$instance = static::instance();
		$this->set('viewtarget', $viewtarget);
		
		return $instance;
	}
	
} # class
