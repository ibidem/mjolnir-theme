<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Task
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Theme extends \app\Task_Base
{
	/**
	 * Execute task.
	 */
	function run()
	{
		\app\Task::consolewriter($this->writer);

		$path = $this->get('path', false);
		$forced = $this->get('forced', false);

		// theme exists?
		$themepath = \app\Env::key('sys.path').$path;
		if (\file_exists($themepath) && ! $forced)
		{
			$this->writer
				->printf('error', "The theme [$path] already exists.")->eol()
				->printf('status', 'Help', 'Use --forced to overwrite existsing files.')->eol();
			return;
		}

		$ds = DIRECTORY_SEPARATOR;

		// create the directory structure
		\file_exists($themepath) or \mkdir($themepath, 0777, true); # base dir
		$scriptsdir = $themepath.$ds.'+scripts';
		\file_exists($scriptsdir) or \mkdir($scriptsdir, 0777, true); # scripts dir
		$scriptsroot = $scriptsdir.$ds.'root';
		\file_exists($scriptsroot) or \mkdir($scriptsroot, 0777, true); # scripts root
		$scriptssrc = $scriptsdir.$ds.'src';
		\file_exists($scriptssrc) or \mkdir($scriptssrc, 0777, true); # scripts src

		\file_put_contents($scriptssrc.$ds.'.gitkeep', '');
		\file_put_contents($scriptsroot.$ds.'.gitignore', "*\n!.gitignore");
		\file_put_contents($scriptsdir.$ds.'.gitignore', "library/*");

		\file_put_contents($scriptsdir.$ds.'+scripts.php', $this->scripts_config());
		\file_put_contents($scriptsdir.$ds.'+compile.rb', $this->scripts_compile());
		\file_put_contents($scriptsdir.$ds.'+start.rb', $this->scripts_watch());
		\file_put_contents($themepath.$ds.'+theme.php', $this->theme_config());

		$this->writer->writef(' Theme created.')->eol();
	}

	/**
	 * ...
	 */
	protected function scripts_config()
	{
		return
			  '<?php return array'.PHP_EOL
			. "\t(".PHP_EOL
			. "\t\t'version' => '1.0.0',".PHP_EOL
			. "\t\t'sources' => 'src/',".PHP_EOL
			. "\t\t'root' => 'root/',".PHP_EOL
			. "\t\t'mode' => 'targeted',".PHP_EOL
			. PHP_EOL
			. "\t# Complete mode".PHP_EOL
			. PHP_EOL
			. "\t\t'complete-mapping' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t// empty".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. PHP_EOL
			. "\t# Targeted mode".PHP_EOL
			. PHP_EOL
			. "\t\t'targeted-common' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t// empty".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. PHP_EOL
			. "\t\t'targeted-mapping' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t// empty".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. PHP_EOL
			. "\t); # config".PHP_EOL
			;
	}

	/**
	 * ...
	 */
	protected function scripts_compile()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. "Nyx.new.compile_scripts".PHP_EOL
			;
	}

	/**
	 * ...
	 */
	protected function scripts_watch()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. "Nyx.new.watch_scripts".PHP_EOL
			;
	}

	/**
	 * ...
	 */
	protected function theme_config()
	{
		return
			  '<?php return array'.PHP_EOL
			. "\t(".PHP_EOL
			. "\t\t'version' => '1.0.0',".PHP_EOL
			. PHP_EOL
			. "\t\t// configure theme drivers".PHP_EOL
			. "\t\t'loaders' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t'javascript' => null,".PHP_EOL
			. "\t\t\t\t'bootstrap' => null,".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. PHP_EOL
			. "\t\t// target-to-file mapping".PHP_EOL
			. "\t\t'mapping' => array".PHP_EOL
			. "\t\t\t(".PHP_EOL
			. "\t\t\t\t// empty".PHP_EOL
			. "\t\t\t),".PHP_EOL
			. PHP_EOL
			. "\t); # config".PHP_EOL
			;
	}

} # class
