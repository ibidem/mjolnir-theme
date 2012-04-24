<?php namespace ibidem\theme;

/**
 * @package    ibidem
 * @category   Theme
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Mockup extends \app\Controller_HTTP
{
	/**
	 * The mockup main action. Targets pass in and are processed. Note a 
	 * \app\ Mockup class is required to act as a mockup content provider. If 
	 * you need $control functions just extend this class in a mockup module in
	 * your project.
	 * 
	 * Tip. Use the \ibidem\base\Make class to generate random mockup content.
	 */
	public function action_testing()
	{
		$target = $this->params->get('target');
		
		$this->body
			(
				\app\ThemeView::instance()
					->target($target)
					->context(\app\Mockup::instance())
					->control($this)
					->layer($this->layer)
					->render()
			);
	}	
	
	/**
	 * Outputs GET and POST values. Acts as a crude form field tester.
	 */
	public function action_form()
	{
		echo 'POST<br>';
		\var_dump($_POST);
		echo '<br>GET<br>';
		\var_dump($_GET);
	}	
	
	/**
	 * All forms are redirected to the form tester action above.
	 * 
	 * @param string action
	 * @return string 
	 */	
	public function action($action)
	{
		$relay_config = \app\CFS::config('ibidem/relays');
		return $relay_config['!mockup-form']['route']->url();
	}

} # class
