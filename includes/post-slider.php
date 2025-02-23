<?php

if (!defined('ABSPATH')) exit;

class Portable_VC_Addons_Post_Slider {
    public function __construct() {
        add_action('vc_before_init', [$this, 'register_vc_element']);
    }

    public function register_vc_element() {
        vc_map([
            'name' => __('Post Slider', 'portable-vc-addons'),
            'base' => 'portable_post_slider',
            'category' => __('Portable VC Addons', 'portable-vc-addons'),
            'params' => [
                [
                    'type' => 'textfield',
                    'heading' => __('Post Type', 'portable-vc-addons'),
                    'param_name' => 'post_type',
                    'value' => 'post',
                    'description' => __('Enter the post type to display.', 'portable-vc-addons')
                ],
                [
                    'type' => 'textfield',
                    'heading' => __('Item Count', 'portable-vc-addons'),
                    'param_name' => 'count',
                    'value' => '5',
                    'description' => __('Number of posts to display.', 'portable-vc-addons')
                ],
                [
                    'type' => 'dropdown',
                    'heading' => __('Design Layout', 'portable-vc-addons'),
                    'param_name' => 'layout',
                    'value' => ['Default' => 'default', 'Layout 1' => 'layout-1', 'Layout 2' => 'layout-2'],
                    'description' => __('Select a design layout.', 'portable-vc-addons')
                ],
                [
                    'type' => 'checkbox',
                    'heading' => __('Navigation Arrows', 'portable-vc-addons'),
                    'param_name' => 'arrows',
                    'value' => ['Enable' => 'true'],
                    'description' => __('Show previous/next arrows.', 'portable-vc-addons')
                ],
                [
                    'type' => 'checkbox',
                    'heading' => __('Navigation Bullets', 'portable-vc-addons'),
                    'param_name' => 'bullets',
                    'value' => ['Enable' => 'true'],
                    'description' => __('Show navigation bullets.', 'portable-vc-addons')
                ]
            ]
        ]);
    }
}

new Portable_VC_Addons_Post_Slider();
