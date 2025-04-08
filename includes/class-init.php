<?php
if (!defined('ABSPATH')) {
    exit;
}

class Portable_VC_Addons_Init {
    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once PORTABLE_VC_ADDONS_PATH . 'includes/class-shortcodes.php';
        require_once PORTABLE_VC_ADDONS_PATH . 'includes/class-assets.php';
        require_once PORTABLE_VC_ADDONS_PATH . 'addons/post-slider/post-slider.php';
        require_once PORTABLE_VC_ADDONS_PATH . 'addons/single-post/single-post.php';
    }

    private function init_hooks() {
        // Hook into WPBakery Builder
        add_action('vc_before_init', array($this, 'integrate_with_vc'));
    }

    public function integrate_with_vc() {
        // Check if WPBakery Page Builder is installed
        if (!defined('WPB_VC_VERSION')) {
            add_action('admin_notices', array($this, 'show_vc_notice'));
            return;
        }

        // Load addons
        do_action('portable_vc_addons_loaded');
    }

    public function show_vc_notice() {
        echo '<div class="notice notice-warning"><p>Portable VC Addons requires WPBakery Page Builder to be installed and activated.</p></div>';
    }
}