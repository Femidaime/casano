<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;

$product_style                 = casano_get_option( 'casano_woo_single_product_layout', 'default' );
$img_bg_color                  = casano_get_option( 'single_product_img_bg_color', 'transparent' );
$product_meta                  = get_post_meta( get_the_ID(), '_custom_product_metabox_theme_options', true );

$summary_class     = '';
$single_left_style = '';
if ( isset( $product_meta['product_style'] ) ) {
	if ( trim( $product_meta['product_style'] != '' ) && $product_meta['product_style'] != 'global' ) {
		$product_style                 = $product_meta['product_style'];
		$img_bg_color                  = isset( $product_meta['product_img_bg_color'] ) ? $product_meta['product_img_bg_color'] : 'transparent';
	}
}
$class_variable = '';
if ( $product->is_type( 'variable' ) ) { 
	$class_variable = 'has-variable';
}
?>
<div class="casano-content-single-product-mobile">
    <div id="product-<?php the_ID(); ?>" <?php post_class( $product_style . ' product-mobile-layout' ); ?>>
	    <?php
	    $size_guide_id = isset($product_meta['casano_sizeguide_options']) ? $product_meta['casano_sizeguide_options'] : 0;
	    $on_sizeguide = isset($product_meta['size_guide']) ? $product_meta['size_guide'] : false;
	    $size_guide_query = new WP_Query(array('p' => $size_guide_id,
	                                           'post_type' => 'sizeguide',
	                                           'posts_per_page' => 1
	    ));
	    if ($on_sizeguide) :
		    if ($size_guide_query->have_posts()):
			    while ($size_guide_query->have_posts()): $size_guide_query->the_post(); ?>
                    <div class="modal fade" id="popup-size-guide" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="<?php echo esc_attr__('Close', 'casano'); ?>">
								    <?php echo esc_html__('x', 'casano'); ?>
                                </button>
                                <div class="modal-inner row">
                                    <div class="size-guide-content col-lg-12">
                                        <div class="size-guide-inner">
										    <?php the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
			    <?php endwhile;
		    endif;
	    endif;
	    wp_reset_postdata();
	    ?>
        <div class="main-content-product clearfix">
            <div class="content-product-inner">
                <div class="single-left" <?php if ( $product_style == 'width_background' ) {
					echo 'style="background-color: ' . esc_attr( $img_bg_color ) . ';"';
				} ?> >
					<?php wc_get_template_part( 'single-product/product', 'image-mobile' ); ?>
					<?php
					/**
					 * @hooked casano_show_product_360deg
					 * @hooked casano_show_product_video.
					 */
					?>
                    <div class="casano-product-button">
						<?php
						do_action( 'casano_product_360deg' );
						do_action( 'casano_product_video' );
						?>
                    </div>
                </div> <!--End .Single-left -->
                <div class="detail-content">
                    <div class="summary entry-summary <?php echo esc_attr( $class_variable ); ?><?php echo esc_attr( $summary_class ); ?>">
                    	<?php
						/**
						 * woocommerce_single_product_summary hook.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked casano_open_product_mobile_more_detail_wrap - 25
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 * @hooked fami_woocommerce_output_product_data_tabs_mobile() - 115
						 * @hooked casano_close_product_mobile_more_detail_wrap() - 120
						 */
						do_action( 'woocommerce_single_product_summary' );
						?>
                    </div><!-- .summary -->
                </div> <!--End .detail-content -->
            </div>
        </div><!--End .main-content-product -->
		<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10 // Removed on mobile
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
		?>
    </div><!-- #product-<?php the_ID(); ?> -->
	<?php do_action( 'woocommerce_after_single_product' ); ?>
	<?php if ( $product->is_purchasable() || $product->is_type( 'external' ) || $product->is_type( 'grouped' ) ) { ?>
		<?php if ( $product->is_in_stock() ) { ?>
            <button type="button"
                    class="casano-single-add-to-cart-btn add-to-cart-fixed-btn btn button"><span
                        class="icon icon-basket"></span> <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
            </button>
		<?php } else { ?>
            <button type="button"
                    class="casano-single-add-to-cart-btn add-to-cart-out-of-stock add-to-cart-fixed-btn btn button"><span
                        class="icon icon-basket"></span> <?php esc_html_e( 'Out Of Stock', 'casano' ); ?>
            </button>
		<?php } ?>
	<?php } ?>
</div>
