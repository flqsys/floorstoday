<?php
/**
 * Variation SKU generation service.
 *
 * @package EasyAutoSkuGenerator
 */

namespace EasyAutoSkuGenerator\Services;

use WC_Data_Exception;
use WC_Product;

class VariationSkuService {
	/**
	 * Generate variation SKUs for bulk flow.
	 *
	 * @param WC_Product $product Product.
	 * @param string     $parent_sku Parent SKU.
	 * @return void
	 */
	public static function generate_for_bulk( WC_Product $product, string $parent_sku ): void {
		$children_ids = $product->get_children();
		$separator    = SkuGenerationService::get_variation_separator();
		$count        = 0;

		foreach ( $children_ids as $child_id ) {
			$count++;
			$variation = wc_get_product( $child_id );
			if ( ! $variation ) {
				continue;
			}

			$prefix = count( $children_ids ) < 100 ? sprintf( '%02d', $count ) : sprintf( '%03d', $count );

			try {
				$variation->set_sku( $parent_sku . $separator . $prefix );
				$variation->save();
			} catch ( WC_Data_Exception $exception ) {
				// SKU uniqueness conflict — skip this variation silently.
				continue;
			}
		}
	}

	/**
	 * Generate variation SKUs when product is saved.
	 *
	 * @param WC_Product $product Product.
	 * @param string     $parent_sku Parent SKU.
	 * @return void
	 */
	public static function generate_on_product_save( WC_Product $product, string $parent_sku ): void {
		if ( '' === $parent_sku ) {
			return;
		}

		$children_ids = $product->get_children();
		$separator    = SkuGenerationService::get_variation_separator();
		$count        = 0;

		foreach ( $children_ids as $child_id ) {
			$count++;
			$variation = wc_get_product( $child_id );
			if ( ! $variation ) {
				continue;
			}

			$original_sku = $variation->get_sku();
			$prefix       = count( $children_ids ) < 100 ? sprintf( '%02d', $count ) : sprintf( '%03d', $count );
			$new_sku      = $parent_sku . $separator . $prefix;

			if ( '' !== $original_sku && $original_sku !== $parent_sku ) {
				continue;
			}

			try {
				$variation->set_sku( $new_sku );
				$variation->save();
			} catch ( WC_Data_Exception $exception ) {
				// SKU uniqueness conflict — skip this variation silently.
				continue;
			}
		}
	}
}
