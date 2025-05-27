<?php

/**
 * @author William Sergio Minossi
 * @copyright 2016
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

$ah_help = '<big>';

$ah_help .=  "1) ". __("General Settings Tab:" , "antihacker");
$ah_help .=  " " . __("You disable all the xml-rpc API (or only Pingback) and Json WordPress Rest API in that tab and others usefull options to increase the site security. We suggest check yes for all.", "antihacker");


$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  "2) ". __("Limit Visits: You can limit the number of visits by minute or Hour.", "antihacker");



$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  "3) ". __("Block HTTP Tools Tab: Block Hackers and Bots using HTTP tools.", "antihacker");



$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  "4) ". __("Whitelist Tab: Open and add your IP address to the whitelist field (if necessary) and click save changes. You can see your current  IP address at that page.", "antihacker");

$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  "5) ". __("eMail Settings Tab: The email alert will be send to your wordpress user email. You can change this email by click over the tab email settings at the plugin management page.", "antihacker");


$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  "6) ". __("Notification Sectings Tab: Update your's Email Alerts. (Alerts about failed logins, succesfull logins and firewall blocks)", "antihacker");

$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  "7) ". __("Go Pro Tab: Add Your Purchase Code on Purchase Code Field and click Save Changes", "antihacker");

$ah_help .= '<br>';
$ah_help .= '<br>';
$ah_help .= '<br>';

$ah_help .=  '<b>'. __("What Happens if someone not whitelisted try to login (or i change my ip)?", "antihacker");


$ah_help .= '</b><br>';


$ah_help .=  __("Your login page will request your wordpress user email and will send to you one alert email someone not whitelisted just made login. If the email is correct, the login go through. Then, by security, not show your wordpress user email at your page.", "antihacker");

$ah_help .= '<br>';

$ah_help .=  __("To avoid receive the alert email, just add your IP to whitelist. Please, read above (5).", "antihacker" );

$ah_help .= '<br>';

$ah_help .=  __("The email alert will be send to your wordpress user email. You can change this email by click over the tab email settings.", "antihacker");

$ah_help .= '<br>';
$ah_help .= '<br>';
$ah_help .= '<br><b>';


$ah_help .=  __("If necessary, (you are unable to login) you can remove this plugin by FTP. Go to folder: wp-content/plugins/ and remove the folder AntiHacker with all files.", "antihacker");


$ah_help .= '</b><br>';
$ah_help .= '<br><b>';


$ah_help .= '<a href="http://antihackerplugin.com/on-line-manual/" class="button button-primary">'.__("OnLine Guide","antihacker").'</a>';
$ah_help .= '&nbsp;&nbsp;';
$ah_help .= '<a href="http://antihackerplugin.com/faq/" class="button button-primary">'.__("Faq Page","antihacker").'</a>';
$ah_help .= '&nbsp;&nbsp;';
$ah_help .= '<a href="http://antihackerplugin.com" class="button button-primary">'.__("Support Page","antihacker").'</a>';
$ah_help .= '&nbsp;&nbsp;';
$ah_help .= '<a href="http://siterightaway.net/troubleshooting/" class="button button-primary">'.__("Troubleshooting Page","antihacker").'</a>';


$ah_help .= '<br><br>';

$ah_help .= __('That is all. Enjoy it!', "antihacker");

$ah_help .= '</big>';



?>
