<?php
/**
 * Plugin Name: Floors Today Inbox
 * Description: Homepage lead inbox and REST submissions for Floors Today.
 * Version: 1.0.0
 * Author: Faris
 */

if (!defined('ABSPATH')) {
    exit;
}

const FT_INBOX_POST_TYPE = 'ft_inbox_lead';
const FT_INBOX_REST_NAMESPACE = 'floors-today/v1';
const FT_INBOX_REST_ROUTE = '/inbox-leads';
const FT_INBOX_SETTINGS_OPTION = 'ft_inbox_settings';

function ft_inbox_allowed_statuses() {
    return [
        'new' => 'New',
        'contacted' => 'Contacted',
        'estimate_booked' => 'Estimate Booked',
        'closed' => 'Closed',
    ];
}

function ft_inbox_capability() {
    return current_user_can('manage_ft_inbox');
}

function ft_inbox_field_labels() {
    return [
        'full_name' => 'Full Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'postal_code' => 'Postal Code',
        'flooring_type' => 'Flooring Type',
        'property_type' => 'Property Type',
        'start_time' => 'Start Time',
        'source' => 'Source',
        'traffic_source' => 'Traffic Source',
        'utm_source' => 'UTM Source',
        'utm_medium' => 'UTM Medium',
        'utm_campaign' => 'UTM Campaign',
        'utm_content' => 'UTM Content',
        'utm_term' => 'UTM Term',
        'referrer_url' => 'Previous Website',
        'device_platform' => 'Device Platform',
        'page_url' => 'Page URL',
        'user_agent' => 'User Agent',
        'ip_address' => 'IP Address',
    ];
}

function ft_inbox_default_email_template() {
    return "New homepage lead received.\n\n"
        . "Name: {full_name}\n"
        . "Phone: {phone}\n"
        . "Email: {email}\n"
        . "Postal Code: {postal_code}\n"
        . "Flooring: {flooring_type}\n"
        . "Property Type: {property_type}\n"
        . "Start Time: {start_time}\n"
        . "Source: {source}\n\n"
        . "Open lead: {lead_url}\n";
}

function ft_inbox_default_client_email_template() {
    return "<h2>Thank you, {full_name}</h2>\n"
        . "<p>We received your free in-home estimate request.</p>\n"
        . "<p>A Floors Today specialist will contact you shortly using the phone number or email you provided.</p>\n"
        . "<p><strong>Flooring:</strong> {flooring_type}<br>\n"
        . "<strong>Property:</strong> {property_type}<br>\n"
        . "<strong>Preferred timing:</strong> {start_time}</p>\n"
        . "<p>Floors Today</p>";
}

function ft_inbox_default_settings() {
    return [
        'notifications_enabled' => '1',
        'notification_recipients' => get_option('admin_email'),
        'notification_subject' => 'New Floors Today Lead: {full_name}',
        'notification_template' => ft_inbox_default_email_template(),
        'from_name' => get_bloginfo('name'),
        'from_email' => 'info@floorstoday.ca',
        'reply_to_customer' => '1',
        'client_notifications_enabled' => '1',
        'client_subject' => 'We received your Floors Today estimate request',
        'client_template' => ft_inbox_default_client_email_template(),
        'client_from_name' => get_bloginfo('name'),
        'client_from_email' => 'info@floorstoday.ca',
    ];
}

function ft_inbox_settings() {
    $saved = get_option(FT_INBOX_SETTINGS_OPTION, []);
    return array_replace(ft_inbox_default_settings(), is_array($saved) ? $saved : []);
}

function ft_inbox_template_variables() {
    return [
        'lead_id',
        'lead_url',
        'full_name',
        'email',
        'phone',
        'postal_code',
        'flooring_type',
        'property_type',
        'start_time',
        'source',
        'page_url',
        'date',
        'status',
    ];
}

function ft_inbox_render_template($template, $lead) {
    $replacements = [];

    foreach (ft_inbox_template_variables() as $variable) {
        $replacements['{' . $variable . '}'] = isset($lead[$variable]) ? (string) $lead[$variable] : '';
    }

    return strtr($template, $replacements);
}

function ft_inbox_sanitize_lead_payload($request) {
    $params = $request instanceof WP_REST_Request ? $request->get_json_params() : (array) $request;

    if (!is_array($params)) {
        $params = [];
    }

    return [
        'full_name' => sanitize_text_field($params['fullName'] ?? $params['full_name'] ?? ''),
        'email' => sanitize_email($params['email'] ?? ''),
        'phone' => sanitize_text_field($params['phone'] ?? ''),
        'postal_code' => sanitize_text_field($params['postalCode'] ?? $params['postal_code'] ?? ''),
        'flooring_type' => sanitize_text_field($params['flooringType'] ?? $params['flooring_type'] ?? ''),
        'property_type' => sanitize_text_field($params['propertyType'] ?? $params['property_type'] ?? ''),
        'start_time' => sanitize_text_field($params['startTime'] ?? $params['start_time'] ?? ''),
        'source' => sanitize_text_field($params['source'] ?? 'Homepage estimate form'),
        'traffic_source' => sanitize_text_field($params['trafficSource'] ?? $params['traffic_source'] ?? 'Direct'),
        'utm_source' => sanitize_text_field($params['utmSource'] ?? $params['utm_source'] ?? ''),
        'utm_medium' => sanitize_text_field($params['utmMedium'] ?? $params['utm_medium'] ?? ''),
        'utm_campaign' => sanitize_text_field($params['utmCampaign'] ?? $params['utm_campaign'] ?? ''),
        'utm_content' => sanitize_text_field($params['utmContent'] ?? $params['utm_content'] ?? ''),
        'utm_term' => sanitize_text_field($params['utmTerm'] ?? $params['utm_term'] ?? ''),
        'referrer_url' => esc_url_raw($params['referrerUrl'] ?? $params['referrer_url'] ?? ''),
        'device_platform' => sanitize_text_field($params['devicePlatform'] ?? $params['device_platform'] ?? ''),
        'page_url' => esc_url_raw($params['pageUrl'] ?? $params['page_url'] ?? ''),
        'honeypot' => sanitize_text_field($params['ftInboxTrap'] ?? ''),
    ];
}

function ft_inbox_unread_count() {
    $query = new WP_Query([
        'post_type' => FT_INBOX_POST_TYPE,
        'post_status' => 'private',
        'fields' => 'ids',
        'posts_per_page' => 1,
        'meta_query' => [
            [
                'key' => '_ft_inbox_unread',
                'value' => '1',
            ],
        ],
    ]);

    return (int) $query->found_posts;
}

add_action('init', function () {
    register_post_type(FT_INBOX_POST_TYPE, [
        'labels' => [
            'name' => 'Inbox Leads',
            'singular_name' => 'Inbox Lead',
        ],
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'supports' => ['title'],
        'capability_type' => 'post',
    ]);

    foreach (['administrator', 'shop_manager', 'sales_admin'] as $role_name) {
        $role = get_role($role_name);

        if ($role && !$role->has_cap('manage_ft_inbox')) {
            $role->add_cap('manage_ft_inbox');
        }
    }
});

add_action('admin_menu', function () {
    add_menu_page(
        'Inbox',
        'Inbox',
        'manage_ft_inbox',
        'ft-inbox',
        'ft_inbox_render_admin_page',
        'dashicons-email-alt2',
        57
    );

    add_options_page(
        'Form Settings',
        'Form Settings',
        'manage_options',
        'ft-form-settings',
        'ft_inbox_render_settings_page'
    );
});

add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['toplevel_page_ft-inbox', 'settings_page_ft-form-settings'], true)) {
        return;
    }

    $base = plugin_dir_url(__FILE__);
    $dir = __DIR__;

    wp_enqueue_style('ft-inbox', $base . 'inbox.css', [], filemtime($dir . '/inbox.css'));

    if (current_user_can('manage_options')) {
        wp_enqueue_style('ft-inbox-admin', $base . 'inbox-admin.css', ['ft-inbox'], filemtime($dir . '/inbox-admin.css'));
    } else {
        wp_enqueue_style('ft-inbox-sales', $base . 'inbox-sales.css', ['ft-inbox'], filemtime($dir . '/inbox-sales.css'));
    }
});

add_action('admin_footer', function () {
    if (!ft_inbox_capability()) {
        return;
    }

    $count = ft_inbox_unread_count();
    ?>
    <script>
      (function () {
        function syncInboxCount() {
          var inboxLinks = document.querySelectorAll('#adminmenu a[href*="page=ft-inbox"]');

          document.querySelectorAll('#adminmenu .ft-inbox-menu-count').forEach(function (badge) {
            badge.remove();
          });

          inboxLinks.forEach(function (link) {
            var menuItem = link.closest('li');
            var scope = menuItem || link;

            scope.querySelectorAll('.awaiting-mod, .update-plugins').forEach(function (badge) {
              badge.remove();
            });
          });

          if (<?php echo (int) $count; ?> < 1 || !inboxLinks.length) {
            return;
          }

          var inboxLink = inboxLinks[0];
          var menuLabel = inboxLink.querySelector('.wp-menu-name') || inboxLink;
          var badge = document.createElement('span');
          badge.className = 'ft-inbox-menu-count';
          badge.textContent = <?php echo wp_json_encode((string) $count); ?>;
          badge.setAttribute('aria-label', <?php echo wp_json_encode($count . ' unread leads'); ?>);
          menuLabel.appendChild(badge);
        }

        if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', syncInboxCount);
        } else {
          syncInboxCount();
        }
      }());
    </script>
    <?php
});

add_action('rest_api_init', function () {
    register_rest_route(FT_INBOX_REST_NAMESPACE, FT_INBOX_REST_ROUTE, [
        'methods' => 'POST',
        'permission_callback' => '__return_true',
        'callback' => 'ft_inbox_handle_rest_submission',
    ]);
});

function ft_inbox_handle_rest_submission(WP_REST_Request $request) {
    $data = ft_inbox_sanitize_lead_payload($request);

    if ($data['honeypot'] !== '') {
        return new WP_Error('ft_inbox_spam', 'Unable to send this request.', ['status' => 400]);
    }

    $ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
    $rate_key = 'ft_inbox_rate_' . md5($ip);

    if (get_transient($rate_key)) {
        return new WP_Error('ft_inbox_rate_limited', 'Please wait a moment before sending another request.', ['status' => 429]);
    }

    $name_parts = preg_split('/\s+/', trim($data['full_name']), -1, PREG_SPLIT_NO_EMPTY);

    if (count($name_parts) < 2) {
        return new WP_Error('ft_inbox_invalid_name', 'Please enter your first and last name.', ['status' => 400]);
    }

    if ($data['phone'] === '' || $data['phone'] === '+1' || $data['email'] === '' || !is_email($data['email'])) {
        return new WP_Error('ft_inbox_invalid', 'Please enter your name, phone, and a valid email.', ['status' => 400]);
    }

    $title = sprintf(
        '%s - %s',
        $data['full_name'],
        current_time('M j, Y g:ia')
    );

    $lead_id = wp_insert_post([
        'post_type' => FT_INBOX_POST_TYPE,
        'post_status' => 'private',
        'post_title' => $title,
    ], true);

    if (is_wp_error($lead_id)) {
        return new WP_Error('ft_inbox_save_failed', 'Unable to save this request.', ['status' => 500]);
    }

    $data['ip_address'] = $ip;
    $data['user_agent'] = sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? '');

    foreach ($data as $key => $value) {
        if ($key !== 'honeypot') {
            update_post_meta($lead_id, '_ft_inbox_' . $key, $value);
        }
    }

    update_post_meta($lead_id, '_ft_inbox_status', 'new');
    update_post_meta($lead_id, '_ft_inbox_notes', '');
    update_post_meta($lead_id, '_ft_inbox_unread', '1');
    set_transient($rate_key, 1, MINUTE_IN_SECONDS);
    ft_inbox_send_notification($lead_id);

    return rest_ensure_response([
        'ok' => true,
        'leadId' => $lead_id,
        'message' => 'Your request was received.',
    ]);
}

function ft_inbox_send_notification($lead_id) {
    $settings = ft_inbox_settings();
    $lead = ft_inbox_get_lead($lead_id);

    if (!$lead) {
        return;
    }

    if ($settings['notifications_enabled'] === '1') {
        $recipients = preg_split('/[,;\r\n]+/', $settings['notification_recipients']);
        $recipients = array_values(array_filter(array_map('sanitize_email', $recipients), 'is_email'));

        if ($recipients) {
            $headers = ['Content-Type: text/html; charset=UTF-8'];

            if ($settings['from_name'] !== '' && is_email($settings['from_email'])) {
                $headers[] = 'From: ' . sanitize_text_field($settings['from_name']) . ' <' . sanitize_email($settings['from_email']) . '>';
            }

            if ($settings['reply_to_customer'] === '1' && is_email($lead['email'])) {
                $headers[] = 'Reply-To: ' . sanitize_text_field($lead['full_name']) . ' <' . $lead['email'] . '>';
            }

            ft_inbox_send_mail(
                $lead_id,
                'admin',
                $recipients,
                ft_inbox_render_template($settings['notification_subject'], $lead),
                wpautop(ft_inbox_render_template($settings['notification_template'], $lead)),
                $headers
            );
        }
    }

    if ($settings['client_notifications_enabled'] === '1' && is_email($lead['email'])) {
        $client_headers = ['Content-Type: text/html; charset=UTF-8'];

        if ($settings['client_from_name'] !== '' && is_email($settings['client_from_email'])) {
            $client_headers[] = 'From: ' . sanitize_text_field($settings['client_from_name']) . ' <' . sanitize_email($settings['client_from_email']) . '>';
        }

        ft_inbox_send_mail(
            $lead_id,
            'client',
            $lead['email'],
            ft_inbox_render_template($settings['client_subject'], $lead),
            wpautop(ft_inbox_render_template($settings['client_template'], $lead)),
            $client_headers
        );
    }
}

function ft_inbox_send_mail($lead_id, $type, $to, $subject, $message, $headers) {
    $error_message = '';
    $capture_error = function ($error) use (&$error_message) {
        if (is_wp_error($error)) {
            $error_message = $error->get_error_message();
        }
    };

    add_action('wp_mail_failed', $capture_error);
    $accepted = wp_mail($to, $subject, $message, $headers);
    remove_action('wp_mail_failed', $capture_error);

    update_post_meta($lead_id, '_ft_inbox_' . $type . '_email_status', $accepted ? 'accepted' : 'failed');
    update_post_meta($lead_id, '_ft_inbox_' . $type . '_email_error', $accepted ? '' : $error_message);
    update_post_meta($lead_id, '_ft_inbox_' . $type . '_email_checked_at', current_time('mysql'));

    return $accepted;
}

function ft_inbox_get_lead($lead_id) {
    $post = get_post($lead_id);

    if (!$post || $post->post_type !== FT_INBOX_POST_TYPE) {
        return null;
    }

    $lead = [
        'id' => $post->ID,
        'lead_id' => $post->ID,
        'lead_url' => admin_url('admin.php?page=ft-inbox&lead=' . (int) $post->ID),
        'title' => $post->post_title,
        'date' => get_the_date('M j, Y g:ia', $post),
        'status' => get_post_meta($post->ID, '_ft_inbox_status', true) ?: 'new',
        'unread' => get_post_meta($post->ID, '_ft_inbox_unread', true) === '1',
        'notes' => get_post_meta($post->ID, '_ft_inbox_notes', true),
    ];

    foreach (array_keys(ft_inbox_field_labels()) as $key) {
        $lead[$key] = get_post_meta($post->ID, '_ft_inbox_' . $key, true);
    }

    return $lead;
}

function ft_inbox_handle_settings_save() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (($_POST['ft_inbox_action'] ?? '') !== 'save_settings') {
        return;
    }

    check_admin_referer('ft_inbox_save_settings', 'ft_inbox_settings_nonce');

    $defaults = ft_inbox_default_settings();
    $subject = sanitize_text_field(wp_unslash($_POST['notification_subject'] ?? ''));
    $template = wp_kses_post(wp_unslash($_POST['notification_template'] ?? ''));
    $client_subject = sanitize_text_field(wp_unslash($_POST['client_subject'] ?? ''));
    $client_template = wp_kses_post(wp_unslash($_POST['client_template'] ?? ''));

    update_option(FT_INBOX_SETTINGS_OPTION, [
        'notifications_enabled' => isset($_POST['notifications_enabled']) ? '1' : '0',
        'notification_recipients' => sanitize_textarea_field(wp_unslash($_POST['notification_recipients'] ?? '')),
        'notification_subject' => $subject !== '' ? $subject : $defaults['notification_subject'],
        'notification_template' => $template !== '' ? $template : $defaults['notification_template'],
        'from_name' => sanitize_text_field(wp_unslash($_POST['from_name'] ?? '')),
        'from_email' => sanitize_email(wp_unslash($_POST['from_email'] ?? $defaults['from_email'])),
        'reply_to_customer' => isset($_POST['reply_to_customer']) ? '1' : '0',
        'client_notifications_enabled' => isset($_POST['client_notifications_enabled']) ? '1' : '0',
        'client_subject' => $client_subject !== '' ? $client_subject : $defaults['client_subject'],
        'client_template' => $client_template !== '' ? $client_template : $defaults['client_template'],
        'client_from_name' => sanitize_text_field(wp_unslash($_POST['client_from_name'] ?? '')),
        'client_from_email' => sanitize_email(wp_unslash($_POST['client_from_email'] ?? $defaults['client_from_email'])),
    ]);

    wp_safe_redirect(admin_url('options-general.php?page=ft-form-settings&updated=1'));
    exit;
}

add_action('admin_init', 'ft_inbox_handle_settings_save');

function ft_inbox_handle_admin_actions() {
    if (!ft_inbox_capability()) {
        return;
    }

    if (!isset($_POST['ft_inbox_action'], $_POST['ft_inbox_nonce'])) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ft_inbox_nonce'])), 'ft_inbox_action')) {
        return;
    }

    $lead_id = absint($_POST['lead_id'] ?? 0);
    $lead = ft_inbox_get_lead($lead_id);

    if (!$lead) {
        return;
    }

    $action = sanitize_text_field(wp_unslash($_POST['ft_inbox_action']));

    if ($action === 'update_lead') {
        $status = sanitize_text_field(wp_unslash($_POST['status'] ?? 'new'));
        $statuses = ft_inbox_allowed_statuses();

        if (!isset($statuses[$status])) {
            $status = 'new';
        }

        update_post_meta($lead_id, '_ft_inbox_status', $status);
        update_post_meta($lead_id, '_ft_inbox_notes', sanitize_textarea_field(wp_unslash($_POST['notes'] ?? '')));
        wp_safe_redirect(admin_url('admin.php?page=ft-inbox&lead=' . $lead_id . '&updated=1'));
        exit;
    }
}

add_action('admin_init', 'ft_inbox_handle_admin_actions');

function ft_inbox_render_admin_page() {
    if (!ft_inbox_capability()) {
        wp_die(esc_html__('You do not have permission to view this page.', 'floors-today'));
    }

    $lead_id = absint($_GET['lead'] ?? 0);

    echo '<div class="wrap ft-inbox-wrap">';

    if ($lead_id) {
        ft_inbox_render_detail($lead_id);
    } else {
        ft_inbox_render_list();
    }

    echo '</div>';
}

function ft_inbox_render_list() {
    $status = sanitize_text_field(wp_unslash($_GET['status'] ?? ''));
    $search = sanitize_text_field(wp_unslash($_GET['s'] ?? ''));
    $statuses = ft_inbox_allowed_statuses();

    $args = [
        'post_type' => FT_INBOX_POST_TYPE,
        'post_status' => 'private',
        'posts_per_page' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if ($search !== '') {
        $args['s'] = $search;
    }

    if (isset($statuses[$status])) {
        $args['meta_query'] = [
            [
                'key' => '_ft_inbox_status',
                'value' => $status,
            ],
        ];
    }

    $query = new WP_Query($args);

    echo '<div class="ft-inbox-hero">';
    echo '<div><span class="ft-inbox-eyebrow">Sales workspace</span><h1>Inbox</h1><p>Review estimate requests and keep every follow-up moving.</p></div>';
    echo '<div class="ft-inbox-hero__count"><strong>' . esc_html((string) ft_inbox_unread_count()) . '</strong><span>Unread leads</span></div>';
    echo '</div>';

    echo '<nav class="ft-inbox-status-nav" aria-label="Lead status filters">';
    echo '<a class="' . ($status === '' ? 'is-active' : '') . '" href="' . esc_url(admin_url('admin.php?page=ft-inbox')) . '">All</a>';
    foreach ($statuses as $key => $label) {
        $status_url = add_query_arg(['page' => 'ft-inbox', 'status' => $key], admin_url('admin.php'));
        echo '<a class="' . ($status === $key ? 'is-active' : '') . '" href="' . esc_url($status_url) . '">' . esc_html($label) . '</a>';
    }
    echo '</nav>';

    echo '<form class="ft-inbox-filters" method="get">';
    echo '<input type="hidden" name="page" value="ft-inbox">';
    echo '<div class="ft-inbox-search"><span class="dashicons dashicons-search" aria-hidden="true"></span><input type="search" name="s" value="' . esc_attr($search) . '" placeholder="Search name, phone, or email"></div>';
    echo '<select name="status"><option value="">All statuses</option>';
    foreach ($statuses as $key => $label) {
        echo '<option value="' . esc_attr($key) . '"' . selected($status, $key, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    echo '<button class="button button-primary">Filter</button>';
    echo '</form>';

    echo '<div class="ft-inbox-list">';

    if (!$query->have_posts()) {
        echo '<div class="ft-inbox-empty">No leads found.</div>';
    }

    while ($query->have_posts()) {
        $query->the_post();
        $lead = ft_inbox_get_lead(get_the_ID());
        $detail_url = admin_url('admin.php?page=ft-inbox&lead=' . (int) $lead['id']);

        $read_class = $lead['unread'] ? ' is-unread' : ' is-read';
        echo '<a class="ft-inbox-card ft-status-' . esc_attr($lead['status']) . esc_attr($read_class) . '" href="' . esc_url($detail_url) . '">';
        echo '<span class="ft-inbox-avatar">' . esc_html(strtoupper(substr($lead['full_name'] ?: '?', 0, 1))) . '</span>';
        echo '<div class="ft-inbox-card__main">';
        echo '<strong>' . esc_html($lead['full_name'] ?: 'Unknown lead') . '</strong>';
        echo '<span>' . esc_html($lead['flooring_type'] ?: 'Flooring not selected') . ' · ' . esc_html($lead['property_type'] ?: 'Property not selected') . '</span>';
        echo '</div>';
        echo '<div class="ft-inbox-card__meta">';
        echo '<span>' . esc_html($lead['phone']) . ' · ' . esc_html($lead['postal_code']) . '</span>';
        echo '<span>' . esc_html($lead['date']) . '</span>';
        echo '</div>';
        echo '<div class="ft-inbox-card__status">';
        echo '<em>' . esc_html(ft_inbox_allowed_statuses()[$lead['status']] ?? 'New') . '</em>';
        echo '<span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>';
        echo '</div>';
        echo '</a>';
    }

    wp_reset_postdata();
    echo '</div>';
}

function ft_inbox_render_detail($lead_id) {
    $lead = ft_inbox_get_lead($lead_id);
    $statuses = ft_inbox_allowed_statuses();

    if (!$lead) {
        echo '<h1>Lead not found</h1>';
        return;
    }

    if ($lead['unread']) {
        update_post_meta($lead_id, '_ft_inbox_unread', '0');
        $lead['unread'] = false;
    }

    echo '<p><a href="' . esc_url(admin_url('admin.php?page=ft-inbox')) . '">&larr; Back to Inbox</a></p>';
    echo '<div class="ft-inbox-detail">';
    echo '<section class="ft-inbox-panel ft-inbox-panel--primary">';
    echo '<div class="ft-inbox-detail__head">';
    echo '<div class="ft-inbox-detail__identity"><span class="ft-inbox-avatar ft-inbox-avatar--large">' . esc_html(strtoupper(substr($lead['full_name'] ?: '?', 0, 1))) . '</span><div><span class="ft-inbox-eyebrow">Estimate request</span><h1>' . esc_html($lead['full_name'] ?: 'Inbox Lead') . '</h1><p>Received ' . esc_html($lead['date']) . '</p></div></div>';
    echo '<span class="ft-inbox-pill ft-status-' . esc_attr($lead['status']) . '">' . esc_html($statuses[$lead['status']] ?? 'New') . '</span>';
    echo '</div>';

    echo '<div class="ft-inbox-quick-actions">';
    if ($lead['phone']) {
        echo '<a class="button button-primary" href="tel:' . esc_attr(preg_replace('/[^\d+]/', '', $lead['phone'])) . '"><span class="dashicons dashicons-phone"></span> Call</a>';
    }
    if ($lead['email']) {
        echo '<a class="button" href="mailto:' . esc_attr($lead['email']) . '"><span class="dashicons dashicons-email-alt"></span> Email</a>';
    }
    echo '</div>';

    $tracking_fields = [
        'source',
        'traffic_source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'referrer_url',
        'device_platform',
        'page_url',
    ];

    echo '<div class="ft-inbox-data-grid">';
    foreach (ft_inbox_field_labels() as $key => $label) {
        if (in_array($key, array_merge(['user_agent', 'ip_address'], $tracking_fields), true)) {
            continue;
        }

        echo '<div><span>' . esc_html($label) . '</span><strong>' . esc_html($lead[$key] ?: '-') . '</strong></div>';
    }

    echo '<h2 class="ft-inbox-data-grid__heading">Tracking Information</h2>';
    foreach ($tracking_fields as $key) {
        $label = ft_inbox_field_labels()[$key] ?? $key;
        echo '<div><span>' . esc_html($label) . '</span><strong>' . esc_html($lead[$key] ?: '-') . '</strong></div>';
    }
    echo '</div>';
    echo '</section>';

    echo '<aside class="ft-inbox-panel">';
    echo '<form method="post">';
    wp_nonce_field('ft_inbox_action', 'ft_inbox_nonce');
    echo '<input type="hidden" name="ft_inbox_action" value="update_lead">';
    echo '<input type="hidden" name="lead_id" value="' . esc_attr((string) $lead['id']) . '">';
    echo '<label>Status<select name="status">';
    foreach ($statuses as $key => $label) {
        echo '<option value="' . esc_attr($key) . '"' . selected($lead['status'], $key, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select></label>';
    echo '<label>Internal notes<textarea name="notes" rows="9">' . esc_textarea($lead['notes']) . '</textarea></label>';
    echo '<button class="button button-primary button-large">Save Lead</button>';
    echo '</form>';
    echo '</aside>';
    echo '</div>';
}

function ft_inbox_render_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('You do not have permission to view this page.', 'floors-today'));
    }

    $settings = ft_inbox_settings();

    echo '<div class="wrap ft-inbox-wrap ft-inbox-settings">';
    echo '<div class="ft-inbox-hero">';
    echo '<div><span class="ft-inbox-eyebrow">Homepage lead form</span><h1>Form Settings</h1><p>Configure Inbox email notifications and the message template.</p></div>';
    echo '<a class="button" href="' . esc_url(admin_url('admin.php?page=ft-inbox')) . '">Open Inbox</a>';
    echo '</div>';

    if (isset($_GET['updated'])) {
        echo '<div class="notice notice-success is-dismissible"><p>Form settings saved.</p></div>';
    }

    echo '<div class="ft-inbox-settings-grid">';
    echo '<section class="ft-inbox-panel">';
    echo '<form method="post">';
    wp_nonce_field('ft_inbox_save_settings', 'ft_inbox_settings_nonce');
    echo '<input type="hidden" name="ft_inbox_action" value="save_settings">';

    echo '<label class="ft-inbox-check"><input type="checkbox" name="notifications_enabled" value="1" ' . checked($settings['notifications_enabled'], '1', false) . '> <span>Enable new lead email notifications</span></label>';

    echo '<label>Notification recipients';
    echo '<textarea name="notification_recipients" rows="4" placeholder="sales@example.com, manager@example.com">' . esc_textarea($settings['notification_recipients']) . '</textarea>';
    echo '<small>Enter multiple addresses using commas or separate lines.</small>';
    echo '</label>';

    echo '<label>Email subject';
    echo '<input type="text" name="notification_subject" value="' . esc_attr($settings['notification_subject']) . '">';
    echo '</label>';

    echo '<label>From name';
    echo '<input type="text" name="from_name" value="' . esc_attr($settings['from_name']) . '">';
    echo '</label>';

    echo '<label class="ft-inbox-check"><input type="checkbox" name="reply_to_customer" value="1" ' . checked($settings['reply_to_customer'], '1', false) . '> <span>Reply directly to the customer when responding</span></label>';

    echo '<label>Email template';
    echo '<textarea name="notification_template" rows="16">' . esc_textarea($settings['notification_template']) . '</textarea>';
    echo '</label>';

    echo '<button class="button button-primary button-large">Save Form Settings</button>';
    echo '</form>';
    echo '</section>';

    echo '<aside class="ft-inbox-panel ft-inbox-settings-help">';
    echo '<h2>Template variables</h2>';
    echo '<p>Use these placeholders in the email subject or template.</p>';
    echo '<div class="ft-inbox-token-grid">';
    foreach (ft_inbox_template_variables() as $variable) {
        echo '<code>{' . esc_html($variable) . '}</code>';
    }
    echo '</div>';
    echo '<h2>Email delivery</h2>';
    echo '<p>Messages are sent through WordPress <code>wp_mail()</code>. Configure SMTP on CloudPanel for reliable live delivery.</p>';
    echo '</aside>';
    echo '</div>';
    echo '</div>';
}
