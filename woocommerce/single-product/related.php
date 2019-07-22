<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version       4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product, $woocommerce_loop;

if (empty($product) || !$product->exists()) {
    return;
}

if (!$related_products) {
    return;
}

$classes = array();
$casano_woo_product_style = 1;
$casano_enable_relate_products = casano_get_option('enable_relate_products', 'yes');
if ($casano_enable_relate_products != 'yes') {
    return;
}

$classes[] = 'product-item style-' . $casano_woo_product_style;
$template_style = 'style-' . $casano_woo_product_style;

$woo_related_ls_items = casano_get_option('casano_woo_related_ls_items', 4);
$woo_related_lg_items = casano_get_option('casano_woo_related_lg_items', 4);
$woo_related_md_items = casano_get_option('casano_woo_related_md_items', 3);
$woo_related_sm_items = casano_get_option('casano_woo_related_sm_items', 2);
$woo_related_xs_items = casano_get_option('casano_woo_related_xs_items', 2);
$woo_related_ts_items = casano_get_option('casano_woo_related_ts_items', 2);

$data_reponsive = array(
    '0' => array(
        'items' => $woo_related_ts_items,
        'margin' => 20,
    ),
    '360' => array(
        'items' => $woo_related_xs_items,
        'margin' => 20,
    ),
    '768' => array(
        'items' => $woo_related_sm_items,
        'margin' => 20,
    ),
    '992' => array(
        'items' => $woo_related_md_items,
        'margin' => 22,
    ),
    '1200' => array(
        'items' => $woo_related_lg_items,
        'margin' => 22,
    ),
    '1500' => array(
        'items' => $woo_related_ls_items,
        'margin' => 22,
    ),
);

$data_reponsive = json_encode($data_reponsive);
$loop = 'false';
$dots = 'true';
$data_margin = '22';
$woo_related_title = casano_get_option('casano_related_products_title', 'Related Products');

if ($related_products) : ?>
    <section class="related products product-grid">
        <div class="container-width">
            <h2 class="product-grid-title"><?php echo esc_html($woo_related_title) ?></h2>
            <div class="owl-carousel owl-products equal-container better-height nav-center"
                 data-margin="<?php echo esc_attr($data_margin); ?>" data-nav="true"
                 data-dots="<?php echo esc_attr($dots); ?>" data-loop="<?php echo esc_attr($loop); ?>"
                 data-responsive='<?php echo esc_attr($data_reponsive); ?>'>
                <?php foreach ($related_products as $related_product) : ?>
                    <div <?php post_class($classes) ?> >
                        <?php
                        $post_object = get_post($related_product->get_id());

                        setup_postdata($GLOBALS['post'] =& $post_object);

                        wc_get_template_part('product-styles/content-product', $template_style); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif;

wp_reset_postdata();
