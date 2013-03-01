<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeLoader_Bootstrap extends \app\Instantiatable implements \mjolnir\types\ThemeLoader
{
	use \app\Trait_ThemeLoader;

	/**
	 * ...
	 */
	function run()
	{
		$htmllayer = $this->channel()->get('layer:html');

		if ($htmllayer === null)
		{
			return; # we do not support other hooks at this time
		}

		$theme = $this->channel()->get('theme', \app\Theme::instance());
		
		$htmllayer->add
			(
				'script',
				[
					'type' => 'application/javascript',
					'src' => \app\URL::href
						(
							'mjolnir:theme/themedriver/bootstrap.route',
							[
								'theme'   => $theme->themename(),
							]
						)
				]
			);
	}

} # class
