<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Theme extends \app\Instantiatable implements \mjolnir\types\Theme
{
	use \app\Trait_Theme;
	
	/**
	 * @return string
	 */
	static function instance($themename = null, $themepath = null)
	{
		$instance = parent::instance();
		
		if ($themename === null && $themepath == null)
		{
			$theme = isset($_GET['theme']) ? $_GET['theme'] : null;

			if ($theme === null)
			{
				$theme = \app\Session::get('theme', null);
			}
			
			if ($theme === null)
			{
				$themedetectors = \app\CFS::config('mjolnir/theme-detectors');
				
				foreach ($themedetectors as $detector)
				{
					$theme = $detector();
					
					if ($theme !== null)
					{
						break;
					}
				}
			}
			
			if ($theme !== null)
			{
				$this->themename_is($theme);
				$this->themepath_for($theme);
			}
		}
		
		return $instance;
	}
	
	/**
	 * List of core themes. Core themes are themes defined in ENVFILE under the
	 * key themes. Any themes located in the cascading file system are ancilary 
	 * themes since they are used for various misc pages and may appear even 
	 * outside of DOCROOT.
	 * 
	 * @return array or null
	 */
	static function corethemes()
	{
		$environment = include ENVFILE;
		
		if (isset($environment['themes']))
		{
			return $environment['themes'];
		}
		else # environment not set; no core themes
		{
			return null;
		}
	}
	
} # class
