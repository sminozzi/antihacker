<?php
/**
 * @author William Sergio Minossi
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/*
  $sql = "CREATE TABLE ".$table. " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `date` varchar(4) NOT NULL,
        `qlogin` text NOT NULL,
        `qfire` text NOT NULL,
        `qtotal` varchar(100) NOT NULL,
    UNIQUE (`id`),
    UNIQUE (`date`)
    ) $charset_collate;";
*/
global $wpdb;
$table_name = $wpdb->prefix . "ah_stats";

//$query = "SELECT date,qtotal FROM " . $table_name;
//$results9 = $wpdb->get_results($query);

/*
$query = $wpdb->prepare(
    "SELECT date, qtotal FROM %i",
    $table_name
);
*/

$results9 = $wpdb->get_results($wpdb->prepare("SELECT date, qtotal FROM %i", $table_name));



$results8 = json_decode(json_encode($results9), true);
unset($results9);
$x = 0; 
$d = 15;
for ($i = $d ; $i > 0; $i--)
{
    $timestamp = time();
    $tm = 86400 * ($x); // 60 * 60 * 24 = 86400 = 1 day in seconds
    $tm = $timestamp - $tm;
    $the_day = date("d", $tm);
    $this_month = date('m', $tm);
    $array30d[$x] = $this_month.$the_day ;
    //$_dia = 'dia_';
    $mykey = array_search(trim($array30d[$x]), array_column($results8, 'date'));
    if($mykey)
    {
        // $awork = array_column( $results8 , 'qtotal');
        // $array30[$x] = $awork[$key];
        // objeto:
        // $array30[$x] = $results9[$key]->qtotal;
        // 
        $awork = $results8[$mykey]['qtotal'];
        $array30[$x] = $awork;
    }
    else
      $array30[$x] = 0;
    $x++;
}
$array30 = array_reverse($array30);
$array30d = array_reverse($array30d);
// print_r($array30);
?>