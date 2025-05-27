<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 

  
$antihacker_memory = antihacker_check_memory();
echo '<div id="antihacker-memory-page">';
echo '<div class="antihacker-block-title">';
    if ( $antihacker_memory['msg_type'] == 'notok')
       {
        echo esc_attr__('Unable to get your Memory Info','antihacker');
        echo '</div>';
    }
    else
    {
echo esc_attr__('Memory Info','antihacker');
echo '</div>';
echo '<div id="memory-tab">';
echo '<br />';

if($antihacker_memory['msg_type']  == 'ok')
 $mb = 'MB';
else
 $mb = '';
echo '<hr>'; 






echo esc_attr__('Current WordPress Memory Limit:','antihacker').' ' . esc_attr($antihacker_memory['wp_limit']) . esc_attr($mb) .
    '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';

$perc = $antihacker_memory['usage'] / $antihacker_memory['wp_limit'];
if ($perc > .7)
   echo '<span style="'.esc_attr($antihacker_memory['color']).';">';
echo esc_attr__('Your usage now:','antihacker').' '. esc_attr($antihacker_memory['usage']) .
    'MB &nbsp;&nbsp;&nbsp;';
if ($perc > .7)
   echo '</span>';    
echo '|&nbsp;&nbsp;&nbsp;'. esc_attr__('Total (Server) PHP Memory Limit:','antihacker').' ' . esc_attr($antihacker_memory['limit']) .
    'MB';
   echo '<hr>';    
  // echo '<br />'; 
  
?>  
   </strong>
<!-- <div id="memory-tab"> -->

<?php

$free =  $antihacker_memory['wp_limit'] - $antihacker_memory['usage'] ;

///////////////////////////
// Fix it...

if ( $perc > .7  or $free < 30  ) {
    echo '<h2 style="color: red;">';
    echo esc_attr__('Our plugin cannot function properly because your WordPress memory limit is too low. Your site will experience serious issues, even if you deactivate our plugin.','antihacker');
    // echo $free;
    echo '</h2>';
    }
    else{
      echo '<br />';
      echo '<br />';
    }

    $wplimit = $antihacker_memory['wp_limit'];

        echo esc_attr__('If you want adjust and control your WordPress Memory Limit and PHP Memory Limit quickly and without edit any files, try our free plugin WPmemory:','antihacker');
        echo '<br />';
        echo '<a href="https://wordpress.org/plugins/wp-memory/">'.esc_attr__('Learn More','antihacker').'</a>';
    
        echo '<br />';
        echo '<br />';
        echo '<hr>';
        echo esc_attr__('Follow this instructions to do it manually:','antihacker');
        echo '<br />';

    echo '<br />';
    esc_attr_e('To increase the WordPress memory limit, add this info to your file wp-config.php (located at root folder of your server)','antihacker');
    echo '<br />';
    esc_attr_e('(just copy and paste)','antihacker');
    echo '<br />';
    echo '<br />';
    echo '<strong>';    
esc_attr_e("define('WP_MEMORY_LIMIT', '128M');",'antihacker');
echo '</strong>'; 
echo '<br />';
echo '<br />';
    esc_attr_e('before this row:','antihacker');
    echo '<br />';
    esc_attr_e("/* That's all, stop editing! Happy blogging. */",'antihacker');
    echo '<br />';
    echo '<br />';
    esc_attr_e('If you need more, just replace 128 with the new memory limit.','antihacker');
    echo '<br />';
    echo '<br />';
    esc_attr_e('To increase your total PHP Server Memory, talk with your hosting company about how make changes on your php.ini file or use our plugin WPmemory (above).','antihacker');
    echo '<br />';
    echo '<br />';
    echo '<hr />';
    echo '<br />';    
echo '<strong>';    
esc_attr_e('How to Tell if Your Site Needs a Shot of more Memory:','antihacker');
echo '</strong>'; 
echo '<br />';
echo '<br />';
        esc_attr_e("If your site is behaving slowly, or pages fail to load, you 
    get random white screens of death or 500 
    internal server error you may need more memory. 
Several things consume memory, such as WordPress itself, the plugins installed, the 
theme you're using and the site content.",'antihacker');
echo '<br />'; 
     esc_attr_e("Basically, the more content and features you add to your site, 
the bigger your memory limit has to be.
if you're only running a small 
site with basic functions without a Page Builder and Theme 
Options (for example the native Twenty Twenty). However, once 
you use a Premium WordPress theme and you start encountering 
unexpected issues, it may be time to adjust your memory limit 
to meet the standards for a modern WordPress installation.",'antihacker');
echo '<br />';
echo '<br />';   
     esc_attr_e('Increase the WP Memory Limit is a standard practice in 
WordPress and you find instructions also in the official 
WordPress documentation (Increasing memory allocated to PHP).','antihacker');

echo '<br />';
echo '<br />';
    esc_attr_e('Here is the link:','antihacker');    
    echo '<br />'; 
    ?>
<a href="https://codex.wordpress.org/Editing_wp-config.php" target="_blank">https://codex.wordpress.org/Editing_wp-config.php</a>
<br /><br />
</div>
</div>
<?php } ?>