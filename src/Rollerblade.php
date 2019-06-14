<?php
/**
 * Rollerblade plugin for Craft CMS 3.x
 *
 * A simple plugint to use a regular old Asset source for your SVG Icons, but still have flexibility in inlining the output.
 *
 * @link      https://digitalsurgeons.com
 * @copyright Copyright (c) 2019 Digital Surgeons
 */

namespace digitalsurgeons\rollerblade;

use digitalsurgeons\rollerblade\twigextensions\RollerbladeTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 *
 * @author    Digital Surgeons
 * @package   Rollerblade
 * @since     0.1.0
 *
 */
class Rollerblade extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Rollerblade::$plugin
     *
     * @var Rollerblade
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '0.1.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Rollerblade::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Add in our Twig extensions
        Craft::$app->view->registerTwigExtension(new RollerbladeTwigExtension());
    }
}
