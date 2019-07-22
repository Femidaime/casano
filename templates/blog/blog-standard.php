<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$animation_on_scroll = casano_get_option( 'animation_on_scroll', false );
$classes[]           = 'post-item post-standard';
if ( $animation_on_scroll ) {
	$classes[] = 'casano-wow fadeInUp';
}
?>
<?php
if ( have_posts() ) : ?>
	<?php do_action( 'casano_before_blog_content' ); ?>
    <div class="blog-standard content-post">
		<?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( $classes ); ?>>
				<?php casano_post_format(); ?>
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
			            <?php
			            $enable_except_post = casano_get_option( 'enable_except_post', '' );
			            if ( $enable_except_post == 1 ) {
				            casano_post_excerpt();
			            } else {
				            casano_post_full_content();
			            }
			            ?>
			            <?php
			            if ( $enable_except_post == 1 ) {
				            casano_post_readmore();
			            } else {
				            casano_post_tags();
			            }
			            ?>
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
	do_action( 'casano_after_blog_content' ); ?>
<?php else :
	get_template_part( 'content', 'none' );
endif; ?>