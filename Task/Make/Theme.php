<?php namespace ibidem\theme;

/**
 * @package    ibidem
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Theme extends \app\Task
{
	/**
	 * @param array settings 
	 * @return string
	 */
	private function theme_config($settings)
	{
		return
			  '<?php return array'.PHP_EOL
			. "\t(".PHP_EOL
			. "\t\t'scripts' => '{$settings['script.dir.default']}',".PHP_EOL
			. "\t\t'styles'  => '{$settings['style.dir.default']}',".PHP_EOL
			. PHP_EOL
			. "\t\t// mapping targets to files".PHP_EOL
			. "\t\t'targets' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t'example_target' => array".PHP_EOL
			. "\t\t\t\t\t(".PHP_EOL
			. "\t\t\t\t\t\t// Read As: some page is placed in the headers template,".PHP_EOL
			. "\t\t\t\t\t\t// which is itself placed in the site template".PHP_EOL
			. "\t\t\t\t\t\t'template_site', 'template_headers', 'some_page'".PHP_EOL
			. "\t\t\t\t\t),".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. "\t);".PHP_EOL
			. PHP_EOL
			;
	}
	
	/**
	 * @return string
	 */
	private function script_config()
	{
		return
			  '<?php return array'.PHP_EOL
			. "\t(".PHP_EOL
			. "\t\t'version' => '1.0', # used in cache busting; update as necesary".PHP_EOL
			. PHP_EOL
			. "\t\t// set the script.root to '' (empty string) when writing (entirely) just".PHP_EOL
			. "\t\t// plain old js files; and not compiling coffee scripts, etc".PHP_EOL
			. "\t\t'script.root' => 'Script.root'.DIRECTORY_SEPARATOR,".PHP_EOL
			. PHP_EOL
			. "\t\t// mapping targets to files".PHP_EOL
			. "\t\t'targets' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t'example_target' => array".PHP_EOL
			. "\t\t\t\t\t(".PHP_EOL
			. "\t\t\t\t\t\t// Read As: combine file1, file2, and file3 and output".PHP_EOL
			. "\t\t\t\t\t\t// as (theme) scripts for example_target".PHP_EOL
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
	private function coffee_start()
	{
		return
			  ':: change to directory (and partition) of the script'.PHP_EOL
			. 'cd /d %~dp0'.PHP_EOL
			. ':: run coffee compiler'.PHP_EOL
			. 'coffee -o Script.root/ -cw src/'.PHP_EOL
			. PHP_EOL
			;
	}
	
	/**
	 * Execute task.
	 */
	public function execute()
	{
		$theme = $this->config['theme'];
		$namespace = \ltrim($this->config['namespace'], '\\');
		$forced = $this->config['forced'];
		
		$modules = \app\CFS::get_modules();
		$namespaces = \array_flip($modules);

		// module path exists?
		if ( ! isset($namespaces[$namespace]) || ! \file_exists($namespaces[$namespace]))
		{
			$this->writer
				->error('Module ['.$namespace.'] doesn\'t exist; you can use make:module to create it')->eol();
			return;
		}
		
		$ds = DIRECTORY_SEPARATOR;
		
		// load configuration
		$settings = \app\CFS::config('ibidem/themes');
		
		// module exists
		$module_path = $namespaces[$namespace].$ds;
		$theme_path = $module_path
			. \ibidem\cfs\CFSCompatible::APPDIR.$ds
			. $settings['themes.dir'].$ds
			. $theme.$ds;
		
		if (\file_exists($theme_path) && ! $forced)
		{
			$this->writer
				->error('Theme ['.$theme.'] already exist; use --forced to overwrite.')->eol();
			return;
		}
		
		// themes/$theme
		\file_exists($theme_path) or \mkdir($theme_path, 0777, true);
		// themes/$theme/!config
		\file_put_contents
			(
				$theme_path.$settings['themes.config'].EXT, 
				self::theme_config($settings)
			);
		
		// themes/$theme/Styles
		$path = $theme_path.$settings['style.dir.default'];
		\file_exists($path) or \mkdir($path, 0777, true);
		
		## Scripts
		
		// themes/$theme/Scripts
		$path = $theme_path.$settings['script.dir.default'];
		\file_exists($path) or \mkdir($path, 0777, true);
		
		// themes/$theme/Scripts/src
		$path = $theme_path.$settings['script.dir.default'].$ds.'src';
		\file_exists($path) or \mkdir($path, 0777, true);
		
		// themes/$theme/Scripts/Script.root
		$path = $theme_path.$settings['script.dir.default'].$ds.'Script.root';
		\file_exists($path) or \mkdir($path, 0777, true);
		
		// themes/$theme/Scripts/!config
		\file_put_contents
			(
				$theme_path.$settings['script.dir.default'].$ds.$settings['script.config'].EXT, 
				self::script_config()
			);
		
		// themes/$theme/Scripts/!start.cmd
		\file_put_contents
			(
				$theme_path.$settings['script.dir.default'].$ds.'!start.cmd', 
				self::coffee_start()
			);
		
		$this->writer->status('Success', 'Theme created.')->eol();
	}
	
} # class
