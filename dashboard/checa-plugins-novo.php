<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

// >>>>>>>>>>>>   set_time_limit(30);


// In your WordPress plugin file or functions.php
// PHP Function:
function antihacker_check_plugins_and_display_results() {
    set_time_limit(30);

    // Perform plugin check
    $plugin_check_result = check_plugins();

    // Acumula a saída em uma variável
    $output = '<div id="plugin-check-results">';
    $output .= '<h2>Plugin Check Results</h2>';
    $output .= '<p>' . esc_html__('Last check for updates made (Y-M-D):', 'antihacker') . ' ' . date('Y-m-d', esc_attr($antihacker_last_plugin_scan)) . '</p>';
    
    // Output the plugin check result
    if ($plugin_check_result) {
        $output .= '<p>' . esc_html__('All plugins are up to date.', 'antihacker') . '</p>';
    } else {
        $output .= '<p>' . esc_html__('Some plugins may need attention. Please check the list below:', 'antihacker') . '</p>';
        // Add logic to output the specific plugins that need attention
        // You can use $plugin_check_result to get the details
    }

    $output .= '</div>';

    // Encerra o script e retorna a saída
    wp_die(esc_html($output));
}


//////////
// JavaScript for Button Click and Loading Spinner:

// JavaScript function to handle the button click and display results
// plugins-check-auto
jQuery(document).ready(function ($) {
    $('#check-plugins-button').on('click', function () {
        var $button = $(this);
        $button.prop('disabled', true);
        $button.after('<span class="spinner"></span>');

        // AJAX request to perform plugin check
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'antihacker_check_plugins_and_display_results',
            },
            success: function (response) {
                // Display the result on the screen
                $('#plugin-check-results').replaceWith(response);
            },
            error: function (error) {
                console.error('AJAX error:', error);
            },
            complete: function () {
                // Remove the loading spinner and enable the button
                $button.prop('disabled', false);
                $button.siblings('.spinner').remove();
            }
        });
    });
});



/////////////

// Modified HTML Form:

<div id="antihacker-notifications-page">
   <div class="antihacker-block-title">
      <?php esc_attr_e('Check Plugins','antihacker'); ?>
   </div>
   <div id="notifications-tab">
    <b>
    <?php esc_attr_e('Check Plugins for updates.','antihacker');?>
    </b>
    <br>
    <?php esc_attr_e('This test will check all your plugins against WordPress repository to see 
    if they are updated last one year. Plugins not updated last one year
    are suspect to be abandoned and we suggest replacing them.','antihacker');?>
    <br>
    <br>
    <?php
    $timeout_plugin = time() > ($antihacker_last_plugin_scan + 60 * 60 * 24 * 365);

    if(!$timeout_plugin){
      echo esc_attr__('Last check for updates made (Y-M-D):', 'antihacker').' ';
      echo esc_attr(date ('Y-m-d', $antihacker_last_plugin_scan));
    }
    ?>
    <br>
    <br>
    <button id="check-plugins-button" class="button button-primary"><?php esc_attr_e('Check Plugins Now','antihacker');?></button>
   </div>
</div>

//

// Enfileirar o script JavaScript
function antihacker_enqueue_plugin_check_script() {
    wp_enqueue_script('plugin-check-script', get_template_directory_uri() . '/path/to/your/script.js', array('jquery'), '1.0', true);

    // Adicione a variável ajaxurl ao script
    wp_localize_script('plugin-check-script', 'pluginCheckAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'antihacker_enqueue_plugin_check_script');

// Criar o hook para lidar com a solicitação AJAX
function antihacker_check_plugins_and_display_results() {
    // Sua lógica PHP para verificar os plugins vai aqui
    // Certifique-se de chamar a função check_plugins() ou implementar a lógica apropriada
    // ...

    // Sua lógica para imprimir os resultados vai aqui
    // ...

    wp_die(); // Termina a execução e evita a saída HTML desnecessária
}

add_action('wp_ajax_check_plugins_and_display_results', 'antihacker_check_plugins_and_display_results');
add_action('wp_ajax_nopriv_check_plugins_and_display_results', 'antihacker_check_plugins_and_display_results');

