<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link       https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package    WordPress
 * @subpackage Casano
 * @since      1.0
 * @version    1.0
 */

get_header(); ?>
<?php $image404 = get_template_directory_uri() . '/assets/images/img404.jpg'; ?>
    <div class="container">
        <div class="text-center page-404">
            <figure class="heading"><img alt="<?php echo esc_attr( get_bloginfo( 'name' ) )?>" src="<?php echo  esc_url( $image404 )?>"/></figure>
            <h2 class="title"><?php esc_html_e( 'We are sorry, the page you\'ve requested is not available', 'casano' ); ?></h2>
			<?php get_search_form(); ?>
            <a class="button"
               href="<?php echo esc_url( get_home_url() ); ?>"><?php esc_html_e( 'Back To HomePage', 'casano' ); ?></a>
        </div>
    </div>
<?php get_footer();
