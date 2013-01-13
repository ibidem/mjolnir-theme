<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Themes
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Layer_Theme extends \app\Layer
	implements \mjolnir\types\RelayCompatible
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
	 * Save output to file, to void future processing.
	 */
	function save_theme_file( & $output)
	{
		if (\app\CFS::config('mjolnir/base')['static-theme'])
		{
			$uri = \app\Layer_HTTP::detect_uri();

			$path = PUBDIR.\ltrim($uri, '/');

			\preg_match('#(?<dir>^.*)/(?<file>[^/]+)$#', $path, $file_info);

			\file_exists($file_info['dir']) or \mkdir($file_info['dir'], 0777, true);
			\file_put_contents($path, $output);
		}
	}

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
			$script = $params->get('style');

			# we don't process the version; the version is only for the
			# useragent to allow for easy cache busting

			$settings = \app\CFS::config('mjolnir/themes');
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
				$global_config = \app\CFS::config('mjolnir/themes');
				$theme_config['scripts'] = isset($theme_config['scripts']) ? $theme_config['scripts'] : $global_config['script.dir.default'];
				$theme_config['styles'] = isset($theme_config['styles']) ? $theme_config['styles'] : $global_config['style.dir.default'];
			}
			else # no theme configuration
			{
				throw new \app\Exception
					(
						'Missing theme configuration for ['.$theme.'].'
					);
			}

			if ($mode === 'js-bootstrap')
			{
				// expires headers
				\app\GlobalEvent::fire('http:expires', \strtotime('+30 days'));

				// mime type
				\app\GlobalEvent::fire('http:content-type', 'text/javascript');

				// compute bootstrap
				$bootstrap_config = \app\CFS::config('mjolnir/js-bootstrap');
				$bootstrap = '';

				if ( ! empty($bootstrap_config))
				{
					$bootstrap = "// application data\nvar mjolnir = {\n\t";
					$bootstrap .= \app\Arr::implode
						(
							",\n\t",
							$bootstrap_config,
							function ($key, $func)
							{
								try
								{
									return '"'.$key.'": '.($func());
								}
								catch (\Exception $e)
								{
									if (\app\CFS::config('mjolnir/base')['development'])
									{
										\mjolnir\log('Javascript', 'Critical failure on key ['.$key.']. '.$e->getMessage(), 'javascript/');
										die('console.log("Critical failure on key ['.$key.']. '.$e->getMessage().'");');
									}
									else # public
									{
										die('console.log("Critical failure on key ['.$key.']");');
									}
								}
							}
						);
					$bootstrap .= "\n};\n\n";
				}

				// $this->contents($bootstrap.$output);
				$this->contents($bootstrap);

				return;
			}
			else if (false === \in_array($mode, ['script', 'script-src', 'complete-script'])) // ($mode !== 'script')
			{
				$style_dir = $theme_path.$theme_config['styles'].DIRECTORY_SEPARATOR
						. $script.DIRECTORY_SEPARATOR;

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
					throw new \app\Exception
						(
							'Missing style dir for theme ['.$theme.'].'
						);
				}

				if ($style_config_file)
				{
					$style_config = include $style_config_file;
				}
				else # no style configuration
				{
					throw new \app\Exception
						(
							'Missing style configuration for theme ['.$theme.'].'
						);
				}

				// expires headers
				\app\GlobalEvent::fire('http:expires', \strtotime('+30 days'));

				if ($mode === 'complete-style')
				{
					try
					{
						\app\GlobalEvent::fire('http:content-type', 'text/css');

						if ( ! isset($style_config['complete-style']))
						{
							throw new \app\Exception
								("Missing definition for [complete-style] in the style [$script] of theme [$theme].");
						}

						// combine all files; if necesary
						$output = '';
						foreach ($style_config['complete-style'] as $file)
						{
							$output .= \file_get_contents
								(
									$absolute_style_dir.$style_config['style.root'].$file.'.css'
								);
						}

						$this->save_theme_file($output);

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
				if ($mode === 'style')
				{
					try
					{
						$target = $params->get('target', null);

						\app\GlobalEvent::fire('http:content-type', 'text/css');

						if ($target !== null)
						{
							if ( ! isset($style_config['targets'][$target]))
							{
								throw new \app\Exception
									("Missing target [$target] in the style [$script] of theme [$theme].");
							}

							$target_files = $style_config['common'];

							// merge target files to common files; preserving order
							foreach ($style_config['targets'][$target] as $script)
							{
								$target_files[] = $script;
							}
						}
						else # complete mode
						{
							$target_files = $style_config['complete-style'];
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

						$this->save_theme_file($output);

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
				else if ($mode === 'style-src')
				{
					try
					{
						$target = \str_replace('\\', '/', $params->get('target'));

						// we don't allow parent references
						if (\strpos($target, '..') !== false)
						{
							throw new \app\Exception('Parent reference is forbidden.');
						}

						$file = $absolute_style_dir.$style_config['style.root'].'../src/'.$target.'.css';

						if (\file_exists($file))
						{
							$output = \file_get_contents($file);
							$this->save_theme_file($output);
						}
						else #
						{
							$output = '';
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
					$target = $params->get('target', 'complete-script');

					$script_dir = $theme_path.$theme_config['scripts'].DIRECTORY_SEPARATOR;

					// theme styles are static content dependent
					$absolute_script_dir = $script_dir;

					if ($absolute_script_dir)
					{
						$script_config_file = $absolute_script_dir.$settings['script.config'].EXT;
					}
					else # path not found
					{
						throw new \app\Exception
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
						throw new \app\Exception
							(
								'Missing script configuration.'
							);
					}

					// we don't allow parent references
					if (\strpos($target, '..') !== false)
					{
						throw new \app\Exception('Parent reference is forbidden.');
					}

					\app\GlobalEvent::fire('http:expires', strtotime('-1 day'));

					$file = $absolute_script_dir.$script_config['script.root'].'../closure/'.$target.'.min.js.map';

					$output = \file_get_contents($file);

					$this->save_theme_file($output);

					$this->contents($output);
				}
				else # $mode === 'resource'
				{
					try
					{
						$path = $params->get('path');

						// we don't allow parent references
						if (\strpos($path, '..') !== false)
						{
							throw new \app\Exception_NotAllowed('Parent path not allowed.');
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

						$output = \file_get_contents($file);

						$this->save_theme_file($output);

						$this->contents($output);
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
					throw new \app\Exception
						(
							'Missing script dir.'
						);
				}

				if ($script_config_file)
				{
					$script_config = include $script_config_file;
				}
				else # no script configuration
				{
					throw new \app\Exception
						(
							'Missing script configuration.'
						);
				}

				$target = $params->get('target');

				// mime type
				\app\GlobalEvent::fire('http:content-type', 'text/javascript');

				// check output mode
				if (isset($script_config['closure-mode']) && $script_config['closure-mode'] && ! \app\CFS::config('mjolnir/base')['disable']['closure-mode'])
				{
					$target = \str_replace('\\', '/', $target);

					if ($mode === 'script-src')
					{
						// this is mapped file from the closure
						$output = \file_get_contents($absolute_script_dir.'src/'.$target.'.js');
					}
					else if ($mode === 'complete-script')
					{

						\app\GlobalEvent::fire
							(
								'http:attributes',
								[
									'X-SourceMap' =>
										\preg_replace // non-relative urls won't work
											(
												'#.*/#',
												'',
												\app\URL::href
													(
														'\mjolnir\theme\Layer_Theme::complete-script-map',
														[
															'version' => $script_config['version'],
															'theme' => $theme,
															'style' => $style
														]
													)
											)
								]
							);

						$file_to_load = $absolute_script_dir.'/closure/complete-script.min.js';
						if (\file_exists($file_to_load))
						{
							$output = \file_get_contents($file_to_load);
						}
						else # file does not exist
						{
							$output = 'Missing complete script.';
						}
					}
					else # targetted closure file
					{
						\app\GlobalEvent::fire('http:attributes', ['X-SourceMap' => $target.'.min.js.map']);

						$file_to_load = $absolute_script_dir.'/closure/'.$target.'.min.js';
						if (\file_exists($file_to_load))
						{
							$output = \file_get_contents($file_to_load);
						}
						else # file does not exist
						{
							$output = 'Missing closure file for ['.$target.']. Potentially failed to compile.';
						}
					}
				}
				else # standard mode
				{
					if ($mode === 'complete-script')
					{
						if ( ! isset($script_config['complete-script']))
						{
							throw new \app\Exception
								("Missing [complete-script] definition in scripts, for theme [$theme].");
						}

						$target_files = [];
						foreach ($script_config['complete-script'] as $script)
						{
							if ( ! \preg_match('#(^[a-z]+:\/\/|^\/\/).*$#', $script))
							{
								if ( ! \in_array($script, $target_files))
								{
									$target_files[] = $script;
								}
							}
						}

						try
						{
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
						catch (\Exception $e)
						{
							if (\app\CFS::config('mjolnir/base')['development'])
							{
								echo $e->getMessage();
								die;
							}
						}
					}
					else # targetted mode
					{
						if ( ! isset($script_config['targets'][$target]))
						{
							throw new \app\Exception
								("Missing target [$target] in scripts, for theme [$theme].");
						}

						$target_files = [];
						foreach ($script_config['common'] as $script)
						{
							if ( ! \preg_match('#(^[a-z]+:\/\/|^\/\/).*$#', $script))
							{
								if ( ! \in_array($script, $target_files))
								{
									$target_files[] = $script;
								}
							}
						}

						// merge target files to common files; preserving order
						foreach ($script_config['targets'][$target] as $script)
						{
							if ( ! \preg_match('#(^[a-z]+:\/\/|^\/\/).*$#', $script))
							{
								if ( ! \in_array($script, $target_files))
								{
									$target_files[] = $script;
								}
							}
						}

						try
						{
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
						catch (\Exception $e)
						{
							if (\app\CFS::config('mjolnir/base')['development'])
							{
								echo $e->getMessage();
								die;
							}
						}
					}
				}

				$this->save_theme_file($output);

				$this->contents($output);
			}
		}
		catch (\Exception $exception)
		{
			$this->exception($exception);
		}
	}

	/**
	 * @param string theme
	 * @return array
	 */
	static function script_config($theme)
	{
		$settings = \app\CFS::config('mjolnir/themes');
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

			if ( ! isset($theme_config['scripts']))
			{
				$theme_config['scripts'] = \app\CFS::config('mjolnir/themes')['script.dir.default'];
			}
		}
		else # no theme configuration
		{
			throw new \app\Exception
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
			throw new \app\Exception
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
			throw new \app\Exception
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
		$settings = \app\CFS::config('mjolnir/themes');

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

			if ( ! isset($theme_config['styles']))
			{
				$theme_config['styles'] = \app\CFS::config('mjolnir/themes')['style.dir.default'];
			}
		}
		else # no theme configuration
		{
			throw new \app\Exception
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
			throw new \app\Exception
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
			throw new \app\Exception
				(
					'Missing style configuration.'
				);
		}
	}

	/**
	 * @param array relay configuration
	 * @return \mjolnir\theme\Layer_Theme
	 */
	function relay_config(array $relay)
	{
		$this->relay = $relay;
		return $this;
	}

} # class
