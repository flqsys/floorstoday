<?php
/**
 * Plugin Name: Easy Auto SKU Generator for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/easy-woocommerce-auto-sku-generator/
 * Text Domain: easy-woocommerce-auto-sku-generator
 * Domain Path: /languages
 * Description: Automatically assign a unique SKU for all variations of your product. Just activate the plugin
 * Version: 1.3.4
 * Author: Dan Zakirov
 * Author URI: https://profiles.wordpress.org/alexodiy/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 10.8.1
 *
 *     Copyright Dan Zakirov
 *
 *     This file is part of Easy Auto SKU Generator for WooCommerce,
 *     a plugin for WordPress.
 *
 *     Easy Auto SKU Generator for WooCommerce is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     Easy Auto SKU Generator for WooCommerce is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package EasyAutoSkuGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'skuautoffxf_wpdocs_load_textdomain' );

/**
 * Load plugin textdomain.
 *
 * @return void
 */
function skuautoffxf_wpdocs_load_textdomain() {
	load_plugin_textdomain(
		'easy-woocommerce-auto-sku-generator',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}

register_activation_hook( __FILE__, 'ffxf_sku_plugin_activate' );

/**
 * Set install date on plugin activation (2 days ahead for the rating reminder).
 *
 * @return void
 */
function ffxf_sku_plugin_activate() {
	update_option( 'glideffxf_data_install', wp_date( 'Y-m-d', strtotime( '+2 days' ) ) );
}

/**
 * Register the admin-notice dismissal script.
 *
 * @return void
 */
function ffxf_registering_notice_script() {
	wp_register_script(
		'ffxf-rate-sku',
		plugins_url( '/assets/js/ffxf_rate_sku.js', __FILE__ ),
		array( 'jquery' ),
		'1.1.9',
		true
	);
	wp_localize_script(
		'ffxf-rate-sku',
		'ffxf_sp',
		array(
			'ffxf_sp' => __( 'Thank you very much!<br>Remember to make updates when it is available.', 'easy-woocommerce-auto-sku-generator' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'ffxf_registering_notice_script' );

// Save SKU from slug when slug-mode is active.
if ( 'ffxf_slug' === get_option( 'skuautoffxf_letters_and_numbers' ) ) {
	add_action( 'woocommerce_update_product', 'ffxf_save_post_product', 10, 1 );
}

/**
 * Save product slug as SKU when SKU is empty (slug mode only).
 *
 * @param int $product_id Product ID.
 * @return void
 */
function ffxf_save_post_product( $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return;
	}

	if ( '' === (string) get_post_meta( $product->get_id(), '_sku', true ) ) {
		update_post_meta( $product->get_id(), '_sku', $product->get_slug() );
	}
}

/**
 * Enqueue scripts and styles for the product edit screen.
 *
 * @return void
 */
function ffxf_registering_script() {
	$screen = get_current_screen();

	if ( ! $screen || ( 'edit' !== $screen->parent_base && 'product' !== $screen->id ) ) {
		return;
	}

	$site_url = home_url();

	$skuautoffxf_auto_number         = get_option( 'skuautoffxf_auto_number' );
	$skuautoffxf_auto_prefix         = get_option( 'skuautoffxf_auto_prefix' );
	$skuautoffxf_auto_id             = get_option( 'skuautoffxf_auto_ID' );
	$skuautoffxf_previous            = get_option( 'skuautoffxf_previous' );
	$skuautoffxf_letters_and_numbers = get_option( 'skuautoffxf_letters_and_numbers' );
	$skuautoffxf_suffix              = get_option( 'skuautoffxf_suffix' );
	$skuautoffxf_number_dop          = get_option( 'skuautoffxf_number_dop' );
	$skuautoffxf_variation_separator = get_option( 'skuautoffxf_variation_separator' );

	wp_enqueue_style( 'ffxf_autosku', plugins_url( '/assets/css/ffxf_autosku.css', __FILE__ ), array(), '1.3.4' );
	wp_enqueue_style( 'ffxf_tooltip', plugins_url( '/assets/css/ffxf_tooltip.css', __FILE__ ), array(), '1.1.9' );

	if ( 'ffxf_slug' === $skuautoffxf_letters_and_numbers ) {
		ffxf_enqueue_slug_script( $site_url );
		return;
	}

	ffxf_enqueue_auto_sku_script( $site_url, $skuautoffxf_auto_number, $skuautoffxf_auto_prefix, $skuautoffxf_auto_id, $skuautoffxf_previous, $skuautoffxf_letters_and_numbers, $skuautoffxf_suffix, $skuautoffxf_number_dop, $skuautoffxf_variation_separator );
}
add_action( 'admin_enqueue_scripts', 'ffxf_registering_script' );

/**
 * Enqueue the slug-based SKU script.
 *
 * @param string $site_url Site home URL.
 * @return void
 */
function ffxf_enqueue_slug_script( $site_url ) {
	$slug = (string) get_post_field( 'post_name', get_post() );

	wp_enqueue_script(
		'ffxf_slug_script',
		plugins_url( '/assets/js/ffxf_slug_script.js', __FILE__ ),
		array( 'jquery' ),
		'1.3.4',
		true
	);

	wp_localize_script(
		'ffxf_slug_script',
		'ffxf_slug',
		array(
			'slug_product'                       => $slug,
			'skuautoffxf_text_description'       => __( 'This feature is intented to generate another SKU formats, but you are using generation by product slug. If you want to use this feature, please change the slug and save product, then use this button. Also, you can use this feature if you rewrite old saved SKU.', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_bottom'                => __( 'You can always leave your suggestion for improvement in the user support forum. By clicking on the icon, you will be taken to the user support forum. Leave your suggestions, questions.', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_left'                  => __( 'You can always take part in finalizing this plugin.', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_right'                 => __( 'You have any suggestions for improving the plugin?', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip'                       => __( 'Settings SKU', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_trigger_script'        => __( 'Re-Create SKU online', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_trigger_script_thanks' => __( 'Thank you!', 'easy-woocommerce-auto-sku-generator' ),
			'skuautoffxf_site_url'               => $site_url,
		)
	);
}

/**
 * Enqueue the auto-SKU (number/letter/mixed) script.
 *
 * @param string $site_url                       Site home URL.
 * @param mixed  $skuautoffxf_auto_number        Option: character count.
 * @param mixed  $skuautoffxf_auto_prefix        Option: SKU prefix.
 * @param mixed  $skuautoffxf_auto_id            Option: append product ID.
 * @param mixed  $skuautoffxf_previous           Option: take previous product.
 * @param mixed  $skuautoffxf_letters_and_numbers Option: SKU format.
 * @param mixed  $skuautoffxf_suffix             Option: SKU suffix.
 * @param mixed  $skuautoffxf_number_dop         Option: additional number.
 * @param mixed  $skuautoffxf_variation_separator Option: variation separator.
 * @return void
 */
function ffxf_enqueue_auto_sku_script(
	$site_url,
	$skuautoffxf_auto_number,
	$skuautoffxf_auto_prefix,
	$skuautoffxf_auto_id,
	$skuautoffxf_previous,
	$skuautoffxf_letters_and_numbers,
	$skuautoffxf_suffix,
	$skuautoffxf_number_dop,
	$skuautoffxf_variation_separator
) {
	// Resolve character count.
	$auto_number = (string) $skuautoffxf_auto_number;
	if ( '0' === $auto_number ) {
		$auto_number = '';
	} elseif ( '' === $auto_number ) {
		$auto_number = '5';
	}

	// Resolve character set.
	$format = (string) $skuautoffxf_letters_and_numbers;
	if ( 'ffxf_letters' === $format ) {
		$ffxf_format_sku = 'QWERTYUIOPASDFGHJKLZXCVBNM';
	} elseif ( 'ffxf_landnum' === $format ) {
		$ffxf_format_sku = '123456789QWERTYUIOPASDFGHJKLZXCVBNM123456789';
	} else {
		$ffxf_format_sku = '123456789';
	}

	// Resolve product ID suffix.
	$skuautoffxf_id = ( 'yes' === (string) $skuautoffxf_auto_id ) ? (string) get_the_id() : '';

	// Resolve "take previous product" data.
	$str_prev_ID_done = '';
	$prev_ID_draft    = '';
	$prev_ID          = '';

	if ( 'yes' === (string) $skuautoffxf_previous ) {
		$result = ffxf_resolve_previous_product_sku();

		$str_prev_ID_done = $result['sku_next'];
		$prev_ID_draft    = $result['sku_draft'];
		$prev_ID          = $result['sku_prev'];

		if ( $result['show_notice'] ) {
			add_action( 'admin_notices', 'ffxf_plugin_notice_this_product_cannot_be_generated' );
		}
	}

	wp_enqueue_script(
		'ffxf_auto_sku',
		plugins_url( '/assets/js/ffxf_auto_sku.js', __FILE__ ),
		array( 'jquery' ),
		'1.3.4',
		true
	);

	wp_localize_script(
		'ffxf_auto_sku',
		'ffxf_sku',
		array(
			'ffxf_format_sku'                    => $ffxf_format_sku,
			'ffxf_id'                            => $skuautoffxf_id,
			'ffxf_prev_ID'                       => $str_prev_ID_done,
			'skuautoffxf_auto_prefix'            => (string) $skuautoffxf_auto_prefix,
			'skuautoffxf_auto_prefix_text'       => __( 'Attention! You use the "Take previous product" function for your SKU. If you want to rewrite this SKU manually, first save the product, after which you can rewrite SKU manually. Remember, this function generates an article based on the previous published product and its SKU.<br><span style="font-weight:700;color:red;padding:0">Important! Do not use alphabetic values ​​in SKU when option "Take previous product" is enabled!</span> ', 'easy-woocommerce-auto-sku-generator' ),
			'skuautoffxf_id'                     => $skuautoffxf_id,
			'skuautoffxf_auto_number'            => $auto_number,
			'data_tooltip_bottom'                => __( 'You can always leave your suggestion for improvement in the user support forum. By clicking on the icon, you will be taken to the user support forum. Leave your suggestions, questions.', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_left'                  => __( 'You can always take part in finalizing this plugin.', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_right'                 => __( 'You have any suggestions for improving the plugin?', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip'                       => __( 'Settings SKU', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_trigger_script'        => __( 'Re-Create SKU online', 'easy-woocommerce-auto-sku-generator' ),
			'data_tooltip_trigger_script_thanks' => __( 'Thank you!', 'easy-woocommerce-auto-sku-generator' ),
			'skuautoffxf_site_url'               => $site_url,
			'skuautoffxf_prev_ID_old'            => $prev_ID,
			'skuautoffxf_prev_ID_old_text'       => __( 'Previous published product SKU - ', 'easy-woocommerce-auto-sku-generator' ),
			'skuautoffxf_prev_ID_draft'          => $prev_ID_draft,
			'skuautoffxf_prev_ID_draft_text'     => __( 'Previous product SKU in draft - ', 'easy-woocommerce-auto-sku-generator' ),
			'skuautoffxf_suffix'                 => (string) $skuautoffxf_suffix,
			'skuautoffxf_number_dop'             => (string) $skuautoffxf_number_dop,
			'skuautoffxf_variation_separator'    => (string) $skuautoffxf_variation_separator,
		)
	);
}

/**
 * Resolve SKU data for "take previous product" mode.
 *
 * Returns an array with keys:
 *   sku_next     — incremented SKU to use for current product
 *   sku_prev     — raw SKU of last published product
 *   sku_draft    — raw SKU of last draft product
 *   show_notice  — true when previous SKU is not numeric
 *
 * @return array{sku_next: string, sku_prev: string, sku_draft: string, show_notice: bool}
 */
function ffxf_resolve_previous_product_sku() {
	$result = array(
		'sku_next'    => '',
		'sku_prev'    => '',
		'sku_draft'   => '',
		'show_notice' => false,
	);

	// Draft SKU.
	$draft_query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'draft',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		)
	);

	if ( $draft_query->post_count > 0 ) {
		$draft_sku = get_post_meta( $draft_query->post->ID, '_sku', true );
		$result['sku_draft'] = $draft_sku !== false ? (string) $draft_sku : '';
	}

	wp_reset_postdata();

	// Previous published product SKU.
	$prev_post = get_previous_post();
	if ( ! $prev_post || ! isset( $prev_post->ID ) ) {
		return $result;
	}

	$prev_sku = (string) get_post_meta( $prev_post->ID, '_sku', true );
	$result['sku_prev'] = $prev_sku;

	if ( is_numeric( $prev_sku ) ) {
		$length          = strlen( $prev_sku );
		$result['sku_next'] = str_pad( (string) ( (int) $prev_sku + 1 ), $length, '0', STR_PAD_LEFT );
	} else {
		$result['show_notice'] = true;
	}

	return $result;
}

/**
 * Admin notice: previous product SKU is not numeric, "take previous" mode cannot generate.
 *
 * @return void
 */
function ffxf_plugin_notice_this_product_cannot_be_generated() {
	?>
	<div class="notice notice-warning is-dismissible">
		<h3><?php esc_html_e( 'Easy Auto SKU Generator for WooCommerce', 'easy-woocommerce-auto-sku-generator' ); ?></h3>
		<p>
			<?php
			echo wp_kses(
				__( 'SKU on this product cannot be generated. Previous SKU publication is not a numeric value. We remind you that you use the <strong>"Take the previous product"</strong> function, and it only works with digital values. You must correct the SKU of the previous product published one with digital values in order for the Take Previous Product feature to work. Also, you can simply manually change the SKU of the current product, but before that, just save this product.', 'easy-woocommerce-auto-sku-generator' ),
				array( 'strong' => array() )
			);
			?>
		</p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'easy-woocommerce-auto-sku-generator' ); ?></span>
		</button>
	</div>
	<?php
}

add_action( 'woocommerce_admin_process_product_object', 'ffxf_wc_auto_generate_variations_skus', 10, 1 );

/**
 * Generate variation SKUs when a variable product is saved.
 *
 * @param WC_Product $product Product object.
 * @return void
 */
function ffxf_wc_auto_generate_variations_skus( $product ) {
	$skuautoffxf_auto_variant = get_option( 'skuautoffxf_auto_variant' );

	if ( ! $product || ! $product->is_type( 'variable' ) || 'yes' === $skuautoffxf_auto_variant ) {
		return;
	}

	$parent_sku = $product->get_sku();
	ffxf_generate_variation_skus_on_product_save( $product, $parent_sku );
}

add_filter( 'woocommerce_get_sections_products', 'skuautoffxf_add_section' );

/**
 * Register SKU Settings section in WooCommerce Products settings.
 *
 * @param array $sections Existing WooCommerce sections.
 * @return array
 */
function skuautoffxf_add_section( $sections ) {
	$sections['skuautoffxf'] = __( 'SKU Settings', 'easy-woocommerce-auto-sku-generator' );
	return $sections;
}

/**
 * Enqueue styles and register scripts for the plugin settings page.
 *
 * @return void
 */
function ffxf_registering_setting_script() {
	$section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

	if ( 'skuautoffxf' !== $section ) {
		return;
	}

	wp_enqueue_style( 'ffxf_settings', plugins_url( '/assets/css/ffxf_settings.css', __FILE__ ), array(), '1.3.4' );
	wp_enqueue_style( 'ffxf_tooltip', plugins_url( '/assets/css/ffxf_tooltip.css', __FILE__ ), array(), '1.1.9' );

	wp_register_script(
		'ffxf_settings_script',
		plugins_url( '/assets/js/ffxf_settings_script.js', __FILE__ ),
		array( 'jquery' ),
		'1.3.4',
		true
	);

	// Legacy locale object — kept for backward compatibility with any third-party code.
	wp_localize_script(
		'ffxf_settings_script',
		'ffxf_settings_locale',
		array(
			'ffxf_message'         => __( 'If you use current SKU format, then Characters, Prefix SKU, and Add Product ID settings will not be respected. SKU will be generated basing on your product slug.', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_message_preiv'   => __( 'This function is made especially for online store owners who want their product SKU to be formed on the basis of the previous product. The function works only with numbers, its detailed description can be found in <br><a href="https://wordpress.org/support/topic/does-it-do-this-2/" target="_blank">this topic forum</a>.', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_text_category_1' => __( 'In category', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_text_category_2' => __( 'found', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_text_category_3' => __( 'products', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_text_category_4' => __( 'In your store', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_text_category_5' => __( 'products.', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_text_category_6' => __( 'You have not selected a category!', 'easy-woocommerce-auto-sku-generator' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'ffxf_registering_setting_script' );

/**
 * Add Settings link to plugins list table.
 *
 * @param array  $links Plugin action links.
 * @param string $file  Plugin basename.
 * @return array
 */
function skuautoffxf_wpc_add_settings_link( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=products&section=skuautoffxf' ) ) . '">'
			. esc_html__( 'Settings', 'easy-woocommerce-auto-sku-generator' )
			. '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'skuautoffxf_wpc_add_settings_link', 10, 2 );

// Service classes.
require_once plugin_dir_path( __FILE__ ) . 'src/Services/SkuGenerationService.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Services/VariationSkuService.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Services/BulkGenerationService.php';

// Legacy function bridges (keep public function names for backward compat).
require_once plugin_dir_path( __FILE__ ) . 'inc/functions-plugin.php';

// Plugin settings fields.
require_once plugin_dir_path( __FILE__ ) . 'inc/skuautoffxf_all_settings.php';

// Settings page layout, modals and bulk generators.
require_once plugin_dir_path( __FILE__ ) . 'inc/skuautoffxf_setting_block_and_generator.php';

// Rating / feedback notice.
require_once plugin_dir_path( __FILE__ ) . 'inc/skuautoffxf_rate.php';

// Allow identical SKUs when option is enabled.
if ( 'yes' === (string) get_option( 'skuautoffxf_duplicate_sku' ) ) {
	add_filter( 'wc_product_has_unique_sku', '__return_false', PHP_INT_MAX );
}

// Declare HPOS compatibility.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
