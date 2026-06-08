<?php

namespace FluentPdf\Classes;

defined('ABSPATH') or die;

use FluentPdf\Classes\Controller\FontDownloader;

class AdminMenuHandler
{
    public function register()
    {
        add_action('admin_menu', function () {
            if (apply_filters('fluent_pdf_hide_menu', false)) {
                return;
            }
            $this->addMenu();
        });
    }

    public function addMenu()
    {
        $title = __('Fluent PDF', 'fluent-pdf');

        add_options_page(
            $title,
            $title,
            'manage_options',
            'fluent_pdf_settings',
            array($this, 'renderSettings')
        );
    }

    public function renderSettings()
    {
        $fontManager = new FontDownloader();

        if ($fontManager->isBaselineMissing()) {
            $downloadableFiles = $fontManager->getDownloadableFonts();
            if ($downloadableFiles) {
                $this->renderFontInstaller($downloadableFiles);
                return;
            }
        }

        Vite::enqueueScript('fluent-pdf-script-boot', 'admin/start.js', array('jquery'), FLUENT_PDF_VERSION, true);
        Vite::enqueueStyle('fluent-pdf-style-boot', 'scss/admin/app.scss', array(), FLUENT_PDF_VERSION);

        wp_add_inline_style('fluent-pdf-style-boot', '.fluent-pdf-admin-page .el-input__wrapper input{border:none;box-shadow:none;background:transparent;outline:none}');

        $fluentPdfVars = apply_filters('fluent-pdf/admin_app_vars', array(
            'assets_url' => FLUENT_PDF_URL . 'assets/',
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fluent_pdf_admin_nonce'),
        ));

        wp_localize_script('fluent-pdf-script-boot', 'fluent_pdf_admin', $fluentPdfVars);

        echo '<div class="wrap">
                <h1>' . esc_html__('Fluent PDF', 'fluent-pdf') . '</h1>
                <div class="fluent-pdf-admin-page" id="fluent-pdf_app">
                    <router-view></router-view>
                </div>
            </div>';
    }

    private function renderFontInstaller($downloadableFiles)
    {
        Vite::enqueueScript('fluent_pdf_admin', 'admin/FontManager/FontManager.js', array('jquery'), FLUENT_PDF_VERSION, true);

        wp_localize_script('fluent_pdf_admin', 'fluent_pdf_admin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('fluent_pdf_admin_nonce'),
        ]);

        $statuses = [];
        $globalSettingsUrl = '#';

        echo '<div class="wrap">';
        include FLUENT_PDF_PATH . 'views/admin_screen.php';
        echo '</div>';
    }
}
