<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Image Overlay Container – Portable VC Addon
 * Author: bitBirds Solutions
 */

/* -----------------------------------------------------------
 * VC MAP
 * ----------------------------------------------------------- */
add_action('vc_before_init', 'portable_vc_image_overlay_container');

function portable_vc_image_overlay_container() {

    vc_map(array(
        'name'        => __('Image Overlay Container', 'portable-vc-addons'),
        'base'        => 'portable_image_overlay',
        'category'    => __('Portable VC Addons', 'portable-vc-addons'),
        'icon'        => 'icon-wpb-images-stack',
        'description' => __('Container with background image and overlay', 'portable-vc-addons'),

        'params' => array(

            /* Background Image */
            array(
                'type' => 'attach_image',
                'heading' => __('Background Image', 'portable-vc-addons'),
                'param_name' => 'bg_image',
            ),

            /* Overlay */
            array(
                'type' => 'colorpicker',
                'heading' => __('Overlay Color', 'portable-vc-addons'),
                'param_name' => 'overlay_color',
                'value' => '#000000',
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Overlay Opacity (0–1)', 'portable-vc-addons'),
                'param_name' => 'overlay_opacity',
                'value' => '0.5',
            ),

            /* Heading */
            array(
                'type' => 'textfield',
                'heading' => __('Heading Text', 'portable-vc-addons'),
                'param_name' => 'heading_text',
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Heading Font Size (px)', 'portable-vc-addons'),
                'param_name' => 'heading_size',
                'value' => '32',
            ),

            /* Content */
            array(
                'type' => 'textarea',
                'heading' => __('Content Text', 'portable-vc-addons'),
                'param_name' => 'content_text',
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Content Font Size (px)', 'portable-vc-addons'),
                'param_name' => 'content_size',
                'value' => '16',
            ),

            /* Extra Class */
            array(
                'type' => 'textfield',
                'heading' => __('Extra CSS Class', 'portable-vc-addons'),
                'param_name' => 'el_class',
            ),
        ),
    ));
}

/* -----------------------------------------------------------
 * SHORTCODE OUTPUT
 * ----------------------------------------------------------- */
add_shortcode('portable_image_overlay', 'portable_image_overlay_output');

function portable_image_overlay_output($atts) {

    $atts = shortcode_atts(array(
        'bg_image'        => '',
        'overlay_color'   => '#000000',
        'overlay_opacity' => '0.5',
        'heading_text'    => '',
        'heading_size'    => '32',
        'content_text'    => '',
        'content_size'    => '16',
        'el_class'        => '',
    ), $atts);

    $bg_url = '';
    if (!empty($atts['bg_image'])) {
        $bg_url = wp_get_attachment_image_url($atts['bg_image'], 'full');
    }

    $overlay_rgba = portable_hex_to_rgba(
        $atts['overlay_color'],
        floatval($atts['overlay_opacity'])
    );

    ob_start();
    ?>
    <div class="portable-overlay-container <?php echo esc_attr($atts['el_class']); ?>"
         style="background-image:url('<?php echo esc_url($bg_url); ?>');">

        <div class="portable-overlay"
             style="background: <?php echo esc_attr($overlay_rgba); ?>;"></div>

        <div class="portable-overlay-content">

            <?php if (!empty($atts['heading_text'])) : ?>
                <h2 style="font-size:<?php echo intval($atts['heading_size']); ?>px;">
                    <?php echo esc_html($atts['heading_text']); ?>
                </h2>
            <?php endif; ?>

            <?php if (!empty($atts['content_text'])) : ?>
                <div class="portable-overlay-text"
                     style="font-size:<?php echo intval($atts['content_size']); ?>px;">
                    <?php echo wp_kses_post($atts['content_text']); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <?php

    return ob_get_clean();
}

/* -----------------------------------------------------------
 * HELPER: HEX TO RGBA
 * ----------------------------------------------------------- */
function portable_hex_to_rgba($hex, $opacity = 1) {

    $hex = str_replace('#', '', $hex);

    if (strlen($hex) === 3) {
        $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
        $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
        $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }

    return "rgba($r,$g,$b,$opacity)";
}
/* -----------------------------------------------------------
 * ENQUEUE STYLES
 * ----------------------------------------------------------- */
function portable_vc_image_overlay_styles() {
    wp_enqueue_style(
        'portable-vc-image-overlay',
        plugins_url('assets/css/vc-image-overlay-container.css', __FILE__)
    ); 
}
add_action('wp_enqueue_scripts', 'portable_vc_image_overlay_styles');