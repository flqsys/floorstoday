<?php defined('ABSPATH') or die; ?>

<?php if(count($downloadableFiles)): ?>
<style>
    .ff_font_installer {
        max-width: 520px;
        margin: 40px auto;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 40px 32px;
        text-align: center;
    }
    .ff_font_installer img {
        max-width: 160px;
        margin-bottom: 24px;
    }
    .ff_font_installer h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1e1e1e;
        margin: 0 0 8px;
    }
    .ff_font_installer p {
        font-size: 14px;
        color: #606266;
        line-height: 1.6;
        margin: 0 0 24px;
    }
    .ff_font_installer .ff_install_btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        min-width: 200px;
        padding: 0 24px;
        background: #1a73e8;
        border: none;
        border-radius: 4px;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        overflow: hidden;
        transition: background 0.2s;
    }
    .ff_font_installer .ff_install_btn:hover {
        background: #1b5fc1;
    }
    .ff_font_installer .ff_install_btn:disabled {
        cursor: not-allowed;
    }
    .ff_font_installer .ff_install_btn .ff_download_fonts_bar {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 0;
        background: #1a7efb;
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    .ff_font_installer .ff_install_btn .ff_download_fonts_text {
        position: relative;
        z-index: 1;
    }
    .ff_font_installer .ff_download_loading {
        margin-top: 16px;
        font-size: 13px;
        color: #909399;
        min-height: 20px;
    }
    .ff_font_installer .ff_download_logs {
        margin-top: 12px;
        text-align: left;
        font-size: 12px;
        color: #909399;
        max-height: 120px;
        overflow-y: auto;
        background: #f5f7fa;
        border-radius: 4px;
        padding: 8px 12px;
        line-height: 1.8;
    }
    .ff_font_installer .ff_download_logs.hidden {
        display: none;
    }
</style>
<div class="ff_font_installer">
    <img src="<?php echo esc_url(FLUENT_PDF_URL . 'assets/images/pdf-img.png'); ?>" alt="">
    <h3><?php echo esc_html__('Fonts Required for PDF Generation', 'fluentforms-pdf'); ?></h3>
    <p><?php echo esc_html__('This module requires fonts for PDF generation. Click the button below to download the required font files. This is a one-time setup.', 'fluentforms-pdf'); ?></p>
    <button id="ff_download_fonts" class="ff_install_btn">
        <span class="ff_download_fonts_bar"></span>
        <span class="ff_download_fonts_text"><?php echo esc_html__('Install Fonts', 'fluentforms-pdf'); ?></span>
    </button>
    <div class="ff_download_loading"></div>
    <div class="ff_download_logs hidden"></div>
</div>
<?php else: ?>

<div class="ff_pdf_system_status" style="<?php echo isset($inheritStyle) && $inheritStyle ? '' :  'max-width: 600px; margin: 0 auto; padding-top: 32px;'?>">
    <h3 class="mb-3">Fluent PDF Module is now active <?php if(!$statuses['status']): ?><span style="color: red;">But Few Server Extensions are missing</span><?php endif; ?></h3>
    <ul>
        <?php foreach ($statuses['extensions'] as $status): ?>
        <li>
            <?php if($status['status']): ?><span class="dashicons dashicons-yes"></span>
            <?php else: ?><span class="dashicons dashicons-no-alt"></span><?php endif; ?>
            <?php echo esc_html($status['label']); ?>
        </li>
        <?php endforeach; ?>
    </ul>

    <?php if($statuses['status']): ?>
    <p>All Looks good! You can now use Fluent PDF Addon. <a href="<?php echo esc_url($globalSettingsUrl); ?>">Click Here</a> to check your global PDF feed settings</p>
    <?php endif; ?>
</div>
<?php endif; ?>
