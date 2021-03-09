<?php

/**
 * The file that handle Elementor Accessibility
 *
 * Use Elementor Hook
 * Change WIdget HTML Attribute
 * before render to the Frontend
 * to Adjust Accessibility
 *
 * @link       https://github.com/novanda1
 * @since      1.0.0
 *
 * @package    Labs_Services
 * @subpackage Labs_Services/features
 */


class Labs_Services_Elementor_Accessibility
{
    /**
     * Get repeater setting key. by elementor
     *
     * Retrieve the unique setting key for the current repeater item. Used to connect the current element in the
     * repeater to it's settings model and it's control in the panel.
     *
     * PHP usage (inside `Widget_Base::render()` method):
     *
     *    $tabs = $this->get_settings( 'tabs' );
     *    foreach ( $tabs as $index => $item ) {
     *        $tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
     *        $this->add_inline_editing_attributes( $tab_title_setting_key, 'none' );
     *        echo '<div ' . $this->get_render_attribute_string( $tab_title_setting_key ) . '>' . $item['tab_title'] . '</div>';
     *    }
     *
     * @since 1.0.0
     * @access protected
     *
     * @param string $setting_key      The current setting key inside the repeater item (e.g. `tab_title`).
     * @param string $repeater_key     The repeater key containing the array of all the items in the repeater (e.g. `tabs`).
     * @param int $repeater_item_index The current item index in the repeater array (e.g. `3`).
     *
     * @return string The repeater setting key (e.g. `tabs.3.tab_title`).
     */
    protected function get_repeater_setting_key($setting_key, $repeater_key, $repeater_item_index)
    {
        return implode('.', [$repeater_key, $repeater_item_index, $setting_key]);
    }


    /**
     * This function 
     * hook before Elementor Widget Render
     * 
     * @since    1.0.0
     */
    public function before_render_content($widget)
    {

        if ('accordion' === $widget->get_name()) {
            /**
             * handle Accordion Widget
             * 
             * ISSUE -> 
             * This role element marks child elements as presentational, 
             * which hides them from the accessibility tree, but some of these children are focusable, 
             * so they can be navigated to, but are not voiced in a screen reader.
             * 
             * SOLUTION ->
             * add aria-label in role="tab" to presentation 
             * what this tab according tab title, to voiced in a screen reader
             */
            $settings = $widget->get_settings();

            function get_accordion_title($settings, $index)
            {
                return $settings['tabs'][$index]['tab_title'];
            }

            foreach ($settings['tabs'] as $index => $item) :
                $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

                /**
                 * add aria label to role="tab"
                 */
                $widget->add_render_attribute($tab_title_setting_key, [
                    'aria-label' => get_accordion_title($settings, $index)
                ]);

            endforeach;
        }
    }
}
