<?php
if (!defined('ABSPATH')) {
    exit;
}

// Register the VC element
add_action('vc_before_init', 'portable_vc_add_hover_box');
function portable_vc_add_hover_box() {
    vc_map(array(
        'name' => __('Hover Box', 'portable-vc-addons'),
        'base' => 'whb_hover_box',
        'category' => __('Portable VC Addons', 'portable-vc-addons'),
        'icon' => 'dashicons-format-image',
        'params' => array(
            array(
                'type' => 'attach_image',
                'heading' => __('Image', 'portable-vc-addons'),
                'param_name' => 'image',
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Title', 'portable-vc-addons'),
                'param_name' => 'title',
            ),
            array(
                'type' => 'textarea',
                'heading' => __('Description', 'portable-vc-addons'),
                'param_name' => 'description',
            ),
            array(
                'type' => 'textarea',
                'heading' => __('Hover Description', 'portable-vc-addons'),
                'param_name' => 'hover_description',
            ),
        ),
    ));
}
// Enqueue styles
function whb_enqueue_styles() {
    wp_enqueue_style( 'whb-style', plugins_url( 'assets/css/vc-hover-box.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'whb_enqueue_styles' );
// Shortcode output
add_shortcode( 'whb_hover_box', 'whb_hover_box_shortcode' );
function whb_hover_box_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'image' => '',
        'title' => '',
        'description' => '',
        'hover_description' => '',
    ), $atts );

    $image_url = wp_get_attachment_image_url( $atts['image'], 'full' );

    ob_start(); ?>
    <div class="whb-box">
        <?php if ( $image_url ): ?>
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $atts['title'] ); ?>">
        <?php endif; ?>
        <div class="whb-content">
            <h3><?php echo esc_html( $atts['title'] ); ?></h3>
            <p><?php echo esc_html( $atts['description'] ); ?></p>
            <div class="whb-hover">
                <?php echo esc_html( $atts['hover_description'] ); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}