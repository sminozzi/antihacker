<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2016
 */

// Function 404 called. Backtrace: Array ( [0] => Array ( [file] => /home/realesta/public_html/wp-content/plugins/antihacker/includes/functions/functions.php [line] => 3708 [function] => is_404 [args] => Array ( ) ) [1] => Array ( [file] => /home/realesta/public_html/wp-content/plugins/antihacker/includes/functions/functions.php [line] => 3671 [function] => antihacker_gravalog [args] => Array ( [0] => Firewall ) ) [2] => Array ( [file] => /home/realesta/public_html/wp-content/plugins/antihacker/antihacker.php [line] => 362 [function] => antihacker_response [args] => Array ( [0] => Firewall ) ) [3] => Array ( [file] => /home/realesta/public_html/wp-settings.php [line] => 453 [args] => Array ( [0] => /home/realesta/public_html/wp-content/plugins/antihacker/antihacker.php ) [function] => include_once ) [4] => Array ( [file] => /home/realesta/public_html/wp-config.php [line] => 79 [args] => Array ( [0] => /home/realesta/public_html/wp-settings.php ) [function] => require_once ) [5] => Array ( [file] => /home/realesta/public_html/wp-load.php [line] => 50 [args] => Array ( [0] => /home/realesta/public_html/wp-config.php ) [function] => require_once ) [6] => Array ( [file] => /home/realesta/public_html/wp-blog-header.php [line] => 13 [args] => Array ( [0] => /home/realesta/public_html/wp-load.php ) [function] => require_once ) [7] => Array ( [file] => /home/realesta/public_html/index.php [line] => 17 [args] => Array ( [0] => /home/realesta/public_html/wp-blog-header.php ) [function] => require ) ) 
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
//$antihacker_debug = true;
$antihacker_debug = false;

global $wpdb;
global $wp_query;
// $antihacker_ip = trim(antihacker_findip());

// $antihacker_ip = '41.78.139.17';


//debug($antihacker_ip);
//debug();
$antihacker_ua = trim(antihacker_get_ua());
$antihacker_table = $wpdb->prefix . "ah_fingerprint";
$antihacker_http_tools = trim(get_site_option('antihacker_http_tools', ''));
$antihacker_http_tools = explode(PHP_EOL, $antihacker_http_tools);

if (version_compare(trim(ANTIHACKERVERSION), trim(ANTIHACKERVERSIONANT)) > 0 or empty($antihacker_http_tools)) {

    $antihacker_http_tools = trim(get_site_option('antihacker_http_tools', ''));
    $antihacker_http_tools = explode(PHP_EOL, $antihacker_http_tools);


    if (empty($antihacker_string_whitelist))
        antihacker_create_whitelist();


    if (empty($antihacker_http_tools) or $antihacker_update_http_tools == 'yes')
        antihacker_create_httptools();



    antihacker_create_db_stats();
    antihacker_create_db_blocked();
    antihacker_create_db_visitors();
    antihacker_create_db_fingerprint();
    antihacker_create_db_scan_files();
    antihacker_create_db_scan();
    antihacker_create_db_rules();  
    antihacker_remove_index();
    // antihacker_add_index();
    antihacker_upgrade_db();
    antihacker_upgrade_db_visitors();
    antihacker_upgrade_db_blocked();
    antihacker_upgrade_db_tor();
    antihacker_upd_tor_db();
    antihacker_populate_stats();
    antihacker_populate_rules();

    if (!add_option('antihacker_version', ANTIHACKERVERSION)) {
        update_option('antihacker_version', ANTIHACKERVERSION);
    }
}


$antihacker_table = $wpdb->prefix . "ah_fingerprint";

$result = $wpdb->get_results($wpdb->prepare("
SELECT  fingerprint FROM `$antihacker_table` 
WHERE ip = %s 
AND fingerprint != '' limit 1", $antihacker_ip));

$qrow = $wpdb->num_rows;


add_action('wp_head', 'antihacker_ajaxurl');



$antihacker_is_human = '';
// auto declare is s.e ?

$antihacker_mysearch = array(
    'bingbot',
    'googlebot',
    'msn.com',
    'slurp',
    'facebookexternalhit',
    'AOL',
    'Baidu',
    'Bingbot',
    'DuckDuck',
    'Teoma',
    'Yahoo',
    'seznam',
    'Yandex',
    'Twitterbot',
    'facebookexternalhit',
);
for ($i = 0; $i < count($antihacker_mysearch); $i++) {
    if (stripos($antihacker_ua, $antihacker_mysearch[$i]) !== false) {
        $antihacker_is_human = 0;
    }
}

//debug($qrow);

if( $antihacker_is_human !== 0) {
    ////////-----------------E' bot ou nao----------------------
    // $qrow = 0;
    if ($qrow < 1) {
        add_action('wp_enqueue_scripts', 'antihacker_include_scripts');
        add_action('admin_enqueue_scripts', 'antihacker_include_scripts');
        if (antihacker_first_time2() > 0)
            $antihacker_is_human = '0';
        else
            $antihacker_is_human = '?';
    } else {
        $fingerprint_filed  = $result[0]->fingerprint;
        if ($fingerprint_filed == '')
            $antihacker_is_human = 0;
        else
            $antihacker_is_human = 1;
    }

    // debug($antihacker_is_human);

    //add_action('template_redirect', 'antihacker_final_step');

    add_filter('plugin_row_meta', 'antihacker_custom_plugin_row_meta', 10, 2);

     add_action('wp_ajax_antihacker_grava_fingerprint', 'antihacker_grava_fingerprint');
     add_action('wp_ajax_nopriv_antihacker_grava_fingerprint', 'antihacker_grava_fingerprint');

     add_action('wp_ajax_antihacker_get_ajax_data', 'antihacker_get_ajax_data');



    add_action('wp_ajax_antihacker_add_whitelist', 'antihacker_add_whitelist');
    add_action('wp_ajax_nopriv_antihacker_add_whitelist', 'antihacker_add_whitelist');

    // $antihacker_string_whitelist



    add_action('wp_ajax_antihacker_add_string_whitelist', 'antihacker_add_string_whitelist');
    add_action('wp_ajax_nopriv_antihacker_add_string_whitelist', 'antihacker_add_string_whitelist');

} // if( $antihacker_is_human != 0)


/*
add_action('init', 'antihacker_create_schedule');
add_action('antihacker_cron_hook', 'antihacker_cron_function');
if (!empty($antihacker_checkversion)) {
    add_action('init', 'antihacker_create_schedule2');
    add_action('antihacker_cron_hook2', 'antihacker_upd_tor_db');
}
*/



// Block HTTP_tools
if (!empty($antihacker_checkversion))
    add_action('plugins_loaded', 'antihacker_check_httptools');
// antihacker_blank_ua
if (!empty($antihacker_checkversion))
    add_action('plugins_loaded', 'antihacker_check_useragent');
$antihacker_now = strtotime("now");
$antihacker_after = strtotime("now") + (3600);
/*
if (empty($antihacker_checkversion)) {
    add_action('admin_notices', 'antihacker_bill_ask_for_upgrade');
}
*/


/*
// Block Login Post without  - ver antihacker
if( !empty($antihacker_request_url)  and $antihacker_method == 'POST') {
    if (!preg_match('/(wp-login.php)/', $antihacker_request_url) and $antihacker_referer == '') {
        antihacker_stats_moreone('qnoref');
        if ($antihacker_Blocked_else_email == 'yes') {
            antihacker_alertme13();
        }
        antihacker_response('Login Post Without Referrer');
    }
}
*/
/*
// Block  Post without referrer
if( !empty($antihacker_request_url  and $antihacker_method == 'POST' and $antihacker_referer == '') {
        antihacker_stats_moreone('qnoref');
        if ($antihacker_Blocked_else_email == 'yes') {
            antihacker_alertme13();
        }
        antihacker_response('Post Without Referrer');
}
*/
if ($antihacker_block_falsegoogle == 'yes') {
    if (antihacker_check_false_googlebot()) {
        if ($antihacker_Blocked_else_email == 'yes')
            antihacker_alertme7();
        antihacker_stats_moreone('qfalseg');
        antihacker_response('False Googlebot, Msnbot & Bingbot');
    }
}
add_action('plugins_loaded', 'antihacker_check_limits');
if ($antihacker_new_user_subscriber == 'yes') {
    add_action('user_register', 'antihacker_new_user_subscriber', 10, 1);
}
$qpluginsnow = antihacker_q_plugins_now();
$qplugins = antihacker_q_plugins();
if (($qplugins == 0 and $qpluginsnow > 0) or ($qplugins > $qpluginsnow)) {
    antihacker_save_name_plugins();
    $qplugins = antihacker_q_plugins();
}
if ($qpluginsnow > $qplugins) {
    $nplugins = get_site_option('antihacker_name_plugins', '');
    $nplugins = explode(PHP_EOL, $nplugins);
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();
    $all_plugins_keys = array_keys($all_plugins);
    if (count($all_plugins) < 1)
        return;
    $my_plugins_now = array();
    $loopCtr = 0;
    foreach ($all_plugins as $plugin_item) {
        $plugin_title = $plugin_item['Name'];
        $my_plugins_now[$loopCtr] = $plugin_title;
        $loopCtr++;
    }
    $antihacker_new_plugin = '';
    for ($i = 0; $i < $qpluginsnow; $i++) {
        $plugin_name = $my_plugins_now[$i];
        if (!in_array($plugin_name, $nplugins)) {
            $antihacker_new_plugin = $plugin_name;
            break;
        }
    }
    add_action('plugins_loaded', 'antihacker_alert_plugin');
    antihacker_save_name_plugins();
}  //  if ($qpluginsnow > $qplugins)  
if ($qpluginsnow < $qplugins) {
    antihacker_save_name_plugins();
}

$antihacker_rest_api = trim(get_site_option('antihacker_rest_api', 'No'));
if ($antihacker_rest_api <> 'No' ){
    if(!antihacker_isourserver()    ) {
     add_action('plugins_loaded', 'antihacker_after_inic');
    }
}

if(!antihacker_isourserver()    ) {
    if (get_site_option('my_radio_xml_rpc', 'No') == 'Yes') {
        if (!empty($antihacker_request_url)) {
            $pos = strpos($antihacker_request_url, 'xmlrpc.php');
            if ($pos !== false) {
                if ($antihacker_Blocked_else_email == 'yes')
                    antihacker_alertme15();
                antihacker_stats_moreone('xmlrpc');
                antihacker_response('xmlrpc access denied');
                return;
            }
        }
        add_filter('xmlrpc_enabled', '__return_false');
    }


    if (get_site_option('my_radio_xml_rpc', 'No') == 'Pingback'){

        add_filter('xmlrpc_methods', 'antihacker_premove_xmlrpc_pingback_ping');

    }
}
add_filter('custom_menu_order', 'antihacker_change_note_submenu_order');
//add_action('wp_loaded', 'antihacker_control_availablememory');
if (isset($_GET['page'])) {
    $page = trim(sanitize_text_field($_GET['page']));
    // die($page);
    if ($page == 'anti_hacker_plugin' or $page == 'anti-hacker' or $page == 'antihacker_my-custom-submenu-page') {
        // die('xxxxxxxxxxxxxxx');
        add_filter('admin_head', 'antihacker_contextual_help', 10, 3);
    }
}

add_action('template_redirect', 'antihacker_final_step');

///////////////////////////////////////////////////////////////////

function antihacker_create_whitelist()
{
    $mywhitelist = array(
        'Paypal',
        'Stripe',
        'SiteUptime'
    );

    $text = '';
    for ($i = 0; $i < count($mywhitelist); $i++) {
        $text .= $mywhitelist[$i] . PHP_EOL;
    }
    if (!add_option('antihacker_string_whitelist', $text)) {
        update_option('antihacker_string_whitelist', $text);
    }
}


function antihacker_successful_login($user_login)
{
    global $antihacker_amy_whitelist;
    global $antihacker_my_radio_report_all_logins;
    global $antihacker_ip;
    global $antihacker_admin_email;
    global $antihacker_server;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist) and $antihacker_my_radio_report_all_logins <> 'Yes') {
        return 1;
    }
    $dt = date("Y-m-d H:i:s");
    $dom = $antihacker_server;
    $msg = __('This email was sent from your website', "antihacker") . ' ';
    $msg .= $dom . '&nbsp; ' . __('by the AntiHacker plugin.', "antihacker");
    $msg .= '<br>';
    $msg .= __('Date', "antihacker") . ': ' . $dt . '<br>';
    $msg .= __('Ip', "antihacker") . ': ' . $antihacker_ip . '<br>';
    $msg .= __('Domain', "antihacker") . ': ' . $dom . '<br>';
    $msg .= __('User', "antihacker") . ': ' . $user_login;
    $msg .= '<br>';
    $msg .= __('Add this IP to your whitelist to stop this email and change your Notification Settings.', "antihacker");
    $email_from = 'wordpress@' . $dom;
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: " . $email_from . "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $to = $antihacker_admin_email;
    $subject = __('Login Successful at', "antihacker") . ': ' . $dom;
    wp_mail($to, $subject, $msg, $headers, '');
    return 1;
}
/*
function antihacker_activ_message()
{
    echo '<div class="updated"><p>';
    $bd_msg = '<img src="' . ANTIHACKERURL . '/images/infox350.png" />';
    $bd_msg .= '<h2>';
    $bd_msg .= __('Anti Hacker Plugin was activated!', "antihacker");
    $bd_msg .= '</h2>';
    $bd_msg .= '<h3>';
    $bd_msg .= __('For details and help, take a look at Anti Hacker at your left menu', "antihacker");
    $bd_msg .= '<br />';
    $bd_msg .= ' <a class="button button-primary" href="admin.php?page=anti-hacker">';
    $bd_msg .= __('or click here', "antihacker");
    $bd_msg .= '</a>';
    echo esc_attr($bd_msg);
    echo "</p></h3></div>";
}
*/

function antihacker_adm_enqueue_scripts2()
{
    global $antihacker_current_screen;
    wp_enqueue_script('wp-pointer');
    require_once ABSPATH . 'wp-admin/includes/screen.php';
    $myscreen = get_current_screen();
    $antihacker_current_screen = $myscreen->id;
    $dismissed_string = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
    // $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
   // if (in_array('plugins', $dismissed)) {  
    if ( !empty($dismissed_string))  {
        $r = update_option('antihacker_was_activated', '0');
        if (!$r) {
            add_option('antihacker_was_activated', '0');
        }
        return;
    }
    if (get_option('antihacker_was_activated', '0') == '1') 
      add_action('admin_print_footer_scripts', 'antihacker_admin_print_footer_scripts');
}

function antihacker_admin_print_footer_scripts()
{
    global $antihacker_current_screen;
    $pointer_content = 'Open Anti Hacker Plugin Here!';
    $pointer_content2= 'Just Click Over Anti Hacker, then Go To Settings=>StartUp Guide.';
    ?>
        <script type="text/javascript">
        //<![CDATA[
            // setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
        jQuery(document).ready( function($) {
            $('#toplevel_page_anti_hacker_plugin').pointer({
                content: '<?php echo '<h3>'.esc_attr($pointer_content).'</h3><p>'.esc_attr($pointer_content2);?>',
                position: {
                        edge: 'left',
                        align: 'right'
                    },
                close: function() {
                    // Once the close button is hit
                    $.post( ajaxurl, {
                            pointer: '<?php echo esc_attr($antihacker_current_screen); ?>',
                            action: 'dismiss-wp-pointer'
                        });
                }
            }).pointer('open');
            /* $('.wp-pointer-undefined .wp-pointer-arrow').css("right", "50px"); */
        });
        //]]>
        </script>
        <?php
}


function antihacker_my_deactivation()
{
    // require_once (ANTIHACKERPATH . "includes/feedback/feedback.php");
    global $antihacker_admin_email, $antihacker_ip;
    global $antihacker_server;
    $current_user = wp_get_current_user();
    $user_login = $current_user->user_login;
    $dt = date("Y-m-d H:i:s");
    $dom = $antihacker_server;
    $url = sanitize_url($_SERVER['PHP_SELF']);
    $msg = __('Alert: the Anti Hacker plugin was been deactivated from plugins page.', "antihacker");
    $msg .= '<br>';
    $msg .= __('Date', "antihacker") . ': ' . $dt . '<br>';
    $msg .= __('Ip', "antihacker") . ': ' . $antihacker_ip . '<br>';
    $msg .= __('Domain', "antihacker") . ': ' . $dom . '<br>';
    $msg .= __('User', "antihacker") . ': ' . $user_login;
    $msg .= '<br>';
    $msg .= __('This email was sent from your website', "antihacker") . ' ' . $dom . ' ';
    $msg .= __('by Anti Hacker plugin.', "antihacker") . '<br>';
    $email_from = 'wordpress@' . $dom;
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: " . $email_from . "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $to = $antihacker_admin_email;
    $subject = __('Plugin Deactivated at', "antihacker") . ': ' . $dom;
    wp_mail($to, $subject, $msg, $headers, '');
    return 1;
}
function antihacker_user_enumeration_email()
{
    global $antihacker_admin_email, $antihacker_ip;
    global $antihacker_amy_whitelist;

    global $antihacker_ua, $antihacker_string_whitelist;
    global $antihacker_server;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $current_user = wp_get_current_user();
    $user_login = $current_user->user_login;
    $dt = date("Y-m-d H:i:s");
    $dom = $antihacker_server;
    $url = sanitize_url($_SERVER['PHP_SELF']);
    $msg = __('Alert: User Enumeration attempt was blocked.', "antihacker");
    $msg .= '<br>';
    $msg .= __('Date', "antihacker") . ': ' . $dt . '<br>';
    $msg .= __('Ip', "antihacker") . ': ' . $antihacker_ip . '<br>';
    $msg .= __('Domain', "antihacker") . ': ' . $dom . '<br>';
    $msg .= '<br>';
    $msg .= __('This email was sent from your website', "antihacker") . ' ' . $dom . ' ';
    $msg .= __('by Anti Hacker plugin.', "antihacker") . '<br>';
    $msg .= '<br>';
    $msg .= __('You can disable it in Dashboard => Anti Hacker => Settings => Notifications', "antihacker");
    $msg .= '<br>';
    $email_from = 'wordpress@' . $dom;
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: " . $email_from . "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $to = $antihacker_admin_email;
    $subject = __('User Enumeration attempt was blocked', "antihacker") . ': ' . $dom;
    wp_mail($to, $subject, $msg, $headers, '');
    return 1;
}
function antihacker_blocked_email($type)
{
    global $antihacker_admin_email, $antihacker_ip;
    global $antihacker_amy_whitelist;

    global $antihacker_ua, $antihacker_string_whitelist;
    global $antihacker_server;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $current_user = wp_get_current_user();
    $user_login = $current_user->user_login;
    $dt = date("Y-m-d H:i:s");
    $dom = $antihacker_server;
    $url = sanitize_url($_SERVER['PHP_SELF']);
    $msg = __('Alert: Attack was blocked:', "antihacker");
    $msg .= ' ' . $type;
    $msg .= '<br>';
    $msg .= __('Date', "antihacker") . ': ' . $dt . '<br>';
    $msg .= __('Ip', "antihacker") . ': ' . $antihacker_ip . '<br>';
    $msg .= __('Domain', "antihacker") . ': ' . $dom . '<br>';
    $msg .= '<br>';
    $msg .= __('This email was sent from your website', "antihacker") . ' ' . $dom . ' ';
    $msg .= __('by Anti Hacker plugin.', "antihacker") . '<br>';
    $msg .= '<br>';
    $msg .= __('You can disable it in Dashboard => Anti Hacker => Settings => Notifications', "antihacker");
    $msg .= '<br>';
    $email_from = 'wordpress@' . $dom;
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: " . $email_from . "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $to = $antihacker_admin_email;
    $subject = __('Attack was blocked', "antihacker") . ': ' . $dom;
    wp_mail($to, $subject, $msg, $headers, '');
    return 1;
}
function antihacker_ah_email_display()
{ ?>
    <!-- <INPUT TYPE=CHECKBOX NAME="my_captcha">Yes, i'm a human! -->
    <?php echo esc_attr__('My Wordpress user email:', "antihacker"); ?>
    <br />
    <input type="text" id="myemail" name="myemail" value="" placeholder="" size="100" />
    <br />
<?php
}
function antihacker_failed_login($user_login)
{
    global $antihacker_amy_whitelist;
    global $antihacker_checkbox_all_failed;
    global $antihacker_ip;
    global $antihacker_admin_email;
    global $antihacker_server;
    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)) {
        return;
    }
    if ($antihacker_checkbox_all_failed == '1') {
        $dt = date("Y-m-d H:i:s");
        $dom = $antihacker_server;
        $msg =  __('This email was sent from your website', "antihacker");
        $msg .= ': ' . $dom . ' ';
        $msg .=  __('by the AntiHacker plugin.', "antihacker");
        $msg .= '<br> ';
        $msg .= __('Date', "antihacker");
        $msg .= ': ' . $dt . '<br>';
        $msg .= __('Ip', "antihacker") . ': ' . $antihacker_ip . '<br>';
        $msg .= __('Domain', "antihacker") . ': ' . $dom . '<br>';
        $msg .= __('User', "antihacker") . ': ' . $user_login;
        $msg .= '<br>';
        $msg .= __('Failed login', "antihacker");
        $msg .= '<br>';
        $msg .= '<br>';
        $msg .= __('You can stop emails at the Notifications Settings Tab.', "antihacker");
        $msg .= '<br>';
        $msg .= __('Dashboard => Anti Hacker => Notifications Settings.', "antihacker");
        $email_from = 'wordpress@' . $dom;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= "From: " . $email_from . "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $to = $antihacker_admin_email;
        $subject = __('Failed Login at:', "antihacker") . ' ' . $dom;
        wp_mail($to, $subject, $msg, $headers, '');
    }
    antihacker_stats_moreone('qlogin');
    // antihacker_response('Failed Login');
    antihacker_gravalog('Failed Login');
    return;
}
function antihacker_premove_xmlrpc_pingback_ping($methods)
{
    unset($methods['pingback.ping']);
    return $methods;
};
/////////////////////////////////////////
// Disable Json WordPress Rest API (also embed from WordPress 4.7). 
// Take a look our faq page (at our site) for details.'
function antihacker_after_inic()
{
    
    if(antihacker_isourserver()  )
     return true;



    $ah_current_WP_version = get_bloginfo('version');
    function antihacker_Force_Auth_Error()
    {
        add_filter('rest_authentication_errors', 'antihacker_only_allow_logged_in_rest_access');
    }
    function antihacker_Disable_Via_Filters()
    {
        if(antihacker_isourserver()  )
          return true;
        
        // Filters for WP-API version 1.x
        add_filter('json_enabled', '__return_false');
        add_filter('json_jsonp_enabled', '__return_false');
        // Filters for WP-API version 2.x
        add_filter('rest_enabled', '__return_false');
        add_filter('rest_jsonp_enabled', '__return_false');
        // Remove REST API info from head and headers
        remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('template_redirect', 'rest_output_link_header', 11);
        // 2019-04-23
        add_filter('rest_authentication_errors', function ($result) {
            if (!empty($result)) {
                return $result;
            }
            if (!is_user_logged_in()) {
                return new WP_Error('rest_not_logged_in', 'You are not currently logged in.', array('status' => 401));
            }
            return $result;
        });
    }
    function antihacker_only_allow_logged_in_rest_access($access)
    {
        if (!is_user_logged_in()) {
            return new WP_Error('rest_cannot_access', __('Only authenticated users can access API.', 'disable-json-api'), array('status' => rest_authorization_required_code()));
        }
        return $access;
    }
    if (version_compare($ah_current_WP_version, '4.7', '>=')) {
        antihacker_Force_Auth_Error();
    } else {
        antihacker_Disable_Via_Filters();
    }
}
function antihacker_debug_enabled()
{
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<br /><b>';
    echo esc_attr__('Message from Anti Hacker Plugin', 'antihacker');
    echo ':</b><br />';
    echo esc_attr__('Looks like Debug mode is enabled. (WP_DEBUG is true)', 'antihacker');
    echo '.<br />';
    echo esc_attr__('if enabled on a production website, it might cause information disclosure, allowing malicious users to view errors and additional logging information', 'antihacker');
    echo '.<br />';
    echo esc_attr__('Please, take a look in our site, FAQ page, item => Wordpress Debug Mode or disable this message at General Settings Tab. ', 'antihacker');
    echo '<br /><br /></div>';
}
function antihacker_alertme3($antihacker_string)
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist) or $antihacker_Blocked_Firewall <> 'yes') {
        return;
    }
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked by firewall.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = __('Malicious String Found:', 'antihacker') . " " . $antihacker_string;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme4($antihacker_string)
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist) or $antihacker_Blocked_Firewall <> 'yes') {
        return;
    }
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked by firewall.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = __('Malicious User Agent Found:', 'antihacker') . " " . $antihacker_string;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme5()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked Looking for Plugin Vulnerabilities.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme6()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global  $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked Looking for Theme Vulnerabilities.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme7()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because is false Search Engine Google/Bing-Microsoft/Slurp.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme8()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global  $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because previously it is looking for vulnerabilities in this site.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme9()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    global  $antihacker_current_url;
    $subject = __("Detected Tor on ", "antihacker") . $antihacker_server;
    $message[] = __("Traffic from Tor detected and blocked.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Tor IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme10()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global $antihacker_ua;
    global  $antihacker_current_url;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because using HTTP Tools.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme11()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global  $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because attempt to Login.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme12()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global  $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because Blank User Agent.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email,  $subject, $msg);
    return;
}

function antihacker_alertme13()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global $antihacker_current_url;
    global $antihacker_Blocked_else_email;
    global $antihacker_ua, $antihacker_string_whitelist;

    $antihacker_Blocked_else_email = trim(sanitize_text_field(get_site_option('antihacker_Blocked_else_email', 'no')));
    $antihacker_Blocked_else_email = strtolower($antihacker_Blocked_else_email);



    if ($antihacker_Blocked_else_email != 'yes')
      return;



    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected  on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because attempt to Login with Bot.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme14()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global  $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;


    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Malicious attack was detected and blocked because excedeed number page open limit", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_alertme15()
{
    global $antihacker_ip, $antihacker_amy_whitelist, $antihacker_admin_email;
    global $antihacker_Blocked_Firewall, $antihacker_server;
    global  $antihacker_current_url;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $subject = __("Detected on ", "antihacker") . $antihacker_server;
    $message[] = __("Attack was detected and blocked because accessing xmlrpc.php file and it was blocked on settings page.", "antihacker");
    $message[] = "";
    $message[] = __('Date', 'antihacker') . "..............: " . date("F j, Y, g:i a");
    $message[] = __('Robot IP Address', 'antihacker') . "..: " . $antihacker_ip;
    $message[] = "";
    $message[] = __('URL requested:', 'antihacker') . " " . $antihacker_current_url;
    $message[] = "";
    $message[] = __('User Agent:', 'antihacker') . " " . $antihacker_ua;
    $message[] = "";
    $message[] = __('eMail sent by Anti Hacker Plugin.', 'antihacker');
    $message[] = __(
        'You can stop emails at the Notifications Settings Tab.',
        'antihacker'
    );
    $message[] = __('Dashboard => Anti Hacker => Settings.', 'antihacker');
    $message[] = "";
    $msg = join("\n", $message);
    wp_mail($antihacker_admin_email, $subject, $msg);
    return;
}
function antihacker_change_note_submenu_order($menu_ord)
{
    global $submenu;
    function antihacker_str_replace_json($search, $replace, $subject)
    {
        return json_decode(str_replace($search, $replace, json_encode($subject)), true);
    }
    $key = 'Anti Hacker';
    $val = 'Dashboard';
    $submenu = antihacker_str_replace_json($key, $val, $submenu);
}
function antihacker_populate_stats()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_stats";

    
    $query = "SELECT * FROM $table_name";
    $wpdb->query($query);
    
    // error
    /*
    $wpdb->query($wpdb->prepare("
    SELECT  * FROM `$table_name`"));
    */

    
    if ($wpdb->num_rows > 359)
        return;
    for ($i = 01; $i < 13; $i++) {
        for ($k = 01; $k < 32; $k++) {
            $year = 2020;
            if (!checkdate($i, $k,  $year))
                continue;
            $mdata = (string) $i;
            if (strlen($mdata) < 2)
                $mdata = '0' . $mdata;
            $ddata = (string) $k;
            if (strlen($ddata) < 2)
                $ddata = '0' . $ddata;
            $data = $mdata . $ddata;

            /*
            $query = "select *  from " . $table_name . " WHERE date = '" . $data .
                "' LIMIT 1";
            $wpdb->query($query);
            */

            $wpdb->query($wpdb->prepare("
            SELECT  * FROM `$table_name` WHERE date = %s LIMIT 1",$data));

            if ($wpdb->num_rows > 0)
                continue;

            /*
            $query = "INSERT INTO " . $table_name .
                " (date)
                  VALUES ('" . $data . "')";
            $r = $wpdb->get_results($query);
            */

            $r = $wpdb->get_results($wpdb->prepare(
                "INSERT INTO `$table_name`
                (`date`) 
                VALUES (%s)", $data));

        }
    }
}

function antihacker_populate_rules()
{
  global $wpdb;
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


  $file = ANTIHACKERPATH . 'assets/rules.txt';
  $file2 = ANTIHACKERPATH . 'assets/rules2.txt';
  


  $table_name = $wpdb->prefix . "ah_rules";

  $query = "select COUNT(*) from " . $table_name;

  $file = ANTIHACKERPATH . 'assets/rules.txt';
  if ($wpdb->get_var($query) > 700) {
      //  796)
        try {
            if (!file_exists($file) or !file_exists($file2) ) {
               return;
            }
        } catch (Exception $e) {
            // echo 'Error: ' . $e->getMessage();
        }
      $query = "TRUNCATE TABLE $table_name";
      $wpdb->query($query);
  }

  try {
    if (!file_exists($file) or !file_exists($file2) ) {
       // return;
       error_log('Fail to Open File Rules.txt path: '.$file);
    }
  } catch (Exception $e) {
     // echo 'Error: ' . $e->getMessage();
  }


  try {
    $fhandle = @fopen($file, "r");
    // $file2 = ANTIHACKERPATH . 'assets/rules2.txt';
    $fhandle2 = @fopen($file2, "r");
  } catch (Exception $e) {
     // echo 'Error: ' . $e->getMessage();
  }  
  if (!$fhandle)
    die('Fail to Open File Rules.txt path: '. esc_attr($file));

  if (!$fhandle2 )
    die('Fail to Open File Rules.txt path: '.esc_attr($file2));

  // while (!feof($fhandle)) {
  while (($line1 = fgets($fhandle)) !== false && ($line2 = fgets($fhandle2)) !== false) {
    
    $line1 = rtrim($line1);
    $line2 = rtrim($line2);

    $segments1 = str_split($line1, 4);
    $segments2 = str_split($line2, 4);

    $tempString = '';

    for ($i = 0; $i < max(count($segments1), count($segments2)); $i++) {
            $tempString .= isset($segments1[$i]) ? $segments1[$i] : '';
            $tempString .= isset($segments2[$i]) ? $segments2[$i] : '';
    }

    $query = $tempString;
    //  $query = fgets($fhandle);

      // INSERT INTO `wp_ah_rules` (`id`, `name`, `strings`, `cond`, `descri`, `autor`, `url`, `obs`, `flag`) VALUES(1, 'ajaxcommand', 'JGEgPSAiQWpheCBDb21tYW5kIFNoZWxsIGJ5Ig==\nJGIgPSAiYSBocmVmPWh0dHA6Ly93d3cuaXJvbndhcmV6LmluZm8i\nJGMgPSAiJ0NsZWFyIEhpc3RvcnknID0+ICdDbGVhckhpc3RvcnkoKSci\nJGQgPSAiZm9yIHNvbWUgZWhoLi4uaGVscCI=\n', 'any of them', ' Ajax Command shell', '', ' https://github.com/tennc/webshell/blob/master/xakep-shells/PHP/Ajax_PHP%20Command%20Shell.php.txt', '', '');
      // $table_name 
      $query = str_replace("wp_ah_rules", $table_name, $query);

      $r = $wpdb->get_results($query);

      if($wpdb->last_error != ''){
        echo '<hr>';
        var_dump($query);
        echo '<hr>';
        $wpdb->print_error();
        die('Anti Hacker Plugin: Error to write Rules to Database');
      }
      
  }

  fclose($fhandle);
  fclose($fhandle2);
  // debug 
   unlink($file);
   unlink($file2);
}


function antihacker_stats_moreone($qtype)
{
    global $wpdb;
    $qtoday = date("m") + date("d");

// Parse error: syntax error, unexpected 'm' (T_STRING), expecting ')' in /home/minozzi/public_html/wp-content/plugins/antihacker/includes/functions/functions.php on line 1287


    $mdata = date("m");
    $ddata = date("d");
    $mdata = (string) $mdata;
    if (strlen($mdata) < 2)
        $mdata = '0' . $mdata;
    $ddata = (string) $ddata;
    if (strlen($ddata) < 2)
        $ddata = '0' . $ddata;
    $qtoday = $mdata . $ddata;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


    $table_name = $wpdb->prefix . "ah_stats";
/*
    `id` mediumint(9) NOT NULL,
    `date` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
 
   - `qlogin` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qfire` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qenum` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qplugin` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qtema` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qfalseg` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qblack` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qtor` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qnoref` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qtools` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qblank` text COLLATE utf8mb4_unicode_ci NOT NULL,
   - `qrate` text COLLATE utf8mb4_unicode_ci NOT NULL,
 
   - `qtotal` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
   - `xmlrpc` text COLLATE utf8mb4_unicode_ci NOT NULL
*/

    if ($qtype != 'qfire'
		and $qtype != 'qenum'
		and $qtype != 'qlogin'
		and $qtype != 'qtor'
		and $qtype != 'qplugin'
		and $qtype != 'qfalseg'
		and $qtype != 'qtema'
		and $qtype != 'qtotal'
		and $qtype != 'qtools'
		and $qtype != 'qrate'
		and $qtype != 'qnoref'
		and $qtype != 'qblank'
		and $qtype != 'xmlrpc'
		and $qtype != 'qblack') {
		return;
	}

    antihacker_populate_stats();

    $r = $wpdb->get_results(
        "UPDATE  `$table_name`
         SET $qtype = $qtype + 1, 
         qtotal = qtotal+1 
         WHERE date = $qtoday");

  //  $str = var_export(debug_backtrace(),true);
  //  error_log($str);

// mail('sergiominozzi@gmail.com', "linha 1334",$str);


/*
    // Does't work
    $r = $wpdb->get_results($wpdb->prepare(
        "UPDATE  `$table_name`
        SET %s = %s + 1, 
        qtotal = qtotal+1 
        WHERE date = %s", $qtype, $qtoday));
        */


    if (!empty($wpdb->last_error))
        antihacker_upgrade_db();
}
function antihacker_create_db_stats()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "ah_stats";
    global $wpdb;
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `date` varchar(4) NOT NULL,
        `qlogin` text NOT NULL,
        `qfire` text NOT NULL,
        `qenum` text NOT NULL, 
        `qplugin` text NOT NULL, 
        `qtema` text NOT NULL, 
        `qfalseg` text NOT NULL, 
        `qblack` text NOT NULL, 
        `qtor` text NOT NULL, 
        `qnoref` text NOT NULL, 
        `qtools` text NOT NULL, 
        `qblank` text NOT NULL, 
        `qrate` text NOT NULL,  
        `xmlrpc` text NOT NULL,    
        `qtotal` varchar(100) NOT NULL,
    UNIQUE (`id`),
    UNIQUE (`date`)
    ) $charset_collate;";
    dbDelta($sql);
}
function antihacker_create_db_blocked()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "ah_blockeds";
    global $wpdb;
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `ip` TEXT NOT NULL,
    UNIQUE (`id`)
    ) $charset_collate;";
    dbDelta($sql);
    $alter = "CREATE INDEX ip ON " . $table . " (`ip`(50))";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
}
function antihacker_create_db_visitors()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "ah_visitorslog";
    global $wpdb;
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `ip` text NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `human` varchar(1) NOT NULL,
        `response` varchar(5) NOT NULL,
        `bot` varchar(1) NOT NULL,
        `method` varchar(10) NOT NULL,
        `url` text NOT NULL,
        `referer` text NOT NULL,  
        `ua` TEXT NOT NULL,
        `access` varchar(10) NOT NULL,
        `reason` text NOT NULL,
    UNIQUE (`id`)
    ) $charset_collate;";
    dbDelta($sql);
  
    // $alter = "CREATE INDEX ip2 ON " . $table . " (`ip`(50))";
    ob_start();
    $wpdb->query("CREATE INDEX ip2 ON  `$table` (`ip`(50))");
    ob_end_clean();
    // dbDelta($sql);
}
function antihacker_create_db_fingerprint()
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = $wpdb->prefix . "ah_fingerprint";
    if (antihacker_tablexist($table)) {
        return;
    }
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `ip` varchar(50) NOT NULL,
        `fingerprint` text NOT NULL,
        `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (`id`),
    UNIQUE (`ip`)
    ) $charset_collate;";
    dbDelta($sql);
}
function antihacker_create_db_tor()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // creates my_table in database if not exists
    $table = $wpdb->prefix . "ah_tor";
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `ip` varchar(50) NOT NULL,
    UNIQUE (`ip`)
    ) $charset_collate;";
    dbDelta($sql);
    // $sql = "CREATE INDEX ip ON " . $table . " (ip)";
    $sql = "CREATE INDEX ip ON " . $table . " (`ip`(50))";
    dbDelta($sql);
}
function antihacker_create_db_scan_files()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table = $wpdb->prefix . "ah_scan_files";
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `flag` varchar(1) NOT NULL,
        `obs` text NOT NULL,
        UNIQUE (`id`),
        UNIQUE (`name`)
    )";
    dbDelta($sql);
}
function antihacker_create_db_scan()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table = $wpdb->prefix . "ah_scan";
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `date_inic` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `date_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
        `log` longtext NOT NULL,
        `qfiles` int(11) NOT NULL,
        `pointer` int(11) NOT NULL,
        `mystatus` varchar(20) NOT NULL,
        `debug` longtext NOT NULL,
        `malware` longtext NOT NULL,
        `flag` varchar(1) NOT NULL,
        `obs` text NOT NULL,
        UNIQUE (`id`)
    )";
    dbDelta($sql);
}
function antihacker_create_db_rules()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table = $wpdb->prefix . "ah_rules";
    if (antihacker_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
            `id` int(11) NOT NULL,
            `name` varchar(100) NOT NULL,
            `strings` text NOT NULL,
            `cond` text NOT NULL,
            `descri` text NOT NULL,
            `autor` text NOT NULL,
            `url` text NOT NULL,
            `obs` text NOT NULL,
            `flag` varchar(1) NOT NULL
    )";
    // die($sql);

    dbDelta($sql);
}

// ini` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
// `dfim` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
function antihacker_activated()
{
    ob_start();
    global $antihacker_my_whitelist;
    global $antihacker_string_whitelist;
    global $antihacker_admin_email;
    global $antihacker_update_http_tools;
    global $antihacker_http_tools;

    antihacker_create_db_stats();
    antihacker_create_db_blocked();
    antihacker_create_db_visitors();
    antihacker_create_db_fingerprint();
    antihacker_upgrade_db();
    antihacker_upgrade_db_visitors();
    antihacker_upgrade_db_blocked();
    antihacker_upgrade_db_tor();
    antihacker_populate_stats();
    global $antihacker_is_admin;

    if (empty($antihacker_http_tools) or $antihacker_update_http_tools == 'yes'){
        antihacker_create_httptools();
    }
        

    $antihacker_ip = antihacker_findip();
    if ($antihacker_is_admin) {


        if (empty($antihacker_my_whitelist)) {
            if (get_site_option('antihacker_my_whitelist') !== false) {
                $return = update_site_option('antihacker_my_whitelist', $antihacker_ip);
            } else {
                $return = add_site_option('antihacker_my_whitelist', $antihacker_ip);
            }
        }
    }
    $antihacker_installed = trim(get_option('antihacker_installed', ''));
    if (empty($antihacker_installed)) {
        add_option('antihacker_installed', time());
        update_option('antihacker_installed', time());
    }

        // Pointer

        $r = update_option('antihacker_was_activated', '1');
        if (!$r) {
            add_option('antihacker_was_activated', '1');
        }
        $pointers = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
     
     //var_dump($pointers);
     //die();
     
        $pointers = ''; // str_replace( 'plugins', '', $pointers );
        update_user_meta(get_current_user_id(), 'dismissed_wp_pointers', $pointers);

        

    ob_end_clean();
}
function antihacker_tablexist($table)
{
    global $wpdb;
    $table_name = $table;
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
        return true;
    else
        return false;
}



function antihacker_check_memory() {
    // global $memory;
    $memory["color"] = "font-weight:normal;";
    try {

        // PHP $memory["limit"]
        if(!function_exists('ini_get')){
            $memory["msg_type"] = "notok";
            return $memory;
        }
        else{
            $memory["limit"] = (int) ini_get("memory_limit");
        }

        if (!is_numeric($memory["limit"])) {
            $memory["msg_type"] = "notok";
            return $memory;
        } else {
            if ($memory["limit"] > 9999999) {
                $memory["limit"] =
                    $memory["limit"] / 1024 / 1024;
            }
        }


        // usage
        if(!function_exists('memory_get_usage')){
            $memory["msg_type"] = "notok";
            return $memory;
        }
        else{
            // $bill_install_memory["usage"] = round(memory_get_usage() / 1024 / 1024, 0);
            $memory["usage"] = (int) memory_get_usage();
        }


        if ($memory["usage"] < 1) {
            $memory["msg_type"] = "notok";
            return $memory;
        }
        else{
            $memory["usage"] = round($memory["usage"] / 1024 / 1024, 0);

        }

        if (!is_numeric($memory["usage"])) {
            $memory["msg_type"] = "notok";
            return $memory;
        }


        // WP
        if (!defined("WP_MEMORY_LIMIT")) {
            $memory['wp_limit'] = 40;
        } else {
            $memory['wp_limit'] = (int) WP_MEMORY_LIMIT;

        }		

        $memory["percent"] =
            $memory["usage"] / $memory["wp_limit"];
        $memory["color"] = "font-weight:normal;";
        if ($memory["percent"] > 0.7) {
            $memory["color"] = "font-weight:bold;color:#E66F00";
        }
        if ($memory["percent"] > 0.85) {
            $memory["color"] = "font-weight:bold;color:red";
        }
        $memory["msg_type"] = "ok";
        return $memory;
    } catch (Exception $e) {
        $memory["msg_type"] = "notok(7)";
        return $memory;
    }
}

function antihacker_message_low_memory()
{
    echo '<div class="notice notice-warning">
                     <br />
                     <b>
                     Anti Hacker Plugin Warning: You need increase the WordPress memory limit!
                     <br />
                     Please, check 
                     <br />
                     Dashboard => Anti Hacker => (tab) Memory Checkup
                     <br /><br />
                     </b>
                     </div>';
}
/*
function antihacker_control_availablememory()
{
    $anti_hacker_memory = antihacker_check_memory();
    if ($anti_hacker_memory['msg_type'] == 'notok')
        return;

    //    if ($anti_hacker_memory['percent'] > .7  or $anti_hacker_memory_free < 30 ) {
 
    if ($anti_hacker_memory['percent'] > .7 or $antihacker_memory['free'] < 30)
        add_action('admin_notices', 'antihacker_message_low_memory');

}
*/
function antihacker_find_perc()
{
    global $antihacker_checkversion;
    global $antihacker_notif_scan;

    $antihacker_option_name[] = 'my_radio_xml_rpc';
    $antihacker_option_name[] = 'antihacker_rest_api';
    $antihacker_option_name[] = 'antihacker_automatic_plugins';
    $antihacker_option_name[] = 'antihacker_automatic_themes';
    $antihacker_option_name[] = 'antihacker_replace_login_error_msg';
    $antihacker_option_name[] = 'antihacker_disallow_file_edit';
    $antihacker_option_name[] = 'antihacker_debug_is_true';
    $antihacker_option_name[] = 'antihacker_firewall';
    $antihacker_option_name[] = 'antihacker_hide_wp';
    $antihacker_option_name[] = 'antihacker_block_enumeration';
    $antihacker_option_name[] = 'antihacker_block_all_feeds';
    $antihacker_option_name[] = 'antihacker_new_user_subscriber';
    $antihacker_option_name[] = 'antihacker_block_falsegoogle';
    $antihacker_option_name[] = 'antihacker_block_search_plugins';
    $antihacker_option_name[] = 'antihacker_block_search_themes';
    $antihacker_option_name[] = 'antihacker_block_tor';
    $antihacker_option_name[] = 'antihacker_block_falsegoogle';
    $antihacker_option_name[] = 'antihacker_block_http_tools';
    $antihacker_option_name[] = 'antihacker_blank_ua';
    $antihacker_option_name[] = 'antihacker_radio_limit_visits';
    $antihacker_option_name[] = 'antihacker_application_password';
    $perc = 1;
    $wnum = count($antihacker_option_name);
    for ($i = 0; $i < $wnum; $i++) {
        $yes_or_not = trim(sanitize_text_field(get_site_option($antihacker_option_name[$i], '')));
        if (strtoupper($yes_or_not) == 'YES')
            $perc = $perc + (10 / ($wnum + 1));
    }
    if (empty($antihacker_checkversion) and $perc > 7)
        $perc = 7;
    $perc = round($perc, 0, PHP_ROUND_HALF_UP);

    if ($perc > 10)
        $perc = 10;


    // 7 days
    //antihacker_notif_scan
    if(time() > ( $antihacker_notif_scan + 60*60*24*7 ))
      $perc = $perc - 2;

    if ($perc < 0)
        $perc = 0;
    

    return $perc;
}
function antihacker_filter_rest_endpoints($endpoints)
{
    global $antihacker_Blocked_userenum_email;
    ///wp-json/contact-form-7/v1/contact-forms/571/feedback
    $workurl = sanitize_url($_SERVER['REQUEST_URI']);
    if (stripos($workurl, 'contact-form-7') !== false)
        return $endpoints;
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
        if ($antihacker_Blocked_userenum_email == 'yes')
            antihacker_user_enumeration_email();
        antihacker_stats_moreone('qenum');
        antihacker_response('User enumeration');
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        if ($antihacker_Blocked_userenum_email == 'yes')
            antihacker_user_enumeration_email();
        antihacker_stats_moreone('qenum');
        antihacker_response('User enumeration');
    }
    if (isset($endpoints['/wp/v2/posts'])) {
        unset($endpoints['/wp/v2/posts']);
        if ($antihacker_Blocked_userenum_email == 'yes')
            antihacker_user_enumeration_email();
        antihacker_stats_moreone('qenum');
        antihacker_response('User enumeration');
    }
    return $endpoints;
}
function antihacker_block_enumeration()
{
    global $antihacker_Blocked_userenum;
    if (isset($_SERVER['REQUEST_URI'])) {
        if (!preg_match('/(wp-comments-post)/', $_SERVER['REQUEST_URI']) && !empty($_REQUEST['author']) && (int) $_REQUEST['author']) {
            if ($antihacker_Blocked_userenum == 'yes')
                antihacker_user_enumeration_email();
            antihacker_stats_moreone('qenum');
            antihacker_response('User enumeration');
        }
    }
}
function antihacker_remove_index()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_visitorslog";
    if (!$wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
        return;
    $query = 'show index from '.$table_name;
    $result = $wpdb->get_results($query);
    $result = json_decode(json_encode($result), true);
    $query = "SELECT COUNT(1) indexExists FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema=DATABASE() AND table_name='" . $table_name . "' AND index_name='ip'";
    $result = $wpdb->get_var($query);
    if ($result > 0) {
        $query = "ALTER TABLE ".$table_name. " DROP INDEX ip";
        ob_start();
        $wpdb->query($query);
        ob_end_clean();
    }
    $query = "SELECT COUNT(1) indexExists FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema=DATABASE() AND table_name='" . $table_name . "' AND index_name='date'";
    $result = $wpdb->get_var($query);
    if ($result > 0) {
        $query = "ALTER TABLE ". $table_name. " DROP INDEX date";
        ob_start();
        $wpdb->query($query);
        ob_end_clean();
    }
    /*
    foreach ($result as $results) {
        if ($results['Column_name'] == 'ip') {
            $query = "ALTER TABLE `$table_name` DROP INDEX ip";
            ob_start();
            $wpdb->query($query);
            ob_end_clean();
        }
        if ($results['Column_name'] == 'date') {
            $query = "ALTER TABLE `$table_name` DROP INDEX date";
            ob_start();
            $wpdb->query($query);
            ob_end_clean();
        }
    } */
}

function antihacker_add_index()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_visitorslog";
    if (!$wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
        return;
    $query = 'show index from ' . $table_name;
    $result = $wpdb->get_results($query);
    $result = json_decode(json_encode($result), true);
    foreach ($result as $results) {
        if ($results['Column_name'] == 'ip2') {
            return;
        }
    }
   // $sql = "CREATE INDEX ip2 ON " . $table_name . " (ip)";
    $alter = "CREATE INDEX ip2 ON " . $table . " (`ip`(50))";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
  //  dbDelta($sql);
}

function antihacker_upgrade_db()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_stats";
    if (!antihacker_tablexist($table_name))
        return;
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qenum'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qenum text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qplugin'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qplugin text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qtema'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qtema text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qfalseg'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qfalseg text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qblack'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qblack text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qtor'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qtor text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qnoref'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qnoref text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qtools'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qtools text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qblank'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qblank text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }

    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'xmlrpc'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD xmlrpc text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }





    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'qrate'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD qrate text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
}
function antihacker_upgrade_db_blocked()
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_blockeds";
    if (!antihacker_tablexist($table_name)) {
        return;
    }
    $query = "ALTER TABLE " . $table_name . " DROP INDEX ip";
    ob_start();
    $wpdb->query($query);
    ob_end_clean();
    $alter = "ALTER TABLE " . $table_name . " MODIFY `ip`  varchar(50) NOT NULL";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
    $alter = "CREATE INDEX ip ON " . $table_name . " (`ip`(50))";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
}
function antihacker_upgrade_db_tor()
{
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_tor";
    if (!antihacker_tablexist($table_name)) {
        return;
    }
    $query = "ALTER TABLE " . $table_name . " DROP INDEX ip";
    ob_start();
    $wpdb->query($query);
    ob_end_clean();
    $alter = "ALTER TABLE " . $table_name . " MODIFY `ip` varchar(50) NOT NULL";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
    $alter = "CREATE INDEX ip ON " . $table_name . " (`ip`(50))";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
}
function antihacker_upgrade_db_visitors()
{
    global $wpdb, $wp_filesystem;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    // $table_name = $wpdb->prefix . "sbb_badips";
    $table_name = $wpdb->prefix . "ah_visitorslog";
    if (!antihacker_tablexist($table_name)) {
        return;
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'human'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD human varchar(10) NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $alter = "ALTER TABLE " . $table_name . " MODIFY `ip` varchar(50) NOT NULL";
    ob_start();
    $wpdb->query($alter);
    ob_end_clean();
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'method'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD method text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'url'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD url text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'referer'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD referer text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'ua'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD ua text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'access'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD access varchar(10) NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'bot'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD bot varchar(10) NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SHOW COLUMNS FROM " . $table_name . " LIKE 'reason'";
    $wpdb->query($query);
    if (empty($wpdb->num_rows)) {
        $alter = "ALTER TABLE " . $table_name . " ADD reason text NOT NULL";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
    $query = "SELECT COUNT(1) indexExists FROM INFORMATION_SCHEMA.STATISTICS
    WHERE table_schema=DATABASE() AND table_name='" . $table_name . "' AND index_name='ip2'";
    $result = $wpdb->get_var($query);
    if ($result < 1) {
        $alter = "CREATE INDEX ip2 ON " . $table_name . " (`ip`(50))";
        ob_start();
        $wpdb->query($alter);
        ob_end_clean();
    }
}
function anti_hacker_dangerous_file()
{
    global $antihacker_dangerous_file;
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<br /><b>';
    echo esc_attr__('Message from Anti Hacker Plugin', 'antihacker');
    echo ':</b><br />';
    echo esc_attr__('Looks like you have this file in your server', 'antihacker') . ': ' . esc_attr($antihacker_dangerous_file);
    echo '.<br />';
    echo esc_attr__('We suggest you to remove it ASAP.', 'antihacker');
    echo '<br /><br /></div>';
}
/*
function antihacker_create_schedule2()
{
    $args = array(false);
    if (!wp_next_scheduled('antihacker_cron_hook2')) {
        $x =  wp_schedule_event(time(), 'daily', 'antihacker_cron_hook2');
    }
}
*/
function antihacker_is_tor()
{
    global $wpdb;
    global $antihacker_ip;
    //  $antihacker_ip = '103.15.29.151';
    // $antihacker_ip =  '103.150.142.125';
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table = $wpdb->prefix . "ah_tor";

    /*
    $query = "select count(*) FROM " . $table .
        " WHERE ip = '" . $antihacker_ip . "'";
    $r = $wpdb->get_var($query);
    */


    $r = $wpdb->get_var($wpdb->prepare("
    SELECT  count(*) FROM `$table` 
     WHERE ip = %s",$antihacker_ip));

    if (!empty($wpdb->last_error))
        antihacker_upd_tor_db();
    return $r;
}
function antihacker_upd_tor_db()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table = $wpdb->prefix . "ah_tor";
    antihacker_create_db_tor();
    $query = 'TRUNCATE TABLE ' . $table;
    $result = $wpdb->get_results($query);
    $arr2 = antihacker_arr_tor();
    if (!$arr2)
        return false;
    $arr = array_unique($arr2);
    for ($i = 0; $i < count($arr); $i++) {
        if (!isset($arr[$i]))
            continue;
        if (empty(trim($arr[$i])))
            continue;
        if (!filter_var($arr[$i], FILTER_VALIDATE_IP))
            continue;

        /*
        $query = "INSERT INTO " . $table .
            " (ip)
          VALUES ('" . $arr[$i] . "')";
        */

        $r = $wpdb->get_results($wpdb->prepare(
            "INSERT INTO `$table` 
            (ip) 
            VALUES (%s)", $arr[$i]));

    }
    return true;
}
function antihacker_arr_tor()
{
    $x = antihacker_get_tor();
    if (strlen($x) < 1000)
        return false;
    $arr = explode(PHP_EOL, $x);
    $q = count($arr);
    if ($q < 10)
        return false;
    $arrtor = array();
    for ($i = 0; $i < $q; $i++) {
        $pos = strpos($arr[$i], "ExitAddress");
        if ($pos !== false) {
            $work = explode(' ', $arr[$i]);
            $arrtor[] = $work[1];
        }
    }
    return $arrtor;






    
}
function antihacker_get_tor()
{
    /*
    $urlcurl = 'https://check.torproject.org/exit-addresses';
    $ch = curl_init($urlcurl);
    curl_setopt($ch, CURLOPT_POST, 0);
    //  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //A given cURL operation should only take
    //10 seconds max.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    if ($result === false) {
        return false;
    }
    curl_close($ch);
    return $result;
    */

    $response = wp_remote_get('https://check.torproject.org/exit-addresses');
    $body     = wp_remote_retrieve_body( $response );


    if ( ! $body ) {
        // Something went wrong.
        return false;
    }

   // var_dump(json_decode( $body, true ));

    return $body;


}
function antihacker_update_httptools($antihacker_http_tools)
{   // Load into table
    /*
    global $wpdb;
    if (count($antihacker_http_tools) < 1)
        return;
    $table_name = $wpdb->prefix . "ah_http_tools";
    $query = "SELECT name FROM " . $table_name;
    // testar se table tem zero...
    $results9 = $wpdb->get_results($query);
    //  $results10 = json_decode(json_encode($results9), true);
    $names = array();
    foreach ($results9 as $array) {
        $names[] = $array->name;
    }
    $total = count($antihacker_http_tools);
    for ($i = 0; $i < $total; $i++) {
        $needle = $antihacker_http_tools[$i];
        if (array_search($needle, $names, true)  === false) {
            $query = "INSERT INTO " . $table_name .
                " (name)
            VALUES ('" . $needle . "')";
            $r = $wpdb->get_results($query);
        }
    }
    */
}
function antihacker_block_httptools()
{
    global $antihacker_ua;
    global $antihacker_http_tools;

    if (antihacker_maybe_search_engine())
        return '';

    if (antihacker_isourserver())
        return '';
    if (count($antihacker_http_tools) < 1)
        return '';
    for ($i = 0; $i < count($antihacker_http_tools); $i++) {
        $toolnickname = trim(sanitize_text_field($antihacker_http_tools[$i]));
        if (stripos($antihacker_ua,  $toolnickname) !== false)
            return $toolnickname;
    }
    return '';
}
// antihacker_create_httptools();
function antihacker_create_httptools()
{
    global $antihacker_http_tools;

    $antihacker_http_tools = trim(get_site_option('antihacker_http_tools', ''));
    $antihacker_http_tools = explode(PHP_EOL, $antihacker_http_tools);


    $tools_list = array(
        '4D_HTTP_Client',
        'android-async-http',
        'axios',
        'andyhttp',
        'ansible-httpget',
        'Aplix',
        'akka-http',
        'attohttpc',
        'curl',
        'CakePHP',
        'Cowblog',
        'DAP/NetHTTP',
        'Dispatch',
        'fasthttp',
        'FireEyeHttpScan',
        'Go-http-client',
        'Go1.1packagehttp',
        'Go 1.1 package http',
        'Go http package',
        'Go-http-client',
        'Gree_HTTP_Loader',
        'GuzzleHttp',
        'hyp_http_request',
        'HTTPConnect',
        'HttpGenerator',
        'http generic',
        'Httparty',
        'HTTPing',
        'http-ping',
        'http.rb/',
        'HTTPREAD',
        'Java-http-client',
        'Jodd HTTP',
        'raynette_httprequest',
        'java/',
        'kurl',
        'Laminas_Http_Client',
        'libsoup',
        'libhttp',
        'lua-resty-http',
        'mio_httpc',
        'mozillacompatible',
        'nghttp2',
        'mio_httpc',
        'Miro-HttpClient',
        'php/',
        'phpscraper',
        'PHX HTTP',
        'PHX HTTP Client',
        'python-requests',
        'Python-urllib',
        'restful',
        'rpm-bot',
        'RxnetHttp',
        'scalaj-http',
        'SP-Http-Client',
        'Stilo OMHTTP',
        'tiehttp',
        'Valve/Steam',
        'Wget',
        'Wolfram',
        'Zend_Http_Client',
        'ZendHttpClient'
    );
    $text = '';
    for ($i = 0; $i < count($tools_list); $i++) {
        if (!in_array($tools_list[$i], $antihacker_http_tools)) {
            $text .= $tools_list[$i] . PHP_EOL;
        }
        update_option('antihacker_http_tools', $text);
    }
}
function antihacker_howmany_bots_visit()
{
    global $wpdb;
    global $antihacker_ip;
    global $antihacker_rate_limiting;
    if ($antihacker_rate_limiting < '1')
        return 0;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_visitorslog";


    return $wpdb->get_var($wpdb->prepare("
                SELECT  count(*) FROM `$table_name` 
                 WHERE ip = %s
                  AND `bot` = '1'
                  AND `date` >=  CURDATE() - interval 1 minute ORDER BY `date` DESC", $antihacker_ip));

                  
}
function antihacker_howmany_visit_200()
{
    global $wpdb;
    global $antihacker_ip;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_visitorslog";

    return $wpdb->get_var($wpdb->prepare("
                SELECT  count(*) FROM `$table_name` 
                 WHERE ip = %s
                 AND `response` LIKE '200' ", $antihacker_ip));

}
function antihacker_howmany_visit_404()
{
    global $wpdb;
    global $antihacker_ip;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_visitorslog";

    return $wpdb->get_var($wpdb->prepare("
    SELECT  count(*) FROM `$table_name` 
     WHERE ip = %s
     AND `response` LIKE '404' ", $antihacker_ip));


}


function antihacker_howmany_bots_visit2()
{
    global $wpdb;
    global $antihacker_ip;
    global $antihacker_rate_limiting_day;
    if ($antihacker_rate_limiting_day < '1')
        return 0;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_visitorslog";

    return $wpdb->get_var($wpdb->prepare("
    SELECT  count(*) FROM `$table_name` 
    WHERE ip =  %s
      AND `bot` = '1'
      AND `date` >=  CURDATE() - interval 1 hour ORDER BY `date` DESC", $antihacker_ip));

}
function antihacker_check_httptools()
{
    global $antihacker_ua;
    global $antihacker_ip;
    global $antihacker_amy_whitelist;
    global $antihacker_string_whitelist;
    global $antihacker_Blocked_else_email;
    global $antihacker_block_http_tools;
    global $antihacker_is_admin;

    if (!empty($antihacker_ua) and !$antihacker_is_admin   and !antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)) {

        if (!antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist)) {

            if (!empty(antihacker_block_httptools()) and $antihacker_block_http_tools != 'no') {
                antihacker_stats_moreone('qtools');
                if ($antihacker_Blocked_else_email == 'yes') {
                    antihacker_alertme10();
                }
                antihacker_response('HTTP Tools');
                //wp_die();
            }
        }
    }
}
function antihacker_check_useragent()
{
    global $antihacker_ua;
    global $antihacker_ip;
    global $antihacker_amy_whitelist;
    global $antihacker_Blocked_else_email;
    global $antihacker_blank_ua;
    global $antihacker_is_admin;



   if(antihacker_isourserver())
     return;

    if (!$antihacker_is_admin  and !antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)) {
        if (empty($antihacker_ua) and $antihacker_blank_ua != 'no') {
            antihacker_stats_moreone('qblank');
            if ($antihacker_Blocked_else_email == 'yes') {
                antihacker_alertme12();
            }
            antihacker_response('Blank User Agent');
            //wp_die();
        }
    }
}
/*
function antihacker_isourserver() {
	global $antihacker_ip;
	$server_ip = sanitize_text_field($_SERVER['SERVER_ADDR']);

	if (!filter_var( $server_ip, FILTER_VALIDATE_IP ))
	  return false;


	if ( $server_ip == $antihacker_ip ) {
		return true;
	}


	return false;
}
*/

function antihacker_gopro_callback9()
{
    $urlgopro = "http://antihackerplugin.com/premium/";
?>
    <script type="text/javascript">
        <!--
        window.location = "<?php echo esc_url($urlgopro); ?>";
        -->
    </script>
<?php
}
function antihacker_add_menu_items9()
{
    global $antihacker_checkversion;
    if (empty($antihacker_checkversion)) {
        $antihacker_gopro_page = add_submenu_page(
            'anti_hacker_plugin', // $parent_slug
            'Go Pro', // string $page_title
            '<font color="#FF6600">Go Pro</font>', // string $menu_title
            'manage_options', // string $capability
            'antihacker_my-custom-submenu-page9',
            'antihacker_gopro_callback9',
            9
        );
    }
}
function antihacker_custom_plugin_row_meta($links, $file)
{
    global $antihacker_checkversion;
    if (strpos($file, 'antihacker.php') !== false) {
        $new_links = array(
            'OnLine Guide' => '<a href="http://antihackerplugin.com/help/" target="_blank">OnLine Guide</a>'
        );
        if (empty($antihacker_checkversion)) {
            $new_links['Pro'] = '<a href="http://antihackerplugin.com/premium/" target="_blank"><b><font color="#FF6600">Go Pro</font></b></a>';
        }
        $links = array_merge($links, $new_links);
    }
    return $links;
}
function antihacker_ajaxurl()
{
    echo '<script type="text/javascript">
           var ajaxurl = "' . esc_url(admin_url('admin-ajax.php')) . '";
         </script>';
}
function antihacker_include_scripts()
{
    wp_enqueue_script("jquery");
    wp_enqueue_script('jquery-ui-core');
    wp_register_script('ah-scripts', ANTIHACKERURL .
        'js/antihacker_fingerprint.js', array('jquery'), null, true); //true = footer
    wp_enqueue_script('ah-scripts');
}
function antihacker_get_ajax_data()
{
    require_once('server_processing.php');
    wp_die();
}
function antihacker_grava_fingerprint()
{
    global $antihacker_ip;
    global $wpdb;
    if (isset($_REQUEST)) {
        $fingerprint = sanitize_text_field($_REQUEST['fingerprint']);
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $mytable_name = $wpdb->prefix . "ah_fingerprint";


        /*
        $query = "SELECT * from " . $mytable_name . "
        WHERE ip = '$antihacker_ip' and fingerprint != '' limit 1";
        $wpdb->get_results($query);
        */

        $wpdb->get_results($wpdb->prepare(
            "SELECT * from `$mytable_name` 
            WHERE ip = %s and fingerprint != '' limit 1", $antihacker_ip));




        if ($wpdb->num_rows > 0)
            die();

        /*
        $query = "INSERT INTO " . $mytable_name .
            " (ip, fingerprint	)
                VALUES (
             '" . $antihacker_ip . "',
             '" . $fingerprint . "')";
        $r = $wpdb->get_results($query);
        */

        $r = $wpdb->get_results($wpdb->prepare(
            "INSERT INTO `$mytable_name`
            (ip, fingerprint)
            VALUES (%s, %s)", $antihacker_ip, $fingerprint));




    }
    die();
}
function antihacker_first_time2()
{
    global $wpdb;
    global $antihacker_ip;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $table_name = $wpdb->prefix . "ah_visitorslog";

    return $wpdb->get_var($wpdb->prepare("
    SELECT  count(*) FROM `$table_name`
      WHERE ip = %s
        AND `date` >=  CURDATE()- interval 7 day ORDER BY `date` DESC", $antihacker_ip ));

}
/*
function antihacker_cron_function_clean_db_old()
{
    global $wpdb;
    //global $antihacker_rate_penalty;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_blockeds";
    $sql = "delete from " . $table_name . " WHERE `date` <  CURDATE() - interval 1 day";
    $wpdb->query($sql);
    $table_name = $wpdb->prefix . "ah_visitorslog";
    $sql = "delete from " . $table_name . " WHERE `date` <  CURDATE() - interval 30 day";
    $wpdb->query($sql);
    $table_name = $wpdb->prefix . "ah_fingerprint";
    $sql = "delete from " . $table_name . " WHERE `data` <  CURDATE() - interval 60 day";
    $wpdb->query($sql);
    $wdata = date("md", strtotime('tomorrow'));


    $table_name = $wpdb->prefix . "ah_stats";

    $wpdb->get_results($wpdb->prepare(
        "UPDATE  `$table_name` 
         SET qrate='', qnoref='', qtools='',qblank='',qlogin='', qtor='', qfire='', qenum='', qtotal='', qplugin='', qtema='', qfalseg='', qblack=''
         WHERE `date` = %s", $wdata));

}
         */
/*
function antihacker_create_schedule()
{
    $args = array(false);
    if (!wp_next_scheduled('antihacker_cron_hook'))
        $x =  wp_schedule_event(time(), 'daily', 'antihacker_cron_hook');
}
*/
function antihacker_isourserver()
{
    global $antihacker_ip;
    global $antihacker_ip, $antihacker_amy_whitelist;
    global $antihacker_ua, $antihacker_string_whitelist;

    // $server_ip = $_SERVER['REMOTE_ADDR'];



    // Check if $_SERVER['SERVER_ADDR'] exists before assigning it to $server_ip
    if (isset($_SERVER['SERVER_ADDR'])) {
        $server_ip = sanitize_text_field($_SERVER['SERVER_ADDR']);
        // Now $server_ip contains the sanitized value
        // You can proceed with the rest of your code here
    } else {
        // $_SERVER['SERVER_ADDR'] doesn't exist, handle it accordingly
        // echo "Server address is not available.";
        return false;
    }

    // $server_ip = sanitize_text_field($_SERVER['SERVER_ADDR']);
    if ($server_ip == $antihacker_ip)
        return true;


        
    
        if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
            return true;
    
        if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
            return true;

    return false;
}

function antihacker_add_blocklist($ip)
{
    global $wpdb;
    global $antihacker_ip, $antihacker_amy_whitelist;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;



    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;


    if (antihacker_maybe_search_engine())
        return;


    // if ($antihacker_is_admin or is_super_admin())
    //     return;
    if (antihacker_isourserver())
        return;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_blockeds";


    $result = $wpdb->get_var($wpdb->prepare(
        "SELECT * from `$table_name` 
        WHERE ip = %s ", $antihacker_ip));


    if ($result > 0)
        return true;

   $r = $wpdb->get_results($wpdb->prepare(
            "INSERT INTO `$table_name` 
            (ip)
            VALUES (%s)", $antihacker_ip));


}

function antihacker_add_whitelist()
{
    //return false;

    global $antihacker_amy_whitelist;
    global $antihacker_my_whitelist;

    if ( ! isset( $_POST['antihacker_nonce_table'] ) || ! wp_verify_nonce(sanitize_text_field($_POST['antihacker_nonce_table']), 'antihacker_view_blocked_visits' ) ) {
        wp_die('Nonce Fail');
    }
    
    if (!current_user_can('administrator')) 
         wp_die('Fail by Administration Permissions');


    if (!isset($_REQUEST['ip']))
        die();
    if (!filter_var($_REQUEST['ip'], FILTER_VALIDATE_IP))
        die();
    $ip = trim(filter_var($_REQUEST['ip'], FILTER_VALIDATE_IP));
    if (empty($ip))
        die();
    if (antihacker_ah_whitelisted($ip, $antihacker_amy_whitelist))
        die();
    asort($antihacker_amy_whitelist);
    $text = '';
    for ($i = 0; $i < count($antihacker_amy_whitelist); $i++) {
        if (!empty($text))
            $text .= PHP_EOL;
        $text .= $antihacker_amy_whitelist[$i];
    }
    $text .= PHP_EOL . $ip;
    if (!add_option('antihacker_my_whitelist', $text))
        update_option('antihacker_my_whitelist', $text);
    wp_die('ok');
}
function antihacker_check_blocklist($ip)
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_blockeds";
           

    /*
    $query = "select * from " . $table_name .
    " WHERE ip = '" . $ip . "'" .
    " AND `date` >= (CURDATE() - interval 15 minute) " .
    " LIMIT 1";
    */
    
    $r = $wpdb->get_var($wpdb->prepare("
    SELECT  * FROM `$table_name` 
     WHERE ip = %s
      AND `date` >=  (CURDATE() - interval 15 minute) LIMIT 1",$ip));

    if ($r > 0)
        return true;
    else
        return false;

}
function antihacker_bill_ask_for_upgrade2()
{
    global $antihacker_checkversion;
    if (!empty($antihacker_checkversion)) {
        return;
    }
    echo '<script type="text/javascript">';
    echo 'jQuery(document).ready(function() {';
    echo 'jQuery("#antihacker_block_search_themes_1").attr("disabled", true);';
    echo 'jQuery("#antihacker_block_search_plugins_1").attr("disabled", true);';
    echo 'jQuery("#antihacker_block_false_google_1").attr("disabled", true);';
    echo 'jQuery("#antihacker_block_tor_1").attr("disabled", true);';
    echo 'jQuery("#antihacker_block_http_tools_1").attr("disabled", true);';
    echo 'jQuery("#antihacker_blank_ua_1").attr("disabled", true);';
    echo '});';
    echo '</script>';
}

function antihacker_bill_ask_for_upgrade()
{
    global $antihacker_checkversion;
    if (!empty($antihacker_checkversion)) {
        return;
    }
    $time = date('Ymd');
    if ($time == '20191129') {
        $x = 4; // rand(0, 3);
        // $x = 4;
    } else {
        $x = rand(0, 3);
    }
    // $x = 3;
    if ($x == 0) {
        $banner_image = ANTIHACKERIMAGES . '/eating.png';
        $bill_banner_bkg_color = 'orange';
        $banner_txt = esc_attr__('Hackers can do all sorts of nasty stuff and destroy your site and online reputation.', 'antihacker');
    } elseif ($x == 1) {
        $banner_image = ANTIHACKERIMAGES . '/monitor-com-maca3.png';
        $bill_banner_bkg_color = 'orange';
        $banner_txt = esc_attr__('Hackers don’t play by the rules.', 'antihacker');
    } elseif ($x == 2) {
        $banner_image = ANTIHACKERIMAGES . '/unlock-icon-red-small.png';
        $bill_banner_bkg_color = 'turquoise';
        $banner_txt = esc_attr__('Hackers stresses your Web servers.', 'antihacker');
    } elseif ($x == 3) {
        $banner_image = ANTIHACKERIMAGES . '/5stars.png';
        $bill_banner_bkg_color = 'turquoise';
        $banner_txt = esc_attr__('Show support with a 5-star rating.', 'antihacker');
    } elseif ($x == 4) {
        $banner_image = ANTIHACKERIMAGES . '/special-offer.png';
        $bill_banner_bkg_color = 'turquoise';
        $banner_txt = esc_attr__('BLACK FRIDAY 30% OFF! Use the coupon code: special-black_2019. Limited time!', 'antihacker');
    } else {
        $banner_image = ANTIHACKERIMAGES . '/keys_from_left.png';
        $bill_banner_bkg_color = 'orange';
        $banner_txt = esc_attr__('Become Pro: Increase your protection.', 'antihacker');
    }
    $banner_tit = esc_attr__('Anti Hackers Plugin. Its time to Get Pro Protection!', 'antihacker');
    echo '<script type="text/javascript" src="' . esc_url(ANTIHACKERURL) .
        'js/c_o_o_k_i_e.js' . '"></script>';
?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            <?php
            if (empty($antihacker_checkversion)) {
                echo 'jQuery("#antihacker_block_search_themes_1").attr("disabled", true);';
                echo 'jQuery("#antihacker_block_search_plugins_1").attr("disabled", true);';
                echo 'jQuery("#antihacker_block_falsegoogle_1").attr("disabled", true);';
                echo 'jQuery("#antihacker_block_tor_1").attr("disabled", true);';
                echo 'jQuery("#antihacker_block_http_tools_1").attr("disabled", true);';
                echo 'jQuery("#antihacker_blank_ua_1").attr("disabled", true);';
            }
            ?>
            var hide_message = jQuery.cookie('antihacker_antihacker_bill_go_pro_hide');
            /*	 hide_message = false; */
            if (hide_message == "true") {
                jQuery(".antihacker_bill_go_pro_container").css("display", "none");
            } else {
                setTimeout(function() {
                    //  jQuery(".bill_go_pro_container").slideDown("slow");
                    jQuery(".antihacker_bill_go_pro_container").css("display", "block");
                }, 2000);
            };
            jQuery(".antihacker_bill_go_pro_close_icon").click(function() {
                jQuery(".antihacker_bill_go_pro_message").css("display", "none");
                jQuery.cookie("antihacker_antihacker_bill_go_pro_hide", "true", {
                    expires: 7
                });
                jQuery(".antihacker_bill_go_pro_container").css("display", "none");
            });
            jQuery(".antihacker_bill_go_pro_dismiss").click(function(event) {
                jQuery(".antihacker_bill_go_pro_message").css("display", "none");
                jQuery.cookie("antihacker_antihacker_bill_go_pro_hide", "true", {
                    expires: 7
                });
                event.preventDefault()
                jQuery(".antihacker_bill_go_pro_container").css("display", "none");
            });
        }); // end (jQuery);
    </script>
    <style type="text/css">
        .antihacker_bill_go_pro_close_icon {
            width: 31px;
            height: 31px;
            border: 0px solid red;
            box-shadow: none;
            float: right;
            margin: 8px;
            margin: 60px 40px 8px 8px;
        }

        .antihacker_bill_hide_settings_notice:hover,
        .antihacker_bill_hide_premium_options:hover {
            cursor: pointer;
        }

        .antihacker_bill_hide_premium_options {
            position: relative;
        }

        .antihacker_bill_go_pro_image {
            float: left;
            margin-right: 20px;
            max-height: 90px !important;
        }

        .antihacker_bill_image_go_pro {
            max-width: 200px;
            max-height: 88px;
        }

        .antihacker_bill_go_pro_text {
            font-size: 18px;
            padding: 10px;
            margin-bottom: 5px;
        }

        .antihacker_bill_go_pro_button_primary_container {
            float: left;
            margin-top: 0px;
        }

        .antihacker_bill_go_pro_dismiss_container {
            margin-top: 0px;
        }

        .antihacker_bill_go_pro_buttons {
            display: flex;
            max-height: 30px;
            margin-top: -10px;
        }

        .antihacker_bill_go_pro_container {
            border: 1px solid darkgray;
            height: 88px;
            padding: 0;
            margin: 10px 0px 15px 0px;
            background: <?php echo esc_attr($bill_banner_bkg_color);
                        ?>
        }

        .antihacker_bill_go_pro_dismiss {
            margin-left: 15px !important;
        }

        .button {
            vertical-align: top;
        }

        @media screen and (max-width:900px) {
            .antihacker_bill_go_pro_text {
                font-size: 16px;
                padding: 5px;
                margin-bottom: 10px;
            }
        }

        @media screen and (max-width:800px) {
            .antihacker_bill_go_pro_container {
                display: none !important;
            }
        }
    </style>
    <div class="notice notice-success antihacker_bill_go_pro_container" style="display: none;">
        <div class="antihacker_bill_go_pro_message antihacker_bill_banner_on_plugin_page antihacker_bill_go_pro_banner">
            <div class="antihacker_bill_go_pro_image">
                <img class="antihacker_bill_image_go_pro" title="" src="<?php echo esc_attr($banner_image); ?>" alt="" />
            </div>
            <div class="antihacker_bill_go_pro_text">
                <!-- <strong>
								Weekly Updates!
							</strong> -->
                <span>
                    <strong>
                        <?php echo esc_attr($banner_txt); ?>
                    </strong>
                </span>
                <br />
                <?php
                if ($x != '3')
                    echo esc_attr($banner_tit);
                else
                    echo esc_attr__('Help keep Anti Hacker Plugin going strong!', 'antihacker');
                ?>
            </div>
            <div class="antihacker_bill_go_pro_buttons">
                <div class="antihacker_bill_go_pro_button_primary_container">
                    <?php if ($x != '3') {
                        echo '<a class="button button-primary" target="_blank" href="http://antihackerplugin.com/premium/">';
                        echo  esc_attr__("Learn More", "antihacker");
                        echo '</a>';
                    } else {
                        echo '<a class="button button-primary" target="_blank" href="https://wordpress.org/support/plugin/antihacker/reviews/#new-post">';
                        echo  esc_attr__("Go to WordPress", "antihacker");
                        echo '</a>';
                    } ?>
                </div>
                <div class="antihacker_bill_go_pro_dismiss_container">
                    <a class="button button-secondary antihacker_bill_go_pro_dismiss" target="_blank" href="http://antihackerplugin.com/premium/"><?php echo esc_attr__(
                                                                                                                                                        'Dismiss',
                                                                                                                                                        'antihacker'
                                                                                                                                                    ); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php
} // end Bill ask for upgrade

function antihacker_gocom()
{
    global $antihacker_now;
    $antihacker_con = get_option('$antihacker_con', $antihacker_now);
    if ($antihacker_con > $antihacker_now) {
        return false;
    } else {
        return true;
    }
}
function antihacker_confail()
{
    global $antihacker_after;
    global $antihacker_checkversion;
    add_option('$antihacker_con', $antihacker_after);
    update_option('$antihacker_con', $antihacker_after);
}
function antihacker_update()
{
    global $antihacker_checkversion;
    if (!antihacker_gocom()) {
        return;
    }
    $last_checked = get_option('antihacker_last_checked2', '0');
    $days = 3;
    $write = time() - (4 * 24 * 3600);
    if ($last_checked == '0') {
        if (!add_option('antihacker_last_checked2', $write)) {
            update_option('antihacker_last_checked2', $write);
        }
        return;
    } elseif (($last_checked + ($days * 24 * 3600)) > time()) {
        return;
    }
    ob_start();
    $domain_name = get_site_url();
    $urlParts = parse_url($domain_name);
    $domain_name = preg_replace('/^www\./', '', $urlParts['host']);
    $myarray = array(
        'domain_name' => $domain_name,
        'antihacker_checkversion' => $antihacker_checkversion,
        'antihacker_version' => ANTIHACKERVERSION
    );
    $url = "http://antihackerplugin.com/api/httpapi.php";
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $myarray,
        'cookies' => array()
    ));
    if (is_wp_error($response)) {
        antihacker_confail();
        ob_end_clean();
        return;
    }
    $r = trim($response['body']);
    $r = json_decode($r, true);
    $q = count($r);
    if ($q == 1) {
        $botip = trim($r[0]['ip']);
        if ($botip == '-9') {
            update_option('antihacker_checkversion', '');
            update_option('antihacker_block_falsegoogle', '');
            update_option('antihacker_block_search_plugins', '');
            update_option('antihacker_block_search_themes', '');
        }
    }
    if (!add_option('antihacker_last_checked2', time())) {
        update_option('antihacker_last_checked2', time());
    }
    ob_end_clean();
}
function antihacker_check_limits()
{
    global $antihacker_radio_limit_visits;
    global $antihacker_ip;
    global $antihacker_amy_whitelist;
    global $antihacker_rate_limiting;
    global $antihacker_ua;
    //global $antihacker_my_radio_report_all_logins;
    global $antihacker_uaOri;
    global $antihacker_rate_limiting_day;
    global $antihacker_Blocked_else_email;
    global $antihacker_ua, $antihacker_string_whitelist;
    global $antihacker_is_admin;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if (antihacker_maybe_search_engine($antihacker_ua))
        return;

    if ($antihacker_radio_limit_visits == 'yes' and !$antihacker_is_admin   and !antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)) {
        if ($antihacker_rate_limiting == 'unlimited')
            $antihacker_rate_limiting = 999999;
        if (antihacker_howmany_bots_visit() > $antihacker_rate_limiting) {
            antihacker_stats_moreone('qrate');
            if ($antihacker_Blocked_else_email == 'yes') {
                antihacker_alertme14($antihacker_uaOri);
            }
            antihacker_response('Exceed Rating Limit');
        }
    }
    if ($antihacker_radio_limit_visits == 'yes' and !$antihacker_is_admin  and !antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)) {
        $quant = 999999;
        switch ($antihacker_rate_limiting_day) {
            case 1:
                $quant = 5;
                break;
            case 2:
                $quant = 10;
                break;
            case 3:
                $quant = 20;
                break;
            case 4:
                $quant = 50;
                break;
            case 5:
                $quant = 100;
                break;
        }
        if (antihacker_howmany_bots_visit2() > $quant) {
            antihacker_stats_moreone('qrate');
            if ($antihacker_Blocked_else_email == 'yes') {
                antihacker_alertme14($antihacker_uaOri);
            }
            antihacker_response('Exceed Rating Limit');
        }
    }
}
function antihacker_check_false_googlebot()
{
    global $antihacker_checkversion;
    //  or is_super_admin()
    //if ($antihacker_is_admin or empty($antihacker_checkversion))
    //    return false;
    // crawl-66-249-73-151.googlebot.com
    // msnbot-157-55-39-204.search.msn.com
    // msnbot-157-55-39-143.search.msn.com
    global $antihacker_ip;
    global $antihacker_ua;
    $mysearch = array(
        'googlebot',
        'bingbot',
        'msn.com',
    );
    $mysearch1 = array(
        'googlebot',
        'msnbot',
        'msnbot'
    );
    for ($i = 0; $i < count($mysearch); $i++) {
        if (stripos($antihacker_ua, $mysearch[$i]) !== false) {
            $host = trim(strip_tags(gethostbyaddr($antihacker_ip)));
            if ($host == trim($antihacker_ip))
                return false;
            if (stripos($host, $mysearch1[$i]) === false) {
                return true;
            }
        }
    }
    return false;
}
function antihacker_get_ua()
{
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return ""; // mozilla compatible";
    }
    $ua = trim(sanitize_text_field($_SERVER['HTTP_USER_AGENT']));
    $ua = antihacker_clear_extra($ua);
    return $ua;
}
function antihacker_clear_extra($mystring)
{
    $mystring = str_replace('$', 'S;', $mystring);
    $mystring = str_replace('{', '!', $mystring);
    $mystring = str_replace('shell', 'chell', $mystring);
    $mystring = str_replace('curl', 'kurl', $mystring);
    $mystring = str_replace('<', '&lt;', $mystring);
    $mystring = str_replace('=', '&#61;', $mystring);

    return $mystring;
}

function antihacker_maybe_search_engine()
{
    global $antihacker_ua;

    // Clean up and convert user agent to lowercase
    $ua = trim(strtolower($antihacker_ua));

    // List of known search engine and social media bots
    $mysearch = array(
        'AOL',
        'Baidu',
        'Bingbot',
        'DuckDuck',
        'Teoma',
        'Twitterbot',
        'Yahoo',
        'Yandex',
        'facebookexternalhit', // This one should be lowercase for consistency
        'googlebot',
        'Google-InspectionTool',
        'msn.com',
        'seznam',
        'slurp',
    );
    
    // Check if the user agent matches any of the known bots
    foreach ($mysearch as $bot) {
        if (stripos($ua, $bot) !== false) {
            // If a match is found, return true
            return true;
        }
    }

    // If no match is found, return false
    return false;
}



function antihacker_final_step()
{
    global $antihacker_ua;
    global $antihacker_block_search_plugins;
    global $antihacker_block_search_themes;
    global $antihacker_Blocked_else_email;
    global $antihacker_ip;
    global $antihacker_checkversion;
    global $antihacker_debug;
    global $antihacker_rate404_limiting;
    global $antihacker_radio_limit_visits;

    //debug();

    if (!$antihacker_debug) {
       // if ($antihacker_is_admin or is_super_admin() or empty($antihacker_checkversion))
       //     return;
    }
    if (is_404()) {
        $antihacker_response = '404';
    } else {
        $antihacker_response = http_response_code();
    }
    if ($antihacker_response == '404' and !antihacker_maybe_search_engine()) {
        // Excess 404
        if ($antihacker_rate404_limiting != 'unlimited' and $antihacker_radio_limit_visits == 'yes') {
            if (antihacker_howmany_visit_404($antihacker_rate404_limiting) >= $antihacker_rate404_limiting and antihacker_howmany_visit_200() < 1) {
                antihacker_stats_moreone('qrate');
                antihacker_add_blocklist($antihacker_ip);
                if ($antihacker_Blocked_else_email == 'yes') {
                    antihacker_alertme14($antihacker_ua);
                }
                antihacker_response('Exceed 404 Rating Limit');
            }
        }
        //  Plugins ...
        if ($antihacker_block_search_plugins == 'yes') {
            if (antihacker_looking_for_plugin()) {
                $plugin_name = antihacker_plugin_name();
                if (!antihacker_valid_plugin($plugin_name)) {
                    antihacker_stats_moreone('qplugin');
                    antihacker_add_blocklist($antihacker_ip);
                    if ($antihacker_Blocked_else_email == 'yes')
                        antihacker_alertme5();
                    antihacker_response('Search For Plugin Vulnerabilities');
                }
            }
        }
        //  Temas ...
        if ($antihacker_block_search_themes == 'yes') {
            if (antihacker_looking_for_tema()) {
                $tema_name = antihacker_tema_name();
                if (!antihacker_valid_tema($tema_name)) {
                    antihacker_stats_moreone('qtema');
                    antihacker_add_blocklist($antihacker_ip);
                    if ($antihacker_Blocked_else_email == 'yes')
                        antihacker_alertme6();
                    antihacker_response('Search For Theme Vulnerabilities');
                }
            }
        }
    }
    //debug();
    antihacker_gravalog('');
}
function antihacker_valid_tema($tema_procurado)
{
    $all_temas = wp_get_themes();
    $loopCtr = 0;
    foreach ($all_temas as $tema_item) {
        $tema_title = trim(strtolower($tema_item['Name']));
        $tema_procurado = trim(strtolower($tema_procurado));
        if ($tema_title == $tema_procurado) {
            return true;
        }
        $loopCtr++;
    }
    return false;
}
function antihacker_valid_plugin($plugin_procurado)
{
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();
    $plugin_procurado = trim(strtolower($plugin_procurado));
    foreach ($all_plugins as $plugin_item) {
        $plugin_title = trim(strtolower($plugin_item['TextDomain']));
        if ($plugin_title == $plugin_procurado)
            return true;
        /*
        $pos = stripos($plugin_title, $plugin_procurado);
        if ($pos !== false) {
            return true;
        }
        */
    }
    return false;
}
function antihacker_looking_for_plugin()
{
    global $antihacker_current_url;
    $plugins_url = plugins_url();
    $pos =   antihacker_rstrpos2($plugins_url, '/', 2);
    $plugins_url = substr($plugins_url, $pos) . '/';
    if (stripos($antihacker_current_url, $plugins_url) !== false) {
        return true;
    } else {
        return false;
    }
}
function antihacker_plugin_name()
{
    // nome plugin procurado.
    global $antihacker_current_url;
    $plugins_url = plugins_url();
    $wpos =   antihacker_rstrpos2($plugins_url, '/', 2);
    $plugins_url = substr($plugins_url, $wpos) . '/';
    $wsize =  strlen($plugins_url);
    $xwork = substr($antihacker_current_url, $wsize);
    $wpos = strpos($xwork, '/');
    return substr($xwork, 0, $wpos);
}
function antihacker_looking_for_tema()
{
    global $antihacker_current_url;
    $themes_url = get_template_directory();
    $pos =   antihacker_rstrpos2($themes_url, '/', 3);
    $themes_url = substr($themes_url, $pos) . '/';
    $wsize = strlen($themes_url);
    $themes_url = substr($themes_url, 0, $wsize - 1);
    $wpos = strrpos($themes_url, '/');
    $themes_url = substr($themes_url, 0, $wpos);
    if (stripos($antihacker_current_url, $themes_url) !== false) {
        return true;
    } else {
        return false;
    }
}
function antihacker_tema_name()
{
    global $antihacker_current_url;
    $themes_url = get_template_directory();
    $pos =   antihacker_rstrpos2($themes_url, '/', 3);
    $themes_url = substr($themes_url, $pos) . '/';
    $wsize = strlen($themes_url);
    $themes_url = substr($themes_url, 0, $wsize - 1);
    $wpos = strrpos($themes_url, '/');
    $wstring = substr($themes_url, 0, $wpos + 1);
    $wpos = strpos($antihacker_current_url, $wstring);
    $wlen = strlen($wstring);
    $tema_name = substr($antihacker_current_url, $wpos + $wlen);
    $wpos = strpos($tema_name, '/');
    $tema_name = substr($tema_name, 0, $wpos);
    return $tema_name;
}
//search backwards for needle in haystack, and return its position
function antihacker_rstrpos2($haystack, $needle, $num)
{
    for ($i = 1; $i <= $num; $i++) {
        # first loop return position of needle
        if ($i == 1) {
            $pos = strrpos($haystack, $needle);
        }
        # subsequent loops trim haystack to pos and return needle's new position
        if ($i != 1) {
            $haystack = substr($haystack, 0, $pos);
            $pos = strrpos($haystack, $needle);
        }
    }
    return $pos;
}
function antihacker_new_user_subscriber($user_id)
{
    $user = new WP_User($user_id);
    $user->set_role('subscriber');
}
//if ($antihacker_is_admin) {
// Report new plugin installed...
function antihacker_save_name_plugins()
{
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();
    $all_plugins_keys = array_keys($all_plugins);
    if (count($all_plugins) < 1)
        return;
    $my_plugins = '';
    $loopCtr = 0;
    foreach ($all_plugins as $plugin_item) {
        if ($my_plugins != '')
            $my_plugins .= PHP_EOL;
        $plugin_title = $plugin_item['Name'];
        $my_plugins .= $plugin_title;
        $loopCtr++;
    }
    if (!update_site_option('antihacker_name_plugins', $my_plugins))
        add_site_option('antihacker_name_plugins', $my_plugins);
}
function antihacker_alert_plugin()
{
    global $antihacker_admin_email, $antihacker_new_plugin;
    global $antihacker_amy_whitelist, $antihacker_ip;
    global $antihacker_server;

    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (antihacker_isourserver())
        return;
    $dt = date("Y-m-d H:i:s");
    $dom = $antihacker_server;
    $url = sanitize_url($_SERVER['PHP_SELF']);
    $msg = __('Alert: New Plugin was installed.', "antihacker");
    $msg .= '<br>';
    $msg .= __('New Plugin Name: ', "antihacker");
    $msg .= $antihacker_new_plugin;
    $msg .= '<br>';
    $msg .= __('Date', "antihacker");
    $msg .= ': ';
    $msg .= $dt;
    $msg .= '<br>';
    $msg .= __('Domain', "antihacker");
    $msg .= ': ';
    $msg .= $dom;
    $msg .= '<br>';
    $msg .= '<br>';
    $msg .= __('This email was sent from your website', "antihacker");
    $msg .= ': ';
    $msg .= $dom . ' ';
    $msg .= __('by Anti Hacker plugin.', "antihacker");
    $msg .= '<br>';
    $email_from = 'wordpress@' . $dom;
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: " . $email_from . "\r\n" . 'Reply-To: ' . $antihacker_admin_email .
        "\r\n" . 'X-Mailer: PHP/' . phpversion();
    $to = $antihacker_admin_email;
    $subject = __('Alert: New Plugin was installed at: ', "antihacker") . $dom;
    wp_mail($to, $subject, $msg, $headers, '');
    return 1;
}
//}     // End  Report new plugin installed...
function antihacker_contextual_help()
{
    $myhelp = '<br><big>';
    $myhelp .= __('Improve system security and help prevent unauthorized access to your account.', "antihacker");
    $myhelp .= '<br>';
    $myhelp .= __('Read the StartUp guide at Anti Hacker Settings page.', "antihacker");
    $myhelp .= '<br>';
    $myhelp .= __('Visit the', "antihacker");
    $myhelp .= ' <a href="http://antihackerplugin.com" target="_blank">';
    $myhelp .= __('plugin site', "antihacker");
    $myhelp .= ' </a>';
    $myhelp .= __('for more details.', "antihacker");
    $myhelp .= '</big>';
    $myhelptable = '<br />';
    $myhelptable .= 'Main Response Codes:';
    $myhelptable .= '<br />';
    $myhelptable .= '200 = Normal (content is empty if is a bot)';
    $myhelptable .= '<br />';
    $myhelptable .= '403 = Forbidden (page content doesn\'t show)';
    $myhelptable .= '<br />';
    $myhelptable .= '404 = Page Not Found';
    $myhelptable .= '<br />';
    $myhelptable .= '<br />';
    $myhelptable .= 'Main Methods:';
    $myhelptable .= '<br />';
    $myhelptable .= 'GET is used to request data from a specified resource.';
    $myhelptable .= '<br />';
    $myhelptable .= 'POST is used to send data to a server to create/update a resource.';
    $myhelptable .= '<br />';
    $myhelptable .= 'HEAD is almost identical to GET, but without the response body.';
    $myhelptable .= '<br />';
    $myhelptable .= '<br />';
    $myhelptable .= 'URL BLANK:';
    $myhelptable .= '<br />';
    $myhelptable .= 'It is your Homepage.';
    $myhelptable .= '<br />';
    $myhelptable .= '<br />';
    $screen = get_current_screen();
    $screen->add_help_tab(array(
        'id' => 'wptuts-overview-tab',
        'title' => __('Overview', 'plugin_domain'),
        'content' => '<p>' . $myhelp . '</p>',
    ));
    $screen->add_help_tab(array(
        'id' => 'antihacker-visitors-log',
        'title' => __('Blocked Visits Log', 'antihacker'),
        'content' => '<p>' . $myhelptable . '</p>',
    ));
    return;
}
function antihacker_q_plugins()
{
    // $nplugins = sanitize_text_field(get_site_option('antihacker_name_plugins', ''));
    $nplugins = get_site_option('antihacker_name_plugins', '');
    $nplugins = explode(PHP_EOL, $nplugins);
    return count($nplugins);
}
function antihacker_q_plugins_now()
{
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();
    $all_plugins_keys = array_keys($all_plugins);
    return count($all_plugins);
}
function antihacker_response($antihacker_why_block)
{
    global $antihacker_ip;
    global $antihacker_amy_whitelist;
    global $antihacker_debug;

    global $antihacker_ua, $antihacker_string_whitelist;

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    if ($antihacker_debug) {
    } else {
        // if ($antihacker_is_admin or is_super_admin())
        //     return;
        if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
            return;
        if (antihacker_isourserver())
            return;
    }
    antihacker_gravalog($antihacker_why_block);
    /*
    die('403');
    header('HTTP/1.1 403 Forbidden');
    header('Status: 403 Forbidden');
    header('Connection: Close');
    http_response_code(403);
    die();
 //    exit();
 */
}
function antihacker_gravalog($antihacker_why_block)
{

    global $wpdb;
    global $antihacker_ip;
    global $antihacker_response;
    global $antihacker_checkversion;
    global $antihacker_version;
    global $antihacker_is_human;
    global $antihacker_method;
    global $antihacker_request_url;
    global $antihacker_referer;
    global $antihacker_ua;
    global $antihacker_amy_whitelist;
    global $antihacker_Blocked_else_email;
    global $antihacker_ua, $antihacker_string_whitelist;

    //debug($antihacker_is_human);

    if (antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist))
        return;

    // if ($antihacker_is_admin or is_super_admin())
    //     return;
    if (antihacker_maybe_search_engine())
        return;
    if (antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist))
        return;
    if (@is_404()) {
        $antihacker_response = '404';
    } else {
        $antihacker_response = http_response_code();
    }
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . "ah_visitorslog";
    if (version_compare(trim(ANTIHACKERVERSION), trim($antihacker_version)) > 0) {
        antihacker_remove_index();
    }
    if ($antihacker_is_human == '0') {
        $bot = '1';
        //$antihacker_is_human = 'Bot';
    } elseif ($antihacker_is_human == '1') {
        $bot = '0';
        //$antihacker_is_human = 'Human';
    } else {
        $bot = '?';
        $antihacker_is_human = '?';
    }
    if (!empty(trim($antihacker_why_block)))
        $antihacker_response = 403;
    if ($antihacker_response == 403)
        $antihacker_access = 'Denied';
    else
        $antihacker_access = 'OK';
    $antihacker_ua  = str_replace("'", "\'", $antihacker_ua);
    $antihacker_request_url = str_replace("'", "\'", $antihacker_request_url);
    $antihacker_referer  = str_replace("'", "\'", $antihacker_referer);



    $query = $wpdb->prepare(
        "INSERT INTO $table_name (bot, reason, ip, response, human, method, url, referer, access, ua)
         VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        $bot,
        $antihacker_why_block,
        $antihacker_ip,
        $antihacker_response,
        $antihacker_is_human,
        $antihacker_method,
        $antihacker_request_url,
        $antihacker_referer,
        $antihacker_access,
        $antihacker_ua
    );
    
    $r = $wpdb->query($query);
    // die(var_export($r));

    /*
    $query = $wpdb->prepare(
        "INSERT INTO $table_name (bot, reason, ip, response, human, method, url, referer, access, ua)
         VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        $bot,
        $antihacker_why_block,
        $antihacker_ip,
        $antihacker_response,
        $antihacker_is_human,
        $antihacker_method,
        $antihacker_request_url,
        $antihacker_referer,
        $antihacker_access,
        $antihacker_ua
    );
    
    $r = $wpdb->query($query);
    die(var_export($r));
    */

    

    //debug($query);
    //debug($r);
    

    /*
    $r = $wpdb->get_results($wpdb->prepare("INSERT INTO `$table_name`
    (bot,reason,ip, response, human, method, url, referer, access, ua)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
    $bot,
    $antihacker_why_block,
    $antihacker_ip,
    $antihacker_response,
    $antihacker_is_human,
    $antihacker_method,
    $antihacker_request_url,
    $antihacker_referer,
    $antihacker_access,
    $antihacker_ua));

    die(var_export($r));
    */


    if ($antihacker_response == 403 and $antihacker_why_block != 'Failed Login') {
        http_response_code(403);
        // header('HTTP/1.1 403 Forbidden');
        // header('Status: 403 Forbidden');
        //header('Connection: Close');
        die('403 Forbidden');
    }
    return;
} 
// 2023 //
function antihacker_sizeFilter($bytes)
{
	$label = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);
	return (round($bytes, 2) . " " . $label[$i]);
}



?>