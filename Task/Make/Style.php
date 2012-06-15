<?php namespace ibidem\theme;

/**
 * @package    ibidem
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Style extends \app\Task
{
	/**
	 * @return string
	 */
	private function style_config()
	{
		return
			  '<?php return array'.PHP_EOL
			. "\t(".PHP_EOL
			. "\t\t'version' => '1.0', # used in cache busting; update as necesary".PHP_EOL
			. PHP_EOL
			. "\t\t// set the style.root to '' (empty string) when writing (entirely) just".PHP_EOL
			. "\t\t// plain old css files; and not compiling sass scripts, etc".PHP_EOL
			. "\t\t'style.root' => 'root'.DIRECTORY_SEPARATOR,".PHP_EOL
			. PHP_EOL
			. "\t\t// mapping targets to files".PHP_EOL
			. "\t\t'targets' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t'example_target' => array".PHP_EOL
			. "\t\t\t\t\t(".PHP_EOL
			. "\t\t\t\t\t\t// Read As: combine file1, file2, and file3 and output".PHP_EOL
			. "\t\t\t\t\t\t// as (theme) style for example_target".PHP_EOL
			. "\t\t\t\t\t\t'file1', 'file2', 'file3'".PHP_EOL
			. "\t\t\t\t\t),".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. "\t);".PHP_EOL
			. PHP_EOL
			;
	}	
	
	/**
	 * @return string
	 */
	private function compass_start()
	{
		return
			  '# change to script dir'.PHP_EOL
			. 'Dir.chdir File.expand_path(File.dirname(__FILE__))'.PHP_EOL
			. '# run compass compiler'.PHP_EOL
			. 'Kernel.exec(\'compass watch -c +compass.rb\')'.PHP_EOL
			. PHP_EOL
			;
	}
	
	/**
	 * @return string
	 */
	private function compass_rb()
	{
		return
			  '# Require any additional compass plugins here.'.PHP_EOL
			. PHP_EOL
			. '# required ibidem configuration'.PHP_EOL
			. 'http_path = ""'.PHP_EOL
			. 'css_dir = "Style.root"'.PHP_EOL
			. 'images_dir = "Style.root/images"'.PHP_EOL
			. 'relative_assets = true'.PHP_EOL
			. PHP_EOL
			. '# Output style'.PHP_EOL
			. 'output_style = :compressed'.PHP_EOL
			. 'line_comments = false'.PHP_EOL
			. PHP_EOL
			. '# source directory'.PHP_EOL
			. 'sass_dir = "src"'.PHP_EOL
			. PHP_EOL
			;
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$theme = $this->config['theme'];
		$style = $this->config['style'];
		$forced = $this->config['forced'];
		
		$ds = DIRECTORY_SEPARATOR;
		
		// load settings
		$settings = \app\CFS::config('ibidem/themes');
		
		// find theme
		$theme_path = \app\CFS::dir($settings['themes.dir'].$ds.$theme);
		
		if ($theme_path === null)
		{
			$this->writer
				->error('Theme ['.$theme.'] does not exist; use make:theme to create it.')->eol();
			return;
		}
		
		// load theme configuration
		$theme_config = include $theme_path.$settings['themes.config'].EXT;
		
		$style_path = $theme_path.$theme_config['styles'].$ds.$style.$ds;
		
		if (\file_exists($style_path) && ! $forced)
		{
			$this->writer
				->error('Style ['.$style.'] already exist; use --forced to overwrite.')->eol();
			return;
		}
		
		// styles/$style
		\file_exists($style_path) or \mkdir($style_path, 0777, true);
		
		// styles/$style/src
		$path = $style_path.$ds.'src';
		\file_exists($path) or \mkdir($path, 0777, true);
		
		// styles/$style/Style.root
		$path = $style_path.$ds.'root';
		\file_exists($path) or \mkdir($path, 0777, true);
		
		// styles/$style/!config
		\file_put_contents
			(
				$style_path.$settings['style.config'].EXT, 
				self::style_config()
			);
		
		// styles/$style/!start.cmd
		\file_put_contents
			(
				$style_path.'+start.cmd', 
				self::compass_start()
			);
		
		// styles/$style/!compass.rb
		\file_put_contents
			(
				$style_path.'+compass.rb', 
				self::compass_rb()
			);
		
		$this->writer->status('Success', 'Style created.')->eol();
	}
	
	
} # class
