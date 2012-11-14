<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Themes
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeView extends \app\Instantiatable
	implements \mjolnir\types\ErrorView
{
	/**
	 * @var string
	 */
	protected $theme;

	/**
	 * @var string
	 */
	protected $style;

	/**
	 * @var string
	 */
	protected $target;

	/**
	 * @var string
	 */
	protected $errortarget;

	/**
	 * @var string
	 */
	protected $control;

	/**
	 * @var string
	 */
	protected $context;

	/**
	 * @var \Exception
	 */
	protected $exception;

	/**
	 * @var \mjolnir\types\Layer
	 */
	protected $layer;

	/**
	 * @var array or null
	 */
	protected $errors;

	/**
	 * @var string theme path
	 */
	protected $base_path;

	/**
	 * @return \app\ThemeView
	 */
	static function instance()
	{
		$instance = parent::instance();
		$config = \app\CFS::config('mjolnir/themes');
		$instance->theme = $config['theme.default'];
		$instance->style = $config['style.default'];

		// register theme as error view
		\app\GlobalEvent::fire('webpage:errorview', $instance);

		return $instance;
	}

	/**
	 * The error page will be returned or null if the exception can't be
	 * handled by the view; to allow for multi-view system to handle complex
	 * exceptions.
	 *
	 * @return string or null
	 */
	function errorpage(\Exception $e)
	{		
		$config = $this->load_configuration();

		if (\is_a($e, '\app\Exception'))
		{
			$exception = \preg_replace('#.*\Exception(_)?#', '', \get_class($e));
			if (empty($exception))
			{
				$exception = 'Unknown';
			}
		}
		else # other
		{
			$exception = 'Unknown';
		}

		if (isset($config['targets']['exception-'.$exception]))
		{
			$this->errortarget = 'exception-'.$exception;
			$this->target = null;
			$context_class = '\app\Context_Exception_'.$exception;
			$this->context = $context_class::instance();

			try
			{
				return $this->exception($e)->render();
			}
			catch(\Exception $e)
			{
				if (\app\CFS::config('mjolnir/base')['development'])
				{
					echo 'Critical Theme failure: '.$e->getMessage();
					echo 'Critical Theme failures are caused by error pages with errors.';
					echo '99% of the time this is because you\'ve included a template with the error as the template for the error pages.';
					if (\app\Layer::find('http'))
					{
						echo '<pre>';
					}
					echo "\n";
					echo \str_replace(DOCROOT, '', $e->getTraceAsString());
				}
				else # non development
				{
					echo 'An unknown has occured in the theme system.';
				}

				exit(1);
			}
		}
		else # no handling
		{
			if (\app\CFS::config('mjolnir/base')['development'])
			{
				return 'Missing error handling for ['.$exception.'] for theme ['.$this->theme.']';
			}
			else
			{
				return 'Theme files have been corrupted. Terminating.';
			}
		}
	}

	/**
	 * @return \app\ThemeView
	 */
	function exception(\Exception $exception)
	{		
		$this->exception = $exception;

		return $this;
	}

	/**
	 * @param string theme name
	 * @return \app\ThemeView
	 */
	function theme($theme)
	{
		$this->theme = $theme;
		return $this;
	}

	/**
	 * @param string style name
	 * @return \app\ThemeView
	 */
	function style($style)
	{
		$this->style = $style;
		return $this;
	}

	/**
	 * Target should usually be equivalent to the default route name.
	 *
	 * @param string target name
	 * @return \app\ThemeView
	 */
	function target($target)
	{
		$this->target = $target;
		$this->errortarget = null;

		return $this;
	}

	/**
	 * @param type $error
	 * @return \app\ThemeView
	 */
	function errortarget($error)
	{
		$this->target = null;
		$this->errortarget = $error;

		return $this;
	}

	/**
	 * Themes are dynamic. Binding variables is thus inneficient and potentially
	 * error prone since the theme file won't necesarily use those variables, or
	 * even if one theme file uses them another may not.
	 *
	 * You need to pass an object and the theme needs to grab it's values from
	 * methods. There is no abstraction between the passed object and the theme
	 * files since it's assumed the theme files were designed specifically to be
	 * coupled to the object, and vise versa.
	 *
	 * @param mixed context object
	 * @return \app\ThemeView
	 */
	function context($context)
	{
		$this->context = $context;
		return $this;
	}

	/**
	 * @param array or null errors
	 * @return \app\ThemeView $this
	 */
	function errors(array & $errors = null)
	{
		$this->errors = & $errors;
		return $this;
	}

	/**
	 * Typically this would be the controller.
	 *
	 * @param mixed control object
	 * @return \app\ThemeView
	 */
	function control($control)
	{
		$this->control = $control;
		return $this;
	}

	/**
	 * @return array
	 */
	function load_configuration()
	{
		$settings = \app\CFS::config('mjolnir/themes');

		$env_config = include DOCROOT.'environment'.EXT;
		$env_is_set = isset($env_config['themes']) && isset($env_config['themes'][$this->theme]);

		if ($env_is_set)
		{
			$this->base_path = $env_config['themes'][$this->theme].DIRECTORY_SEPARATOR;
		}
		else # env is not set
		{
			$this->base_path = \app\CFS::dir
				(
					$settings['themes.dir'].DIRECTORY_SEPARATOR
						. $this->theme.DIRECTORY_SEPARATOR
				);
		}

		// load theme configuration
		return include $this->base_path.$settings['themes.config'].EXT;
	}

	/**
	 * @return string
	 */
	function render()
	{
		$config = $this->load_configuration();

		if ($this->target !== null)
		{
			if ( ! isset($config['targets'][$this->target]))
			{
				throw new \app\Exception('['.$this->target.'] is not a valid target.');
			}

			$files = $config['targets'][$this->target];
		}
		else if ($this->errortarget !== null)
		{
			if ( ! isset($config['targets'][$this->errortarget]))
			{
				throw new \app\Exception('['.$this->errortarget.'] is not a valid error for the theme.');
			}

			$files = $config['targets'][$this->errortarget];

			$this->target = $this->errortarget;
		}
		else # both errortarget and target are null
		{
			throw new \app\Exception('Target or Error Target is required. None provided.');
		}

		if (empty($files))
		{
			throw new \app\Exception
				("Missing view files for [$this->target]");
		}

		$file = $this->base_path.\array_pop($files).EXT;
		$view_file = $file;

		if ( ! $view_file)
		{
			throw new \app\Exception
				("Missing [$file].");
		}

		$base_file = \app\View::instance()
			->file_path($view_file)
			->variable('context', $this->context)
			->variable('control', $this->control)
			->variable('errors', $this->errors)
			->variable('exception', $this->exception)
			->variable('theme', $this);

		$files = \array_reverse($files);
		foreach ($files as $file)
		{
			$view_file = $this->base_path.$file.EXT;

			if ( ! $view_file)
			{
				throw new \app\Exception
					("Missing [{$this->base_path}$file].");
			}

			$base_file = \app\View::instance()
				->file_path($view_file, '')
				->variable('context', $this->context)
				->variable('control', $this->control)
				->variable('errors', $this->errors)
				->variable('theme', $this)
				->variable('view', $base_file);
		}

		// send styles
		$style_config = \app\Layer_Theme::style_config($this->theme, $this->style);

		if (isset($style_config['complete-mode']) && $style_config['complete-mode'])
		{
			$url = \app\URL::route('\mjolnir\theme\Layer_Theme::complete-style')
				->url
					(
						[
							'theme' => $this->theme,
							'style' => $this->style,
							'version' => $style_config['version'],
						]
					);

			\app\GlobalEvent::fire('webpage:style', $url);
		}
		else if (isset($style_config['targets'][$this->target]))
		{
			$url = \app\URL::route('\mjolnir\theme\Layer_Theme::style')
				->url
					(
						[
							'theme' => $this->theme,
							'style' => $this->style,
							'version' => $style_config['version'],
							'target' => $this->target
						]
					);

			\app\GlobalEvent::fire('webpage:style', $url);
		}

		// send script
		$script_config = \app\Layer_Theme::script_config($this->theme);

		if (isset($script_config['complete-mode']) && $script_config['complete-mode'])
		{
			if (isset($script_config['preload']))
			{
				foreach ($script_config['preload'] as $script)
				{
					\app\GlobalEvent::fire('webpage:script', $script);
				}
			}

			\app\GlobalEvent::fire
				(
					'webpage:script',
					\app\URL::href
						(
							'\mjolnir\theme\Layer_Theme::js-bootstrap',
							[
								'theme' => $this->theme,
								'style' => $this->style,
								'version' => $script_config['version'],
							]
						)
				);

			$url = \app\URL::route('\mjolnir\theme\Layer_Theme::complete-script')
				->url
					(
						[
							'theme' => $this->theme,
							'style' => $this->style,
							'version' => $script_config['version'],
						]
					);

			\app\GlobalEvent::fire('webpage:script', $url);

			// retrieve direct load scripts
			$direct_load = [];
			foreach ($script_config['complete-script'] as $script)
			{
				// is it an url?
				if (\preg_match('#(^[a-z]+:\/\/|^\/\/).*$#', $script))
				{
					$direct_load[] = $script;
				}
			}

			foreach ($direct_load as $script)
			{
				\app\GlobalEvent::fire('webpage:script', $script);
			}
		}
		else if (isset($script_config['targets'][$this->target]))
		{
			if (isset($script_config['preload']))
			{
				foreach ($script_config['preload'] as $script)
				{
					\app\GlobalEvent::fire('webpage:script', $script);
				}
			}

			\app\GlobalEvent::fire
				(
					'webpage:script',
					\app\URL::href
						(
							'\mjolnir\theme\Layer_Theme::js-bootstrap',
							[
								'theme' => $this->theme,
								'style' => $this->style,
								'version' => $script_config['version'],
							]
						)
				);

			\app\GlobalEvent::fire
				(
					'webpage:script',
					\app\URL::href
						(
							'\mjolnir\theme\Layer_Theme::script',
							[
								'theme' => $this->theme,
								'style' => $this->style,
								'version' => $script_config['version'],
								'target' => $this->target
							]
						)
				);

			// retrieve direct load scripts
			$direct_load = [];
			if (isset($script_config['common']))
			{
				foreach ($script_config['common'] as $script)
				{
					// is it an url?
					if (\preg_match('#(^[a-z]+:\/\/|^\/\/).*$#', $script))
					{
						$direct_load[] = $script;
					}
				}
			}

			foreach ($script_config['targets'][$this->target] as $script)
			{
				// is it an url?
				if (\preg_match('#(^[a-z]+:\/\/|^\/\/).*$#', $script))
				{
					$direct_load[] = $script;
				}
			}

			foreach ($direct_load as $script)
			{
				\app\GlobalEvent::fire('webpage:script', $script);
			}
		}

		return $base_file->render();
	}

	/**
	 * @param \mjolnir\types\Layer layer
	 * @return \app\ThemeView
	 */
	function layer(\mjolnir\types\Layer $layer)
	{
		$this->layer = $layer;
		return $this;
	}

	/**
	 * @return \app\View
	 */
	function partial($path)
	{
		return \app\View::instance()
			->file_path($this->base_path.$path.EXT)
			->variable('control', $this->control)
			->variable('context', $this->context)
			->variable('errors', $this->errors)
			->variable('theme', $this);
	}

	/**
	 * Shorthand for including a piece of code into the head of the page.
	 *
	 * @return \app\ThemeHeadInclude
	 */
	function headinclude($path)
	{
		return \app\ThemeHeadInclude::instance()
			->file_path($this->base_path.$path.EXT)
			->variable('control', $this->control)
			->variable('context', $this->context)
			->variable('errors', $this->errors)
			->variable('theme', $this);
	}

	/**
	 * Shorthand for including a piece of code into the head of the page.
	 *
	 * @return \app\ThemeFooterInclude
	 */
	function footerinclude($path)
	{
		return \app\ThemeFooterInclude::instance()
			->file_path($this->base_path.$path.EXT)
			->variable('control', $this->control)
			->variable('context', $this->context)
			->variable('errors', $this->errors)
			->variable('theme', $this);
	}

	/**
	 * @return string URL relative to src folder in scripts
	 */
	function cast_style($src)
	{
		$params = array
			(
				'theme'   => $this->theme,
				'style'   => $this->style,
				'version' => '1.0',
				'target'  => $src
			);

		return \app\URL::href('\mjolnir\theme\Layer_Theme::style-src', $params);
	}

	/**
	 * @return string URL relative to src folder in scripts
	 */
	function cast_script($src)
	{
		$params = array
			(
				'theme'  => $this->theme,
				'style'  => $this->style,
				'version' => '1.0',
				'target' => $src
			);

		return \app\URL::href('\mjolnir\theme\Layer_Theme::script-src', $params);
	}

	/**
	 * Adds script to theme, from the theme itself. Note this won't get compiled
	 * or anything, it's simply conditionally added. Recomended only if used
	 * with external scripts.
	 *
	 * Since external scripts are assumed (90% of the time) if you are including
	 * a internal script you must gurantee the validity of the URL yourself.
	 */
	function script($script)
	{
		\app\GlobalEvent::fire('webpage:script', $script);
	}

	/**
	 * @deprecated use render always; so exceptions will work properly
	 */
	final function __toString()
	{
		// views may contain logic, by allowing __toString not only does
		// Exception handling become unnecesarily complicated because of how
		// this special method can't throw exceptions, it also ruins the entire
		// stack by throwing the exception in a completely undefined manner,
		// ie. whenever it decides to convert to a string. It's not worth it.
		\app\Layer::get_top()->exception
			(
				new \app\Exception('Casting to string not allowed for Theme Views.'),
				true # no throw
			);
	}

} # class
