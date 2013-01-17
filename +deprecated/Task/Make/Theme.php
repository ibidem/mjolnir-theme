<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Theme extends \app\Instantiatable implements \mjolnir\types\Task
{
	use \app\Trait_Task;

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
			. "\t\t'script.root' => '',".PHP_EOL
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
	 * ...
	 */
	function run()
	{
		\app\Task::consolewriter($this->writer);

		$theme = $this->get('theme', false);
		$namespace = \ltrim($this->get('namespace', ''), '\\');
		$forced = $this->get('forced', false);

		$modules = \app\CFS::system_modules();
		$namespaces = \array_flip($modules);

		// module path exists?
		if ( ! isset($namespaces[$namespace]) || ! \file_exists($namespaces[$namespace]))
		{
			$this->writer
				->printf('error', 'Module ['.$namespace.'] doesn\'t exist; you can use make:module to create it')->eol();
			return;
		}

		$ds = DIRECTORY_SEPARATOR;

		// load configuration
		$settings = \app\CFS::config('mjolnir/themes');

		// module exists
		$module_path = $namespaces[$namespace].$ds;
		$theme_path = $module_path
			. \mjolnir\cfs\CFSCompatible::APPDIR.$ds
			. $settings['themes.dir'].$ds
			. $theme.$ds;

		if (\file_exists($theme_path) && ! $forced)
		{
			$this->writer
				->printf('error', 'Theme ['.$theme.'] already exist; use --forced to overwrite.')->eol();
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

		// themes/$theme/Scripts/!config
		\file_put_contents
			(
				$theme_path.$settings['script.dir.default'].$ds.$settings['script.config'].EXT,
				self::script_config()
			);

		$this->writer->printf('status', 'Success', 'Theme created.')->eol();
	}

} # class
