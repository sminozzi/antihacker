<?php

/**
 * @author William Sergio Minossi
 * @copyright 2016
 */
// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}
$antihacker_option_name[] = 'my_radio_xml_rpc';
$antihacker_option_name[] = 'antihacker_rest_api';
$antihacker_option_name[] = 'antihacker_automatic_plugins';
$antihacker_option_name[] = 'antihacker_automatic_themes';
$antihacker_option_name[] = 'antihacker_replace_login_error_msg';
$antihacker_option_name[] = 'antihacker_disallow_file_edit';
$antihacker_option_name[] = 'antihacker_debug_is_true';
$antihacker_option_name[] = 'antihacker_firewall';
$antihacker_option_name[] = 'antihacker_my_whitelist';
$antihacker_option_name[] = 'antihacker_my_email_to';
$antihacker_option_name[] = 'antihacker_hide_wp';
$antihacker_option_name[] = 'antihacker_block_enumeration';
$antihacker_option_name[] = 'antihacker_block_all_feeds';
$antihacker_option_name[] = 'antihacker_new_user_subscriber';
$antihacker_option_name[] = 'antihacker_checkversion';
$antihacker_option_name[] = 'antihacker_block_falsegoogle';
$antihacker_option_name[] = 'antihacker_block_search_plugins';
$antihacker_option_name[] = 'antihacker_block_search_themes';
$antihacker_option_name[] = 'antihacker_version';
$antihacker_option_name[] = 'antihacker_block_tor';
$antihacker_option_name[] = 'antihacker_block_false_google';
$antihacker_option_name[] = 'antihacker_block_http_tools';
$antihacker_option_name[] = 'antihacker_blank_ua';
$antihacker_option_name[] = 'antihacker_radio_limit_visits';
$antihacker_option_name[] = 'antihacker_rate_limiting_day';
$antihacker_option_name[] = 'antihacker_rate_limiting';
$antihacker_option_name[] = 'antihacker_rate404_limiting';
$antihacker_option_name[] = 'antihacker_application_password';
$antihacker_option_name[] = 'antihacker_checkbox_all_fail';
$antihacker_option_name[] = 'antihacker_Blocked_Firewall';
$antihacker_option_name[] = 'antihacker_Blocked_else_email';
$antihacker_option_name[] = 'antihacker_my_radio_report_all_logins';
$antihacker_option_name[] = 'antihacker_googlesafe_checked';
$antihacker_option_name[] = 'antihacker_safebrowsing';
$antihacker_option_name[] = 'antihacker_http_tools';
$antihacker_option_name[] = 'antihacker_update_http_tools';
$antihacker_option_name[] = 'anti_hacker_last_feedback';
$antihacker_option_name[] = 'antihacker_optin';
$antihacker_option_name[] = 'antihacker_show_widget';
$antihacker_option_name[] = 'antihacker_notif_scan';
$antihacker_option_name[] = 'antihacker_notif_level';
$antihacker_option_name[] = 'antihacker_notif_visit';
$antihacker_option_name[] = 'antihacker_last_scan';
$antihacker_option_name[] = 'antihacker_last_plugin_scan';
$antihacker_option_name[] = 'antihacker_last_theme_scan';
$antihacker_option_name[] = 'antihacker_last_theme_update';
$antihacker_option_name[] = 'antihacker_disable_sitemap';

$wnum = count($antihacker_option_name);
for ($i = 0; $i < $wnum; $i++) {
    delete_option($antihacker_option_name[$i]);
    // For site options in Multisite
    delete_site_option($antihacker_option_name[$i]);
}

/*
// Drop a custom db table
global $wpdb;
$current_table = $wpdb->prefix . 'ah_stats';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'ah_blockeds';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'ah_visitorslog';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'ah_fingerprint';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_tor";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_scan_files";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_scan";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . "ah_rules";
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
$current_table = $wpdb->prefix . 'wptools_page_load_times';
$wpdb->query( "DROP TABLE IF EXISTS $current_table" );
*/

global $wpdb;

$antihacker_tables = [
    'ah_stats',
    'ah_blockeds',
    'ah_visitorslog',
    'ah_fingerprint',
    'ah_tor',
    'ah_scan_files',
    'ah_scan',
    'ah_rules',
    'wptools_page_load_times',
    'bill_catch_some_bots'
];

foreach ($antihacker_tables as $table) {
    try {
        $current_table = $wpdb->prefix . $table;
        $drop_result = $wpdb->query("DROP TABLE IF EXISTS $current_table");

        if (false === $drop_result) {
            // throw new Exception("Falha ao tentar excluir a tabela: $current_table");
        }
    } catch (Exception $e) {
        // Ignore o erro ou registre uma mensagem personalizada
        // error_log($e->getMessage());  // Opcional: descomente se quiser registrar o erro
    }
}


register_deactivation_hook(__FILE__, 'antihacker_deactivation');

wp_clear_scheduled_hook('antihacker_weekly_scan');
wp_clear_scheduled_hook('antihacker_cron_hook');
wp_clear_scheduled_hook('antihacker_cron_hook2');

wp_clear_scheduled_hook('antihacker_cron_event_plugins_scan');






/*
[ 27-Jan-2025 08:45:03 UTC] 
WordPress database error DROP command denied to user 
'galtour1_catering-new'@'localhost' 
for table `galtour1_catering-new`.`dcgl_ah_rules` 
for query 
DROP TABLE IF EXISTS dcgl_ah_rules 
made by require('/usr/local/cpanel/3rdparty/wp-toolkit/plib/vendor/wp-cli/
wpt-wp-cli.php'), 
require_once('/usr/local/cpanel/3rdparty/wp-toolkit/plib/vendor/wp-cli/
vendor/wp-cli/wp-cli/php/boot-fs.php'), 
require_once('/usr/local/cpanel/3rdparty/wp-toolkit/plib/vendor/wp-cli/vendor
/wp-cli/wp-cli/php/wp-cli.php'), WP_CLIbootstrap, 
WP_CLIBootstrapLaunchRunner->process, WP_CLIRunner->start, 
WP_CLIRunner->run_command_and_exit, WP_CLIRunner->run_command, 
WP_CLIDispatcherSubcommand->invoke, call_user_func, 
WP_CLIDispatcherCommandFactory::WP_CLIDispatcher{closure}, 
call_user_func, Plugin_Command->
uninstall, uninstall_plugin, include_once('/plugins/antihacker/uninstall.php')","
*/

$plugin_name = 'bill-catch-errors.php'; // Name of the plugin file to be removed

// Retrieve all must-use plugins
$wp_mu_plugins = get_mu_plugins();

// MU-Plugins directory
$mu_plugins_dir = WPMU_PLUGIN_DIR;

if (isset($wp_mu_plugins[$plugin_name])) {
    // Get the plugin's destination path
    $destination = $mu_plugins_dir . '/' . $plugin_name;

    // Attempt to remove the plugin
    if (!unlink($destination)) {
        // Log the error if the file could not be deleted
        error_log("Error removing the plugin file from the MU-Plugins directory: $destination");
    } else {
        // Optionally, log success if the plugin is removed successfully
        // error_log("Successfully removed the plugin file: $destination");
    }
}
