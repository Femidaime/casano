<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$casano_woo_product_style = casano_get_option( 'casano_shop_product_style', 1 );
$enable_products_sizes    = casano_get_option( 'enable_products_sizes', false );
/*
 * 5 items: col-bg-15 col-lg-15 col-md-15 col-sm-3 col-xs-4 col-ts-6
 * 4 items: col-bg-3 col-lg-3 col-md-4 col-sm-4 col-xs-6 col-ts-12
 * 3 items: col-bg-4 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-ts-12
 */
$casano_woo_bg_items = 3;     // 15
$casano_woo_lg_items = 3;     // 15
$casano_woo_md_items = 4;     // 15
$casano_woo_sm_items = 6;     // 3
$casano_woo_xs_items = 6;     // 4
$casano_woo_ts_items = 12;    // 6

$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
if ( $enable_single_product_mobile && casano_is_mobile() ) {
	$casano_woo_bg_items      = 15;     // 15
	$casano_woo_lg_items      = 15;     // 15
	$casano_woo_md_items      = 15;     // 15
	$casano_woo_sm_items      = 3;      // 3
	$casano_woo_xs_items      = 4;      // 4
	$casano_woo_ts_items      = 6;      // 6
	$casano_woo_product_style = 1;      // Always use product style 1 on real mobile
}

$shop_type = casano_get_option( 'shop_type', '' );
if ( $shop_type == 'masonry' ){
	$casano_woo_product_style = 2;
}

// Custom columns
if ( ! $enable_products_sizes ) {
	$casano_woo_bg_items = casano_get_option( 'casano_woo_bg_items', 3 );
	$casano_woo_lg_items = casano_get_option( 'casano_woo_lg_items', 3 );
	$casano_woo_md_items = casano_get_option( 'casano_woo_md_items', 4 );
	$casano_woo_sm_items = casano_get_option( 'casano_woo_sm_items', 4 );
	$casano_woo_xs_items = casano_get_option( 'casano_woo_xs_items', 6 );
	$casano_woo_ts_items = casano_get_option( 'casano_woo_ts_items', 6 );
}
$animate_class = 'famiau-wow-continuous auto-clear casano-wow fadeInUp';
$classes[]     = 'product-item';
$shop_type     = casano_get_option( 'shop_type', '' );
if ( $shop_type == 'masonry' ) {
	$classes[] = 'isotope-item';
}
$classes[] = 'rows-space-30';
$classes[] = 'col-bg-' . $casano_woo_bg_items;
$classes[] = 'col-lg-' . $casano_woo_lg_items;
$classes[] = 'col-md-' . $casano_woo_md_items;
$classes[] = 'col-sm-' . $casano_woo_sm_items;
$classes[] = 'col-xs-' . $casano_woo_xs_items;
$classes[] = 'col-ts-' . $casano_woo_ts_items;
$classes[] = $animate_class;

$template_style = 'style-' . $casano_woo_product_style;
$classes[]      = 'style-' . $casano_woo_product_style;
?>
<li <?php post_class( $classes ); ?>>
	<?php wc_get_template_part( 'product-styles/content-product', $template_style ); ?>
</li>
