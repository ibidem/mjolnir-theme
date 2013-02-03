<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeLoader_Dart extends \app\Instantiatable implements \mjolnir\types\ThemeLoader
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

		foreach ($this->get('head', []) as $path)
		{
			$htmllayer->add
				(
					'headscript',
					[
						'type' => 'application/dart',
						'src' => \app\URL::href
							(
								'mjolnir:theme/themedriver/dart.route',
								[
									'theme'   => $theme->themename(),
									'version' => $theme->version(),
									'path'    => $path
								]
							)
					]
				);
		}

		foreach ($this->get('script', []) as $path)
		{
			$htmllayer->add
				(
					'script',
					[
						'type' => 'application/dart',
						'src' => \app\URL::href
							(
								'mjolnir:theme/themedriver/dart.route',
								[
									'theme'   => $theme->themename(),
									'version' => $theme->version(),
									'path'    => $path
								]
							)
					]
				);
		}

		// fallback code
		$htmllayer->add
			(
				'headscript',
				[
					'type' => 'application/x-javascript',
					'src' => \app\URL::href
						(
							'mjolnir:theme/themedriver/dart-resource.route',
							[
								'theme'   => $theme->themename(),
								'version' => $theme->version(),
								'path'    => '/packages/browser/dart.js'
							]
						)
				]
			);

	}

} # class
