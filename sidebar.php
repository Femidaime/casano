<?php
$casano_blog_used_sidebar = casano_get_option( 'blog_sidebar', 'primary_sidebar' );
if ( is_single() ) {
    $casano_blog_used_sidebar = casano_get_option( 'single_post_sidebar', 'primary_sidebar' );
}
?>
<?php if ( is_active_sidebar( $casano_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area">
        <?php dynamic_sidebar( $casano_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>