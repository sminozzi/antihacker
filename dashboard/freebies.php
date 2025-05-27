<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2021
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly 
?>
<div id="antihacker-notifications-page">
    <div class="antihacker-block-title">
        More Tools
    </div>
    <div id="notifications-tab">
        <div id="freebies-tab">


        <?php

        if(is_multisite())
           $url = esc_url(ANTIHACKERHOMEURL)  . "plugin-install.php?s=sminozzi&tab=search&type=author";
        else
           $url = esc_url(ANTIHACKERHOMEURL).'/admin.php?page=antihacker_new_more_plugins';



        echo '<script>';
        echo 'window.location.replace("'.esc_url($url).'");';
        // $msg .= 'window.location.replace("'.esc_url(STOPBADBOTSHOMEURL).'plugin-install.php?s=sminozzi&tab=search&type=author");';
        echo '</script>';


       ?>


            <br />
        </div>
    </div>
</div>