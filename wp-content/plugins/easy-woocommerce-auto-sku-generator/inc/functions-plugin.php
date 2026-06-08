<?php
/**
 * Legacy function bridges.
 *
 * @package EasyAutoSkuGenerator
 */

use EasyAutoSkuGenerator\Services\SkuGenerationService;
use EasyAutoSkuGenerator\Services\VariationSkuService;

/**
 * Legacy wrapper: get variation separator value.
 *
 * @return string
 */
function get_variation_separator_value() {
	return SkuGenerationService::get_variation_separator();
}

/**
 * Legacy wrapper: get SKU character set.
 *
 * @return string
 */
function ffxf_get_sku_characters() {
	return SkuGenerationService::get_character_set();
}

/**
 * Legacy wrapper: generate random SKU core.
 *
 * @param int    $length     Number of random chars.
 * @param string $number_dop Additional number value.
 * @return string
 */
function ffxf_generate_random_sku_core( $length, $number_dop = '' ) {
	return SkuGenerationService::build_random_sku_core( (int) $length, (string) $number_dop );
}

/**
 * Legacy wrapper: bulk variation SKU generation.
 *
 * @param WC_Product $product Variable product instance.
 * @param string     $parent_sku Parent SKU.
 * @return void
 */
function ffxf_generate_variation_skus( $product, $parent_sku ) {
	VariationSkuService::generate_for_bulk( $product, (string) $parent_sku );
}

/**
 * Legacy wrapper: variation SKU generation on product save.
 *
 * @param WC_Product $product Variable product instance.
 * @param string     $parent_sku Parent SKU.
 * @return void
 */
function ffxf_generate_variation_skus_on_product_save( $product, $parent_sku ) {
	VariationSkuService::generate_on_product_save( $product, (string) $parent_sku );
}
