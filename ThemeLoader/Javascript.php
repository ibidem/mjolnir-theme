<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeLoader_Javascript extends \app\Instantiatable implements \mjolnir\types\ThemeLoader
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
		$javascriptconfig = $this->javascriptconfig($theme);

		if ($javascriptconfig['mode'] === 'targeted')
		{
			$target = $this->get('viewtarget');
			if (isset($javascriptconfig['targeted-mapping'][$target]))
			{
				// find true target
				while (\is_string($styleconfig['targeted-mapping'][$target]))
				{
					$target = $styleconfig['targeted-mapping'][$target];
				}

				$htmllayer->add
					(
						'script',
						[
							'type' => 'application/javascript',
							'src' => \app\URL::href
								(
									'mjolnir:theme/themedriver/javascript.route',
									[
										'theme'   => $theme->themename(),
										'version' => isset($javascriptconfig['version']) ? $javascriptconfig : $theme->version(),
										'target'  => $target,
									]
								)
						]
					);
			}
		}
		else if ($javascriptconfig['mode'] === 'complete')
		{
			$htmllayer->add
				(
					'script',
					[
						'type' => 'application/javascript',
						'src' => \app\URL::href
							(
								'mjolnir:theme/themedriver/javascript-complete.route',
								[
									'theme'   => $theme->themename(),
									'version' => isset($javascriptconfig['version']) ? $javascriptconfig : $theme->version(),
								]
							)
					]
				);
		}
		else # undefined mode
		{
			throw new \app\Exception('Unrecognized script mode: '.$javascriptconfig['mode']);
		}
	}

	/**
	 * @return array
	 */
	protected function javascriptconfig(\mjolnir\types\Theme $theme)
	{
		$javascriptsconfig = $theme->configvalue('scripts.configname', '+scripts');
		$javascriptdir = $theme->configvalue('default.scripts.dir', '+scripts');
		return $theme->loadrelative("/$javascriptdir/$javascriptsconfig".EXT);
	}

} # class
