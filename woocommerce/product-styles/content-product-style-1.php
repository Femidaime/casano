<?php
/*
     Name: Product style 1
     Slug: content-product-style-1
*/

$args = isset($args) ? $args : null;
?>
<div class="product-inner tooltip-style-01">
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
        <?php if ( class_exists( 'YITH_WCWL' ) ) {?>
            <div class="button-loop-action">
                <?php
                do_action('fami_wccp_shop_loop');
                do_action('casano_function_shop_loop_item_compare');
                do_action('casano_function_shop_loop_item_quickview');
                ?>
            </div>
        <?php }?>
    </div>
    <div class="product-info equal-elem">
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
        <div class="add-to-cart">
		    <?php do_action('woocommerce_after_shop_loop_item'); ?>
        </div>
    </div>
</div>
