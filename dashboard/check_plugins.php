<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2021
 */
if (!defined('ABSPATH'))
   exit; // Exit if accessed directly 
global $antihacker_last_plugin_scan;

// require_once(ANTIHACKERPATH . "includes/functions/plugin-check-list.php");

//debug2();

// ssh://server/home/minozzi/public_html/wp-content/plugins/antihacker/includes/functions/plugin-check-list.php




//         action: '$antihacker_check_plugins_and_display_results',
// add_action('wp_ajax_$antihacker_check_plugins_and_display_results', '$antihacker_check_plugins_and_display_results');

if( isset( $_GET[ 'notif' ] ) ){ 
   $notif = sanitize_text_field($_GET[ 'notif' ]);
   if($notif == 'plugins')
      update_option('antihacker_last_plugin_scan', time());
}
if( isset( $_GET[ 'action' ] ) ){ 
    $action = sanitize_text_field($_GET[ 'action' ]);
    if($action == 'scan'){
       update_option('antihacker_last_plugin_scan', time());
      flush();
      //debug2();
       antihacker_scan_plugins();
       return;
    }
 }

 //debug2();

$timeout = time() > ($antihacker_last_plugin_scan + 60 * 60 * 24 * 3);
$timeout = time() > ($antihacker_last_plugin_scan + 10);
$site = ANTIHACKERHOMEURL . "admin.php?page=anti_hacker_plugin&tab=plugins&notif=";


?>


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
   <div id="result-container" style="display: none; padding:20px;">
      <!-- Conteúdo do resultado aqui -->
        <center>
Please, wait... 
<br>
<br>
         <img id="antihacker_spinner" alt="antihacker_spinner" src="<?php echo esc_attr(ANTIHACKERIMAGES);?>/spinner.gif" width="50px" style="opacity:.5"; />
        
      </center>
   </div>
</div>