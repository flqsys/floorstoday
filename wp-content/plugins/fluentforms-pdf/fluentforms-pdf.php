<?php
/**
 * Plugin Name: Fluent PDF Generator
 * Plugin URI:  https://wpmanageninja.com/downloads/fluentform-pro-add-on/
 * Description: Download and Email entries as pdf with multiple template for all Fluent Products.
 * Author: WPManageNinja LLC
 * Author URI:  https://wpmanageninja.com
 * Version: 2.1.1
 * Text Domain: fluentforms-pdf
 * Domain Path: /assets/languages
 * License: GPLv2 or later
 */

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright 2019 WPManageNinja LLC. All rights reserved.
 */

defined('ABSPATH') or die;

// Guard against both PDF plugins being active
if (defined('FLUENT_PDF')) {
    add_action('admin_notices', function () {
        printf(
            '<div class="notice notice-warning"><p>%s</p></div>',
            esc_html__('Both "Fluent Forms PDF Generator" and "Fluent PDF Generator" are active. Please deactivate one to avoid conflicts.', 'fluentforms-pdf')
        );
    });
    return;
}

// New constants (used by fluent-pdf codebase)
define('FLUENT_PDF', true);
define('FLUENT_PDF_VERSION', '2.1.1');
define('FLUENT_PDF_PATH', plugin_dir_path(__FILE__));
define('FLUENT_PDF_URL', plugin_dir_url(__FILE__));
define('FLUENT_PDF_PRODUCTION', 'yes');

// Backward-compat constants (WPPayForm, old FF core check these)
define('FLUENTFORM_PDF_VERSION', FLUENT_PDF_VERSION);
define('FLUENTFORM_PDF_PATH', FLUENT_PDF_PATH);
define('FLUENTFORM_PDF_URL', FLUENT_PDF_URL);

// Used by apply_filters_deprecated() in AvailableOptions and templates
if (!defined('FLUENTPDF_FRAMEWORK_UPGRADE')) {
    define('FLUENTPDF_FRAMEWORK_UPGRADE', '2.0.0');
}

require_once FLUENT_PDF_PATH . 'vendor/autoload.php';
require_once FLUENT_PDF_PATH . 'vendor-prefixed/mpdf/mpdf/src/functions.php';
require_once FLUENT_PDF_PATH . 'API/Pdf.php';

/**
 * Backward-compat: alias old namespace so custom templates extending the v1 class still work.
 * The deprecation notice fires only when code actually loads the old class name,
 * not on every request.
 *
 * @deprecated 2.1.1 Use FluentPdf\Modules\FluentForms\Templates\TemplateManager instead.
 */
spl_autoload_register(function ($class) {
    if ($class === 'FluentFormPdf\Classes\Templates\TemplateManager') {
        _deprecated_function(
            'FluentFormPdf\Classes\Templates\TemplateManager',
            '2.1.1',
            'FluentPdf\Modules\FluentForms\Templates\TemplateManager'
        );
        class_alias(
            'FluentPdf\Modules\FluentForms\Templates\TemplateManager',
            'FluentFormPdf\Classes\Templates\TemplateManager'
        );
    }
});

class FluentPdf
{
    public function boot()
    {
        (new FluentPdf\Classes\AdminMenuHandler())->register();
        (new FluentPdf\Classes\PdfBuilder())->register();

        do_action('fluent_pdf_loaded');
    }
}

add_action('plugins_loaded', function () {
    (new FluentPdf())->boot();
});

// FluentForms integration
add_action('plugins_loaded', function () {
    if (!defined('FLUENTFORM') || !function_exists('wpFluentForm')) {
        return;
    }

    (new FluentPdf\Modules\FluentForms\FluentFormsIntegration(wpFluentForm()))->register();
}, 20);

add_action('init', function () {
    (new FluentPdf\Classes\Controller\GlobalFontManager())->registerAjax();
});

register_activation_hook(__FILE__, function () {
    FluentPdf\Classes\Controller\Activator::activate();
});

register_deactivation_hook(__FILE__, function () {
    FluentPdf\Classes\Controller\Activator::deactivate();
});
