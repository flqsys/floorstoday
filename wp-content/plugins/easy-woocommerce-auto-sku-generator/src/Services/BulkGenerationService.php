<?php
/**
 * Bulk SKU generation service.
 *
 * @package EasyAutoSkuGenerator
 */

namespace EasyAutoSkuGenerator\Services;

use WC_Product;

class BulkGenerationService {
	/**
	 * Render status icon for product row.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $checked Recreate flag.
	 * @param string     $auto_variant Variation generation option.
	 * @param string     $text_domain Text domain.
	 * @return string
	 */
	public static function get_status_icon_html( WC_Product $product, string $checked, string $auto_variant, string $text_domain ): string {
		$message_variable = __( 'Variable product. Depending on the settings, SKU variations may be generated.', $text_domain );
		$message_exists   = __( 'SKU already exists and has not been recreated', $text_domain );
		$message_created  = __( 'SKU was not detected but was recreated!', $text_domain );
		$message_rebuild  = __( 'SKU has been recreated', $text_domain );
		$message_regular  = __( 'Regular product', $text_domain );

		if ( $product->is_type( 'variable' ) && 'no' === $auto_variant ) {
			return '<span data-tooltip-right data-tooltip="' . esc_attr( $message_variable ) . '"><i class="dashicons dashicons-tickets-alt"></i></span> ';
		}

		if ( '0' === $checked ) {
			if ( get_post_meta( $product->get_id(), '_sku', true ) ) {
				return '<span data-tooltip-right data-tooltip="' . esc_attr( $message_exists ) . '"><i class="dashicons dashicons-migrate"></i></span>';
			}

			return '<span data-tooltip-right data-tooltip="' . esc_attr( $message_created ) . '"><i style="color: #FF5722;" class="dashicons dashicons-dismiss"></i></span>';
		}

		if ( '1' === $checked ) {
			return '<span data-tooltip-right data-tooltip="' . esc_attr( $message_rebuild ) . '"><i class="dashicons dashicons-admin-appearance"></i></span>  ';
		}

		return '<span data-tooltip-right data-tooltip="' . esc_attr( $message_regular ) . '"><i class="dashicons dashicons-paperclip"></i></span>  ';
	}

	/**
	 * Generate or read SKU for product based on current mode.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $checked Recreate flag.
	 * @param mixed  $auto_number Option skuautoffxf_auto_number.
	 * @param string $auto_id Option skuautoffxf_auto_ID.
	 * @param string $number_dop Additional number from request.
	 * @param bool   $skip_zero_as_empty If true, keep empty length when auto number is zero.
	 * @param string $text_domain Text domain.
	 * @return string
	 */
	public static function get_sku_output( int $product_id, string $checked, $auto_number, string $auto_id, string $number_dop, bool $skip_zero_as_empty, string $text_domain ): string {
		$product_id_suffix = 'yes' === $auto_id ? (string) $product_id : '';

		if ( 'ffxf_slug' === get_option( 'skuautoffxf_letters_and_numbers' ) ) {
			if ( '1' === $checked ) {
				$slug = (string) get_post_field( 'post_name', $product_id );
				update_post_meta( $product_id, '_sku', $slug );
				return $slug;
			}

			if ( '0' === $checked ) {
				$existing = (string) get_post_meta( $product_id, '_sku', true );
				if ( '' !== $existing ) {
					return $existing;
				}

				return __( 'SKU not detected', $text_domain );
			}

			return '';
		}

		if ( '1' === $checked ) {
			$resolved_length = self::resolve_auto_number( $auto_number, true );
			return self::generate_and_save_sku( $product_id, $resolved_length, $product_id_suffix, $number_dop );
		}

		if ( '0' === $checked ) {
			$existing_sku = (string) get_post_meta( $product_id, '_sku', true );
			if ( '' !== $existing_sku ) {
				return $existing_sku;
			}

			$resolved_length = self::resolve_auto_number( $auto_number, $skip_zero_as_empty );
			return self::generate_and_save_sku( $product_id, $resolved_length, $product_id_suffix, $number_dop );
		}

		return '';
	}

	/**
	 * Resolve auto number length according to current mode.
	 *
	 * @param mixed $auto_number Stored option value.
	 * @param bool  $zero_as_empty Keep empty when option equals zero.
	 * @return string
	 */
	private static function resolve_auto_number( $auto_number, bool $zero_as_empty ): string {
		$auto_number = (string) $auto_number;

		if ( '0' === $auto_number ) {
			return $zero_as_empty ? '' : (string) wp_rand( 4, 7 );
		}

		if ( '' === $auto_number ) {
			return (string) wp_rand( 4, 7 );
		}

		return $auto_number;
	}

	/**
	 * Generate SKU and persist it.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $length SKU random length.
	 * @param string $product_id_suffix Product ID suffix.
	 * @param string $number_dop Additional number.
	 * @return string
	 */
	private static function generate_and_save_sku( int $product_id, string $length, string $product_id_suffix, string $number_dop ): string {
		$generated = (string) get_option( 'skuautoffxf_auto_prefix' ) . ffxf_generate_random_sku_core( $length, $number_dop ) . $product_id_suffix;
		update_post_meta( $product_id, '_sku', $generated );

		return $generated;
	}
}
