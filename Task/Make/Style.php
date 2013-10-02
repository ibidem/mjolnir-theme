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

		$dirs = array
			(
				$stylepath,
				$stylepath.$ds.'bin',
				$stylepath.$ds.'bin'.$ds.'etc',
				$stylepath.$ds.'bin'.$ds.'etc'.$ds.'compass',
				$stylepath.$ds.'bin'.$ds.'tmp',
				$stylepath.$ds.'root',
				$stylepath.$ds.'src',
			);

		foreach ($dirs as $dir)
		{
			\app\Filesystem::makedir($dir);
		}

		\app\Filesystem::puts($stylepath.$ds.'src'.$ds.'.gitkeep', '');
		\app\Filesystem::puts($stylepath.$ds.'root'.$ds.'.gitignore', "*\n!.gitignore");
		\app\Filesystem::puts($stylepath.$ds.'bin'.$ds.'tmp'.$ds.'.gitignore', $this->bin_ignore_file());
		\app\Filesystem::puts($stylepath.$ds.'bin'.$ds.'etc'.$ds.'compass'.$ds.'development.rb', $this->compass_development());
		\app\Filesystem::puts($stylepath.$ds.'bin'.$ds.'etc'.$ds.'compass'.$ds.'production.rb', $this->compass_production());
		\app\Filesystem::puts($stylepath.$ds.'+style.php', $this->style_config());
		\app\Filesystem::puts($stylepath.$ds.'+compile.rb', $this->style_compile());
		\app\Filesystem::puts($stylepath.$ds.'start.rb', $this->style_watch());
		\app\Filesystem::puts($stylepath.$ds.'nyx.json', $this->sample_nyx_config());

		$this->writer->writef(' Style created.')->eol();
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
			. "\t\t\t\"keep\": [\"sass\", \"fonts\", \"README.md\", \"LICENSE\"]".PHP_EOL
			. "\t\t},".PHP_EOL
			. "\t\t{".PHP_EOL
			. "\t\t\t\"repo\": \"https://github.com/FortAwesome/Font-Awesome.git\",".PHP_EOL
			. "\t\t\t\"path\": \"src/vendor/fontawesome\",".PHP_EOL
			. "\t\t\t\"version\": \"v3.2.1\",".PHP_EOL
			. "\t\t\t\"keep\": [\"scss\", \"font\", \"README.md\"]".PHP_EOL
			. "\t\t}".PHP_EOL
			. "\t]".PHP_EOL
			. PHP_EOL
			. "}".PHP_EOL
			;
	}

	/**
	 * @return string
	 */
	protected function compass_development()
	{
		return
			  "# Required Mjolnir configuration".PHP_EOL
			. PHP_EOL
			. "\thttp_path = \"\"".PHP_EOL
			. "\tcss_dir = \"root\"".PHP_EOL
			. "\timages_dir = \"root/images\"".PHP_EOL
			. "\trelative_assets = true".PHP_EOL
			. "\tsass_dir = \"src\"".PHP_EOL
			. PHP_EOL
			. "# Output".PHP_EOL
			. PHP_EOL
			. "\tsass_options = { :debug_info => true }".PHP_EOL
			. "\toutput_style = :expanded".PHP_EOL
			. "\tline_comments = true".PHP_EOL
			;

	}

	/**
	 * @return string
	 */
	protected function compass_production()
	{
		return
			  "# Required Mjolnir configuration".PHP_EOL
			. PHP_EOL
			. "\thttp_path = \"\"".PHP_EOL
			. "\tcss_dir = \"root\"".PHP_EOL
			. "\timages_dir = \"root/images\"".PHP_EOL
			. "\trelative_assets = true".PHP_EOL
			. "\tsass_dir = \"src\"".PHP_EOL
			. PHP_EOL
			. "# Output".PHP_EOL
			. PHP_EOL
			. "\toutput_style = :compressed".PHP_EOL
			. "\tline_comments = false".PHP_EOL
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
	 * @return string
	 */
	protected function style_compile()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. 'basedir = File.expand_path(File.dirname(__FILE__))'.PHP_EOL
			. 'Dir.chdir basedir'.PHP_EOL
			. "Nyx.new.compile_style".PHP_EOL
			;
	}

	/**
	 * @return string
	 */
	protected function style_watch()
	{
		return
			 '#!/usr/bin/env ruby'.PHP_EOL
			. PHP_EOL
			. "require 'rubygems'".PHP_EOL
			. "require 'nyx'".PHP_EOL
			. PHP_EOL
			. 'basedir = File.expand_path(File.dirname(__FILE__))'.PHP_EOL
			. 'Dir.chdir basedir'.PHP_EOL
			. "Nyx.new.watch_style".PHP_EOL
			;
	}

} # class
