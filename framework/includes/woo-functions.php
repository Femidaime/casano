<?php
/* ==================== HOOK SHOP ==================== */
/* Remove Div cover content shop */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/* Custom shop control */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_before_shop_loop' );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );


add_action( 'casano_before_shop_loop', 'casano_shop_top_control', 10 );
add_action( 'woocommerce_before_main_content', 'casano_woocommerce_breadcrumb', 20 );

/* Custom product per page */
add_filter( 'loop_shop_per_page', 'casano_loop_shop_per_page', 20 );

/* Custom product categories cat thumbnails */
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
add_action( 'casano_woocommerce_subcategory_thumbnail', 'casano_woocommerce_subcategory_thumbnail', 10 );

/* Remove CSS */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_filter( 'woocommerce_enqueue_styles', '__return_false' );
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );

add_action( 'wp_enqueue_scripts', 'casano_wp_enqueue_scripts' );
function casano_wp_enqueue_scripts() {
	wp_dequeue_style( 'woocommerce_admin_styles' );
}

/**  Cusstom number related **/
add_filter( 'woocommerce_output_related_products_args', 'casano_related_products_args' );
function casano_related_products_args( $args ) {
	$limit                  = casano_get_option( 'casano_related_products_perpage', 8 );
	$args['posts_per_page'] = $limit; // 4 related products
	
	return $args;
}

/* Custom Product Thumbnail */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'casano_template_loop_product_thumbnail', 10, 1 );

/* ==================== HOOK SHOP ==================== */

remove_action( 'woocommerce_shortcode_before_product_cat_loop', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
/* ==================== CART PAGE ==================== */

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 30 );

/* ==================== CART PAGE ==================== */

/* ==================== SINGLE PRODUCT =============== */

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 15 );
add_action( 'casano_product_flash', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_single_product_summary', 'casano_open_product_mobile_more_detail_wrap', 25 );
// After single excerpt and before single add to cart
add_action( 'woocommerce_single_product_summary', 'fami_woocommerce_output_product_data_tabs_mobile', 115 );
add_action( 'woocommerce_single_product_summary', 'casano_close_product_mobile_more_detail_wrap', 120 );
add_action( 'woocommerce_after_single_product', 'casano_product_share', 1 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'fami_woocommerce_output_product_data_tabs', 10 );
add_action( 'casano_woocommerce_single_product_summary', 'fami_woocommerce_output_product_data_tabs', 10 );
add_action( 'fami_variable', 'woocommerce_template_single_title', 8 );
add_action( 'fami_variable', 'woocommerce_template_single_rating', 9 );
add_action( 'casano_function_shop_loop_item_countdown', 'casano_function_shop_loop_item_countdown', 10 );
add_action( 'woocommerce_single_product_summary', 'casano_single_product_brands', 4 );
add_action( 'woocommerce_single_product_summary', 'casano_select_variable_mobile', 19 );
add_action( 'woocommerce_single_product_summary', 'casano_function_offer_boxed_product', 20 );
add_action( 'woocommerce_single_product_summary', 'casano_function_shop_loop_item_countdown', 21 );
add_action( 'woocommerce_single_product_summary', 'casano_size_guide', 22 );
/* Remove star rating */
$product_star_rating = casano_get_option( 'product_star_rating', '' );
if($product_star_rating == 'nostar' ) {
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	remove_action( 'fami_variable', 'woocommerce_template_single_rating', 9 );
}
/* ==================== HOOK PRODUCT ================= */

/*Remove woocommerce_template_loop_product_link_open */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/*Custom product name*/
add_action( 'woocommerce_after_shop_loop_item_title', 'casano_template_loop_product_title', 6 );
add_action( 'casano_custom_save_flash', 'casano_custom_save_flash', 5 );
add_action( 'casano_function_shop_loop_process_variable', 'casano_function_shop_loop_process_variable', 5 );

/*casano button product*/
add_action( 'casano_product_video', 'casano_show_product_video', 11 );
add_action( 'casano_product_360deg', 'casano_show_product_360deg', 12 );


/* Casano Custom Checkout Page */

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

add_action( 'woocommerce_before_checkout_form', 'checkout_login_open', 1 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 5 );
add_action( 'woocommerce_before_checkout_form', 'checkout_login_close', 6 );
add_action( 'woocommerce_before_checkout_form', 'checkout_coupon_open', 7 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'checkout_coupon_close', 11 );

// Quickview
remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_rating', 10 );

add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_title', 10 );
add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_rating', 5 );

//add_action( 'yith_wcqv_product_summary', 'casano_wc_loop_product_wishlist_btn', 26 );
// Sticky on single
add_action( 'sticky_thumbnail_product_summary', 'casano_woocommerce_thumbnail_sticky', 5 );
add_action( 'sticky_info_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'sticky_info_product_summary', 'woocommerce_template_single_rating', 10 );
/* Casano _add_filter */
add_filter( 'woocommerce_show_page_title', 'casano_woocommerce_page_title' );

function casano_woocommerce_result_count() {
	if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
		return;
	}
	$args = array(
		'total'    => wc_get_loop_prop( 'total' ),
		'per_page' => wc_get_loop_prop( 'per_page' ),
		'current'  => wc_get_loop_prop( 'current_page' ),
	);
	echo esc_html__( 'We\'ve got ', 'casano' ) . '<span>' . $args['total'] . '</span>' . esc_html__( ' products for you', 'casano' );
}

function checkout_login_open() {
	if ( ! is_user_logged_in() ) {
		echo '<div class="casano-checkout-login">';
	}
}

function checkout_login_close() {
	if ( ! is_user_logged_in() ) {
		echo '</div>';
	}
}

function checkout_coupon_open() {
	echo '<div class="casano-checkout-coupon">';
}

function checkout_coupon_close() {
	echo '</div>';
}

// Add action Single Product hook : woocommerce_before_single_product_summary
if ( ! function_exists( 'casano_show_product_360deg' ) ) {
	function casano_show_product_360deg() {
		global $product;
		$meta_360 = get_post_meta( $product->get_id(), '_custom_product_woo_options', '' );

		if ( empty( $meta_360 ) || empty( $meta_360[0]['360gallery'] ) ) {
			return;
		}
		$images = $meta_360[0]['360gallery'];
		$images = explode( ',', $images );
		if ( empty( $images ) ) {
			return;
		}
		$id               = rand( 0, 999 );
		$title            = '';
		$frames_count     = count( $images );
		$images_js_string = '';
		?>
        <div id="product-360-view" class="product-360-view-wrapper mfp-hide">
            <div class="casano-threed-view threed-id-<?php echo esc_attr( $id ); ?>">
				<?php if ( ! empty( $title ) ): ?>
                    <h3 class="threed-title"><span><?php echo esc_html( $title ); ?></span></h3>
				<?php endif ?>
                <ul class="threed-view-images">
					<?php if ( count( $images ) > 0 ): ?>
						<?php $i = 0;
						foreach ( $images as $img_id ): $i ++; ?>
							<?php
							$img              = wp_get_attachment_image_src( $img_id, 'full' );
							$images_js_string .= "'" . $img[0] . "'";
							$width            = $img[1];
							$height           = $img[2];
							if ( $i < $frames_count ) {
								$images_js_string .= ",";
							}
							?>
						<?php endforeach ?>
					<?php endif ?>
                </ul>
                <div class="spinner">
                    <span>0%</span>
                </div>
            </div>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $('.threed-id-<?php echo esc_attr( $id ); ?>').ThreeSixty({
                        totalFrames: <?php echo esc_attr( $frames_count ); ?>,
                        endFrame: <?php echo esc_attr( $frames_count ); ?>,
                        currentFrame: 1,
                        imgList: '.threed-view-images',
                        progress: '.spinner',
                        imgArray: [<?php printf( '%s', $images_js_string ); ?>],
                        height: <?php echo esc_attr( $height ); ?>,
                        width: <?php echo esc_attr( $width ); ?>,
                        responsive: true,
                        navigation: true
                    });
                });
            </script>
        </div>
        <div class="product-360-button">
            <a href="#product-360-view"><span class="screen-reader-text"><?php echo esc_html__( '360 Degree', 'casano' ); ?></span></a>
        </div>
		<?php
	}
}
if ( ! function_exists( 'casano_show_product_video' ) ) {
	function casano_show_product_video() {
		global $product;
		$video_url = get_post_meta( $product->get_id(), '_custom_product_woo_options', '' );
		if ( ! empty( $video_url[0]['youtube_url'] ) ) {
			echo '<div class="casano-bt-video"><a href="' . esc_url( $video_url[0]['youtube_url'] ) . '">' . esc_html__( 'Play Video', 'casano' ) . '</a></div>';
		}
	}
}

/* Add countdown in product */
add_action( 'casano_display_product_countdown_in_loop', 'casano_display_product_countdown_in_loop', 1 );

/* Short Product description */
add_action( 'casano_product_short_description', 'casano_product_short_description', 15 );

/* Custom flash icon */
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'casano_group_flash', 5 );


/* Add categories to product */
add_action( 'casano_add_categories_product', 'casano_add_categories_product', 1 );

/* ==================== HOOK PRODUCT ==================== */

/* WC_Vendors */
if ( class_exists( 'WC_Vendors' ) && class_exists( 'WCV_Vendor_Shop' ) ) {
	// Add sold by to product loop before add to cart
	if ( WC_Vendors::$pv_options->get_option( 'sold_by' ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 9 );
		add_action( 'woocommerce_shop_loop_item_title', array( 'WCV_Vendor_Shop', 'template_loop_sold_by' ), 1 );
	}
}


/* CUSTOM PRODUCT TITLE */
if ( ! function_exists( 'casano_template_loop_product_title' ) ) {
	function casano_template_loop_product_title() {
		$title_class = array( 'product-title product-name' );
		?>
        <h3 class="<?php echo esc_attr( implode( ' ', $title_class ) ); ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
		<?php
	}
}

/* CUSTOM PAGINATION */
//remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
//add_action( 'woocommerce_after_shop_loop', 'casano_custom_pagination', 10 );

// Will remove
if ( ! function_exists( 'casano_custom_pagination' ) ) {
	function casano_custom_pagination() {
		global $wp_query;
		$enable_loadmore = casano_get_option( 'casano_enable_loadmore', 'default' );
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		?>
		<?php if ( $enable_loadmore != 'default' ): ?>
			<?php

			if ( class_exists( 'PrdctfltrInit' ) || class_exists( 'XforWC_Product_Filters' )) {
				echo '<nav class="woocommerce-pagination prdctfltr-pagination prdctfltr-pagination-load-more">
                        <a href="#" class="button">Load More</a>
                    </nav>';
			} else {
				global $wp_query;
				echo '<div class="casano-ajax-load" data-mode="grid" data-2nd_page_url="' . esc_url( get_next_posts_page_link( $wp_query->max_num_pages ) ) . '" data-cur_page="1" data-total_page="' . esc_attr( $wp_query->max_num_pages ) . '" data-load-more=\'{"page":"' . esc_attr( $wp_query->max_num_pages ) . '","container":"product-grid","layout":"' . esc_attr( $enable_loadmore ) . '"}\'>';
				next_posts_link( esc_html__( 'Load More', 'casano' ), $wp_query->max_num_pages );
				echo '</div>';
			}

			?>
		<?php else: ?>
            <nav class="woocommerce-pagination pagination">
				<?php
				echo paginate_links(
					apply_filters( 'woocommerce_pagination_args',
					               array(
						               'base'      => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
						               'format'    => '',
						               'add_args'  => false,
						               'current'   => max( 1, get_query_var( 'paged' ) ),
						               'total'     => $wp_query->max_num_pages,
						               'prev_text' => esc_html__( 'Previous', 'casano' ),
						               'next_text' => esc_html__( 'Next', 'casano' ),
						               'type'      => 'plain',
						               'end_size'  => 3,
						               'mid_size'  => 3,
					               )
					)
				);
				?>
            </nav>
		<?php endif; ?>
		<?php
	}
}

/* CUSTOM RATTING */
add_filter( "woocommerce_product_get_rating_html", "casano_get_rating_html", 10, 3 );
if ( ! function_exists( 'casano_get_rating_html ' ) ) {
	function casano_get_rating_html( $rating_html, $rating, $count = 0 ) {
		if ( ! $count ) {
			global $product;
			if ( $product ) {
				$count = $product->get_review_count();
			}
		}

		$rating_html = '<div class="rating-wapper"><div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'casano' ), $rating ) . '">';
		$rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong></span>';
		if ( $count == 1 ) {
			$rating_html .= '</div><span class="review">(' . intval( $count ) . '<span class="review-text">' . esc_html__( ' review', 'casano' ) . '</span>)</span></div>';
		} else {
			$rating_html .= '</div><span class="review">(' . intval( $count ) . '<span class="review-text">' . esc_html__( ' reviews', 'casano' ) . '</span>)</span></div>';
		}

		return $rating_html;
	}
}

/* SINGLE PRODUCT MOBILE MORE DETAIL OPEN */
function casano_open_product_mobile_more_detail_wrap() {
	$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && casano_is_mobile() ) {
		echo '<div class="product-mobile-more-detail-wrap">';
	}
}

/* SINGLE PRODUCT MOBILE MORE DETAIL CLOSE */
function casano_close_product_mobile_more_detail_wrap() {
	$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && casano_is_mobile() ) {
		echo '</div> <!-- .product-mobile-more-detail-wrap -->';
	}
}

function casano_select_variable_mobile() {
	global $product;
	$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && casano_is_mobile() ) {
		if ( $product->is_type( 'variable' ) ) { ?>
            <a href="#" class="toggle-variations-select-mobile"><?php echo esc_html__( ' Select variation', 'casano' ); ?></a>
		<?php }
	}
}

/* SING PRODUCT TABS */
function fami_woocommerce_output_product_data_tabs() {
	$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
	if ( ! $enable_single_product_mobile || ! casano_is_mobile() ) {
		woocommerce_output_product_data_tabs();
	}
}

function fami_woocommerce_output_product_data_tabs_mobile() {
	$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
	if ( $enable_single_product_mobile && casano_is_mobile() ) {
		woocommerce_output_product_data_tabs();
	}
}

/* CUSTOM PRODUCT CATEGORIES THUMBNAILS */
if ( ! function_exists( 'casano_woocommerce_thumbnail_sticky' ) ) {
	function casano_woocommerce_thumbnail_sticky() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}
		$dimensions   = array(
			'width'  => 50,
			'height' => 50
		);
		$thumbnail_id = get_post_thumbnail_id();

		$image = casano_resize_image( $thumbnail_id, null, $dimensions['width'], $dimensions['height'], true, true, false );
		echo casano_img_output( $image, '', esc_attr( get_the_title() ) );
	}
}
/* CUSTOM PRODUCT THUMBNAIL */
if ( ! function_exists( 'casano_template_loop_product_thumbnail' ) ) {

	function casano_template_loop_product_thumbnail( $args = array() ) {
		global $product;

		// GET SIZE IMAGE SETTING
		$crop      = true;
		$size      = array(
			"width"  => 689,
            "height" => 726,
            "crop"   => 1,
        );
		$wc_width  = 689;
		$wc_height = 726;
		if ( $size ) {
			$wc_width  = $size['width'];
			$wc_height = $size['height'];
			if ( ! $size['crop'] ) {
				$crop = false;
			}
		}

		$w = isset( $args['width'] ) ? intval( $args['width'] ) : $wc_width;
		$h = isset( $args['height'] ) ? intval( $args['height'] ) : $wc_height;


		$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', true );
		$atts_swatches_mobile         = false;
		if ( $enable_single_product_mobile && casano_is_mobile() ) {
			$atts_swatches_mobile = false;
		}

		ob_start();
		?>
        <a class="thumb-link" href="<?php the_permalink(); ?>">
			<?php
			$image_thumb        = casano_resize_image( get_post_thumbnail_id( $product->get_id() ), null, $w, $h, $crop, true, false );
			$class_img_thumb    = 'attachment-post-thumbnail';
			$secondary_img_html = $class = '';
			$attachment_ids     = $product->get_gallery_image_ids();
			if ( isset( $attachment_ids[0] ) ) {
				$secondary_class = 'product-secondary-img';
				if ( ! $atts_swatches_mobile ) {
					$secondary_class .= ' wp-post-image';
				} else {
					$class_img_thumb .= ' wp-post-image';
				}
				$secondary_img      = casano_resize_image( $attachment_ids[0], null, $w, $h, $crop, true, false );
				$secondary_img_html .= '<figure class="product-second-figure product-second-fadeinDown">';
				$secondary_img_html .= '<div class="woocommerce-product-gallery__image">';
				$secondary_img_html .= casano_img_output( $secondary_img, $secondary_class, esc_attr( get_post_meta( $attachment_ids[0], '_wp_attachment_image_alt', true ) ) );
				$secondary_img_html .= '</div>';
				$secondary_img_html .= '</figure>';
			} else {
				$class_img_thumb .= ' wp-post-image';
			}

			echo '<div class="images">';
			echo '<div class="woocommerce-product-gallery__image--placeholder">';
			echo casano_img_output( $image_thumb, $class_img_thumb, get_the_title(), get_the_title() );
			echo casano_html_output( $secondary_img_html );
			echo '</div>';
			echo '</div>';
			?>
        </a>
		<?php
		echo ob_get_clean();
	}
}

/* CUSTOM PRODUCT CATEGORIES THUMBNAILS */
if ( ! function_exists( 'casano_woocommerce_subcategory_thumbnail' ) ) {
	function casano_woocommerce_subcategory_thumbnail( $category ) {
		$small_thumbnail_size = apply_filters( 'subcategory_archive_thumbnail_size', 'woocommerce_thumbnail' );
		$dimensions           = wc_get_image_size( $small_thumbnail_size );
		$thumbnail_id         = get_term_meta( $category->term_id, 'thumbnail_id', true );

		$image = casano_resize_image( $thumbnail_id, null, $dimensions['width'], $dimensions['height'], true, true, false );
		echo casano_img_output( $image, '', esc_attr( $category->name ) );
	}
}


/* ADD CATEGORIES LIST IN PRODUCT */
if ( ! function_exists( 'casano_add_categories_product' ) ) {

	function casano_add_categories_product() {
		$html = '';
		$html .= '<span class="cat-list">';
		$html .= wc_get_product_category_list( get_the_ID() );
		$html .= '</span>';
		printf( '%s', $html );
	}
}

/* CUSTOM BREADCRUMB */
if ( ! function_exists( 'casano_woocommerce_breadcrumb' ) ) {
	function casano_woocommerce_breadcrumb() {
		$args = array(
			'delimiter'   => '',
			'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs"><ul class="breadcrumb">',
			'wrap_after'  => '</ul></nav>',
			'before'      => '<li>',
			'after'       => '</li>',
		);
		woocommerce_breadcrumb( $args );
	}
}
/* HOOK CONTROL */
if ( ! function_exists( 'casano_shop_top_control' ) ) {
	function casano_shop_top_control() {
		$enable_shop_mobile = casano_get_option( 'enable_shop_mobile', true );
		if ( $enable_shop_mobile && casano_is_mobile() ) {
			get_template_part( 'template-parts/shop-top', 'control-mobile' );
		} else {
			get_template_part( 'template-parts/shop-top', 'control' );
		}
	}
}

/* VIEW MORE */
if ( ! function_exists( 'casano_shop_view_more' ) ) {
	function casano_shop_view_more() {
		$shop_display_mode = 'grid';
		if ( isset( $_SESSION['shop_display_mode'] ) ) {
			$shop_display_mode = $_SESSION['shop_display_mode'];
		}
		?>
        <div class="grid-view-mode">
            <a data-mode="grid"
               class="modes-mode mode-grid display-mode <?php if ( $shop_display_mode == "grid" ): ?>active<?php endif; ?>"
               href="javascript:void(0)">
                <i class="flaticon-17grid"></i>
				<?php echo esc_html__( 'Grid', 'casano' ) ?>
            </a>
            <a data-mode="list"
               class="modes-mode mode-list display-mode <?php if ( $shop_display_mode == "list" ): ?>active<?php endif; ?>"
               href="javascript:void(0)">
                <i class="flaticon-18list"></i>
				<?php echo esc_html__( 'List', 'casano' ) ?>
            </a>
        </div>
		<?php
	}
}

/*----------------------
Product view style
----------------------*/
if ( ! function_exists( 'wp_ajax_frontend_set_products_view_style_callback' ) ) {
	function wp_ajax_frontend_set_products_view_style_callback() {
		check_ajax_referer( 'casano_ajax_frontend', 'security' );
		$mode                          = $_POST['mode'];
		$_SESSION['shop_display_mode'] = $mode;

		die();
	}
}
add_action( 'wp_ajax_frontend_set_products_view_style', 'wp_ajax_frontend_set_products_view_style_callback' );
add_action( 'wp_ajax_nopriv_frontend_set_products_view_style', 'wp_ajax_frontend_set_products_view_style_callback' );

if ( ! function_exists( 'casano_loop_shop_per_page' ) ) {
	function casano_loop_shop_per_page() {
		$casano_woo_products_perpage = casano_get_option( 'product_per_page', '12' );

		return $casano_woo_products_perpage;
	}
}

/*----------------------
Product per page
----------------------*/
if ( ! function_exists( 'wp_ajax_fronted_set_products_perpage_callback' ) ) {
	function wp_ajax_fronted_set_products_perpage_callback() {
		check_ajax_referer( 'casano_ajax_frontend', 'security' );
		$mode                                  = $_POST['mode'];
		$_SESSION['casano_woo_products_perpage'] = $mode;
		die();
	}
}
add_action( 'wp_ajax_fronted_set_products_perpage', 'wp_ajax_fronted_set_products_perpage_callback' );
add_action( 'wp_ajax_nopriv_fronted_set_products_perpage', 'wp_ajax_fronted_set_products_perpage_callback' );

/* QUICK VIEW */
if ( class_exists( 'YITH_WCQV_Frontend' ) ) {
	// Class frontend
	$enable           = get_option( 'yith-wcqv-enable' ) == 'yes' ? true : false;
	$enable_on_mobile = get_option( 'yith-wcqv-enable-mobile' ) == 'yes' ? true : false;
	// Class frontend
	if ( ( ! casano_is_mobile() && $enable ) || ( casano_is_mobile() && $enable_on_mobile && $enable ) ) {
		remove_action( 'woocommerce_after_shop_loop_item', array(
			YITH_WCQV_Frontend::get_instance(),
			'yith_add_quick_view_button'
		), 15 );
		add_action( 'casano_function_shop_loop_item_quickview', array(
			YITH_WCQV_Frontend::get_instance(),
			'yith_add_quick_view_button'
		), 5 );
	}
}

/* WISH LIST */
if ( ! function_exists( 'casano_wc_loop_product_wishlist_btn' ) ) {
	function casano_wc_loop_product_wishlist_btn() {
		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) && get_option( 'yith_wcwl_enabled' ) == 'yes' ) {
			if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . get_the_ID() . '"]' );
			}
		}
	}
}
add_action( 'casano_function_shop_loop_item_wishlist', 'casano_wc_loop_product_wishlist_btn', 1 );

/* COMPARE */
if ( class_exists( 'YITH_Woocompare' ) && get_option( 'yith_woocompare_compare_button_in_products_list' ) == 'yes' ) {
	global $yith_woocompare;
	$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
	if ( $yith_woocompare->is_frontend() || $is_ajax ) {
		if ( $is_ajax ) {
			if ( ! class_exists( 'YITH_Woocompare_Frontend' ) ) {
				if ( file_exists( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' ) ) {
					require_once( YITH_WOOCOMPARE_DIR . 'includes/class.yith-woocompare-frontend.php' );
				}
			}
			$yith_woocompare->obj = new YITH_Woocompare_Frontend();
		}
		/* Remove button */
		remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
	}
}

/* Add compare button */
if ( ! function_exists( 'casano_wc_loop_product_compare_btn' ) ) {
	function casano_wc_loop_product_compare_btn() {
		if ( shortcode_exists( 'yith_compare_button' ) ) {
			echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
		} // End if ( shortcode_exists( 'yith_compare_button' ) )
		else {
			if ( class_exists( 'YITH_Woocompare_Frontend' ) ) {
				$YITH_Woocompare_Frontend = new YITH_Woocompare_Frontend();
				echo do_shortcode( '[yith_compare_button product_id="' . get_the_ID() . '"]' );
			}
		}
	}
}
add_action( 'casano_function_shop_loop_item_compare', 'casano_wc_loop_product_compare_btn', 1 );

if ( ! function_exists( 'casano_wisth_list_url' ) ) {
	function casano_wisth_list_url() {
		$url = '';
		if ( function_exists( 'yith_wcwl_object_id' ) ) {
			$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
			$url              = get_the_permalink( $wishlist_page_id );
		}

		return $url;
	}
}

/* GROUP NEW FLASH */

if ( ! function_exists( 'casano_group_flash' ) ) {
	function casano_group_flash() {
		global $product;
		?>
        <div class="flash">
			<?php
			woocommerce_show_product_loop_sale_flash();
			casano_show_product_loop_new_flash();
			?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'casano_show_product_loop_new_flash' ) ) {
	/**
	 * Get the sale flash for the loop.
	 *
	 * @subpackage    Loop
	 */
	function casano_show_product_loop_new_flash() {
		wc_get_template( 'loop/new-flash.php' );
	}
}

add_filter( 'woocommerce_sale_flash', 'casano_custom_sale_flash' );

if ( ! function_exists( 'casano_custom_sale_flash' ) ) {
	function casano_custom_sale_flash() {
		$percent = casano_get_percent_discount();
		if ( $percent != '' ) {
			return '<span class="onsale">' . $percent . '</span>';
		} else {
			return '';
		}

	}
}
if ( ! function_exists( 'casano_get_percent_discount' ) ) {
	function casano_get_percent_discount() {
		global $product;
		$percent = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;

				for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
					$variation_id = $available_variations[ $i ]['variation_id'];

					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ), 0 );
					}

					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}

					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$percent .= '-' . $minimumper . '%';
				} else {
					$percent .= '-(' . $minimumper . '-' . $maximumper . ')%';
				}

			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = round( ( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ), 0 );
					$percent    .= '-' . $percentage . '%';
				}
			}
		}

		return $percent;
	}
}
if ( ! function_exists( 'casano_custom_save_flash' ) ) {
	function casano_custom_save_flash() {
		$save = casano_get_save_discount();
		if ( $save != '' ) {
			echo '<div class="onsave"><span><span>' . esc_html__( 'Save ', 'casano' ) . '</span>' . $save . get_woocommerce_currency_symbol() . '</span></div>';
		} else {
			echo '';
		}
	}
}
if ( ! function_exists( 'casano_get_save_discount' ) ) {
	function casano_get_save_discount() {
		global $product;
		$save = '';
		if ( $product->is_on_sale() ) {
			if ( $product->is_type( 'variable' ) ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				$minimumper           = 0;
				$percentage           = 0;

				for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
					$variation_id = $available_variations[ $i ]['variation_id'];

					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					if ( $regular_price > 0 && $sales_price > 0 ) {
						$percentage = $regular_price - $sales_price;
					}

					if ( $minimumper == 0 ) {
						$minimumper = $percentage;
					}
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}

					if ( $percentage < $minimumper ) {
						$minimumper = $percentage;
					}
				}
				if ( $minimumper == $maximumper ) {
					$save .= '' . $minimumper . '';
				} else {
					$save .= '' . $minimumper . '-' . $maximumper . '';
				}

			} else {
				if ( $product->get_regular_price() > 0 && $product->get_sale_price() > 0 ) {
					$percentage = $product->get_regular_price() - $product->get_sale_price();
					$save       .= '' . $percentage . '';
				}
			}
		}

		return $save;
	}
}
if ( ! function_exists( 'casano_function_shop_loop_process_variable' ) ) {
	function casano_function_shop_loop_process_variable() {
		global $product;
		$units_sold   = get_post_meta( $product->get_id(), 'total_sales', true );
		$availability = $product->get_stock_quantity();
		if ( $availability == '' ) {
			$percent = 0;
		} else {
			$total_percent = $availability + $units_sold;
			$percent       = round( ( ( $units_sold / $total_percent ) * 100 ), 0 );
		}
		?>
        <div class="process-valiable">
            <div class="valiable-text">
                <span class="text">
                    <?php
                    echo esc_html__( 'Already Sold: ', 'casano' );
                    ?>
                    <span>
                        <?php echo esc_attr( $units_sold ); ?>
                    </span>
                </span>
                <span class="text">
                    <?php echo esc_html__( 'Available: ', 'casano' ) ?>
                    <span>
                        <?php
                        if ( $availability != '' ) {
	                        echo esc_html( $availability );
                        } else {
	                        echo esc_html__( 'Unlimit', 'casano' );
                        }
                        ?>
                    </span>
                </span>
            </div>
            <span class="valiable-total total">
                <span class="process"
                      style="width: <?php echo esc_attr( $percent ) . '%' ?>"></span>
            </span>
        </div>
		<?php
	}
}
/* GROUP NEW FLASH */
/* CUSTOM DESCRIPTION */
if ( ! function_exists( 'casano_product_short_description' ) ) {
	function casano_product_short_description() {
		global $post;
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( ! $post->post_excerpt ) {
				return;
			}
			?>
            <div class="product-des">
				<?php the_excerpt(); ?>
            </div>
			<?php
		}
	}
}
//SINGLE PRODUCT BRAND
if ( ! function_exists( 'casano_single_product_brands' ) ) {
	function casano_single_product_brands() {
		global $product;

		$terms = get_the_terms( $product->get_id(), 'product_brand' );

		if ( is_wp_error( $terms ) ) {
			return;
		}

		if ( ! $terms ) {
			return;
		}

		?>
        <div class="brand-product">
            <ul class="list-brands product-taxonomies-list">
				<?php
				foreach ( $terms as $term ) {
					if ( ! $term ) {
						continue;
					}
					$tax_img_id = get_term_meta( $term->term_id, 'tax_image', true );
					$term_img   = casano_resize_image( $tax_img_id, null, 150, 50, false, true, false );
					?>
                    <li class="item-brand">
                        <a class="brand-link"
                           href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo casano_img_output( $term_img ); ?></a>
                    </li>
					<?php
				}
				?>
            </ul>
        </div>
		<?php
	}
}
/* COUNTDOWN IN LOOP */
if ( ! function_exists( 'casano_display_product_countdown_in_loop' ) ) {
	function casano_display_product_countdown_in_loop() {
		global $product;
		$date  = casano_get_max_date_sale( $product->get_id() );
		?>
		<?php if ( $date > 0 ):
			$y = date( 'Y', $date );
			$m = date( 'm', $date );
			$d = date( 'd', $date );
			$h = date( 'h', $date );
			$i = date( 'i', $date );
			$s = date( 's', $date );
			?>
            <div class="product-count-down">
                <div class="casano-countdown" data-y="<?php echo esc_attr( $y ); ?>"
                     data-m="<?php echo esc_attr( $m ); ?>"
                     data-d="<?php echo esc_attr( $d ); ?>" data-h="<?php echo esc_attr( $h ); ?>"
                     data-i="<?php echo esc_attr( $i ); ?>" data-s="<?php echo esc_attr( $s ); ?>"></div>
            </div>
		<?php endif; ?>
		<?php
	}
}

// GET DATE SALE
if ( ! function_exists( 'casano_get_max_date_sale' ) ) {
	function casano_get_max_date_sale( $product_id ) {
		$time = 0;
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => - 1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		
		if ( ! empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "
        SELECT
        meta_value
        FROM $wpdb->postmeta
        WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ")
        ORDER BY meta_value DESC
        LIMIT 1
    "
			);
			
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		
		if ( ! $sale_price_dates_to ) {
			$sale_price_dates_to = get_post_meta( $product_id, '_sale_price_dates_to', true );
			
			if ( $sale_price_dates_to == '' ) {
				$sale_price_dates_to = '0';
			}
			
			return $sale_price_dates_to;
		}
	}
}

/* AJAX MINI CART */

add_filter( 'woocommerce_add_to_cart_fragments', 'casano_header_add_to_cart_fragment' );

if ( ! function_exists( ( 'casano_header_add_to_cart_fragment' ) ) ) {
	function casano_header_add_to_cart_fragment( $fragments ) {
		ob_start();
		
		get_template_part( 'template-parts/header', 'minicart' );
		
		$fragments['div.casano-minicart'] = ob_get_clean();
		
		return $fragments;
	}
}

/* AJAX UPDATE WISH LIST */
if ( ! function_exists( ( 'casano_update_wishlist_count' ) ) ) {
	function casano_update_wishlist_count() {
		if ( function_exists( 'YITH_WCWL' ) ) {
			wp_send_json( YITH_WCWL()->count_products() );
		}
	}
	
	// Wishlist ajaxify update
	add_action( 'wp_ajax_casano_update_wishlist_count', 'casano_update_wishlist_count' );
	add_action( 'wp_ajax_nopriv_casano_update_wishlist_count', 'casano_update_wishlist_count' );
}

// Share Single
function casano_product_share() {
	if ( function_exists( 'casano_toolkit_product_share' ) ) {
		casano_toolkit_product_share();
	}
}

// Login
if ( ! function_exists( 'casano_login_modal' ) ) {
	/**
	 * Add login modal to footer
	 */
	function casano_login_modal() {
		if ( ! shortcode_exists( 'woocommerce_my_account' ) ) {
			return;
		}
		
		if ( is_user_logged_in() ) {
			return;
		}
		
		// Don't load login popup on real mobile when header mobile is enabled
		$enable_header_mobile = casano_get_option( 'enable_header_mobile', false );
		if ( $enable_header_mobile && casano_is_mobile() ) {
			return;
		}
		
		?>
        <div id="login-popup" class="woocommerce-account md-content mfp-with-anim mfp-hide">
            <div class="casano-modal-content">
				<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
            </div>
        </div>
		<?php
	}
	
	add_action( 'wp_footer', 'casano_login_modal' );
};

// Top Cart
function add_order_tracking_setting( $settings ) {
	$new_settings = array();
	foreach ( $settings as $index => $setting ) {
		$new_settings[ $index ] = $setting;
		
		if ( isset( $setting['id'] ) && 'woocommerce_terms_page_id' == $setting['id'] ) {
			$new_settings['order_tracking_page_id'] = array(
				'title'    => esc_html__( 'Order Tracking Page', 'casano' ),
				'desc'     => esc_html__( 'Page content: [woocommerce_order_tracking]', 'casano' ),
				'id'       => 'casano_order_tracking_page_id',
				'type'     => 'single_select_page',
				'class'    => 'wc-enhanced-select-casanod',
				'css'      => 'min-width:300px;',
				'desc_tip' => true,
			);
		}
	}
	
	return $new_settings;
}

add_filter( 'woocommerce_get_settings_checkout', 'add_order_tracking_setting', 10 );
if ( ! function_exists( 'casano_is_order_tracking_page' ) ) :
	/**
	 * Check if current page is order tracking page
	 *
	 * @return bool
	 */
	function casano_is_order_tracking_page() {
		$page_id = get_option( 'casano_order_tracking_page_id' );
		$page_id = casano_get_translated_object_id( $page_id );
		
		if ( ! $page_id ) {
			return false;
		}
		
		return is_page( $page_id );
	}
endif;

if ( ! function_exists( 'casano_is_wishlist_page' ) ) {
	function casano_is_wishlist_page() {
		$page_id = get_option( 'yith_wcwl_wishlist_page_id' );
		$page_id = casano_get_translated_object_id( $page_id );
		
		if ( ! $page_id ) {
			return false;
		}
		
		return is_page( $page_id );
	}
}

if ( ! function_exists( 'casano_get_translated_object_id' ) ) :
	/**
	 * Get translated object ID if the WPML plugin is installed
	 * Return the original ID if this plugin is not installed
	 *
	 * @param int    $id            The object ID
	 * @param string $type          The object type 'post', 'page', 'post_tag', 'category' or 'attachment'. Default is 'page'
	 * @param bool   $original      Set as 'true' if you want WPML to return the ID of the original language element if the translation is missing.
	 * @param bool   $language_code If set, forces the language of the returned object and can be different than the displayed language.
	 *
	 * @return mixed
	 */
	function casano_get_translated_object_id( $id, $type = 'page', $original = true, $language_code = false ) {
		if ( function_exists( 'wpml_object_id_filter' ) ) {
			return wpml_object_id_filter( $id, $type, $original, $language_code );
		} elseif ( function_exists( 'icl_object_id' ) ) {
			return icl_object_id( $id, $type, $original, $language_code );
		}
		
		return $id;
	}
endif;

/**
 * Display a special page header for WooCommerce pages
 */
function casano_woocommerce_pages_header() {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}
	
	$allow = is_cart() || is_account_page() || casano_is_order_tracking_page();
	
	if ( function_exists( 'yith_wcwl_is_wishlist_page' ) ) {
		$allow = $allow || yith_wcwl_is_wishlist_page();
	}
	
	if ( ! $allow ) {
		return;
	}
	
	$page_id = casano_get_single_page_id();
	
	$pages = array();
	
	// Prepare for cart links
	$pages['cart'] = sprintf(
		'<li class="shopping-cart-link line-hover %s"><a href="%s">%s<span class="count cart-counter">(%d)</span></a></li>',
		is_cart() ? 'active' : '',
		esc_url( wc_get_cart_url() ),
		esc_html__( 'Shopping Cart', 'casano' ),
		WC()->cart->get_cart_contents_count()
	);
	
	// Prepare for wishlist link
	if ( function_exists( 'yith_wcwl_count_products' ) ) {
		$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
		
		$pages['wishlist'] = sprintf(
			'<li class="wishlist-link line-hover %s"><a href="%s">%s<span class="count wishlist-counter">(%d)</span></a></li>',
			yith_wcwl_is_wishlist_page() ? 'active' : '',
			esc_url( get_permalink( $wishlist_page_id ) ),
			esc_html__( 'Wishlist', 'casano' ),
			yith_wcwl_count_products()
		);
	}
	
	// Prepare for order tracking link
	if ( $tracking_page_id = get_option( 'casano_order_tracking_page_id' ) ) {
		$pages['order_tracking'] = sprintf(
			'<li class="order-tracking-link line-hover %s"><a href="%s">%s</a></li>',
			casano_is_order_tracking_page() ? 'active' : '',
			esc_url( get_permalink( casano_get_translated_object_id( $tracking_page_id ) ) ),
			esc_html__( 'Order Tracking', 'casano' )
		);
	}
	
	// Prepare for account link
	if ( is_user_logged_in() ) {
		$pages['account'] = sprintf(
			'<li class="account-link line-hover %s"><a href="%s">%s</a></li>',
			is_account_page() ? 'active' : '',
			esc_url( wc_get_page_permalink( 'myaccount' ) ),
			esc_html__( 'My Account', 'casano' )
		);
	}
	
	// Prepare for login/logout link
	if ( is_user_logged_in() ) {
		$pages['logout'] = sprintf(
			'<li class="logout-link line-hover"><a href="%s">%s</a></li>',
			esc_url( wc_logout_url( wc_get_account_endpoint_url( 'customer-logout' ) ) ),
			esc_html__( 'Logout', 'casano' )
		);
		
	} else {
		$pages['login'] = sprintf(
			'<li class="login-link line-hover %s"><a href="%s">%s</a></li>',
			is_account_page() ? 'active' : '',
			esc_url( wc_get_page_permalink( 'myaccount' ) ),
			esc_html__( 'Login', 'casano' )
		);
	}
	
	$pages = apply_filters( 'casano_woocomemrce_page_header_links', $pages );
	if ( casano_is_mobile() ) {
		if ( ! empty( $pages ) ) {
			$page_title = $page_id > 0 ? get_the_title( $page_id ) : '';
			?>
            <div class="woocommerce-page-headermid">
                <div class="container">
					<?php printf( '<h2 class="title-page">%s</h2>', $page_title ); ?>
					<?php get_template_part( 'template-parts/part', 'breadcrumb' ); ?>
					<?php printf( '<div class="woocommerce-page-header"><div class="container"><ul>%s</ul></div></div>', implode( "\n", $pages ) ); ?>
                </div>
            </div>
			<?php
		}
	}
}

add_action( 'casano_after_header', 'casano_woocommerce_pages_header', 20 );


// Topbar single
add_action( 'casano_product_toolbar', 'casano_product_toolbar', 5 );
function casano_product_toolbar() {
	$casano_first_post = get_previous_post();
	$casano_last_post  = get_next_post();
	$thumbnail_prev  = array(
		'url'    => '',
		'width'  => 100,
		'height' => 100,
	);
	$thumbnail_next  = array(
		'url'    => '',
		'width'  => 100,
		'height' => 100,
	);
	
	if ( ! empty( $casano_first_post ) ) {
		$thumbnail_prev = casano_resize_image( get_post_thumbnail_id( $casano_first_post->ID ), null, 100, 100, true, true, false );
	}
	if ( ! empty( $casano_last_post ) ) {
		$thumbnail_next = casano_resize_image( get_post_thumbnail_id( $casano_last_post->ID ), null, 100, 100, true, true, false );
	}
	?>
    <div class="product-toolbar">
        <div class="container product-toolbar-wrap">
			<?php
			get_template_part( 'template-parts/part', 'breadcrumb' );
			the_post_navigation(
				array(
					'screen_reader_text' => esc_html__( 'Product navigation', 'casano' ),
					'prev_text'          => '<span class="fa fa-angle-left"></span><span class="single-text">'. esc_html__('Prev','casano').'</span><figure class="img-thumb-nav">' . casano_img_output( $thumbnail_prev ) . '</figure>',
					'next_text'          => '<span class="single-text">'. esc_html__('Next','casano').'</span><figure class="img-thumb-nav"><img src="' . esc_url( $thumbnail_next['url'] ) . '" alt="' . esc_attr__( 'Next', 'casano' ) . '" width="' . esc_attr( $thumbnail_next['width'] ) . '" height="' . esc_attr( $thumbnail_next['height'] ) . '"></figure><span class="fa fa-angle-right"></span>',
				) );
			?>
        </div>
    </div>
	<?php
}

// Login Social

if ( ! function_exists( 'casano_social_login' ) ) {
	function casano_social_login() {
		if ( ! class_exists( 'APSL_Lite_Class' ) ) {
			return;
		}
		echo '<span class="divider">' . esc_attr__( 'OR', 'casano' ) . '</span>';
		echo do_shortcode( '[apsl-login-lite login_text=""]' );
	}
	
	add_action( 'woocommerce_login_form_end', 'casano_social_login', 10 );
}
// REMOVE CART ITEM

if ( ! function_exists( 'casano_remove_cart_item_via_ajax' ) ) {
	function casano_remove_cart_item_via_ajax() {
		
		$response = array(
			'message'        => '',
			'fragments'      => '',
			'cart_hash'      => '',
			'mini_cart_html' => '',
			'err'            => 'no'
		);
		
		$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
		$nonce         = isset( $_POST['nonce'] ) ? trim( $_POST['nonce'] ) : '';
		
		if ( $cart_item_key == '' || $nonce == '' ) {
			$response['err'] = 'yes';
			wp_send_json( $response );
		}
		
		if ( ( wp_verify_nonce( $nonce, 'woocommerce-cart' ) ) ) {
			
			if ( $cart_item = WC()->cart->get_cart_item( $cart_item_key ) ) {
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		} else {
			$response['message'] = esc_html__( 'Security check error!', 'casano' );
			$response['err']     = 'yes';
			wp_send_json( $response );
		}
		
		ob_start();
		
		get_template_part( 'template-parts/header', 'minicart' );
		
		$mini_cart = ob_get_clean();
		
		$response['fragments']      = apply_filters( 'woocommerce_add_to_cart_fragments', array(
			                                                                                'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
		                                                                                )
		);
		$response['cart_hash']      = apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() );
		$response['mini_cart_html'] = $mini_cart;
		
		wp_send_json( $response );
		
		die();
	}
	
	add_action( 'wp_ajax_casano_remove_cart_item_via_ajax', 'casano_remove_cart_item_via_ajax' );
	add_action( 'wp_ajax_nopriv_casano_remove_cart_item_via_ajax', 'casano_remove_cart_item_via_ajax' );
}
function casano_ajax_add_to_cart_redirect_template() {
	if ( isset( $_REQUEST['casano-ajax-add-to-cart'] ) ) {
		get_template_part( 'template-parts/header', 'minicart' );
		exit;
	}
}

add_action( 'wp', 'casano_ajax_add_to_cart_redirect_template', 1000 );


// ====================================
/**
 * Wislist in single product
 */
add_action( 'woocommerce_after_add_to_cart_button', 'woocommerce_template_single_sharing', 50 );
add_filter( 'yith_wcwl_positions', 'casano_single_product_wislist_button_positions', 999, 1 );
if ( ! function_exists( 'casano_single_product_wislist_button_positions' ) ) {
	function casano_single_product_wislist_button_positions( $positions ) {
		global $product;
		if ( isset( $positions['add-to-cart']['hook'] ) ) {
			if ( ( gettype( $product ) == "object" ) && $product->is_type( 'variable' ) ) {
				$positions['add-to-cart']['hook'] = 'woocommerce_after_single_variation';
			} else {
				$positions['add-to-cart']['hook'] = 'woocommerce_after_add_to_cart_button';
			}
		}
		if ( isset( $positions['add-to-cart']['priority'] ) ) {
			$positions['add-to-cart']['priority'] = 1;
		}
		
		return $positions;
	}
}
/**
 * Custom title woo
 */

if ( ! function_exists( 'casano_woocommerce_page_title' ) ) {
	
	/**
	 * casano_woocommerce_page_title function.
	 *
	 * @param  bool $echo
	 *
	 * @return string
	 */
	function casano_woocommerce_page_title( $show = true ) {
		return false;
	}
}
if ( ! function_exists( 'casano_woocommerce_catalog_ordering' ) ) {
	
	/**
	 * Output the product sorting options.
	 */
	function casano_woocommerce_catalog_ordering() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}
		$show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
			'menu_order' => __( 'Sort by', 'casano' ),
			'popularity' => __( 'Popularity', 'casano' ),
			'rating'     => __( 'Rating', 'casano' ),
			'date'       => __( 'Newness', 'casano' ),
			'price'      => __( 'Price: low', 'casano' ),
			'price-desc' => __( 'Price: high', 'casano' ),
		) );
		
		$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
		$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.
		
		if ( wc_get_loop_prop( 'is_search' ) ) {
			$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'casano' ) ), $catalog_orderby_options );
			
			unset( $catalog_orderby_options['menu_order'] );
		}
		
		if ( ! $show_default_orderby ) {
			unset( $catalog_orderby_options['menu_order'] );
		}
		
		if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
			unset( $catalog_orderby_options['rating'] );
		}
		
		if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
			$orderby = current( array_keys( $catalog_orderby_options ) );
		}
		
		wc_get_template( 'loop/fami-orderby.php', array(
			'catalog_orderby_options' => $catalog_orderby_options,
			'orderby'                 => $orderby,
			'show_default_orderby'    => $show_default_orderby,
		) );
	}
}
if ( ! function_exists( 'casano_function_offer_boxed_product' ) ) {
	function casano_function_offer_boxed_product() {
		$offer_meta  = get_post_meta( get_the_ID(), '_offer_boxed_product_metabox_theme_options', true );
		$title_offer = isset( $offer_meta['title_offer_boxed'] ) ? $offer_meta['title_offer_boxed'] : '';
		$list_offer  = isset( $offer_meta['list_offer_boxed'] ) ? $offer_meta['list_offer_boxed'] : '';
		if ( $list_offer ) { ?>
            <div class="offer-boxed-product">
				<?php if ( $title_offer ) { ?>
                    <h4 class="title-offer"><?php echo esc_attr( $title_offer ); ?></h4>
				<?php } ?>
				<?php echo wp_specialchars_decode( $list_offer ); ?>
            </div>
		<?php }
	}
}
//SIZE GUIDE
if ( ! function_exists( 'casano_size_guide' ) ) {
	function casano_size_guide() {
		$product_meta   = get_post_meta( get_the_ID(), '_custom_product_metabox_theme_options', true );
		$on_sizeguide = isset( $product_meta['size_guide'] ) ? $product_meta['size_guide'] : false;
		if ( $on_sizeguide ) : ?>
            <span class="size-guide-text" data-toggle="modal" data-target="#popup-size-guide"><?php esc_html_e( 'Size Guide', 'casano' ); ?></span>
		<?php endif;
	}
}
//PRODUCT DEAL
if ( ! function_exists( 'casano_function_shop_loop_item_countdown' ) ) {
	function casano_function_shop_loop_item_countdown() {
		global $product;
		$date = casano_get_max_date_sale( $product->get_id() );
		if ( $date > 0 ) {
			?>
            <div class="deals-in-wrap">
                <h4 class="deals-title"><?php echo esc_html__( 'Hungry Up ! Deals end in :', 'casano' ); ?></h4>
                <div class="countdown-product casano-countdown">
                    <div class="timers" data-date="<?php echo date( 'm/j/Y g:i:s', $date ); ?>">
                        <div class="timer-day box"><span class="time day"></span><span
                                    class="time-title"><?php echo esc_html__( 'Days', 'casano' ); ?></span></div>
                        <div class="timer-hour box"><span class="time hour"></span><span
                                    class="time-title"><?php echo esc_html__( 'Hours', 'casano' ); ?></span></div>
                        <div class="timer-min box"><span class="time min"></span><span
                                    class="time-title"><?php echo esc_html__( 'Mins', 'casano' ); ?></span></div>
                        <div class="timer-secs box"><span class="time secs"></span><span
                                    class="time-title"><?php echo esc_html__( 'Secs', 'casano' ); ?></span></div>
                    </div>
                </div>
            </div>
			<?php
		}
	}
}
if ( ! function_exists( 'casano_get_max_date_sale' ) ) {
	function casano_get_max_date_sale( $product_id ) {
		$date_now = current_time( 'timestamp', 0 );
		// Get variations
		$args          = array(
			'post_type'   => 'product_variation',
			'post_status' => array( 'private', 'publish' ),
			'numberposts' => - 1,
			'orderby'     => 'menu_order',
			'order'       => 'asc',
			'post_parent' => $product_id,
		);
		$variations    = get_posts( $args );
		$variation_ids = array();
		if ( $variations ) {
			foreach ( $variations as $variation ) {
				$variation_ids[] = $variation->ID;
			}
		}
		$sale_price_dates_to = false;
		if ( ! empty( $variation_ids ) ) {
			global $wpdb;
			$sale_price_dates_to = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join( ',', $variation_ids ) . ") ORDER BY meta_value DESC LIMIT 1" );
			if ( $sale_price_dates_to != '' ) {
				return $sale_price_dates_to;
			}
		}
		if ( ! $sale_price_dates_to ) {
			$sale_price_dates_to   = get_post_meta( $product_id, '_sale_price_dates_to', true );
			$sale_price_dates_from = get_post_meta( $product_id, '_sale_price_dates_from', true );
			if ( $sale_price_dates_to == '' || $date_now < $sale_price_dates_from ) {
				$sale_price_dates_to = '0';
			}
		}
		
		return $sale_price_dates_to;
	}
}
/* GALLERY PRODUCT */
if ( ! function_exists( 'casano_gallery_product_thumbnail' ) ) {
	function casano_gallery_product_thumbnail( $args = array() ) {
		global $post, $product;
		// GET SIZE IMAGE SETTING
		$crop            = true;
		$html            = '';
		$html_thumb      = '';
		$attachment_ids  = $product->get_gallery_image_ids();
		$class_img_thumb = 'attachment-post-thumbnail';
		$class_img_thumb .= ' wp-post-image';
		/* primary image */
		$primary_image       = casano_resize_image( get_post_thumbnail_id( $product->get_id() ), null, 600, 600, $crop, true, false );
		$primary_image_small = casano_resize_image( get_post_thumbnail_id( $product->get_id() ), null, 120, 134, $crop, true, false );
		$html                .= '<figure>' . casano_img_output( $primary_image, $class_img_thumb, get_the_title() ) . '</figure>';
		$html_thumb          .= '<figure>' . casano_img_output( $primary_image_small, $class_img_thumb, get_the_title() ) . '</figure>';
		/* thumbnail image */
		if ( $attachment_ids && has_post_thumbnail() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				$secondary_thumb       = casano_resize_image( $attachment_id, null, 600, 600, $crop, true, false );
				$secondary_thumb_small = casano_resize_image( $attachment_id, null, 120, 134, $crop, true, false );
				$html                  .= '<figure>' . casano_img_output( $secondary_thumb, $class_img_thumb, esc_attr( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) . '</figure>';
				$html_thumb            .= '<figure>' . casano_img_output( $secondary_thumb_small, $class_img_thumb, esc_attr( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) . '</figure>';
			}
		}
		?>
        <div class="product-gallery">
            <div class="product-gallery-slick">
				<?php echo wp_specialchars_decode( $html ); ?>
            </div>
            <div class="vertical_thumnail">
				<?php echo wp_specialchars_decode( $html_thumb ); ?>
            </div>
        </div>
		<?php
	}
}
/**
 *
 * SHOP CATEGORY PAGE
 */
add_action( 'casano_woocommerce_before_main_content', 'casano_woocommerce_category_description', 60 );
if ( ! function_exists( 'casano_woocommerce_category_description' ) ) {
	function casano_woocommerce_category_description() {
		$enable_cat = casano_get_option( 'enable_best_seller_on_product_cat', false );
		$banner_cat = casano_get_option( 'category_banner' );
		$banner_url = casano_get_option( 'category_banner_url', '#' );
		if ( is_product_category() && $enable_cat ) {
			$category_html     = '';
			if ( $banner_cat ) {
				$banner_cate_img  = casano_resize_image( $banner_cat, null, 1400, 359, false, false, false );
				$class_img_thumb  = 'img-banner-cat';
				$image_banner_cat = '<figure>' . casano_img_output( $banner_cate_img, $class_img_thumb, get_the_title() ) . '</figure>';
				$category_html    .= '<div class="product-grid categories-slide col-sm-12"><a href="' . esc_url( $banner_url ) . '">' . wp_specialchars_decode( $image_banner_cat ) . '</a></div>';
			}
			?>
            <div class="categories-product-woo row <?php //echo esc_attr( $class_shop ); ?>">
				<?php echo wp_specialchars_decode( $category_html ); ?>
            </div>
			<?php
		}
	}
}
