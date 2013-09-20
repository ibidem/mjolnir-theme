<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Task
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Make_Style extends \app\Task_Base
{
	/**
	 * Execute task.
	 */
	function run()
	{
		\app\Task::consolewriter($this->writer);

		$path = $this->get('path', false);
		$forced = $this->get('forced', false);

		// style exists?
		$stylepath = \app\Env::key('sys.path').$path;
		if (\file_exists($stylepath) && ! $forced)
		{
			$this->writer
				->printf('error', "The style [$stylepath] already exists.")->eol()
				->printf('status', 'Help', 'Use --forced to overwrite existsing files.')->eol();
			return;
		}

		$ds = DIRECTORY_SEPARATOR;

		// create the directory structure
		\app\Filesystem::makedir($stylepath);
		$styleroot = $stylepath.$ds.'root';
		\app\Filesystem::makedir($styleroot);
		$stylesrc = $stylepath.$ds.'src';
		\app\Filesystem::makedir($stylesrc);

		\file_put_contents($stylesrc.$ds.'.gitkeep', '');
		\file_put_contents($styleroot.$ds.'.gitignore', "*\n!.gitignore");
		\file_put_contents($stylepath.$ds.'.gitignore', "library/*");

		\file_put_contents($stylepath.$ds.'+style.php', $this->style_config());
		\file_put_contents($stylepath.$ds.'+compile.rb', $this->style_compile());
		\file_put_contents($stylepath.$ds.'+start.rb', $this->style_watch());

		$this->writer->writef(' Style created.')->eol();
	}

	/**
	 * ...
	 */
	protected function style_config()
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
	protected function style_compile()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. "Nyx.new.compile_style".PHP_EOL
			;
	}

	/**
	 * ...
	 */
	protected function style_watch()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. "Nyx.new.watch_style".PHP_EOL
			;
	}

} # class
