<?php
if (!defined('ABSPATH')) exit;

add_action('vc_before_init', 'portable_vc_add_html_table');
function portable_vc_add_html_table() {
    vc_map(array(
        'name' => __('HTML Table', 'portable-vc-addons'),
        'base' => 'portable_vc_html_table',
        'category' => __('Portable VC Addons', 'portable-vc-addons'),
        'icon' => 'dashicons-table-col-after',
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Table Heading', 'portable-vc-addons'),
                'param_name' => 'table_heading',
                'description' => __('Optional heading/title shown above the table.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __('Use first row as header?', 'portable-vc-addons'),
                'param_name' => 'use_header',
                'description' => __('Render the first row as table headers (<th>) instead of normal cells.', 'portable-vc-addons'),
                'value' => array(__('Yes', 'portable-vc-addons') => 'yes'),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Header Alignment', 'portable-vc-addons'),
                'param_name' => 'header_alignment',
                'value' => array(
                    'Left' => 'left',
                    'Center' => 'center',
                    'Right' => 'right',
                ),
                'std' => 'center',
                'description' => __('Align text in header row.', 'portable-vc-addons'),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __('Content Alignment', 'portable-vc-addons'),
                'param_name' => 'content_alignment',
                'value' => array(
                    'Left' => 'left',
                    'Center' => 'center',
                    'Right' => 'right',
                ),
                'std' => 'left',
                'description' => __('Align text in all content (non-header) cells.', 'portable-vc-addons'),
            ),

            array(
                'type' => 'textfield',
                'heading' => __('Rows', 'portable-vc-addons'),
                'param_name' => 'rows',
                'admin_label' => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Columns', 'portable-vc-addons'),
                'param_name' => 'cols',
                'admin_label' => true,
            ),
            array(
                'type' => 'param_group',
                'heading' => __('Table Cells', 'portable-vc-addons'),
                'param_name' => 'table_data',
                'params' => array(
                    array(
                        'type' => 'textfield',
                        'heading' => __('Cell Content', 'portable-vc-addons'),
                        'param_name' => 'cell',
                    )
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Custom CSS Class', 'portable-vc-addons'),
                'param_name' => 'custom_class',
                'description' => __('Optional class name to add to the <table> element.', 'portable-vc-addons'),
            ),
        )
    ));
}

//Enquee
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script(
        'portable-vc-table-admin',
        PORTABLE_VC_ADDONS_URL . '/addons/custom-table/assets/js/portable-vc-table-admin.js',
        ['jquery'],
        time(),
        PORTABLE_VC_ADDONS_SCRIPT_VERSION,
        true
    );
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'portable-vc-table',
        PORTABLE_VC_ADDONS_URL . '/addons/custom-table/assets/css/portable-vc-table.css',
        [],
        time(),
        PORTABLE_VC_ADDONS_SCRIPT_VERSION
    );
});

add_shortcode('portable_vc_html_table', 'portable_vc_html_table_output');
function portable_vc_html_table_output($atts) {
    $atts = shortcode_atts(array(
        'rows' => 2,
        'cols' => 2,
        'use_header' => '',
        'table_data' => '',
        'header_alignment' => 'center',
        'content_alignment' => 'left',
        'custom_class' => '',
        'table_heading' => '',
    ), $atts);

    $table_heading = esc_attr($atts['table_heading']);
    $header_align = esc_attr($atts['header_alignment']);
    $content_align = esc_attr($atts['content_alignment']);

    
    $rows = intval($atts['rows']);
    $cols = intval($atts['cols']);
    $custom_class = !empty($atts['custom_class']) ? esc_attr($atts['custom_class']) : '';
    $data = vc_param_group_parse_atts($atts['table_data']);
    $use_header = $atts['use_header'] === 'yes';

    if ($rows <= 0 || $cols <= 0) return '<p>Invalid table dimensions</p>';

    $header_style = "font-weight: bold; padding: 8px;";
    $cell_style = "padding: 8px;";

    $class_attr = $custom_class ? ' class="' . esc_attr($custom_class) . '"' : '';
    $html = '<div class="responsive-table ' . $custom_class . '">';

    if (!empty($table_heading)) {
        $html .= '<h3 class="table-heading">' . esc_html($table_heading) . '</h3>';
    }

    $html .= '<table' . $class_attr . ' style="width:100%; border-collapse:collapse;" border="1">';

    $cell_index = 0;

    for ($i = 0; $i < $rows; $i++) {
        $html .= '<tr>';
        for ($j = 0; $j < $cols; $j++) {
            $cell_content = isset($data[$cell_index]['cell']) ? esc_html($data[$cell_index]['cell']) : '';
            $is_header = $use_header && $i === 0;
            $tag = ($use_header && $i === 0) ? 'th' : 'td';
            $alignment = ($use_header && $i === 0) ? $header_align : $content_align;
            $style = $is_header ? $header_style : "padding: 8px;";
            $html .= "<$tag style='$style; padding:8px; text-align: $alignment;'>$cell_content</$tag>";
            $cell_index++;
        }
        $html .= '</tr>';
    }

    $html .= '</table></div>';
    return $html;
}