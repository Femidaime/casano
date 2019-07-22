<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;
$class_gallery     = '';
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$featured_img_full = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$attachment_ids    = $product->get_gallery_image_ids();
$feature_img       = casano_resize_image( $post_thumbnail_id, null, 759, 918, true, true, false );
// $feature_img_full  = casano_resize_image( $post_thumbnail_id, null, 3000, 3000, true, true, false );

if ( count( $attachment_ids ) > 1 && has_post_thumbnail() ) {
	$class_gallery = 'casano-detail-gallery';
}
?>
<div class="casano-product-single-gallery <?php echo esc_attr( $class_gallery ); ?>">
	<?php
	if ( $attachment_ids && has_post_thumbnail() ) {
		// $thumb_size = "{$feature_img_full['width']}x{$feature_img_full['height']}";
		$html = '<div class="casano-product-gallery__image"><a href="' . esc_url( $featured_img_full[0] ) . '" data-img_width="' . esc_attr( $featured_img_full[1] ) . '" data-img_height="' . esc_attr( $featured_img_full[2] ) . '">';
		$html .= '<img width="' . esc_attr( $feature_img['width'] ) . '" height="' . esc_attr( $feature_img['height'] ) . '" src="' . esc_attr( $feature_img['url'] ) . '">';
		$html .= '</a></div>';
		foreach ( $attachment_ids as $attachment_id ) {
			$img_full           = wp_get_attachment_image_src( $attachment_id, 'full' );
			$gallery_img_thumbn = casano_resize_image( $attachment_id, null, 759, 918, true, true, false );
			$html               .= '<div class="casano-product-gallery__image"><a href="' . esc_url( $img_full[0] ) . '" data-img_width="' . esc_attr( $img_full[1] ) . '" data-img_height="' . esc_attr( $img_full[2] ) . '">';
			$html               .= '<img width="' . esc_attr( $gallery_img_thumbn['width'] ) . '" height="' . esc_attr( $gallery_img_thumbn['height'] ) . '" src="' . esc_attr( $gallery_img_thumbn['url'] ) . '">';
			$html               .= '</a></div>';
		}
		echo apply_filters( 'casano_single_product_image', $html );
	} elseif ( has_post_thumbnail() && empty( $attachment_ids ) ) {
		$html = '<div class="casano-product-gallery__image"><a href="' . esc_url( $featured_img_full[0] ) . '" data-img_width="' . esc_attr( $featured_img_full[1] ) . '" data-img_height="' . esc_attr( $featured_img_full[2] ) . '">';
		$html .= '<img width="' . esc_attr( $feature_img['width'] ) . '" height="' . esc_attr( $feature_img['height'] ) . '" src="' . esc_attr( $feature_img['url'] ) . '">';
		$html .= '</a></div>';
		echo apply_filters( 'casano_single_product_image', $html );
	}
	?>
</div>
