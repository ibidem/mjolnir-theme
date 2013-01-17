<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeFooterInclude extends \app\View
{
	/**
	 * @return string view
	 */
	function render()
	{
		$contents = parent::render();

		\app\GlobalEvent::fire('webpage:body-extra', $contents);

		return $contents;
	}

} # class
