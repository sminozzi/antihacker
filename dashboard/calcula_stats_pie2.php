<?php
/**
 * @author William Sergio Minossi
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;
$table_name = $wpdb->prefix . "ah_visitorslog";


//$quantos_bots = $wpdb->get_var("SELECT COUNT(*) FROM `$table_name` WHERE `human` = '0'");
//$quantos_humanos = $wpdb->get_var("SELECT COUNT(*) FROM `$table_name` WHERE `human` = '1'");

$quantos_bots = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %i WHERE `human` = '0'", $table_name));
$quantos_humanos = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %i WHERE `human` = '1'", $table_name));

if($quantos_humanos < 1)
    $quantos_humanos = 1;
if($quantos_bots < 1)
{
    esc_attr_e("Just give us a little time to collect data so we can display it for you here.","antihacker");
    return;
}
$total = $quantos_bots +  $quantos_humanos;
$antihacker_results10[0]['Bots'] = $quantos_bots/$total;
$antihacker_results10[0]['Humans'] = $quantos_humanos/$total; 
