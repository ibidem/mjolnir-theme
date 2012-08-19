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
	function execute()
	{
		try 
		{
			$params = $this->relay['matcher']->get_params();
			$mode = $this->relay['mode'];
			$theme = $params->get('theme');
			$style = $params->get('style');
			
			# we don't process version; version is only for the useragent
			
			$settings = \app\CFS::config('ibidem/themes');
			$env_config = include DOCROOT.'environment'.EXT;
			$env_is_set = isset($env_config['themes']) && isset($env_config['themes'][$theme]);
			
			if ($env_is_set)
			{
				$theme_path = $env_config['themes'][$theme].DIRECTORY_SEPARATOR;
				$theme_config_file = $theme_path.$settings['themes.config'].EXT;
			}
			else # search for theme
			{
				$theme_path = \app\CFS::dir
					(
						$settings['themes.dir'].DIRECTORY_SEPARATOR
							. $theme.DIRECTORY_SEPARATOR
					);

				$theme_config_file = $theme_path.$settings['themes.config'].EXT;
			}

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
			
			if (false === \in_array($mode, ['script', 'script-src'])) // ($mode !== 'script')
			{
				$style_dir = $theme_path.$theme_config['styles'].DIRECTORY_SEPARATOR
						. $style.DIRECTORY_SEPARATOR;

				if ($env_is_set)
				{
					$absolute_style_dir = $style_dir;
				}
				else # environment
				{
					// theme styles are static content dependent
					$absolute_style_dir = $style_dir;
				}

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
			
				// expires headers
				\app\GlobalEvent::fire('http:expires', \strtotime('+30 days'));
				
				if ($mode === 'style')
				{
					try 
					{
						$target = $params->get('target');

						\app\GlobalEvent::fire('http:content-type', 'text/css');

						if ( ! isset($style_config['targets'][$target]))
						{
							throw new \app\Exception_NotFound
								("Missing target [$target] in style [$style] of theme [$theme].");
						}
						
						$target_files = $style_config['common'];
					
						// merge target files to common files; preserving order
						foreach ($style_config['targets'][$target] as $target_file)
						{
							$target_files[] = $target_file;
						}

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
				else if ($mode === 'script-map')
				{
					$target = $params->get('target');
				
					$script_dir = $theme_path.$theme_config['scripts'].DIRECTORY_SEPARATOR;

					// theme styles are static content dependent
					$absolute_script_dir = $script_dir;

					if ($absolute_script_dir)
					{
						$script_config_file = $absolute_script_dir.$settings['script.config'].EXT;
					}
					else # path not found
					{
						throw new \app\Exception_NotFound
							(
								'Missing script dir.'
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
					
					// we don't allow parent references
					if (\strpos($target, '..') !== false)
					{
						throw new \app\Exception_NotApplicable();
					}
					
					\app\GlobalEvent::fire('http:expires', strtotime('-1 day'));
					$file = $absolute_script_dir.$script_config['script.root'].'../closure/'.$target.'.min.js.map';
					$this->contents(\file_get_contents($file));
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
						\app\GlobalEvent::fire('http:content-type', \finfo_file($finfo, $file));
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
			else # script mode
			{
				$script_dir = $theme_path.$theme_config['scripts'].DIRECTORY_SEPARATOR;

				// theme styles are static content dependent
				$absolute_script_dir = $script_dir;

				if ($absolute_script_dir)
				{
					$script_config_file = $absolute_script_dir.$settings['script.config'].EXT;
				}
				else # path not found
				{
					throw new \app\Exception_NotFound
						(
							'Missing script dir.'
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
				\app\GlobalEvent::fire('http:content-type', 'text/javascript');

				// check output mode
				if (isset($script_config['closure-mode']) && $script_config['closure-mode'])
				{
					$target = \str_replace('\\', '/', $target);

					if ($mode === 'script-src')
					{
						// this is mapped file from the closure
						$output = \file_get_contents($absolute_script_dir.'src/'.$target.'.js');
					}
					else # this is the actual closure file
					{
						\app\GlobalEvent::fire('http:attributes', ['X-SourceMap' => $target.'.min.js.map']);
						$output = \file_get_contents($absolute_script_dir.'/closure/'.$target.'.min.js');
					}
				}
				else # standard mode
				{
					if ( ! isset($script_config['targets'][$target]))
					{
						throw new \app\Exception_NotFound
							("Missing target [$target] in scripts, for theme [$theme].");
					}

					$target_files = $script_config['common'];

					// merge target files to common files; preserving order
					foreach ($script_config['targets'][$target] as $target_file)
					{
						$target_files[] = $target_file;
					}

					// combine all files; if necesary
					$output = '';
					foreach ($target_files as $file)
					{
						// this header is included for easier development
						$no_path_file = \preg_replace('#(.*/)#', '', $file);
						$output .= PHP_EOL.'// '.\str_repeat('-', 77).PHP_EOL;
						$output .= '// '.$no_path_file.'.js'.PHP_EOL.PHP_EOL;

						$output 
							.= \file_get_contents
									(
										$absolute_script_dir.$script_config['script.root'].$file.'.js'
									)
							. PHP_EOL.PHP_EOL;
					}
				}

				// compute bootstrap
				$bootstrap_config = \app\CFS::config('ibidem/js-bootstrap');
				if ( ! empty($bootstrap_config))
				{
					$bootstrap = "// application data\nvar ibidem = {\n\t";
					$bootstrap .= \app\Collection::implode
						(
							",\n\t", 
							$bootstrap_config, 
							function ($key, $func) 
							{
								return '"'.$key.'": '.($func());
							}
						);
					$bootstrap .= "\n};\n\n";
				}

				// $this->contents($bootstrap.$output);
				$this->contents($output);
			}
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
	static function script_config($theme)
	{	
		$settings = \app\CFS::config('ibidem/themes');
		$env_config = include DOCROOT.'environment'.EXT;
		$env_is_set = isset($env_config['themes']) && isset($env_config['themes'][$theme]);

		if ($env_is_set)
		{
			$theme_path = $env_config['themes'][$theme].DIRECTORY_SEPARATOR;
		}
		else # env not set
		{
			$theme_path = \app\CFS::dir
				(
					$settings['themes.dir'].DIRECTORY_SEPARATOR
						. $theme.DIRECTORY_SEPARATOR
				);
		}
		
		$theme_config_file = $theme_path.$settings['themes.config'].EXT;

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
		$absolute_script_dir = $script_dir;

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
	static function style_config($theme, $style)
	{
		$settings = \app\CFS::config('ibidem/themes');

		$env_config = include DOCROOT.'environment'.EXT;
		$env_is_set = isset($env_config['themes']) && isset($env_config['themes'][$theme]);

		if ($env_is_set)
		{
			$theme_path = $env_config['themes'][$theme].DIRECTORY_SEPARATOR;
		}
		else # env not set
		{
			$theme_path = \app\CFS::dir
				(
					$settings['themes.dir'].DIRECTORY_SEPARATOR
						. $theme.DIRECTORY_SEPARATOR
				);
		}
		
		$theme_config_file = $theme_path.$settings['themes.config'].EXT;

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
		$absolute_style_dir = $style_dir;

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
	function relay_config(array $relay)
	{
		$this->relay = $relay;
		return $this;
	}
	
} # class
