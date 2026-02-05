<?php
if (!defined('ABSPATH')) exit;

/*--------------------------------------------------------------
 VC MAP
--------------------------------------------------------------*/
add_action('vc_before_init', 'portable_vc_posts_grid_map');

function portable_vc_posts_grid_map() {
    vc_map(array(
        'name'     => __('Posts Grid (Load More)', 'portable-vc'),
        'base'     => 'portable_posts_grid',
        'category' => __('Portable VC Addons', 'portable-vc'),
        'icon'     => 'icon-wpb-application-icon-large',
        'params'   => array(

            array(
                'type'       => 'textfield',
                'heading'    => __('Posts Per Row', 'portable-vc'),
                'param_name' => 'posts_per_row',
                'value'      => '3',
            ),

            array(
                'type'       => 'textfield',
                'heading'    => __('Rows Per Page', 'portable-vc'),
                'param_name' => 'rows_per_page',
                'value'      => '2',
            ),

            array(
                'type'       => 'checkbox',
                'heading'    => __('Categories', 'portable-vc'),
                'param_name' => 'categories',
                'value'      => portable_vc_posts_grid_categories(),
            ),
        )
    ));
}

/*--------------------------------------------------------------
 Categories Helper
--------------------------------------------------------------*/
function portable_vc_posts_grid_categories() {
    $cats = get_categories(array('hide_empty' => false));
    $out  = array();
    foreach ($cats as $cat) {
        $out[$cat->name] = $cat->slug; // FIXED: value as slug, label as name
    }
    return $out;
}

/*--------------------------------------------------------------
 Shortcode
--------------------------------------------------------------*/
add_shortcode('portable_posts_grid', 'portable_posts_grid_render');

function portable_posts_grid_render($atts) {

    $atts = shortcode_atts(array(
        'posts_per_row' => 3,
        'rows_per_page' => 2,
        'categories'    => '',
    ), $atts);

    // FIXED: Get selected categories from comma-separated string
    $selected_cats = array();
    if (!empty($atts['categories'])) {
        // VC checkbox returns comma-separated string of selected values
        $selected_cats = array_map('trim', explode(',', $atts['categories']));
    }
    $cats_query = implode(',', $selected_cats);

    // Total posts per page
    $posts_per_page = intval($atts['posts_per_row']) * intval($atts['rows_per_page']);

    // Enqueue CSS & JS for the addon
    $base = PORTABLE_VC_ADDONS_URL . 'addons/vc-posts-grid/assets/';

    wp_enqueue_style(
        'portable-posts-grid',
        $base . 'css/posts-grid.css',
        array(),
        time()
    );

    wp_enqueue_script(
        'portable-posts-grid',
        $base . 'js/posts-grid.js',
        array('jquery'),
        time(),
        true
    );

    wp_localize_script('portable-posts-grid', 'PortablePostsGrid', array(
        'ajax' => admin_url('admin-ajax.php')
    ));

    ob_start(); ?>
    <div class="portable-posts-grid"
         data-columns="<?php echo intval($atts['posts_per_row']); ?>"
         data-limit="<?php echo intval($posts_per_page); ?>"
         data-page="1"
         data-categories="<?php echo esc_attr($cats_query); ?>"
         style="--cols:<?php echo intval($atts['posts_per_row']); ?>">

        <div class="portable-posts-grid-inner">
            <?php echo portable_posts_grid_query($posts_per_page, 1, $selected_cats); ?>
        </div>

        <div class="portable-grid-actions">
            <div class="portable-loader" style="display:none;"></div>
            <button class="portable-load-more">Load More</button>
            <button class="portable-load-less" style="display:none;">Load Less</button>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/*--------------------------------------------------------------
 Query Function
--------------------------------------------------------------*/
function portable_posts_grid_query($limit, $paged, $categories = array()) {

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'paged'          => $paged,
        'post_status'    => 'publish',
    );

    // FIXED: Only add category filter if categories are actually selected
    if (!empty($categories) && is_array($categories)) {
        $cats_query = implode(',', $categories);
        $args['category_name'] = $cats_query;
    }

    $q = new WP_Query($args);
    $html = '';

    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $html .= '<div class="portable-post-item">';
            $html .= '<a href="' . esc_url(get_permalink()) . '">';
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail(get_the_ID(), 'medium');
            }
            $html .= '<h3>' . esc_html(get_the_title()) . '</h3>';
            $html .= '</a>';
            $html .= '</div>';
        }
    }

    wp_reset_postdata();
    return $html;
}

/*--------------------------------------------------------------
 AJAX Load More
--------------------------------------------------------------*/
add_action('wp_ajax_portable_posts_grid_load', 'portable_posts_grid_load');
add_action('wp_ajax_nopriv_portable_posts_grid_load', 'portable_posts_grid_load');

function portable_posts_grid_load() {

    $page      = intval($_POST['page']);
    $limit     = intval($_POST['limit']);
    $cats      = !empty($_POST['categories']) ? array_map('trim', explode(',', sanitize_text_field($_POST['categories']))) : array();

    // Get the HTML
    $html = portable_posts_grid_query($limit, $page, $cats);
    
    // Calculate total pages
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'paged'          => $page,
        'post_status'    => 'publish',
    );
    
    if (!empty($cats) && is_array($cats)) {
        $cats_query = implode(',', $cats);
        $args['category_name'] = $cats_query;
    }
    
    // Get total count for pagination info
    $count_query = new WP_Query(array_merge($args, array('posts_per_page' => -1, 'fields' => 'ids')));
    $total_posts = $count_query->found_posts;
    $total_pages = ceil($total_posts / $limit);
    
    wp_reset_postdata();
    
    // Return JSON response
    wp_send_json(array(
        'html' => $html,
        'has_more' => $page < $total_pages,
        'total_pages' => $total_pages,
        'current_page' => $page
    ));
}