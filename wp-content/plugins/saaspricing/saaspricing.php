<?php

/**
 * Plugin Name: SaasPricing – Advanced Pricing & Comparison Tables for Elementor
 * Description: Top Elementor Widget for Price Table, comparison table and real time pricing calculator With trendy design and different table style
 * Version: 1.2.7
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Elementor tested up to: 3.35.6
 * Author: Debuggers Studio
 * Author URI: https://debuggersstudio.com
 * Text Domain: saaspricing
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires Plugins: elementor
 */

 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'SAASP_PRICING__FILE__', __FILE__ ); 
define( 'SAASP_PRICING__DIR__', __DIR__ );
define( 'SAASP_PRICING_URL', plugins_url( '/', SAASP_PRICING__FILE__ ) );
define( 'SAASP_PRICING_ASSETS_URL', SAASP_PRICING_URL . 'assets/' ); 

function saasp_load_plugin_data() {

	require_once( SAASP_PRICING__DIR__ . '/includes/Utils.php' );
	require_once( SAASP_PRICING__DIR__ . '/includes/widget.php' );
    
	\Saas_Pricing_Table\Saas_Pricing::instance();

}
add_action( 'plugins_loaded', 'saasp_load_plugin_data' );