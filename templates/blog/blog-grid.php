<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$animation_on_scroll = casano_get_option('animation_on_scroll', false);
$classes = array('post-item', 'post-grid');
$classes[] = 'col-bg-4';
$classes[] = 'col-lg-4';
$classes[] = 'col-md-4';
$classes[] = 'col-sm-6';
$classes[] = 'col-xs-6';
$classes[] = 'col-ts-12';
if ($animation_on_scroll) {
    $classes[] = 'casano-wow fadeInUp';
}
?>
<?php if (have_posts()) : ?>
    <div class="blog-grid content-post row auto-clear equal-container better-height">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class($classes); ?>>
                <div class="post-inner">
                    <?php casano_post_thumbnail(); ?>
                    <div class="post-content">
                        <div class="post-info equal-elem">
                            <?php
                            casano_post_title();
                            ?>
                            <div class="post-excerpt-content">
                                <?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 14, esc_html__( '...', 'casano' ) ); ?>
                            </div>
                        </div>
                        <div class="post-foot">
                            <a class="readmore-sc"
                               href="<?php the_permalink(); ?>"><?php echo esc_html__( 'Read more', 'casano' ); ?></a>
                        </div>
                        <div class="post-meta clearfix">
		                    <?php
		                    casano_post_datebox();
		                    casano_post_comment();
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