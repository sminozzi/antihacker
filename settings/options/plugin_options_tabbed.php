<?php

namespace Antihacker\WP\Settings;

if (!defined('ABSPATH')) exit; // Exit if accessed directly 
// $mypage = new Page('Anti Hacker', array('type' => 'menu'));
$mypage = new Page('Anti Hacker', array('type' => 'submenu', 'parent_slug' => 'anti_hacker_plugin'));
$settings = array();
$myip = antihacker_findip();
require_once(ANTIHACKERPATH . "guide/guide.php");
$settings['Startup Guide']['Startup Guide'] = array('info' => $ah_help);
$fields = array();
$settings['Startup Guide']['Startup Guide']['fields'] = $fields;
$msg2 = esc_attr__('Just check yes or not. After that, click SAVE changes. ', "antihacker");
$msg2 .= '<b>&nbsp;';
$msg2 .= esc_attr__('We suggest click Yes for all. ', "antihacker");
$msg2 .= '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('Please, visit our Faq page for more details (button at Startup Guide Tab).', "antihacker");
$msg2 .= '<br />';
$settings['General Settings']['settings'] = array('info' => $msg2);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'my_radio_xml_rpc',
	'label' => esc_attr__('Disable xml-rpc API. Look our FAQ page (at our site) for more details.', "antihacker"),
	'radio_options' => array(
		array('value' => 'Yes', 'label' => esc_attr__('Yes, disable All', "antihacker")),
		array('value' => 'Pingback', 'label' => esc_attr__('Yes, disable only Ping Back', "antihacker")),
		//	array('value'=>'Autentic', 'label' =>esc_attr__('Yes, disable only Call with Authentication Request (wp.getUsersBlogs)', "antihacker")),		
		array('value' => 'No', 'label' => esc_attr__('No', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_rest_api',
	'label' => esc_attr__("Disable Json WordPress Rest API (also new WordPress 4.7 Rest API). Take a look our faq page (at our site) for details. Don't disable if you want to use Contact 7 Form Plugin.", "antihacker"),
	'radio_options' => array(
		array('value' => 'Yes', 'label' => esc_attr__('Yes, disable', "antihacker")),
		array('value' => 'No', 'label' => esc_attr__('No', "antihacker")),
	)
);
/*
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_automatic_plugins',
	'label' =>esc_attr__('Sets WordPress to automatically download and install plugin updates.', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' =>esc_attr__('No', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_automatic_themes',
	'label' =>esc_attr__('Sets WordPress to automatically download and install themes updates.', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' =>esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' =>esc_attr__('No', "antihacker")),
	)
);
*/
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_replace_login_error_msg',
	'label' => esc_attr__('Sets WordPress to replace the login error message to Wrong Username or Password', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_disallow_file_edit',
	'label' => esc_attr__('Disable file editing within the WordPress dashboard', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_debug_is_true',
	'label' => esc_attr__('Enable dashboard warning message when Debug is true', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_firewall',
	'label' => esc_attr__('Enable Firewall? (Block Malicious Requests, Queries, User Agents and URLS. 100% Plug-n-play, no configuration required.)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_hide_wp',
	'label' => esc_attr__('Hide WordPress Version to improve security?', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_enumeration',
	'label' => esc_attr__('Block User enumeration to improve security?', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_all_feeds',
	'label' => esc_attr__('Block All Feeds to avoid bots exploit.', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_media_comments',
	'label' => esc_attr__('Block Comments in Media Pages.', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);





$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_new_user_subscriber',
	'label' => esc_attr__('Create All New Users always as Subscriber. Protect you from flaws in plugins and themes and don\'t allow hackers include new Administrator.', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);



$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_falsegoogle',
	'label' => esc_attr__('Block False Googlebot, Msnbot & Bingbot? (Premium Version Only)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);


$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_search_plugins',
	'label' => esc_attr__('Block Search For Plugin Vulnerabilities? (Premium Version Only)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)
);


$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_search_themes',
	'label' => esc_attr__('Block Search For Theme Vulnerabilities? (Premium Version Only)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)

);

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_tor',
	'label' => esc_attr__('Block all traffic from Tor? - The Onion Router -  (Premium Version Only)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker"))
	)

);

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_blank_ua',
	'label' => esc_attr__('Block all with Blank User Agent? -  (Premium Version Only)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('no', "antihacker"))
	)
);


// For example, to completely disable Application Passwords add the following 
// code snippet to your site.

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_application_password',
	'label' => esc_attr__('Disable Application Passwords (WordPress version 5.6 allows external applications to request permission to connect to a site and generate a password)', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', 'antihacker')),
		array('value' => 'no', 'label' => esc_attr__('no', 'antihacker'))
	)
);

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_show_widget',
	'label' => esc_attr__('Show Anti Hacker Widget at main Dashboard page (only for Admin users).', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', 'antihacker')),
		array('value' => 'no', 'label' => esc_attr__('no', 'antihacker'))
	)
);

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_disable_sitemap',
	'label' => esc_attr__("Disable WordPress Native XML Sitemap for USER Listings.(This is not about the site's main XML sitemap)", "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes, disable', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker")),
	)
);


$fields[] = array(
	'type'           => 'select',
	'name'           => 'antihacker_keep_log',
	'label'          => __('How long keep the visitors log file? If you have a very heavy traffic, select 1 day. Your choices can impact the Blocked Visits Log.', 'antihacker'),
	'id'             => 'my_select', // (optional, will default to name)
	'value'          => 'red', // (optional, will default to '')
	'select_options' => array(
		array(
			'value' => '30',
			'label' => __('Select', 'antihacker'),
		),
		array(
			'value' => '1',
			'label' => '1 ' . __('day', 'antihacker'),
		),
		array(
			'value' => '3',
			'label' => '3 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '7',
			'label' => '7 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '14',
			'label' => '14 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '21',
			'label' => '21 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '30',
			'label' => '30 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '90',
			'label' => '90 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '180',
			'label' => '180 ' . __('days', 'antihacker'),
		),
		array(
			'value' => '360',
			'label' => '360 ' . __('days', 'antihacker'),
		),
	),
);

$settings['General Settings']['settings']['fields'] = $fields;



//
$msg2 = '<br />';
$msg2 .= '<b>' . __('This page works only in Premium Version.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('Bots and Hackers can make a lot of visits in a short time period.', 'antihacker');

$msg2 .= '<br />';
$msg2 .= esc_attr__('We can limit a number of visits, just choose the options below.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('You can also whitelist Ips, look the Whitelist tab.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('Search engine crawlers has unlimited access.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('IP will be blocked by one hour.', 'antihacker') . '</b>';
$msg2 .= '<br />';

$settings['Limit Visits'][__('Instructions')] = array('info' => $msg2);


$fields = array();

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_radio_limit_visits',
	'label' => esc_attr__('Enable the Rate Limiting?', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('no', "antihacker"))
	)
);



// Select List
$fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'antihacker_rate_limiting',
	'label' => esc_attr__('If requests exceed', 'antihacker'),
	'id' => 'rate_limiting', // (optional, will default to name)
	'value' => 'red', // (optional, will default to '')
	'select_options' => array(
		array('value' => 'unlimited', 'label' => esc_attr__('Unlimited', "antihacker")),
		array('value' => '5', 'label' => esc_attr__('5 per minute', "antihacker")),
		array('value' => '6', 'label' => esc_attr__('6 per minute', "antihacker")),
		array('value' => '7', 'label' => esc_attr__('7 per minute', "antihacker")),
		array('value' => '8', 'label' => esc_attr__('8 per minute', "antihacker")),
		array('value' => '9', 'label' => esc_attr__('9 per minute', "antihacker")),
		array('value' => '10', 'label' => esc_attr__('10 per minute', "antihacker")),
		array('value' => '15', 'label' => esc_attr__('15 per minute', "antihacker")),
		array('value' => '20', 'label' => esc_attr__('30 per minute', "antihacker")),
		array('value' => '30', 'label' => esc_attr__('40 per minute', "antihacker")),
		array('value' => '50', 'label' => esc_attr__('50 per minute', "antihacker")),
	)
);

$fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'antihacker_rate_limiting_day',
	'label' => esc_attr__('if requests exceed', 'antihacker'),
	'id' => 'rate_limiting_day', // (optional, will default to name)
	'value' => 'red', // (optional, will default to '')
	'select_options' => array(
		array('value' => 'unlimited', 'label' => esc_attr__('Unlimited', "antihacker")),
		array('value' => '1', 'label' => esc_attr__('5 per hour', "antihacker")),
		array('value' => '2', 'label' => esc_attr__('10 per hour', "antihacker")),
		array('value' => '3', 'label' => esc_attr__('20 per hour', "antihacker")),
		array('value' => '4', 'label' => esc_attr__('50 per hour', "antihacker")),
		array('value' => '5', 'label' => esc_attr__('100 per hour', "antihacker")),
		array('value' => '6', 'label' => esc_attr__('200 per hour', "antihacker")),
		array('value' => '7', 'label' => esc_attr__('500 per hour', "antihacker"))

	)
);

/*
	$fields[] = array(
		'type' 	=> 'select',
		'name' 	=> 'antihacker_rate_penalty',
		'label' =>esc_attr__('How long is an IP address blocked when it breaks a rule','antihacker'),
		'id' => 'my_select', // (optional, will default to name)
		'value' => 'red', // (optional, will default to '')
		'select_options' => array(
			array('value'=>'2', 'label' =>esc_attr__('5 minutes', "antihacker")),
			array('value'=>'3', 'label' =>esc_attr__('30 minutes', "antihacker")),
			array('value'=>'4', 'label' =>esc_attr__('1 Hour', "antihacker")),
			array('value'=>'5', 'label' =>esc_attr__('2 Hours', "antihacker")),
			array('value'=>'6', 'label' =>esc_attr__('6 Hours', "antihacker")),
			array('value'=>'7', 'label' =>esc_attr__('24 Hours', "antihacker"))
	
			)			
		);

*/

$fields[] = array(
	'type' 	=> 'select',
	'name' 	=> 'antihacker_rate404_limiting',
	'label' => esc_attr__('If made only 404 requests exceed', 'antihacker'),
	'id' => 'rate404_limiting', // (optional, will default to name)
	'value' => 'red', // (optional, will default to '')
	'select_options' => array(
		array('value' => 'unlimited', 'label' => esc_attr__('Unlimited', "antihacker")),
		array('value' => '5', 'label' => esc_attr__('five 404 pages', "antihacker")),
		array('value' => '10', 'label' => esc_attr__('ten 404 pages', "antihacker")),
		array('value' => '15', 'label' => esc_attr__('fifteen 404 pages', "antihacker")),
		array('value' => '20', 'label' => esc_attr__('twenty 404 pages', "antihacker")),
		array('value' => '50', 'label' => esc_attr__('fifty 404 pages', "antihacker")),
	)
);

$settings['Limit Visits']['']['fields'] = $fields;



//
$msg2 = '<br />';
$msg2 .= '<b>' . __('This page works only in Premium Version.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= '<b>' . __('HTTP Tools are tools to do HTTP request, used for not humans.', 'antihacker') . '</b>';

$msg2 .= '<br />';
$msg2 .= esc_attr__('To Block HTTP Tools, just add one for each line.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('To Manage, you can also remove one or more lines. Then, click Save Changes.', 'antihacker') . '</b>';
$msg2 .= '<br />';
$msg2 .= esc_attr__('Activate eMail notification for some days and manage the Whitelist tables.', 'antihacker') . '</b>';
$msg2 .= '<br />';

$settings['Block HTTP Tools'][__('Instructions about to block HTTP tools (Only Premium)', 'antihacker')] = array('info' => $msg2);

$fields = array();

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_block_http_tools',
	'label' => esc_attr__('Block HTTP tools?', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('no', "antihacker"))
	)
);

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_update_http_tools',
	'label' => esc_attr__('Update HTTP tools from Anti Hacker Plugin Server each new plugin version or plugin is activated? Maybe you will need remove that tools you don\'t want to block again.', 'antihacker'),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('no', "antihacker"))
	)
);



$fields[] = array(
	'type' 	=> 'textarea',
	'name' 	=> 'antihacker_http_tools',
	'label' => 'HTTP tools to block:'
);



$settings['Block HTTP Tools']['']['fields'] = $fields;

//



$msg2 = esc_attr__('Add IPs or strings to your whitelist, then click SAVE CHANGES.', "antihacker");
$msg2 .= '<br />';
$msg2 .= esc_attr__('If necessary add more than one, use only one ip by line.', "antihacker");
$msg2 .= '<br />';
$msg2 .= esc_attr__('You can also add IPs from the Blocked Visitors Log Table page.', "antihacker");
$msg2 .= '<br />';
$msg2 .=  '<b>';
$msg2 .= esc_attr__('Your current ip is: ', "antihacker");
$msg2 .= $myip;
$msg2 .=  '</b>';
$settings['Whitelist']['whitelist'] = array('info' => $msg2);

$fields = array();

$fields[] = array(
	'type' 	=> 'textarea',
	'name' 	=> 'antihacker_my_whitelist',
	'label' => esc_attr__('IP whitelist', 'antihacker'),

);
$fields[] = array(
	'type' 	=> 'textarea',
	'name' 	=> 'antihacker_string_whitelist',
	'label' => esc_attr__('String whitelist (no case sensitive)', 'antihacker'),
);

$settings['Whitelist']['whitelist']['fields'] = $fields;
$admin_email_wp = get_option('admin_email');
$msg_email = esc_attr__('Fill out the email address to send messages.', "antihacker");
$msg_email .= '<br />';
$msg_email = esc_attr__('Left Blank to use your default Wordpress email.', "antihacker");
$msg_email .= '<br />';
$msg_email .= esc_attr__('Then, click save changes.', "antihacker");
$settings['Email Settings']['email'] = array('info' => $msg_email);
$fields = array();
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'antihacker_my_email_to',
	'label' => 'email'
);
$settings['Email Settings']['email']['fields'] = $fields;
//$admin_email = get_option( 'admin_email' ); 


$notificatin_msg = esc_attr__('Do you want receive email alerts? ', "antihacker");
$settings['Notifications Settings']['Notifications'] = array('info' => $notificatin_msg);
$fields = array();

/*
$fields[] = array(
	'type' 	=> 'checkbox',
	'name' 	=> 'antihacker_checkbox_all_failed',
	'label' =>esc_attr__('Alert me all Failed Login Attempts', "antihacker")
);
*/

$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_checkbox_all_failed',
	'label' => esc_attr__('Alert me all Failed Login Attempts', "antihacker"),
	'radio_options' => array(
		array('value' => '1', 'label' => esc_attr__('Yes, All', "antihacker")),
		array('value' => '0', 'label' => esc_attr__('No', "antihacker")),
	)
);


$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_my_radio_report_all_logins',
	'label' => esc_attr__('Alert me All Logins', "antihacker"),
	'radio_options' => array(
		array('value' => 'Yes', 'label' => esc_attr__('Yes, All', "antihacker")),
		array('value' => 'No', 'label' => esc_attr__('No, Alert me Only Not White listed', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_Blocked_Firewall',
	'label' => esc_attr__('Alert me All Times Firewall Block Something.', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker")),
	)
);
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'antihacker_Blocked_Else_email',
	'label' => esc_attr__('Alert me All Times Plugin Blocks Something Else?', "antihacker"),
	'radio_options' => array(
		array('value' => 'yes', 'label' => esc_attr__('Yes', "antihacker")),
		array('value' => 'no', 'label' => esc_attr__('No', "antihacker")),
	)
);
$settings['Notifications Settings']['Notifications']['fields'] = $fields;


$fields = array();

$fields = array();

if (is_multisite()) {

	$url = esc_url(ANTIHACKERHOMEURL) . "plugin-install.php?s=sminozzi&tab=search&type=author";

	$msg = '<script>';
	$msg .= 'window.location.replace("' . $url . '");';
	$msg .= '</script>';
} else {
	$msg =  '<script>';
	$msg .= 'window.location.replace("' . esc_url(ANTIHACKERHOMEURL) . '/admin.php?page=antihacker_new_more_plugins");';
	// $msg .= 'window.location.replace("'.esc_url(ANTIHACKERHOMEURL).'plugin-install.php?s=sminozzi&tab=search&type=author");';
	$msg .= '</script>';
}

$settings['Useful Tools']['Useful Tools'] = array('info' => $msg);

/*
$settings['Stop Bad Bots Tools']['Stop Bad Bots Tools'] = array('info' => $msg );
$fields = array();
$settings['Stop Bad Bots Tools']['Stop Bad Bots Tools']['fields'] = $fields;
*/


$fields = array();
//$settings['Memory Checkup'][__('Memory Checkup')]['fields'] = $fields;
//
$gopro = '<span style="font-size: 24pt; color: #CC3300;">Premium Features<font color="#000000">';
$gopro .= '<br />';
$gopro .= '<font size="4">';

$gopro .= esc_attr__('Visit our Premium Page for more details.', 'antihacker');
$gopro .= '<br />';
$gopro .= '<a href="http://antihackerplugin.com/premium" class="button button-primary">' . __("Premium Page", "antihacker") . '</a>';
$gopro .= '<br />';
$gopro .= '<br />';
$gopro .= esc_attr__('Paste below the Item Purchase Code received by email from us when you bought the premium version.', 'antihacker');
$gopro .= '<br />';
$gopro .= esc_attr__("You don't need reinstall the plugin.", "antihacker");
$gopro .=  '  ' . __("After that, click SAVE CHANGES Button.", "antihacker");
// Form
$settings['Go Pro']['Go Pro'] = array('info' => $gopro);
// $fields = array();
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'antihacker_checkversion',
	'label' => esc_attr__('Purchase Code:', "antihacker"),
);
$settings['Go Pro']['Go Pro']['fields'] = $fields;


new OptionPageBuilderTabbed($mypage, $settings);
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
			if (!isset($_SERVER[$header])) {
				continue;
			}
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
