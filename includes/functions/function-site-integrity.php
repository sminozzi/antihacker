<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access not allowed.');
}





/**
 * Main class for the File Integrity Checker plugin
 */
class antihacker_File_Integrity_Checker
{
    public function __construct()
    {
        //add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_run_files_integrity_check', array($this, 'run_files_integrity_check'));
        add_action('wp_ajax_view_file_diff', array($this, 'view_file_diff'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts($hook)
    {
        if ($hook !== 'tools_page_file-integrity-checker') {
            return;
        }
        wp_enqueue_style('file-integrity-style', plugins_url('/style.css', __FILE__));
        wp_enqueue_script('file-integrity-script', plugins_url('/script.js', __FILE__), array('jquery'), '1.1', true);
        wp_localize_script('file-integrity-script', 'fileIntegrityAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('file-integrity-check-nonce'),
        ));
    }
    /**
     * Run the integrity check via AJAX
     */
    public function run_files_integrity_check()
    {
        check_ajax_referer('file-integrity-check-nonce', 'nonce');
        $checksums = $this->call_checksum_api();
        $files = $this->parse_checksum_results($checksums);
        $this->create_the_response($files);
    }
    /**
     * Call WordPress API for checksums
     */
    public function call_checksum_api(): array
    {
        $wpversion = get_bloginfo('version');
        $wplocale = get_locale();
        $checksums = get_core_checksums($wpversion, $wplocale);
        if (false === $checksums) {
            return array();
        }
        set_transient('file-integrity-checksums', $checksums, 2 * HOUR_IN_SECONDS);
        foreach ($checksums as $file => $checksum) {
            if (false !== strpos($file, 'wp-content/')) {
                unset($checksums[$file]);
            }
        }
        return $checksums;
    }
    /**
     * Parse checksum results
     */
    public function parse_checksum_results(array $checksums): array
    {
        if (empty($checksums)) {
            return array();
        }
        $filepath = ABSPATH;
        $files = array();
        // Arquivos a serem ignorados na verificação de checksums
        $excluded_files = array('wp-config.php', 'wp-includes/version.php');
        $nonce = wp_create_nonce('view_diff_action');
        foreach ($checksums as $file => $checksum) {
            if (in_array($file, $excluded_files, true)) {
                continue;
            }
            if (file_exists($filepath . $file) && md5_file($filepath . $file) !== $checksum) {
                $reason = esc_html__('Content changed', 'file-integrity-checker') .
                    ' <a href="#" class="view-diff" data-file="' . esc_attr($file) . '" data-nonce="' . esc_attr($nonce) . '">' .
                    esc_html__('(View Diff)', 'file-integrity-checker') . '</a>';
                array_push($files, array($file, $reason));
            } elseif (!file_exists($filepath . $file)) {
                $reason = esc_html__('File not found', 'file-integrity-checker');
                array_push($files, array($file, $reason));
            }
        }
        if (class_exists('RecursiveDirectoryIterator')) {
            $directories = array(
                untrailingslashit(ABSPATH),
                untrailingslashit(ABSPATH . 'wp-admin'),
                untrailingslashit(ABSPATH . WPINC),
            );
            // Manter a exclusão também para arquivos desconhecidos
            $excluded_files = array('.htaccess', 'wp-config.php', 'wp-includes/version.php');
            foreach ($directories as $directory) {
                if (untrailingslashit(ABSPATH) === $directory) {
                    $iterator = new DirectoryIterator($directory);
                    foreach ($iterator as $file) {
                        if ($file->isFile()) {
                            $path = str_replace(ABSPATH, '', $file->getPathname());
                            if (!isset($checksums[$path]) && !in_array($path, $excluded_files, true)) {
                                $reason = esc_html__('This is an unknown file', 'file-integrity-checker');
                                array_push($files, array($path, $reason));
                            }
                        }
                    }
                } else {
                    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
                    foreach ($iterator as $file) {
                        if ($file->isFile()) {
                            $path = str_replace(ABSPATH, '', $file->getPathname());
                            if (!isset($checksums[$path]) && !in_array($path, $excluded_files, true)) {
                                $reason = esc_html__('This is an unknown file', 'file-integrity-checker');
                                array_push($files, array($path, $reason));
                            }
                        }
                    }
                }
            }
        }
        return $files;
    }
    /**
     * Create the response HTML
     */
    public function create_the_response(array $files): void
    {
        $filepath = ABSPATH;
        $output = '';
        if (empty($files)) {
            $output .= '<div class="notice notice-success inline">
    <p>';
            $output .= esc_html__('All files passed the check. Everything seems to be OK!', 'file-integrity-checker');
            $output .= '</p>
</div>';
        } else {
            $output .= '<div class="notice notice-error inline">
    <p>';
            $output .= esc_html__('It appears some files may have been modified.', 'file-integrity-checker');
            // $output .= '<br>' . esc_html__('One possible reason is that your installation contains translated versions. Reinstalling WordPress can resolve this.', 'file-integrity-checker');
            $output .= '</p>
</div>
<table class="widefat striped">
    <thead>
        <tr>
            <th>';
            $output .= esc_html__('Status', 'file-integrity-checker');
            $output .= '</th>
            <th>';
            $output .= esc_html__('File', 'file-integrity-checker');
            $output .= '</th>
            <th>';
            $output .= esc_html__('Reason', 'file-integrity-checker');
            $output .= '</th>
        </tr>
    </thead>
    <tbody>';
            foreach ($files as $tampered) {
                $output .= '<tr>';
                $output .= '<td><span class="warning-icon">⚠</span><span class="screen-reader-text">' . esc_html__('Error', 'file-integrity-checker') . '</span></td>';
                $output .= '<td>' . esc_html($filepath . $tampered[0]) . '</td>';
                $output .= '<td>' . $tampered[1] . '</td>'; // Already escaped in parse_checksum_results
                $output .= '</tr>';
            }
            $output .= '</tbody>
</table>';
        }
        wp_send_json_success(array('message' => $output));
    }
    /**
     * Display file diff in a popup via AJAX
     */
    public function view_file_diff()
    {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        // Verificar o nonce
        if (!wp_verify_nonce($nonce, 'view_diff_action')) {
            wp_send_json_error(array('message' => 'Invalid Nonce.'));
        }
        if (!current_user_can('manage_options')) {
            wp_send_json_error();
        }
        $file_safe = sanitize_text_field($_POST['file']);
        $wpversion = get_bloginfo('version');
        $allowed_files = get_transient('file-integrity-checksums');
        if (false === $allowed_files) {
            $allowed_files = $this->call_checksum_api();
        }
        if (!isset($allowed_files[$file_safe])) {
            wp_send_json_error(array('message' => esc_html__('You do not have access to this file.', 'file-integrity-checker')));
        }
        $local_file_body = file_get_contents(ABSPATH . $file_safe, true);
        $remote_file = wp_remote_get('https://core.svn.wordpress.org/tags/' . $wpversion . '/' . $file_safe);
        $remote_file_body = wp_remote_retrieve_body($remote_file);
        $diff_args = array('show_split_view' => true);
        $output = '<div class="diff-popup-content">';
        $output .= '<h3>' . esc_html__('File Diff', 'file-integrity-checker') . ': ' . esc_html($file_safe) . '</h3>';
        $output .= '<table class="diff">
        <thead>
            <tr class="diff-sub-title">
                <th>';
        $output .= esc_html__('Original', 'file-integrity-checker');
        $output .= '</th>
                <th>';
        $output .= esc_html__('Modified', 'file-integrity-checker');
        $output .= '</th>
            </tr>
    </table>';
        $output .= wp_text_diff($remote_file_body, $local_file_body, $diff_args);
        $output .= '<button class="button close-diff-popup">' . esc_html__('Close', 'file-integrity-checker') . '</button>';
        $output .= '</div>';
        wp_send_json_success(array('message' => $output));
    }
    public function get_integrity_results(): array
    {
        $checksums = $this->call_checksum_api();
        return $this->parse_checksum_results($checksums);
    }
}

$antihacker_file_integrity_checker = new antihacker_File_Integrity_Checker();
//$file_integrity_checker = new FileIntegrityChecker();
add_action('wp_ajax_view_file_diff', array($antihacker_file_integrity_checker, 'view_file_diff'));
