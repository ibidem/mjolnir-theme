<?php namespace mjolnir\theme;

/**
 * PROTOTYPE - subject to change
 * 
 * @package    mjolnir
 * @category   Cascading File System
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Theme_Packager extends \app\Task_Base
{
	/**
	 * @todo cleaner version of this prototype
	 * @todo forced
	 * @todo read driver based version
	 * 
	 * Execute task.
	 */
	function run()
	{
		\app\Task::consolewriter($this->writer);

		$local = $this->get('local', false);

		if ( ! $local)
		{
			$paths = \app\CFS::paths();
		}
		else # only local files
		{
			$paths = [];
		}

		$env_config = \app\Env::key('environment.config');

		if (isset($env_config['themes']))
		{
			foreach ($env_config['themes'] as $theme => $path)
			{
				$paths[] = $path;
			}
		}

		$files = \app\CFS::find_files('#^\+compile.rb$#', $paths);

		if (empty($files))
		{
			$this->writer->writef(' No [+compile.rb] files detected on the system.')
				->eol()->eol();
		}

		foreach ($files as $file)
		{
			$target = \dirname($file).'/';
			$this->writer->writef(' Resolving: '.$target)->eol()->eol();
			\app\Filesystem::delete($target.'packages/'.VERSION.'/');
			$this->recurse_copy($target.'root/', $target.'packages/'.VERSION.'/');
			\app\Filesystem::delete($target.'packages/'.VERSION.'/.gitignore');
		}
	}
	
	function recurse_copy($src, $dst) 
	{
		$dir = \opendir($src); 
		\app\Filesystem::makedir($dst);
		while (false !== ( $file = \readdir($dir))) 
		{
			if (($file != '.') && ($file != '..')) 
			{
				if (\is_dir($src . '/' . $file))
				{
					$this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else # file
				{
					\copy($src.'/'.$file, $dst.'/'.$file); 
				}
			}
		}
		
		\closedir($dir); 
	}

} # class
