<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 */

namespace PureFeedWidget;

/**
 * Class Cron
 * @package PureFeedWidget
 */
class Cron
{
    /** @var string */
    public static $cron_hook_id = 'pure_feed_widget_plugin_cron_event';

    /** @var string */
    protected static $cron_interval = 'daily';

    /** @var Cron|null  */
    protected static $instance = null;

    /** @var string */
    protected $log_file;


    public function __construct()
    {
        $this->log_file = dirname(__DIR__) . "/tmp/cron_log.log";
    }

    /**
     * Schedules the cron event.
     */
    public static function scheduleEvent()
    {
        wp_schedule_event(time(), self::$cron_interval, self::$cron_hook_id);
    }

    /**
     * Clears the scheduled cron event.
     */
    public static function clearScheduledEvent()
    {
        wp_clear_scheduled_hook(self::$cron_hook_id);
    }


    /**
     * Scheduled event
     * Called by pure_feed_widget_plugin_cron_event hook
     */
    public static function executeCron()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        self::$instance->run();
        self::$instance = null;
    }

    public function run()
    {
        $this->log(str_repeat("=", 80));
        $this->log("Executing Cron Job for PFW.");
        $this->log(str_repeat("=", 80));
        //
        // add something more meaningful
        //
    }

    /**
     * @param $msg
     */
    protected function log($msg)
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $msg = sprintf("[%s]: %s", $currentDateTime, $msg) . PHP_EOL;
        file_put_contents($this->log_file, $msg, FILE_APPEND);
    }
}
