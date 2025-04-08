<?php
if (!defined('ABSPATH')) {
    exit;
}

class Portable_VC_Addons_Shortcodes {
    public function __construct() {
        add_shortcode('portable_vc_post_slider', array($this, 'render_post_slider'));
        add_shortcode('portable_vc_single_post', array($this, 'render_single_post'));
    }

    public function render_post_slider($atts) {
        $atts = shortcode_atts(array(
            'post_type' => 'post',
            'layout'    => 'slider-layout-1',
            'count'     => 5,
            'arrows'   => 'yes',
            'bullets'   => 'yes',
        ), $atts, 'portable_vc_post_slider');


        wp_enqueue_style('portable-vc-post-slider', PORTABLE_VC_ADDONS_URL . 'addons/post-slider/assets/css/post-slider.css', array(), PORTABLE_VC_ADDONS_SCRIPT_VERSION);
        wp_enqueue_style('portable-vc-swiper-css', PORTABLE_VC_ADDONS_URL . 'addons/post-slider/assets/css/swiper-bundle.min.css', array(), PORTABLE_VC_ADDONS_SCRIPT_VERSION);
        wp_enqueue_script('portable-vc-swiper-js', PORTABLE_VC_ADDONS_URL . 'addons/post-slider/assets/js/swiper-bundle.min.js', array('jquery'), PORTABLE_VC_ADDONS_SCRIPT_VERSION, true);
        wp_enqueue_script('portable-vc-post-slider', PORTABLE_VC_ADDONS_URL . 'addons/post-slider/assets/js/post-slider.js', array('jquery'), PORTABLE_VC_ADDONS_SCRIPT_VERSION, true);


        ob_start();
        include PORTABLE_VC_ADDONS_PATH . "addons/post-slider/views/{$atts['layout']}.php";
        return ob_get_clean();
    }

    // Shortcode function
    public function render_single_post($atts) {
        $atts = shortcode_atts(array(
            'post_id'     => '',
            'layout'      => 'layout-1',
        ), $atts);
        
        if (empty($atts['post_id'])) {
            return __('No post selected.', 'portable-vc-addons');
        }
        
        $post = get_post($atts['post_id']);
        if (!$post) {
            return __('Post not found.', 'portable-vc-addons');
        }
        // var_dump(PORTABLE_VC_ADDONS_PATH . "addons/single-post/views/post-{$atts['layout']}.php"); die;
        ob_start();
        // include PORTABLE_VC_ADDONS_PATH . "addons/post-slider/views/{$atts['sp_layout']}.php";
        include PORTABLE_VC_ADDONS_PATH . "addons/single-post/views/post-{$atts['layout']}.php";

        return ob_get_clean();
    }
}

new Portable_VC_Addons_Shortcodes();