<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-19 10:20:31
 */
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly

// add_thickbox();
?>

<div id="antihacker-scan-id" style="display:none;">
    <span class="spinner"></span>
    <div id="antihacker_steps" ">
          <img id="antihacker_3steps" src=" <?php echo esc_url(ANTIHACKERURL); ?>/images/steps.png" style="width:300px;" />
    </div>
    <div id="antihacker_wrap_trickbox" style="padding-top:10px; padding-bottom: 20px;">
    <?php esc_attr_e('Keep This Window Open or Click Cancel Scan Button to Abort or click Pause Button.You can leave this window working
      and open a new tab in your browser and visit other pages or sites.
      Please be patient, this should take time depending on the size of your website and server speed.
      This window close automatically when the job is completed.',"antihacker"); ?>
    </div>
    <div id="antihacker_scan_msg" style="padding-top:20px; padding-bottom: 20px;"><?php esc_attr_e('Please, wait...',"antihacker"); ?></div>
    <a href="#" id="antihacker-scan-cancel" class="button button-primary"><?php esc_attr_e('Cancel Scan',"antihacker"); ?></a>
    <a href="#" id="antihacker-scan-button_ok" class="button button-primary"><?php esc_attr_e('OK',"antihacker"); ?></a>
    <a href="#" id="antihacker-pause-button" class="button button-primary" style="margin-left: 20px;"><?php esc_attr_e('Pause',"antihacker"); ?></a>
    <a href="#" id="antihacker-resume-button" class="button button-primary"><?php esc_attr_e('Resume',"antihacker"); ?></a>
</div>

<div id="antihacker-scan-bkg" style="display:block;">
<h3> 
<?php esc_attr_e('Scan Your Site Against Malware',"antihacker"); ?></h3>
<?php esc_attr_e('This options can run a manual scan in your site against 797 types of malware.',"antihacker"); ?> <br />
<?php esc_attr_e('The duration can be a few minutes to many hours, depends of the quantity of files,
your server speed and speed scan selected below.',"antihacker"); ?><br />
<?php esc_attr_e('To begin, click the button Run Scan Now. It will reset and clear the last scan info recorded (if any).',"antihacker");?><br />
<?php esc_attr_e('After begin, leave the window open untill finish.You can open a new tab in your browser and visit other pages if you
want.',"antihacker"); ?><br />
<?php esc_attr_e('After end, look the result on Scan Results tab, the scan window will close automatically.',"antihacker"); ?><br />
<?php esc_attr_e('Click Help Button to top right corner if necessary.',"antihacker"); ?>
<?php esc_attr_e('Click Cancel Scan Button to abort or Pause button if necessary.',"antihacker"); ?><br />
<?php esc_attr_e('Visit the plugin site for a',"antihacker"); ?> 
<a href="http://antihackerplugin.com/malware-removal-guide/" target="_blank">
<?php esc_attr_e('Malware Removal Guide',"antihacker"); ?></a>.

<p>
<?php esc_attr_e('Select a speed to run the scan.
    For entry-level hosting plans, we suggest choose Very Slow.
    Mark Fast or Very Fast if you have a fast server and enough free memory, otherwise your server can overload:',"antihacker"); ?></p>
<form id="antihackere_scan_form">
<input type="hidden" id="images_path" name="images_path" value="<?php echo esc_url(ANTIHACKERURL).'images/';?>">

<?php wp_nonce_field('antihacker_scan_nonce', 'antihacker_scan_nonce'); ?>

<div>
    <input type="radio" class="speed" id="very_slow" name="speed" value="very_slow">
    <label for="slow"><?php esc_attr_e('Very Slow',"antihacker"); ?></label>
</div>

<div>
    <input type="radio" class="speed" id="slow" name="speed" value="slow">
    <label for="slow"><?php esc_attr_e('Slow',"antihacker"); ?></label>
</div>
<div>
    <input type="radio" class="speed" id="normal" name="speed"" value=" normal" checked>
    <label for="normal"><?php esc_attr_e('Normal',"antihacker"); ?></label>
</div>
<div>
    <input type="radio" class="speed" id="fast" name="speed"" value="fast">
    <label for="fast"><?php esc_attr_e('Fast',"antihacker"); ?></label>
</div>
<div>
    <input type="radio" class="speed" id="very_fast" name="speed"" value="very_fast">
    <label for="fast"><?php esc_attr_e('Very Fast',"antihacker"); ?></label>
</div>
<br />
<a href="#" id="antihacker-scan-ok"
    class="antihacker_scan button button-primary"><?php esc_attr_e('Run Scan
    Now',"antihacker"); ?></a>

</form>
</div>