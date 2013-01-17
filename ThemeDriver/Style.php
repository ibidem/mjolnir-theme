<?php namespace mjolnir\theme;

/**
 * @package    mjolnir
 * @category   Theme
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class ThemeDriver_Style extends \app\Instantiatable implements \mjolnir\types\ThemeDriver
{
	use \app\Trait_ThemeDriver;
	
	/**
	 * ...
	 */
	function render()
	{
		$styleconfig = $this->collection_entityfile('style', 'styles');
		$this->channel()->set('styleconfig', $styleconfig);

		$stylepath = $this->channel()->get('stylepath');
		$rootpath = $stylepath.$styleconfig['root'];

		$target = $this->channel()->get('relaynode')->get('target');

		if ( ! isset($styleconfig['targeted-mapping'][$target]))
		{
			$style = $this->channel()->get('relaynode')->get('style');
			throw new \Exception("Missing target [$target] in style [$style].");
		}
		
		$common = isset($styleconfig['targeted-common']) ? $styleconfig['targeted-common'] : [];
		
		$this->channel()->add('http:header', ['content-type', 'text/css']);
		
		return $this->combine($rootpath, \array_merge($common, $styleconfig['targeted-mapping'][$target]), '.css');
	}

} # class
