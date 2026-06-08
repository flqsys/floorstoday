<?php
/**
 * Rating / feedback admin notice and AJAX handlers.
 *
 * @package EasyAutoSkuGenerator
 */

add_action( 'admin_notices', 'ffxf_plugin_notice_rate' );

/**
 * Display the rating notice when the install date has passed.
 *
 * @return void
 */
function ffxf_plugin_notice_rate() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$install_date = get_option( 'glideffxf_data_install' );
	$today        = strtotime( wp_date( 'Y-m-d' ) );

	// Show notice if option is missing or install date has passed.
	if ( false !== $install_date && null !== $install_date && $today < strtotime( $install_date ) ) {
		return;
	}

	wp_enqueue_script( 'ffxf-rate-sku' );
	?>
	<div id="ffxf_rate_sku" class="notice notice-info is-dismissible">
		<p>
			<img
				src="https://ps.w.org/easy-woocommerce-auto-sku-generator/assets/icon-128x128.png"
				alt=""
				style="float:left;width:96px;margin-right:14px;border-radius:4px;"
			>
			<?php esc_html_e( 'Hello!', 'easy-woocommerce-auto-sku-generator' ); ?><br>
			<?php esc_html_e( 'We are very pleased that you are using the Easy Auto SKU Generator for WooCommerce plugin within a few days.', 'easy-woocommerce-auto-sku-generator' ); ?>
			<br>
			<?php esc_html_e( 'Please rate plugin. It will help us a lot.', 'easy-woocommerce-auto-sku-generator' ); ?>
		</p>
		<p>
			<a id="i_have_already_by_ajax_callback" class="button button-secondary" href="#">
				<?php esc_html_e( "Don't show again!", 'easy-woocommerce-auto-sku-generator' ); ?>
				<span class="dashicons dashicons-smiley" style="font-size:16px;position:relative;bottom:-5px;" aria-hidden="true"></span>
			</a>
			<a id="remind_me_later_by_ajax_callback" class="button button-secondary" href="#">
				<?php esc_html_e( 'Remind me later', 'easy-woocommerce-auto-sku-generator' ); ?>
				<span class="dashicons dashicons-backup" style="font-size:16px;position:relative;bottom:-5px;" aria-hidden="true"></span>
			</a>
			<a
				id="leave_feedback"
				class="button button-primary"
				href="https://wordpress.org/support/plugin/easy-woocommerce-auto-sku-generator/reviews/#new-post"
				target="_blank"
				rel="noopener noreferrer"
			>
				<?php esc_html_e( 'Leave feedback', 'easy-woocommerce-auto-sku-generator' ); ?>
				<span class="dashicons dashicons-format-status" style="font-size:16px;position:relative;bottom:-5px;" aria-hidden="true"></span>
			</a>
		</p>
	</div>
	<?php
}

add_action( 'wp_ajax_i_have', 'i_have_already_by_ajax_callback' );

/**
 * AJAX: user clicked "Don't show again" — snooze for 4 years.
 *
 * @return void
 */
function i_have_already_by_ajax_callback() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die();
	}

	update_option( 'glideffxf_data_install', wp_date( 'Y-m-d', strtotime( '+4 years' ) ) );
	wp_die();
}

add_action( 'wp_ajax_remind_me_later', 'remind_me_later_by_ajax_callback' );

/**
 * AJAX: user clicked "Remind me later" — snooze for 1 day.
 *
 * @return void
 */
function remind_me_later_by_ajax_callback() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die();
	}

	update_option( 'glideffxf_data_install', wp_date( 'Y-m-d', strtotime( '+1 days' ) ) );
	wp_die();
}

add_action( 'wp_ajax_leave_feedback', 'leave_feedback_by_ajax_callback' );

/**
 * AJAX: user clicked "Leave feedback" — snooze for 5 years.
 *
 * @return void
 */
function leave_feedback_by_ajax_callback() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die();
	}

	update_option( 'glideffxf_data_install', wp_date( 'Y-m-d', strtotime( '+5 years' ) ) );
	wp_die();
}
