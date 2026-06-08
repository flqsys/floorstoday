<?php
/**
 * Add settings to the specific section we created before.
 *
 * @package EasyAutoSkuGenerator
 */

add_filter( 'woocommerce_get_settings_products', 'skuautoffxf_all_settings', 10, 2 );

/**
 * Register all plugin settings for the SKU section.
 *
 * @param array  $settings        Existing settings.
 * @param string $current_section Current WooCommerce section slug.
 * @return array
 */
function skuautoffxf_all_settings( $settings, $current_section ) {
	wp_enqueue_script( 'ffxf_settings_script' );

	if ( 'skuautoffxf' !== $current_section ) {
		return $settings;
	}

	$ffxf_settings_sku = array();

	$ffxf_settings_sku[] = array(
		'name' => __( 'General plugin settings', 'easy-woocommerce-auto-sku-generator' ),
		'type' => 'title',
		'desc' => __( 'On this page you can configure sku', 'easy-woocommerce-auto-sku-generator' ),
		'id'   => 'skuautoffxfid',
	);

	$ffxf_settings_sku[] = array(
		'title'             => __( 'Characters', 'easy-woocommerce-auto-sku-generator' ),
		'desc_tip'          => __( 'SKU prefix and product ID are not counted', 'easy-woocommerce-auto-sku-generator' ),
		'id'                => 'skuautoffxf_auto_number',
		'type'              => 'number',
		'custom_attributes' => array(
			'min'  => 0,
			'step' => 1,
		),
		'default' => '5',
		'class'   => 'manage_stock_field',
		'css'     => 'display:block; width:50px;',
		'desc'    => __( 'Specify the number of characters in SKU', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name'        => __( 'Prefix SKU', 'easy-woocommerce-auto-sku-generator' ),
		'type'        => 'text',
		'default'     => '',
		'placeholder' => 'For example BN_',
		'desc_tip'    => __( 'Characters in the prefix are not assigned to the total number of characters (field above)', 'easy-woocommerce-auto-sku-generator' ),
		'id'          => 'skuautoffxf_auto_prefix',
		'css'         => 'width:100%;display:block',
		'desc'        => __( 'Enter any prefix that will be displayed at the beginning of the SKU. <br>For example <span class="skuautoffxf_separator">BN_</span>893267', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name'    => __( 'Select SKU format', 'easy-woocommerce-auto-sku-generator' ),
		'id'      => 'skuautoffxf_letters_and_numbers',
		'type'    => 'radio',
		'default' => 'ffxf_numbers',
		'options' => array(
			'ffxf_numbers' => __( 'Only numbers, for example - 893267', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_letters' => __( 'Only letters, for example - KSZHGD', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_landnum' => __( 'Letters and numbers, for example - 7SZ4G2', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_slug'    => __( 'Use product slug, for example - your-product', 'easy-woocommerce-auto-sku-generator' ),
		),
	);

	$ffxf_settings_sku[] = array(
		'name' => __( 'Add product ID', 'easy-woocommerce-auto-sku-generator' ),
		'type' => 'checkbox',
		'id'   => 'skuautoffxf_auto_ID',
		'css'  => 'min-width:300px;display:block',
		'desc' => __( 'If checked, product ID will be added to SKU', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name' => __( 'Take previous product', 'easy-woocommerce-auto-sku-generator' ),
		'type' => 'checkbox',
		'id'   => 'skuautoffxf_previous',
		'css'  => 'min-width:300px;display:block',
		'desc' => __( 'Take into account the previous product', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name' => __( 'Duplicate SKUs', 'easy-woocommerce-auto-sku-generator' ),
		'type' => 'checkbox',
		'id'   => 'skuautoffxf_duplicate_sku',
		'css'  => 'min-width:300px;display:block',
		'desc' => __( 'Allow identical SKUs. If enabled, some SKUs can be identical', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name'     => __( 'SKU suffix', 'easy-woocommerce-auto-sku-generator' ),
		'type'     => 'text',
		'id'       => 'skuautoffxf_suffix',
		'css'      => 'min-width:300px;display:block',
		'placeholder' => 'For example "_SUF_"',
		'desc_tip' => __( 'The suffix is set at the end of the SKU and can have different characters. We recommend using the suffix in combination with the "Additional number" option e.g. suffix "_" and additional number "001" then your SKU will have this format BN_893267<em>_001</em>.', 'easy-woocommerce-auto-sku-generator' ),
		'desc'     => __( 'Enter any suffix that will appear at the end of the SKU. <br>For example BN_893267<span class="skuautoffxf_separator">_SUF_</span>', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name'     => __( 'Additional number', 'easy-woocommerce-auto-sku-generator' ),
		'type'     => 'number',
		'id'       => 'skuautoffxf_number_dop',
		'css'      => 'min-width:300px;display:block',
		'placeholder' => 'For example "001"',
		'desc_tip' => __( 'For example, you can set this field to 001 and then +1 will be added at the end of the SKU when the SKU is generated. In this way you can generate SKUs in order 001, 002, 003. We recommend using the suffix in combination with the "SKU suffix" option', 'easy-woocommerce-auto-sku-generator' ),
		'desc'     => __( 'This number is applied at the end of the SKU for the mass generator and adds +1 at each step.<br>For example BN_893267_SUF_<span class="skuautoffxf_separator">001</span><br><span class="skuautoffxf_warning">Incrementing by +1 only applies during bulk generation, while when creating a new product (during product editing), this field will simply append to the end of the SKU.</span>', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name'     => __( 'Format "Additional number"', 'easy-woocommerce-auto-sku-generator' ),
		'id'       => 'skuautoffxf_format_an',
		'type'     => 'select',
		'default'  => 'ffxf_format_an',
		'options'  => array(
			'ffxf_format_an'    => __( 'For example: 008 → 009 → 0010 → 0011', 'easy-woocommerce-auto-sku-generator' ),
			'ffxf_format_an_up' => __( 'For example: 008 → 009 → 010 → 011', 'easy-woocommerce-auto-sku-generator' ),
		),
		'desc_tip' => __( 'This option is experimental, and specifying "0" at the beginning may lead to incorrect results. This functionality will be improved over time', 'easy-woocommerce-auto-sku-generator' ),
		'desc'     => __( 'In the previous field, you selected a value that starts with "0" so you can choose the generation format<br><span class="skuautoffxf_warning">Applies only during bulk generation.</span>', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name' => __( 'Enable variant settings', 'easy-woocommerce-auto-sku-generator' ),
		'type' => 'checkbox',
		'id'   => 'skuautoffxf_variation_settings',
		'css'  => 'min-width:300px;display:block',
		'desc' => __( 'If enabled, you can fine-tune the generation of variant SKU more precisely', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name'        => __( 'Variation Separator', 'easy-woocommerce-auto-sku-generator' ),
		'type'        => 'text',
		'default'     => '',
		'placeholder' => 'For example "-"',
		'id'          => 'skuautoffxf_variation_separator',
		'css'         => 'width:100%;display:block',
		'desc_tip'    => __( 'This setting is responsible for the separator between the main SKU and the variation number.', 'easy-woocommerce-auto-sku-generator' ),
		'desc'        => __( 'You can use the characters "/", "\", "|", "-", "--", ".",  "&", "#", "$", "@" or another prefix "_var_". <br>For example BN_893267<span class="skuautoffxf_separator">--</span>01, BN_893267<span class="skuautoffxf_separator">--</span>02<br>Another example BN_893267<span class="skuautoffxf_separator">_var_</span>01, BN_893267<span class="skuautoffxf_separator">_var_</span>02', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'name' => __( 'Variable product', 'easy-woocommerce-auto-sku-generator' ),
		'type' => 'checkbox',
		'id'   => 'skuautoffxf_auto_variant',
		'css'  => 'min-width:300px;display:block',
		'desc' => __( 'Turn off generation of variable product', 'easy-woocommerce-auto-sku-generator' ),
	);

	$ffxf_settings_sku[] = array(
		'type' => 'sectionend',
		'id'   => 'skuautoffxf',
	);

	return $ffxf_settings_sku;
}

add_action( 'admin_enqueue_scripts', 'skuautoffxf_enqueue_bulk_generator_script' );

/**
 * Register and localise the bulk generator script for the settings page.
 *
 * @return void
 */
function skuautoffxf_enqueue_bulk_generator_script() {
	$section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

	if ( 'skuautoffxf' !== $section ) {
		return;
	}

	wp_register_script(
		'sku-bulk-generator',
		plugins_url( '/assets/js/sku-bulk-generator.js', dirname( __FILE__ ) ),
		array( 'jquery' ),
		'1.3.1',
		true
	);

	wp_localize_script(
		'sku-bulk-generator',
		'skuBulkData',
		array(
			'numberDop'         => (string) get_option( 'skuautoffxf_number_dop' ),
			'totalProducts'     => (int) wp_count_posts( 'product' )->publish,
			'formatOption'      => (string) get_option( 'skuautoffxf_format_an' ),
			'nonce'             => wp_create_nonce( 'load_more_posts' ),
			'nonceCat'          => wp_create_nonce( 'load_more_posts_category' ),
			'batchHintNonce'    => wp_create_nonce( 'skuautoffxf_batch_hint' ),
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'textProductsLeft'  => __( 'Products left:', 'easy-woocommerce-auto-sku-generator' ),
			'textDone'          => __( 'Product processing completed!', 'easy-woocommerce-auto-sku-generator' ),
			'textProcessing'    => __( 'At the moment, the process of generating all articles is in progress. The process will take some time, please wait until the end!', 'easy-woocommerce-auto-sku-generator' ),
			'textComplete'      => __( 'Thanks for waiting! If the process did not work correctly, please refer to the user support forum.', 'easy-woocommerce-auto-sku-generator' ),
			'textCategoryDone'  => __( 'Category processing completed!', 'easy-woocommerce-auto-sku-generator' ),
			'textInCategory'    => __( 'In category', 'easy-woocommerce-auto-sku-generator' ),
			'textFound'         => __( 'found', 'easy-woocommerce-auto-sku-generator' ),
			'textProducts'      => __( 'products', 'easy-woocommerce-auto-sku-generator' ),
			'textInStore'       => __( 'In your store', 'easy-woocommerce-auto-sku-generator' ),
			'textProductsTotal' => __( 'products.', 'easy-woocommerce-auto-sku-generator' ),
			'textNoCategory'    => __( 'You have not selected a category!', 'easy-woocommerce-auto-sku-generator' ),
			'msgSlug'           => __( 'If you use current SKU format, then Characters, Prefix SKU, and Add Product ID settings will not be respected. SKU will be generated basing on your product slug.', 'easy-woocommerce-auto-sku-generator' ),
			'msgPrevious'       => __( 'This function is made especially for online store owners who want their product SKU to be formed on the basis of the previous product. The function works only with numbers, its detailed description can be found in <br><a href="https://wordpress.org/support/topic/does-it-do-this-2/" target="_blank">this topic forum</a>.<br><br>At the moment, you see other options with a darkened color - they do not work when the "Take previous product" option is enabled. All characters, and with this setting only numbers, will come from the last published product.', 'easy-woocommerce-auto-sku-generator' ),
			'textError'         => __( 'A server error occurred. Please try again or check your error log.', 'easy-woocommerce-auto-sku-generator' ),
		)
	);

	wp_enqueue_script( 'sku-bulk-generator' );
}
