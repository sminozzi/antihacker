<?php

/**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
// ob_start();
define('ANTIHACKERADMURL', admin_url());
$antihacker_urlsettings = ANTIHACKERADMURL . "/admin.php?page=antihacker_settings33";
add_action('admin_init', 'antihacker_settings_init');
add_action('admin_menu', 'antihacker_add_admin_menu');
function antihacker_enqueue_scripts()
{
    wp_enqueue_style('bill-help-dashboard-ah', ANTIHACKERURL . '/dashboard/css/help.css');
    wp_register_script('antihacker-dashboard', ANTIHACKERURL . '/dashboard/js/dashboard.js', array('jquery'), ANTIHACKERVERSION, true);
    wp_enqueue_script('antihacker-dashboard');
}
add_action('admin_init', 'antihacker_enqueue_scripts');
function antihacker_add_admin_menu()
{
    global $menu;
    add_menu_page(
        'Anti Hacker22',
        'Anti Hacker',
        'manage_options',
        'anti_hacker_plugin', // slug 
        'antihacker_options_page',
        ANTIHACKERIMAGES . '/protect.png',
        '100'
    );
    include_once(ABSPATH . 'wp-includes/pluggable.php');
    $link_our_new_CPT = urlencode('edit.php?post_type=antihackerfields');
}
function antihacker_settings_init()
{
    register_setting('antihacker', 'antihacker_settings');
}
function antihacker_options_page()
{
    global $antihacker_activated, $antihacker_update_theme;
    global $antihacker_active;
    global $antihacker_ip_active;
    global $antihacker_checkversion;
    global $antihacker_firewall;
    $wpversion = get_bloginfo('version');
    $current_user = wp_get_current_user();
    $plugin = plugin_basename(__FILE__);
    $email = $current_user->user_email;
    $username =  trim($current_user->user_firstname);
    $user = $current_user->user_login;
    $user_display = trim($current_user->display_name);
    if (empty($username))
        $username = $user;
    if (empty($username))
        $username = $user_display;
    $theme = wp_get_theme();
    $themeversion = $theme->version;
    if (isset($_GET['tab']))
        $active_tab = sanitize_text_field($_GET['tab']);
    else
        $active_tab = 'dashboard';
?>
    <center>
        <img id="antihacker_spinner" alt="antihacker_spinner" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/spinner.gif" width="50px" style="opacity:.5" ; />
    </center>
    <div id="antihacker-theme-help-wrapper" style="opacity:.0;">
        <div id="antihacker-not-activated"></div>
        <div id="antihacker_header">
            <div id="antihacker-logo">
                <img alt="logo" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/logo.png" width="250px" />
            </div>
            <div id="antihacker-nocloud">
                <img alt="No Cloud" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/no_cloud.png" width="200px" />
            </div>
            <div id="antihacker_help_title">
                Help and Support Page
            </div>
            <div id="antihacker-social">
                <a href="http://antihackerplugin.com/share/"><img alt="social bar" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/social-bar.png" width="250px" /></a>
            </div>
        </div>
        <?php
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=anti_hacker_plugin&tab=memory&tab=dashboard" class="nav-tab">Dashboard</a>
            <a href="?page=anti_hacker_plugin&tab=memory" class="nav-tab">Memory Check Up</a>
            <!--   <a href="?page=anti_hacker_plugin&tab=errors" class="nav-tab">Site Errors</a> -->
            <a href="?page=anti_hacker_plugin&tab=plugins" class="nav-tab">Check Plugins</a>
            <a href="?page=anti_hacker_plugin&tab=notifications" class="nav-tab">Notifications</a>
            <a href="?page=anti_hacker_plugin&tab=check_file_integrity" class="nav-tab">File Integrity Checker</a>
            <a href="?page=anti_hacker_plugin&tab=freebies" class="nav-tab">More Tools</a>
        </h2>
        <?php


        //debug2();

        if ($active_tab == 'memory') {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . 'dashboard/memory.php');
        } elseif ($active_tab == 'notifications') {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . 'dashboard/notifications.php');
        } elseif ($active_tab == 'plugins') {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . 'dashboard/check_plugins.php');
        } elseif ($active_tab == 'freebies') {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . 'dashboard/freebies.php');
        } elseif ($active_tab == 'errors') {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . '/dashboard/errors.php');
        } elseif ($active_tab == 'check_file_integrity') {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . '/dashboard/check_file_integrity.php');
            // $antihacker_file_integrity_checker->run_files_integrity_check();
        } else {
            echo '<div id="antihacker-dashboard-wrap">';
            echo '<div id="antihacker-dashboard-left">';
            require_once(ANTIHACKERPATH . 'dashboard/dashboard.php');
        }
        ?>
    </div> <!-- "antihacker-dashboard-left"> -->
    <div id="antihacker-dashboard-right">
        <div id="antihacker-containerright-dashboard">
            <?php
            //
            //
            //
            require_once(ANTIHACKERPATH . "dashboard/mybanners.php");
            ?>
        </div>
    </div> <!-- "antihacker-dashboard-right"> -->
    </div> <!-- "car-dealer-dashboard-wrap"> -->
    </div> <!-- "antihacker-theme_help-wrapper"> -->
<?php
} // end Function antihacker_options_page
require_once(ABSPATH . 'wp-admin/includes/screen.php');
// ob_end_clean();
include_once(ABSPATH . 'wp-includes/pluggable.php');
if (! function_exists('antihacker_is_bill_theme')) {
    function antihacker_is_bill_theme()
    {
        $my_theme = wp_get_theme();
        $theme = trim($my_theme->get('Name'));
        // die($theme);
        $mythemes = array(
            'boatdealer',
            'KarDealer',
            'verticalmenu',
            'fordummies',
            'Real Estate Right Now'
        );
        // boatseller
        $count = count($mythemes);
        $theme =  strtolower(trim($theme));
        for ($i = 0; $i < $count; $i++) {
            if ($theme == strtolower(trim($mythemes[$i])))
                return true;
        }
        return false;
    }
}
function antihackerCheckThemeDead()
{
    if (! function_exists('themes_api')) {
        require_once(ABSPATH . 'wp-admin/includes/theme.php');
    }
    $my_theme = wp_get_theme();
    $my_theme_name =  esc_html($my_theme->get('Name'));
    $args = array(
        'slug' => $my_theme_name,
    );
    $call_api = themes_api('theme_information', $args);
    if (!isset($call_api->last_updated))
        return false;
    $last_upd = strtotime($call_api->last_updated);
    return (time() > ($last_upd + (60 * 60 * 24 * 365 * 2)));
} ?>