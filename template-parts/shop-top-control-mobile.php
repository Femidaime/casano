<?php if (!is_product()) { ?>
	<?php
	$shop_page_id = wc_get_page_id('shop');
	$shop_page_url = get_permalink($shop_page_id);
	$enable_products_sizes = casano_get_option('enable_products_sizes', false);
	$shop_display_mode = 'grid';
	$shop_mode_grid_url = add_query_arg('shop_display_mode', 'grid');
	$shop_mode_list_url = add_query_arg('shop_display_mode', 'list');

	?>
    <div class="container">
        <div class="row mobile-shop-real">
            <h2 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h2>
        </div>
    </div>
	<?php if ( class_exists( 'PrdctfltrInit' ) || class_exists( 'XforWC_Product_Filters' )) { ?>
        <div class="shop-prdctfltr-filter-wrap">
			<?php woocommerce_catalog_ordering(); ?>
        </div>
	<?php } ?>
    <div class="toolbar-products toolbar-products-mobile toolbar-top">
        <div class="part-wrap part-filter-wrap">
	        <?php if ( class_exists( 'PrdctfltrInit' ) || class_exists( 'XforWC_Product_Filters' )) { ?>
                <div class="actions-wrap clearfix">
                    <div class="action-mini">
						<?php casano_woocommerce_catalog_ordering(); ?>
                    </div>
                    <div class="action-mini">
                        <a class="filter-toggle filter-toggle-button" href="javascripti:void(0);"><?php esc_html_e( 'Filter', 'casano' ); ?></a>
                    </div>
                </div>
			<?php } ?>
        </div>
    </div>
<?php }; ?>