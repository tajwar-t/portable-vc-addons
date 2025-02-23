<?php
if (!defined('ABSPATH')) {
    exit;
}

class Portable_VC_Addons_Assets {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets() {
        wp_enqueue_style('portable-vc-addons-style', PORTABLE_VC_ADDONS_URL . 'assets/css/style.css', array(), PORTABLE_VC_ADDONS_VERSION);
        wp_enqueue_script('portable-vc-addons-script', PORTABLE_VC_ADDONS_URL . 'assets/js/script.js', array('jquery'), PORTABLE_VC_ADDONS_VERSION, true);
    }
}

new Portable_VC_Addons_Assets();

function enqueue_custom_slider_scripts() {

    wp_enqueue_script('custom-slider-js', get_template_directory_uri() . '/assets/js/custom-slider.js', array('swiper-js'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_slider_scripts');