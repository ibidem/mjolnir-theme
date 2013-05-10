<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeLoader_Style extends \app\Instantiatable implements \mjolnir\types\ThemeLoader
{
	use \app\Trait_ThemeLoader;

	/**
	 * ...
	 */
	function run()
	{
		if ($this->channel() !== null)
		{
			$htmllayer = $this->channel()->get('layer:html');

			if ($htmllayer === null)
			{
				return; # we do not support other hooks at this time
			}

			$theme = $this->channel()->get('theme', \app\Theme::instance());
			$styleconfig = $this->styleconfig($theme);

			if ($styleconfig['mode'] === 'targeted')
			{
				$target = $this->get('viewtarget');
				if (isset($styleconfig['targeted-mapping'][$target]))
				{
					// find true target
					while (\is_string($styleconfig['targeted-mapping'][$target]))
					{
						$target = $styleconfig['targeted-mapping'][$target];
					}

					$htmllayer->add
						(
							'stylesheet',
							[
								'type' => 'text/css',
								'href' => \app\URL::href
									(
										'mjolnir:theme/themedriver/style.route',
										[
											'theme'   => $theme->themename(),
											'style'   => $theme->get('style', $this->get('default.style')),
											'version' => isset($styleconfig['version']) ? $styleconfig['version'] : $theme->version(),
											'target'  => $target,
										]
									)
							]
						);
				}
			}
			else if ($styleconfig['mode'] === 'complete')
			{
				$htmllayer->add
					(
						'stylesheet',
						[
							'type' => 'text/css',
							'href' => \app\URL::href
								(
									'mjolnir:theme/themedriver/style-complete.route',
									[
										'theme'   => $theme->themename(),
										'style'   => $theme->get('style', $this->get('default.style')),
										'version' => isset($styleconfig['version']) ? $styleconfig['version'] : $theme->version(),
									]
								)
						]
					);
			}
			else # undefined mode
			{
				throw new \app\Exception('Unrecognized style mode: '.$styleconfig['mode']);
			}
		}
	}

	/**
	 * @return array
	 */
	protected function styleconfig(\mjolnir\types\Theme $theme)
	{
		$stylesconfig = $theme->configvalue('styles.configname', '+style');
		$stylesdir = $theme->configvalue('default.styles.dir', '+styles');
		$style = $theme->get('style', $this->get('default.style'));
		// check if style exists
		$styleconfig = "/$stylesdir/$style/$stylesconfig".EXT;
		if ($theme->relativepathexists($styleconfig))
		{
			return $theme->loadrelative($styleconfig);
		}
		else # invalid path
		{
			if ($style !== $this->get('default.style'))
			{
				// reset to default and try again
				return $this->styleconfig($theme->set('style', $this->get('default.style')));
			}
			else # default style
			{
				throw new \app\Exception("Unable to load default style ($style)");
			}
		}
	}

} # class
