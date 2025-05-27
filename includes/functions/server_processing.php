<?php /**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2024-06-29
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/ 

global $wpdb;
global $table_prefix;
$table = $table_prefix . "ah_visitorslog";
// Table's primary key
$primaryKey = 'date';
$columns = array(
    array(
        'db'        => 'date',
        'dt'        => 1,
        'formatter' => function ($d, $row) {
            return date('d-M-Y H:i:s', strtotime($d));
        }
    ),
    array('db' => 'access', 'dt' => 2),
    array('db' => 'ip', 'dt' => 3),
   // array('db' => 'human',  'dt' => 4),
    array('db' => 'reason',     'dt' => 4),
    array('db' => 'response',   'dt' => 5),
    array('db' => 'method',     'dt' => 6),
    array('db' => 'ua',     'dt' => 7),
    array('db' => 'url',     'dt' => 8),
    array('db' => 'referer',     'dt' => 9)

);
require('_ssp.class.php');


echo json_encode(
    ANTIHACKER_SSP::simple($_GET, $table, $primaryKey, $columns)
);
return;


$sanitized_get = array_map('sanitize_text_field', $_GET);
$sanitized_table = sanitize_text_field($table);
$sanitized_primary_key = sanitize_text_field($primaryKey);
//$sanitized_columns = array_map('sanitize_text_field', $columns);
$sanitized_columns = array_map('esc_sql', $columns);

/*
echo wp_json_encode(
    ANTIHACKER_SSP::simple($sanitized_get, $sanitized_table, $sanitized_primary_key, $sanitized_columns),
    JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
);
*/

/*
echo wp_json_encode(
    ANTIHACKER_SSP::simple($sanitized_get, $sanitized_table, $sanitized_primary_key, $sanitized_columns),
);
*/

echo wp_json_encode(
    ANTIHACKER_SSP::simple($sanitized_get, $sanitized_table, $sanitized_primary_key, $columns),
);

