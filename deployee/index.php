<?php
declare(strict_types=1);

define('FT_DEPLOYEE_ROOT', dirname(__DIR__));
define('FT_DEPLOYEE_STORAGE', __DIR__ . DIRECTORY_SEPARATOR . 'storage');
define('FT_DEPLOYEE_BACKUPS', FT_DEPLOYEE_STORAGE . DIRECTORY_SEPARATOR . 'backups');
define('FT_DEPLOYEE_SETTINGS', FT_DEPLOYEE_STORAGE . DIRECTORY_SEPARATOR . 'settings.json');

require_once FT_DEPLOYEE_ROOT . DIRECTORY_SEPARATOR . 'wp-load.php';

if (!is_user_logged_in()) {
    auth_redirect();
}

if (!current_user_can('manage_options')) {
    wp_die('You need WordPress administrator access to use Deployee.', 'Access denied', ['response' => 403]);
}

function ft_deployee_ensure_storage(): void {
    foreach ([FT_DEPLOYEE_STORAGE, FT_DEPLOYEE_BACKUPS] as $directory) {
        if (!is_dir($directory)) {
            wp_mkdir_p($directory);
        }
    }
}

function ft_deployee_defaults(): array {
    return [
        'project_name' => get_bloginfo('name') ?: 'WordPress Project',
        'local_url' => home_url('/'),
        'remote_url' => 'https://staging.floorstoday.ca/',
        'remote_path' => '/home/cloudpanel/htdocs/staging.floorstoday.ca',
        'git_branch' => 'main',
        'wp_cli_path' => FT_DEPLOYEE_ROOT . DIRECTORY_SEPARATOR . 'wp-cli.phar',
        'php_binary' => PHP_BINARY,
        'keep_backups' => 10,
    ];
}

function ft_deployee_systems(): array {
    return [
        [
            'id' => 'wordpress-deploy',
            'name' => 'WordPress Deploy',
            'description' => 'Code, database, and backups',
            'href' => home_url('/deployee/'),
            'icon' => 'WP',
            'active' => true,
        ],
    ];
}

function ft_deployee_settings(): array {
    $settings = ft_deployee_defaults();

    if (!is_file(FT_DEPLOYEE_SETTINGS)) {
        return $settings;
    }

    $saved = json_decode((string) file_get_contents(FT_DEPLOYEE_SETTINGS), true);
    return is_array($saved) ? array_merge($settings, $saved) : $settings;
}

function ft_deployee_save_settings(array $settings): bool {
    ft_deployee_ensure_storage();
    return false !== file_put_contents(
        FT_DEPLOYEE_SETTINGS,
        wp_json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

function ft_deployee_normalize_url(string $url): string {
    $url = esc_url_raw(trim($url));
    return $url === '' ? '' : trailingslashit($url);
}

function ft_deployee_command_available(): bool {
    $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
    return function_exists('proc_open') && !in_array('proc_open', $disabled, true);
}

function ft_deployee_run(array $parts): array {
    if (!ft_deployee_command_available()) {
        return ['ok' => false, 'output' => 'PHP proc_open is unavailable on this server. Run the displayed CLI command instead.'];
    }

    $command = implode(' ', array_map('escapeshellarg', $parts));
    $descriptors = [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $process = proc_open($command, $descriptors, $pipes, FT_DEPLOYEE_ROOT);

    if (!is_resource($process)) {
        return ['ok' => false, 'output' => 'Unable to start the deployment command.'];
    }

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit_code = proc_close($process);

    return [
        'ok' => $exit_code === 0,
        'output' => trim($stdout . PHP_EOL . $stderr),
        'command' => $command,
    ];
}

function ft_deployee_wp_cli(array $arguments, array $settings): array {
    return ft_deployee_run(array_merge([
        $settings['php_binary'],
        $settings['wp_cli_path'],
    ], $arguments, [
        '--path=' . FT_DEPLOYEE_ROOT,
        '--no-color',
    ]));
}

function ft_deployee_deploy_code(array $settings): array {
    $git = [
        'git',
        '-c',
        'safe.directory=' . str_replace('\\', '/', FT_DEPLOYEE_ROOT),
    ];
    $status = ft_deployee_run(array_merge($git, ['status', '--porcelain']));

    if (!$status['ok']) {
        return $status;
    }

    if (trim((string) $status['output']) !== '') {
        return [
            'ok' => false,
            'output' => "Code was not pushed because the project has uncommitted changes.\n\nCommit the changes first, then click Deploy Code again.",
        ];
    }

    return ft_deployee_run(array_merge($git, [
        'push',
        'origin',
        $settings['git_branch'],
    ]));
}

function ft_deployee_backup_name(string $type): string {
    $site = sanitize_title(get_bloginfo('name') ?: 'wordpress');
    return sprintf('%s-%s-%s.sql', $site, $type, gmdate('Ymd-His'));
}

function ft_deployee_export_database(array $settings, bool $for_remote): array {
    ft_deployee_ensure_storage();
    $filename = ft_deployee_backup_name($for_remote ? 'deploy' : 'backup');
    $path = FT_DEPLOYEE_BACKUPS . DIRECTORY_SEPARATOR . $filename;

    if ($for_remote) {
        if ($settings['local_url'] === '' || $settings['remote_url'] === '') {
            return ['ok' => false, 'output' => 'Local URL and server URL are required.'];
        }

        $result = ft_deployee_wp_cli([
            'search-replace',
            untrailingslashit($settings['local_url']),
            untrailingslashit($settings['remote_url']),
            '--all-tables-with-prefix',
            '--skip-columns=guid',
            '--export=' . $path,
        ], $settings);
    } else {
        $result = ft_deployee_wp_cli([
            'db',
            'export',
            $path,
            '--add-drop-table',
        ], $settings);
    }

    if ($result['ok'] && is_file($path)) {
        $result['file'] = $filename;
        ft_deployee_prune_backups((int) $settings['keep_backups']);
    }

    return $result;
}

function ft_deployee_prune_backups(int $keep): void {
    $keep = max(1, min(50, $keep));
    $files = glob(FT_DEPLOYEE_BACKUPS . DIRECTORY_SEPARATOR . '*.sql') ?: [];
    usort($files, static fn(string $a, string $b): int => filemtime($b) <=> filemtime($a));

    foreach (array_slice($files, $keep) as $file) {
        @unlink($file);
    }
}

function ft_deployee_import_database(array $settings, array $upload): array {
    if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'output' => 'Choose a valid SQL file to import.'];
    }

    $extension = strtolower(pathinfo((string) $upload['name'], PATHINFO_EXTENSION));
    if ($extension !== 'sql') {
        return ['ok' => false, 'output' => 'Only .sql files are accepted.'];
    }

    $backup = ft_deployee_export_database($settings, false);
    if (!$backup['ok']) {
        return ['ok' => false, 'output' => "Safety backup failed, so the import was stopped.\n" . $backup['output']];
    }

    ft_deployee_ensure_storage();
    $temporary = FT_DEPLOYEE_STORAGE . DIRECTORY_SEPARATOR . 'import-' . wp_generate_uuid4() . '.sql';

    if (!move_uploaded_file((string) $upload['tmp_name'], $temporary)) {
        return ['ok' => false, 'output' => 'The uploaded SQL file could not be moved into protected storage.'];
    }

    $import = ft_deployee_wp_cli(['db', 'import', $temporary], $settings);
    @unlink($temporary);

    if (!$import['ok']) {
        return $import;
    }

    $replace = ft_deployee_wp_cli([
        'search-replace',
        untrailingslashit($settings['remote_url']),
        untrailingslashit($settings['local_url']),
        '--all-tables-with-prefix',
        '--skip-columns=guid',
    ], $settings);

    return [
        'ok' => $replace['ok'],
        'output' => trim("Database imported.\n\n" . $replace['output']),
    ];
}

function ft_deployee_backups(): array {
    ft_deployee_ensure_storage();
    $files = glob(FT_DEPLOYEE_BACKUPS . DIRECTORY_SEPARATOR . '*.sql') ?: [];
    usort($files, static fn(string $a, string $b): int => filemtime($b) <=> filemtime($a));

    return array_map(static function (string $path): array {
        return [
            'name' => basename($path),
            'size' => size_format((int) filesize($path), 2),
            'date' => wp_date('M j, Y g:i a', (int) filemtime($path)),
        ];
    }, $files);
}

function ft_deployee_cli_command(array $settings, bool $for_remote): string {
    $php = escapeshellarg($settings['php_binary']);
    $wp = escapeshellarg($settings['wp_cli_path']);
    $path = escapeshellarg(FT_DEPLOYEE_ROOT);
    $output = escapeshellarg('deployee/storage/backups/' . ft_deployee_backup_name($for_remote ? 'deploy' : 'backup'));

    if (!$for_remote) {
        return "{$php} {$wp} db export {$output} --add-drop-table --path={$path}";
    }

    $local = escapeshellarg(untrailingslashit($settings['local_url']));
    $remote = escapeshellarg(untrailingslashit($settings['remote_url']));
    return "{$php} {$wp} search-replace {$local} {$remote} --all-tables-with-prefix --skip-columns=guid --export={$output} --path={$path}";
}

ft_deployee_ensure_storage();
$settings = ft_deployee_settings();
$notice = null;

if (isset($_GET['download'])) {
    $file = sanitize_file_name(wp_unslash((string) $_GET['download']));
    check_admin_referer('ft_deployee_download_' . $file);
    $path = FT_DEPLOYEE_BACKUPS . DIRECTORY_SEPARATOR . $file;

    if (!is_file($path) || pathinfo($path, PATHINFO_EXTENSION) !== 'sql') {
        wp_die('Backup file not found.', 'Not found', ['response' => 404]);
    }

    nocache_headers();
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_referer('ft_deployee_action');
    $action = sanitize_key(wp_unslash((string) ($_POST['deployee_action'] ?? '')));

    if ($action === 'save_settings') {
        $settings = [
            'project_name' => sanitize_text_field(wp_unslash((string) ($_POST['project_name'] ?? ''))),
            'local_url' => ft_deployee_normalize_url(wp_unslash((string) ($_POST['local_url'] ?? ''))),
            'remote_url' => ft_deployee_normalize_url(wp_unslash((string) ($_POST['remote_url'] ?? ''))),
            'remote_path' => sanitize_text_field(wp_unslash((string) ($_POST['remote_path'] ?? ''))),
            'git_branch' => sanitize_key(wp_unslash((string) ($_POST['git_branch'] ?? 'main'))),
            'wp_cli_path' => sanitize_text_field(wp_unslash((string) ($_POST['wp_cli_path'] ?? ''))),
            'php_binary' => sanitize_text_field(wp_unslash((string) ($_POST['php_binary'] ?? ''))),
            'keep_backups' => max(1, min(50, absint($_POST['keep_backups'] ?? 10))),
        ];
        $notice = [
            'ok' => ft_deployee_save_settings($settings),
            'output' => 'Deployment settings saved.',
        ];
    } elseif ($action === 'export_backup') {
        $notice = ft_deployee_export_database($settings, false);
    } elseif ($action === 'export_remote') {
        $notice = ft_deployee_export_database($settings, true);
    } elseif ($action === 'deploy_code') {
        $confirmation = sanitize_text_field(wp_unslash((string) ($_POST['confirmation'] ?? '')));
        $notice = $confirmation === 'DEPLOY CODE'
            ? ft_deployee_deploy_code($settings)
            : ['ok' => false, 'output' => 'Type DEPLOY CODE exactly to confirm.'];
    } elseif ($action === 'import_database') {
        $confirmation = sanitize_text_field(wp_unslash((string) ($_POST['confirmation'] ?? '')));
        $notice = $confirmation === 'IMPORT DATABASE'
            ? ft_deployee_import_database($settings, $_FILES['sql_file'] ?? [])
            : ['ok' => false, 'output' => 'Type IMPORT DATABASE exactly to confirm.'];
    }
}

$checks = [
    ['label' => 'WordPress administrator', 'ok' => current_user_can('manage_options'), 'value' => wp_get_current_user()->user_login],
    ['label' => 'WP-CLI', 'ok' => is_file($settings['wp_cli_path']), 'value' => $settings['wp_cli_path']],
    ['label' => 'PHP command execution', 'ok' => ft_deployee_command_available(), 'value' => ft_deployee_command_available() ? 'Available' : 'Unavailable'],
    ['label' => 'Backup storage', 'ok' => is_writable(FT_DEPLOYEE_STORAGE), 'value' => FT_DEPLOYEE_STORAGE],
    ['label' => 'Database', 'ok' => true, 'value' => DB_NAME . ' / ' . DB_HOST],
];
$backups = ft_deployee_backups();
$systems = ft_deployee_systems();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>Deployee - <?php echo esc_html($settings['project_name']); ?></title>
    <link rel="stylesheet" href="<?php echo esc_url(home_url('/deployee/assets/deployee.css')); ?>">
</head>
<body>
<div class="deploy-app">
    <aside class="deploy-sidebar" id="deploy-sidebar">
        <div class="deploy-brand">
            <span class="deploy-brand-mark">D</span>
            <div>
                <strong>Deployee</strong>
                <small>System workspace</small>
            </div>
        </div>

        <nav class="deploy-navigation" aria-label="Deployee navigation">
            <span class="deploy-nav-label">Systems</span>
            <?php foreach ($systems as $system) : ?>
                <a class="deploy-system-link <?php echo $system['active'] ? 'is-active' : ''; ?>" href="<?php echo esc_url($system['href']); ?>">
                    <span class="deploy-system-icon"><?php echo esc_html($system['icon']); ?></span>
                    <span>
                        <strong><?php echo esc_html($system['name']); ?></strong>
                        <small><?php echo esc_html($system['description']); ?></small>
                    </span>
                </a>
            <?php endforeach; ?>

            <a class="deploy-add-system" href="#add-system">
                <span aria-hidden="true">+</span>
                Add system
            </a>

            <span class="deploy-nav-label">WordPress Deploy</span>
            <a href="#environment">Environment</a>
            <a class="deploy-nav-action" href="#database">
                <span class="deploy-nav-action-icon">DB</span>
                Deploy Database
            </a>
            <a class="deploy-nav-action" href="#code-deployment">
                <span class="deploy-nav-action-icon">&lt;/&gt;</span>
                Deploy Code
            </a>
            <a href="#backups">Backup library</a>
        </nav>

        <div class="deploy-sidebar-footer">
            <a href="<?php echo esc_url(admin_url()); ?>">WordPress Admin</a>
            <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener">View Website</a>
        </div>
    </aside>

    <div class="deploy-workspace">
        <header class="deploy-topbar">
            <button class="deploy-menu-toggle" type="button" aria-controls="deploy-sidebar" aria-expanded="false">
                <span></span><span></span><span></span>
                <span class="screen-reader-text">Open navigation</span>
            </button>
            <div>
                <span class="deploy-eyebrow">Deployment workspace</span>
                <h1>WordPress Deploy</h1>
                <p>Move WordPress code and databases without breaking serialized data.</p>
            </div>
            <div class="deploy-top-actions">
                <a href="<?php echo esc_url(admin_url()); ?>">WordPress Admin</a>
                <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener">View Site</a>
            </div>
        </header>

<main class="deploy-shell">
    <?php if (is_array($notice)) : ?>
        <div class="deploy-notice <?php echo $notice['ok'] ? 'is-success' : 'is-error'; ?>">
            <strong><?php echo $notice['ok'] ? 'Completed' : 'Action stopped'; ?></strong>
            <pre><?php echo esc_html((string) $notice['output']); ?></pre>
            <?php if (!empty($notice['file'])) : ?>
                <a class="deploy-button is-primary" href="<?php echo esc_url(wp_nonce_url(
                    add_query_arg('download', $notice['file']),
                    'ft_deployee_download_' . $notice['file']
                )); ?>">Download SQL</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <section class="deploy-summary">
        <div>
            <span class="deploy-eyebrow">Current project</span>
            <h2><?php echo esc_html($settings['project_name']); ?></h2>
            <p><?php echo esc_html($settings['local_url']); ?> → <?php echo esc_html($settings['remote_url']); ?></p>
        </div>
        <div class="deploy-health">
            <?php foreach ($checks as $check) : ?>
                <div class="deploy-health-item">
                    <span class="deploy-status <?php echo $check['ok'] ? 'is-ok' : 'is-bad'; ?>"></span>
                    <div>
                        <strong><?php echo esc_html($check['label']); ?></strong>
                        <small><?php echo esc_html($check['value']); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="deploy-card deploy-quick-actions">
        <div class="deploy-card-head">
            <span class="deploy-step">Go</span>
            <div>
                <h2>Quick deploy</h2>
                <p>Prepare the database package or push committed code from one place.</p>
            </div>
        </div>
        <div class="deploy-action-grid">
            <form method="post" class="deploy-action">
                <?php wp_nonce_field('ft_deployee_action'); ?>
                <input type="hidden" name="deployee_action" value="export_remote">
                <strong>Deploy Database</strong>
                <p>Build a serialization-safe SQL file with server URLs and download it.</p>
                <button class="deploy-button is-primary" type="submit">Prepare Database</button>
            </form>
            <form method="post" class="deploy-action" data-confirm-deploy>
                <?php wp_nonce_field('ft_deployee_action'); ?>
                <input type="hidden" name="deployee_action" value="deploy_code">
                <input type="hidden" name="confirmation" value="DEPLOY CODE">
                <strong>Deploy Code</strong>
                <p>Push committed changes to origin/<?php echo esc_html($settings['git_branch']); ?>.</p>
                <button class="deploy-button is-code" type="submit">Deploy Code</button>
            </form>
        </div>
    </section>

    <div class="deploy-grid">
        <section class="deploy-card" id="environment">
            <div class="deploy-card-head">
                <span class="deploy-step">1</span>
                <div>
                    <h2>Environment settings</h2>
                    <p>Non-secret deployment values. Database passwords stay in `wp-config.php`.</p>
                </div>
            </div>
            <form method="post" class="deploy-form">
                <?php wp_nonce_field('ft_deployee_action'); ?>
                <input type="hidden" name="deployee_action" value="save_settings">
                <div class="deploy-fields two-columns">
                    <label>Project name<input name="project_name" value="<?php echo esc_attr($settings['project_name']); ?>" required></label>
                    <label>Git branch<input name="git_branch" value="<?php echo esc_attr($settings['git_branch']); ?>" required></label>
                    <label>Local URL<input type="url" name="local_url" value="<?php echo esc_attr($settings['local_url']); ?>" required></label>
                    <label>Server URL<input type="url" name="remote_url" value="<?php echo esc_attr($settings['remote_url']); ?>" required></label>
                    <label class="full-width">CloudPanel site path<input name="remote_path" value="<?php echo esc_attr($settings['remote_path']); ?>"></label>
                    <label>WP-CLI path<input name="wp_cli_path" value="<?php echo esc_attr($settings['wp_cli_path']); ?>" required></label>
                    <label>PHP binary<input name="php_binary" value="<?php echo esc_attr($settings['php_binary']); ?>" required></label>
                    <label>Backups to keep<input type="number" min="1" max="50" name="keep_backups" value="<?php echo esc_attr((string) $settings['keep_backups']); ?>"></label>
                </div>
                <button class="deploy-button is-primary" type="submit">Save Settings</button>
            </form>
        </section>

        <section class="deploy-card" id="database">
            <div class="deploy-card-head">
                <span class="deploy-step">2</span>
                <div>
                    <h2>Database export</h2>
                    <p>Create a safety backup or a server-ready SQL file.</p>
                </div>
            </div>
            <div class="deploy-action-grid">
                <form method="post" class="deploy-action">
                    <?php wp_nonce_field('ft_deployee_action'); ?>
                    <input type="hidden" name="deployee_action" value="export_backup">
                    <strong>Local safety backup</strong>
                    <p>Exports the current database exactly as it is.</p>
                    <button class="deploy-button" type="submit">Create Backup</button>
                </form>
                <form method="post" class="deploy-action">
                    <?php wp_nonce_field('ft_deployee_action'); ?>
                    <input type="hidden" name="deployee_action" value="export_remote">
                    <strong>Export for server</strong>
                    <p>Converts local URLs to the configured server URL in the exported SQL only.</p>
                    <button class="deploy-button is-primary" type="submit">Build Server SQL</button>
                </form>
            </div>
            <div class="deploy-command">
                <span>CLI equivalent</span>
                <code><?php echo esc_html(ft_deployee_cli_command($settings, true)); ?></code>
            </div>
        </section>

        <section class="deploy-card">
            <div class="deploy-card-head">
                <span class="deploy-step">3</span>
                <div>
                    <h2>Import server database</h2>
                    <p>Creates a local backup first, imports SQL, then converts server URLs to local.</p>
                </div>
            </div>
            <form method="post" enctype="multipart/form-data" class="deploy-form">
                <?php wp_nonce_field('ft_deployee_action'); ?>
                <input type="hidden" name="deployee_action" value="import_database">
                <label class="deploy-upload">
                    <span>SQL file</span>
                    <input type="file" name="sql_file" accept=".sql,application/sql,text/plain" required>
                </label>
                <label>
                    Confirmation
                    <input name="confirmation" placeholder="Type IMPORT DATABASE" autocomplete="off" required>
                </label>
                <button class="deploy-button is-danger" type="submit">Backup and Import</button>
            </form>
        </section>

        <section class="deploy-card" id="code-deployment">
            <div class="deploy-card-head">
                <span class="deploy-step">4</span>
                <div>
                    <h2>Code deployment</h2>
                    <p>Recommended CloudPanel flow after pushing the repository.</p>
                </div>
            </div>
            <ol class="deploy-steps">
                <li><span>1</span><div><strong>Push locally</strong><code>git add . && git commit -m "Deploy update" && git push origin <?php echo esc_html($settings['git_branch']); ?></code></div></li>
                <li><span>2</span><div><strong>Pull on CloudPanel</strong><code>cd <?php echo esc_html($settings['remote_path']); ?> && git pull origin <?php echo esc_html($settings['git_branch']); ?></code></div></li>
                <li><span>3</span><div><strong>Import server SQL</strong><code>php wp-cli.phar db import deployee/storage/backups/your-deploy.sql --path=<?php echo esc_html($settings['remote_path']); ?></code></div></li>
                <li><span>4</span><div><strong>Flush WordPress caches</strong><code>php wp-cli.phar cache flush --path=<?php echo esc_html($settings['remote_path']); ?></code></div></li>
            </ol>
        </section>
    </div>

    <section class="deploy-card deploy-backups" id="backups">
        <div class="deploy-card-head">
            <span class="deploy-step">5</span>
            <div>
                <h2>Backup library</h2>
                <p>SQL files are protected from direct web access and excluded from Git.</p>
            </div>
        </div>
        <?php if (!$backups) : ?>
            <div class="deploy-empty">No deployment backups yet.</div>
        <?php else : ?>
            <div class="deploy-table-wrap">
                <table>
                    <thead><tr><th>File</th><th>Created</th><th>Size</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($backups as $backup) : ?>
                        <tr>
                            <td><strong><?php echo esc_html($backup['name']); ?></strong></td>
                            <td><?php echo esc_html($backup['date']); ?></td>
                            <td><?php echo esc_html($backup['size']); ?></td>
                            <td><a href="<?php echo esc_url(wp_nonce_url(
                                add_query_arg('download', $backup['name']),
                                'ft_deployee_download_' . $backup['name']
                            )); ?>">Download</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <section class="deploy-card deploy-add-system-card" id="add-system">
        <div class="deploy-card-head">
            <span class="deploy-step">+</span>
            <div>
                <h2>Add another system</h2>
                <p>The left menu is ready for more deployment tools and project dashboards.</p>
            </div>
        </div>
        <div class="deploy-system-options">
            <div>
                <strong>New system module</strong>
                <p>Add the system to <code>ft_deployee_systems()</code>, then point it to its page inside the Deployee folder.</p>
            </div>
            <code>deployee/systems/your-system/index.php</code>
        </div>
    </section>
</main>
    </div>
</div>
<script>
(() => {
    const button = document.querySelector('.deploy-menu-toggle');
    const sidebar = document.querySelector('.deploy-sidebar');
    if (!button || !sidebar) return;

    const closeMenu = () => {
        document.body.classList.remove('deploy-menu-open');
        button.setAttribute('aria-expanded', 'false');
    };

    button.addEventListener('click', () => {
        const isOpen = document.body.classList.toggle('deploy-menu-open');
        button.setAttribute('aria-expanded', String(isOpen));
    });

    sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));

    document.querySelectorAll('[data-confirm-deploy]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Push committed code to the configured Git branch now?')) {
                event.preventDefault();
            }
        });
    });
})();
</script>
</body>
</html>
