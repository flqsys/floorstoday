<?php
/**
 * Plugin Name: Floors Today Next Homepage Backend
 * Description: WordPress settings and REST data for the Next.js homepage front end.
 * Version: 0.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

const FT_NEXT_HOME_OPTION = 'ft_next_homepage_settings';

function ft_next_homepage_defaults() {
    return [
        'primary_color' => '#155f99',
        'secondary_color' => 'lab(76 3.16 65.32)',
        'background_color' => 'oklch(0.985 0.002 90)',
        'foreground_color' => 'oklch(0.20 0.02 30)',
        'phone' => '1-888-772-7848',
        'email' => 'info@floorstoday.com',
        'service_area' => 'Serving Ontario & Surrounding Areas',
        'logo_text' => 'Floors Today',
        'logo_image' => '',
        'favicon_image' => '/floorstoday/public/favicon.png',
        'logo_size' => '40px',
        'cta_label' => 'Free Estimate',
        'show_header' => '1',
        'show_footer' => '1',
        'button_radius' => '8px',
        'button_font_weight' => '700',
        'button_text_transform' => 'none',
        'button_padding_x' => '18px',
        'button_padding_y' => '12px',
        'button_hover_mix' => '88%',
        'button_border_width' => '0px',
        'button_border_style' => 'solid',
        'button_border_color' => 'transparent',
        'hero_badge' => 'LIMITED TIME: 50.50.50 SALE',
        'hero_badge_bg_color' => 'lab(76 3.16 65.32)',
        'hero_badge_text_color' => '#ffffff',
        'hero_badge_font_size' => '16px',
        'hero_badge_padding_x' => '16px',
        'hero_badge_padding_y' => '8px',
        'hero_title' => 'Transform Your Home with',
        'hero_highlight' => 'Premium Flooring',
        'hero_badge_animation_color_1' => 'lab(76 3.16 65.32)',
        'hero_badge_animation_color_2' => '#ffffff',
        'hero_badge_animation_location' => '90deg',
        'hero_badge_animation_speed' => '4s',
        'hero_text' => 'All-inclusive pricing with no hidden fees. Get a complete quote during your free in-home consultation.',
        'hero_image' => '/floorstoday/public/images/hero-living-room.png',
        'hero_show_background' => '1',
        'hero_show_overlay' => '1',
        'hero_overlay_opacity' => '0.72',
        'form_title' => 'Get Your FREE In-Home Estimate',
        'form_subtitle' => 'No obligation. Takes just 2 minutes.',
        'process_title' => 'How It Works',
        'process_text' => 'Getting beautiful new floors has never been easier. Our simple 3-step process takes the stress out of flooring.',
        'process_bg_color_1' => 'oklch(0.985 0.002 90)',
        'process_bg_color_2' => 'oklch(0.985 0.002 90)',
        'process_bg_location' => 'to bottom',
        'comparison_title' => 'All-Inclusive Price Estimate, No Hidden Fees',
        'comparison_text' => 'Floors Today makes it easy with one, easy to understand price, complete with all the commonly up-charged items required for your floor to be installed. You will know the full project price during your free in-home appointment, upfront, before any installation work begins.',
        'comparison_button' => 'Book An Appointment',
        'comparison_bg_color_1' => 'var(--primary)',
        'comparison_bg_color_2' => 'var(--primary)',
        'comparison_bg_location' => 'to bottom',
        'cta_title' => 'Ready to Get Started?',
        'cta_subtitle' => 'Schedule a FREE In-Home Estimate',
        'cta_text' => 'Our flooring experts will bring samples to your home, measure your space, and provide an all-inclusive price quote with no hidden fees.',
        'cta_button' => 'Schedule Now',
        'cta_bg_color_1' => 'var(--primary)',
        'cta_bg_color_2' => 'var(--primary)',
        'cta_bg_location' => 'to bottom',
        'category_title' => 'Shop By Category',
        'category_text' => 'Explore our wide selection of premium flooring options for every style and budget',
        'category_bg_color_1' => 'oklch(0.96 0.005 90)',
        'category_bg_color_2' => 'oklch(0.96 0.005 90)',
        'category_bg_location' => 'to bottom',
        'guarantee_title' => 'Low Price Guarantee',
        'guarantee_subtitle' => "We won't be beat on price!",
        'guarantee_text' => "If you find a lower price on a comparable product and installation, we'll beat any competitive offer, guaranteed! Our commitment to value means you get the best flooring at the best price, every time.",
        'guarantee_link' => 'Get More Information',
        'guarantee_image' => 'https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=600&h=400&fit=crop',
        'guarantee_bg_color_1' => 'oklch(0.985 0.002 90)',
        'guarantee_bg_color_2' => 'oklch(0.985 0.002 90)',
        'guarantee_bg_location' => 'to bottom',
        'deals_badge' => 'Limited Time Offers',
        'deals_title' => 'Floors Today Coupons & Special Offers',
        'deals_text' => 'Exclusive flooring deals designed to fit your budget — quality floors without the hidden costs',
        'deals_body' => 'At Floors Today, we believe great flooring should be accessible without confusion or hidden costs. Along with our everyday competitive pricing, we offer limited-time promotions and special deals to help homeowners save on high-quality flooring and professional installation.',
        'deals_card_title' => '50.50.50',
        'deals_card_subtitle' => 'SALE',
        'deals_button' => 'Claim Your Savings',
        'deals_bg_color_1' => 'oklch(0.96 0.005 90)',
        'deals_bg_color_2' => 'oklch(0.985 0.002 90)',
        'deals_bg_location' => 'to bottom',
        'testimonials_title' => 'What Our Customers Say',
        'testimonials_text' => 'Join thousands of satisfied Ontario homeowners who trust Floors Today for their flooring needs',
        'testimonials_bg_color_1' => 'oklch(0.985 0.002 90)',
        'testimonials_bg_color_2' => 'oklch(0.985 0.002 90)',
        'testimonials_bg_location' => 'to bottom',
        'newsletter_title' => 'Subscribe to Newsletter',
        'newsletter_text' => 'Get the latest deals and flooring tips',
        'newsletter_button' => 'Subscribe',
        'footer_about' => "We believe in transparent pricing. That's why our all-inclusive estimates include every essential detail in delivering a seamless flooring experience with no unexpected costs.",
        'footer_bg_color_1' => 'oklch(0.20 0.02 30)',
        'footer_bg_color_2' => 'oklch(0.20 0.02 30)',
        'footer_bg_location' => 'to bottom',
        'nav_items' => [
            ['name' => 'Solid Hardwood', 'href' => '#hardwood'],
            ['name' => 'Engineered Hardwood', 'href' => '#engineered'],
            ['name' => 'Laminate', 'href' => '#laminate'],
            ['name' => 'Vinyl', 'href' => '#vinyl'],
            ['name' => 'Carpet', 'href' => '#carpet'],
        ],
        'process_steps' => [
            [
                'title' => 'Schedule a Free In-Home Consult with a Flooring Expert',
                'description' => 'Book your free consultation at a time that works for you. Our experts come to your home with samples.',
                'button' => 'Get Started Now',
                'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'No Surprises, No Hidden Fees',
                'description' => 'A flooring expert will bring samples, help you pick the right floors, measure your rooms, and give you an All-Inclusive Price Estimate.',
                'button' => '',
                'image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=400&h=300&fit=crop',
            ],
            [
                'title' => 'Schedule-Friendly Installation',
                'description' => 'Upon approval, you will be able to finance your purchase and schedule your professional installation.',
                'button' => '',
                'image' => 'https://images.unsplash.com/photo-1600573472550-8090b5e0745e?w=400&h=300&fit=crop',
            ],
        ],
        'comparison_rows' => [
            'Product Cost',
            'Measuring',
            'Professional Installation',
            'Padding/Underlayment',
            'Moving Furniture',
            'Haul Away of Old Flooring',
            'Thresholds/Transitions',
            'Clean Up',
            'All-Inclusive Price Estimate',
        ],
        'categories' => [
            ['name' => 'Solid Hardwood', 'slug' => 'solid-hardwood', 'description' => 'Timeless elegance with natural wood beauty', 'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=500&fit=crop'],
            ['name' => 'Engineered Hardwood', 'slug' => 'engineered-hardwood', 'description' => 'Versatile and durable for any room', 'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=400&h=500&fit=crop'],
            ['name' => 'Laminate', 'slug' => 'laminate', 'description' => 'Affordable style with easy maintenance', 'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=400&h=500&fit=crop'],
            ['name' => 'Vinyl', 'slug' => 'vinyl', 'description' => 'Waterproof and pet-friendly options', 'image' => 'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?w=400&h=500&fit=crop'],
            ['name' => 'Carpet', 'slug' => 'carpet', 'description' => 'Soft comfort for bedrooms and living areas', 'image' => 'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=400&h=500&fit=crop'],
        ],
        'offers' => [
            ['title' => 'Up to 50% Off', 'description' => 'Select hardwood and engineered flooring styles'],
            ['title' => 'Free Installation', 'description' => 'On qualifying orders over $2,500'],
            ['title' => 'Price Match Plus', 'description' => "We'll beat any competitor's price by 5%"],
            ['title' => '0% Financing', 'description' => 'For 12 months on approved credit'],
        ],
        'testimonials' => [
            ['name' => 'Sarah M.', 'location' => 'Toronto, ON', 'floorType' => 'Solid Hardwood', 'text' => 'The team at Floors Today was incredible. From the initial consultation to the final installation, everything was seamless. Our new hardwood floors look absolutely stunning!'],
            ['name' => 'Michael R.', 'location' => 'Mississauga, ON', 'floorType' => 'Engineered Hardwood', 'text' => 'I was worried about hidden fees after bad experiences elsewhere, but Floors Today delivered exactly what they promised. The all-inclusive pricing is the real deal.'],
            ['name' => 'Jennifer L.', 'location' => 'Hamilton, ON', 'floorType' => 'Luxury Vinyl', 'text' => 'Best decision we made for our home renovation. The vinyl flooring is perfect for our busy family with kids and pets. Highly recommend their professional installation.'],
        ],
    ];
}

function ft_next_homepage_settings() {
    $saved = get_option(FT_NEXT_HOME_OPTION, []);
    $settings = array_replace_recursive(ft_next_homepage_defaults(), is_array($saved) ? $saved : []);

    array_walk_recursive($settings, function (&$value) {
        if (is_string($value)) {
            $value = str_replace('/floorstest/', '/floorstoday/public/', $value);
        }
    });

    return $settings;
}

function ft_next_homepage_frontend_url() {
    return home_url('/public/');
}

function ft_next_clean_text($value) {
    return sanitize_text_field(wp_unslash($value));
}

function ft_next_clean_textarea($value) {
    return wp_kses_post(wp_unslash($value));
}

function ft_next_clean_color($value) {
    $value = trim((string) wp_unslash($value));
    return preg_match('/^[a-zA-Z0-9#.,%() \/-]+$/', $value) ? $value : '';
}

function ft_next_clean_lines($value) {
    $lines = preg_split('/\r\n|\r|\n/', (string) wp_unslash($value));
    return array_values(array_filter(array_map('sanitize_text_field', $lines)));
}

add_action('admin_menu', function () {
    add_menu_page(
        'Next Homepage',
        'Next Homepage',
        'manage_options',
        'ft-next-homepage',
        'ft_next_homepage_render_admin',
        'dashicons-admin-home',
        58
    );
});

add_action('template_redirect', function () {
    if (!is_front_page() || is_admin() || wp_doing_ajax() || wp_is_json_request()) {
        return;
    }

    wp_safe_redirect(ft_next_homepage_frontend_url(), 302);
    exit;
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_ft-next-homepage') {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_media();

    wp_add_inline_style('wp-color-picker', '
        #wpcontent {
            background: #f3f5f7;
        }
        .ft-next-admin {
            max-width: none;
            width: 100%;
            margin: 0;
            padding: 0 22px 32px 0;
            color: #17202a;
        }
        .ft-next-admin * {
            box-sizing: border-box;
        }
        .ft-next-admin a {
            color: #155f99;
        }
        .ft-next-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(320px, 460px);
            justify-content: space-between;
            gap: 22px;
            align-items: stretch;
            width: 100%;
            margin: 0 0 18px;
            padding: 26px;
            border: 1px solid #d9e2ea;
            border-radius: 8px;
            background:
                radial-gradient(circle at 0 0, rgba(184, 107, 0, .14), transparent 30%),
                linear-gradient(135deg, #ffffff 0%, #eef5fa 100%);
            box-shadow: 0 16px 38px rgba(30, 49, 72, .08);
        }
        .ft-next-hero h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.2;
            letter-spacing: 0;
            color: #111827;
        }
        .ft-next-muted {
            margin: 0;
            color: #5f6f82;
            font-size: 14px;
        }
        .ft-next-quicknav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }
        .ft-next-quicknav a {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 6px 10px;
            border: 1px solid #cfd9e2;
            border-radius: 8px;
            background: rgba(255, 255, 255, .74);
            color: #243447;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
        }
        .ft-next-quicknav a:hover,
        .ft-next-quicknav a:focus {
            border-color: #155f99;
            color: #155f99;
            box-shadow: 0 0 0 2px rgba(21, 95, 153, .12);
            outline: none;
        }
        .ft-next-endpoint {
            min-width: 0;
            padding: 16px;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #d9e2ea;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.8);
        }
        .ft-next-endpoint code {
            display: block;
            white-space: normal;
            margin-top: 7px;
            padding: 9px 10px;
            border-radius: 6px;
            background: #f5f7f9;
            color: #1d2a38;
            word-break: break-word;
        }
        .ft-next-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(300px, 360px);
            gap: 18px;
            align-items: start;
            width: 100%;
        }
        .ft-next-grid > div:first-child {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            align-items: start;
        }
        .ft-next-grid > div:first-child .ft-next-card {
            margin: 0;
        }
        .ft-next-grid > div:first-child .ft-next-card:nth-child(n+5),
        .ft-next-card--wide {
            grid-column: 1 / -1;
        }
        .ft-next-grid > div:first-child .ft-next-card:nth-child(n+5) input.regular-text,
        .ft-next-grid > div:first-child .ft-next-card:nth-child(n+5) textarea.large-text,
        .ft-next-grid > div:first-child .ft-next-card:nth-child(n+5) .ft-next-image-row,
        .ft-next-grid > div:first-child .ft-next-card:nth-child(n+5) .ft-next-color-row {
            max-width: none;
        }
        .ft-next-grid > div:first-child .ft-next-card:nth-child(n+5) .form-table th {
            width: 220px;
        }
        .ft-next-card {
            margin: 0 0 20px;
            width: 100%;
            border: 1px solid #d9e2ea;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(30, 49, 72, .07);
            overflow: hidden;
            scroll-margin-top: 48px;
        }
        .ft-next-card__head {
            padding: 15px 18px;
            border-bottom: 1px solid #edf1f5;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }
        .ft-next-card__head h2 {
            margin: 0;
            font-size: 15px;
            line-height: 1.35;
            color: #17202a;
        }
        .ft-next-card__body {
            padding: 18px;
        }
        .ft-next-card .form-table {
            margin: 0;
        }
        .ft-next-card .form-table th {
            width: 180px;
            padding: 14px 14px 14px 0;
            color: #314154;
            font-weight: 700;
        }
        .ft-next-card .form-table td {
            padding: 10px 0;
        }
        .ft-next-card input.regular-text,
        .ft-next-card textarea.large-text,
        .ft-next-card select,
        .ft-next-inline-grid input,
        .ft-next-inline-grid textarea,
        .ft-next-field-stack input,
        .ft-next-field-stack textarea,
        .ft-next-table input,
        .ft-next-table textarea {
            width: 100%;
            max-width: 720px;
            border-radius: 8px;
            border-color: #cbd5df;
            min-height: 38px;
            color: #1d2a38;
        }
        .ft-next-card textarea.large-text,
        .ft-next-inline-grid textarea,
        .ft-next-field-stack textarea,
        .ft-next-table textarea {
            min-height: 92px;
        }
        .ft-next-card input:focus,
        .ft-next-card textarea:focus,
        .ft-next-card select:focus {
            border-color: #155f99;
            box-shadow: 0 0 0 2px rgba(21, 95, 153, .15);
            outline: none;
        }
        .ft-next-image-row {
            display: flex;
            gap: 10px;
            align-items: center;
            max-width: 720px;
        }
        .ft-next-image-input {
            display: none !important;
        }
        .ft-next-image-preview {
            display: block;
            width: 320px;
            max-width: 100%;
            height: 190px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #d9e2ea;
            margin-top: 10px;
            background: #f5f7f9;
        }
        .ft-next-table {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #d9e2ea;
            box-shadow: none;
        }
        .ft-next-table th {
            background: #f8fafc;
            color: #314154;
            font-size: 12px;
            text-transform: uppercase;
        }
        .ft-next-repeater {
            display: grid;
            gap: 14px;
        }
        .ft-next-repeater-item {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(260px, .65fr);
            gap: 18px;
            padding: 16px;
            border: 1px solid #d9e2ea;
            border-radius: 8px;
            background: #f8fafc;
        }
        .ft-next-repeater-item--single {
            grid-template-columns: minmax(0, 1fr);
        }
        .ft-next-repeater-item--single .ft-next-media-panel,
        .ft-next-repeater-item--single .ft-next-image-preview {
            width: 100%;
        }
        .ft-next-repeater-item--single .ft-next-image-preview {
            max-width: 720px;
            height: auto;
            aspect-ratio: 16 / 9;
        }
        .ft-next-repeater-item h3 {
            grid-column: 1 / -1;
            margin: 0;
            font-size: 13px;
            color: #17202a;
        }
        .ft-next-field-stack {
            display: grid;
            gap: 12px;
        }
        .ft-next-field-stack label {
            display: grid;
            gap: 6px;
            font-weight: 600;
            color: #314154;
        }
        .ft-next-subgroup {
            display: grid;
            gap: 12px;
            padding: 14px;
            border: 1px solid #d9e2ea;
            border-radius: 8px;
            background: #fff;
        }
        .ft-next-subgroup h4 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #17202a;
        }
        .ft-next-inline-grid {
            display: grid;
            gap: 16px;
            margin-bottom: 16px;
        }
        .ft-next-inline-grid--2 {
            grid-template-columns: repeat(2, minmax(220px, 1fr));
        }
        .ft-next-inline-grid--3 {
            grid-template-columns: repeat(3, minmax(180px, 1fr));
        }
        .ft-next-inline-grid--header {
            grid-template-columns: minmax(150px, 1fr) minmax(180px, 1fr) minmax(260px, 30%) minmax(170px, 1fr);
        }
        .ft-next-inline-grid label {
            display: grid;
            gap: 6px;
            font-weight: 600;
            color: #314154;
        }
        .ft-next-inline-grid input,
        .ft-next-inline-grid textarea,
        .ft-next-field-stack input,
        .ft-next-field-stack textarea {
            font-weight: 400;
        }
        .ft-next-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 12px 14px;
            border: 1px solid #d9e2ea;
            border-radius: 8px;
            background: #f8fafc;
            font-weight: 600;
            color: #314154;
        }
        .ft-next-toggle input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .ft-next-toggle__switch {
            width: 48px;
            height: 26px;
            border-radius: 999px;
            background: #c3c4c7;
            position: relative;
            transition: background .18s ease;
            flex: 0 0 auto;
        }
        .ft-next-toggle__switch::after {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.25);
            transition: transform .18s ease;
        }
        .ft-next-toggle input:checked + .ft-next-toggle__switch {
            background: #155f99;
        }
        .ft-next-toggle input:checked + .ft-next-toggle__switch::after {
            transform: translateX(22px);
        }
        .ft-next-media-panel {
            display: grid;
            align-content: start;
            gap: 8px;
        }
        .ft-next-media-panel > span {
            font-weight: 600;
        }
        .ft-next-color-row {
            display: grid;
            grid-template-columns: auto minmax(220px, 1fr) auto;
            gap: 10px;
            align-items: center;
            max-width: 720px;
        }
        .ft-next-color-row .wp-picker-container {
            min-width: 110px;
        }
        .ft-next-color-row .wp-picker-container .wp-color-result.button {
            margin: 0;
        }
        .ft-next-color-value {
            width: 100%;
        }
        .ft-next-swatch {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            border: 1px solid #cbd5df;
            background: var(--ft-color, transparent);
        }
        .ft-next-sidebar {
            position: sticky;
            top: 44px;
            display: grid;
            gap: 14px;
        }
        .ft-next-preview {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #d9e2ea;
            background: #fff;
            box-shadow: 0 12px 30px rgba(30, 49, 72, .08);
        }
        .ft-next-preview__top {
            padding: 16px;
            color: #fff;
            background: var(--ft-primary, #155f99);
        }
        .ft-next-preview__body {
            padding: 18px;
            background: var(--ft-bg, #f7f6f2);
            color: var(--ft-fg, #231f20);
        }
        .ft-next-preview__button {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #fff;
            background: var(--ft-secondary, #b86b00);
            font-weight: 700;
        }
        .ft-next-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
            padding: 14px;
            border: 1px solid #d9e2ea;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(30, 49, 72, .06);
        }
        .ft-next-actions .button {
            min-height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }
        .ft-next-actions .button-primary {
            background: #155f99;
            border-color: #155f99;
        }
        @media (max-width: 1280px) {
            .ft-next-grid,
            .ft-next-hero {
                grid-template-columns: 1fr;
            }
            .ft-next-grid > div:first-child {
                grid-template-columns: 1fr;
            }
            .ft-next-grid > div:first-child .ft-next-card {
                margin: 0 0 20px;
            }
            .ft-next-endpoint,
            .ft-next-sidebar {
                min-width: 0;
                margin-top: 16px;
                position: static;
            }
            .ft-next-sidebar {
                margin-top: 0;
            }
            .ft-next-repeater-item {
                grid-template-columns: 1fr;
            }
            .ft-next-inline-grid--2,
            .ft-next-inline-grid--3,
            .ft-next-inline-grid--header {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 782px) {
            .ft-next-admin {
                padding-right: 10px;
            }
            .ft-next-hero {
                padding: 18px;
            }
            .ft-next-card .form-table th,
            .ft-next-card .form-table td {
                display: block;
                width: 100%;
                padding: 8px 0;
            }
            .ft-next-color-row {
                grid-template-columns: 1fr;
            }
            .ft-next-actions {
                align-items: stretch;
                flex-direction: column;
            }
        }
    ');

    wp_add_inline_script('wp-color-picker', "
        jQuery(function($) {
            var quickNav = $('.ft-next-quicknav');

            $('.ft-next-card').each(function(index) {
                var card = $(this);
                var title = $.trim(card.find('.ft-next-card__head h2').first().text());

                if (!title) {
                    return;
                }

                var id = 'ft-next-section-' + index;
                card.attr('id', id);
                $('<a />', {
                    href: '#' + id,
                    text: title
                }).appendTo(quickNav);
            });

            quickNav.on('click', 'a', function(event) {
                var target = $($(this).attr('href'));

                if (!target.length) {
                    return;
                }

                event.preventDefault();
                $('html, body').animate({ scrollTop: target.offset().top - 48 }, 220);
            });

            function cssToHex(value) {
                value = (value || '').trim();
                if (/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(value)) {
                    return value;
                }

                var probe = $('<span />').css({
                    color: value,
                    display: 'none'
                }).appendTo(document.body);
                var rgb = probe.css('color');
                probe.remove();
                var match = rgb && rgb.match(/^rgba?\\((\\d+),\\s*(\\d+),\\s*(\\d+)/i);
                if (!match) {
                    return '#ffffff';
                }

                return '#' + [match[1], match[2], match[3]].map(function(part) {
                    return ('0' + parseInt(part, 10).toString(16)).slice(-2);
                }).join('');
            }

            $('.ft-next-color-picker').each(function() {
                var input = $(this);
                var raw = input.val();
                var swatch = input.closest('.ft-next-color-row').find('.ft-next-swatch');
                var pickerInput = input.closest('.ft-next-color-row').find('.ft-next-picker-source');

                function updateSwatch(value) {
                    swatch.css('--ft-color', value || 'transparent');
                    document.documentElement.style.setProperty(input.data('preview-var'), value || '');
                }

                pickerInput.val(cssToHex(raw));
                updateSwatch(raw);

                pickerInput.wpColorPicker({
                    change: function(event, ui) {
                        var picked = ui.color.toString();
                        input.val(picked);
                        updateSwatch(picked);
                    },
                    clear: function() {
                        input.val('');
                        updateSwatch('');
                    }
                });

                input.on('input change', function() {
                    updateSwatch(input.val());
                    pickerInput.wpColorPicker('color', cssToHex(input.val()));
                });
            });

            $('.ft-next-image-button').on('click', function(event) {
                event.preventDefault();
                var button = $(this);
                var row = button.closest('.ft-next-image-field');
                var input = row.find('.ft-next-image-input');
                var preview = row.find('.ft-next-image-preview');
                var frame = wp.media({
                    title: 'Select image',
                    button: { text: 'Use this image' },
                    multiple: false
                });

                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var url = attachment.url || '';
                    input.val(url).trigger('change');
                    preview.attr('src', url).show();
                });

                frame.open();
            });

            $('.ft-next-image-input').on('input change', function() {
                var input = $(this);
                var preview = input.closest('.ft-next-image-field').find('.ft-next-image-preview');
                if (input.val()) {
                    preview.attr('src', input.val()).show();
                } else {
                    preview.hide();
                }
            }).trigger('change');
        });
    ");
});

add_action('admin_post_ft_next_homepage_save', function () {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    check_admin_referer('ft_next_homepage_save');

    $current = ft_next_homepage_settings();
    $data = [];
    $text_fields = [
        'phone', 'email', 'service_area', 'logo_text', 'logo_image', 'favicon_image', 'logo_size', 'cta_label', 'hero_badge',
        'hero_badge_font_size', 'hero_badge_padding_x', 'hero_badge_padding_y',
        'hero_title', 'hero_highlight', 'hero_badge_animation_location', 'hero_badge_animation_speed', 'form_title', 'form_subtitle',
        'process_title', 'comparison_title', 'comparison_button',
        'cta_title', 'cta_subtitle', 'cta_button', 'hero_image', 'hero_overlay_opacity',
        'button_radius', 'button_font_weight', 'button_text_transform',
        'button_padding_x', 'button_padding_y', 'button_hover_mix',
        'button_border_width', 'button_border_style',
        'process_bg_location', 'comparison_bg_location', 'category_bg_location',
        'guarantee_bg_location', 'deals_bg_location', 'testimonials_bg_location',
        'cta_bg_location', 'footer_bg_location',
        'category_title', 'guarantee_title', 'guarantee_subtitle', 'guarantee_link',
        'guarantee_image', 'deals_badge', 'deals_title', 'deals_card_title',
        'deals_card_subtitle', 'deals_button', 'testimonials_title',
        'newsletter_title', 'newsletter_text', 'newsletter_button',
    ];
    $textarea_fields = [
        'hero_text', 'process_text', 'comparison_text', 'cta_text', 'footer_about',
        'category_text', 'guarantee_text', 'deals_text', 'deals_body',
        'testimonials_text',
    ];
    $color_fields = [
        'primary_color', 'secondary_color', 'background_color', 'foreground_color',
        'button_border_color', 'hero_badge_bg_color', 'hero_badge_text_color',
        'hero_badge_animation_color_1', 'hero_badge_animation_color_2',
        'process_bg_color_1', 'process_bg_color_2',
        'comparison_bg_color_1', 'comparison_bg_color_2',
        'category_bg_color_1', 'category_bg_color_2',
        'guarantee_bg_color_1', 'guarantee_bg_color_2',
        'deals_bg_color_1', 'deals_bg_color_2',
        'testimonials_bg_color_1', 'testimonials_bg_color_2',
        'cta_bg_color_1', 'cta_bg_color_2',
        'footer_bg_color_1', 'footer_bg_color_2',
    ];

    foreach ($text_fields as $field) {
        $data[$field] = isset($_POST[$field]) ? ft_next_clean_text($_POST[$field]) : $current[$field];
    }

    foreach ($textarea_fields as $field) {
        $data[$field] = isset($_POST[$field]) ? ft_next_clean_textarea($_POST[$field]) : $current[$field];
    }

    foreach ($color_fields as $field) {
        $data[$field] = isset($_POST[$field]) ? ft_next_clean_color($_POST[$field]) : $current[$field];
    }

    $data['show_header'] = isset($_POST['show_header']) ? '1' : '0';
    $data['show_footer'] = isset($_POST['show_footer']) ? '1' : '0';
    $data['hero_show_background'] = isset($_POST['hero_show_background']) ? '1' : '0';
    $data['hero_show_overlay'] = isset($_POST['hero_show_overlay']) ? '1' : '0';

    $data['comparison_rows'] = isset($_POST['comparison_rows'])
        ? ft_next_clean_lines($_POST['comparison_rows'])
        : $current['comparison_rows'];

    $data['nav_items'] = [];
    $nav_names = $_POST['nav_name'] ?? [];
    $nav_hrefs = $_POST['nav_href'] ?? [];
    for ($i = 0; $i < 5; $i++) {
        $name = isset($nav_names[$i]) ? ft_next_clean_text($nav_names[$i]) : '';
        $href = isset($nav_hrefs[$i]) ? ft_next_clean_text($nav_hrefs[$i]) : '';
        if ($name !== '') {
            $data['nav_items'][] = ['name' => $name, 'href' => $href ?: '#'];
        }
    }

    $data['process_steps'] = [];
    for ($i = 0; $i < 3; $i++) {
        $data['process_steps'][] = [
            'title' => isset($_POST['process_step_title'][$i]) ? ft_next_clean_text($_POST['process_step_title'][$i]) : $current['process_steps'][$i]['title'],
            'description' => isset($_POST['process_step_description'][$i]) ? ft_next_clean_textarea($_POST['process_step_description'][$i]) : $current['process_steps'][$i]['description'],
            'button' => isset($_POST['process_step_button'][$i]) ? ft_next_clean_text($_POST['process_step_button'][$i]) : '',
            'image' => isset($_POST['process_step_image'][$i]) ? esc_url_raw(wp_unslash($_POST['process_step_image'][$i])) : $current['process_steps'][$i]['image'],
        ];
    }

    $data['categories'] = [];
    for ($i = 0; $i < 5; $i++) {
        $name = isset($_POST['category_name'][$i]) ? ft_next_clean_text($_POST['category_name'][$i]) : '';
        if ($name === '') {
            continue;
        }
        $data['categories'][] = [
            'name' => $name,
            'slug' => isset($_POST['category_slug'][$i]) ? ft_next_clean_text($_POST['category_slug'][$i]) : sanitize_title($name),
            'description' => isset($_POST['category_description'][$i]) ? ft_next_clean_textarea($_POST['category_description'][$i]) : '',
            'image' => isset($_POST['category_image'][$i]) ? esc_url_raw(wp_unslash($_POST['category_image'][$i])) : '',
        ];
    }

    $data['offers'] = [];
    for ($i = 0; $i < 4; $i++) {
        $title = isset($_POST['offer_title'][$i]) ? ft_next_clean_text($_POST['offer_title'][$i]) : '';
        if ($title === '') {
            continue;
        }
        $data['offers'][] = [
            'title' => $title,
            'description' => isset($_POST['offer_description'][$i]) ? ft_next_clean_textarea($_POST['offer_description'][$i]) : '',
        ];
    }

    $data['testimonials'] = [];
    for ($i = 0; $i < 3; $i++) {
        $name = isset($_POST['testimonial_name'][$i]) ? ft_next_clean_text($_POST['testimonial_name'][$i]) : '';
        if ($name === '') {
            continue;
        }
        $data['testimonials'][] = [
            'name' => $name,
            'location' => isset($_POST['testimonial_location'][$i]) ? ft_next_clean_text($_POST['testimonial_location'][$i]) : '',
            'floorType' => isset($_POST['testimonial_floor_type'][$i]) ? ft_next_clean_text($_POST['testimonial_floor_type'][$i]) : '',
            'text' => isset($_POST['testimonial_text'][$i]) ? ft_next_clean_textarea($_POST['testimonial_text'][$i]) : '',
        ];
    }

    update_option(FT_NEXT_HOME_OPTION, $data);
    wp_safe_redirect(admin_url('admin.php?page=ft-next-homepage&updated=1'));
    exit;
});

function ft_next_homepage_card_open($title) {
    echo '<section class="ft-next-card"><div class="ft-next-card__head"><h2>' . esc_html($title) . '</h2></div><div class="ft-next-card__body">';
}

function ft_next_homepage_card_close() {
    echo '</div></section>';
}

function ft_next_homepage_field($settings, $field, $label, $type = 'text') {
    $value = $settings[$field] ?? '';
    if ($type === 'checkbox') {
        echo '<label class="ft-next-toggle" for="' . esc_attr($field) . '">';
        echo '<span>' . esc_html($label) . '</span>';
        echo '<span>';
        echo '<input id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" type="checkbox" value="1" ' . checked((string) $value, '1', false) . '>';
        echo '<span class="ft-next-toggle__switch" aria-hidden="true"></span>';
        echo '</span>';
        echo '</label>';
        return;
    }

    echo '<tr><th scope="row"><label for="' . esc_attr($field) . '">' . esc_html($label) . '</label></th><td>';
    if ($type === 'textarea') {
        echo '<textarea id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" rows="4" class="large-text">' . esc_textarea($value) . '</textarea>';
    } elseif ($type === 'image') {
        echo '<div class="ft-next-image-field">';
        echo '<div class="ft-next-image-row">';
        echo '<input id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" type="text" class="regular-text ft-next-image-input" value="' . esc_attr($value) . '">';
        echo '<button type="button" class="button ft-next-image-button">Select Image</button>';
        echo '</div>';
        echo '<img class="ft-next-image-preview" src="' . esc_url($value) . '" alt="" loading="lazy">';
        echo '</div>';
    } elseif ($type === 'color') {
        $preview_var = [
            'primary_color' => '--ft-primary',
            'secondary_color' => '--ft-secondary',
            'background_color' => '--ft-bg',
            'foreground_color' => '--ft-fg',
        ][$field] ?? '';
        echo '<div class="ft-next-color-row">';
        echo '<input type="text" class="ft-next-picker-source" value="">';
        echo '<input id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" type="text" class="regular-text ft-next-color-picker ft-next-color-value" data-preview-var="' . esc_attr($preview_var) . '" value="' . esc_attr($value) . '">';
        echo '<span class="ft-next-swatch" aria-hidden="true"></span>';
        echo '</div>';
        echo '<p class="description">Use the selector, or type an exact CSS color value like <code>lab(76 3.16 65.32)</code>.</p>';
    } else {
        echo '<input id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" type="text" class="regular-text" value="' . esc_attr($value) . '">';
    }
    echo '</td></tr>';
}

function ft_next_homepage_color_control($settings, $field) {
    $value = $settings[$field] ?? '';
    echo '<div class="ft-next-color-row">';
    echo '<input type="text" class="ft-next-picker-source" value="">';
    echo '<input id="' . esc_attr($field) . '" name="' . esc_attr($field) . '" type="text" class="regular-text ft-next-color-picker ft-next-color-value" value="' . esc_attr($value) . '">';
    echo '<span class="ft-next-swatch" aria-hidden="true"></span>';
    echo '</div>';
}

function ft_next_homepage_gradient_controls($settings, $prefix) {
    echo '<div class="ft-next-inline-grid ft-next-inline-grid--3" style="margin-bottom:16px;">';
    echo '<label>Background color 1';
    ft_next_homepage_color_control($settings, $prefix . '_bg_color_1');
    echo '</label>';
    echo '<label>Background color 2';
    ft_next_homepage_color_control($settings, $prefix . '_bg_color_2');
    echo '</label>';
    echo '<label>Gradient location';
    echo '<input name="' . esc_attr($prefix . '_bg_location') . '" type="text" value="' . esc_attr($settings[$prefix . '_bg_location'] ?? 'to bottom') . '">';
    echo '<span class="description">Examples: <code>to bottom</code>, <code>to right</code>, <code>135deg</code>.</span>';
    echo '</label>';
    echo '</div>';
}

function ft_next_homepage_render_admin() {
    $settings = ft_next_homepage_settings();
    $endpoint = rest_url('floors-today/v1/homepage');
    $frontend_url = ft_next_homepage_frontend_url();
    ?>
    <div class="wrap ft-next-admin">
        <?php if (isset($_GET['updated'])) : ?>
            <div class="notice notice-success is-dismissible"><p>Homepage settings saved.</p></div>
        <?php endif; ?>

        <div class="ft-next-hero">
            <div>
                <h1>Next Homepage</h1>
                <p class="ft-next-muted">Edit the content, colors, and settings used by the Next.js front end.</p>
                <nav class="ft-next-quicknav" aria-label="Homepage sections"></nav>
            </div>
            <div class="ft-next-endpoint">
                <strong>Connected front end</strong>
                <code><?php echo esc_html($frontend_url); ?></code>
                <strong style="display:block;margin-top:12px;">REST endpoint</strong>
                <code><?php echo esc_html($endpoint); ?></code>
            </div>
        </div>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('ft_next_homepage_save'); ?>
            <input type="hidden" name="action" value="ft_next_homepage_save">

            <div class="ft-next-grid">
                <div>
                    <?php ft_next_homepage_card_open('Style'); ?>
                        <table class="form-table" role="presentation">
                            <?php
                            ft_next_homepage_field($settings, 'primary_color', 'Primary blue color', 'color');
                            ft_next_homepage_field($settings, 'secondary_color', 'Light brown accent', 'color');
                            ft_next_homepage_field($settings, 'background_color', 'Page background', 'color');
                            ft_next_homepage_field($settings, 'foreground_color', 'Text color', 'color');
                            ?>
                        </table>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Logo'); ?>
                        <div class="ft-next-field-stack">
                            <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                <label>
                                    Logo text
                                    <input name="logo_text" type="text" value="<?php echo esc_attr($settings['logo_text']); ?>">
                                </label>
                                <label>
                                    Logo size
                                    <input name="logo_size" type="text" value="<?php echo esc_attr($settings['logo_size']); ?>">
                                </label>
                            </div>
                            <div class="ft-next-repeater-item" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                                <div class="ft-next-media-panel">
                                    <span>Logo image</span>
                                    <div class="ft-next-image-field">
                                        <div class="ft-next-image-row">
                                            <input name="logo_image" type="text" class="ft-next-image-input" value="<?php echo esc_attr($settings['logo_image']); ?>">
                                            <button type="button" class="button ft-next-image-button">Select</button>
                                        </div>
                                        <img class="ft-next-image-preview" src="<?php echo esc_url($settings['logo_image']); ?>" alt="">
                                    </div>
                                </div>
                                <div class="ft-next-media-panel">
                                    <span>Favicon beside logo</span>
                                    <div class="ft-next-image-field">
                                        <div class="ft-next-image-row">
                                            <input name="favicon_image" type="text" class="ft-next-image-input" value="<?php echo esc_attr($settings['favicon_image']); ?>">
                                            <button type="button" class="button ft-next-image-button">Select</button>
                                        </div>
                                        <img class="ft-next-image-preview" src="<?php echo esc_url($settings['favicon_image']); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Button Style'); ?>
                        <table class="form-table" role="presentation">
                            <?php
                            ft_next_homepage_field($settings, 'button_radius', 'Border radius');
                            ft_next_homepage_field($settings, 'button_border_width', 'Border width');
                            ?>
                            <tr>
                                <th scope="row"><label for="button_border_style">Border style</label></th>
                                <td>
                                    <select id="button_border_style" name="button_border_style">
                                        <?php foreach (['solid', 'dashed', 'dotted', 'double', 'none'] as $style) : ?>
                                            <option value="<?php echo esc_attr($style); ?>" <?php selected($settings['button_border_style'], $style); ?>>
                                                <?php echo esc_html(ucfirst($style)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <?php
                            ft_next_homepage_field($settings, 'button_border_color', 'Border color', 'color');
                            ft_next_homepage_field($settings, 'button_font_weight', 'Font weight');
                            ft_next_homepage_field($settings, 'button_text_transform', 'Text transform');
                            ft_next_homepage_field($settings, 'button_padding_x', 'Horizontal padding');
                            ft_next_homepage_field($settings, 'button_padding_y', 'Vertical padding');
                            ft_next_homepage_field($settings, 'button_hover_mix', 'Hover color strength');
                            ?>
                            <tr>
                                <th scope="row">Examples</th>
                                <td>
                                    <p class="description">Use values like <code>8px</code>, <code>999px</code>, <code>600</code>, <code>uppercase</code>, or <code>88%</code>.</p>
                                </td>
                            </tr>
                        </table>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Header'); ?>
                        <div class="ft-next-inline-grid ft-next-inline-grid--header">
                            <label>
                                Phone
                                <input name="phone" type="text" value="<?php echo esc_attr($settings['phone']); ?>">
                            </label>
                            <label>
                                Email
                                <input name="email" type="text" value="<?php echo esc_attr($settings['email']); ?>">
                            </label>
                            <label>
                                Service area
                                <input name="service_area" type="text" value="<?php echo esc_attr($settings['service_area']); ?>">
                            </label>
                            <label>
                                Header button label
                                <input name="cta_label" type="text" value="<?php echo esc_attr($settings['cta_label']); ?>">
                            </label>
                        </div>
                        <div class="ft-next-inline-grid ft-next-inline-grid--2">
                            <?php
                            ft_next_homepage_field($settings, 'show_header', 'Show header', 'checkbox');
                            ft_next_homepage_field($settings, 'show_footer', 'Show footer', 'checkbox');
                            ?>
                        </div>
                        <table class="widefat striped ft-next-table">
                            <thead><tr><th>Name</th><th>Link</th></tr></thead>
                            <tbody>
                            <?php for ($i = 0; $i < 5; $i++) : $item = $settings['nav_items'][$i] ?? ['name' => '', 'href' => '']; ?>
                                <tr>
                                    <td><input name="nav_name[]" type="text" value="<?php echo esc_attr($item['name']); ?>"></td>
                                    <td><input name="nav_href[]" type="text" value="<?php echo esc_attr($item['href']); ?>"></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Hero'); ?>
                        <div class="ft-next-repeater">
                            <div class="ft-next-repeater-item ft-next-repeater-item--single">
                                <h3>Hero settings</h3>
                                <div class="ft-next-field-stack">
                                    <div class="ft-next-subgroup">
                                        <h4>Main content</h4>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Hero title
                                                <input name="hero_title" type="text" value="<?php echo esc_attr($settings['hero_title']); ?>">
                                            </label>
                                            <label>
                                                Hero highlighted text
                                                <input name="hero_highlight" type="text" value="<?php echo esc_attr($settings['hero_highlight']); ?>">
                                            </label>
                                        </div>
                                        <label>
                                            Hero paragraph
                                            <textarea name="hero_text" rows="4"><?php echo esc_textarea($settings['hero_text']); ?></textarea>
                                        </label>
                                    </div>

                                    <div class="ft-next-subgroup">
                                        <h4>Promo badge text</h4>
                                        <label>
                                            Promo badge
                                            <input name="hero_badge" type="text" value="<?php echo esc_attr($settings['hero_badge']); ?>">
                                        </label>
                                    </div>

                                    <div class="ft-next-subgroup">
                                        <h4>Promo badge style</h4>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--3" style="margin-bottom:0;">
                                            <label>
                                                Background color
                                                <?php ft_next_homepage_color_control($settings, 'hero_badge_bg_color'); ?>
                                            </label>
                                            <label>
                                                Text color
                                                <?php ft_next_homepage_color_control($settings, 'hero_badge_text_color'); ?>
                                            </label>
                                            <label>
                                                Font size
                                                <input name="hero_badge_font_size" type="text" value="<?php echo esc_attr($settings['hero_badge_font_size']); ?>">
                                            </label>
                                        </div>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Horizontal padding
                                                <input name="hero_badge_padding_x" type="text" value="<?php echo esc_attr($settings['hero_badge_padding_x']); ?>">
                                            </label>
                                            <label>
                                                Vertical padding
                                                <input name="hero_badge_padding_y" type="text" value="<?php echo esc_attr($settings['hero_badge_padding_y']); ?>">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="ft-next-subgroup">
                                        <h4>Promo badge animation</h4>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Animation color 1
                                                <?php ft_next_homepage_color_control($settings, 'hero_badge_animation_color_1'); ?>
                                            </label>
                                            <label>
                                                Animation color 2
                                                <?php ft_next_homepage_color_control($settings, 'hero_badge_animation_color_2'); ?>
                                            </label>
                                        </div>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Gradient location
                                                <input name="hero_badge_animation_location" type="text" value="<?php echo esc_attr($settings['hero_badge_animation_location']); ?>">
                                            </label>
                                            <label>
                                                Animation speed
                                                <input name="hero_badge_animation_speed" type="text" value="<?php echo esc_attr($settings['hero_badge_animation_speed']); ?>">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="ft-next-subgroup">
                                        <h4>Form text</h4>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Form title
                                                <input name="form_title" type="text" value="<?php echo esc_attr($settings['form_title']); ?>">
                                            </label>
                                            <label>
                                                Form subtitle
                                                <input name="form_subtitle" type="text" value="<?php echo esc_attr($settings['form_subtitle']); ?>">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="ft-next-media-panel">
                                    <div class="ft-next-subgroup">
                                        <h4>Background image</h4>
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <?php ft_next_homepage_field($settings, 'hero_show_background', 'Show background', 'checkbox'); ?>
                                            <?php ft_next_homepage_field($settings, 'hero_show_overlay', 'Show overlay', 'checkbox'); ?>
                                        </div>
                                        <label>
                                            Overlay transparency
                                            <input name="hero_overlay_opacity" type="text" value="<?php echo esc_attr($settings['hero_overlay_opacity']); ?>">
                                            <span class="description">Use 0 to 1. Example: <code>0.72</code>, <code>0.35</code>, or <code>0</code>.</span>
                                        </label>
                                        <div class="ft-next-image-field">
                                            <div class="ft-next-image-row">
                                                <input name="hero_image" type="text" class="ft-next-image-input" value="<?php echo esc_attr($settings['hero_image']); ?>">
                                                <button type="button" class="button ft-next-image-button">Select</button>
                                            </div>
                                            <img class="ft-next-image-preview" src="<?php echo esc_url($settings['hero_image']); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Section One'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'process'); ?>
                        <div class="ft-next-field-stack" style="margin-bottom:16px;">
                            <label>
                                Section title
                                <input name="process_title" type="text" value="<?php echo esc_attr($settings['process_title']); ?>">
                            </label>
                            <label>
                                Section paragraph
                                <textarea name="process_text" rows="4"><?php echo esc_textarea($settings['process_text']); ?></textarea>
                            </label>
                        </div>
                        <div class="ft-next-repeater">
                            <?php foreach ($settings['process_steps'] as $index => $step) : ?>
                                    <div class="ft-next-repeater-item">
                                        <h3>Step <?php echo esc_html((string) ($index + 1)); ?></h3>
                                    <div class="ft-next-field-stack">
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Title
                                                <input name="process_step_title[]" type="text" value="<?php echo esc_attr($step['title']); ?>">
                                            </label>
                                            <label>
                                                Button label
                                                <input name="process_step_button[]" type="text" value="<?php echo esc_attr($step['button']); ?>">
                                            </label>
                                        </div>
                                        <label>
                                            Description
                                            <textarea name="process_step_description[]" rows="4"><?php echo esc_textarea($step['description']); ?></textarea>
                                        </label>
                                    </div>
                                    <div class="ft-next-media-panel">
                                        <span>Image</span>
                                        <div class="ft-next-image-field">
                                            <div class="ft-next-image-row">
                                                <input name="process_step_image[]" type="text" class="ft-next-image-input" value="<?php echo esc_attr($step['image']); ?>">
                                                <button type="button" class="button ft-next-image-button">Select</button>
                                            </div>
                                            <img class="ft-next-image-preview" src="<?php echo esc_url($step['image']); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Section Two'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'comparison'); ?>
                        <div class="ft-next-field-stack" style="margin-bottom:16px;">
                            <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                <label>
                                    Section title
                                    <input name="comparison_title" type="text" value="<?php echo esc_attr($settings['comparison_title']); ?>">
                                </label>
                                <label>
                                    Button label
                                    <input name="comparison_button" type="text" value="<?php echo esc_attr($settings['comparison_button']); ?>">
                                </label>
                            </div>
                            <label>
                                Section paragraph
                                <textarea name="comparison_text" rows="4"><?php echo esc_textarea($settings['comparison_text']); ?></textarea>
                            </label>
                        </div>
                        <table class="form-table" role="presentation">
                            <tr>
                                <th scope="row"><label for="comparison_rows">Table rows</label></th>
                                <td>
                                    <textarea id="comparison_rows" name="comparison_rows" rows="10" class="large-text"><?php echo esc_textarea(implode("\n", $settings['comparison_rows'])); ?></textarea>
                                    <p class="description">One row per line. Each row gets a check mark under Floors Today.</p>
                                </td>
                            </tr>
                        </table>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Categories'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'category'); ?>
                        <div class="ft-next-field-stack" style="margin-bottom:16px;">
                            <label>
                                Section title
                                <input name="category_title" type="text" value="<?php echo esc_attr($settings['category_title']); ?>">
                            </label>
                            <label>
                                Section paragraph
                                <textarea name="category_text" rows="4"><?php echo esc_textarea($settings['category_text']); ?></textarea>
                            </label>
                        </div>
                        <div class="ft-next-repeater">
                            <?php for ($i = 0; $i < 5; $i++) : $cat = $settings['categories'][$i] ?? ['name' => '', 'slug' => '', 'description' => '', 'image' => '']; ?>
                                <div class="ft-next-repeater-item">
                                    <h3>Category <?php echo esc_html((string) ($i + 1)); ?></h3>
                                    <div class="ft-next-field-stack">
                                        <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                            <label>
                                                Name
                                                <input name="category_name[]" type="text" value="<?php echo esc_attr($cat['name']); ?>">
                                            </label>
                                            <label>
                                                Slug
                                                <input name="category_slug[]" type="text" value="<?php echo esc_attr($cat['slug']); ?>">
                                            </label>
                                        </div>
                                        <label>
                                            Description
                                            <textarea name="category_description[]" rows="4"><?php echo esc_textarea($cat['description']); ?></textarea>
                                        </label>
                                    </div>
                                    <div class="ft-next-media-panel">
                                        <span>Image</span>
                                        <div class="ft-next-image-field">
                                            <div class="ft-next-image-row">
                                                <input name="category_image[]" type="text" class="ft-next-image-input" value="<?php echo esc_attr($cat['image']); ?>">
                                                <button type="button" class="button ft-next-image-button">Select</button>
                                            </div>
                                            <img class="ft-next-image-preview" src="<?php echo esc_url($cat['image']); ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Guarantee'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'guarantee'); ?>
                        <div class="ft-next-repeater">
                            <div class="ft-next-repeater-item">
                                <h3>Guarantee content</h3>
                                <div class="ft-next-field-stack">
                                    <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                        <label>
                                            Title
                                            <input name="guarantee_title" type="text" value="<?php echo esc_attr($settings['guarantee_title']); ?>">
                                        </label>
                                        <label>
                                            Subtitle
                                            <input name="guarantee_subtitle" type="text" value="<?php echo esc_attr($settings['guarantee_subtitle']); ?>">
                                        </label>
                                    </div>
                                    <label>
                                        Text
                                        <textarea name="guarantee_text" rows="5"><?php echo esc_textarea($settings['guarantee_text']); ?></textarea>
                                    </label>
                                    <label>
                                        Link label
                                        <input name="guarantee_link" type="text" value="<?php echo esc_attr($settings['guarantee_link']); ?>">
                                    </label>
                                </div>
                                <div class="ft-next-media-panel">
                                    <span>Image</span>
                                    <div class="ft-next-image-field">
                                        <div class="ft-next-image-row">
                                            <input name="guarantee_image" type="text" class="ft-next-image-input" value="<?php echo esc_attr($settings['guarantee_image']); ?>">
                                            <button type="button" class="button ft-next-image-button">Select</button>
                                        </div>
                                        <img class="ft-next-image-preview" src="<?php echo esc_url($settings['guarantee_image']); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Deals'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'deals'); ?>
                        <div class="ft-next-inline-grid ft-next-inline-grid--2">
                            <label>
                                Badge
                                <input name="deals_badge" type="text" value="<?php echo esc_attr($settings['deals_badge']); ?>">
                            </label>
                            <label>
                                Page title
                                <input name="deals_title" type="text" value="<?php echo esc_attr($settings['deals_title']); ?>">
                            </label>
                        </div>

                        <div class="ft-next-field-stack" style="margin-bottom:16px;">
                            <label>
                                Intro text
                                <textarea name="deals_text" rows="3"><?php echo esc_textarea($settings['deals_text']); ?></textarea>
                            </label>
                            <label>
                                Body text
                                <textarea name="deals_body" rows="4"><?php echo esc_textarea($settings['deals_body']); ?></textarea>
                            </label>
                        </div>

                        <div class="ft-next-inline-grid ft-next-inline-grid--3">
                            <label>
                                Sale card title
                                <input name="deals_card_title" type="text" value="<?php echo esc_attr($settings['deals_card_title']); ?>">
                            </label>
                            <label>
                                Sale card subtitle
                                <input name="deals_card_subtitle" type="text" value="<?php echo esc_attr($settings['deals_card_subtitle']); ?>">
                            </label>
                            <label>
                                Button label
                                <input name="deals_button" type="text" value="<?php echo esc_attr($settings['deals_button']); ?>">
                            </label>
                        </div>

                        <table class="widefat striped ft-next-table">
                            <thead><tr><th>Offer title</th><th>Description</th></tr></thead>
                            <tbody>
                            <?php for ($i = 0; $i < 4; $i++) : $offer = $settings['offers'][$i] ?? ['title' => '', 'description' => '']; ?>
                                <tr>
                                    <td><input name="offer_title[]" type="text" value="<?php echo esc_attr($offer['title']); ?>"></td>
                                    <td><textarea name="offer_description[]" rows="3"><?php echo esc_textarea($offer['description']); ?></textarea></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('Testimonials'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'testimonials'); ?>
                        <div class="ft-next-field-stack" style="margin-bottom:16px;">
                            <label>
                                Section title
                                <input name="testimonials_title" type="text" value="<?php echo esc_attr($settings['testimonials_title']); ?>">
                            </label>
                            <label>
                                Section paragraph
                                <textarea name="testimonials_text" rows="4"><?php echo esc_textarea($settings['testimonials_text']); ?></textarea>
                            </label>
                        </div>
                        <table class="widefat striped ft-next-table">
                            <thead><tr><th>Name</th><th>Location</th><th>Floor type</th><th>Text</th></tr></thead>
                            <tbody>
                            <?php for ($i = 0; $i < 3; $i++) : $item = $settings['testimonials'][$i] ?? ['name' => '', 'location' => '', 'floorType' => '', 'text' => '']; ?>
                                <tr>
                                    <td><input name="testimonial_name[]" type="text" value="<?php echo esc_attr($item['name']); ?>"></td>
                                    <td><input name="testimonial_location[]" type="text" value="<?php echo esc_attr($item['location']); ?>"></td>
                                    <td><input name="testimonial_floor_type[]" type="text" value="<?php echo esc_attr($item['floorType']); ?>"></td>
                                    <td><textarea name="testimonial_text[]" rows="3"><?php echo esc_textarea($item['text']); ?></textarea></td>
                                </tr>
                            <?php endfor; ?>
                            </tbody>
                        </table>
                    <?php ft_next_homepage_card_close(); ?>

                    <?php ft_next_homepage_card_open('CTA and Footer'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'cta'); ?>
                        <?php ft_next_homepage_gradient_controls($settings, 'footer'); ?>
                        <div class="ft-next-field-stack">
                            <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                <label>
                                    CTA title
                                    <input name="cta_title" type="text" value="<?php echo esc_attr($settings['cta_title']); ?>">
                                </label>
                                <label>
                                    CTA subtitle
                                    <input name="cta_subtitle" type="text" value="<?php echo esc_attr($settings['cta_subtitle']); ?>">
                                </label>
                            </div>
                            <label>
                                CTA paragraph
                                <textarea name="cta_text" rows="4"><?php echo esc_textarea($settings['cta_text']); ?></textarea>
                            </label>
                            <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                <label>
                                    CTA button label
                                    <input name="cta_button" type="text" value="<?php echo esc_attr($settings['cta_button']); ?>">
                                </label>
                                <label>
                                    Newsletter title
                                    <input name="newsletter_title" type="text" value="<?php echo esc_attr($settings['newsletter_title']); ?>">
                                </label>
                            </div>
                            <div class="ft-next-inline-grid ft-next-inline-grid--2" style="margin-bottom:0;">
                                <label>
                                    Newsletter text
                                    <input name="newsletter_text" type="text" value="<?php echo esc_attr($settings['newsletter_text']); ?>">
                                </label>
                                <label>
                                    Newsletter button
                                    <input name="newsletter_button" type="text" value="<?php echo esc_attr($settings['newsletter_button']); ?>">
                                </label>
                            </div>
                            <label>
                                Footer about text
                                <textarea name="footer_about" rows="4"><?php echo esc_textarea($settings['footer_about']); ?></textarea>
                            </label>
                        </div>
                    <?php ft_next_homepage_card_close(); ?>
                </div>

                <aside class="ft-next-sidebar">
                    <div class="ft-next-preview" style="<?php echo esc_attr('--ft-primary:' . $settings['primary_color'] . ';--ft-secondary:' . $settings['secondary_color'] . ';--ft-bg:' . $settings['background_color'] . ';--ft-fg:' . $settings['foreground_color'] . ';'); ?>">
                        <div class="ft-next-preview__top">
                            <strong><?php echo esc_html($settings['logo_text']); ?></strong>
                            <p style="margin:8px 0 0;"><?php echo esc_html($settings['service_area']); ?></p>
                        </div>
                        <div class="ft-next-preview__body">
                            <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:var(--ft-secondary);"><?php echo esc_html($settings['hero_badge']); ?></p>
                            <h3 style="margin:0 0 8px;font-size:24px;line-height:1.15;color:var(--ft-fg);">
                                <?php echo esc_html($settings['hero_title']); ?>
                                <span style="color:var(--ft-secondary);"><?php echo esc_html($settings['hero_highlight']); ?></span>
                            </h3>
                            <p style="margin:0;color:var(--ft-fg);opacity:.78;"><?php echo esc_html($settings['hero_text']); ?></p>
                            <span class="ft-next-preview__button"><?php echo esc_html($settings['cta_label']); ?></span>
                        </div>
                    </div>

                    <div class="ft-next-actions">
                        <a class="button" href="<?php echo esc_url($frontend_url); ?>" target="_blank" rel="noopener">View Front End</a>
                        <?php submit_button('Save Settings', 'primary', 'submit', false); ?>
                    </div>
                </aside>
            </div>
        </form>
    </div>
    <?php
}

add_action('rest_api_init', function () {
    register_rest_route('floors-today/v1', '/homepage', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function () {
            return rest_ensure_response(ft_next_homepage_settings());
        },
    ]);
});
