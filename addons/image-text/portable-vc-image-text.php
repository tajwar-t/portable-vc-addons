<?php
/*
Plugin Name: Portable VC Addons - Image & Text
Description: Adds a WPBakery element to display an image and text side by side with styling options.
Version: 1.3
Author: Tajwar
*/

if (!defined('ABSPATH')) exit;

// Register VC Element
add_action('vc_before_init', 'portable_vc_add_image_text');
function portable_vc_add_image_text() {
    vc_map(array(
        'name' => __('Image & Text', 'portable-vc-addons'),
        'base' => 'portable_vc_image_text',
        'category' => __('Portable VC Addons', 'portable-vc-addons'),
        'icon' => 'dashicons-align-left',
        'params' => array(
            array(
                'type' => 'attach_image',
                'heading' => __('Image', 'portable-vc-addons'),
                'param_name' => 'image',
                'description' => __('Upload or choose an image.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Image Width', 'portable-vc-addons'),
                'param_name' => 'image_width',
                'description' => __('Enter width for image (e.g. 150px or 40%). Leave empty for auto.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'textarea_html',
                'heading' => __('Text', 'portable-vc-addons'),
                'param_name' => 'content',
                'description' => __('Enter the text content that will appear beside the image.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Image Position', 'portable-vc-addons'),
                'param_name' => 'image_position',
                'value' => array(
                    __('Left', 'portable-vc-addons') => 'left',
                    __('Right', 'portable-vc-addons') => 'right',
                ),
                'std' => 'left',
                'description' => __('Choose whether the image appears on the left or right side.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'colorpicker',
                'heading' => __('Text Color', 'portable-vc-addons'),
                'param_name' => 'text_color',
                'description' => __('Choose a custom color for the text.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Font Size', 'portable-vc-addons'),
                'param_name' => 'font_size',
                'description' => __('Enter font size (e.g. 16px, 1.2em, 120%).', 'portable-vc-addons'),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Font Weight', 'portable-vc-addons'),
                'param_name' => 'font_weight',
                'value' => array(
                    __('Normal', 'portable-vc-addons') => 'normal',
                    __('Bold', 'portable-vc-addons') => 'bold',
                    __('Light', 'portable-vc-addons') => '300',
                    __('Medium', 'portable-vc-addons') => '500',
                    __('Semi-Bold', 'portable-vc-addons') => '600',
                ),
                'std' => 'normal',
                'description' => __('Set the font weight of the text.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom CSS Class', 'portable-vc-addons'),
                'param_name' => 'custom_class',
                'description' => __('Optional custom CSS class for styling.', 'portable-vc-addons'),
            ),
        )
    ));
}

// Enqueue CSS
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'portable-vc-image-text',
        plugin_dir_url(__FILE__) . 'assets/css/portable-vc-image-text.css',
        [],
        time()
    );
});

// Shortcode Output
add_shortcode('portable_vc_image_text', 'portable_vc_image_text_output');
function portable_vc_image_text_output($atts, $content = null) {
    $atts = shortcode_atts(array(
        'image' => '',
        'image_position' => 'left',
        'image_width' => '',
        'text_color' => '',
        'font_size' => '',
        'font_weight' => 'normal',
        'custom_class' => '',
    ), $atts);

    $image_url = $atts['image'] ? wp_get_attachment_image_url($atts['image'], 'large') : '';
    $custom_class = !empty($atts['custom_class']) ? esc_attr($atts['custom_class']) : '';
    $content = wpb_js_remove_wpautop($content, true);

    // Image style
    $img_style = 'max-width:100%; height:auto;';
    if (!empty($atts['image_width'])) {
        $img_style .= ' width:' . esc_attr($atts['image_width']) . ';';
    }

    // Text style
    $text_style = 'margin:0;';
    if (!empty($atts['text_color'])) {
        $text_style .= 'color:' . esc_attr($atts['text_color']) . ';';
    }
    if (!empty($atts['font_size'])) {
        $text_style .= 'font-size:' . esc_attr($atts['font_size']) . ';';
    }
    if (!empty($atts['font_weight'])) {
        $text_style .= 'font-weight:' . esc_attr($atts['font_weight']) . ';';
    }

    $img_html = $image_url ? '<div class="portable-vc-image"><img src="' . esc_url($image_url) . '" alt="" style="' . $img_style . '"></div>' : '';
    $text_html = '<div class="portable-vc-text" style="' . $text_style . '">' . $content . '</div>';

    $html = '<div class="portable-vc-image-text ' . $custom_class . '">';

    if ($atts['image_position'] === 'right') {
        $html .= $text_html . $img_html;
    } else {
        $html .= $img_html . $text_html;
    }

    $html .= '</div>';

    return $html;
}
