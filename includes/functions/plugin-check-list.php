<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
// >>>>>>>>>>>>   set_time_limit(30);


// In your WordPress plugin file or functions.php
// PHP Function:

// action: '$antihacker_check_plugins_and_display_results',

function antihacker_check_plugins_and_display_results() {
     set_time_limit(120);

     // wp_die('1');
     // wp_die(wp_send_json_success('1'));


    


    // Perform plugin check
    // $plugin_check_result = check_plugins();

    $antihacker_plugins = antihacker_scan_plugins();


    if ($antihacker_plugins === false || !is_array($antihacker_plugins)) {
        wp_send_json_error(esc_html__('Failed to retrieve plugins.', 'antihacker'));
        return; // Certifica-se de que a execução é interrompida
    }


    
    //$plugin_check_result = true;
    // debug2($antihacker_plugins);

    // Acumula a saída em uma variável
    $output = '<div id="plugin-check-results">';

   
    $output .= '<h2>Plugin Check Results</h2>';
    // $output .= '<p>' . esc_html__('Last check for updates made (Y-M-D):', 'antihacker');
    // $output .=  ' ' . date('Y-m-d', esc_attr($antihacker_last_plugin_scan)) . '</p>';
    
    $output .= '<big>';

    // Output the plugin check result
    $output .= '<p>'.esc_html__('The left column displays the last update date.', 'antihacker') . '</p>';
    
    if (count($antihacker_plugins) < 1) {
        $output .= '<p>' . esc_html__('No Plugins Found!', 'antihacker') . '</p>';
        $output .= '</big>';
    } 
    else {
        //  $output .= '<p>' . esc_html__('Some plugins may need attention. Please check the list below:', 'antihacker') . '</p>';
        $output .= '</big>';
        foreach ($antihacker_plugins as $plugin_info) {

            if(empty($plugin_info))
              continue;

            if(strlen($plugin_info) < 3)
              continue;

            $output .= $plugin_info . '<br>';
            $output .= '<hr>';
        }
    }
   
    $output .= '</div>';

    

    // Encerra o script e retorna a saída
    // wp_die($output);

    //$output = 'ok';

    update_option('antihacker_last_plugin_scan', time());



    //wp_die(esc_attr(wp_send_json_success(esc_html($output))));


    $allowed_tags = array(
        'div' => array(
            'id' => array(),
        ),
        'h2' => array(),
        'big' => array(),
        'p' => array(),
        'br' => array(),
        'hr' => array()
    );

   // debug2($output);
    
  //  wp_die(wp_kses($output, $allowed_tags));

    $sanitized_output = wp_kses($output, $allowed_tags);

    // Encerra o script e retorna a saída como JSON
    wp_send_json_success($sanitized_output);
    
}

/*
function antihacker_scan_plugins()
{
    $result = array(); // Array para armazenar as strings

    //return $result;

    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $all_plugins_work = get_plugins();
    $all_plugins = array_keys($all_plugins_work);
    $q = count($all_plugins);

    for ($i = 0; $i < $q; $i++) {
        $pos = strpos($all_plugins[$i], '/');
        $myplugin = trim(substr($all_plugins[$i], 0, $pos));

        if (empty($myplugin) || strlen($myplugin) < 3) {
            continue;
        }

        $pluginData = antihacker_CheckPluginUpdate($myplugin);
        if (!isset($pluginData['last_updated'])) {
            $last_update = 'Not Found => ';
        } else {
            $last_update = substr($pluginData['last_updated'], 0, 10);
        }

        $timeout = strtotime($last_update) + (60 * 60 * 24 * 365);
        $plugin_info = esc_attr($last_update) . ' - ' . esc_attr($myplugin);

        // Adiciona asteriscos se a data de atualização for superior a um ano
        if ($timeout < time()) {
            $plugin_info = '***' . $plugin_info . '***';
        }

        $result[] = $plugin_info; // Adiciona a string ao array
    }

    return $result; // Retorna o array de strings
}







	function antihacker_CheckPluginUpdate($plugin) {
		$response = wp_remote_get('https://api.wordpress.org/plugins/info/1.0/'.esc_attr($plugin).'.json' );
		$body     = wp_remote_retrieve_body( $response );
		if ( ! $body ) {
			// Something went wrong.
			return "";
		}
		return json_decode( $body, true );
}
*/