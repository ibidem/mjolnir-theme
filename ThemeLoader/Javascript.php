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
		if ($this->channel() !== null)
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
					while (\is_string($javascriptconfig['targeted-mapping'][$target]))
					{
						$target = $javascriptconfig['targeted-mapping'][$target];
					}
					
					if (\app\CFS::config('mjolnir/base')['development'] && \app\CFS::config('mjolnir/base')['dev:conf']['raw:js'])
					{
						foreach ($javascriptconfig['targeted-common'] as $script)
						{
							$htmllayer->add
								(
									'script',
									[
										'type' => 'application/javascript',
										'src' => \app\URL::href
											(
												'mjolnir:theme/themedriver/javascript-source.route',
												[
													'theme'   => $theme->themename(),
													'version' => isset($javascriptconfig['version']) ? $javascriptconfig['version'] : $theme->version(),
													'path'  => $script,
												]
											)
									]
								);
						}

						foreach ($javascriptconfig['targeted-mapping'][$target] as $script)
						{
							$htmllayer->add
								(
									'script',
									[
										'type' => 'application/javascript',
										'src' => \app\URL::href
											(
												'mjolnir:theme/themedriver/javascript-source.route',
												[
													'theme'   => $theme->themename(),
													'version' => isset($javascriptconfig['version']) ? $javascriptconfig['version'] : $theme->version(),
													'path'  => $script,
												]
											)
									]
								);
						}
					}
					else # production
					{
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
												'version' => isset($javascriptconfig['version']) ? $javascriptconfig['version'] : $theme->version(),
												'target'  => $target,
											]
										)
								]
							);
					}
				}
			}
			else if ($javascriptconfig['mode'] === 'complete')
			{
				if (\app\CFS::config('mjolnir/base')['development'] && \app\CFS::config('mjolnir/base')['dev:conf']['raw:js'])
				{
					foreach ($javascriptconfig['complete-mapping'] as $script)
					{
						$htmllayer->add
							(
								'script',
								[
									'type' => 'application/javascript',
									'src' => \app\URL::href
										(
											'mjolnir:theme/themedriver/javascript-source.route',
											[
												'theme'   => $theme->themename(),
												'version' => isset($javascriptconfig['version']) ? $javascriptconfig['version'] : $theme->version(),
												'path'  => $script,
											]
										)
								]
							);
					}
				}
				else # production
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
											'version' => isset($javascriptconfig['version']) ? $javascriptconfig['version'] : $theme->version(),
										]
									)
							]
						);
				}
			}
			else # undefined mode
			{
				throw new \app\Exception('Unrecognized script mode: '.$javascriptconfig['mode']);
			}
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
