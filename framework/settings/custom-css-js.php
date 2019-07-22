<?php

if ( ! function_exists( 'casano_custom_css' ) ) {
	function casano_custom_css() {
		$css = '';
		$css .= casano_theme_color();
		$css .= casano_vc_custom_css_footer();
		wp_enqueue_style( 'casano_custom_css', get_theme_file_uri( '/assets/css/customs.css' ), array(), '1.0' );
		wp_add_inline_style( 'casano_custom_css', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'casano_custom_css', 999 );

if ( ! function_exists( 'casano_theme_color' ) ) {
	function casano_theme_color() {
		$css = '';
		
		// Typography
		$enable_google_font = casano_get_option( 'enable_google_font', false );
		if ( $enable_google_font ) {
			$body_font = casano_get_option( 'typography_themes' );
			if ( ! empty( $body_font ) ) {
				$typography_themes['family']  = 'Open Sans';
				$typography_themes['variant'] = '400';
				$body_fontsize        = casano_get_option( 'fontsize-body', '15' );
				
				$css .= 'body{';
				$css .= 'font-family: "' . $body_font['family'] . '";';
				if ( '100italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 100;
					font-style: italic;
				';
				} elseif ( '300italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 300;
					font-style: italic;
				';
				} elseif ( '400italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 400;
					font-style: italic;
				';
				} elseif ( '700italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 700;
					font-style: italic;
				';
				} elseif ( '800italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 700;
					font-style: italic;
				';
				} elseif ( '900italic' == $body_font['variant'] ) {
					$css .= '
					font-weight: 900;
					font-style: italic;
				';
				} elseif ( 'regular' == $body_font['variant'] ) {
					$css .= 'font-weight: 400;';
				} elseif ( 'italic' == $body_font['variant'] ) {
					$css .= 'font-style: italic;';
				} else {
					$css .= 'font-weight:' . $body_font['variant'] . ';';
				}
				// Body font size
				if ( $body_fontsize ) {
					$css .= 'font-size:' . esc_attr( $body_fontsize ) . 'px;';
				}
				$css .= '}';
				$css .= 'body
				{
					font-family: "' . $body_font['family'] . '";
				}
			
				';
			}
		}
		
		/* Main color */
		$main_color      = casano_get_option( 'casano_main_color', '#2dbbf0' );
		$body_text_color = trim( casano_get_option( 'casano_body_text_color', '' ) );
		$css .= '
				.post-password-form input[type="submit"]:hover,
				.woocommerce-error .button:hover, .woocommerce-info .button:hover, .woocommerce-message .button:hover,
				.widget_shopping_cart .woocommerce-mini-cart__buttons .button:not(.checkout):hover,
				.widget_shopping_cart .woocommerce-mini-cart__buttons .button.checkout,
				#widget-area .widget .select2-container--default .select2-selection--multiple .select2-selection__choice,
				.woocommerce-widget-layered-nav-dropdown .woocommerce-widget-layered-nav-dropdown__submit:hover,
				.fami-btn:hover,
				.owl-carousel.circle-dark .owl-nav > *:hover,
				.header .minicart-content-inner .minicart-number-items,
				.header .minicart-content-inner .actions .button.button-checkout:hover,
				.header .minicart-content-inner .actions .button:hover,
				.search-view,
				.comment-text .comment-edit-link:hover,
				.comment-text .comment-reply-link:hover,
				.comment-form .form-submit #submit:hover,
				.reset_variations:hover,
				.panel-categories.cate-image .owl-carousel .owl-nav > *:hover,
				.part-filter-wrap .filter-toggle,
				.part-filter-wrap .filter-toggle-button,
				.casano_socials_list_widget .social::before,
				.prdctfltr_wc .prdctfltr_woocommerce_filter_title,
				.prdctfltr_wc .prdctfltr_woocommerce_filter_title:hover,
				.onsale,
				#yith-wcwl-popup-message,
				.fami-wccp-content-wrap .owl-carousel .owl-nav > button:hover,
				.return-to-shop .button:hover,
				.actions-btn .shopping:hover,
				.actions .coupon .button:hover,
				.wc-proceed-to-checkout .checkout-button:hover,
				.track_order .form-tracking .button:hover,
				#popup-newsletter .newsletter-form-wrap .submit-newsletter:hover,
				.casano-content-single-product-mobile .product-mobile-layout .woocommerce-product-gallery .flex-control-nav.flex-control-thumbs li img.flex-active,
				.bestseller-cat-products .block-title > a,
				body.error404 .page-404 .button:hover,
				.wpcf7-form .wpcf7-submit:hover,
				.casano-title.style-03 .button:hover,
				.casano-newsletter.style-02 .newsletter-form-wrap .submit-newsletter:hover,
				.casano-newsletter.style-04 .newsletter-form-wrap .submit-newsletter,
				.casano-socials.style-01 .social-item::after,
				.casano-banner.style-02 .button:hover,
				.casano-instagram-sc.style-01 .icon,
				.casano-button.style-02 .button:hover,
				a.backtotop {
					background-color: ' . esc_attr( $main_color ) . ';
				}
				.widget_tag_cloud .tagcloud a:hover {
					background-color: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				a:hover, a:focus, a:active,
				.wcml-dropdown .wcml-cs-submenu li:hover > a,
				.horizon-menu .main-menu .menu-item .submenu .menu-item:hover > a,
				.horizon-menu .main-menu .menu-item:hover > .toggle-submenu,
				.close-vertical-menu:hover,
				.vertical-menu .main-navigation .main-menu > .menu-item:hover > a,
				.header .casano-minicart:hover .mini-cart-icon,
				.header .minicart-content-inner .close-minicart:hover,
				.header .minicart-items .product-cart .product-remove .remove:hover,
				.header-search-box .search-icon:hover,
				.header-search-box > .icons:hover,
				.instant-search-modal .product-cats label span:hover,
				.instant-search-modal .product-cats label.selected span,
				.currency-language .dropdown .active a,
				header .wcml_currency_switcher li li a:hover,
				.block-account a:hover,
				.post-item .categories a,
				.post-content .dropcap,
				.single-post-info .categories a:hover,
				.woocommerce-product-gallery .woocommerce-product-gallery__trigger:hover,
				.summary .woocommerce-product-rating .woocommerce-review-link:hover,
				.detail-content .summary .price,
				.summary .stock.out-of-stock,
				div button.close,
				.blog-grid .post-meta .categories,
				.filter-button-group .filter-list .blog-filter.active,
				.product_meta a:hover,
				.social-share-product .share-product-title:hover,
				.casano-social-product a:hover,
				.center_slider .price,
				.close-tab:hover,
				.wc-tabs li a:hover,
				a.product-sticky-toggle-tab-content:hover,
				.wc-tabs li.active a,
				.wc-tabs li.active,
				p.stars:hover a:before,
				p.stars.selected:not(:hover) a:before,
				.total-price-html,
				div.famibt-wrap .famibt-item .famibt-price,
				.famibt-wrap ins,
				.WOOF_Widget .woof_container .icheckbox_flat-purple.checked ~ label,
				.WOOF_Widget .woof_container .iradio_flat-purple.checked ~ label,
				.WOOF_Widget .woof_container li label.hover,
				.WOOF_Widget .woof_container li label.hover,
				.box-mobile-menu .back-menu:hover,
				.box-mobile-menu .close-menu:hover,
				.box-mobile-menu .main-menu .menu-item.active > a,
				.box-mobile-menu .main-menu .menu-item:hover > a,
				.box-mobile-menu .main-menu .menu-item:hover > .toggle-submenu::before,
				.widget_search .searchform button:hover,
				.casano_newsletter_widget button:hover,
				nav.woocommerce-breadcrumb a:hover,
				.toolbar-products .category-filter li.active a,
				.toolbar-products .category-filter li a:hover,
				span.prdctfltr_reset-clone:hover,
				span.prdctfltr_title_selected:hover,
				.prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title:hover,
				.enable-shop-page-mobile .shop-page a.products-size.products-list.active,
				.enable-shop-page-mobile .woocommerce-page-header ul .line-hover a:hover,
				.enable-shop-page-mobile .woocommerce-page-header ul .line-hover.active a,
				.price ins,
				.product-item.style-1 .add-to-cart a:hover,
				.product-item.style-3 .yith-wcqv-button:hover,
				.product-item.style-3 .fami-wccp-button:hover,
				.product-item.style-1 .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a::before,
				.product-item.style-1 .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a::before,
				.product-item.style-2 .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a::before,
				.product-item.style-2 .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a::before,
				.yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse a::before,
				.yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse a::before,
				.toolbar-products-mobile .cat-item.active, .toolbar-products-mobile .cat-item.active a,
				.real-mobile-toolbar.toolbar-products-shortcode .cat-item.active, .real-mobile-toolbar.toolbar-products-shortcode .cat-item.active a,
				.fami-wccp-field .woocommerce-Price-amount.amount,
				body .woocommerce table.shop_table tr td.product-remove a:hover,
				.validate-required label::after,
				.woocommerce-MyAccount-navigation > ul li.is-active a,
				#popup-newsletter button.close:hover,
				.single-product-mobile .product-grid .product-info .price,
				div.casano-content-single-product-mobile .summary .yith-wcwl-add-to-wishlist,
				body .vc_toggle_default.vc_toggle_active .vc_toggle_title > h4,
				.casano-newsletter.style-01 .newsletter-form-wrap .submit-newsletter:hover,
				.casano-newsletter.style-03 .newsletter-form-wrap .submit-newsletter:hover {
					color: ' . esc_attr( $main_color ) . ';
				}
				blockquote, q {
					border-left: 3px solid ' . esc_attr( $main_color ) . ';
				}
				.header .casano-minicart .mini-cart-icon .minicart-number {
					background: ' . esc_attr( $main_color ) . ';
				}
				.header .to-cart::before {
					border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.instant-search-modal .product-cats label span::before {
					border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::before {
					border-color: ' . esc_attr( $main_color ) . ';
				}
				.currency-language .wcml-dropdown-click a.wcml-cs-item-toggle:hover::after {
					border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
				}
				.currency-language .dropdown > a:hover::after {
					border-color: ' . esc_attr( $main_color ) . ' transparent transparent transparent;
				}
				.currency-language .dropdown > a:hover::before {
					border-color: ' . esc_attr( $main_color ) . ';
				}
				.post-item .tags a:hover {
					background-color: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				.casano-share-socials a:hover {
					background-color: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				.blog-grid .title span::before {
					border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.filter-button-group .filter-list .blog-filter::before {
					border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.summary .cart .single_add_to_cart_button {
					background: ' . esc_attr( $main_color ) . ';
				}
				.summary .yith-wcwl-add-to-wishlist:hover {
					border-color: ' . esc_attr( $main_color ) . ';
					background-color: ' . esc_attr( $main_color ) . ';
				}
				.summary .compare:hover,
				.summary .fami-wccp-button:hover {
					color: ' . esc_attr( $main_color ) . ' !important;
				}
				@media (min-width: 1200px) {
					.unique-wrap .summary .woocommerce-variation-add-to-cart .yith-wcwl-add-to-wishlist:hover,
					.unique-wrap .summary .cart .woocommerce-variation-add-to-cart .single_add_to_cart_button:hover {
						color: ' . esc_attr( $main_color ) . ';
					}
				}
				.sticky_info_single_product button.casano-single-add-to-cart-btn.btn.button {
					background: ' . esc_attr( $main_color ) . ';
				}
				a.product-sticky-toggle-tab-content::before {
					border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.wc-tabs li a::before {
					border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.famibt-messages-wrap a.button.wc-forward:hover {
					background: ' . esc_attr( $main_color ) . ';
				}
				.panel-categories.cate-count .panel-categories-inner .category-title > a::before {
					border-bottom: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.panel-categories.cate-icon .panel-categories-inner .category-title::before {
					border-bottom: 3px solid ' . esc_attr( $main_color ) . ';
				}
				.price_slider_amount .button:hover, .price_slider_amount .button:focus {
					background-color: ' . esc_attr( $main_color ) . ';
					border: 2px solid ' . esc_attr( $main_color ) . ';
				}
				.WOOF_Widget .woof_container li .icheckbox_flat-purple.hover,
				.WOOF_Widget .woof_container li .iradio_flat-purple.hover,
				.icheckbox_flat-purple.checked,
				.iradio_flat-purple.checked {
					background: ' . esc_attr( $main_color ) . ' 0 0 !important;
					border: 1px solid ' . esc_attr( $main_color ) . ' !important;
				}
				.main-product .with_background .summary .yith-wcwl-add-to-wishlist:hover {
					background-color: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				.toolbar-products .category-filter li a::before {
					border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label.prdctfltr_active > span::before,
				div.prdctfltr_wc.prdctfltr_round .prdctfltr_filter label:hover > span::before {
					background: ' . esc_attr( $main_color ) . ';
					border: 1px double ' . esc_attr( $main_color ) . ';
					color: ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr_woocommerce_filter_submit:hover, .prdctfltr_wc .prdctfltr_buttons .prdctfltr_reset span:hover, .prdctfltr_sale:hover,
				.prdctfltr_instock:hover {
					background: ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr_sc.hide-cat-thumbs .product-category h2.woocommerce-loop-category__title::before {
					border-bottom: 1px solid ' . esc_attr( $main_color ) . ';
				}
				.prdctfltr-pagination-load-more .button:hover {
					background: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				div.pf_rngstyle_flat .irs-from::after, div.pf_rngstyle_flat .irs-to::after, div.pf_rngstyle_flat .irs-single::after {
					border-top-color: ' . esc_attr( $main_color ) . ';
				}
				.product-item.style-2 .yith-wcqv-button .blockOverlay,
				.product-item.style-2 .compare .blockOverlay {
					background: ' . esc_attr( $main_color ) . ' !important;
				}
				.fami-wccp-products-list-wrap .actions-wrap a:hover {
					background: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				body .woocommerce table.shop_table .product-add-to-cart .add_to_cart:hover {
					background: ' . esc_attr( $main_color ) . ';
				}
				.woocommerce-MyAccount-content input.button:hover {
					background: ' . esc_attr( $main_color ) . ';
				}
				.woocommerce-cart-form-mobile .actions .actions-btn .shopping:hover {
					background-color: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
				.error404 .casano-searchform button {
					background: ' . esc_attr( $main_color ) . ';
				}
				body.wpb-js-composer .vc_tta-style-classic .vc_tta-panel.vc_active .vc_tta-panel-title > a {
					color: ' . esc_attr( $main_color ) . ' !important;
				}
				.casano-mapper .casano-pin .casano-popup-footer a:hover {
					background: ' . esc_attr( $main_color ) . ' !important;
					border-color: ' . esc_attr( $main_color ) . ' !important;
				}
				@media (min-width: 992px) {
					.ziss-popup-wrap .ziss-popup-inner .ziss-popup-body.ziss-right-no-content ~ .ziss-popup-nav:hover,
					.ziss-popup-wrap .ziss-popup-inner .ziss-popup-body:not(.ziss-right-no-content) ~ .ziss-popup-nav:hover {
						color: ' . esc_attr( $main_color ) . ';
					}
				}
				.casano-products.style-1 .owl-nav > button:hover {
					background-color: ' . esc_attr( $main_color ) . ' !important;
				}
				.casano-button.style-01 .button:hover {
					background-color: ' . esc_attr( $main_color ) . ';
					border-color: ' . esc_attr( $main_color ) . ';
				}
		';
		
		if ( $body_text_color && $body_text_color != '' ) {
			$css .= 'body {color: ' . esc_attr( $body_text_color ) . '}';
		}
		
		return $css;
	}
}

if ( ! function_exists( 'casano_vc_custom_css_footer' ) ) {
	function casano_vc_custom_css_footer() {
		
		$casano_footer_options = casano_get_option( 'casano_footer_options', '' );
		$page_id              = casano_get_single_page_id();
		
		$data_option_meta = get_post_meta( $page_id, '_custom_metabox_theme_options', true );
		if ( $page_id > 0 ) {
			$enable_custom_footer = false;
			if ( isset( $data_option_meta['enable_custom_footer'] ) ) {
				$enable_custom_footer = $data_option_meta['enable_custom_footer'];
			}
			if ( $enable_custom_footer ) {
				$casano_footer_options = $data_option_meta['casano_metabox_footer_options'];
			}
		}
		
		$shortcodes_custom_css = get_post_meta( $casano_footer_options, '_wpb_post_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $casano_footer_options, '_wpb_shortcodes_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $casano_footer_options, '_casano_shortcode_custom_css', true );
		$shortcodes_custom_css .= get_post_meta( $casano_footer_options, '_responsive_js_composer_shortcode_custom_css', true );
		
		return $shortcodes_custom_css;
	}
}