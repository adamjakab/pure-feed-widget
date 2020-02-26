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
    /** @var array  */
    protected $defaults = [
        "title" => "Publications",
        "endpoint" => "Research-Outputs",
        "size" => "10",
        "rendering" => "None"
    ];

    /** @var array  */
    protected $endpoint_options = ["Research-Outputs", "Persons"];

    /**
     * Once these came from:  $url . '/research-outputs-meta/renderings?apiKey=' . $apikey;
     * For now I took it out
     *
     * @var array
     */
    protected $rendering_options = ["portal-short", "standard", "detailsPortal", "mla", "author", "cbe", "authorlist",
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
        $form = "";

        $title = $this->getInstanceValue("title", $instance, []);
        $url = $this->getInstanceValue("url", $instance, []);
        $api_key = $this->getInstanceValue("api_key", $instance, []);
        $organization_uuid = $this->getInstanceValue("organization_uuid", $instance, []);
        $endpoint = $this->getInstanceValue("endpoint", $instance, []);
        $size = $this->getInstanceValue("size", $instance, []);
        $rendering = $this->getInstanceValue("rendering", $instance, []);

        $form .= $this->getFormInputField("title", "Title", $title);
        $form .= $this->getFormInputField("url", "API URL", $url, true);
        $form .= $this->getFormInputField("api_key", "API KEY", $api_key, true);
        $form .= $this->getFormInputField("organization_uuid", "Organization UUID", $organization_uuid);
        $form .= $this->getFormSelectField("endpoint", "Select an endpoint", $endpoint, $this->endpoint_options);
        $form .= $this->getFormInputField("size", "Number of items", $size);
        $form .= $this->getFormSelectField("rendering", "Remote rendering format", $rendering, $this->rendering_options, true);

        print $form;
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

    /**
     * @todo: use twig for this
     *
     * @param string $field_name
     * @param string $field_title
     * @param string $field_value
     * @param bool $required
     * @return string
     */
    protected function getFormInputField($field_name, $field_title, $field_value, $required = false)
    {
        $field = '';

        $field_title .= $required ? '(*)' : '';

        $field .=
            '<p>'
            . '<label for="' . esc_attr($this->get_field_id($field_name)) . '">'
            . esc_attr($field_title)
            . '<input'
            . ' id="' . esc_attr($this->get_field_id($field_name)) . '"'
            . ' class="' . join(" ", [$field_name, "widefat"]) . '"'
            . ' name="' . esc_attr($this->get_field_name($field_name)) . '"'
            . ' value="' . $field_value . '"'
            . ($required ? 'required' : '')
            . ' type="text"'
            . '>'
            . '</label>'
            . '</p>';

        return $field;
    }

    /**
     * @param string $field_name
     * @param string $field_title
     * @param string $field_value
     * @param array $options
     * @param bool $allow_none
     * @return string
     * @todo: use twig for this
     *
     */
    protected function getFormSelectField($field_name, $field_title, $field_value, $options, $allow_none=false)
    {
        $field = '';

        $field .=
            '<p>'
            . '<label for="' . esc_attr($this->get_field_id($field_name)) . '">'
            . esc_attr($field_title)
            . '<select'
            . ' id="' . esc_attr($this->get_field_id($field_name)) . '"'
            . ' class="' . join(" ", [$field_name, "widefat"]) . '"'
            . ' name="' . esc_attr($this->get_field_name($field_name)) . '"'
            . '>';

        sort($options);
        if ($allow_none) {
            array_unshift($options, "None");
        }
        foreach($options as $option) {
            $field .= '<option value="' . $option . '"' . ($option == esc_attr($field_value) ? ' selected' : null) . '>' . $option . '</option>';
        }

        $field .=
            '</select>'
            . '</label>'
            . '</p>';

        return $field;
    }
}
