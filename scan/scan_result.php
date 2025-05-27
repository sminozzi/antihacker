<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-21 17:51:46
 */
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
/*
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `date_inic` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `date_end` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `log` text NOT NULL,
        `qfiles` int(11) NOT NULL,
        `mystatus` varchar(20) NOT NULL,
        `debug` text NOT NULL,
        `malware` text NOT NULL,
        `flag` varchar(1) NOT NULL,
        `obs` text NOT NULL,
        UNIQUE (`id`),
        UNIQUE (`name`)
*/
global $wpdb;
$table_name = $wpdb->prefix . "ah_scan";
//$query = "select count(*) from `$table_name`";
//$q =  $wpdb->get_var($query);
$q = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM %i", $table_name));


if($q < 1){
  echo '<h3>'. esc_attr__("No scan made yet.","antihacker"). '</h3>';
  echo esc_attr__("Go to NEW SCAN tab to make a new scan.","antihacker");
  return;
 }
 echo '<h3>'. esc_attr__("Scan Result","antihacker"). '</h3>';

 

//$query = "select malware from `$table_name` ORDER BY id DESC limit 1";
//$r =  $wpdb->get_var($query);
$r = $wpdb->get_var($wpdb->prepare("SELECT malware FROM %i ORDER BY id DESC LIMIT 1", $table_name));


if (empty($r))
   echo esc_attr__("No Malware found...","antihacker");

else {
   //$query = "select date_end from `$table_name` ORDER BY id DESC limit 1";
   //$r2 =  $wpdb->get_var($query);
   $r2 = $wpdb->get_var($wpdb->prepare("SELECT date_end FROM %i ORDER BY id DESC LIMIT 1", $table_name));



   echo esc_attr__("DateScan : ","antihacker").esc_attr(substr($r2,0,10));
  
   echo '<hr>';

   $allowed_atts = array(
    'align'      => array(),
    'class'      => array(),
    'type'       => array(),
    'id'         => array(),
    'dir'        => array(),
    'lang'       => array(),
    'style'      => array(),
    'xml:lang'   => array(),
    'src'        => array(),
    'alt'        => array(),
    'href'       => array(),
    'rel'        => array(),
    'rev'        => array(),
    'target'     => array(),
    'novalidate' => array(),
    'type'       => array(),
    'value'      => array(),
    'name'       => array(),
    'tabindex'   => array(),
    'action'     => array(),
    'method'     => array(),
    'for'        => array(),
    'width'      => array(),
    'height'     => array(),
    'data'       => array(),
    'title'      => array(),

    'checked' => array(),
    'selected' => array(),


  );


   $my_allowed['form'] = $allowed_atts;
   $my_allowed['select'] = $allowed_atts;
   // select options
   $my_allowed['option'] = $allowed_atts;
   $my_allowed['style'] = $allowed_atts;
   $my_allowed['label'] = $allowed_atts;
   $my_allowed['input'] = $allowed_atts;
   $my_allowed['textarea'] = $allowed_atts;

     //more...future...
   $my_allowed['form']     = $allowed_atts;
   $my_allowed['label']    = $allowed_atts;
   $my_allowed['input']    = $allowed_atts;
   $my_allowed['textarea'] = $allowed_atts;
   $my_allowed['iframe']   = $allowed_atts;
   $my_allowed['script']   = $allowed_atts;
   $my_allowed['style']    = $allowed_atts;
   $my_allowed['strong']   = $allowed_atts;
   $my_allowed['small']    = $allowed_atts;
   $my_allowed['table']    = $allowed_atts;
   $my_allowed['span']     = $allowed_atts;
   $my_allowed['abbr']     = $allowed_atts;
   $my_allowed['code']     = $allowed_atts;
   $my_allowed['pre']      = $allowed_atts;
   $my_allowed['div']      = $allowed_atts;
   $my_allowed['img']      = $allowed_atts;
   $my_allowed['h1']       = $allowed_atts;
   $my_allowed['h2']       = $allowed_atts;
   $my_allowed['h3']       = $allowed_atts;
   $my_allowed['h4']       = $allowed_atts;
   $my_allowed['h5']       = $allowed_atts;
   $my_allowed['h6']       = $allowed_atts;
   $my_allowed['ol']       = $allowed_atts;
   $my_allowed['ul']       = $allowed_atts;
   $my_allowed['li']       = $allowed_atts;
   $my_allowed['em']       = $allowed_atts;
   $my_allowed['hr']       = $allowed_atts;
   $my_allowed['br']       = $allowed_atts;
   $my_allowed['tr']       = $allowed_atts;
   $my_allowed['td']       = $allowed_atts;
   $my_allowed['p']        = $allowed_atts;
   $my_allowed['a']        = $allowed_atts;
   $my_allowed['b']        = $allowed_atts;
   $my_allowed['i']        = $allowed_atts;


  // $r = wp_kses($r, $my_allowed);
  // echo  nl2br($r);
   echo wp_kses(nl2br($r), $my_allowed);
}