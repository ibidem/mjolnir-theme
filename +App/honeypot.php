<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'ibidem\theme'

class Context_Exception_NotAllowed extends \ibidem\theme\Context_Exception_NotAllowed { /** @return \ibidem\theme\Context_Exception_NotAllowed */ static function instance() { return parent::instance(); } }
class Context_Exception_NotApplicable extends \ibidem\theme\Context_Exception_NotApplicable { /** @return \ibidem\theme\Context_Exception_NotApplicable */ static function instance() { return parent::instance(); } }
class Context_Exception_NotFound extends \ibidem\theme\Context_Exception_NotFound { /** @return \ibidem\theme\Context_Exception_NotFound */ static function instance() { return parent::instance(); } }
class Context_Exception_Unknown extends \ibidem\theme\Context_Exception_Unknown { /** @return \ibidem\theme\Context_Exception_Unknown */ static function instance() { return parent::instance(); } }
class Context_Style extends \ibidem\theme\Context_Style { /** @return \ibidem\theme\Context_Style */ static function instance() { return parent::instance(); } }
class Controller_Mockup extends \ibidem\theme\Controller_Mockup { /** @return \ibidem\theme\Controller_Mockup */ static function instance() { return parent::instance(); } }
class Layer_Theme extends \ibidem\theme\Layer_Theme { /** @return \ibidem\theme\Layer_Theme */ static function instance() { return parent::instance(); } }
class Task_Make_Style extends \ibidem\theme\Task_Make_Style { /** @return \ibidem\theme\Task_Make_Style */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Theme extends \ibidem\theme\Task_Make_Theme { /** @return \ibidem\theme\Task_Make_Theme */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class ThemeView extends \ibidem\theme\ThemeView { /** @return \ibidem\theme\ThemeView */ static function instance() { return parent::instance(); } }
