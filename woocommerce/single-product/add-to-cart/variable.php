<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
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
 * @version 3.5.5
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;
$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<?php  if ( $enable_single_product_mobile && casano_is_mobile() ) { ?>
    <form class="variations_form cart variable_mobile" method="post" enctype='multipart/form-data'
          data-product_id="<?php echo absint( $product->get_id() ); ?>"
          data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
        <?php do_action( 'woocommerce_before_variations_form' ); ?>
        <?php
        wc_get_template_part( 'single-product/product', 'image-mobile' );
        ?>
        <div class="single_variation_wrap">
            <?php
            /**
             * woocommerce_before_single_variation Hook.
             */
            do_action( 'woocommerce_before_single_variation' );
            /**
             * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
             *
             * @since  2.4.0
             * @hooked woocommerce_single_variation - 10 Empty div for variation data.
             * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
             */
            do_action( 'woocommerce_single_variation' );
            /**
             * woocommerce_after_single_variation Hook.
             */

            do_action( 'woocommerce_after_single_variation' );
            ?>
        </div>
        <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
            <p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'casano' ); ?></p>
        <?php else : ?>
            <table class="variations" cellspacing="0">
                <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                    <tr class="variation">
                        <td class="variation-title">
                            <label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label>
                        </td>
                        <td class="value">
                            <?php
                            $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) : $product->get_variation_default_attribute( $attribute_name );
                            wc_dropdown_variation_attribute_options( array(
                                'options'   => $options,
                                'attribute' => $attribute_name,
                                'product'   => $product,
                                'selected'  => $selected
                            ) );
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php echo wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'casano' ) . '</a>' ) ); ?>

            <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <?php endif; ?>

        <?php do_action( 'woocommerce_after_variations_form' ); ?>
    </form>
<?php }else{ ?>
    <form class="variations_form cart" method="post" enctype='multipart/form-data'
          data-product_id="<?php echo absint( $product->get_id() ); ?>"
          data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
        <?php do_action( 'woocommerce_before_variations_form' ); ?>

        <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
            <p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'casano' ); ?></p>
        <?php else : ?>
            <table class="variations" cellspacing="0">

                <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                    <tr class="variation">
                        <td class="variation-title">
                            <label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label>
                        </td>
                        <td class="value">
                            <?php
                            $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) : $product->get_variation_default_attribute( $attribute_name );
                            wc_dropdown_variation_attribute_options( array(
                                'options'   => $options,
                                'attribute' => $attribute_name,
                                'product'   => $product,
                                'selected'  => $selected
                            ) );
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <?php echo wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'casano' ) . '</a>' ) ); ?>
            <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

            <div class="single_variation_wrap">
                <?php
                /**
                 * woocommerce_before_single_variation Hook.
                 */
                do_action( 'woocommerce_before_single_variation' );

                /**
                 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
                 *
                 * @since  2.4.0
                 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                 */
                do_action( 'woocommerce_single_variation' );

                /**
                 * woocommerce_after_single_variation Hook.
                 */
                do_action( 'woocommerce_after_single_variation' );
                ?>
            </div>


        <?php endif; ?>

        <?php do_action( 'woocommerce_after_variations_form' ); ?>
    </form>
<?php } ?>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
