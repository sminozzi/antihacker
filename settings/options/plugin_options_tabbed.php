<?php namespace Antihacker\WP\Settings;


$mypage = new Page('Anti Hacker', array('type' => 'menu'));
   
   
$settings = array();

$myip = findip();  

require_once (AHPATH. "guide/guide.php");


$settings['Startup Guide']['Startup Guide'] = array('info' => $ah_help );
$fields = array();   

        
$settings['Startup Guide']['Startup Guide']['fields'] = $fields;



$msg2 = 'Add your current ip to your whitelist, then click SAVE CHANGES. <b> Your current ip is: '.$myip .'</b>';


$settings['General Settings']['whitelist'] = array('info' => $msg2);
$fields = array();   
$fields[] = array(
	'type' 	=> 'textarea',
	'name' 	=> 'my_whitelist',
	'label' => 'whitelist'
	);
        
$settings['General Settings']['whitelist']['fields'] = $fields;



$admin_email = get_option( 'admin_email' ); 
$msg_email = 'Fill out the email address to send messages.<br />Left Blank to use your default Wordpress email.<br />('.$admin_email.')<br />Then, click save changes.';

 
$settings['Email Settings']['email'] = array('info' => $msg_email );
$fields = array();
$fields[] = array(
	'type' 	=> 'text',
	'name' 	=> 'my_email_to',
	'label' => 'email'
	);
$settings['Email Settings']['email']['fields'] = $fields;




//$admin_email = get_option( 'admin_email' ); 
$notificatin_msg = 'Do you want receive alerts? ';
 
$settings['Notifications Settings']['Notifications'] = array('info' => $notificatin_msg );
$fields = array();


$fields[] = array(
	'type' 	=> 'checkbox',
	'name' 	=> 'my_checkbox_all_failed',
	'label' => 'Alert me all Failed Login Attempts'
	);
        
    
$fields[] = array(
	'type' 	=> 'radio',
	'name' 	=> 'my_radio_all_logins',
	'label' => 'Alert me All Logins',
	'radio_options' => array(
		array('value'=>'Yes', 'label' => 'Yes, All'),
		array('value'=>'No', 'label' => 'No, Alert me Only Not White listed'),
		)			
	);    
    
    
    
    
$settings['Notifications Settings']['Notifications']['fields'] = $fields;



new OptionPageBuilderTabbed($mypage, $settings);


function findip()
{

    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $ip = trim($ip);

    if (!empty($ip))
        return $ip;
    else
        return 'unknow';


}