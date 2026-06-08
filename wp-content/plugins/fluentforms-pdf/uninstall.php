<?php

/**
 * Fired when the plugin is deleted (not just deactivated).
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

define('FLUENT_PDF_PATH', plugin_dir_path(__FILE__));

if (!defined('FLUENTPDF_FRAMEWORK_UPGRADE')) {
    define('FLUENTPDF_FRAMEWORK_UPGRADE', '2.0.0');
}

require_once FLUENT_PDF_PATH . 'vendor/autoload.php';

FluentPdf\Classes\Controller\Activator::uninstall();
