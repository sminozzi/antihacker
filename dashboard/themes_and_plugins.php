<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

function antihacker_report_inactive()
{
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

   $all_plugins_work = get_plugins();

   $all_plugins = array_keys($all_plugins_work);

    $q = count($all_plugins);

    echo esc_attr__('Plugins', 'antihacker');
    echo '<br>';


    
    $count_plugin = 0;
    for($i=0; $i< $q; $i++)
    {


        if ( is_plugin_inactive($all_plugins[$i]) ) 
        {
            $count_plugin++;

            if($count_plugin > 5)
               continue;

            $pos = strpos($all_plugins[$i], '/');
            echo esc_attr(substr($all_plugins[$i], 0, $pos));
            echo '<br>';

            //plugin is not activated

        }

    }
    
    
    if($count_plugin == 0){
     echo '<br>';
     echo esc_attr__('No inactive Plugins! All right.', 'antihacker').'...';

     echo '<br>';
    }
    if($count_plugin > 5){
      echo esc_attr__('More', 'antihacker').'...';
      echo '<br>';
     }
     


  $my_theme = wp_get_theme();
  $current = esc_html( $my_theme->get( 'TextDomain' ) );

  

  echo '<hr>';
  echo esc_attr__('Themes', 'antihacker');
  echo '<br>';


  $all_themes_work = wp_get_themes();
  $all_themes = array_keys($all_themes_work);

  $q = count($all_themes);
  $count_themes = 0;

  

  for($i = 0; $i < $q; $i++){
  
      if($current != $all_themes[$i]) {

        $count_themes++;
        if($count_themes > 5)
          continue;
         
        echo  esc_attr($all_themes[$i]);
        echo '<br>';




      }
         

  }

  if($count_themes == 0){
    echo '<br>';
    echo esc_attr__('No inactive Themes! All right.', 'antihacker');
    echo '<br>';
   }

   if($count_themes > 5){
      echo esc_attr__('More', 'antihacker').'...';
      echo '<br>';
   }
}
antihacker_report_inactive();