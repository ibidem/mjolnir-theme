<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'ibidem\theme'

class Controller_Mockup extends \ibidem\theme\Controller_Mockup { /** @return \ibidem\theme\Controller_Mockup */ static function instance() { return parent::instance(); } }
class Layer_Theme extends \ibidem\theme\Layer_Theme { /** @return \ibidem\theme\Layer_Theme */ static function instance() { return parent::instance(); } }
class Task_Make_Style extends \ibidem\theme\Task_Make_Style { /** @return \ibidem\theme\Task_Make_Style */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class Task_Make_Theme extends \ibidem\theme\Task_Make_Theme { /** @return \ibidem\theme\Task_Make_Theme */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
class ThemeView extends \ibidem\theme\ThemeView { /** @return \ibidem\theme\ThemeView */ static function instance() { return parent::instance(); } }
