<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       4.0.0
 */

$shop_type = casano_get_option( 'shop_type', '' );
$products_class =  array( 'product-grid' );
if ( $shop_type == 'masonry' ){
	$products_class[] = 'casano-isotope';
}
?>
<ul class="row products  equal-container <?php echo esc_attr( implode( ' ', $products_class ) ); ?> better-height products_list-size-default">
<?php
$shop_type     = casano_get_option( 'shop_type', '' );
if ( $shop_type == 'masonry' ) {?>
	<li class="isotope-sizer"></li>
<?php }