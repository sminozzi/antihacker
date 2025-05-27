<?php
/**
 * @author William Sergio Minossi
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;
$table_name = $wpdb->prefix . "ah_stats";
$month_day = date('md');


if($month_day < '0115') {

    /*
    $antihacker_results8 = $wpdb->get_results($wpdb->prepare("SELECT 
        date, 
        qlogin as brute, 
        qtor as tor,
        qfire as firewall,
        qenum as enumeration,
        qplugin as plugin,
        qtema as theme,
        qfalseg as false_se,
        qblack as blacklisted,
        qnoref as noref,
        qblank as blank,
        qtools as tools,
        qrate as rate
        FROM `$table_name`
        WHERE
        `date` <= %s OR `date`  > '1215'", $month_day));

        */




$month_day_sanitized = $wpdb->prepare('%s', $month_day);

// $table_name_sanitized = $wpdb->prepare('%i', $table_name);


$antihacker_results8 = $wpdb->get_results($wpdb->prepare("
    SELECT 
        date, 
        qlogin as brute, 
        qtor as tor,
        qfire as firewall,
        qenum as enumeration,
        qplugin as plugin,
        qtema as theme,
        qfalseg as false_se,
        qblack as blacklisted,
        qnoref as noref,
        qblank as blank,
        qtools as tools,
        qrate as rate
    FROM %i
    WHERE
        `date` <= %s OR `date` > '1215'", $table_name, $month_day_sanitized));




    
    }
    else{

        /*
        $antihacker_results8 = $wpdb->get_results($wpdb->prepare("SELECT 
        date, 
        qlogin as brute, 
        qtor as tor,
        qfire as firewall,
        qenum as enumeration,
        qplugin as plugin,
        qtema as theme,
        qfalseg as false_se,
        qblack as blacklisted,
        qnoref as noref,
        qblank as blank,
        qtools as tools,
    qrate as rate
        FROM `$table_name`
        WHERE
        `date` <= %s", $month_day));
    }
    */

    $antihacker_results8 = $wpdb->get_results($wpdb->prepare("
    SELECT 
        date, 
        qlogin as brute, 
        qtor as tor,
        qfire as firewall,
        qenum as enumeration,
        qplugin as plugin,
        qtema as theme,
        qfalseg as false_se,
        qblack as blacklisted,
        qnoref as noref,
        qblank as blank,
        qtools as tools,
        qrate as rate
    FROM %i
    WHERE
        `date` <= %s", $table_name, $month_day));
    
    }

// $antihacker_results8 = $wpdb->get_results($query);
$antihacker_results9 = json_decode(json_encode($antihacker_results8), true);
unset($antihacker_results8);

$antihacker_results10[0]['brute'] = 0;
$antihacker_results10[0]['firewall'] = 0;
$antihacker_results10[0]['enumeration'] = 0;
$antihacker_results10[0]['plugin'] = 0;
$antihacker_results10[0]['theme'] = 0;
$antihacker_results10[0]['false_se'] = 0;
$antihacker_results10[0]['tor'] = 0;

$antihacker_results10[0]['noref'] = 0;
$antihacker_results10[0]['blank'] = 0;
$antihacker_results10[0]['tools'] = 0;

$antihacker_results10[0]['rate'] = 0;

for($i = 0; $i < count($antihacker_results9); $i++)
{
    $antihacker_results10[0]['brute'] = $antihacker_results10[0]['brute'] + intval( $antihacker_results9[$i]['brute']);
    $antihacker_results10[0]['firewall'] = $antihacker_results10[0]['firewall'] + intval( $antihacker_results9[$i]['firewall']);
    $antihacker_results10[0]['enumeration'] = $antihacker_results10[0]['enumeration'] + intval(  $antihacker_results9[$i]['enumeration']);
    $antihacker_results10[0]['plugin'] = $antihacker_results10[0]['plugin'] + intval( $antihacker_results9[$i]['plugin']);
    $antihacker_results10[0]['theme'] =  $antihacker_results10[0]['theme'] + intval( $antihacker_results9[$i]['theme']);
    $antihacker_results10[0]['false_se'] = $antihacker_results10[0]['false_se'] + intval( $antihacker_results9[$i]['false_se']);
    $antihacker_results10[0]['tor'] = $antihacker_results10[0]['tor'] + intval( $antihacker_results9[$i]['tor']);

    $antihacker_results10[0]['noref'] = $antihacker_results10[0]['noref'] + intval( $antihacker_results9[$i]['noref']);

    $antihacker_results10[0]['blank'] = $antihacker_results10[0]['blank'] + intval( $antihacker_results9[$i]['blank']);

    $antihacker_results10[0]['tools'] = $antihacker_results10[0]['tools'] + intval( $antihacker_results9[$i]['tools']);

    $antihacker_results10[0]['rate'] = $antihacker_results10[0]['tools'] + intval( $antihacker_results9[$i]['rate']);


}
 //print_r($antihacker_results10);
 //die();


return;