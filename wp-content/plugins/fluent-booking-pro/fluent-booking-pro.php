<?php defined('ABSPATH') or die;
/**
 * Plugin Name: FluentBooking Pro
 * Description: The Pro version of FluentBooking Plugin
 * Version: 2.1.2
 * Author: WPManageNinja LLC
 * Author URI: https://fluentbooking.com
 * Plugin URI: https://fluentbooking.com
 * License: GPLv2 or later
 * Text Domain: fluent-booking-pro
 * Domain Path: /language
 */

if (defined('FLUENT_BOOKING_PRO_DIR_FILE')) {
    return;
}

define('FLUENT_BOOKING_PRO_DIR_FILE', __FILE__);
define('FLUENT_BOOKING_PRO_DIR', plugin_dir_path(__FILE__));
define('FLUENT_BOOKING_PRO_VERSION', '2.1.2');
define('FLUENT_BOOKING_PRO_DB_VERSION', '1.0.0');
define('FLUENT_BOOKING_MIN_CORE_VERSION', '2.1.2');

require __DIR__ . '/vendor/autoload.php';

call_user_func(function ($bootstrap) {
    $bootstrap(__FILE__);
}, require(__DIR__ . '/boot/app.php'));
