<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$animation_on_scroll = casano_get_option('animation_on_scroll', false);
$classes = array('post-item', 'post-classic');
if ($animation_on_scroll) {
    $classes[] = 'casano-wow fadeInUp';
}
$width = 1050;
$height = 590;
$i = 0;
?>
<?php if (have_posts()) :?>
    <div class="blog-classic content-post row auto-clear">
        <?php while (have_posts()) : the_post();?>
            <article <?php post_class($classes); ?>>
                <div class="post-inner clearfix">
                    <div class="post-thumb">
	                    <?php
	                    $i++;
	                    if(($i - 1)%3 != 0) {
		                    $width = 450;
		                    $height = 483;
	                    }else {
		                    $width = 1050;
		                    $height = 590;
                        }
	                    $thumb = casano_resize_image(get_post_thumbnail_id(), null, $width, $height, true, true, false);
	                    echo casano_img_output($thumb);
	                    ?>
                    </div>
                    <div class="post-content">
                        <div class="post-meta clearfix">
                            <?php
                            casano_post_category();
                            casano_post_date();
                            ?>
                        </div>
                        <div class="post-info">
                            <?php
                            casano_post_title();
                            casano_post_author();
                            ?>
                            <div class="post-excerpt-content">
                                <?php
                                echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 35, '');
                                ?>
                            </div>
	                        <?php
	                        casano_post_readmore();
	                        ?>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile;
        wp_reset_postdata(); ?>
    </div>
    <?php
    /**
     * Functions hooked into casano_after_blog_content action
     *
     * @hooked casano_paging_nav               - 10
     */
    do_action('casano_after_blog_content'); ?>
<?php else :
    get_template_part('content', 'none');
endif;