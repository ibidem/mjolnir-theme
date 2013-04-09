<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeFooterInclude extends \app\View implements \mjolnir\types\View
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

		$htmllayer = $this->channel()->get('layer:html', null);
		
		if ($htmllayer !== null)
		{
			$htmllayer->add('extra_footer_markup', $contents);
		}

		return $contents;
	}

} # class
