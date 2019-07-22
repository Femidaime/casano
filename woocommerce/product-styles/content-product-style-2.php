<?php
/*
     Name: Product style 2
     Slug: content-product-style-2
*/

$args = isset($args) ? $args : null;
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
?>
<div class="product-inner tooltip-style-02">
    <div class="product-thumb">
        <?php
        /**
         * woocommerce_before_shop_loop_item_title hook.
         *
         * @hooked woocommerce_show_product_loop_sale_flash - 10
         * @hooked woocommerce_template_loop_product_thumbnail - 10
         */
        do_action('woocommerce_before_shop_loop_item_title', $args);
        ?>
        <div class="button-loop-action">
            <div class="add-to-cart">
		        <?php do_action('woocommerce_after_shop_loop_item'); ?>
            </div>
            <?php
            do_action('casano_function_shop_loop_item_quickview');
            do_action('fami_wccp_shop_loop');
            do_action('casano_function_shop_loop_item_compare');
            ?>
        </div>
    </div>
    <div class="product-info">
        <?php
        /**
         * woocommerce_after_shop_loop_item_title hook.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price - 10
         */
        do_action('woocommerce_after_shop_loop_item_title');
        do_action('casano_function_shop_loop_item_wishlist');
        ?>
    </div>
</div>
<?php
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );