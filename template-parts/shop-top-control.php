<?php if ( ! is_product() ) { ?>
	<?php
	$page_id               = wc_get_page_id( 'shop' );
	$page_url              = get_permalink( $page_id );
	$enable_products_sizes = casano_get_option( 'enable_products_sizes', false );
	$product_size_active   = isset( $_REQUEST['products_size'] ) ? $_REQUEST['products_size'] : 'size-4';
	if ( ! in_array( $product_size_active, array( 'size-3', 'size-4', 'size-5', 'size-6' ) ) ) {
		$product_size_active = 'size-3';
	}
	$shop_mode_grid_url    = add_query_arg( 'shop_display_mode', 'grid' );
	$shop_mode_list_url    = add_query_arg( 'shop_display_mode', 'list' );
	$casano_woo_shop_layout = casano_get_option( 'sidebar_shop_page_position', 'left' );
	$filter_shop_page      = casano_get_option( 'filter_shop_page', 'top_sidebar' );
	?>
    <div class="toolbar-products toolbar-top">

            <div class="part-wrap part-acrion-left-wrap">
                <div class="filter-ordering">
			        <?php casano_woocommerce_catalog_ordering(); ?>
                </div>
            </div>
            <div class="part-wrap part-acrion-center-wrap">
		        <?php if ( $enable_products_sizes ) { ?>
                    <div class="part-products-size-wrap">
                        <div class="products-sizes">
                            <a href="#" data-products_num="5"
                               class="products-size size-5 <?php if ( $product_size_active == 'size-5' ) {
						           echo 'active';
					           } ?>">
                                <svg viewBox="0 0 16 16" id="view-size-5" width="100%" height="100%">
                                    <path d="M4.769 3.385c0 .762-.623 1.385-1.385 1.385S2 4.146 2 3.385 2.623 2 3.385 2s1.384.623 1.384 1.385zM9.385 3.385c0 .762-.623 1.385-1.385 1.385s-1.385-.624-1.385-1.385S7.238 2 8 2s1.385.623 1.385 1.385zM4.769 8c0 .762-.623 1.385-1.385 1.385S2 8.762 2 8s.623-1.385 1.385-1.385S4.769 7.238 4.769 8zM9.385 8c0 .762-.623 1.385-1.385 1.385S6.615 8.762 6.615 8 7.238 6.615 8 6.615 9.385 7.238 9.385 8zM4.769 12.615c0 .762-.623 1.385-1.384 1.385S2 13.377 2 12.615s.623-1.385 1.385-1.385 1.384.624 1.384 1.385zM9.385 12.615C9.385 13.377 8.762 14 8 14s-1.385-.623-1.385-1.385.623-1.384 1.385-1.384 1.385.623 1.385 1.384zM14 3.385c0 .762-.623 1.385-1.385 1.385s-1.385-.623-1.385-1.385S11.854 2 12.615 2C13.377 2 14 2.623 14 3.385zM14 8c0 .762-.623 1.385-1.385 1.385S11.231 8.762 11.231 8s.623-1.385 1.385-1.385C13.377 6.615 14 7.238 14 8zM14 12.615c0 .762-.623 1.385-1.385 1.385s-1.385-.623-1.385-1.385.623-1.385 1.385-1.385A1.39 1.39 0 0 1 14 12.615z"></path>
                                </svg>
                            </a>
                            <a href="#" data-products_num="4"
                               class="products-size size-4 <?php if ( $product_size_active == 'size-4' ) {
						           echo 'active';
					           } ?>">
                                <svg viewBox="0 0 16 16" id="view-size-4" width="100%" height="100%">
                                    <path d="M7 4.5C7 5.875 5.875 7 4.5 7S2 5.875 2 4.5 3.125 2 4.5 2 7 3.125 7 4.5zM14 4.5C14 5.875 12.875 7 11.5 7S9 5.875 9 4.5 10.125 2 11.5 2 14 3.125 14 4.5zM7 11.5C7 12.875 5.875 14 4.5 14S2 12.875 2 11.5 3.125 9 4.5 9 7 10.125 7 11.5zM14 11.5c0 1.375-1.125 2.5-2.5 2.5S9 12.875 9 11.5 10.125 9 11.5 9s2.5 1.125 2.5 2.5z"></path>
                                </svg>
                            </a>
                            <a href="#" data-products_num="3"
                               class="products-size <?php if ( $product_size_active == 'size-3' ) {
						           echo 'active';
					           } ?>">
                                <svg viewBox="0 0 16 16" id="view-size-3" width="100%" height="100%">
                                    <path d="M14 8c0 3.3-2.7 6-6 6s-6-2.7-6-6 2.7-6 6-6 6 2.7 6 6z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
		        <?php } ?>
            </div>
            <div class="part-wrap part-acrion-right-wrap">
		        <?php if ( class_exists( 'PrdctfltrInit' ) || class_exists( 'XforWC_Product_Filters' )): ?>
                    <div class="part-filter-wrap">
                        <div class="actions-wrap">
					        <?php if ( $filter_shop_page != 'drawer_sidebar' && $filter_shop_page != 'offcanvas_sidebar' ): ?>
                                <a class="filter-toggle filter-toggle-button"
                                   href="#">
                                    <i></i>
							        <?php esc_html_e( 'Filter', 'casano' ); ?></a>
					        <?php endif; ?>
					        <?php if ( $filter_shop_page == 'drawer_sidebar' || $filter_shop_page == 'offcanvas_sidebar' ): ?>
                                <a class="filter-toggle-drawer-canvas filter-toggle-button"
                                   href="#">
                                    <i></i>
							        <?php esc_html_e( 'Filter', 'casano' ); ?></a>
					        <?php endif; ?>
                        </div>
                    </div>
		        <?php endif; ?>
            </div>

    </div>
<?php }; ?>