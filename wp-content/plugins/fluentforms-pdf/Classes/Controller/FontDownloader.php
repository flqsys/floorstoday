<?php

namespace FluentPdf\Classes\Controller;

defined('ABSPATH') or die;

class FontDownloader
{

    private $github_repo = 'https://raw.githubusercontent.com/WPManageNinja/mpdf-core-fonts/master/';

    public function getCoreFonts()
    {
        $filePath = FLUENT_PDF_PATH . '/core-fonts.json';
        if (!file_exists($filePath)) {
            return [];
        }

        $json = file_get_contents($filePath);
        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function getDownloadableFonts($limit = 0)
    {
        $fontDir = $this->getFontDir();
        if(!function_exists('\list_files')) {
            $admin_path = ABSPATH .'/wp-admin/';
            include_once $admin_path.'includes/file.php';
        }
        $downloadedFiles = \list_files($fontDir, 1);

        $fileNames = [];
        foreach ($downloadedFiles as $file) {
            $fileNames[] = str_replace($fontDir, '', $file);
        }
        $coreFonts = $this->getCoreFonts();
        $downloadableFonts = [];
        foreach ($coreFonts as $coreFont) {
            if($limit && count($downloadableFonts) == $limit) {
                return $downloadableFonts;
            }
            if(!in_array($coreFont['name'], $fileNames)) {
                $downloadableFonts[] = $coreFont;
            }
        }
        return $downloadableFonts;
    }

    public function download($fontName)
    {
        $destination = $this->getFontDir();
        $res = wp_remote_get(
            $this->github_repo . $fontName,
            [
                'timeout'  => 60,
                'stream'   => true,
                'filename' => $destination . $fontName,
            ]
        );

        /* Check for errors and log them to file */
        if ( is_wp_error( $res ) ) {
            return $res;
        }

        $res_code = wp_remote_retrieve_response_code( $res );
        if ( $res_code !== 200 ) {
           return new \WP_Error('failed', 'Core Font API Response Failed');
        }
        return true;
    }

    /**
     * Check whether the baseline fonts required for PDF generation are missing.
     */
    public function isBaselineMissing()
    {
        $fontDir = $this->getFontDir();
        $baseline = ['DejaVuSans.ttf', 'DejaVuSans-Bold.ttf'];

        foreach ($baseline as $font) {
            if (!file_exists($fontDir . $font)) {
                return true;
            }
        }

        return false;
    }

    private function getFontDir()
    {
        $dirStructure = AvailableOptions::getDirStructure();
        return $dirStructure['fontDir'].'/';
    }
}
