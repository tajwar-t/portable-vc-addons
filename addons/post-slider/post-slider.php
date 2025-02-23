<?php
if (!defined('ABSPATH')) {
    exit;
}


add_action('portable_vc_addons_loaded', function() {
    $post_types_array = array(
        __('Posts', 'portable-vc-addons') => 'post',
        __('Pages', 'portable-vc-addons') => 'page',
    );
    if ( class_exists('Vc_Manager') ) {
        $vc_enabled_post_types = vc_editor_post_types(); // WPBakery function to get enabled post types
        
        foreach ($vc_enabled_post_types as $post_type) {
            $label = get_post_type_object($post_type)->labels->name;
            if(!empty($label)){
                $post_types_array[ __( $label, 'portable-vc-addons' ) ] = $post_type;
            }
        }
    }
    vc_map(array(
        'name'        => __('Post Slider', 'portable-vc-addons'),
        'base'        => 'portable_vc_post_slider',
        'category'    => __('Portable VC Addons', 'portable-vc-addons'),
        'params'      => array(
            array(
                'type'        => 'dropdown',
                'heading'     => __('Post Type', 'portable-vc-addons'),
                'param_name'  => 'post_type',
                'value'       => $post_types_array,
            ),
            array(
                'type'        => 'dropdown',
                'heading'     => __('Layout', 'portable-vc-addons'),
                'param_name'  => 'layout',
                'value'       => array(
                    __('Layout 1', 'portable-vc-addons') => 'slider-layout-1',
                    __('Layout 2', 'portable-vc-addons') => 'slider-layout-2',
                ),
            ),
            array(
                'type'        => 'dropdown',
                'heading'     => __('Item Count', 'portable-vc-addons'),
                'param_name'  => 'count',
                'value'       => array(
                    __('All', 'portable-vc-addons') => -1,
                    __('5', 'portable-vc-addons') => 5,
                    __('10', 'portable-vc-addons') => 10,
                ),
            ),
            array(
                'type'        => 'checkbox',
                'heading'     => __('Show Arrows?', 'portable-vc-addons'),
                'param_name'  => 'arrows',
                'value'       => array(__('Yes', 'portable-vc-addons') => 'yes'),
            ),
            array(
                'type'        => 'checkbox',
                'heading'     => __('Show Bullets?', 'portable-vc-addons'),
                'param_name'  => 'bullets',
                'value'       => array(__('Yes', 'portable-vc-addons') => 'yes'),
            ),
        ),
    ));
});