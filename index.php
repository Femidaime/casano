<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Casano
 */

get_header();
$term_id       = get_queried_object_id();
$sidebar_isset = wp_get_sidebars_widgets();

/* Blog Layout */
$casano_blog_layout       = casano_get_option( 'casano_blog_layout', 'left' );
$casano_blog_style        = casano_get_option( 'blog-style', 'standard' );
$casano_blog_used_sidebar = casano_get_option( 'blog_sidebar', 'primary_sidebar' );
$casano_container_class   = array( 'main-container' );

if ( is_single() ) {
	
	/*Single post layout*/
	$casano_blog_layout       = casano_get_option( 'sidebar_single_post_position', 'left' );
	$casano_blog_used_sidebar = casano_get_option( 'single_post_sidebar', 'primary_sidebar' );
}

if ( isset( $sidebar_isset[ $casano_blog_used_sidebar ] ) && empty( $sidebar_isset[ $casano_blog_used_sidebar ] ) ) {
	$casano_blog_layout = 'full';
}

if ( $casano_blog_layout == 'full' ) {
	$casano_container_class[] = 'no-sidebar';
} else {
	$casano_container_class[] = $casano_blog_layout . '-sidebar has-sidebar';
}

$casano_content_class   = array();
$casano_content_class[] = 'main-content';

if ( $casano_blog_layout == 'full' ) {
	$casano_content_class[] = 'col-sm-12 col-xs-12';
} else {
	$casano_content_class[] = 'col-lg-9 col-md-8 col-sm-12 col-xs-12';
}

$casano_sidebar_class   = array();
$casano_sidebar_class[] = 'sidebar';

if ( $casano_blog_layout != 'full' ) {
	$casano_sidebar_class[] = 'col-lg-3 col-md-4 col-sm-12 col-xs-12';
}

?>
<div class="<?php echo esc_attr( implode( ' ', $casano_container_class ) ); ?>">
    <!-- POST LAYOUT -->
	<?php if ( is_single() ) { ?>
        <div class="casano-breadcrumb">
            <div class="container">
			    <?php get_template_part( 'template-parts/part', 'breadcrumb' ); ?>
            </div>
        </div>
	<?php } ?>
    <div class="container">
        <div class="row">
            <div class="<?php echo esc_attr( implode( ' ', $casano_content_class ) ); ?>">
				<?php
				if ( is_single() ) {
					while ( have_posts() ): the_post();
						get_template_part( 'templates/blog/blog', 'single' );
						/*If comments are open or we have at least one comment, load up the comment template.*/
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;
					endwhile;
					wp_reset_postdata();
				} else {
					get_template_part( 'templates/blog/blog', $casano_blog_style );
				} ?>
            </div>
			<?php if ( $casano_blog_layout != 'full' ): ?>
                <div class="<?php echo esc_attr( implode( ' ', $casano_sidebar_class ) ); ?>">
					<?php get_sidebar(); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>

