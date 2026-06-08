<?php
/**
 * AJAX callback: bulk SKU generation for products in a category.
 *
 * @package EasyAutoSkuGenerator
 */

use EasyAutoSkuGenerator\Services\BulkGenerationService;

add_action( 'wp_ajax_load_posts_by_ajax_category', 'load_posts_by_ajax_category_callback' );

/**
 * Bulk generation AJAX handler for a product category.
 *
 * @return void
 */
function load_posts_by_ajax_category_callback() {
	if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Forbidden' ), 403 );
	}

	check_ajax_referer( 'load_more_posts_category', 'security' );

	$auto_number  = get_option( 'skuautoffxf_auto_number' );
	$auto_id      = (string) get_option( 'skuautoffxf_auto_ID' );
	$auto_variant = (string) get_option( 'skuautoffxf_auto_variant' );

	$paged      = empty( $_GET['paged'] )      ? 1  : (int) $_GET['paged'];
	$batch_size = empty( $_GET['batch_size'] ) ? 1  : min( 10, max( 1, (int) $_GET['batch_size'] ) );
	$number_dop = isset( $_GET['number_dop'] ) ? sanitize_text_field( wp_unslash( $_GET['number_dop'] ) ) : '';
	$checked    = isset( $_GET['checked'] )    ? sanitize_text_field( wp_unslash( $_GET['checked'] ) )    : '';
	$select_cat = isset( $_GET['select_cat'] ) ? sanitize_text_field( wp_unslash( $_GET['select_cat'] ) ) : '';

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $batch_size,
			'paged'          => $paged,
			'product_cat'    => $select_cat,
			'no_found_rows'  => false,
		)
	);

	if ( ! $query->have_posts() ) {
		wp_send_json_success(
			array(
				'html'      => '',
				'processed' => 0,
				'has_more'  => false,
			)
		);
	}

	$html      = '';
	$processed = 0;

	while ( $query->have_posts() ) {
		$query->the_post();

		global $product;

		$product_id  = get_the_ID();
		$status_icon = BulkGenerationService::get_status_icon_html( $product, $checked, $auto_variant, 'easy-woocommerce-auto-sku-generator' );
		$sku_output  = BulkGenerationService::get_sku_output( $product_id, $checked, $auto_number, $auto_id, $number_dop, true, 'easy-woocommerce-auto-sku-generator' );

		if ( $product->is_type( 'variable' ) && 'no' === $auto_variant ) {
			$parent_sku = (string) get_post_meta( $product_id, '_sku', true );
			ffxf_generate_variation_skus( $product, $parent_sku );
		}

		$html .= '<div class="ffxf-product-row">'
			. '<div class="ffxf-product-row__title">'
				. '<p class="title_product">' . wp_kses_post( $status_icon ) . esc_html( get_the_title() ) . '</p>'
			. '</div>'
			. '<div class="ffxf-product-row__sku">'
				. '<span class="ffxf-sku-label">' . esc_html__( 'New SKU:', 'easy-woocommerce-auto-sku-generator' ) . ' </span>'
				. '<span class="slr">' . esc_html( $sku_output ) . '</span>'
			. '</div>'
		. '</div>';

		$processed++;
	}

	wp_reset_postdata();

	$has_more = ( $query->max_num_pages > $paged );

	wp_send_json_success(
		array(
			'html'      => $html,
			'processed' => $processed,
			'has_more'  => $has_more,
		)
	);
}
