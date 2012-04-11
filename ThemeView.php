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
	 * @var \ibidem\types\Layer
	 */
	protected $layer;
	
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
	 * Thus, you need to pass an object and the theme needs to grab it's values
	 * from methods. Typically you might pass the controller object but any
	 * object will do. There is no abstraction between the passed object and the
	 * theme files since it's assumed the theme files were designed specifically
	 * to be coupled to the object, and vise versa.
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
	 * @return string 
	 */
	public function render()
	{
		$settings = \app\CFS::config('ibidem/themes');
		
		$base_path = $settings['themes.dir'].DIRECTORY_SEPARATOR
			. $this->theme.DIRECTORY_SEPARATOR;
		
		// load theme configuration
		$config = include \app\CFS::file
			(
				$base_path.$settings['themes.config']
			);

		if ( ! isset($config['targets'][$this->target]))
		{
			throw new \app\Exception_NotFound('Invalid target specified.');
		}
		
		$files = $config['targets'][$this->target];
		
		if (empty($files))
		{
			throw new \app\Exception_NotFound
				("Missing view files for [$this->target]");
		}
		
		$file = $base_path.\array_pop($files);
		$view_file = \app\CFS::file($file);
		
		if ( ! $view_file)
		{
			throw new \app\Exception_NotFound
				("Missing [$file].");
		}
		
		$base_file = \app\View::instance()
			->file_path($view_file)
			->variable('context', $this->context);
		
		$files = \array_reverse($files);
		foreach ($files as $file)
		{
			$view_file = \app\CFS::file($base_path.$file);
			
			if ( ! $view_file)
			{
				throw new \app\Exception_NotFound
					("Missing [$base_path$file].");
			}
			
			$base_file = \app\View::instance()
				->file_path($view_file, '')
				->variable('context', $this->context)
				->variable('view', $base_file);
		}
		
		// send styles
		$style_config = \app\Layer_Theme::style_config($this->theme, $this->style);
		$url = \app\Relay::route(__NAMESPACE__.'\Layer_ThemeResource::style')
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
		
		// send script
		$script_config = \app\Layer_Theme::script_config($this->theme);
		$url = \app\Relay::route(__NAMESPACE__.'\Layer_ThemeResource::script')
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
				\app\Exception_NotApplicable::instance
					('Casting to string not allowed for Views.')
			);
	}

} # class
