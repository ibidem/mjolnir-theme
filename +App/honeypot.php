<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\theme'

class Mockup extends \mjolnir\theme\Mockup { /** @return \mjolnir\theme\Mockup */ static function instance($type = 'paragraph', array $args = null) { return parent::instance($type, $args); } }
class Theme extends \mjolnir\theme\Theme { /** @return \mjolnir\theme\Theme */ static function instance($themename = null, $themepath = null) { return parent::instance($themename, $themepath); } }
class ThemeDriver_Dart extends \mjolnir\theme\ThemeDriver_Dart { /** @return \mjolnir\theme\ThemeDriver_Dart */ static function instance() { return parent::instance(); } }
class ThemeDriver_DartJavascript extends \mjolnir\theme\ThemeDriver_DartJavascript { /** @return \mjolnir\theme\ThemeDriver_DartJavascript */ static function instance() { return parent::instance(); } }
class ThemeDriver_DartJavascriptMap extends \mjolnir\theme\ThemeDriver_DartJavascriptMap { /** @return \mjolnir\theme\ThemeDriver_DartJavascriptMap */ static function instance() { return parent::instance(); } }
class ThemeDriver_DartMap extends \mjolnir\theme\ThemeDriver_DartMap { /** @return \mjolnir\theme\ThemeDriver_DartMap */ static function instance() { return parent::instance(); } }
class ThemeDriver_DartResource extends \mjolnir\theme\ThemeDriver_DartResource { /** @return \mjolnir\theme\ThemeDriver_DartResource */ static function instance() { return parent::instance(); } }
class ThemeDriver_Javascript extends \mjolnir\theme\ThemeDriver_Javascript { /** @return \mjolnir\theme\ThemeDriver_Javascript */ static function instance() { return parent::instance(); } }
class ThemeDriver_JavascriptComplete extends \mjolnir\theme\ThemeDriver_JavascriptComplete { /** @return \mjolnir\theme\ThemeDriver_JavascriptComplete */ static function instance() { return parent::instance(); } }
class ThemeDriver_JavascriptCompleteMap extends \mjolnir\theme\ThemeDriver_JavascriptCompleteMap { /** @return \mjolnir\theme\ThemeDriver_JavascriptCompleteMap */ static function instance() { return parent::instance(); } }
class ThemeDriver_JavascriptCompleteSource extends \mjolnir\theme\ThemeDriver_JavascriptCompleteSource { /** @return \mjolnir\theme\ThemeDriver_JavascriptCompleteSource */ static function instance() { return parent::instance(); } }
class ThemeDriver_JavascriptMap extends \mjolnir\theme\ThemeDriver_JavascriptMap { /** @return \mjolnir\theme\ThemeDriver_JavascriptMap */ static function instance() { return parent::instance(); } }
class ThemeDriver_JavascriptSource extends \mjolnir\theme\ThemeDriver_JavascriptSource { /** @return \mjolnir\theme\ThemeDriver_JavascriptSource */ static function instance() { return parent::instance(); } }
class ThemeDriver_JsonBootstrap extends \mjolnir\theme\ThemeDriver_JsonBootstrap { /** @return \mjolnir\theme\ThemeDriver_JsonBootstrap */ static function instance() { return parent::instance(); } }
class ThemeDriver_Style extends \mjolnir\theme\ThemeDriver_Style { /** @return \mjolnir\theme\ThemeDriver_Style */ static function instance() { return parent::instance(); } }
class ThemeDriver_StyleComplete extends \mjolnir\theme\ThemeDriver_StyleComplete { /** @return \mjolnir\theme\ThemeDriver_StyleComplete */ static function instance() { return parent::instance(); } }
class ThemeDriver_StyleResource extends \mjolnir\theme\ThemeDriver_StyleResource { /** @return \mjolnir\theme\ThemeDriver_StyleResource */ static function instance() { return parent::instance(); } }
class ThemeLoader_Dart extends \mjolnir\theme\ThemeLoader_Dart { /** @return \mjolnir\theme\ThemeLoader_Dart */ static function instance() { return parent::instance(); } }
class ThemeLoader_Javascript extends \mjolnir\theme\ThemeLoader_Javascript { /** @return \mjolnir\theme\ThemeLoader_Javascript */ static function instance() { return parent::instance(); } }
class ThemeLoader_Style extends \mjolnir\theme\ThemeLoader_Style { /** @return \mjolnir\theme\ThemeLoader_Style */ static function instance() { return parent::instance(); } }
class ThemeView extends \mjolnir\theme\ThemeView { /** @return \mjolnir\theme\ThemeView */ static function instance() { return parent::instance(); } }
