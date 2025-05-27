<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2021
 */
if (!defined('ABSPATH'))
   exit; // Exit if accessed directly 
//global $antihacker_last_scan;
global $antihacker_notif_scan;
global $antihacker_notif_level;
global $antihacker_notif_visit;
global $antihacker_last_plugin_scan;
global $antihacker_last_theme_scan;
global $antihacker_last_theme_update;
global $wpdb;

$antihacker_table_name = $wpdb->prefix . "ah_scan";

//$query = "select date_end from $antihacker_table_name ORDER BY id DESC limit 1";
//$antihacker_last_scan =  $wpdb->get_var($query);
$antihacker_last_scan = $wpdb->get_var($wpdb->prepare("SELECT date_end FROM %i ORDER BY id DESC LIMIT 1", $antihacker_table_name));

if (gettype($antihacker_last_scan) != 'integer')
  $antihacker_last_scan = 0;


$antihacker_prot_perc = antihacker_find_perc();
if ($antihacker_prot_perc > 0)
   $antihacker_prot_perc = ($antihacker_prot_perc * 10);

if (isset($_GET['notif'])) {
   $notif = sanitize_text_field($_GET['notif']);
   if ($notif == 'scan') {
      update_option('antihacker_notif_scan', time());
      $antihacker_notif_scan = time();
   }
   if ($notif == 'level') {
      update_option('antihacker_notif_level', time());
      $antihacker_notif_level = time();
   }
   if ($notif == 'visit') {
      update_option('antihacker_notif_visit', time());
      $antihacker_notif_visit = time();
   }
   if ($notif == 'plugins') {
      update_option('antihacker_last_plugin_scan', time());
      $antihacker_last_plugin_scan = time();
   }
   if ($notif == 'theme') {
      update_option('antihacker_last_theme_scan', time());
      $antihacker_last_theme_scan = time();
   }
   if ($notif == 'theme_dead') {
      update_option('antihacker_last_theme_update', time());
      $antihacker_last_theme_update = time();
   }
}


$table_name = $wpdb->prefix . "ah_scan";
//$query = "select `date_end`  from $table_name ORDER BY id DESC limit 1";
//$query = $wpdb->prepare("SELECT `date_end` FROM {$table_name} ORDER BY id DESC LIMIT 1");
//$antihacker_last_scan = $wpdb->get_var($wpdb->prepare("SELECT `date_end` FROM %i ORDER BY id DESC LIMIT 1", $wpdb->prefix . "ah_scan"));



if ($antihacker_last_scan !== null) {
   //$last_scan =  strtotime(trim($wpdb->get_var($query)));
   $last_scan_result = $wpdb->get_var($wpdb->prepare("SELECT `date_end` FROM %i ORDER BY id DESC LIMIT 1", $wpdb->prefix . "ah_scan"));
   // $last_scan =  strtotime(trim($last_scan_result));
   $last_scan =  strtotime($last_scan_result);
}
else
   $last_scan = 0;

   //debug2($last_scan);


$timeout_scan = time() > ($antihacker_notif_scan + 60 * 60 * 24 * 7);
if($timeout_scan){
   $timeout_scan = time() > ($last_scan + 60 * 60 * 24 * 7);

}

//debug2($timeout_scan);

$timeout_level = time() > ($antihacker_notif_level + 60 * 60 * 24 * 7);
//$timeout_level = time() > ($antihacker_notif_level + 10);
$timeout_visit = time() > ($antihacker_notif_visit + 60 * 60 * 24 * 5);
//$timeout_visit = time() > ($antihacker_notif_visit + 10);
$timeout_plugin = time() > ($antihacker_last_plugin_scan + 60 * 60 * 24 * 15);
//$timeout_plugin  = time() > ($antihacker_last_plugin_scan + 10);
$timeout_theme = time() > ($antihacker_last_theme_scan + 60 * 60 * 24 * 7);
//$timeout_theme  = time() > ($antihacker_last_theme_scan + 10);
$timeout_theme_dead = time() > ($antihacker_last_theme_update + 60 * 60 * 24 * 15);
//$timeout_theme_dead  = time() > ($antihacker_last_theme_update + 10);


$site = ANTIHACKERHOMEURL . "admin.php?page=anti_hacker_plugin&tab=notifications&notif=";
?>
<div id="antihacker-notifications-page">
   <div class="antihacker-block-title">
   <?php esc_attr_e('Notifications','antihacker'); ?>
   </div>
   <div id="notifications-tab">
      <?php
      $empty_notif = true;
      
      if ($timeout_theme_dead and antihackerCheckThemeDead()) {
         $empty_notif = false;
      ?>
         <b><?php esc_attr_e('Your current theme was not updated last 2 years.','antihacker'); ?></b>
         <br>
         <?php esc_attr_e('Themes not updated for 2 years are suspect to be abandoned. We suggest replace it.','antihacker'); ?>
         <br>
         <a href="<?php echo esc_url($site) ?>theme_dead"><?php esc_attr_e('Dismiss','antihacker'); ?></a>
         <hr>
      <?php
      }
      if ($timeout_scan) {
         $empty_notif = false;
      ?>
         <b><?php esc_attr_e('No scan for malware made lasts 7 days.','antihacker'); ?></b>
         <br>
         <?php esc_attr_e('To scan, go to','antihacker'); ?>
         <br>
         <?php esc_attr_e('Anti Hacker => Scan For Malware','antihacker'); ?>
         <br>
         <a href="<?php echo esc_url($site) ?>scan"><?php esc_attr_e('Dismiss','antihacker'); ?></a>
         <hr>
      <?php }
      if ($timeout_plugin) {
         $empty_notif = false;
      ?>
         <b><?php esc_attr_e('No check plugins for updates made lasts 15 days.','antihacker'); ?></b>
         <br>
         <?php esc_attr_e('Just Click the Check Plugins Tab','antihacker'); ?>
         <br>
         <a href="<?php echo esc_url($site) ?>plugins"><?php esc_attr_e('Dismiss','antihacker'); ?></a>
         <hr>
      <?php }
      if ($timeout_level and $antihacker_prot_perc < 80) {
         $empty_notif = false;
      ?>
         <b><?php esc_attr_e('Improve your protection level.','antihacker'); ?> </b>
         <br>
         <?php esc_attr_e('Protection Status level:','antihacker'); ?>&nbsp;
         <?php echo esc_attr($antihacker_prot_perc); ?>%
         <br>
         <?php esc_attr_e('To increase, go to','antihacker'); ?>
         <br>
         <?php esc_attr_e('Anti Hacker => Setting => General Settings','antihacker'); ?>
         <br>
         <?php esc_attr_e('and mark all with yes.','antihacker'); ?>
         <br>
         <a href="<?php echo esc_url($site) ?>level"><?php esc_attr_e('Dismiss','antihacker'); ?></a>
         <hr>
      <?php }
      if ($timeout_visit) {
         $empty_notif = false;
      ?>
         <b><?php esc_attr_e("More than 5 days you don't check your Dashboard Page.",'antihacker'); ?> </b>
         <br>
         <?php esc_attr_e('Check the status of Google Safe Browsing, Plugins and Themes deactivated and unexpected files on your root folder and more...','antihacker'); ?>
         <br>
         <?php esc_attr_e('Just Click the Dashboard Tab','antihacker'); ?>
         <br>
         <a href="<?php echo esc_url($site) ?>visit"><?php esc_attr_e('Dismiss','antihacker'); ?></a>
      <?php } 

      if($empty_notif)
         echo '<b>'. esc_attr_e('No notifications at this time!','antihacker').'</b>';
        
      ?>
   </div>
</div>