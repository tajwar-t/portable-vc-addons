<?php
if (!defined('ABSPATH')) {
    exit;
}

class Portable_VC_Addons_Assets {
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets() {
        wp_enqueue_style('portable-vc-addons-style', PORTABLE_VC_ADDONS_URL . 'assets/css/style.css', array(), PORTABLE_VC_ADDONS_SCRIPT_VERSION);
        wp_enqueue_script('portable-vc-addons-script', PORTABLE_VC_ADDONS_URL . 'assets/js/script.js', array('jquery'), PORTABLE_VC_ADDONS_SCRIPT_VERSION, true);
    }
}

new Portable_VC_Addons_Assets();