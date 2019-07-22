<?php
/*
 Name:  Header Style 02
 */
$menu_sticky          = casano_get_option( 'enable_sticky_menu', 'none' );
$header_pos           = casano_get_option( 'header_position', 'relative' );
$header_color         = casano_get_option( 'header_color', 'dark' );
$header_class         = '';
$single_id            = casano_get_single_page_id();
$enable_custom_header = false;

$meta_data = get_post_meta( $single_id, '_custom_metabox_theme_options', true );
// Override custom header (if request from url)
if ( isset( $_GET['enable_custom_header'] ) ) {
	$meta_data['enable_custom_header'] = $_GET['enable_custom_header'] == 'yes';
}
if ( isset( $meta_data['enable_custom_header'] ) ) {
	$enable_custom_header = $meta_data['enable_custom_header'];
}
if ( $enable_custom_header ) {
	$header_pos   = isset( $meta_data['header_position'] ) ? $meta_data['header_position'] : $header_pos;
	$header_color = isset( $meta_data['header_color'] ) ? $meta_data['header_color'] : $header_color;
	$menu_sticky  = $meta_data['enable_sticky_menu'];
}
if ( ( ! is_front_page() && is_home() ) || is_category() || is_tag() ) {
	$header_pos   = casano_get_option( 'blog_header_position', 'relative' );
	$header_color = casano_get_option( 'blog_header_color', 'dark' );
}
if ( class_exists( 'WooCommerce' ) ) {
	if ( is_shop() || is_product_category() || is_product_tag() ) {
		$header_pos   = casano_get_option( 'shop_header_position', 'relative' );
		$header_color = casano_get_option( 'shop_header_color', 'dark' );
	}
}
if ( $menu_sticky == 'normal' ) {
	$header_class = ' menu-sticky-nomal';
} elseif ( $menu_sticky == 'smart' ) {
	$header_class = ' menu-sticky-smart';
}

$header_class               .= ' header-pos-' . esc_attr( $header_pos );
$header_class               .= ' header-color-' . esc_attr( $header_color );
$enable_info_product_single = casano_get_option( 'enable_info_product_single', false );
if ( $enable_info_product_single ) {
	$header_class .= ' sticky-info_single';
}
?>
<header id="header"
        class="site-header header style-02 <?php echo esc_attr( $header_class ); ?>">
    <div class="header-main-inner">
        <div class="header-wrap">
            <div class="header-wrap-stick">
                <div class="header-position">
                    <div class="main-menu-wrapper"></div>
                    <div class="header-container">
                        <div class="header-table">
                            <div class="header-left">
                                <div class="logo">
									<?php casano_get_logo(); ?>
                                </div>
                            </div>
                            <div class="header-center">
                                <?php if ( has_nav_menu( 'primary' ) ) { ?>
                                    <div class="horizon-menu">
                                        <nav class="main-navigation">
                                            <?php
                                            wp_nav_menu( array(
                                                    'menu'            => 'primary',
                                                    'theme_location'  => 'primary',
                                                    'depth'           => 4,
                                                    'container'       => '',
                                                    'container_class' => '',
                                                    'container_id'    => '',
                                                    'menu_class'      => 'clone-main-menu casano-nav main-menu',
                                                    'fallback_cb'     => 'Casano_navwalker::fallback',
                                                    'walker'          => new Casano_navwalker(),
                                                )
                                            );
                                            ?>
                                        </nav>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="header-control-right">
                                <div class="header-control-wrap">
									<?php if ( class_exists( 'SitePress' ) ) { ?>
                                        <div class="currency-language-wrap">
                                            <ul class="currency-language">
												<?php
												get_template_part( 'template-parts/header', 'language' );
												?>
                                            </ul>
                                        </div>
									<?php } ?>
									<?php get_template_part( 'template-parts/header', 'currency' ); ?>
                                    <div class="header-search-box">
		                                <?php casano_search_form(); ?>
                                    </div>
									<?php if ( class_exists( 'WooCommerce' ) ) { ?>
                                        <div class="block-account">
											<?php if ( is_user_logged_in() ) { ?>
												<?php $currentUser = wp_get_current_user(); ?>
                                                <a class="header-userlink"
                                                   href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                                                   title="<?php esc_attr_e( 'My Account', 'casano' ); ?>">
                                                    <span class="screen-reader-text"><?php echo sprintf( esc_html__( 'Hi, %s', 'casano' ), $currentUser->display_name ); ?></span>
                                                    <span class="flaticon-user"></span>
                                                </a>
											<?php } else { ?>
                                                <a href="#login-popup" data-effect="mfp-zoom-in" class="acc-popup">
                                                <span>
                                                    <span class="flaticon-user"></span>
                                                </span>
                                                </a>
											<?php } ?>
                                        </div>
										<?php get_template_part( 'template-parts/header', 'minicart' ); ?>
									<?php }; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-action-res">
		<?php if ( class_exists( 'WooCommerce' ) ) { ?>
            <div class="acction-right">
                <div class="block-account">
					<?php if ( is_user_logged_in() ) { ?>
						<?php $currentUser = wp_get_current_user(); ?>
                        <a class="header-userlink"
                           href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"
                           title="<?php esc_attr_e( 'My Account', 'casano' ); ?>">
                            <span class="screen-reader-text"><?php echo sprintf( esc_html__( 'Hi, %s', 'casano' ), $currentUser->display_name ); ?></span>
                            <span class="flaticon-user"></span>
                        </a>
					<?php } else { ?>
                        <a href="#login-popup" data-effect="mfp-zoom-in" class="acc-popup">
                                            <span>
                                                <span class="flaticon-user"></span>
                                            </span>
                        </a>
					<?php } ?>
                </div>
				<?php get_template_part( 'template-parts/header', 'minicart' ); ?>
            </div>
		<?php }; ?>
        <div class="logo">
			<?php casano_get_logo(); ?>
        </div>
        <div class="meta-woo">
			<?php casano_search_form(); ?>
			<?php if ( has_nav_menu( 'primary' ) ) { ?>
                <a class="menu-bar mobile-navigation" href="javascript:void(0)">
                <span class="menu-btn-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                </a>
			<?php } ?>
        </div>
    </div>
	<?php
	get_template_part( 'template-parts/hero', 'section' );
	?>
</header>