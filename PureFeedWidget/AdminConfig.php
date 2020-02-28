<?php
/**
 * @Author: Adam Jakab
 * @Licence: GNU GPLv3
 * @Copyright (c) 2020. Pure Feed Widget
 */

namespace PureFeedWidget;

/**
 * Class AdminConfig
 * @package PureFeedWidget
 */
class AdminConfig
{
    /** @var string */
    public $hook = 'pure_feed_widget';

    /** @var string */
    public $longname = 'Pure Feed Widget - Pure content for Wordpress';

    /** @var string */
    public $shortname = 'Pure Feed Widget';

    /** @var string */
    public $accesslvl = 'manage_options';

    /** @var array */
    protected $wp_options = [];

    /** @var string  */
    protected $wp_option_key_name = '';

    /** @var string  */
    protected $wp_admin_form_name = 'pure_feed_widget_admin_form';


    public function __construct()
    {
        $this->wp_option_key_name = $this->hook . "_options";

        // These are the default options overridden in registerSettingsHandler
        $this->wp_options = [
            "api_url" => 'https://your.pure.domain/ws/api/516',
            "api_key" => '',
        ];

        add_action('admin_menu', array(&$this, 'registerSettingsPage'));
        add_action('admin_init', array(&$this, 'refreshAdminSettings'));
        add_action('admin_post_process_form', array(&$this, 'handlePostRequest'));
    }

    /**
     * Add menu item for the admin settings page
     */
    public function registerSettingsPage()
    {
        add_options_page($this->longname, $this->shortname, $this->accesslvl, $this->hook, array(&$this, 'renderSettingsPage'));
    }

    /**
     * Get (or set default) serialized options from WP and store it locally as array
     */
    public function refreshAdminSettings()
    {
        $serialized = get_option($this->wp_option_key_name, null);
        if (is_null($serialized)) {
            $serialized = serialize($this->wp_options);
            add_option($this->wp_option_key_name, $serialized);
        }

        $this->wp_options = unserialize($serialized);
    }

    /**
     * Handle the POST request and store the form values
     */
    public function handlePostRequest()
    {
        if (!$this->isPureWidgetAdminPostRequest()) {
            print("Unmatching POST request!");
            wp_redirect($this->getFormAction());
        }

        if (!array_key_exists("pure_feed_widget", $_POST) || !is_array($_POST["pure_feed_widget"])) {
            print("Missing or bad pure_feed_widget key in POST request!");
            wp_redirect($this->getFormAction());
        }

        $data = $_POST["pure_feed_widget"];

        // @todo: sanity checks here!

        $serialized = serialize($data);
        update_option($this->wp_option_key_name, $serialized);

        wp_redirect($this->getFormAction());
    }

    /**
     * @return string|void
     */
    protected function getFormAction()
    {
        return admin_url('options-general.php?page=' . $this->hook);
    }

    /**
     * Render the settings page
     */
    public function renderSettingsPage()
    {

        $renderer = new Renderer(['auto_reload' => true, 'strict_variables' => true]);


        $context = [
            "title" => "Pure Feed Widget Configuration",
            "form_action" => admin_url('admin-post.php'),
            "form_name" => $this->wp_admin_form_name,
            "api_url" => $this->getAdminOption('api_url'),
            "api_key" => $this->getAdminOption('api_key'),
        ];

        print  $renderer->render("admin_config.twig", $context);
    }

    /**
     * @param string $name
     * @param string $default
     * @return mixed|string
     */
    public function getAdminOption(string $name, $default = "")
    {
        $answer = $default;
        if (array_key_exists($name, $this->wp_options)) {
            $answer = $this->wp_options[$name];
        }

        return $answer;
    }

    /**
     * @return bool
     */
    public function isPureWidgetAdminPostRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !is_array($_POST) || !array_key_exists("form_name", $_POST)
            || $_POST["form_name"] != $this->wp_admin_form_name) {
            return false;
        }

        return true;
    }
}