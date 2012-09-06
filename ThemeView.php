<?php namespace ibidem\theme;

/**
 * @package    ibidem
 * @category   Themes
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeView extends \app\Instantiatable
	implements \ibidem\types\ErrorView
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
	 * @var \ibidem\types\Layer
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
		$config = \app\CFS::config('ibidem/themes');
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
				if (\app\CFS::config('ibidem/base')['development'])
				{
					echo 'Critical failure: '.$e->getMessage();
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
			return 'Missing error handling for ['.$exception.']';
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
		$settings = \app\CFS::config('ibidem/themes');
		
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
				throw new \app\Exception_NotFound('['.$this->target.'] is not a valid target.');
			}

			$files = $config['targets'][$this->target];
		}
		else if ($this->errortarget !== null)
		{
			if ( ! isset($config['targets'][$this->errortarget]))
			{
				throw new \app\Exception_NotFound('['.$this->errortarget.'] is not a valid error for the theme.');
			}

			$files = $config['targets'][$this->errortarget];
			
			$this->target = $this->errortarget;
		}
		else # both errortarget and target are null
		{
			throw new \app\Exception_NotApplicable('Target or Error Target is required. None provided.');
		}
		
		if (empty($files))
		{
			throw new \app\Exception_NotFound
				("Missing view files for [$this->target]");
		}
		
		$file = $this->base_path.\array_pop($files).EXT;
		$view_file = $file;
		
		if ( ! $view_file)
		{
			throw new \app\Exception_NotFound
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
				throw new \app\Exception_NotFound
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
		
		if (isset($style_config['targets'][$this->target]))
		{
			$url = \app\URL::route('\ibidem\theme\Layer_Theme::style')
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
		
		if (isset($script_config['targets'][$this->target]))
		{
			\app\GlobalEvent::fire
				(
					'webpage:script', 
					\app\URL::href
						(
							'\ibidem\theme\Layer_Theme::jsbootstrap',
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
							'\ibidem\theme\Layer_Theme::script',
							[
								'theme' => $this->theme,
								'style' => $this->style,
								'version' => $script_config['version'],
								'target' => $this->target
							]
						)
				);
		}
		
		return $base_file->render();
	}
	
	/**
	 * @param \ibidem\types\Layer layer
	 * @return \app\ThemeView
	 */
	function layer(\ibidem\types\Layer $layer)
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
			->variable('context', $this->context);
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
				new \app\Exception_NotApplicable
					('Casting to string not allowed for Theme Views.'),
				true # no throw
			);
	}

} # class
