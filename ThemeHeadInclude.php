<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeHeadInclude extends \app\View implements \mjolnir\types\View
{
	use \app\Trait_Channeled;

	// ------------------------------------------------------------------------
	// interface: Renderable

	/**
	 * ...
	 */
	function render()
	{
		$contents = parent::render();

		$this->channel()->add('html:extra-markup', $contents);

		return $contents;
	}

} # class
