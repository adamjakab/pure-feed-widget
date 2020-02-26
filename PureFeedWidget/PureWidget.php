<?php
/**
 * WordPress widget for fetching research publications from Pure.
 *
 * @package PureFeedWidget
 */

namespace PureFeedWidget;

use Exception;

require_once 'Pure.php';

/**
 * A WordPress widget for listing data from an Elsevier Pure systems.
 */
class PureWidget extends \WP_Widget
{
    /** @var null */
    protected $datasource = null;

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

        if (!empty($instance['url'])) {
            $this->datasource = new Pure($instance['url'], $instance['apikey']);

            echo "<ul class='references'>";
            $publications = $this->datasource->get_research_outputs($org = $instance['org'], $size = $instance['size'], $rendering = $instance['rendering']);

            print("PUBS: " . count($publications));

            foreach ($publications as $pub) {
                print($pub->as_html());
                print(PHP_EOL);
            }
            echo '</ul>';
        }
        echo $args['after_widget'];
    }


    public function form($instance)
    {
        $form = "";

        $title = !empty($instance['title']) ? $instance['title'] : "Pure Feed";
        $url = !empty($instance['url']) ? $instance['url'] : null;
        $org = !empty($instance['org']) ? $instance['org'] : null;
        $apikey = !empty($instance['apikey']) ? $instance['apikey'] : null;
        $size = !empty($instance['size']) ? $instance['size'] : 5;
        $rendering = !empty($instance['rendering']) ? $instance['rendering'] : null;


        $form .= $this->getFormInputField("title", "Title", $title);
        $form .= $this->getFormInputField("url", "API URL", $url, true);
        $form .= $this->getFormInputField("apikey", "API KEY", $apikey, true);

        print $form;
    }

    protected function getFormInputField($field_name, $field_title, $field_value, $required=false)
    {
        $field = '';
        $field .=
            '<p>'
            . '<label for="'.esc_attr($this->get_field_id($field_name)).'">'
            . esc_attr($field_title)
            . '<input'
            . ' id="'.esc_attr($this->get_field_id($field_name)).'"'
            . ' class="' . join(" ", [$field_name, "widefat"]) . '"'
             .' name="'.esc_attr($this->get_field_name($field_name)).'"'
            . ' value="'. $field_value .'"'
            . ($required ? 'required' : '')
            . ' type="text"'
            . '>'
            . '</label>'
            . '</p>';

        return $field;
    }

    /**
    public function _form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('', 'text_domain');
        $url = !empty($instance['url']) ? $instance['url'] : null;
        $org = !empty($instance['org']) ? $instance['org'] : null;
        $apikey = !empty($instance['apikey']) ? $instance['apikey'] : null;
        $size = !empty($instance['size']) ? $instance['size'] : 5;
        $rendering = !empty($instance['rendering']) ? $instance['rendering'] : null;
        ?>
        <!-- Widget title -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_attr_e('Title:'); ?>
            </label>
            <input id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   class="title"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                   type="text"
                   value="<?php echo isset($title) ? esc_attr($title) : null; ?>">
        </p>
        <!-- API endpoint URL -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('url')); ?>">
                <?php esc_attr_e('API URL:'); ?>
            </label>
            <input id="<?php echo esc_attr($this->get_field_id('url')); ?>"
                   class="url"
                   name="<?php echo esc_attr($this->get_field_name('url')); ?>"
                   type="text"
                   required
                   pattern="http.*"
                   value="<?php echo isset($url) ? esc_attr($url) : null; ?>">
        </p>
        <!-- API endpoint key -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('apikey')); ?>">
                <?php esc_attr_e('API key:'); ?>
            </label>
            <input id="<?php echo esc_attr($this->get_field_id('apikey')); ?>"
                   class="apikey"
                   name="<?php echo esc_attr($this->get_field_name('apikey')); ?>"
                   type="text"
                   required
                   value="<?php echo isset($url) ? esc_attr($apikey) : null; ?>">
        </p>

        <!-- Organization UUID -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('org')); ?>">
                <?php esc_attr_e('Organization UUID:'); ?>
            </label>
            <input id="<?php echo esc_attr($this->get_field_id('org')); ?>"
                   class="org"
                   name="<?php echo esc_attr($this->get_field_name('org')); ?>"
                   type="text"
                   required
                   value="<?php echo isset($org) ? esc_attr($org) : null; ?>">
        </p>
        <!-- Number of items to retrieve -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('size')); ?>">
                <?php esc_attr_e('Number of items:'); ?>
            </label>
            <input id="<?php echo esc_attr($this->get_field_id('size')); ?>"
                   class="size"
                   name="<?php echo esc_attr($this->get_field_name('size')); ?>"
                   type="number"
                   min="1"
                   max="50"
                   value="<?php echo isset($size) ? esc_attr($size) : 5; ?>">
        </p>
        <!-- Rendering style, available style retrieved from endpoint -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('rendering')); ?>">
                <?php esc_attr_e('Style:'); ?>
            </label>

            <?php
            $formats_url = $url . '/research-outputs-meta/renderings?apiKey=' . $apikey;
            $renderings = simplexml_load_file($formats_url);
            ?>


            <select id="<?php echo esc_attr($this->get_field_id('rendering')); ?>"
                    class="rendering"
                    name="<?php echo esc_attr($this->get_field_name('rendering')); ?>">
                <!--This would better be AJAX I guess. -->
                <?php
                echo '<option value="">None</option>';
                if ($renderings) {
                    foreach ($renderings->xpath('//renderings/rendering') as $rendering_option) {
                        echo '<option value=' . $rendering_option . (($rendering_option == esc_attr($rendering)) ? ' selected' : null) . '>' . $rendering_option . '</option>';
                    }
                } else {
                    echo '<p>Fetching styles failed</p>';
                }
                ?>
            </select>
        </p>
        <?php
    }*/


    /**
     * Save widget options.
     *
     * @param array $new_instance New widget configuration options.
     * @param array $old_instance Old widget configuration options.
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : 'Latest publications';
        $instance['url'] = (!empty($new_instance['url'])) ? strip_tags($new_instance['url']) : null;
        $instance['apikey'] = (!empty($new_instance['apikey'])) ? strip_tags($new_instance['apikey']) : null;
        $instance['org'] = (!empty($new_instance['org'])) ? strip_tags($new_instance['org']) : null;
        $instance['size'] = (!empty($new_instance['size'])) ? strip_tags($new_instance['size']) : 5;
        $instance['rendering'] = (!empty($new_instance['rendering'])) ? strip_tags($new_instance['rendering']) : 'vancouver';

        return $instance;
    }
}
