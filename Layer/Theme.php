<?php namespace ibidem\theme;

/**
 * @package    ibidem
 * @category   Themes
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_Theme extends \app\Layer
	implements \ibidem\types\RelayCompatible
{
	/**
	 * @var string
	 */
	protected static $layer_name = __CLASS__;
	
	/**
	 * @var array
	 */
	protected $relay;

	/**
	 * Execute the layer.
	 */
	public function execute()
	{
		try 
		{
			$params = $this->relay['route']->get_params();
			$mode = $this->relay['mode'];
			$theme = $params->get('theme');
			$style = $params->get('style');
			
			# we don't process version; version is only for the useragent
			
			$settings = \app\CFS::config('ibidem/themes');

			$theme_path = $settings['themes.dir'].DIRECTORY_SEPARATOR
				. $theme.DIRECTORY_SEPARATOR;
			
			$theme_config_file = \app\CFS::file
				(
					$theme_path.$settings['themes.config']
				);
			
			if ($theme_config_file)
			{
				$theme_config = include $theme_config_file;
			}
			else # no theme configuration
			{
				throw new \app\Exception_NotFound
					(
						'Missing theme configuration.'
					);
			}
			
			if ($mode !== 'script')
			{
				$style_dir = $theme_path.$theme_config['styles'].DIRECTORY_SEPARATOR
						. $style.DIRECTORY_SEPARATOR;

				// theme styles are static content dependent
				$absolute_style_dir = \app\CFS::dir($style_dir);

				if ($absolute_style_dir)
				{
					$style_config_file = $absolute_style_dir.$settings['style.config'].EXT;
				}
				else # path not found
				{
					throw new \app\Exception_NotFound
						(
							'Missing style.'
						);
				}

				if ($style_config_file)
				{
					$style_config = include $style_config_file;
				}
				else # no style configuration
				{
					throw new \app\Exception_NotFound
						(
							'Missing style configuration.'
						);
				}
			
				if ($mode === 'style')
				{
					try 
					{
						$target = $params->get('target');

						// mime type
						$this->dispatch
							(
								\app\Event::instance()
									->subject(\ibidem\types\Event::content_type)
									->contents('text/css')
							);

						if ( ! isset($style_config['targets'][$target]))
						{
							throw new \app\Exception_NotFound
								("Missing target [$target] in style [$style] of theme [$theme].");
						}

						$target_files = $style_config['targets'][$target];

						// combine all files; if necesary
						$output = '';
						foreach ($target_files as $file)
						{
							$output .= \file_get_contents
								(
									$absolute_style_dir.$style_config['style.root'].$file.'.css'
								);
						}

						$this->contents($output);
					}
					catch (\Exception $exception)
					{
						$safe_string = \addslashes($exception->getMessage());

						$this->contents
							(
								'/*'.PHP_EOL.PHP_EOL
								. '   ERROR:'.PHP_EOL.PHP_EOL
								. "   \t".$exception->getMessage().PHP_EOL.PHP_EOL
								. '*/'.PHP_EOL
								. PHP_EOL.PHP_EOL.PHP_EOL
								. "body {content: '{$safe_string}';".PHP_EOL
								. 'position: absolute; top: 0; left: 0; width: 500px; height: 100px; padding: 10px;'.PHP_EOL
								. 'z-index: 1000; color: #222; background: #eee; font-size: medium; font-family: monospace; }'
								. PHP_EOL
							);

						throw $exception;
					}
				}
				else # $mode === 'resource'
				{
					try 
					{
						$path = $params->get('path');

						// we don't allow parent references
						if (\strpos($path, '..') !== false)
						{
							throw new \app\Exception_NotApplicable();
						}

						$file = $absolute_style_dir.$style_config['style.root'].$path;

						if (\file_exists($file))
						{
							$this->contents(\file_get_contents($file));
						}

						// mime type
						$finfo = \finfo_open(FILEINFO_MIME_TYPE);
						$this->dispatch
							(
								\app\Event::instance()
									->subject(\ibidem\types\Event::content_type)
									->contents(\finfo_file($finfo, $file))
							);
						\finfo_close($finfo);

						$this->contents(\file_get_contents($file));
					} 
					catch (\Exception $exception)
					{
						$this->contents($exception->getMessage());
						throw $exception;
					}
				}
			}
			else if ($mode === 'script')
			{
				try
				{
					$script_dir = $theme_path.$theme_config['scripts'].DIRECTORY_SEPARATOR;

					// theme styles are static content dependent
					$absolute_script_dir = \app\CFS::dir($script_dir);

					if ($absolute_script_dir)
					{
						$script_config_file = $absolute_script_dir.$settings['script.config'].EXT;
					}
					else # path not found
					{
						throw new \app\Exception_NotFound
							(
								'Missing script.'
							);
					}

					if ($script_config_file)
					{
						$script_config = include $script_config_file;
					}
					else # no style configuration
					{
						throw new \app\Exception_NotFound
							(
								'Missing script configuration.'
							);
					}
					
					$target = $params->get('target');

					// mime type
					$this->dispatch
						(
							\app\Event::instance()
								->subject(\ibidem\types\Event::content_type)
								->contents('text/javascript')
						);

					if ( ! isset($script_config['targets'][$target]))
					{
						throw new \app\Exception_NotFound
							("Missing target [$target] in scripts, for theme [$theme].");
					}

					$target_files = $script_config['targets'][$target];

					// combine all files; if necesary
					$output = '';
					foreach ($target_files as $file)
					{
						$output 
							.= \file_get_contents
									(
										$absolute_script_dir.$script_config['script.root'].$file.'.js'
									)
							. PHP_EOL.PHP_EOL;
					}

					$this->contents($output);
				}
				catch (\Exception $exception)
				{
					$safe_string = \addslashes($exception->getMessage());
					
					$this->contents
						(
							'/*'.PHP_EOL.PHP_EOL
							. '   ERROR:'.PHP_EOL.PHP_EOL
							. "   \t".$exception->getMessage().PHP_EOL.PHP_EOL
							. '*/'.PHP_EOL
							. PHP_EOL.PHP_EOL.PHP_EOL
							. ";(function () { alert('{$safe_string}') }) ();"
						);
							
					throw $exception;
				}
			}
			
			// expires headers
			$this->dispatch
				(
					\app\Event::instance()
						->subject(\ibidem\types\Event::expires)
						->contents(\strtotime('+30 days'))
				);
		}
		catch (\Exception $exception)
		{
			$this->exception($exception, true);
		}
	}
	
	/**
	 * @param string theme
	 * @return array
	 */
	public static function script_config($theme)
	{	
		$settings = \app\CFS::config('ibidem/themes');

		$theme_path = $settings['themes.dir'].DIRECTORY_SEPARATOR
			. $theme.DIRECTORY_SEPARATOR;

		$theme_config_file = \app\CFS::file
			(
				$theme_path.$settings['themes.config']
			);

		if ($theme_config_file)
		{
			$theme_config = include $theme_config_file;
		}
		else # no theme configuration
		{
			throw new \app\Exception_NotFound
				(
					'Missing theme configuration.'
				);
		}
		
		$script_dir = $theme_path.$theme_config['scripts'].DIRECTORY_SEPARATOR;

		// theme styles are static content dependent
		$absolute_script_dir = \app\CFS::dir($script_dir);

		if ($absolute_script_dir)
		{
			$script_config_file = $absolute_script_dir.$settings['script.config'].EXT;
		}
		else # path not found
		{
			throw new \app\Exception_NotFound
				(
					'Missing script.'
				);
		}

		if ($script_config_file)
		{
			$script_config = include $script_config_file;
		}
		else # no style configuration
		{
			throw new \app\Exception_NotFound
				(
					'Missing script configuration.'
				);
		}
		
		return $script_config;
	}
	
	/**
	 * @param string theme
	 * @param string style
	 * @return array
	 */
	public static function style_config($theme, $style)
	{
		$settings = \app\CFS::config('ibidem/themes');

		$theme_path = $settings['themes.dir'].DIRECTORY_SEPARATOR
			. $theme.DIRECTORY_SEPARATOR;

		$theme_config_file = \app\CFS::file
			(
				$theme_path.$settings['themes.config']
			);

		if ($theme_config_file)
		{
			$theme_config = include $theme_config_file;
		}
		else # no theme configuration
		{
			throw new \app\Exception_NotFound
				(
					'Missing theme configuration.'
				);
		}

		$style_dir = $theme_path.$theme_config['styles'].DIRECTORY_SEPARATOR
				. $style.DIRECTORY_SEPARATOR;

		// theme styles are static content dependent
		$absolute_style_dir = \app\CFS::dir($style_dir);

		if ($absolute_style_dir)
		{
			$style_config_file = $absolute_style_dir.$settings['style.config'].EXT;
		}
		else # path not found
		{
			throw new \app\Exception_NotFound
				(
					'Missing style.'
				);
		}

		if ($style_config_file)
		{
			return include $style_config_file;
		}
		else # no style configuration
		{
			throw new \app\Exception_NotFound
				(
					'Missing style configuration.'
				);
		}
	}
	
	/**
	 * @param array relay configuration
	 * @return \ibidem\theme\Layer_Theme
	 */
	public function relay_config(array $relay)
	{
		$this->relay = $relay;
		return $this;
	}
	
} # class
