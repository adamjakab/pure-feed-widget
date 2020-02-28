<?php
/**
 * Plugin Name: Pure feed widget
 * Plugin URL: https://github.com/xmacex/pure-feed-widget
 * Description: Render content from Elsevier Pure systems.
 * Version: 0.3.0
 * Author: Mace Ojala
 * Author URI: https://github.com/xmacex
 * Licence: GNU GPLv3
 * Licence URL: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * See here though for merging this WordPress docblock with phpdoc docblock https://developer.wordpress.org/plugins/plugin-basics/header-requirements/
 *
 */

use PureFeedWidget\AdminConfig;
use PureFeedWidget\Cron;

require __DIR__ . '/vendor/autoload.php';

# Plugin activation / deactivation hooks
register_activation_hook(__FILE__, ["PureFeedWidget\Cron", "scheduleEvent"]);
register_deactivation_hook(__FILE__, ["PureFeedWidget\Cron", "clearScheduledEvent"]);

# Actions
add_action('pure_feed_widget_plugin_cron_event', ["PureFeedWidget\Cron", "executeCron"]);


/**
 * Register the widget
 */
add_action(
    'widgets_init',
    function () {
        register_widget('PureFeedWidget\PureWidget');
    }
);

/**
 * Register the Admin Configuration page
 */
if (is_admin()) {
    $adminConfig = new AdminConfig();
}
