<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post_type                     = class_exists( 'WooCommerce' ) ? 'product' : '';
$enable_instant_product_search = casano_get_option( 'enable_instant_product_search', false );
$product_search_place_holder   = $enable_instant_product_search ? esc_html__( 'Start search...', 'casano' ) : esc_html__( 'Search...', 'casano' );
$search_form_class             = 'instant-search';
if ( ! $enable_instant_product_search ) {
	$post_type = '';
}

if ( $post_type != 'product' ) {
	$search_form_class .= ' instant-search-disabled';
}

?>
<div class="search-block">
    <a href="#" class="search-icon"><span class="flaticon-magnifying-glass-1 icon"></span></a>
    <form autocomplete="off" method="get" class="search-form <?php echo esc_attr( $search_form_class ); ?>"
          action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <div class="search-close" data-casano="casano-dropdown"><span class="flaticon-close"></span><?php echo esc_html__('Close','casano')?></div>
        <div class="search-fields">
            <div class="search-input">
                <span class="reset-instant-search-wrap"></span>
                <input type="search" class="search-field"
                       placeholder="<?php echo esc_attr( $product_search_place_holder ); ?>" value="" name="s">
				<?php if ( $post_type != '' ) { ?>
                    <input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
				<?php } ?>
                <button type="submit" class="search-submit"><span class="flaticon-magnifying-glass-1"></span>
                </button>
                <div class="search-results-wrapper">
                    <div class="search-results-container search-results-croll scrollbar-macosx">
                        <div class="search-results-container-inner">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>