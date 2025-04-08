<style>
    .blog_single_post .vc_single_image-img{
        width: 100%;
        height: auto;
    }
    .blog_single_post .container .row{
        box-shadow: 0 0 50px 25px #c3c2c270;
        margin-top: 50px;
        margin-bottom: 50px;
    }
    .blog_single_post .container{
        padding: 0 10px;
    }
    .blog_single_post_content{
        padding: 30px;
    }
    .blog_single_post_content h1{
        font-size: 36px;
        font-weight: 500;
        color: #3F3F3F;
    }
    .blog_single_post_content p{
        font-size: 16px;
        font-weight: 400;
        margin-top: 15px;
        color: #666666;
        /* text-align: justify;
        text-justify: inter-word; */
    }
    .blog_single_post_content p a{
        color: #0094FF;
        text-decoration: none;
    }
    .blog_post_single_item > .vc_grid-item-mini{
        box-shadow: 0 0 50px 25px #c3c2c270;
        border-radius: 8px;
    }
    @media only screen and (max-width: 991px){
        /*Tablets [601px -> 1200px]*/
        .vc_row.wpb_row.vc_row-fluid{
            overflow: hidden;
        }
        .blog_single_post_content h1{
            font-size: 24px;
        }
        .blog_single_post_content p{
            font-size: 12px;
        }
    }
</style>
<?php
// Get the post by ID
$post = get_post($atts['post_id']);

// Check if post exists
if ($post) {
    // Get featured image ID
    $featured_image_id = get_post_thumbnail_id($post->ID);
    $featured_image_url = get_the_post_thumbnail_url($post->ID, 'large'); // Custom size
    $post_title = esc_attr($post->post_title);
    // Prepare the heading content
    $heading_content = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content, 100);
    $heading_content .= '...<a href="' . get_permalink($post->ID) . '">Read More</a>';
    
    // Build the dynamic shortcode
    $shortcode = sprintf(
        '[vc_section el_class="blog_single_post"][vc_row][vc_column width="1/2"][vc_single_image image="%d" img_size="full"][/vc_column][vc_column width="1/2"][ultimate_heading main_heading="%s" heading_tag="h1" alignment="left"]%s[/ultimate_heading][/vc_column][/vc_row][/vc_section]',
        $featured_image_id,
        esc_attr($post->post_title),
        $heading_content
    );
    $html_content = "
        <section class='blog_single_post'>
            <div class='container'>            
                <div class='row'>
                    <div class='col-12 col-lg-6 d-flex p-0'>
                        <img decoding='async' width='313' height='161' src='$featured_image_url' class='vc_single_image-img' alt='$post_title'>
                    </div>
                    <div class='col-12 col-lg-6 p-0'>
                        <div class='blog_single_post_content'>
                            <h1>
                                $post_title
                            </h1>
                            <p>
                                $heading_content
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        ";
    
    // Output the shortcode
    echo $html_content;
    
    // Reset post data
    wp_reset_postdata();
} else {
    echo '<p>Post not found</p>';
}
?>