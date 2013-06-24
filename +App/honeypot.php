<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\theme'


/**
 * @method \app\Mockup addmetarenderer($key, $metarenderer)
 * @method \app\Mockup injectmetarenderers(array $metarenderers = null)
 */
class Mockup extends \mjolnir\theme\Mockup
{
	/** @return \app\Mockup */
	static function instance($type = 'paragraph', array $args = null) { return parent::instance($type, $args); }
	/** @return \app\Mockup */
	static function name() { return parent::name(); }
	/** @return \app\Mockup */
	static function lorempixel($width, $height, $category = null, $grayscale = null) { return parent::lorempixel($width, $height, $category, $grayscale); }
	/** @return \app\Mockup */
	static function given_name() { return parent::given_name(); }
	/** @return \app\Mockup */
	static function family_name() { return parent::family_name(); }
	/** @return \app\Mockup */
	static function telephone() { return parent::telephone(); }
	/** @return \app\Mockup */
	static function email() { return parent::email(); }
	/** @return \app\Mockup */
	static function ssn() { return parent::ssn(); }
	/** @return \app\Mockup */
	static function address() { return parent::address(); }
	/** @return \app\Mockup */
	static function city() { return parent::city(); }
	/** @return \app\Mockup */
	static function paragraph() { return parent::paragraph(); }
	/** @return \app\Mockup */
	static function action() { return parent::action(); }
	/** @return \app\Mockup */
	static function url($mockup = null) { return parent::url($mockup); }
	/** @return \app\Mockup */
	static function counter($id) { return parent::counter($id); }
	/** @return \app\Mockup */
	static function title() { return parent::title(); }
	/** @return \app\Mockup */
	static function word() { return parent::word(); }
	/** @return \app\Mockup */
	static function words($count = 10) { return parent::words($count); }
	/** @return \app\Mockup */
	static function rand(array $values) { return parent::rand($values); }
}

/**
 * @method \app\Task_Theme_Packager set($name, $value)
 * @method \app\Task_Theme_Packager add($name, $value)
 * @method \app\Task_Theme_Packager metadata_is(array $metadata = null)
 * @method \app\Task_Theme_Packager writer_is($writer)
 * @method \app\Writer writer()
 */
class Task_Theme_Packager extends \mjolnir\theme\Task_Theme_Packager
{
	/** @return \app\Task_Theme_Packager */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Theme package()
 * @method \app\ThemeView themeview($viewtarget)
 * @method \app\Theme channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\Theme set($name, $value)
 * @method \app\Theme add($name, $value)
 * @method \app\Theme metadata_is(array $metadata = null)
 * @method \app\Theme channel_is($channel = null)
 */
class Theme extends \mjolnir\theme\Theme
{
}

/**
 * @method \app\ThemeDriver_Bootstrap package($theme, $parentversion)
 * @method \app\ThemeDriver_Bootstrap channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_Bootstrap addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_Bootstrap injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_Bootstrap extends \mjolnir\theme\ThemeDriver_Bootstrap
{
	/** @return \app\ThemeDriver_Bootstrap */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_Dart package($theme, $parentversion)
 * @method \app\ThemeDriver_Dart channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_Dart addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_Dart injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_Dart extends \mjolnir\theme\ThemeDriver_Dart
{
	/** @return \app\ThemeDriver_Dart */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_DartJavascript package($theme, $parentversion)
 * @method \app\ThemeDriver_DartJavascript channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_DartJavascript addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_DartJavascript injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_DartJavascript extends \mjolnir\theme\ThemeDriver_DartJavascript
{
	/** @return \app\ThemeDriver_DartJavascript */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_DartJavascriptMap package($theme, $parentversion)
 * @method \app\ThemeDriver_DartJavascriptMap channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_DartJavascriptMap addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_DartJavascriptMap injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_DartJavascriptMap extends \mjolnir\theme\ThemeDriver_DartJavascriptMap
{
	/** @return \app\ThemeDriver_DartJavascriptMap */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_DartMap package($theme, $parentversion)
 * @method \app\ThemeDriver_DartMap channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_DartMap addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_DartMap injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_DartMap extends \mjolnir\theme\ThemeDriver_DartMap
{
	/** @return \app\ThemeDriver_DartMap */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_DartResource package($theme, $parentversion)
 * @method \app\ThemeDriver_DartResource channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_DartResource addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_DartResource injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_DartResource extends \mjolnir\theme\ThemeDriver_DartResource
{
	/** @return \app\ThemeDriver_DartResource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_Javascript package($theme, $parentversion)
 * @method \app\ThemeDriver_Javascript channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_Javascript addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_Javascript injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_Javascript extends \mjolnir\theme\ThemeDriver_Javascript
{
	/** @return \app\ThemeDriver_Javascript */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_JavascriptComplete package($theme, $parentversion)
 * @method \app\ThemeDriver_JavascriptComplete channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_JavascriptComplete addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_JavascriptComplete injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_JavascriptComplete extends \mjolnir\theme\ThemeDriver_JavascriptComplete
{
	/** @return \app\ThemeDriver_JavascriptComplete */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_JavascriptCompleteMap package($theme, $parentversion)
 * @method \app\ThemeDriver_JavascriptCompleteMap channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_JavascriptCompleteMap addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_JavascriptCompleteMap injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_JavascriptCompleteMap extends \mjolnir\theme\ThemeDriver_JavascriptCompleteMap
{
	/** @return \app\ThemeDriver_JavascriptCompleteMap */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_JavascriptCompleteSource package($theme, $parentversion)
 * @method \app\ThemeDriver_JavascriptCompleteSource channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_JavascriptCompleteSource addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_JavascriptCompleteSource injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_JavascriptCompleteSource extends \mjolnir\theme\ThemeDriver_JavascriptCompleteSource
{
	/** @return \app\ThemeDriver_JavascriptCompleteSource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_JavascriptMap package($theme, $parentversion)
 * @method \app\ThemeDriver_JavascriptMap channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_JavascriptMap addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_JavascriptMap injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_JavascriptMap extends \mjolnir\theme\ThemeDriver_JavascriptMap
{
	/** @return \app\ThemeDriver_JavascriptMap */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_JavascriptSource package($theme, $parentversion)
 * @method \app\ThemeDriver_JavascriptSource channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_JavascriptSource addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_JavascriptSource injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_JavascriptSource extends \mjolnir\theme\ThemeDriver_JavascriptSource
{
	/** @return \app\ThemeDriver_JavascriptSource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_Resource package($theme, $parentversion)
 * @method \app\ThemeDriver_Resource channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_Resource addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_Resource injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_Resource extends \mjolnir\theme\ThemeDriver_Resource
{
	/** @return \app\ThemeDriver_Resource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_Style package($theme, $parentversion)
 * @method \app\ThemeDriver_Style channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_Style addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_Style injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_Style extends \mjolnir\theme\ThemeDriver_Style
{
	/** @return \app\ThemeDriver_Style */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_StyleComplete package($theme, $parentversion)
 * @method \app\ThemeDriver_StyleComplete channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_StyleComplete addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_StyleComplete injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_StyleComplete extends \mjolnir\theme\ThemeDriver_StyleComplete
{
	/** @return \app\ThemeDriver_StyleComplete */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_StyleResource package($theme, $parentversion)
 * @method \app\ThemeDriver_StyleResource channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_StyleResource addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_StyleResource injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_StyleResource extends \mjolnir\theme\ThemeDriver_StyleResource
{
	/** @return \app\ThemeDriver_StyleResource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeDriver_StyleSource package($theme, $parentversion)
 * @method \app\ThemeDriver_StyleSource channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeDriver_StyleSource addmetarenderer($key, $metarenderer)
 * @method \app\ThemeDriver_StyleSource injectmetarenderers(array $metarenderers = null)
 */
class ThemeDriver_StyleSource extends \mjolnir\theme\ThemeDriver_StyleSource
{
	/** @return \app\ThemeDriver_StyleSource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeFooterInclude file_is($file, $ext = '.php')
 * @method \app\ThemeFooterInclude file_path($filepath)
 * @method \app\ThemeFooterInclude bind($name,  & $value)
 * @method \app\ThemeFooterInclude pass($name, $value)
 * @method \app\ThemeFooterInclude inherit($view)
 * @method \app\ThemeFooterInclude addmetarenderer($key, $metarenderer)
 * @method \app\ThemeFooterInclude injectmetarenderers(array $metarenderers = null)
 * @method \app\ThemeFooterInclude channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeFooterInclude extends \mjolnir\theme\ThemeFooterInclude
{
	/** @return \app\ThemeFooterInclude */
	static function instance($file = null, $ext = '.php') { return parent::instance($file, $ext); }
}

/**
 * @method \app\ThemeHeadInclude file_is($file, $ext = '.php')
 * @method \app\ThemeHeadInclude file_path($filepath)
 * @method \app\ThemeHeadInclude bind($name,  & $value)
 * @method \app\ThemeHeadInclude pass($name, $value)
 * @method \app\ThemeHeadInclude inherit($view)
 * @method \app\ThemeHeadInclude addmetarenderer($key, $metarenderer)
 * @method \app\ThemeHeadInclude injectmetarenderers(array $metarenderers = null)
 * @method \app\ThemeHeadInclude channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeHeadInclude extends \mjolnir\theme\ThemeHeadInclude
{
	/** @return \app\ThemeHeadInclude */
	static function instance($file = null, $ext = '.php') { return parent::instance($file, $ext); }
}

/**
 * @method \app\ThemeLoader_Bootstrap set($name, $value)
 * @method \app\ThemeLoader_Bootstrap add($name, $value)
 * @method \app\ThemeLoader_Bootstrap metadata_is(array $metadata = null)
 * @method \app\ThemeLoader_Bootstrap channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeLoader_Bootstrap extends \mjolnir\theme\ThemeLoader_Bootstrap
{
	/** @return \app\ThemeLoader_Bootstrap */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeLoader_Dart set($name, $value)
 * @method \app\ThemeLoader_Dart add($name, $value)
 * @method \app\ThemeLoader_Dart metadata_is(array $metadata = null)
 * @method \app\ThemeLoader_Dart channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeLoader_Dart extends \mjolnir\theme\ThemeLoader_Dart
{
	/** @return \app\ThemeLoader_Dart */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeLoader_Javascript set($name, $value)
 * @method \app\ThemeLoader_Javascript add($name, $value)
 * @method \app\ThemeLoader_Javascript metadata_is(array $metadata = null)
 * @method \app\ThemeLoader_Javascript channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeLoader_Javascript extends \mjolnir\theme\ThemeLoader_Javascript
{
	/** @return \app\ThemeLoader_Javascript */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeLoader_Resource set($name, $value)
 * @method \app\ThemeLoader_Resource add($name, $value)
 * @method \app\ThemeLoader_Resource metadata_is(array $metadata = null)
 * @method \app\ThemeLoader_Resource channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeLoader_Resource extends \mjolnir\theme\ThemeLoader_Resource
{
	/** @return \app\ThemeLoader_Resource */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\ThemeLoader_Style set($name, $value)
 * @method \app\ThemeLoader_Style add($name, $value)
 * @method \app\ThemeLoader_Style metadata_is(array $metadata = null)
 * @method \app\ThemeLoader_Style channel_is($channel = null)
 * @method \app\Channel channel()
 */
class ThemeLoader_Style extends \mjolnir\theme\ThemeLoader_Style
{
	/** @return \app\ThemeLoader_Style */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\View partial($path)
 * @method \app\Rendereable compileview($file, $themepath)
 * @method \app\ThemeView file_is($file, $ext = '.php')
 * @method \app\ThemeView file_path($filepath)
 * @method \app\ThemeView bind($name,  & $value)
 * @method \app\ThemeView pass($name, $value)
 * @method \app\ThemeView inherit($view)
 * @method \app\ThemeView addmetarenderer($key, $metarenderer)
 * @method \app\ThemeView injectmetarenderers(array $metarenderers = null)
 * @method \app\ThemeView themepath_is($themepath)
 * @method \app\ThemeView channel_is($channel = null)
 * @method \app\Channel channel()
 * @method \app\ThemeView viewtarget_is($viewtarget)
 */
class ThemeView extends \mjolnir\theme\ThemeView
{
	/** @return \app\ThemeView */
	static function instance($file = null, $ext = '.php') { return parent::instance($file, $ext); }
	/** @return \app\ThemeView */
	static function fortarget($viewtarget, $theme = null) { return parent::fortarget($viewtarget, $theme); }
}
trait Trait_ThemeDriver_JavascriptCommon { use \mjolnir\theme\Trait_ThemeDriver_JavascriptCommon; }
