<?php
if (!defined('ABSPATH')) {
    exit;
}


add_action('portable_vc_addons_loaded', function() {
    if (class_exists('Vc_Manager')) {
        // Fetch all posts for dropdown
        $posts = get_posts(array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'numberposts'    => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ));
        
        $post_options = array();
        foreach ($posts as $post) {
            $post_options[$post->post_title . ' (ID: ' . $post->ID . ')'] = $post->ID;
        }

        // Fetch all grid templates from WPBakery Grid Builder (by querying the database)
        global $wpdb;

        $layout_options = array(
            __('Select Layout', 'portable-vc-addons') => '',
            __('Default Layout', 'portable-vc-addons') => 'layout-1', // Default layout option
        );
        

        vc_map(array(
            'name'        => __('Single Post', 'portable-vc-addons'),
            'base'        => 'portable_vc_single_post',
            'category'    => __('Portable VC Addons', 'portable-vc-addons'),
            'params'      => array(
                array(
                    'type'        => 'dropdown',
                    'heading'     => __('Select Post', 'portable-vc-addons'),
                    'param_name'  => 'post_id',
                    'value'       => $post_options,
                    'admin_label' => true,
                ),
                array(
                    'type'        => 'dropdown',
                    'heading'     => __('Select Layout', 'portable-vc-addons'),
                    'param_name'  => 'layout',
                    'value'       => $layout_options, // Populate with grid templates
                ),
            ),
        ));
    }
});

// Autocomplete callback function
function portable_vc_autocomplete_post_callback($query) {
    global $wpdb;
    $query = trim($query);
    
    $posts = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND post_title LIKE %s LIMIT 20",
            '%' . $wpdb->esc_like($query) . '%'
        ),
        ARRAY_A
    );
    
    $results = array();
    if ($posts) {
        foreach ($posts as $post) {
            $results[] = array(
                'value' => $post['ID'],
                'label' => $post['post_title'] . ' (ID: ' . $post['ID'] . ')',
            );
        }
    }

    wp_send_json($results); // Ensure proper JSON response
}
add_filter('vc_autocomplete_portable_vc_single_post_post_search_callback', 'portable_vc_autocomplete_post_callback', 10, 1);


// Render selected post in the autocomplete field
function portable_vc_autocomplete_post_render($value) {
    if (empty($value)) return false;
    $post = get_post($value);
    if (!$post) return false;
    return array(
        'value' => $post->ID,
        'label' => $post->post_title . ' (ID: ' . $post->ID . ')',
    );
}


