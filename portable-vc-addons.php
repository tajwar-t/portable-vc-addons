<?php
/*
Plugin Name: Portable VC Addons
Description: Customized addons for WPBakery Builder.
Version: 1.1.0
Author: Your Name
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PORTABLE_VC_ADDONS_VERSION', '1.0');
define('PORTABLE_VC_ADDONS_SCRIPT_VERSION', time());
define('PORTABLE_VC_ADDONS_PATH', plugin_dir_path(__FILE__));
define('PORTABLE_VC_ADDONS_URL', plugin_dir_url(__FILE__));

// Include the main initialization file
require_once PORTABLE_VC_ADDONS_PATH . 'includes/class-init.php';

// Initialize the plugin
if (class_exists('Portable_VC_Addons_Init')) {
    Portable_VC_Addons_Init::get_instance();
}