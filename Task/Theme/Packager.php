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

		// search for theme configs
		$files = \app\CFS::find_files('#^\+theme'.EXT.'$#', $paths);

		foreach ($files as $file)
		{
			$target = \str_replace('\\', '/', \dirname($file)).'/';
			$this->writer->writef(' Resolving: '.$target)->eol();
			$theme = \app\Theme::instance('anonymous', $target);
			$theme->package();
		}
	}

} # class
