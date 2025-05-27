<?php /*
Plugin Name: AntiHacker 
Plugin URI: http://antihackerplugin.com
Description: Improve security, prevent unauthorized access by restrict access to login to whitelisted IP, Firewall, Scanner and more.
version: 5.57
Text Domain: antihacker
Domain Path: /language
Author: Bill Minozzi
Author URI: http://billminozzi.com
License: GPL-2.0
*/
// ob_start();
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
// Fix memory
$antihacker_maxMemory = @ini_get('memory_limit');
$antihacker_last = strtolower(substr($antihacker_maxMemory, -1));
$antihacker_maxMemory = (int) $antihacker_maxMemory;
if ($antihacker_last == 'g') {
  $antihacker_maxMemory = $antihacker_maxMemory * 1024 * 1024 * 1024;
  $antihacker_maxMemory = $antihacker_maxMemory * 1024 * 1024;
  $antihacker_maxMemory = $antihacker_maxMemory * 1024;
}

//if ($antihacker_maxMemory < 134217728 /* 128 MB */ && $antihacker_maxMemory > 0) {
//  if (strpos(ini_get('disable_functions'), 'ini_set') === false) {
////    @ini_set('memory_limit', '128M');
//  }
//}

$antihacker_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$antihacker_plugin_version = $antihacker_plugin_data['Version'];
define('ANTIHACKERVERSION', $antihacker_plugin_version);
define('ANTIHACKERPATH', plugin_dir_path(__file__));
define('ANTIHACKERURL', plugin_dir_url(__file__));



// Check if SERVER_NAME is blocked
$antihacker_server = isset($_SERVER['SERVER_NAME']) ? sanitize_text_field($_SERVER['SERVER_NAME']) : 'server_name_blocked_by_host';


define('ANTIHACKERIMAGES', plugin_dir_url(__file__) . 'images');
define('ANTIHACKERHOMEURL', admin_url());
$antihacker_current_url = sanitize_url($_SERVER['REQUEST_URI']);
$antihacker_version = trim(sanitize_text_field(get_site_option('antihacker_version', '')));
// debug
//$antihacker_version = '4.41';
define('ANTIHACKERVERSIONANT', $antihacker_version);
$antihacker_request_url = trim(sanitize_url($_SERVER['REQUEST_URI']));

//$antihacker_method = sanitize_text_field($_SERVER["REQUEST_METHOD"]);
$antihacker_method = antihacker_get_request_method();

$antihacker_is_admin = antihacker_check_wordpress_logged_in_cookie();

if (isset($_SERVER['HTTP_REFERER']))
  $antihacker_referer = sanitize_text_field($_SERVER['HTTP_REFERER']);
else
  $antihacker_referer = '';

define('ANTIHACKERPATHLANGUAGE', dirname(plugin_basename(__FILE__)) . '/language/');
//require_once ANTIHACKERPATH . 'includes/functions/bill-catch-errors.php';
$antihacker_ip = trim(antihacker_findip());

// require_once(ANTIHACKERPATH . "debug.php");
// add_action('shutdown', 'mostra_log', 999);
// Add settings link on plugin page
function antihacker_plugin_settings_link($links)
{
  // $settings_link = '<a href="options-general.php?page=anti-hacker">Settings</a>'; 
  $settings_link = '<a href="admin.php?page=anti-hacker">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'antihacker_plugin_settings_link');

/* Begin Language */
if ($antihacker_is_admin) {
  add_action('init', 'antihacker_localization_init');

  function antihacker_localization_init_fail()
  {
    if (isset($_COOKIE["antihacker_dismiss_language"])) {

      $r = update_option('antihacker_dismiss_language', '1');
      if (!$r) {
        $r = add_option('antihacker_dismiss_language', '1');
      }
    }

    if (get_option('antihacker_dismiss_language') == '1')
      return;

    echo '<div id="antihacker_an2" class="notice notice-warning is-dismissible">';
    echo '<br />';
    echo esc_attr__('Anti Hacker Plugin: Could not load the localization file (Language file)', 'antihacker');
    echo '.<br />';
    echo esc_attr__('Please, take a look in our site, FAQ page, item => How can i translate this plugin?', 'antihacker');
    echo '<br /><br /></div>';
  }
  /*
  if (isset($_GET['page'])) {
    $page = sanitize_text_field($_GET['page']);
    if ($page == 'anti-hacker') {
      $path = dirname(plugin_basename(__FILE__)) . '/language/';
      $loaded = load_plugin_textdomain('antihacker', false, $path);
      if (!$loaded and get_locale() <> 'en_US') {
        //if( function_exists('antihacker_localization_init_fail'))
        // add_action( 'admin_notices', 'antihacker_localization_init_fail' );
      }
    }
  }
  */
  //  add_action('plugins_loaded', 'antihacker_localization_init');
}


// antihacker dismissible_notice2
function antihacker_dismissible_notice2()
{
  $r = update_option('antihacker_dismiss_language', '1');
  if (!$r) {
    $r = add_option('antihacker_dismiss_language', '1');
  }
  if ($r)
    die('OK!!!!!');
  else
    die('NNNN');
}
add_action('wp_ajax_antihacker_dismissible_notice2', 'antihacker_dismissible_notice2');






/*
function antihacker_localization_init()
{

  $loaded = load_plugin_textdomain('antihacker', false, ANTIHACKERPATHLANGUAGE);

  if (!$loaded and get_locale() <> 'en_US') {
    if (function_exists('antihacker_localization_init_fail'))
      add_action('admin_notices', 'antihacker_localization_init_fail');
  }
}
*/


function antihacker_localization_init()
{
  $path = ANTIHACKERPATH . 'language/';
  $locale = apply_filters('plugin_locale', determine_locale(), 'antihacker');

  // Full path of the specific translation file (e.g., es_AR.mo)
  $specific_translation_path = $path . "antihacker-$locale.mo";
  $specific_translation_loaded = false;

  // Check if the specific translation file exists and try to load it
  if (file_exists($specific_translation_path)) {
    $specific_translation_loaded = load_textdomain('antihacker', $specific_translation_path);
  }

  // List of languages that should have a fallback to a specific locale
  $fallback_locales = [
    'de' => 'de_DE',  // German
    'fr' => 'fr_FR',  // French
    'it' => 'it_IT',  // Italian
    'es' => 'es_ES',  // Spanish
    'pt' => 'pt_BR',  // Portuguese (fallback to Brazil)
    'nl' => 'nl_NL'   // Dutch (fallback to Netherlands)
  ];

  // If the specific translation was not loaded, try to fallback to the generic version
  if (!$specific_translation_loaded) {
    $language = explode('_', $locale)[0];  // Get only the language code, ignoring the country (e.g., es from es_AR)

    if (array_key_exists($language, $fallback_locales)) {
      // Full path of the generic fallback translation file (e.g., es_ES.mo)
      $fallback_translation_path = $path . "antihacker-{$fallback_locales[$language]}.mo";

      // Check if the fallback generic file exists and try to load it
      if (file_exists($fallback_translation_path)) {
        load_textdomain('antihacker', $fallback_translation_path);
      }
    }
  }

  // Load the plugin
  load_plugin_textdomain('antihacker', false, plugin_basename(ANTIHACKERPATH) . '/language/');
}





/* End language */


// require_once(ANTIHACKERPATH . "settings/load-plugin.php");


//add_action('wp_enqueue_scripts', 'antihacker_include_scripts');
//add_action('admin_enqueue_scripts', 'antihacker_include_scripts');
//add_action('wp_ajax_antihacker_get_ajax_data', 'antihacker_get_ajax_data');
//add_action('wp_ajax_antihacker_grava_fingerprint', 'antihacker_grava_fingerprint');
//add_action('wp_ajax_nopriv_antihacker_grava_fingerprint', 'antihacker_grava_fingerprint');


if ($antihacker_is_admin) {

  require_once(ANTIHACKERPATH . "includes/functions/plugin-check-list.php");

  add_action('wp_ajax_antihacker_check_plugins_and_display_results', 'antihacker_check_plugins_and_display_results');


  function antihacker_add_admstylesheet()
  {



    global $antihacker_request_url;


    wp_enqueue_style('admin_enqueue_scripts', ANTIHACKERURL . 'settings/styles/admin-settings.css');



    $pos = strpos($antihacker_request_url, 'page=anti_hacker_plugin');
    $pos2 = strpos($antihacker_request_url, 'wp-admin/index.php');
    $pos3 = substr($antihacker_request_url, -10) == '/wp-admin/';

    if ($pos !== false or $pos2 !== false or $pos3) {
      wp_enqueue_script('ah-flot', ANTIHACKERURL .
        'js/jquery.flot.min.js', array('jquery'));
      wp_enqueue_script('flotpie', ANTIHACKERURL .
        'js/jquery.flot.pie.js', array('jquery'));
    }
    wp_enqueue_script('circle', ANTIHACKERURL .
      'js/radialIndicator.js', array('jquery'));
    wp_enqueue_style('bill-datatables-jquery', ANTIHACKERURL . 'assets/css/jquery.dataTables.min.css');
    wp_enqueue_script('botstrap', ANTIHACKERURL .
      'js/bootstrap.bundle.min.js', array('jquery'));
    wp_enqueue_script('easing', ANTIHACKERURL .
      'js/jquery.easing.min.js', array('jquery'));
    wp_enqueue_script('datatables1', ANTIHACKERURL .
      'js/jquery.dataTables.min.js', array('jquery'));
    wp_localize_script('datatables1', 'datatablesajax', array('url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('botstrap4', ANTIHACKERURL .
      'js/dataTables.bootstrap4.min.js', array('jquery'));
    wp_enqueue_script('datatables2', ANTIHACKERURL .
      'js/dataTables.buttons.min.js', array('jquery'));


    $pos = strpos($antihacker_request_url, 'page=antihacker_my-custom-submenu-page');
    if ($pos !== false) {
      wp_register_script('datatables_visitors', ANTIHACKERURL .
        'js/antihacker_table.js', array(), '1.0', true);
    }


    wp_enqueue_script('datatables_visitors');
    $pos = strpos($antihacker_request_url, 'page=antihacker_scan');
    if ($pos !== false) {
      wp_register_script("anti-hacker-scan", ANTIHACKERURL . 'scan/scan.js', array('jquery'), ANTIHACKERVERSION, true);
      wp_enqueue_script('anti-hacker-scan');
    }

    wp_enqueue_script(
      'antihacker_dismiss',
      plugin_dir_url(__FILE__) . 'js/antihacker_dismiss.js'
    );


    //12-23
    wp_enqueue_script('ah-ncplugin-check-script', ANTIHACKERURL . 'dashboard/js/antihacker_plugin_check.js', array('jquery'), ANTIHACKERVERSION, true);

    // Adicione a variável ajaxurl ao script
    //wp_localize_script('plugin-check-script', 'pluginCheckAjax', array('ajaxurl' => admin_url('admin-ajax.php')));


    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-dialog'); // Exemplo: carregar o diálogo do jQuery UI

    // ui 
    $wpmemory_jqueryurl = ANTIHACKERURL . 'assets/css/jquery-ui.css';
    wp_register_style('bill-jquery-ui', $wpmemory_jqueryurl, array(), '1.12.1', 'all');
    wp_enqueue_style('bill-jquery-ui');
  }
  add_action('admin_enqueue_scripts', 'antihacker_add_admstylesheet', 1000);
}






$antihacker_my_whitelist = trim(sanitize_text_field(get_site_option('antihacker_my_whitelist', '')));
$antihacker_amy_whitelist = explode(" ", $antihacker_my_whitelist);
$antihacker_string_whitelist = trim(sanitize_text_field(get_site_option('antihacker_string_whitelist', '')));
$antihacker_string_whitelist = explode(" ", $antihacker_string_whitelist);
$antihacker_admin_email = trim(sanitize_text_field(get_option('antihacker_my_email_to')));
$antihacker_my_radio_report_all_visits =  sanitize_text_field(get_site_option('antihacker_my_radio_report_all_visits', 'No')); // Alert me All Logins


$antihacker_hide_wp = sanitize_text_field(get_option('antihacker_hide_wp', 'yes'));
$antihacker_block_enumeration = sanitize_text_field(get_option('antihacker_block_enumeration', 'no'));
//$antihacker_version = trim(sanitize_text_field(get_site_option('antihacker_version', '')));
// Notifications...
// $antihacker_checkbox_all_failed =  sanitize_text_field(get_site_option('antihacker_checkbox_all_failed', '0')); // Alert me all Failed Login Attempts
$antihacker_checkbox_all_failed  = trim(sanitize_text_field(get_site_option('antihacker_checkbox_all_failed', '0')));


$antihacker_Blocked_else_email = trim(sanitize_text_field(get_site_option('antihacker_Blocked_else_email', 'no')));
$antihacker_Blocked_else_email = strtolower($antihacker_Blocked_else_email);
$antihacker_my_radio_report_all_logins =  sanitize_text_field(get_site_option('antihacker_my_radio_report_all_logins', 'No')); // Alert me All Logins
//
$antihacker_block_all_feeds = trim(sanitize_text_field(get_site_option('antihacker_block_all_feeds', 'no')));
$antihacker_new_user_subscriber = trim(sanitize_text_field(get_site_option('antihacker_new_user_subscriber', 'no')));
$antihacker_checkversion = trim(sanitize_text_field(get_option('antihacker_checkversion', '')));
$antihacker_rate404_limiting = trim(sanitize_text_field(get_option('antihacker_rate404_limiting', 'unlimited')));
$antihacker_application_password = trim(sanitize_text_field(get_option('antihacker_application_password', 'yes')));
$antihacker_update_http_tools = trim(sanitize_text_field(get_option('antihacker_update_http_tools', 'no')));
$antihacker_block_tor = trim(sanitize_text_field(get_site_option('antihacker_block_tor', 'no')));
$antihacker_block_falsegoogle = trim(sanitize_text_field(get_site_option('antihacker_block_falsegoogle', 'no')));
$antihacker_show_widget = trim(sanitize_text_field(get_site_option('antihacker_show_widget', 'no')));
$antihacker_last_plugin_scan = trim(sanitize_text_field(get_site_option('antihacker_last_plugin_scan', '0')));
$antihacker_notif_scan = sanitize_text_field(get_option('antihacker_notif_scan', '0'));
$antihacker_notif_level = sanitize_text_field(get_option('antihacker_notif_level', '0'));
$antihacker_notif_visit = sanitize_text_field(get_option('antihacker_notif_visit', '0'));
$antihacker_last_theme_scan = sanitize_text_field(get_option('antihacker_notif_visit', '0'));
$antihacker_last_theme_update = sanitize_text_field(get_option('antihacker_last_theme_update', '0'));
$antihacker_disable_sitemap = sanitize_text_field(get_option('antihacker_disable_sitemap', 'no'));



$antihacker_plugin_abandoned_email = sanitize_text_field(get_option('antihacker_plugin_abandoned_email', 'yes'));
$antihacker_auto_updates = sanitize_text_field(get_option('antihacker_auto_updates', ''));



if (!empty($antihacker_checkversion)) {
  // $antihacker_block_tor = trim(sanitize_text_field(get_site_option('antihacker_block_tor', 'no')));
  // $antihacker_block_falsegoogle = trim(sanitize_text_field(get_site_option('antihacker_block_falsegoogle', 'no')));
  $antihacker_block_search_plugins = trim(sanitize_text_field(get_site_option('antihacker_block_search_plugins', 'no')));
  $antihacker_block_search_themes = trim(sanitize_text_field(get_site_option('antihacker_block_search_themes', 'no')));
  $antihacker_block_http_tools = sanitize_text_field(get_site_option('antihacker_block_http_tools', 'no'));
  $antihacker_blank_ua = sanitize_text_field(get_site_option('antihacker_blank_ua', 'no'));
  $antihacker_radio_limit_visits =  sanitize_text_field(get_site_option('antihacker_radio_limit_visits', 'no'));
} else {
  $antihacker_block_tor = 'no';
  $antihacker_block_falsegoogle = 'no';
  $antihacker_block_search_plugins = 'no';
  $antihacker_block_search_themes = 'no';
  $antihacker_block_http_tools = 'no';
  $antihacker_blank_ua = 'no';
  $antihacker_radio_limit_visits = 'no';
}
require_once(ANTIHACKERPATH . "includes/functions/functions.php");

$antihacker_firewall = sanitize_text_field(get_option('antihacker_firewall', 'yes'));
$antihacker_Blocked_Firewall = sanitize_text_field(get_option('antihacker_Blocked_Firewall', 'no'));

if (antihacker_isourserver()) {
  $antihacker_firewall = 'no';
  $antihacker_Blocked_Firewall = 'no';
}

if (!empty($_POST["myemail"])) {
  $antihacker_myemail = sanitize_text_field($_POST["myemail"]);
} else
  $antihacker_myemail = '';
require_once(ANTIHACKERPATH . 'dashboard/main.php');
require_once(ANTIHACKERPATH . 'scan/dashboard_scan.php');

if ($antihacker_is_admin) {

  require_once(ANTIHACKERPATH . 'includes/functions/health.php');
  require_once(ANTIHACKERPATH . 'includes/functions/function_sysinfo.php');

  add_action('setup_theme', 'antihacker_load_settings');

  function antihacker_load_settings()
  {
    require_once(ANTIHACKERPATH . "settings/load-plugin.php");
    require_once(ANTIHACKERPATH . "settings/options/plugin_options_tabbed.php");
  }
}

$antihacker_admin_email = trim(sanitize_text_field(get_option('antihacker_my_email_to')));
if (!empty($antihacker_admin_email)) {
  if (!is_email($antihacker_admin_email)) {
    $antihacker_admin_email = '';
    update_option('antihacker_my_email_to', '');
  }
}
if (empty($antihacker_admin_email))
  $antihacker_admin_email = sanitize_email(get_option('admin_email'));
// Firewall
if (!$antihacker_is_admin) {
  if ($antihacker_firewall != 'no') {
    $antihacker_request_uri_array  = array('@eval', 'eval\(', 'UNION(.*)SELECT', '\(null\)', 'base64_', '\/localhost', '\%2Flocalhost', '\/pingserver', 'wp-config\.php', '\/config\.', '\/wwwroot', '\/makefile', 'crossdomain\.', 'proc\/self\/environ', 'usr\/bin\/perl', 'var\/lib\/php', 'etc\/passwd', '\/https\:', '\/http\:', '\/ftp\:', '\/file\:', '\/php\:', '\/cgi\/', '\.cgi', '\.cmd', '\.bat', '\.exe', '\.sql', '\.ini', '\.dll', '\.htacc', '\.htpas', '\.pass', '\.asp', '\.jsp', '\.bash', '\/\.git', '\/\.svn', ' ', '\<', '\>', '\/\=', '\.\.\.', '\+\+\+', '@@', '\/&&', '\/Nt\.', '\;Nt\.', '\=Nt\.', '\,Nt\.', '\.exec\(', '\)\.html\(', '\{x\.html\(', '\(function\(', '\.php\([0-9]+\)', '(benchmark|sleep)(\s|%20)*\(', 'indoxploi', 'xrumer');
    $antihacker_query_string_array = array('@@', '\(0x', '0x3c62723e', '\;\!--\=', '\(\)\}', '\:\;\}\;', '\.\.\/', '127\.0\.0\.1', 'UNION(.*)SELECT', '@eval', 'eval\(', 'base64_', 'localhost', 'loopback', '\%0A', '\%0D', '\%00', '\%2e\%2e', 'allow_url_include', 'auto_prepend_file', 'disable_functions', 'input_file', 'execute', 'file_get_contents', 'mosconfig', 'open_basedir', '(benchmark|sleep)(\s|%20)*\(', 'phpinfo\(', 'shell_exec\(', '\/wwwroot', '\/makefile', 'path\=\.', 'mod\=\.', 'wp-config\.php', '\/config\.', '\$_session', '\$_request', '\$_env', '\$_server', '\$_post', '\$_get', 'indoxploi', 'xrumer');
    $antihacker_user_agent_array   = array('drivermysqli', 'acapbot', '\/bin\/bash', 'binlar', 'casper', 'cmswor', 'diavol', 'dotbot', 'finder', 'flicky', 'md5sum', 'morfeus', 'nutch', 'planet', 'purebot', 'pycurl', 'semalt', 'shellshock', 'skygrid', 'snoopy', 'sucker', 'turnit', 'vikspi', 'zmeu');
    $antihacker_request_uri_string  = false;
    $antihacker_query_string_string = false;

    $antihacker_request_uri_string  = '';
    $antihacker_query_string_string = '';
    $antihacker_user_agent_string   = '';
    // $referrer_string     = '';

    if (isset($_SERVER['REQUEST_URI'])     && !empty($_SERVER['REQUEST_URI']))     $antihacker_request_uri_string  = sanitize_text_field($_SERVER['REQUEST_URI']);
    if (isset($_SERVER['QUERY_STRING'])    && !empty($_SERVER['QUERY_STRING']))    $antihacker_query_string_string = sanitize_text_field($_SERVER['QUERY_STRING']);
    if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) $antihacker_user_agent_string   = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
    if ($antihacker_request_uri_string || $antihacker_query_string_string || $antihacker_user_agent_string) {
      if (
        preg_match('/' . implode('|', $antihacker_request_uri_array)  . '/i', $antihacker_request_uri_string, $matches)  ||
        preg_match('/' . implode('|', $antihacker_query_string_array) . '/i', $antihacker_query_string_string, $matches2) ||
        preg_match('/' . implode('|', $antihacker_user_agent_array)   . '/i', $antihacker_user_agent_string, $matches3)
      ) {
        // $antihacker_firewall
        if ($antihacker_Blocked_Firewall == 'yes') {
          if (isset($matches)) {
            if (is_array($matches)) {
              if (count($matches) > 0) {
                antihacker_alertme3($matches[0]);
              }
            }
          }
          if (isset($matches2)) {
            if (is_array($matches2)) {
              if (count($matches2) > 0)
                antihacker_alertme3($matches2[0]);
            }
          }
          if (isset($matches3)) {
            if (is_array($matches3)) {
              if (count($matches3) > 0)
                antihacker_alertme4($matches3[0]);
            }
          }
        }
        antihacker_stats_moreone('qfire');
        antihacker_response('Firewall');
      } // Endif match...     
    } // endif if ($antihacker_query_string_string || $user_agent_string) 
  } // firewall <> no
}
// End Firewall


if (!antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)) {

  if (sanitize_text_field(get_site_option('antihacker_replace_login_error_msg', 'no')) == 'yes')
    add_filter('login_errors', function ($error) {
      return '<strong>' . __('Wrong Username, Password or eMail', 'antihacker') . '</strong>';
    });


  add_action('login_form', 'antihacker_ah_email_display');
  add_action('wp_authenticate_user', 'antihacker_validate_email_field', 10, 2);

  function antihacker_validate_email_field($user, $password)
  {
    global $antihacker_myemail;
    global $antihacker_method;
    global $antihacker_referer;
    global $antihacker_request_url;
    global $antihacker_Blocked_else_email;


    if (!antihacker_isourserver()) {
      if (!empty($antihacker_request_url)) {
        $pos = strpos($antihacker_request_url, 'xmlrpc.php');
        if ($pos !== false) {
          if ($antihacker_Blocked_else_email == 'yes')
            antihacker_alertme13();
          antihacker_stats_moreone('qlogin');
          antihacker_response('Brute Force Login using xmlrpc');
          return;
        }
      }
    }


    // var_dump($antihacker_referer);
    if ($antihacker_method == 'POST' and trim($antihacker_referer) == '') {
      antihacker_stats_moreone('qnoref');
      antihacker_alertme13();
      antihacker_response('Login Post Without Referrer');
    }

    if (!is_email($antihacker_myemail)) {
      // Blank email
      add_filter('login_errors', function ($error) {
        return '<strong>' . __('Empty email', 'antihacker') . '</strong>';
      });
      antihacker_stats_moreone('qlogin');
      antihacker_alertme13();
      antihacker_gravalog('Failed Login');
      return new WP_Error('wrong_email', 'Please, fill out the email field!');
    } // empty

    // The Query
    $user_query = new WP_User_Query(array('orderby' => 'registered', 'order' => 'ASC'));


    // User Loop

    // var_dump($user_query);


    if (!empty($user_query->results)) {
      foreach ($user_query->results as $user) {
        if (strtolower(trim($user->user_email)) == $antihacker_myemail)
          return $user;
      }
      // echo 'No users found.';
    }

    return new WP_Error('wrong_email', 'email not found!');
  }
} /* endif if (! antihacker_ah_whitelisted($antihacker_ip, $antihacker_my_whitelist)) */ else {

  if (sanitize_text_field(get_site_option('antihacker_replace_login_error_msg', 'no')) == 'yes')
    add_filter('login_errors', function ($error) {
      return '<strong>' . __('Wrong Username or Password', 'antihacker') . '</strong>';
    });
}

add_action('wp_login', 'antihacker_successful_login');
add_action('wp_login_failed', 'antihacker_failed_login');
register_deactivation_hook(__FILE__, 'antihacker_my_deactivation');
register_activation_hook(__FILE__, 'antihacker_activated');





if (sanitize_text_field(get_site_option('antihacker_disallow_file_edit', 'yes')) == 'yes') {
  if (!defined('DISALLOW_FILE_EDIT'))
    define('DISALLOW_FILE_EDIT', true);
}
if (WP_DEBUG and get_site_option('antihacker_debug_is_true', 'yes') == 'yes')
  add_action('admin_notices', 'antihacker_debug_enabled');


function antihackerplugin_load_activate()
{
  global $antihacker_is_admin;
  if ($antihacker_is_admin) {
    // require_once(ANTIHACKERPATH . 'includes/feedback/activated-manager.php');
  }
}
add_action('in_admin_footer', 'antihackerplugin_load_activate');


if ($antihacker_is_admin) {
  if (get_option('antihacker_was_activated', '0') == '1') {
    add_action('admin_enqueue_scripts', 'antihacker_adm_enqueue_scripts2');
  }
}




if ($antihacker_disable_sitemap == 'yes') {
  add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
    return ($name == 'users') ? false : $provider;
  }, 10, 2);
}


function antihacker_custom_dashboard_help()
{
  global $antihacker_checkversion;
  $perc = antihacker_find_perc() * 10;
  if ($perc < 71)
    $color = '#ff0000';
  $color = '#000000';
  echo '<img src="' . esc_attr(ANTIHACKERURL) . '/images/logo.png" style="text-align:center; max-width: 300px;margin: 0px 0 auto;"  />';
  echo '<br />';
  if ($perc < 71) {
    echo '<img src="' . esc_attr(ANTIHACKERURL) . '/images/unlock-icon-red-small.png" style="text-align:center; max-width: 20px;margin: 0px 0 auto;"  />';
    echo '<h2 style="margin-top: -39px; margin-left: 25px; color:' . esc_attr($color) . ';">';
  }
  echo 'Protection rate: ' . esc_attr($perc) . '%';
  echo '</h2>';
  $site = esc_url(ANTIHACKERHOMEURL) . "admin.php?page=anti_hacker_plugin";
  echo  '&nbsp;<a href="' . esc_url($site) . '">For details, visit the plugin dashboard</a>';
  echo '<br />';
  echo '<br />';
  echo '<h3 style="font-size:18px; text-align:center;">Total Attacks Blocked Last 15 days</h3>';
  echo '<br />';
  echo '<div style="max-width: 100%;">';
  require_once("dashboard/attacksgraph.php");
  echo '</div>';
  echo '<br />';
  echo '<hr>';
  echo '<br />';
  echo '<h3 style="font-size:18px; text-align:center;">Blocked Attacks by Type</h3>';
  echo '<br />';
  echo '<br />';
  require_once("dashboard/attacksgraph_pie.php");
  echo '<br />';
  // echo '<hr>';
  echo '<br />';


  echo '<a href="' . esc_url($site) . '" class="button button-primary">Dashboard</a>';
  echo '<br /><br />';

  echo "</p>";
}
function antihacker_add_dashboard_widgets()
{
  // wp_add_dashboard_widget('antihacker-dashboard', 'Anti Hacker  Activities', 'antihacker_custom_dashboard_help', 'dashboardsbb', 'normal', 'high');
  wp_add_dashboard_widget('antihacker_dashboard_widgets', 'Plugin Anti Hacker Activities', 'antihacker_custom_dashboard_help');
}
function anti_hacker_show_dashboard()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_stats";

  //$query = "SELECT date,qtotal FROM " . $table_name;
  //$results9 = $wpdb->get_results($query);

  //$results9 = $wpdb->get_results($wpdb->prepare("
  //SELECT date,qtotal FROM  `$table_name`"));
  $results9 = $wpdb->get_results($wpdb->prepare("SELECT date, qtotal FROM %i", $table_name));


  $results8 = json_decode(json_encode($results9), true);
  unset($results9);
  $x = 0;
  $d = 15;
  for ($i = $d; $i > 0; $i--) {
    $timestamp = time();
    $tm = 86400 * ($x); // 60 * 60 * 24 = 86400 = 1 day in seconds
    $tm = $timestamp - $tm;
    $the_day = date("d", $tm);
    $this_month = date('m', $tm);
    $array30d[$x] = $this_month . $the_day;
    $mykey = array_search(trim($array30d[$x]), array_column($results8, 'date'));
    if ($mykey) {
      $awork = $results8[$mykey]['qtotal'];
      $array30[$x] = $awork;
      $array30[$x] = 0;
      $x++;
    }
  }
  if (count($array30) > 1) {
    for ($i = 0; $i < count($array30); $i++) {
      if ($array30[$i] > 0) {
        return true;
      }
    }
    return false;
  }
}
if ($antihacker_is_admin and $antihacker_show_widget != 'no')
  add_action("wp_dashboard_setup", "antihacker_add_dashboard_widgets");
if ($antihacker_hide_wp == 'yes')
  remove_action('wp_head', 'wp_generator');

if (!antihacker_isourserver()) {
  if (!$antihacker_is_admin and $antihacker_block_enumeration == 'yes') {
    // antihacker_block_enumeration();
    add_action('init', 'antihacker_block_enumeration');
    add_filter('rest_endpoints', 'antihacker_filter_rest_endpoints', 10, 1);
  }
}

// Dangerous files...
$anti_hacker_dangerous_files = array(
  'wp-config.php.bak',
  'wp-config.php.bak.a2',
  'wp-config.php.swo',
  'wp-config.php.save',
  'wp-config.php~',
  'wp-config.old',
  '.wp-config.php.swp',
  'wp-config.bak',
  'wp-config.save',
  'wp-config.php_bak',
  'wp-config.php.swp',
  'wp-config.php.old',
  'wp-config.php.original',
  'wp-config.php.orig',
  'wp-config.txt',
  'wp-config.original',
  'wp-config.orig'
);
// $anti_hacker_dangerous_files = array('wp-config.bak', 'wp-config.old', 'wp-config.txt');
if ($antihacker_is_admin) {
  for ($i = 0; $i < count($anti_hacker_dangerous_files); $i++) {
    $antihacker_dangerous_file =  ABSPATH . $anti_hacker_dangerous_files[$i];
    if (file_exists($antihacker_dangerous_file))
      add_action('admin_notices', 'anti_hacker_dangerous_file');
    break;
  }
}
// End Dangerous ...
if ($antihacker_block_all_feeds == 'yes') {




  function antihacker_disable_feed()
  {
    wp_die(esc_attr('No feed available,please visit our <a href="' . get_bloginfo('url') . '">homepage</a>!'));
  }

  if (!antihacker_isourserver()) {

    add_action('do_feed', 'antihacker_disable_feed', 1);
    add_action('do_feed_rdf', 'antihacker_disable_feed', 1);
    add_action('do_feed_rss', 'antihacker_disable_feed', 1);
    add_action('do_feed_rss2', 'antihacker_disable_feed', 1);
    add_action('do_feed_atom', 'antihacker_disable_feed', 1);
    add_action('do_feed_rss2_comments', 'antihacker_disable_feed', 1);
    add_action('do_feed_atom_comments', 'antihacker_disable_feed', 1);
  }
}
$antihacker_block_media_comments = trim(sanitize_text_field(get_site_option('antihacker_block_media_comments', 'yes')));




if ($antihacker_block_media_comments == 'yes') {
  function anti_hacker_filter_media_comment($open, $post_id)
  {
    $post = get_post($post_id);
    //if ($post->post_type == 'attachment') {
    if ($post && $post->post_type == 'attachment') {
      return false;
    }
    return $open;
  }
  add_filter('comments_open', 'anti_hacker_filter_media_comment', 10, 2);
  add_action('template_redirect', 'antihacker_final_step');
}







if (!empty($antihacker_checkversion))
  add_action('plugins_loaded', 'antihacker_update');
if (antihacker_check_blocklist($antihacker_ip)) {
  if ($antihacker_Blocked_else_email == 'yes') {
    antihacker_alertme8();
  }
  antihacker_stats_moreone('qblack');
  antihacker_response('Black Listed');
}
if ($antihacker_block_tor == 'yes') {
  if (antihacker_is_tor()) {
    if ($antihacker_Blocked_else_email == 'yes') {
      antihacker_alertme9();
    }
    antihacker_stats_moreone('qtor');
    antihacker_response('Tor');
  }
}
require_once ANTIHACKERPATH . 'table/visitors.php';
function antihacker_ah_whitelisted($antihacker_ip, $antihacker_amy_whitelist)
{

  if (gettype($antihacker_amy_whitelist) != 'array')
    return;

  for ($i = 0; $i < count($antihacker_amy_whitelist); $i++) {
    if (trim($antihacker_amy_whitelist[$i]) == $antihacker_ip)
      return 1;
  }
  return 0;
}
function antihacker_string_whitelisted($antihacker_ua, $antihacker_string_whitelist)
{

  if (gettype($antihacker_string_whitelist) != 'array')
    return;

  for ($i = 0; $i < count($antihacker_string_whitelist); $i++) {
    if (empty(trim($antihacker_string_whitelist[$i])))
      continue;
    if (strpos($antihacker_ua, $antihacker_string_whitelist[$i]) !== false)
      return 1;
  }
  return 0;
}
// To completely disable Application Passwords 
if ($antihacker_application_password == 'yes')
  add_filter('wp_is_application_passwords_available', '__return_false');
if ($antihacker_is_admin)
  add_action('admin_menu', 'antihacker_menu');
function antihacker_menu()
{
  add_submenu_page(
    'anti_hacker_plugin', // $parent_slug
    'Scan For Malware', // string $page_title
    'Scan For Malware', // string $menu_title
    'manage_options', // string $capability
    'antihacker_scan', // menu slug
    'antihacker_scan_dashboard', // callable function
    1 // position
  );
}
if (file_exists(ANTIHACKERPATH . 'scan/functions_scan.php'))
  require_once(ANTIHACKERPATH . "scan/functions_scan.php");
else
  add_action('admin_notices', 'antihacker_missing_file');


//////////////////////////////////////


function antihacker_custom_toolbar_link($wp_admin_bar)
{
  global $wp_admin_bar;
  $site = ANTIHACKERHOMEURL . "admin.php?page=anti_hacker_plugin&tab=notifications";
  $args = array(
    'id' => 'antihacker',
    'title' => '<div class="antihacker-logo"></div><span class="text"> Anti Hacker </span>',
    'href' => $site,
    'meta' => array(
      'class' => 'antihacker',
      'title' => ''
    )
  );
  $wp_admin_bar->add_node($args);
  echo '<style>';
  echo '#wpadminbar .antihacker  {
      background: red !important;
      color: black !important;
    }';
  $logourl = ANTIHACKERIMAGES . "/sologo-gray.png";
  echo '#wpadminbar .antihacker-logo  {
      background-image: url("' . esc_url($logourl) . '");
      float: left;
      width: 26px;
      height: 30px;
      background-repeat: no-repeat;
      background-position: 0 6px;
      background-size: 20px;
    }';
  echo '</style>';
}
// $antihacker_timeout_level = time() > ($antihacker_notif_level + 60 * 60 * 24 * 7);

$antihacker_timeout_visit = time() > ($antihacker_notif_visit + 60 * 60 * 24 * 5);


$table_name = $wpdb->prefix . "ah_scan";

// $query = "select `date_end`  from $table_name ORDER BY id DESC limit 1";


// $r = $wpdb->get_var("SELECT `date_end` FROM `$table_name` ORDER BY id DESC limit 1");
$r = $wpdb->get_var($wpdb->prepare("SELECT `date_end` FROM %i ORDER BY id DESC LIMIT 1", $table_name));


if ($r !== null and !empty(trim($r))) {
  // $last_scan =  strtotime(trim($wpdb->get_var($query)));
  $last_scan =  strtotime(trim($r));
} else
  $last_scan = 0;


$antihacker_timeout_scan = time() > ($antihacker_notif_scan + 60 * 60 * 24 * 7);
if ($antihacker_timeout_scan) {
  $antihacker_timeout_scan = time() > ($last_scan + 60 * 60 * 24 * 7);
}


$antihacker_timeout_level = time() > ($antihacker_notif_level + 60 * 60 * 24 * 7);
//$antihacker_timeout_level = time() > ($antihacker_notif_level + 10 );

if ($antihacker_timeout_level) {

  if (antihacker_find_perc() < 8)
    $antihacker_timeout_level = true;
  else
    $antihacker_timeout_level = false;
}

if ($antihacker_timeout_scan or $antihacker_timeout_level or $antihacker_timeout_visit) {
  if (!is_multisite() and $antihacker_is_admin)
    add_action('admin_bar_menu', 'antihacker_custom_toolbar_link', 999);
}
// require_once ANTIHACKERPATH . "includes/functions/functions_api.php";
function antihacker_add_cors_http_header()
{
  header("Access-Control-Allow-Origin: https://antihackerplugin.com");
}
function antihacker_missing_file()
{
  echo '<div class="notice notice-warning is-dismissible">';
  echo '<p>Warning - Missing file: functions_scan.php';
  echo '<br>File Path: ' . esc_attr(ANTIHACKERPATH);
  echo '<br>Probably was deleted by some other antivirus because it has some virus signature to detect them.';
  echo '<br>Please, reinstall Anti Hacker plugin.';
  echo '</p></div>';
}

/* =============================== */

function antihacker_add_more_plugins()
{
  if (is_multisite()) {
    add_submenu_page(
      'anti_hacker_plugin', // $parent_slug
      'More Tools Same Author', // string $page_title
      'More Tools Same Author', // string $menu_title
      'manage_options', // string $capability
      'antihacker_more_plugins', // menu slug
      'antihacker_more_plugins', // callable function
      8 // position
    );
  } else {

    add_submenu_page(
      'anti_hacker_plugin', // $parent_slug
      'More Tools Same Author', // string $page_title
      'More Tools Same Author', // string $menu_title
      'manage_options', // string $capability
      // 'wptools_options39', // menu slug
      // 'wptools_new_more_plugins', // callable function
      'antihacker_new_more_plugins', // menu slug
      'antihacker_new_more_plugins', // callable function
      8 // position
    );
  }
}
add_action('admin_menu', 'antihacker_add_more_plugins');
add_action('admin_menu', 'antihacker_menu');


function antihacker_more_plugins()
{

  echo '<script>';
  echo 'window.location.replace("' . esc_url(ANTIHACKERHOMEURL) . 'plugin-install.php?s=sminozzi&tab=search&type=author");';
  echo '</script>';
}

function antihacker_show_logo()
{
  echo '<div id="antihackers_logo" style="margin-top:10px;">';
  // echo '<br>';
  echo '<img src="';
  echo esc_url(ANTIHACKERIMAGES) . '/logo.png';
  echo '">';
  echo '<br>';
  echo '</div>';
}



/* ---------------------------------- */



function antihacker_plugin_row_meta($links, $file)
{
  if (strpos($file, 'antihacker.php') !== false) {


    if (is_multisite())
      $url = ANTIHACKERHOMEURL . "plugin-install.php?s=sminozzi&tab=search&type=author";
    else
      $url = ANTIHACKERHOMEURL . "admin.php?page=antihacker_new_more_plugins";


    $new_links['Pro'] = '<a href="' . $url . '" target="_blank"><b><font color="#FF6600">Click To see more plugins from same author</font></b></a>';
    $links = array_merge($links, $new_links);
  }
  return $links;
}
//add_filter('plugin_row_meta', 'antihacker_plugin_row_meta', 10, 2);

function antihacker_antihacker_bill_go_pro_hide2()
{
  // $today = date('Ymd', strtotime('+06 days'));
  $today = time();
  if (!update_option('antihacker_bill_go_pro_hide', $today))
    add_option('antihacker_bill_go_pro_hide', $today);
  wp_die();
}
add_action('wp_ajax_antihacker_antihacker_bill_go_pro_hide2', 'antihacker_antihacker_bill_go_pro_hide2');




//---------------- BEGIN $antihacker_plugin_abandoned_email



/*
function antihacker_automatic_plugin_scan() {

   global $antihacker_plugin_abandoned_email;
   global $antihacker_admin_email;
   global $antihacker_last_plugin_scan;


  if($antihacker_plugin_abandoned_email != 'no'){

      if (empty($antihacker_admin_email))
      $antihacker_admin_email = sanitize_email(get_option('admin_email',''));


    $timeout_plugin_scan = time() > ($antihacker_last_plugin_scan + (60 * 60 * 24 * 6));
 
    if($timeout_plugin_scan){
      $r = antihacker_scan_plugins();
      update_option('antihacker_last_plugin_scan', time());


          if (gettype($r) === 'array' && count($r) > 0) {
            function antihacker_filterElements($element) {
              return strpos($element, '***') !== false;
            }
            $result = array_filter($r, 'antihacker_filterElements');

            if (empty($result)) {
              return;
            }

            foreach ($result as &$element) {
                $element = str_replace('***', '', $element);
                $element = str_replace('=>', '', $element);
                $element = str_replace('>', '', $element);
                $element = str_replace('&gt;', '', $element);
            }


            $string_result = implode("\n", $result);


            if (empty($string_result)) 
            return;




            if(isset($_SERVER['SERVER_NAME'])) {
              $dom = sanitize_text_field($_SERVER['SERVER_NAME']);
              $message =  __('This email was sent from your website', "antihacker");
              $message .= ': ' . $dom . ' ';
              $message .=  __('by the AntiHacker plugin.', "antihacker");
              $message .= "\n";
            }



            $message .= esc_attr__("We conducted tests on the WordPress repository, and it appears that some plugins are not being updated. Plugins not updated in the last year are suspect to be abandoned, and we suggest replacing them.",'antihacker');
            $message .= "\n";
            $message .= "\n";
            $subject = esc_attr__("Some plugins on the site require attention.",'antihacker');
            $message .= $string_result;
            $message .= "\n";
            $message .= "\n";
            $message .= __('Visit the Anti Hacker plugin dashboard for additional tips on enhancing site security.', "antihacker");
            $message .= "\n";
            $message .= "\n";
            $message .= __('You can stop emails at the Notifications Tab.', "antihacker");
            $message .= "\n";
            $message .= __('Dashboard => Anti Hacker => Settings => Notifications Settings (tab)', "antihacker");
            $headers = array('Content-Type: text/plain; charset=UTF-8');
            $success = wp_mail($antihacker_admin_email, $subject, $message, $headers);
            //$success = true;
            if (!$success) {
              error_log('Fail to send email antihacker_plugin_abandoned_email');
            }
          }
    }
  }
}
*/

antihacker_automatic_plugin_scan();

function antihacker_automatic_plugin_scan()
{
  global $antihacker_plugin_abandoned_email, $antihacker_admin_email, $antihacker_last_plugin_scan;
  global $antihacker_server;

  if ($antihacker_plugin_abandoned_email != 'no') {


    if (empty($antihacker_admin_email)) {
      $antihacker_admin_email = sanitize_email(get_option('admin_email', ''));
    }

    $timeout_plugin_scan = time() > ($antihacker_last_plugin_scan + (60 * 60 * 24 * 6));

    if ($timeout_plugin_scan) {


      $r = antihacker_scan_plugins();
      update_option('antihacker_last_plugin_scan', time());

      if (is_array($r) && !empty($r)) {




        $result = array_filter($r, function ($element) {
          return strpos($element, '***') !== false;
        });


        if (empty($result)) {
          return;
        }

        foreach ($result as &$element) {
          $element = str_replace(['***', '=>', '>', '&gt;'], '', $element);
        }

        $string_result = implode("\n", $result);


        if (empty($string_result)) {
          return;
        }

        $dom = $antihacker_server;
        $message = __('This email was sent from your website', 'antihacker') . ': ' . $dom . ' ' . __('by the AntiHacker plugin.', 'antihacker') . "\n\n";

        $message .= __('We conducted tests on the WordPress repository, and it appears that some plugins are not being updated. Plugins not updated in the last year are suspect to be abandoned, and we suggest replacing them.', 'antihacker') . "\n\n";
        $subject = __('Some plugins on the site require attention.', 'antihacker');
        $message .= $string_result . "\n\n";
        $message .= __('Visit the Anti Hacker plugin dashboard for additional tips on enhancing site security.', 'antihacker') . "\n\n";
        $message .= __('You can stop emails at the Notifications Tab.', 'antihacker') . "\n";
        $message .= __('Dashboard => Anti Hacker => Settings => Notifications Settings (tab)', 'antihacker') . "\n";
        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        $success = wp_mail($antihacker_admin_email, $subject, $message, $headers);

        if (!$success) {
          error_log('Failed to send email antihacker_plugin_abandoned_email');
        }
      }
    }
  }
}


function antihacker_scan_plugins()
{
  try {
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    //debug2();

    $all_plugins_work = get_plugins();
    if (!is_array($all_plugins_work) || empty($all_plugins_work)) {
      throw new Exception("Unable to retrieve plugins. get_plugins() failed.");
    }
    $all_plugins = array_keys($all_plugins_work);
    $q = count($all_plugins);
    if ($q <= 0) {
      throw new Exception("No plugins found.");
    }
    $result = array();
    for ($i = 0; $i < $q; $i++) {
      $pos = strpos($all_plugins[$i], '/');
      $myplugin = trim(substr($all_plugins[$i], 0, $pos));
      if (empty($myplugin) || strlen($myplugin) < 3) {
        continue;
      }
      // debug2();

      $pluginData = antihacker_CheckPluginUpdate($myplugin);

      if (!is_array($pluginData) || !isset($pluginData['last_updated'])) {
        $last_update = 'Not Found';
      } else {
        $last_update = substr($pluginData['last_updated'], 0, 10);
      }
      // Ajuste da lógica para determinar o timeout
      if ($last_update !== 'Not Found') {
        $timeout = strtotime($last_update) + (60 * 60 * 24 * 365);
        $plugin_info = esc_attr($last_update) . ' - ' . esc_attr($myplugin);
        if ($timeout < time()) {
          $plugin_info = '***' . $plugin_info . '***';
        }
      } else {
        // Defina um valor padrão para evitar problemas no loop
        $plugin_info = 'Not Found => ' . esc_attr($myplugin);
      }

      $result[] = $plugin_info; // Adiciona a string ao array
    }
    return $result; // Retorna o array de strings
  } catch (Exception $e) {
    // Tratar a exceção aqui ou logar para fins de depuração
    error_log("Exception in antihacker_scan_plugins(): " . $e->getMessage());
    return false; // Retorna false em caso de falha
  }
}

function antihacker_CheckPluginUpdate($plugin)
{
  try {

    // debug2($plugin);

    $response = wp_remote_get('https://api.wordpress.org/plugins/info/1.0/' . esc_attr($plugin) . '.json');
    if (is_wp_error($response)) {
      throw new Exception("Failed to retrieve plugin information for $plugin. Error: " . $response->get_error_message());
    }
    $body = wp_remote_retrieve_body($response);
    if (empty($body)) {
      throw new Exception("Empty response body for $plugin.");
    }
    $decoded_body = json_decode($body, true);
    if (!$decoded_body) {
      throw new Exception("Failed to decode JSON response for $plugin.");
    }
    return $decoded_body;
  } catch (Exception $e) {
    error_log("Exception in antihacker_CheckPluginUpdate($plugin): " . $e->getMessage());
    return array();
  }
}
//---------------- END $antihacker_plugin_abandoned_email

// add_action('init','antihacker_add_cors_http_header');
//$out2 = ob_get_contents();
//ob_end_clean ( );

/*

add_action('init', 'antihacker_schedule_cron_event_plugins_scan');

function antihacker_schedule_cron_event_plugins_scan()
{
  // Check if the cron event is already scheduled
  if (!wp_next_scheduled('antihacker_cron_event_plugins_scan')) {
    // Schedule the event to run once week
    wp_schedule_event(time(), 'antihacker_once_week', 'antihacker_cron_event_plugins_scan');
  }
}

// Add a new interval of 1 week
add_filter('cron_schedules', 'antihacker_add_cron_interval_plugins_scan');

function antihacker_add_cron_interval_plugins_scan($schedules)
{
  $schedules['antihacker_once_week'] = array(
    'interval' => (60 * 24 * 7),
    'display'  => 'Once Per Week'
  );
  return $schedules;
}

// Function to be executed by the cron event
add_action('antihacker_cron_event_plugins_scan', 'antihacker_automatic_plugin_scan');
*/


// Adiciona o intervalo personalizado de 1 semana
add_filter('cron_schedules', 'antihacker_add_cron_interval_plugins_scan');
function antihacker_add_cron_interval_plugins_scan($schedules)
{
  $schedules['antihacker_once_week'] = array(
    'interval' => 60 * 60 * 24 * 7, // 1 semana em segundos
    'display'  => __('Once Per Week')
  );
  return $schedules;
}

// Agendamento do evento de cron
add_action('init', 'antihacker_schedule_cron_event_plugins_scan');
function antihacker_schedule_cron_event_plugins_scan()
{
  // Verifica se o evento já está agendado
  if (!wp_next_scheduled('antihacker_cron_event_plugins_scan')) {
    // Agende o evento para o intervalo personalizado
    wp_schedule_event(time(), 'antihacker_once_week', 'antihacker_cron_event_plugins_scan');
  }
}

// Função a ser executada pelo evento de cron
add_action('antihacker_cron_event_plugins_scan', 'antihacker_automatic_plugin_scan');





// 2 antihacker_upd_tor_db -> daily

add_action('init', 'antihacker_schedule_cron_event_update_tor');

function antihacker_schedule_cron_event_update_tor()
{
  // Check if the cron event is already scheduled
  if (!wp_next_scheduled('antihacker_cron_event_update_tor')) {
    // Schedule the event to run once week
    wp_schedule_event(time(), 'antihacker_daily_tor', 'antihacker_cron_event_update_tor');
  }
}

// Add a new interval of 1 week
add_filter('cron_schedules', 'antihacker_add_cron_interval_update_tor');

function antihacker_add_cron_interval_update_tor($schedules)
{
  $schedules['antihacker_daily-tor'] = array(
    'interval' => (60 * 60 * 24),
    'display'  => 'Daily'
  );
  return $schedules;
}

// Function to be executed by the cron event
add_action('antihacker_cron_event_update_tor', 'antihacker_upd_tor_db');


// 3 antihacker_cron_function_clean_db daily

/*
add_action('init', 'antihacker_schedule_cron_event_clean_db');

function antihacker_schedule_cron_event_clean_db()
{
  // Check if the cron event is already scheduled
  if (!wp_next_scheduled('antihacker_cron_event_clean_db')) {
    // Schedule the event to run once week
    wp_schedule_event(time(), 'antihacker_daily_clean', 'antihacker_cron_event_clean_db');
  }
}

// Add a new interval of 1 week
add_filter('cron_schedules', 'antihacker_add_cron_interval_clean_db');

function antihacker_add_cron_interval_clean_db($schedules)
{
  $schedules['antihacker_daily_clean'] = array(
    'interval' => (60 * 60 * 24),
    'display'  => 'Daily'
  );
  return $schedules;
}

// Function to be executed by the cron event
add_action('antihacker_cron_event_clean_db', 'antihacker_cron_function_clean_db');
// cron 24 end

*/

/*
function antihacker_findip()
{
    $ip = '';
    $headers = array(
        'HTTP_CLIENT_IP',        // Bill
        'HTTP_X_REAL_IP',        // Bill
        'HTTP_X_FORWARDED',      // Bill
        'HTTP_FORWARDED_FOR',    // Bill 
        'HTTP_FORWARDED',        // Bill
        'HTTP_X_CLUSTER_CLIENT_IP', //Bill
        'HTTP_CF_CONNECTING_IP', // CloudFlare
        'HTTP_X_FORWARDED_FOR',  // Squid and most other forward and reverse proxies
        'REMOTE_ADDR',           // Default source of remote IP
    );
    for ($x = 0; $x < 8; $x++) {
        foreach ($headers as $header) {
            if (!isset($_SERVER[$header]))
                continue;
            $myheader = trim(sanitize_text_field($_SERVER[$header]));
            if (empty($myheader))
                continue;
            $ip = trim(sanitize_text_field($_SERVER[$header]));
            if (empty($ip)) {
                continue;
            }
            if (false !== ($comma_index = strpos(sanitize_text_field($_SERVER[$header]), ','))) {
                $ip = substr($ip, 0, $comma_index);
            }
            // First run through. Only accept an IP not in the reserved or private range.
            if ($ip == '127.0.0.1') {
                $ip = '';
                continue;
            }
            if (0 === $x) {
                $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE);
            } else {
                $ip = filter_var($ip, FILTER_VALIDATE_IP);
            }
            if (!empty($ip)) {
                break;
            }
        }
        if (!empty($ip)) {
            break;
        }
    }
    if (!empty($ip))
        return $ip;
    else
        return 'unknow';
}
*/

/*  INICIO CRON com problema */
function antihacker_cron_function_clean_db()
{
  // Função que executa o trabalho desejado no cron
  global $wpdb;

  // Limpeza do banco de dados (exemplo)
  $table_name = $wpdb->prefix . "ah_blockeds";
  $wpdb->query("DELETE FROM $table_name WHERE `date` < CURDATE() - INTERVAL 1 DAY");


  $table_name = $wpdb->prefix . "ah_visitorslog";
  $keep_log_setting = get_option('antihacker_keep_log', '30');
  $wpdb->query($wpdb->prepare(
    "DELETE FROM {$table_name} WHERE `date` < CURDATE() - INTERVAL %d DAY",
    absint($keep_log_setting)
  ));
  // $wpdb->query("DELETE FROM $table_name WHERE `date` < CURDATE() - INTERVAL 30 DAY");

  $table_name = $wpdb->prefix . "ah_fingerprint";
  $wpdb->query("DELETE FROM $table_name WHERE `data` < CURDATE() - INTERVAL 60 DAY");

  $wdata = date("md", strtotime('tomorrow'));
  $table_name = $wpdb->prefix . "ah_stats";

  $wpdb->get_results($wpdb->prepare(
    "UPDATE $table_name 
         SET qrate='', qnoref='', qtools='', qblank='', qlogin='', qtor='', qfire='', qenum='', qtotal='', qplugin='', qtema='', qfalseg='', qblack=''
         WHERE `date` = %s",
    $wdata
  ));
}

// 1. Primeiro, registramos o filtro para adicionar o intervalo de cron (1 dia)





add_filter('cron_schedules', 'antihacker_add_cron_interval_clean_db');


/*
function antihacker_add_cron_interval_clean_db($schedules)
{
  // Intervalo de 1 dia (60 segundos * 60 minutos * 24 horas)
  $schedules['antihacker_daily_clean'] = array(
    'interval' => 60 * 60 * 24,  // 1 dia
    'display'  => 'Daily'        // Descrição para o intervalo
  );
  return $schedules;
}
  */


//

function antihacker_add_cron_interval_clean_db($schedules)
{
  try {
    // Tente registrar o intervalo
    $schedules['antihacker_daily_clean'] = array(
      'interval' => 60 * 60 * 24,  // 1 dia em segundos
      'display'  => 'Daily'
    );

    // Se a adição do intervalo falhar, uma exceção será lançada
    if (empty($schedules['antihacker_daily_clean'])) {
      throw new Exception('Fail: Unable to record cron interval "antihacker_daily_clean"');
    }
  } catch (Exception $e) {
    // Não registramos no error_log, apenas capturamos a exceção
    // Aqui você pode fazer qualquer outra coisa, se necessário, mas sem logar no arquivo de erros
  }

  return $schedules;
}


/////





// 2. Depois, agendamos o evento cron para rodar com o intervalo registrado
add_action('init', 'antihacker_schedule_cron_event_clean_db');

/*
function antihacker_schedule_cron_event_clean_db()
{
  // Verifica se o evento já está agendado
  if (!wp_next_scheduled('antihacker_cron_event_clean_db')) {
    // Agenda o evento para rodar diariamente com o intervalo 'antihacker_daily_clean'
    wp_schedule_event(time(), 'antihacker_daily_clean', 'antihacker_cron_event_clean_db');
  }
}
  */

function antihacker_schedule_cron_event_clean_db()
{
  try {
    // Verifica se o evento já está agendado
    if (!wp_next_scheduled('antihacker_cron_event_clean_db')) {
      // Agenda o evento para rodar diariamente com o intervalo 'antihacker_daily_clean'
      wp_schedule_event(time(), 'antihacker_daily_clean', 'antihacker_cron_event_clean_db');
    }
  } catch (Exception $e) {
    // Captura a exceção e não faz log no error_log
    // Aqui você pode adicionar algum tratamento se necessário, mas sem registrar no error_log
  }
}

// 3. Função a ser chamada quando o cron job for executado
add_action('antihacker_cron_event_clean_db', 'antihacker_cron_function_clean_db');






/*  FIM CRON com problema */



function antihacker_check_wordpress_logged_in_cookie()
{
  // Percorre todos os cookies definidos
  foreach ($_COOKIE as $key => $value) {
    // Verifica se algum cookie começa com 'wordpress_logged_in_'
    if (strpos($key, 'wordpress_logged_in_') === 0) {
      // Cookie encontrado
      return true;
    }
  }
  // Cookie não encontrado
  return false;
}

function antihacker_findip()
{
  $headers = array(
    'HTTP_CLIENT_IP',        // Bill
    'HTTP_X_REAL_IP',        // Bill
    'HTTP_X_FORWARDED',      // Bill
    'HTTP_FORWARDED_FOR',    // Bill 
    'HTTP_FORWARDED',        // Bill
    'HTTP_X_CLUSTER_CLIENT_IP', //Bill
    'HTTP_CF_CONNECTING_IP', // CloudFlare
    'HTTP_X_FORWARDED_FOR',  // Squid and most other forward and reverse proxies
    'REMOTE_ADDR',           // Default source of remote IP
  );



  // Correct: Initialize notice_added flag outside the loop
  $notice_added = false;

  foreach ($headers as $header) {
    if (isset($_SERVER[$header])) {
      $ip = trim(sanitize_text_field($_SERVER[$header]));
      if (!empty($ip)) {
        if (strpos($ip, ',') !== false) {
          $ip = substr($ip, 0, strpos($ip, ','));
        }
        // Correct: Only check and return validated IP if not localhost
        if ($ip !== '127.0.0.1') {
          $validated_ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE);
          if ($validated_ip) {
            return $validated_ip;
          }
        }
      }
    }
  }



  return 'unknown';
}




// more...
function antihacker_bill_more()
{
  global $antihacker_is_admin;
  //if (function_exists('is_admin') && function_exists('current_user_can')) {
  if ($antihacker_is_admin and current_user_can("manage_options")) {
    $declared_classes = get_declared_classes();
    foreach ($declared_classes as $class_name) {
      if (strpos($class_name, "Bill_show_more_plugins") !== false) {
        // return;
      }
    }
    require_once dirname(__FILE__) . "/includes/more-tools/class_bill_more.php";
  }
  //}
}
add_action("init", "antihacker_bill_more");


function antihacker_new_more_plugins()
{
  $plugin = new antihacker_Bill_show_more_plugins();
  $plugin->bill_show_plugins();
}



function antihacker_load_chat()
{
  global $antihacker_is_admin;
  if ($antihacker_is_admin and current_user_can("manage_options")) {
    // ob_start();
    //debug2();

    if (!class_exists('antihacker_BillChat\ChatPlugin')) {
      require_once dirname(__FILE__) . "/includes/chat/class_bill_chat.php";
    }
  }
}
add_action('wp_loaded', 'antihacker_load_chat');


//debug2();
function antihacker_bill_hooking_diagnose()
{
  global $antihacker_is_admin;
  // if (function_exists('is_admin') && function_exists('current_user_can')) {
  if ($antihacker_is_admin and current_user_can("manage_options")) {
    $declared_classes = get_declared_classes();
    foreach ($declared_classes as $class_name) {
      if (strpos($class_name, "Bill_Diagnose") !== false) {
        return;
      }
    }
    $plugin_slug = 'antihacker';
    $plugin_text_domain = $plugin_slug;
    $notification_url = "https://wpmemory.com/fix-low-memory-limit/";
    $notification_url2 =
      "https://wptoolsplugin.com/site-language-error-can-crash-your-site/";
    //debug2();
    require_once dirname(__FILE__) . "/includes/diagnose/class_bill_diagnose.php";
  }
  //  } 
}
add_action("init", "antihacker_bill_hooking_diagnose", 10);
//
//



function antihacker_bill_hooking_catch_errors()
{
  global $antihacker_is_admin;
  global $antihacker_plugin_slug;

  if (!function_exists("bill_check_install_mu_plugin")) {
    require_once dirname(__FILE__) . "/includes/catch-errors/bill_install_catch_errors.php";
  }

  $declared_classes = get_declared_classes();
  foreach ($declared_classes as $class_name) {
    if (strpos($class_name, "bill_catch_errors") !== false) {
      return;
    }
  }
  $antihacker_plugin_slug = 'antihacker';
  require_once dirname(__FILE__) . "/includes/catch-errors/class_bill_catch_errors.php";
}
add_action("init", "antihacker_bill_hooking_catch_errors", 15);
// ---------------------------






// ------------------------

function antihacker_load_feedback()
{
  global $antihacker_is_admin;
  //if (function_exists('is_admin') && function_exists('current_user_can')) {
  if ($antihacker_is_admin and current_user_can("manage_options")) {
    // ob_start();
    //
    require_once dirname(__FILE__) . "/includes/feedback-last/feedback-last.php";
    // ob_end_clean();
    //
  }
  //}
  //
}
add_action('wp_loaded', 'antihacker_load_feedback', 10);


// ------------------------


function antihacker_bill_install()
{
  global $antihacker_is_admin;
  if ($antihacker_is_admin and current_user_can("manage_options")) {
    $declared_classes = get_declared_classes();
    foreach ($declared_classes as $class_name) {
      if (strpos($class_name, "Bill_Class_Plugins_Install") !== false) {
        return;
      }
    }
    if (!function_exists('bill_install_ajaxurl')) {
      function bill_install_ajaxurl()
      {
        echo '<script type="text/javascript">
					var ajaxurl = "' .
          esc_attr(admin_url("admin-ajax.php")) .
          '";
					</script>';
      }
    }
    // ob_start();
    $plugin_slug = 'antihacker';
    $plugin_text_domain = $plugin_slug;
    $notification_url = "https://wpmemory.com/fix-low-memory-limit/";
    $notification_url2 =
      "https://wptoolsplugin.com/site-language-error-can-crash-your-site/";
    $logo = ANTIHACKERIMAGES . '/logo.png';
    //$plugin_adm_url = admin_url('tools.php?page=antihacker_new_more_plugins');
    $plugin_adm_url = admin_url();
    require_once dirname(__FILE__) . "/includes/install-checkup/class_bill_install.php";
    // ob_end_clean();
  }
}
add_action('wp_loaded', 'antihacker_bill_install', 15);


function antihacker_get_request_method()
{
  // Initialize an empty array to hold headers if not set
  $headers = [];

  // Check if the standard request method variable is available
  if (isset($_SERVER['REQUEST_METHOD'])) {
    return sanitize_text_field($_SERVER['REQUEST_METHOD']);
  }

  // Use getallheaders() to retrieve all HTTP headers and check for custom method override
  if (function_exists('getallheaders')) {
    $headers = getallheaders();
    if (isset($headers['X-HTTP-Method-Override'])) {
      return strtoupper(sanitize_text_field($headers['X-HTTP-Method-Override']));
    }
  }

  // Analyze global variables to infer the request method
  if (!empty($_POST)) {
    return 'POST';
  } elseif (!empty($_GET)) {
    return 'GET';
  } elseif (isset($headers['Content-Length']) && intval($headers['Content-Length']) > 0) {
    return 'PUT';
  }

  // Fallback: assume GET request if nothing else matches
  return 'GET';
}
// ------------------------------------



// Hook para ativação do plugin
//register_activation_hook(__FILE__, 'capture_unexpected_output');

function capture_unexpected_output()
{
  // Captura e limpa qualquer saída inesperada
  $output = ob_get_clean();

  // Verifica se há saída inesperada
  // if (!empty($output)) {
  // Grava a saída no log de erros do PHP
  debug4("[Plugin Activation Output] " . $output);
  // die(var_dump($output));
  // }
}
