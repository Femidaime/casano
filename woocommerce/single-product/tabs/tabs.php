<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );
$enable_single_product_mobile = casano_get_option( 'enable_single_product_mobile', false );
$product_style                = casano_get_option( 'casano_woo_single_product_layout', 'default' );
$product_meta                 = get_post_meta( get_the_ID(), '_custom_product_metabox_theme_options', true );
if ( isset( $product_meta['product_style'] ) ) {
    if (trim($product_meta['product_style'] != '') && $product_meta['product_style'] != 'global') {
        $product_style = $product_meta['product_style'];
    }
}
if( ($enable_single_product_mobile == 1) && (casano_is_mobile())){ ?>
	<div class="woocommerce-tabs-mobile">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="tabs-mobile-content">
			    <a href="javascript:void(0)" class="button-togole product-mobile-toggle-tab-content" data-togole="<?php echo esc_attr( $key ); ?>">
			    	<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
			    </a>
			    <div id="<?php echo esc_attr( $key ); ?>" class="content-tab-element">
			    	<button class="close-tab"></button>
			    	<h3 class="title-tab"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></h3>
			        <div class="content-des"><?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
<?php } elseif($product_style == 'sticky_detail' || $product_style == 'slider_large' || $product_style == 'modern') {?>
    <div class="woocommerce-tabs-sticky">
        <?php foreach ( $tabs as $key => $tab ) : ?>
            <div class="tabs-sticky-content">
                <a href="#" class="button-togole-tab product-sticky-toggle-tab-content" data-togole="<?php echo esc_attr( $key ); ?>">
                    <?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
                </a>
                <div id="<?php echo esc_attr( $key ); ?>" class="content-tab-sticky-element">
                    <div class="content-tab-sticky"><?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php }else{
if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper">
		<ul class="tabs wc-tabs" role="tablist">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<?php if ( isset( $tab['callback'] ) ) { call_user_func( $tab['callback'], $key, $tab ); } ?>
			</div>
		<?php endforeach; ?>
	</div>

<?php endif; ?>
<?php } ?>

