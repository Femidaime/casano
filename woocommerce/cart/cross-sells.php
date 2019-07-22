<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     4.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
$classes                   = array();
$casano_woo_product_style = 1;
$casano_enable_cross_sell = casano_get_option( 'enable_cross_sell', 'yes' );
if ( $casano_enable_cross_sell != 'yes' ) {
	return;
}

$classes[]      = 'product-item style-' . $casano_woo_product_style;
$template_style = 'style-' . $casano_woo_product_style;

$woo_crosssell_ls_items = casano_get_option( 'casano_woo_crosssell_ls_items', 4 );
$woo_crosssell_lg_items = casano_get_option( 'casano_woo_crosssell_lg_items', 4 );
$woo_crosssell_md_items = casano_get_option( 'casano_woo_crosssell_md_items', 3 );
$woo_crosssell_sm_items = casano_get_option( 'casano_woo_crosssell_sm_items', 2 );
$woo_crosssell_xs_items = casano_get_option( 'casano_woo_crosssell_xs_items', 2 );
$woo_crosssell_ts_items = casano_get_option( 'casano_woo_crosssell_ts_items', 2 );

$data_reponsive = array(
	'0'    => array(
		'items' => $woo_crosssell_ts_items,
        'margin' => 20,
	),
	'480'  => array(
		'items' => $woo_crosssell_xs_items,
        'margin' => 20,
	),
	'768'  => array(
		'items' => $woo_crosssell_sm_items,
        'margin' => 20,
	),
	'992'  => array(
		'items' => $woo_crosssell_md_items,
        'margin' => 22,
	),
	'1200' => array(
		'items' => $woo_crosssell_lg_items,
        'margin' => 22,
	),
	'1500' => array(
		'items' => $woo_crosssell_ls_items,
        'margin' => 22,
	),
);

$data_reponsive = json_encode( $data_reponsive );
$loop           = 'false';
$data_margin       = '22';
$woo_cross_sell_title = casano_get_option( 'casano_cross_sells_products_title', 'You may be interested in...' );

if ( $cross_sells ) : ?>

	<div class="cross-sells products product-grid">

		<h2 class="product-grid-title"><?php echo esc_html( $woo_cross_sell_title ); ?></h2>

		<div class="owl-carousel owl-products nav2 top-right equal-container better-height nav-center nav-circle" data-nav="true" data-dots="true"
			 data-loop="<?php echo esc_attr($loop); ?>" data-responsive='<?php echo esc_attr( $data_reponsive ); ?>'>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>
				<div <?php post_class( $classes ) ?>>
					<?php
					$post_object = get_post( $cross_sell->get_id() );

					setup_postdata( $GLOBALS[ 'post' ] =& $post_object );

					wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
				</div>
			<?php endforeach; ?>

		</div>

	</div>

<?php endif;

wp_reset_postdata();
