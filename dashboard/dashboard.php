<?php
/**
 * @author William Sergio Minozzi
 * @copyright 2017
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
update_option('antihacker_notif_visit', time());
?>
<div id="antihacker-steps3">
    <div class="antihacker-block-title">
        <?php esc_attr_e("Anti Hacker Plugin Activated","antihacker"); ?>
    </div>
    <div class="antihacker-help-container1">
        <div class="antihacker-help-column antihacker-help-column-1">
            <h3><?php esc_attr_e("Memory Usage","antihacker"); ?></h3>
            <?php
            $ds = 256;
            $du = 60;
            $antihacker_memory = antihacker_check_memory();
            if ($antihacker_memory['msg_type'] == 'notok') {
               esc_attr_e('Unable to get your Memory Info','antihacker');
            } else {
                $ds = $antihacker_memory['wp_limit'];
                $du = $antihacker_memory['usage'];
                if ($ds > 0)
                    $perc = number_format(100 * $du / $ds, 0);
                else
                    $perc = 0;
                if ($perc > 100)
                    $perc = 100;
                //die($perc);
                $color = '#e87d7d';
                $color = '#029E26';
                if ($perc > 50)
                    $color = '#e8cf7d';
                if ($perc > 70)
                    $color = '#ace97c';
                if ($perc > 50)
                    $color = '#F7D301';
                if ($perc > 70)
                    $color = '#ff0000';
                $initValue = $perc;
                require_once "circle_memory.php";
                esc_attr_e("For details, click the Memory Check Up Tab above.","antihacker");
            ?>
            <br /> <br />
            <?php } ?>
        </div>
        <!-- "Column1">  -->
        <div class="antihacker-help-column antihacker-help-column-2">
            <?php
            $perc = antihacker_find_perc();
            // die($perc);
            if ($perc < 9) {
                $color = '#ff0000';
                echo '<h3 style="margin-top: 20px; margin-left: 30px; color:' . esc_attr($color) . '; font-weight: bold;" >';
            } else {
                echo '<h3>';
            }
            
            esc_attr_e("Protection Status","antihacker");
            echo '</h3>';
            
            $initValue = $perc * 10;
            require_once "circle_status.php";
            ?>
            <?php
            if ($perc == 10)
                echo '<center>'. esc_attr__('Protection Enabled','antihacker').'</center>';
            else
                echo '<center>'. esc_attr__('Go to Anti Hacker Settings Page (General Settings Tab) and mark all with Yes and run Scan For Malware','antihacker').'</center>';
            ?>
            <br /> <br />
        </div> <!-- "columns 2">  -->
        <div class="antihacker-help-column antihacker-help-column-2">
            <?php
            if (!empty($antihacker_checkversion)) {
                echo '<img src="' . esc_url(ANTIHACKERURL) . '/images/lock-xxl.png" style="text-align:center; width: 40px;margin: 10px 0 auto;"  />';
            ?>
            <h3><?php esc_attr_e("Premium Protection Enabled","antihacker"); ?></h3>
            
            <?php $site = 'http://antihackerplugin.com'; ?>
            <a href="<?php echo esc_url($site); ?>" class="button button-primary">
            <?php esc_attr_e("Learn More","antihacker"); ?></a>
            <?php } else {
                echo '<center>';
                echo '<img src="' . esc_url(ANTIHACKERURL) . '/images/unlock-icon-red-small.png" style="text-align:center; max-width: 40px;margin: 20px 0 auto;"  />';
                echo '</center>';
            ?>
            <h3 style="color:red; margin-top:10px;"><?php esc_attr_e("Only Partial Protection enabled!","antihacker"); ?>

            </h3>
            <?php esc_attr_e("No matter if you are small or big. Hackers want to use your server to send spam, steal traffic and attack new computers.","antihacker"); 
            echo '<br>';
            $site = 'http://antihackerplugin.com/premium/'; ?>
            <a href="<?php echo esc_url($site); ?>" class="button button-primary"><?php esc_attr_e("Learn More","antihacker"); ?></a>
            <?php
            }
            ?>
            <br />
        </div>
        <!-- "Column 3">  -->
    </div> <!-- "Container 1 " -->
</div> <!-- "antihacker-steps3"> -->





<div id="antihacker-services3">
    <!--
     <div class="antihacker-block-title">
     <?php esc_attr_e("Help, Demo, Support, Troubleshooting:","antihacker"); ?>
    </div>
    -->


    <div class="antihacker-help-container1">
        <div class="antihacker-help-column antihacker-help-column-1">
            <img alt="aux" src="<?php echo esc_attr(ANTIHACKERURL) ?>images/service_configuration.png" />
            <div class="bill-dashboard-titles"><?php esc_attr_e("Start Up Guide and Settings","antihacker"); ?>
            </div>
            <br />
            <?php esc_attr_e("Just click Settings in the left menu (Anti Hacker).","antihacker");?>
            <br />
            <?php esc_attr_e("Dashboard => Anti Hacker => Settings","antihacker");?>
            <br />
            <?php $site = esc_attr(ANTIHACKERADMURL) . "admin.php?page=anti-hacker"; ?>
            <a href="<?php echo esc_url($site); ?>" class="button button-primary"><?php esc_attr_e("Go","antihacker");?></a>
            <br /><br />
        </div> <!-- "Column1">  -->
        <div class="antihacker-help-column antihacker-help-column-2">
            <img alt="aux" src="<?php echo esc_url(ANTIHACKERURL) ?>images/support.png" />
            <div class="bill-dashboard-titles"><?php esc_attr_e("OnLine Guide, Support, Faq...","antihacker");?></div>
            <br /><br />
            <?php esc_attr_e("You will find our complete and updated OnLine guide, faqs page, link to support and more in our site.","antihacker");?>
            <br />
            <?php $site = 'http://antihackerplugin.com'; ?>
            <a href="<?php echo esc_url($site); ?>" class="button button-primary"><?php esc_attr_e("Go","antihacker");?></a>
        </div> <!-- "columns 2">  -->
        <div class="antihacker-help-column antihacker-help-column-3">
            <img alt="aux" src="<?php echo esc_attr(ANTIHACKERURL) ?>images/system_health.png" />
            <div class="bill-dashboard-titles"><?php esc_attr_e("Troubleshooting Guide","antihacker");?></div>
            <br /><br />
            <?php esc_attr_e("Use old WP version, Low memory, some plugin with Javascript error are some possible problems.","antihacker");?>
            <br />
            <a href="http://siterightaway.net/troubleshooting/" class="button button-primary"><?php esc_attr_e("Troubleshooting Page","antihacker");?></a>
        </div> <!-- "Column 3">  -->
    </div> <!-- "Container1 ">  -->
</div> <!-- "services"> -->


<div id="antihacker-services3">
<div class="antihacker-help-container1">
        <div class="antihacker-help-column antihacker-help-column-1">
            <img alt="aux" src="<?php echo esc_attr(ANTIHACKERURL) ?>images/system_health.png" widt/>
            <div class="bill-dashboard-titles"><?php echo esc_attr__('Google Safe Browsing','antihacker'); ?></div>
            <br />
            <center>
            <?php require_once "google_safe.php"; ?>
            <br />
            <?php $antihacker_site = ANTIHACKERADMURL . "admin.php?page=anti-hacker"; ?>
            <a href="http://antihackerplugin.com/google-safe-browsing/" class="button button-primary"><?php esc_attr_e("Learn More",'antihacker'); ?></a>
            <br /><br />
            </center>
        </div> <!-- "Column1">  -->
        <div class="antihacker-help-column antihacker-help-column-2">
            <img alt="aux" src="<?php echo esc_attr(ANTIHACKERURL) ?>images/plugin.png" />
            <div class="bill-dashboard-titles"><?php echo esc_attr__('Plugins and Themes Deactivated','antihacker'); ?></div
            <br />

            <?php require_once "themes_and_plugins.php"; ?>
            
            
            <br />
            <a href="http://antihackerplugin.com/plugins-and-themes-deactivated/" class="button button-primary"><?php esc_attr_e("Learn More",'antihacker'); ?></a>
        </div> <!-- "columns 2">  -->
        <div class="antihacker-help-column antihacker-help-column-3">
            <img alt="aux" src="<?php echo esc_attr(ANTIHACKERURL) ?>images/files.png" />
            <div class="bill-dashboard-titles"><?php echo esc_attr__('Root Folder Extra Files','antihacker'); ?></div>
            <br />

            <?php require_once "root_folder.php"; ?>   


            <br />
            <br />


            <a href="http://antihackerplugin.com/files-and-folders-on-your-root-folder/" class="button button-primary"><?php echo esc_attr__('Learn More','antihacker'); ?></a>
        </div> <!-- "Column 3">  -->
    </div> <!-- "Container1 ">  -->
</div> <!-- "services"> -->




<div id="antihacker-services3">
    <div class="antihacker-help-container1" style="margin-bottom: 20px;">
        <div class="antihacker-help-2column antihacker-help-column-1">
            <h3><?php echo esc_attr__('Total Attacks Blocked Last 15 days','antihacker'); ?></h3>
            <br /> <br />
            <?php require_once "attacksgraph.php"; ?>
        </div>
        <div style="min-width:45%; height:360px;" class="antihacker-help-2column antihacker-help-column-2">
            <br /> <br />
            <h3><?php echo esc_attr__('Blocked Attacks by Type','antihacker'); ?></h3>
            <?php require_once "attacksgraph_pie.php"; ?>
        </div>
        <div class="antihacker-help-2column antihacker-help-column-2">
            <h3><?php echo esc_attr__('Bots / Human Visits','antihacker'); ?></h3>
            <br /> <br />
            <?php require_once "botsgraph_pie2.php"; ?>
            <br /><br />
        </div> <!-- "Column 3">  -->
    </div>
</div>