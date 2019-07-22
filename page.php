<?php
/**
 * The template for displaying all default single pages
 *
 * @since 1.0.0
 */

get_header();

$sidebar_isset = wp_get_sidebars_widgets();

/* Data MetaBox */
$data_meta = get_post_meta( get_the_ID(), '_custom_page_side_options', true );

/* Data MetaBox */
$data_meta_banner = get_post_meta( get_the_ID(), '_custom_metabox_theme_options', true );

/*Default page layout*/
$casano_page_extra_class = isset( $data_meta['page_extra_class'] ) ? $data_meta['page_extra_class'] : '';
$casano_page_layout      = isset( $data_meta['sidebar_page_layout'] ) ? $data_meta['sidebar_page_layout'] : 'left';
$casano_page_sidebar     = isset( $data_meta['page_sidebar'] ) ? $data_meta['page_sidebar'] : 'primary_sidebar';
if ( isset( $sidebar_isset[ $casano_page_sidebar ] ) && empty( $sidebar_isset[ $casano_page_sidebar ] ) ) {
	$casano_page_layout = 'full';
}

/*Main container class*/
$casano_main_container_class   = array();
$casano_main_container_class[] = $casano_page_extra_class;
$casano_main_container_class[] = 'main-container';
if ( $casano_page_layout == 'full' ) {
	$casano_main_container_class[] = 'no-sidebar';
} else {
	$casano_main_container_class[] = $casano_page_layout . '-sidebar has-sidebar';
}
$casano_main_content_class   = array();
$casano_main_content_class[] = 'main-content';
if ( $casano_page_layout == 'full' ) {
	$casano_main_content_class[] = 'col-sm-12';
} else {
	$casano_main_content_class[] = 'col-lg-9 col-md-8 col-sm-8 col-xs-12';
}
$casano_slidebar_class   = array();
$casano_slidebar_class[] = 'sidebar';
if ( $casano_page_layout != 'full' ) {
	$casano_slidebar_class[] = 'col-lg-3 col-md-4 col-sm-4 col-xs-12';
}

?>
    <main class="site-main <?php echo esc_attr( implode( ' ', $casano_main_container_class ) ); ?>">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr( implode( ' ', $casano_main_content_class ) ); ?>">
					<?php if ( isset( $data_meta['bg_banner_page'] ) && $data_meta['bg_banner_page'] != '' ) : ?>
                        <h2 class="page-title">
                            <span><?php single_post_title(); ?></span>
                        </h2>
					<?php endif;
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							?>
                            <div class="page-main-content">
								<?php
								the_content();
								wp_link_pages(
									array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'casano' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'casano' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									)
								);
								?>
                            </div>
							<?php
							
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							};
						}
					}
					?>
                </div>
				<?php if ( $casano_page_layout != "full" ):
					if ( is_active_sidebar( $casano_page_sidebar ) ) { ?>
                        <div id="widget-area"
                             class="widget-area <?php echo esc_attr( implode( ' ', $casano_slidebar_class ) ); ?>">
							<?php dynamic_sidebar( $casano_page_sidebar ); ?>
                        </div><!-- .widget-area -->
					<?php };
				endif; ?>
            </div>
        </div>
    </main>
<?php get_footer();