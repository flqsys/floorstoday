<?php
/**
 * Plugin uninstall cleanup.
 *
 * @package EasyAutoSkuGenerator
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$option_keys = array(
	'skuautoffxf_auto_number',
	'skuautoffxf_auto_prefix',
	'skuautoffxf_letters_and_numbers',
	'skuautoffxf_auto_ID',
	'skuautoffxf_auto_variant',
	'skuautoffxf_previous',
	'glideffxf_data_install',
	'skuautoffxf_duplicate_sku',
	'skuautoffxf_variation_separator',
	'skuautoffxf_variation_settings',
	'skuautoffxf_suffix',
	'skuautoffxf_number_dop',
	'ffxf_format_an',
);

$delete_plugin_options = static function () use ( $option_keys ) {
	foreach ( $option_keys as $option_key ) {
		delete_option( $option_key );
	}
};

if ( ! is_multisite() ) {
	$delete_plugin_options();
	return;
}

$blog_ids         = get_sites(
	array(
		'fields' => 'ids',
		'number' => 0,
	)
);
$original_blog_id = get_current_blog_id();

foreach ( $blog_ids as $site_blog_id ) {
	switch_to_blog( (int) $site_blog_id );
	$delete_plugin_options();
}

switch_to_blog( $original_blog_id );
