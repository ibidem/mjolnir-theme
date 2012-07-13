<?php namespace ibidem\theme;

/**
 * @package    ibidem
 * @category   Themes
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeView extends \app\Instantiatable
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
	protected $control;
	
	/**
	 * @var \ibidem\types\Layer
	 */
	protected $layer;
	
	/**
	 * @var array|null
	 */
	protected $errors;
	
	/**
	 * @var string theme path
	 */
	protected $base_path;
	
	/**
	 * @return \ibidem\theme\ThemeView 
	 */
	public static function instance()
	{
		$instance = parent::instance();
		$config = \app\CFS::config('ibidem/themes');
		$instance->theme = $config['theme.default'];
		$instance->style = $config['style.default'];
		
		
		
		return $instance;
	}
	
	/**
	 * @param string theme name
	 * @return \ibidem\theme\ThemeView
	 */
	public function theme($theme) 
	{
		$this->theme = $theme;
		return $this;
	}
	
	/**
	 * @param string style name
	 * @return \ibidem\theme\ThemeView
	 */
	public function style($style) 
	{
		$this->style = $style;
		return $this;
	}
	
	/**
	 * Target should usually be equivalent to the default route name.
	 * 
	 * @param string target name
	 * @return \ibidem\theme\ThemeView
	 */
	public function target($target) 
	{
		$this->target = $target;
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
	 * @return \ibidem\theme\ThemeView
	 */
	public function context($context)
	{
		$this->context = $context;
		return $this;
	}
	
	/**
	 * @param array|null errors
	 * @return \ibidem\theme\ThemeView $this
	 */
	public function errors(array & $errors = null)
	{
		$this->errors = & $errors;
		return $this;
	}
	
	/**
	 * Typically this would be the controller.
	 * 
	 * @param mixed control object
	 * @return \ibidem\theme\ThemeView 
	 */
	public function control($control)
	{
		$this->control = $control;
		return $this;
	}
	
	/**
	 * @return string 
	 */
	public function render()
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
		$config = include $this->base_path.$settings['themes.config'].EXT;

		if ( ! isset($config['targets'][$this->target]))
		{
			throw new \app\Exception_NotFound('['.$this->target.'] is not a valid target.');
		}
		
		$files = $config['targets'][$this->target];
		
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
			$url = \app\Relay::route('\ibidem\theme\Layer_Theme::style')
				->url
					(
						array
						(
							'theme' => $this->theme,
							'style' => $this->style,
							'version' => $style_config['version'],
							'target' => $this->target
						)
					);
			
			$this->layer->dispatch
				(
					\app\Event::instance()
						->subject(\ibidem\types\Event::css_style)
						->contents($url)
				);
		}
		
		// send script
		$script_config = \app\Layer_Theme::script_config($this->theme);
		
		if (isset($script_config['targets'][$this->target]))
		{
			$url = \app\Relay::route('\ibidem\theme\Layer_Theme::script')
				->url
					(
						array
						(
							'theme' => $this->theme,
							'style' => $this->style,
							'version' => $script_config['version'],
							'target' => $this->target
						)
					);
			$this->layer->dispatch
				(
					\app\Event::instance()
						->subject(\ibidem\types\Event::js_script)
						->contents($url)
				);
		}
		
		return $base_file->render();
	}
	
	/**
	 * @param \ibidem\types\Layer layer
	 * @return \ibidem\theme\ThemeView
	 */
	public function layer(\ibidem\types\Layer $layer)
	{
		$this->layer = $layer;
		return $this;
	}
	
	public function partial($path)
	{
		return \app\View::instance()
			->file_path($this->base_path.$path.EXT)
			->variable('control', $this->control)
			->variable('context', $this->context);
	}
	
	/**
	 * @deprecated use render always; so exceptions will work properly
	 */
	public final function __toString()
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
