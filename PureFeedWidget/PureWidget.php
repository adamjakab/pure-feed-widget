<?php
/**
 * WordPress widget for fetching research publications from Pure.
 *
 * @package PureFeedWidget
 */

namespace PureFeedWidget;

use Exception;

/**
 * A WordPress widget for listing data from an Elsevier Pure systems.
 */
class PureWidget extends \WP_Widget
{
    /** @var array */
    protected $defaults = [
        "title" => "Publications",
        "endpoint" => "Research-Outputs",
        "size" => "10",
        "rendering" => "None"
    ];

    /** @var array */
    protected $endpoint_options = ["Research-Outputs", "Persons"];

    /**
     * Once these came from:  $url . '/research-outputs-meta/renderings?apiKey=' . $apikey;
     * For now I took it out
     * "None" is a special option for not using any server side rendering
     *
     * @var array
     */
    protected $rendering_options = ["None", "portal-short", "standard", "detailsPortal", "mla", "author", "cbe", "authorlist",
        "long", "BIBTEX", "vancouver", "system", "apa", "short", "harvard", "RIS", "researchOutputHeader"];


    /**
     * Constructor.
     */
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'pure_widget',
            'description' => 'Pure feed widget',
        );
        parent::__construct('pure_widget', 'Pure Feed widget', $widget_ops);
    }

    /**
     * Widget output.
     *
     * Prints nice HTML, or that's the idea.
     *
     * @param array $args Stuff from WordPress.
     * @param array $instance Widget configuration options.
     * @return void
     * @throws Exception
     */
    public function widget($args, $instance)
    {
        wp_enqueue_style('bootstrap-grid-only',
            plugin_dir_url(dirname(__FILE__)) . '/css/bootstrap.min.css');
        wp_enqueue_style('pure-feed-widget',
            plugin_dir_url(dirname(__FILE__)) . '/css/pure-feed-widget.css');

        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];

        $pure = new Pure($instance);
        $out = $pure->getOutput();
        print($out);

        echo $args['after_widget'];
    }


    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        $renderer = new Renderer(['auto_reload' => true, 'strict_variables' => true]);

        $context = [
            "name" => "Pure Feed Widget Configuration",
            "field" => [
                "title" => $this->getContextValuesForField("title", "Title", "text", $instance, true),
                "url" => $this->getContextValuesForField("url", "API URL", "text", $instance, true),
                "api_key" => $this->getContextValuesForField("api_key", "API KEY", "text", $instance, true),
                "organization_uuid" => $this->getContextValuesForField("organization_uuid", "Organization UUID", "text", $instance, false),
                "endpoint" => $this->getContextValuesForField("endpoint", "Pure API endpoint", "select", $instance, true, $this->endpoint_options),
                "size" => $this->getContextValuesForField("size", "Maximum fetch size", "text", $instance, false),
                "rendering" => $this->getContextValuesForField("rendering", "Remote rendering format", "select", $instance, true, $this->rendering_options),
            ],
        ];

        print  $renderer->render("widget_config_form.twig", $context);
    }

    /**
     * Save widget options.
     *
     * @param array $new New widget configuration options.
     * @param array $old Old widget configuration options.
     * @return array
     */
    public function update($new, $old)
    {
        $instance = [];
        $instance['title'] = $this->getInstanceValue("title", $new, $old);
        $instance['url'] = $this->getInstanceValue("url", $new, $old);
        $instance['api_key'] = $this->getInstanceValue("api_key", $new, $old);
        $instance['organization_uuid'] = $this->getInstanceValue("organization_uuid", $new, $old);
        $instance['endpoint'] = $this->getInstanceValue("endpoint", $new, $old);
        $instance['size'] = $this->getInstanceValue("size", $new, $old);
        $instance['rendering'] = $this->getInstanceValue("rendering", $new, $old);

        return $instance;
    }

    /**
     * @param string $id
     * @param string $label
     * @param string $type
     * @param array $instance
     * @param bool $required
     * @param array $options
     * @return array
     */
    protected function getContextValuesForField(string $id, string $label, string $type, array $instance, bool $required = false, array $options = [])
    {
        return [
            "id" => esc_attr($this->get_field_id($id)),
            "name" => esc_attr($this->get_field_name($id)),
            "value" => $this->getInstanceValue($id, $instance, []),
            "label" => $label,
            "class" => $id,
            "type" => $type,
            "required" => $required,
            "options" => $options
        ];
    }

    /**
     * @param string $attrib
     * @param array $new_instance
     * @param array $old_instance
     * @return string
     */
    protected function getInstanceValue($attrib, $new_instance, $old_instance)
    {
        $value = "";

        if (array_key_exists($attrib, $this->defaults) && !empty($this->defaults[$attrib])) {
            $value = $this->defaults[$attrib];
        }

        if (array_key_exists($attrib, $old_instance) && !empty($old_instance[$attrib])) {
            $value = $old_instance[$attrib];
        }

        if (array_key_exists($attrib, $new_instance) && !empty($new_instance[$attrib])) {
            $value = $new_instance[$attrib];
        }

        $value = trim(strip_tags($value));

        return $value;
    }
}
