<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-20 14:34:01
 * DASHBOARD SCAN
 */
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
if (isset($_GET['page'])) {
  $page = trim(sanitize_text_field($_GET['page']));
  if ($page == 'antihacker_scan') {
    add_filter('admin_head', 'antihacker_contextual_help_scan', 10, 3);
  }
}
function antihacker_enqueue_scan_scripts()
{
  wp_enqueue_style('bill-help-dashboard-scan', ANTIHACKERURL . 'scan/scan.css');
}
add_action('admin_init', 'antihacker_enqueue_scan_scripts');
function antihacker_scan_dashboard()
{
  if (isset($_GET['page'])) {
    $page = sanitize_text_field($_GET['page']);
    if ($page != 'antihacker_scan')
      return;
  }
?>
  <div id="antihacker-theme-help-wrapper">
    <div id="antihacker-not-activated"></div>
    <div id="antihacker_header">
      <div id="antihacker-logo">
        <img alt="logo" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/logo.png" width="250px" />
      </div>
      <div id="antihacker-nocloud">
        <img alt="No Cloud" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/no_cloud.png" width="200px" />
      </div>
      <div id="antihacker_help_title">
        <?php esc_attr_e("Scan Dashboard","antihacker"); ?>
      </div>
      <div id="antihacker-social">
        <a href="http://antihackerplugin.com/share/"><img alt="social bar" src="<?php echo esc_attr(ANTIHACKERIMAGES); ?>/social-bar.png" width="250px" /></a>
      </div>
    </div>
    <?php
    if (isset($_GET['tab']))
      $active_tab = sanitize_text_field($_GET['tab']);
    else
      $active_tab = 'scan';
    ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=antihacker_scan&tab=memory&tab=scan" class="nav-tab">New Scan</a>
      <a href="?page=antihacker_scan&tab=results" class="nav-tab">Scan Results</a>
      <a href="?page=antihacker_scan&tab=log" class="nav-tab">Scan Log</a>
      <a href="?page=antihacker_scan&tab=debug" class="nav-tab">Scan Debug</a>
    </h2>
  <?php
  echo '<div class="antihacker-scan-container">';
  if ($active_tab == 'results') {
    require_once(ANTIHACKERPATH . 'scan/scan_result.php');
  } elseif ($active_tab == 'log') {
    require_once(ANTIHACKERPATH . 'scan/scan_log.php');
  } elseif ($active_tab == 'debug') {
    require_once(ANTIHACKERPATH . 'scan/scan_debug.php');
  } else {
    require_once(ANTIHACKERPATH . 'scan/scan_tab.php');
  }
  echo '</div> <!-- <div class="antihacker-scan-container"> -->';
  echo '</div> <!-- "antihacker-theme_help-wrapper"> -->';
}   ?>