<?php
$query = new WP_Query(array(
    'post_type'      => $atts['post_type'],
    'posts_per_page' => $atts['count'],
));
$is_arrows = $atts['arrows']??'no';
$is_bullets = $atts['bullets']??'no';


if ($query->have_posts()) :
?>

<!-- Swiper -->
<div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="swiper-slide">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="slider-image"><?php the_post_thumbnail(); ?></div>
                <?php endif; ?>
                <div class="slider-content">
                    <h3><?php the_title(); ?></h3>
                    <div class="slider-excerpt"><?php the_excerpt(); ?></div>
                </div>
            </div>
        <?php endwhile; ?>
      </div>
    <?php if($is_arrows == 'yes'): ?>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    <?php endif; ?>
    <?php if($is_bullets == 'yes'): ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>


<?php
endif;
wp_reset_postdata();
?>