<?php
/**
 * WordPress widget for fetching research publications from Pure.
 *
 * @package PureFeedWidget
 */

namespace PureFeedWidget;

use Exception;

# require_once 'Pure.php';

/**
 * A WordPress widget for listing data from an Elsevier Pure systems.
 */
class PureWidget extends \WP_Widget
{
    /** @var Pure */
    protected $pure = null;

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
        echo apply_filters('widget_title', !empty($instance['title']) ? $instance['title'] : 'Latest publications');
        echo $args['after_title'];

        $this->pure = new Pure($instance['url'], $instance['api_key']);
        $publications = $this->pure->get_research_outputs($instance['org'], $instance['size'], $instance['rendering']);
        print("COUNT: " . count($publications));

        /*
        echo "<ul class='references'>";
        foreach ($publications as $pub) {
            print($pub->as_html());
            print(PHP_EOL);
        }
        echo '</ul>';
        */


        echo $args['after_widget'];
    }


    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        $form = "";

        $title = !empty($instance['title']) ? $instance['title'] : "Pure Feed";
        $url = !empty($instance['url']) ? $instance['url'] : null;
        $api_key = !empty($instance['api_key']) ? $instance['api_key'] : null;
        $organization_uuid = !empty($instance['organization_uuid']) ? $instance['organization_uuid'] : null;
        $size = !empty($instance['size']) ? $instance['size'] : 5;
        $rendering = !empty($instance['rendering']) ? $instance['rendering'] : null;

        $rendering_options = ["portal-short", "standard", "detailsPortal", "mla", "author", "cbe", "authorlist",
            "long", "BIBTEX", "vancouver", "system", "apa", "short", "harvard", "RIS", "researchOutputHeader"];

        $form .= $this->getFormInputField("title", "Title", $title);
        $form .= $this->getFormInputField("url", "API URL", $url, true);
        $form .= $this->getFormInputField("api_key", "API KEY", $api_key, true);
        $form .= $this->getFormInputField("organization_uuid", "Organization UUID", $organization_uuid);
        $form .= $this->getFormInputField("size", "Number of items", $size);
        $form .= $this->getFormSelectField("rendering", "Remote rendering format", $rendering, $rendering_options);

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
        $instance['title'] = $this->getInstanceValue("title", 'Latest publications', $new, $old);
        $instance['url'] = $this->getInstanceValue("url", '', $new, $old);
        $instance['api_key'] = $this->getInstanceValue("api_key", '', $new, $old);
        $instance['organization_uuid'] = $this->getInstanceValue("organization_uuid", '', $new, $old);
        $instance['size'] = $this->getInstanceValue("size", "10", $new, $old);
        $instance['rendering'] = $this->getInstanceValue("rendering", "apa", $new, $old);

        return $instance;
    }

    /**
     * @param string $attrib
     * @param string $default
     * @param array $new_instance
     * @param array $old_instance
     * @return string
     */
    protected function getInstanceValue($attrib, $default, $new_instance, $old_instance)
    {
        $value = $default;

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
     * @return string
     */
    protected function getFormSelectField($field_name, $field_title, $field_value, $options)
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
        foreach($options as $option) {
            $field .= '<option value=' . $option . (($option == esc_attr($field_value)) ? ' selected' : null) . '>' . $option . '</option>';
        }

        $field .=
            '</select>'
            . '</label>'
            . '</p>';

        return $field;
    }

    /* One day...
    protected function getAvailableFormats()
    {
        //$formats_url = $url . '/research-outputs-meta/renderings?apiKey=' . $apikey;
        //$renderings = simplexml_load_file($formats_url);
    }
    */
}
