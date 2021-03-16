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
     * Get widget settings for all widget
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @param object $widget
     * 
     * @return array the widget settings 
     */
    protected function get_widget_setting($widget)
    {
        return $widget->get_settings();
    }

    /**
     * Get title for 
     * Accordion, Tabs, and Toggle
     * 
     * @since 1.0.0
     * @access protected
     * 
     * @param array Settings of the widget
     * @param int Index
     */
    protected function get_accordion_title($settings, $index)
    {
        return $settings['tabs'][$index]['tab_title'];
    }


    /**
     * This function 
     * hook before Elementor Widget Render
     * 
     * @since    1.0.0
     */
    public function before_render_content($widget)
    {
        switch ($widget->get_name()):
            case 'accordion':
            case 'tabs':
            case 'toggle':

                /**
                 * An element with a role that hides child elements contains focusable child elements.	
                 * 
                 * WCAG 2.0 A 1.3.1 Section 508 (2017) A 1.3.1 ARIA 1.1 Presentational Children	3 pages
                 * This role element marks child elements as presentational, 
                 * which hides them from the accessibility tree, but some of these children are focusable, 
                 * so they can be navigated to, but are not voiced in a screen reader.
                 */

                $settings = $this->get_widget_setting($widget);
                foreach ($settings['tabs'] as $index => $item) :
                    $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                    $widget->add_render_attribute($tab_title_setting_key, [
                        'aria-label' => $this->get_accordion_title($settings, $index)
                    ]);

                endforeach;

            default:
                return;
        endswitch;
    }
}
