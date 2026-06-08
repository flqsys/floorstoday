<?php
/**
 * Settings page sidebar, modals, and bulk generator wrappers.
 *
 * @package EasyAutoSkuGenerator
 */

// ---------------------------------------------------------------------------
// AJAX: return server-side recommendation for batch size
// ---------------------------------------------------------------------------

add_action( 'wp_ajax_skuautoffxf_get_batch_hint', 'skuautoffxf_get_batch_hint_callback' );

/**
 * Return a recommended batch size based on available server memory.
 *
 * Logic (conservative, works on shared hosting):
 *   < 64 MB  → 1   (very low memory)
 *   < 128 MB → 3
 *   < 256 MB → 5
 *   >= 256 MB → 10
 *
 * @return void
 */
function skuautoffxf_get_batch_hint_callback() {
	if ( ! current_user_can( 'manage_woocommerce' ) && ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Forbidden' ), 403 );
	}

	check_ajax_referer( 'skuautoffxf_batch_hint', 'security' );

	$memory_limit_str = ini_get( 'memory_limit' );
	$memory_bytes     = wp_convert_hr_to_bytes( $memory_limit_str );

	if ( $memory_bytes <= 0 ) {
		// Unable to determine — recommend safe default
		wp_send_json_success( array( 'batch' => 3, 'memory' => $memory_limit_str ) );
	}

	if ( $memory_bytes < 64 * MB_IN_BYTES ) {
		$batch = 1;
	} elseif ( $memory_bytes < 128 * MB_IN_BYTES ) {
		$batch = 3;
	} elseif ( $memory_bytes < 256 * MB_IN_BYTES ) {
		$batch = 5;
	} else {
		$batch = 10;
	}

	wp_send_json_success(
		array(
			'batch'  => $batch,
			'memory' => $memory_limit_str,
		)
	);
}

// ---------------------------------------------------------------------------
// Settings page sidebar
// ---------------------------------------------------------------------------

/**
 * Render the sidebar and bulk-generator panel on the SKU settings page.
 *
 * @return void
 */
function skuautoffxf_action_open() {
	$section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

	if ( 'skuautoffxf' !== $section ) {
		return;
	}

	add_thickbox();
	?>

	<h1><?php esc_html_e( 'Easy Auto SKU Generator for WooCommerce', 'easy-woocommerce-auto-sku-generator' ); ?></h1>

	<div class="wrapper_setting_ffxf">
		<div class="block_left">

			<!-- Bulk generation panel -->
			<div id="postbox-container-2" class="postbox-container postbox">
				<div class="meta-box-sortables">
					<div class="postbox__ffxf">
						<div class="inside">
							<span class="dashicons dashicons-admin-generic my_generic_two"></span>
							<h2><span><?php esc_html_e( 'Bulk generation SKU', 'easy-woocommerce-auto-sku-generator' ); ?></span></h2>
							<p><?php esc_html_e( 'Save your settings first, then use one of the generators below.', 'easy-woocommerce-auto-sku-generator' ); ?></p>
							<hr>

							<div class="mass_generate">
								<div>
									<i class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Generates SKU for all published products based on your saved settings. Products that already have a SKU will be skipped unless you check "Re-create existing SKUs".', 'easy-woocommerce-auto-sku-generator' ); ?>"></i>
									<?php esc_html_e( 'Bulk generate SKU for all products', 'easy-woocommerce-auto-sku-generator' ); ?>
								</div>
								<div>
									<a id="generate_mass_sku" class="button button-primary open">
										<?php esc_html_e( 'Generate all SKU', 'easy-woocommerce-auto-sku-generator' ); ?>
									</a>
								</div>
							</div>
							<hr>

							<div class="mass_generate">
								<div>
									<i class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Generate SKU only for products in a specific category. Useful when you need different SKU prefixes per category.', 'easy-woocommerce-auto-sku-generator' ); ?>"></i>
									<?php esc_html_e( 'Bulk generate SKU by Category', 'easy-woocommerce-auto-sku-generator' ); ?>
								</div>
								<div>
									<a id="generate_mass_sku_category" class="button button-primary open_category">
										<?php esc_html_e( 'Generate by Category', 'easy-woocommerce-auto-sku-generator' ); ?>
									</a>
								</div>
							</div>
							<hr>

							<div class="mass_generate mass_generate--disabled">
								<div>
									<i class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Coming in a future version.', 'easy-woocommerce-auto-sku-generator' ); ?>"></i>
									<?php esc_html_e( 'Bulk generate SKU by Attributes', 'easy-woocommerce-auto-sku-generator' ); ?>
								</div>
								<div>
									<button class="button" disabled><?php esc_html_e( 'Generate by Attributes', 'easy-woocommerce-auto-sku-generator' ); ?></button>
								</div>
							</div>
							<hr>

							<div class="mass_generate mass_generate--disabled">
								<div>
									<i class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Coming in a future version.', 'easy-woocommerce-auto-sku-generator' ); ?>"></i>
									<?php esc_html_e( 'Bulk generate SKU by product tags', 'easy-woocommerce-auto-sku-generator' ); ?>
								</div>
								<div>
									<button class="button" disabled><?php esc_html_e( 'Generate by tags', 'easy-woocommerce-auto-sku-generator' ); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Recommended plugins -->
			<div id="postbox-container-4" class="postbox-container postbox">
				<div class="meta-box-sortables">
					<div class="postbox__ffxf">
						<div class="inside">
							<span class="dashicons dashicons-food"></span>
							<h2><span><?php esc_html_e( 'Recommended Plugins', 'easy-woocommerce-auto-sku-generator' ); ?></span></h2>
							<p><?php esc_html_e( 'Plugins that can boost conversions in your store.', 'easy-woocommerce-auto-sku-generator' ); ?></p>
							<hr>
							<div class="recomendated_plugin_block">
								<div><img src="https://ps.w.org/carousel-upsells-and-related-product-for-woocommerce/assets/icon-256x256.png" alt=""></div>
								<div>
									<a href="/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=carousel-upsells-and-related-product-for-woocommerce&amp;TB_iframe=true&amp;width=772&amp;height=468" class="thickbox open-plugin-details-ffxf-modal" aria-label="Carousel Upsells and Related Product for Woocommerce" data-title="Carousel Upsells and Related Product for Woocommerce">
										<h4><?php esc_html_e( 'Carousel Upsells and Related Product', 'easy-woocommerce-auto-sku-generator' ); ?></h4>
									</a>
								</div>
							</div>
							<hr>
							<div class="recomendated_plugin_block">
								<div><img src="https://ps.w.org/art-woocommerce-order-one-click/assets/icon.svg" alt=""></div>
								<div>
									<a href="/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=art-woocommerce-order-one-click&amp;TB_iframe=true&amp;width=772&amp;height=468" class="thickbox open-plugin-details-ffxf-modal" aria-label="Art WooCommerce Order One Click" data-title="Art WooCommerce Order One Click">
										<h4><?php esc_html_e( 'Art WooCommerce Order One Click', 'easy-woocommerce-auto-sku-generator' ); ?></h4>
									</a>
								</div>
							</div>
							<hr>
							<div class="recomendated_plugin_block">
								<div><img src="https://ps.w.org/easy-hide-admin-menu-items/assets/icon-128x128.png" alt=""></div>
								<div>
									<a href="/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=easy-hide-admin-menu-items&amp;TB_iframe=true&amp;width=772&amp;height=468" class="thickbox open-plugin-details-ffxf-modal" aria-label="Easy Hide Admin Menu Items" data-title="Easy Hide Admin Menu Items">
										<h4><?php esc_html_e( 'Easy Hide Admin Menu Items', 'easy-woocommerce-auto-sku-generator' ); ?></h4>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Message from the author -->
			<div id="postbox-container-1" class="postbox-container postbox">
				<div class="meta-box-sortables">
					<div class="postbox__ffxf">
						<div class="inside inside_icons">
							<span class="dashicons dashicons-businessperson"></span>
							<h2><span><?php esc_html_e( 'Message from the author', 'easy-woocommerce-auto-sku-generator' ); ?></span></h2>
							<p><?php esc_html_e( 'Bulk SKU generation and batch processing are now available. Save your settings before running the generator.', 'easy-woocommerce-auto-sku-generator' ); ?></p>
							<p>
								<?php esc_html_e( 'Have suggestions or found a bug?', 'easy-woocommerce-auto-sku-generator' ); ?>
								<a href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Visit the support forum', 'easy-woocommerce-auto-sku-generator' ); ?>
								</a>.
							</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Review block -->
			<div id="postbox-container-3" class="postbox-container postbox">
				<div class="meta-box-sortables">
					<div class="postbox__ffxf">
						<div class="inside">
							<span class="dashicons dashicons-star-filled"></span>
							<h2><span><?php esc_html_e( 'Enjoying the plugin?', 'easy-woocommerce-auto-sku-generator' ); ?></span></h2>
							<p><?php esc_html_e( 'If Easy Auto SKU Generator saves you time, the best way to support it is to leave a review on WordPress.org. It takes just a minute and helps thousands of other store owners discover the plugin.', 'easy-woocommerce-auto-sku-generator' ); ?></p>
							<p><?php esc_html_e( 'Even a few words make a big difference — thank you!', 'easy-woocommerce-auto-sku-generator' ); ?></p>
							<p class="center">
								<a class="ffxf-review-btn" href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/reviews/#new-post" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'Leave a review', 'easy-woocommerce-auto-sku-generator' ); ?>
								</a>
							</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Easy Hide Admin Menu Items promo -->
			<div id="postbox-container-5" class="postbox-container postbox">
				<div class="meta-box-sortables">
					<div class="postbox__ffxf">
						<div class="inside">
							<span class="dashicons dashicons-editor-unlink"></span>
							<h2><span><?php esc_html_e( 'Tired of the multitude of menus from other plugins?', 'easy-woocommerce-auto-sku-generator' ); ?></span></h2>
							<p>
								<?php esc_html_e( 'I am pleased to introduce our new plugin —', 'easy-woocommerce-auto-sku-generator' ); ?>
								<strong><?php esc_html_e( 'Easy Hide Admin Menu Items', 'easy-woocommerce-auto-sku-generator' ); ?></strong><?php esc_html_e( '! Designed to provide maximum convenience when using the WordPress admin panel.', 'easy-woocommerce-auto-sku-generator' ); ?>
							</p>
							<p><?php esc_html_e( 'Easy Hide Admin Menu Items will help you easily hide unnecessary menu items, making the interface cleaner and more user-friendly.', 'easy-woocommerce-auto-sku-generator' ); ?></p>
							<p>
								<a class="air-join" href="https://wordpress.org/plugins/easy-hide-admin-menu-items/" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'View on WordPress Repository', 'easy-woocommerce-auto-sku-generator' ); ?>
								</a>
							</p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div>
			<span class="dashicons dashicons-rest-api my_generic"></span>
	<?php
}
add_action( 'woocommerce_settings_products', 'skuautoffxf_action_open' );

// ---------------------------------------------------------------------------
// Modals (rendered in admin_footer)
// ---------------------------------------------------------------------------

/**
 * Render bulk-generation modal dialogs in WP admin style.
 *
 * @return void
 */
function skuautoffxf_modal_setting() {
	$section = isset( $_GET['section'] ) ? sanitize_text_field( wp_unslash( $_GET['section'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

	if ( 'skuautoffxf' !== $section ) {
		return;
	}

	$total_products   = (int) wp_count_posts( 'product' )->publish;
	$batch_hint_nonce = wp_create_nonce( 'skuautoffxf_batch_hint' );
	?>

	<!-- ===== MODAL: Bulk generate SKU for all products ===== -->
	<div class="ffxf-modal-frame modal_generate" role="dialog" aria-modal="true" aria-labelledby="ffxf-modal-all-title">
		<div class="ffxf-modal">
			<div class="ffxf-modal-inset">

				<!-- WP-style header bar with title + close -->
				<div class="ffxf-modal-header">
					<h2 id="ffxf-modal-all-title">
						<?php esc_html_e( 'Bulk generate SKU for all products', 'easy-woocommerce-auto-sku-generator' ); ?>
					</h2>
					<button type="button" class="ffxf-modal-close close" aria-label="<?php esc_attr_e( 'Close', 'easy-woocommerce-auto-sku-generator' ); ?>">
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
					</button>
				</div>

				<div class="ffxf-modal-body">

					<p id="text_generate_modal" class="ffxf-modal-desc">
						<?php esc_html_e( 'Save your settings first, then click Start. Products are processed in batches — one batch per server request.', 'easy-woocommerce-auto-sku-generator' ); ?>
					</p>

					<!-- Centred column for form controls -->
					<div class="ffxf-modal-inner">

						<!-- Options -->
						<div class="ffxf-modal-options">
							<label class="ffxf-modal-option" id="check_gen" for="check_generate">
								<input id="check_generate" name="check_generate" type="checkbox" class="checkbox">
								<?php esc_html_e( 'Re-create existing SKUs', 'easy-woocommerce-auto-sku-generator' ); ?>
							</label>
						</div>

						<!-- Batch size -->
						<div class="ffxf-batch-row">
							<label class="ffxf-batch-label" for="ffxf_batch_size_all">
								<?php esc_html_e( 'Products per batch:', 'easy-woocommerce-auto-sku-generator' ); ?>
								<i class="woocommerce-help-tip ffxf-batch-tip" data-tip="<?php esc_attr_e( 'How many products are processed per server request. Higher values are faster but require more server memory. Use &ldquo;Recommend&rdquo; to detect the optimal value for your hosting.', 'easy-woocommerce-auto-sku-generator' ); ?>"></i>
								<span class="ffxf-batch-note"><?php esc_html_e( '(1–10, default: 1)', 'easy-woocommerce-auto-sku-generator' ); ?></span>
							</label>
							<div class="ffxf-batch-controls">
								<input id="ffxf_batch_size_all" type="number" min="1" max="10" value="1" class="small-text">
								<button type="button" class="button ffxf-batch-hint-btn"
									data-nonce="<?php echo esc_attr( $batch_hint_nonce ); ?>"
									data-target="ffxf_batch_size_all"
									title="<?php esc_attr_e( 'Detect recommended batch size based on server memory', 'easy-woocommerce-auto-sku-generator' ); ?>">
									<span class="dashicons dashicons-performance" aria-hidden="true"></span>
									<?php esc_html_e( 'Recommend', 'easy-woocommerce-auto-sku-generator' ); ?>
								</button>
								<span class="ffxf-batch-hint-result" id="ffxf-batch-hint-result-all"></span>
							</div>
						</div>

					</div><!-- /.ffxf-modal-inner -->

					<!-- Action button -->
					<div class="ffxf-modal-action">
						<button type="button" class="button button-primary button-large generate_button">
							<?php esc_html_e( 'Start generating', 'easy-woocommerce-auto-sku-generator' ); ?>
						</button>
					</div>

					<!-- Progress block (hidden until generation starts) -->
					<div class="ffxf-progress-wrap" style="display:none">
						<div class="ffxf-progress-pie progress-pie-chart" data-percent="0">
							<div class="ppc-progress">
								<div class="ppc-progress-fill"></div>
							</div>
							<div class="ppc-percents">
								<div class="pcc-percents-wrapper">
									<span>0%</span>
								</div>
							</div>
						</div>
						<p class="ps">
							<?php
							printf(
								/* translators: %d number of products */
								esc_html__( '%d products in your store.', 'easy-woocommerce-auto-sku-generator' ),
								$total_products
							);
							?>
						</p>
					</div>

					<!-- Product log -->
					<div class="ffxf-log-wrap over">
						<div class="my-posts"></div>
					</div>

				</div><!-- /.ffxf-modal-body -->
			</div><!-- /.ffxf-modal-inset -->
		</div><!-- /.ffxf-modal -->
	</div><!-- /.ffxf-modal-frame -->

	<!-- ===== MODAL: Bulk generate SKU by Category ===== -->
	<div class="ffxf-modal-frame modal_generate_category" role="dialog" aria-modal="true" aria-labelledby="ffxf-modal-cat-title">
		<div class="ffxf-modal">
			<div class="ffxf-modal-inset">

				<!-- WP-style header bar with title + close -->
				<div class="ffxf-modal-header">
					<h2 id="ffxf-modal-cat-title">
						<?php esc_html_e( 'Bulk generate SKU by Category', 'easy-woocommerce-auto-sku-generator' ); ?>
					</h2>
					<button type="button" class="ffxf-modal-close close_category" aria-label="<?php esc_attr_e( 'Close', 'easy-woocommerce-auto-sku-generator' ); ?>">
						<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
					</button>
				</div>

				<div class="ffxf-modal-body">

					<p id="text_generate_modal" class="ffxf-modal-desc">
						<?php esc_html_e( 'Select a category, configure options and click Start.', 'easy-woocommerce-auto-sku-generator' ); ?>
					</p>

					<!-- Centred column for form controls -->
					<div class="ffxf-modal-inner">

						<!-- Options -->
						<div class="ffxf-modal-options">
							<div class="ffxf-modal-option">
								<label for="product_cat"><?php esc_html_e( 'Category:', 'easy-woocommerce-auto-sku-generator' ); ?></label>
								<?php echo wc_product_dropdown_categories(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>

							<label class="ffxf-modal-option" id="check_gen" for="check_generate_category">
								<input id="check_generate_category" name="check_generate_category" type="checkbox" class="checkbox">
								<?php esc_html_e( 'Re-create existing SKUs', 'easy-woocommerce-auto-sku-generator' ); ?>
							</label>
						</div>

						<!-- Status line (category count) -->
						<p class="ps ffxf-cat-status"></p>

						<!-- Batch size -->
						<div class="ffxf-batch-row">
							<label class="ffxf-batch-label" for="ffxf_batch_size_cat">
								<?php esc_html_e( 'Products per batch:', 'easy-woocommerce-auto-sku-generator' ); ?>
								<i class="woocommerce-help-tip ffxf-batch-tip" data-tip="<?php esc_attr_e( 'How many products are processed per server request. Higher values are faster but require more server memory. Use &ldquo;Recommend&rdquo; to detect the optimal value for your hosting.', 'easy-woocommerce-auto-sku-generator' ); ?>"></i>
								<span class="ffxf-batch-note"><?php esc_html_e( '(1–10, default: 1)', 'easy-woocommerce-auto-sku-generator' ); ?></span>
							</label>
							<div class="ffxf-batch-controls">
								<input id="ffxf_batch_size_cat" type="number" min="1" max="10" value="1" class="small-text">
								<button type="button" class="button ffxf-batch-hint-btn"
									data-nonce="<?php echo esc_attr( $batch_hint_nonce ); ?>"
									data-target="ffxf_batch_size_cat"
									title="<?php esc_attr_e( 'Detect recommended batch size based on server memory', 'easy-woocommerce-auto-sku-generator' ); ?>">
									<span class="dashicons dashicons-performance" aria-hidden="true"></span>
									<?php esc_html_e( 'Recommend', 'easy-woocommerce-auto-sku-generator' ); ?>
								</button>
								<span class="ffxf-batch-hint-result" id="ffxf-batch-hint-result-cat"></span>
							</div>
						</div>

					</div><!-- /.ffxf-modal-inner -->

					<!-- Action button -->
					<div class="ffxf-modal-action">
						<button type="button" class="button button-primary button-large generate_button_category" disabled>
							<?php esc_html_e( 'Start generating', 'easy-woocommerce-auto-sku-generator' ); ?>
						</button>
					</div>

					<!-- Progress block -->
					<div class="ffxf-progress-wrap" style="display:none">
						<div class="ffxf-progress-pie progress-pie-chart" data-percent="0">
							<div class="ppc-progress">
								<div class="ppc-progress-fill"></div>
							</div>
							<div class="ppc-percents">
								<div class="pcc-percents-wrapper">
									<span>0%</span>
								</div>
							</div>
						</div>
						<p class="ps"></p>
					</div>

					<!-- Product log -->
					<div class="ffxf-log-wrap over">
						<div class="my-posts"></div>
					</div>

				</div><!-- /.ffxf-modal-body -->
			</div><!-- /.ffxf-modal-inset -->
		</div><!-- /.ffxf-modal -->
	</div><!-- /.ffxf-modal-frame -->

	<div class="ffxf-modal-overlay"></div>
	<?php
}
add_action( 'admin_footer', 'skuautoffxf_modal_setting', 999 );

// ---------------------------------------------------------------------------
// Required sub-files
// ---------------------------------------------------------------------------

/** Bulk generation: all products */
require_once plugin_dir_path( __FILE__ ) . 'generate_all.php';

/** Bulk generation: by category */
require_once plugin_dir_path( __FILE__ ) . 'generate_category.php';

add_action( 'admin_init', 'skuautoffxf_register_setting' );

/**
 * Register plugin option group.
 *
 * @return void
 */
function skuautoffxf_register_setting() {
	register_setting( 'my_options_group', 'my_option_name', 'intval' );
}
