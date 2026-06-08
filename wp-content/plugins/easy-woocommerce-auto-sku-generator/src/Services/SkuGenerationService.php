<?php
/**
 * SKU generation helpers.
 *
 * @package EasyAutoSkuGenerator
 */

namespace EasyAutoSkuGenerator\Services;

class SkuGenerationService {
	/**
	 * Get variation separator from options.
	 *
	 * @return string
	 */
	public static function get_variation_separator(): string {
		$variation_settings_enabled = get_option( 'skuautoffxf_variation_settings' );
		$variation_separator        = get_option( 'skuautoffxf_variation_separator' );

		if ( ! empty( $variation_settings_enabled ) && ! empty( $variation_separator ) ) {
			return (string) $variation_separator;
		}

		return '-';
	}

	/**
	 * Get source characters for SKU body.
	 *
	 * @return string
	 */
	public static function get_character_set(): string {
		$sku_format = get_option( 'skuautoffxf_letters_and_numbers' );

		if ( empty( $sku_format ) || 'ffxf_numbers' === $sku_format ) {
			return '123456789';
		}

		if ( 'ffxf_letters' === $sku_format ) {
			return 'QWERTYUIOPASDFGHJKLZXCVBNM';
		}

		if ( 'ffxf_landnum' === $sku_format ) {
			return '123456789QWERTYUIOPASDFGHJKLZXCVBNM123456789';
		}

		return '123456789';
	}

	/**
	 * Build random SKU core.
	 *
	 * @param int    $length     Number of random chars.
	 * @param string $number_dop Additional number.
	 * @return string
	 */
	public static function build_random_sku_core( int $length, string $number_dop = '' ): string {
		$characters = self::get_character_set();
		$characters_length = strlen( $characters );
		$random = '';

		for ( $index = 0; $index < $length; $index++ ) {
			$random .= $characters[ wp_rand( 0, $characters_length - 1 ) ];
		}

		return $random . (string) get_option( 'skuautoffxf_suffix' ) . $number_dop;
	}
}
