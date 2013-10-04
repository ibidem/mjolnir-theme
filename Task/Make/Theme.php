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

		$dirs = array
			(
				$themepath,
				$themepath.$ds.'+scripts',
				$themepath.$ds.'+scripts'.$ds.'bin',
				$themepath.$ds.'+scripts'.$ds.'bin'.$ds.'etc',
				$themepath.$ds.'+scripts'.$ds.'bin'.$ds.'tmp',
				$themepath.$ds.'+scripts'.$ds.'root',
				$themepath.$ds.'+scripts'.$ds.'src',

			);

		foreach ($dirs as $dir)
		{
			\app\Filesystem::makedir($dir);
		}

		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'bin'.$ds.'etc'.$ds.'.gitkeep', '');
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'bin'.$ds.'tmp'.$ds.'.gitignore', $this->bin_ignore_file());
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'src'.$ds.'.gitkeep', '');
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'root'.$ds.'.gitignore', "*\n!.gitignore");
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'+scripts.php', $this->scripts_config());
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'+compile.rb', $this->scripts_compile());
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'watch.rb', $this->scripts_watch());
		\app\Filesystem::puts($themepath.$ds.'+scripts'.$ds.'nyx.json', $this->sample_nyx_config());
		\app\Filesystem::puts($themepath.$ds.'+theme.php', $this->theme_config());

		$this->writer->writef(' Theme created.')->eol();
	}

	/**
	 * @return string
	 */
	protected function sample_nyx_config()
	{
		return
			  "{".PHP_EOL
			. "\t\"interface\": \"1.1.0\",".PHP_EOL
			. PHP_EOL
			. "\t\"cores\": [".PHP_EOL
			. "\t\t{".PHP_EOL
			. "\t\t\t\"repo\": \"https://github.com/alademann/sass-bootstrap.git\",".PHP_EOL
			. "\t\t\t\"path\": \"src/vendor/twbs\",".PHP_EOL
			. "\t\t\t\"version\": \"v3.0.0_sass\",".PHP_EOL
			. "\t\t\t\"keep\": [\"js\", \"README.md\", \"LICENSE\"]".PHP_EOL
			. "\t\t}".PHP_EOL
			. "\t]".PHP_EOL
			. PHP_EOL
			. "}".PHP_EOL
			;
	}

	/**
	 * @return string
	 */
	protected function bin_ignore_file()
	{
		return
			  '# build process may require extra tools to be downloaded, temporary files or'.PHP_EOL
			. '# various other junk; any scripts should use bin/tmp for this. Configuration'.PHP_EOL
			. '# files should be placed directly in bin or variations such as bin/etc,'.PHP_EOL
			. '# bin/settings, etc (in the mjolnir library bin/etc is generally prefered for'.PHP_EOL
			. '# configuration files)'.PHP_EOL
			. '*'.PHP_EOL
			. '!.gitignore'.PHP_EOL
			;
	}

	/**
	 * @return string
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
	 * @return string
	 */
	protected function scripts_compile()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. 'basedir = File.expand_path(File.dirname(__FILE__))'.PHP_EOL
			. 'Dir.chdir basedir'.PHP_EOL
			. "Nyx.new.compile_scripts".PHP_EOL
			;
	}

	/**
	 * @return string
	 */
	protected function scripts_watch()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. 'basedir = File.expand_path(File.dirname(__FILE__))'.PHP_EOL
			. 'Dir.chdir basedir'.PHP_EOL
			. "Nyx.new.watch_scripts".PHP_EOL
			;
	}

	/**
	 * @return string
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
			. "\t\t\t\t'bootstrap' => null,".PHP_EOL
			. "\t\t\t\t'javascript' => null,".PHP_EOL
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
