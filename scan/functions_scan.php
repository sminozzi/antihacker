<?php
/**
 * @ Author: Bill Minozzi
 * @ Modified time: 2021-03-22 07:53:28
 * @ Modified time: 2021-03-20 18:10:45
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
function antihacker_contextual_help_scan()
{
  $myhelp = '<br>';
  $myhelp .= __('This option will run a manual scan on your site to look for malwares.', "antihacker");
  $myhelp .= '<br>';
  $myhelp .= __('When you click Run Scan Now, the plugin reset and erase the last Scan.', "antihacker");
  $myhelp .= '<br>';
  $myhelp .= __('If you click Cancel button in the middle of the scan, the plugin also reset all information.', "antihacker");
  $myhelp .= '<br>';
  $myhelp .= __('Visit the Anti Hacker ', "antihacker");
  $myhelp .= ' <a href="http://antihackerplugin.com/" target="_blank">';
  $myhelp .= __('plugin site', "antihacker");
  $myhelp .= ' </a>';
  $myhelp .= __(' to learn more about malwares and look the malwares table.', "antihacker");
  $myhelp .= '<br>';
  $myhelp .= __('The plugin will check files up to 2 Gb.', 'antihacker');
  $myhelp .= ' ';
  $myhelp .= __('Files bigger than 2 Gb are suspicious and we suggest you inspect them by hand.', 'antihacker');
  $myhelp .= '<br>';
  $myhelp .= __("The plugin  doesn't fix or modify your files, only alert for possible malware.", 'antihacker');
  $myhelp .= '<br>';
  $myhelp .= __("if the plugin find possible malware, it will write at Scan Results (tab) the file name (and path) and the name of the malware.", 'antihacker');
  // $myhelp .= '<br>';
  $myhelp .= __('Visit the plugin site for tips how to remove the malware.', "antihacker");
  $myhelp .= '<br>';
  $myhelp .= __("If you have other anti malware installed, maybe it will be reported for our plugin as possible malware.", 'antihacker');
  $myhelp .= '<br>';
  $myhelp .= __("Same thing if you run other malware software, maybe it can report malware in our plugin because we have malware sigature files.", 'antihacker');
  $myhelp .= '<br>';
  $myhelp .= __("The Scan Debug tab info is just in case you need contact our support otherwise you can ignore it.", 'antihacker');
  $myhelp .= '<br>';
  $myhelp .= __("Files with size zero are reported on Scan Debug Tab.", 'antihacker');
  $myhelpfreeze = '<br />';
  $myhelpfreeze .= 'If the job freeze (many minutes without progress updates on scan window) you can refresh the page and click Run Scan Now again.';
  $myhelpfreeze .= '<br />';
  $myhelpfreeze .= 'The Anti Hacker Plugin will resume the job from the last point.';
  $myhelpfreeze .= '<br />';
  $myhelpspeed = '<br />';
  $myhelpspeed .= 'If you need change the speed of the job in middle of the scan, just refresh the page and mark the new option before click Run Scan Now button again.';
  $myhelpspeed .= '<br />';
  $myhelptrouble = '<br>';
  $myhelptrouble .= __('Visit the Anti Hacker Scan Troubleshooting Page ', "antihacker");
  $myhelptrouble .= ' <a href="http://antihackerplugin.com/troubleshooting-page/" target="_blank">';
  $myhelptrouble .= __('plugin site', "antihacker");
  $myhelptrouble .= ' </a>';
  $myhelptable = '<br>';
  $myhelptable .= 'Malware (malicious software), is a blanket term for viruses, 
  worms, trojans and other harmful computer softwares designed to cause damage, gain access 
  to sensitive information, steal your traffic or computer resources.<br>';
  $myhelptable  .= __('Visit the Anti Hacker Malware Table  Page at ', "antihacker");
  $myhelptable  .= ' <a href="http://antihackerplugin.com/malware-table/" target="_blank">';
  $myhelptable  .= __('the plugin site', "antihacker") . '.';
  $myhelptable  .= ' </a>';
  $screen = get_current_screen();
  $screen->add_help_tab(array(
    'id' => 'wptuts-overview-tab',
    'title' => __('Overview', 'plugin_domain'),
    'content' => '<p>' . $myhelp . '</p>',
  ));
  $screen->add_help_tab(array(
    'id' => 'antihacker-scan',
    'title' => __('If Freeze', 'antihacker'),
    'content' => '<p>' . $myhelpfreeze . '</p>',
  ));
  $screen->add_help_tab(array(
    'id' => 'antihacker-troubleshooting',
    'title' => __('Troubleshooting', 'antihacker'),
    'content' => '<p>' . $myhelptrouble . '</p>',
  ));
  $screen->add_help_tab(array(
    'id' => 'antihacker-speed',
    'title' => __('Changing Speed', 'antihacker'),
    'content' => '<p>' . $myhelpspeed . '</p>',
  ));
  $screen->add_help_tab(array(
    'id' => 'antihacker-table',
    'title' => __('Malware Table', 'antihacker'),
    'content' => '<p>' . $myhelptable . '</p>',
  ));
  return;
}
/////////////////////////////////////////////////////////////
add_action('wp_ajax_antihacker_ajax_scan', 'antihacker_ajax_scan');
add_action('wp_ajax_antihacker_truncate_scan_table', 'antihacker_truncate_scan_table');
function antihacker_ajax_scan()
{
  global $antihacker_bill_debug;
  global $antihacker_scan_speed;

  
    function antihacker_return_bytes($size) {
        $unit = strtoupper(substr($size, -1));
        $value = (int)substr($size, 0, -1);
        
        switch ($unit) {
            case 'G':
                $value *= 1024;
            case 'M':
                $value *= 1024;
            case 'K':
                $value *= 1024;
        }
        
        return $value;
    }

    $currentMemoryLimit = ini_get('memory_limit');

    $memoryLimitBytes = antihacker_return_bytes($currentMemoryLimit);
    
    if ($memoryLimitBytes < 128 * 1024 * 1024) {
        ini_set('memory_limit', '128M');
    }
  

  // ini_set('memory_limit', '128M');

  if (!isset($_POST['security_nonce']) or !wp_verify_nonce(sanitize_text_field($_POST['security_nonce']), 'antihacker_scan_nonce')) 
  {
    // fail nonce
    //die($_POST['security_nonce']);
    die('Invalid Nonce');
  }

  $antihacker_bill_debug = false; // false;

  if (isset($_POST['radValue']))
    $antihacker_scan_speed = sanitize_text_field($_POST['radValue']);
  else
    $antihacker_scan_speed = 'normal!!';
  /*
  CREATE TABLE `wp_ah_scan` (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `date_inic` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `date_end` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `log` text NOT NULL,
        `qfiles` int(11) NOT NULL,
        `mystatus` varchar(20) NOT NULL,
        `debug` text NOT NULL,
        `malware` text NOT NULL,
        `flag` varchar(1) NOT NULL,
        `obs` text NOT NULL,
        UNIQUE (`id`),
        UNIQUE (`name`)
  */
  global $wpdb;
  global $antihacker_rules;
  global $antihacker_name_rule;
  global $antihacker_cond;
  global $antihacker_files_array;

  $st = antihacker_get_scan_status();

  //die(var_dump($st));

  if ($st == NULL or $st == 'end') {

    antihacker_create_db_scan_files();
    antihacker_create_db_scan();
    antihacker_create_db_rules();

    antihacker_scan_inic();
    $baseContents = scandir(ABSPATH);
    if (!is_array($baseContents)) {
      $text = "Anti Hacker could not read the contents of your base WordPress directory. This usually indicates your permissions are so strict that your web server can\'t read your WordPress directory.";
      antihacker_record_debug($text);
      antihacker_record_log($text);
      die('Fail to read, please, look the Scan Log tab. Click Cancel Button.');
    }
    if (defined('WP_MEMORY_LIMIT'))
      antihacker_record_debug('WordPress Memory Limit: ' . WP_MEMORY_LIMIT);
    antihacker_record_debug('starting');
    antihacker_record_log('starting');
    $table_name = $wpdb->prefix . "ah_scan";
    //    $query = "update " . $table_name . " SET mystatus = 'counting', qfiles = '" . $qfiles . "'";    
    $query = "update " . $table_name . " SET mystatus = 'counting'";
    $r = $wpdb->query($query);
    die("step 1 Counting Files...");
  }
  $st = antihacker_get_scan_status();
  if ($st == 'counting') {
    antihacker_record_log('counting files files to scan');
    antihacker_record_debug('counting files to scan');
    $r = antihacker_fetch_files(ABSPATH);
    $qfiles = (string) count($r);
    $table_name = $wpdb->prefix . "ah_scan";
    $query = "update " . $table_name . " SET mystatus = 'loading', qfiles = '" . $qfiles . "'";
    $r = $wpdb->query($query);
    $txt = 'Number of Files Found to Scan: ' . $qfiles;
    antihacker_record_debug($txt);
    antihacker_record_log($txt);
    antihacker_record_log('loading files to scan to table');
    antihacker_record_debug('loading files to scan to table');
    die('step 2 Loading files to scan...');
  }
  ////////////////////// COUNTING ///////////////////////////////
  $st = antihacker_get_scan_status();
  if ($st == 'loading') {
    global $wpdb;
    global $antihacker_bill_debug;
    $antihacker_quant_files = antihacker_get_qfiles(); // total q files found
    $files_db = antihacker_get_files_from_db(); // total...
    if ($antihacker_bill_debug)
      $antihacker_quant_files = 2000;
    if ($antihacker_quant_files > count($files_db)) {
      $antihacker_files_array = antihacker_fetch_files(ABSPATH);
      $tomake = $antihacker_quant_files;
      if ($antihacker_scan_speed == 'very_slow')
        $maxtomake = 75;
      elseif ($antihacker_scan_speed == 'slow')
        $maxtomake = 150;
      elseif ($antihacker_scan_speed == 'fast')
        $maxtomake = 450;
      elseif ($antihacker_scan_speed == 'very_fast')
        $maxtomake = 600;
      else
        $maxtomake = 300;
      if (($antihacker_quant_files - count($files_db)) < $maxtomake) {
        $table_name = $wpdb->prefix . "ah_scan";
        $query = "update " . $table_name . " SET mystatus = 'scanning'";
        $r = $wpdb->query($query);
        antihacker_record_log('scanning');
        antihacker_record_debug('scanning');
        die('step 3 Scanning files - 0%');
      }
      // Find pointer...
      $table_name = $wpdb->prefix . "ah_scan";
      $query = "select pointer from $table_name ORDER BY id DESC limit 1";
      $pointer =  $wpdb->get_var($query);
      $ctd = 0;
      for ($i = $pointer; $i < $tomake; $i++) {
        $name = base64_encode(trim($antihacker_files_array[$i]));
        if (in_array($name, $files_db)) {
          continue;
        }
        $table_name = $wpdb->prefix . "ah_scan_files";

        /*
        $query = "select name from $table_name WHERE name = '" . $name . "' LIMIT 1";
        if (!empty($wpdb->get_var($query)))
          continue;
        */

        $r = $wpdb->get_var($wpdb->prepare("
          SELECT name FROM `$table_name` WHERE name = %s LIMIT 1", $name));

        if (!empty($r))
          continue;



        if ($ctd > $maxtomake)
          break;
        $ctd++;

        /*
        $query = "INSERT IGNORE INTO " . $table_name .
        " (`name`) VALUES ('" . $name . "')";
        $r = $wpdb->get_results($query);
        */

        $r = $wpdb->get_results($wpdb->prepare(
          "INSERT IGNORE INTO `$table_name` 
          (`name`) 
          VALUES (%s)",
          $name
        ));
      }
      $files_db = antihacker_get_files_from_db();
      $done = round(count($files_db) / $antihacker_quant_files * 100);
      if ($done > 99)
        $done = 100;

      // Update pointer...
      $table_name = $wpdb->prefix . "ah_scan";

      /*
      $query = "UPDATE " . $table_name . " set `pointer` = '" . $i . "'";
      $r = $wpdb->query($query);
      */


      $r = $wpdb->query($wpdb->prepare(
        "UPDATE  `$table_name`
         SET pointer = %s",
        $i
      ));




      die('step 2 loading files to table - ' . esc_attr($done) . '%');
    } else {
      $table_name = $wpdb->prefix . "ah_scan";

      /*
      $query = "update " . $table_name . " SET mystatus = 'scanning'";
      $r = $wpdb->query($query);
      */


      $r = $wpdb->query($wpdb->prepare(
        "UPDATE  `$table_name`
         SET mystatus = %s",
        'scanning'
      ));



      antihacker_record_log('scanning');
      antihacker_record_debug('scanning');
      die('step 3 Scanning files - 0%');
    }
  }
  ////////////////////// SCANNING ///////////////////////////////
  $st = antihacker_get_scan_status();





  if (substr($st, 0, 8) == 'scanning') {

   // die($st);

    if ($antihacker_scan_speed == 'very_slow')
      $maxscan = 25;
    elseif ($antihacker_scan_speed == 'slow')
      $maxscan = 75;
    elseif ($antihacker_scan_speed == 'fast')
      $maxscan = 100;
    elseif ($antihacker_scan_speed == 'very_fast')
      $maxscan = 200;
    else
      $maxscan = 100; // era 500

    $files_to_scan = antihacker_get_files_to_scan($maxscan);

    // die(var_dump($files_to_scan));

    $tomake =  count($files_to_scan);



 


    // die(var_dump($tomake));

    //  die(gettype($tomake));


    $qfiles_todo = antihacker_get_qfiles(); //q files to scan

    //   die($tomake);


    $r = antihacker_get_rules();
    $antihacker_rules = $r[1];
    $antihacker_name_rule = $r[0];
    $antihacker_cond = $r[2];

    //die( (string) count($antihacker_rules));

    if (count($antihacker_rules) < 790) {
      antihacker_populate_rules();
      die('step 3 Scanning files');
    }

    if ($tomake > 0) {

      //die($st);
/*
      if ($antihacker_bill_debug) {
        die('debug 3');
      }
*/

      // $qfiles_todo = antihacker_get_qfiles(); //q files to scan
      $qscan_made = antihacker_get_files_scanned();
      foreach ($files_to_scan as $result) {
        $id = $result["id"];
        $name_file = base64_decode($result["name"]);
        antihacker_scan($name_file);
        // /////////////////////// SCAN /////////////////////////////////////
        if (antihacker_flag_file($id) === false) {
          $txt = 'Fail to flag file: ' . $name_file;
          //$txt .= 'Size: '.$size;
          antihacker_record_debug($txt);
        }
      }

      $qfiles_todo = (int) antihacker_get_qfiles(); //q files to scan

      if ($qfiles_todo == 0) {
        $txt = 'Unable to get quantity of files to scan (L299)';
        antihacker_record_debug($txt);
        die(esc_html($txt));
      }


      $qscan_made = antihacker_get_files_scanned();
      $made_perc =  round(($qscan_made /  $qfiles_todo) * 100);
      ////if ($made_perc > 96)
      //  $made_perc = 96;
      die('step 3 Scanning files - ' . esc_attr($made_perc) . '%');
    }

    $files_to_scan = antihacker_get_files_to_scan(1);
    $tomake =  count($files_to_scan);

    /*
    if ($tomake < 1) {
      antihacker_record_debug('End of Job');
      $table_name = $wpdb->prefix . "ah_scan";
      $query = "update " . $table_name . " SET mystatus = 'end'";
      $r = $wpdb->query($query);
      antihacker_record_log('End of Job');
      die('End of Job!');
    }
    */
  }

  /*
  if ($antihacker_bill_debug) {
    die('debug 4');
  }
  */


  //$files_to_scan = antihacker_get_files_to_scan($maxscan);
  //$tomake = count($files_to_scan);

  // $qfiles_todo = antihacker_get_qfiles(); //q files to scan

  $files_to_scan = antihacker_get_files_to_scan(1);


  $tomake =  count($files_to_scan);



  if ($tomake < 1) {


    /* /////////////////////////////////////////////// */

    /*
    if ($antihacker_bill_debug) {
      $table_name = $wpdb->prefix . "ah_scan";



      $r = $wpdb->query($wpdb->prepare(
        "UPDATE  `$table_name`
         SET mystatus = %s",
        'scanning'
      ));
    }
    */



    if ($st == 'scanning') {
      $pages = get_pages();

      foreach ($pages as $page) {
        $content = trim($page->post_content);

        // die($page->post_title);

        if (strlen($content) > 0)
          antihacker_find_match($content, 'page: ' . sanitize_text_field($page->post_title));
      }
      $table_name = $wpdb->prefix . "ah_scan";

      /*
      $query = "update " . $table_name . " SET mystatus = 'scanning-posts'";
      $r = $wpdb->query($query);
      */

      $r = $wpdb->query($wpdb->prepare(
        "UPDATE  `$table_name`
         SET mystatus = %s",
        'scanning-posts'
      ));

      $txt = 'Pages Scanned: ' . count($pages);
      antihacker_record_log($txt);
      antihacker_record_debug($txt);


      die("Step 3 - " . count($pages) . " Pages Scanned - 97%");
    }


    if ($st == 'scanning-posts') {


      $pages = get_posts();
      foreach ($pages as $page) {
        $content = trim($page->post_content);
        if (strlen($content) > 0)
          antihacker_find_match($content, 'page: (Post) ' . sanitize_text_field($page->post_title));
      }

      $table_name = $wpdb->prefix . "ah_scan";

      $query = "update " . $table_name . " SET mystatus = 'scanning-comments'";
      $r = $wpdb->query($query);


      $r = $wpdb->query($wpdb->prepare(
        "UPDATE  `$table_name`
         SET mystatus = %s",
        'scanning-comments'
      ));

      $txt = 'Posts Scanned: ' . count($pages);
      antihacker_record_log($txt);
      antihacker_record_debug($txt);

      die("Step 3 - " . count($pages) . " Posts Scanned - 98%");
    }

    if ($st == 'scanning-comments') {
      $pages = get_comments();
      foreach ($pages as $page) {
        $content = trim($page->post_content);
        if (strlen($content) > 0)
          antihacker_find_match($content, 'page: (Comment) ' . sanitize_text_field($page->post_title));
      }

/*
      if ($antihacker_bill_debug) {
        die('debug');
      }
*/
      $table_name = $wpdb->prefix . "ah_scan";

      /*
      $query = "update " . $table_name . " SET mystatus = 'end-scan'";
      $r = $wpdb->query($query);
      */

      $r = $wpdb->query($wpdb->prepare(
        "UPDATE  `$table_name`
         SET mystatus = %s",
        'end-scan'
      ));

      $txt = 'Comments Scanned: ' . count($pages);
      antihacker_record_log($txt);
      antihacker_record_debug($txt);

      die("Step 3 - " . count($pages) . " Comments Scanned - 99%");
    }
    /* /////////////////////////////////////////////// */

/*
    if ($antihacker_bill_debug) {
      die('debug');
    }
*/

    antihacker_record_debug('End of Job');
    $table_name = $wpdb->prefix . "ah_scan";

    /*
    $query = "update " . $table_name . " SET mystatus = 'end'";
    $r = $wpdb->query($query);
    */

    $r = $wpdb->query($wpdb->prepare(
      "UPDATE  `$table_name`
       SET mystatus = %s",
      'end'
    ));

    antihacker_record_log('End of Job');
    die('End of Job!');
  }

  die('Running...');
}
// =============================
function antihacker_flag_file($id)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan_files";

  /*
  $query = "update " . $table_name . " SET flag = '1' WHERE id = '" . $id . "'  LIMIT 1";
  $r = $wpdb->query($query);
  */

  $r = $wpdb->query($wpdb->prepare(
    "UPDATE  `$table_name`
     SET flag = '1' 
     WHERE id = %s LIMIT 1",
    $id
  ));

  return $r;
}
function antihacker_unflag()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan_files";
  $query = "update " . $table_name . " SET flag = ''";
  $r = $wpdb->query($query);
  return $r;
}
function antihacker_get_total_db_files()
{
  global $wpdb;
  global $antihacker_bill_debug;
  if ($antihacker_bill_debug)
    return 500;
  $table_name = $wpdb->prefix . "ah_scan_files";
  $query = "select count(*) from $table_name";
  return $wpdb->get_var($query);
}
function antihacker_get_files_scanned()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan_files";
  $query = "select count(*) from $table_name WHERE flag='1'";
  return $wpdb->get_var($query);
}
function antihacker_get_files_to_scan($limit)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan_files";
  //$query = "select name, id from " . $table_name . " where flag <> '1' ORDER BY id LIMIT " . $limit;
  $query = "select name, id from " . $table_name . " where flag <> '1' LIMIT " . $limit;
  return $wpdb->get_results($query, ARRAY_A);

//  return $wpdb->get_results($wpdb->prepare("SELECT name, id FROM %s WHERE flag <> '1' LIMIT %d", $table_name, $limit), ARRAY_A);

}
function antihacker_get_files_from_db()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan_files";
  //$query = "select name, id from " . $table_name . " where flag <> '1' ORDER BY id"; //  LIMIT 1000";
  $query = "select name, id from " . $table_name . " ORDER BY id"; //  LIMIT 1000";
  $results = $wpdb->get_results($query, ARRAY_A);
 // $results = $wpdb->get_results($wpdb->prepare("SELECT name, id FROM %s ORDER BY id LIMIT %d", $table_name, 1000), ARRAY_A);
 // $query = "SELECT name, id FROM $table_name WHERE flag <> '1' LIMIT $limit";
 // $results = $wpdb->get_results($query, ARRAY_A);

  
  return ($results);
}
function antihacker_fetch_files($dir)
{
  $x = scandir($dir);
  $result = array();
  foreach ($x as $filename) {
    if ($filename == '.') continue;
    if ($filename == '..') continue;
    $result[] = $dir . $filename;
    $filePath = $dir . $filename;
    if (is_dir($filePath)) {
      $filePath = $dir . $filename . '/';
      foreach (antihacker_fetch_files($filePath) as $childFilename) {
        $result[] = $childFilename;
      }
    }
  }
  return $result;
}
function antihacker_find_match($str, $file)
{
  global $antihacker_rules;
  global $antihacker_name_rule;
  global $antihacker_cond;
  $match = array();
  /*
    CREATE TABLE `rules` (
    `id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `strings` text NOT NULL,
    `cond` text NOT NULL,
    `descri` text NOT NULL,
    `autor` text NOT NULL,
    `obs` text NOT NULL,
    `flag` varchar(1) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    */
  // Loop de todas rules


  //die($file);





  for ($i = 0; $i < count($antihacker_rules); $i++) {
    if (empty($antihacker_rules[$i]))
      continue;
    $mystrings = explode(PHP_EOL, $antihacker_rules[$i]);
    $myresult = array();


    // die($file);




    ////////////////// Loop de cada rule
    for ($j = 0; $j < count($mystrings); $j++) {
      $mystrings[$j] = trim(base64_decode($mystrings[$j]));
      $pattern = trim(antihacker_find_pattern($mystrings[$j]));

      if (substr($pattern, 0, 1) == '"')
        $pattern = substr($pattern, 1);

      if (substr($pattern, -1) == '"')
        $pattern = substr($pattern, 0, strlen($pattern) - 1);

      if (empty($pattern) or $pattern == '"')
        continue;


      $pos = strpos($mystrings[$j], '=');



      $id_string = trim(substr($mystrings[$j], 0, $pos));
      if (strpos($str, $pattern) === false)
        $myresult[$id_string]  = false;
      else
        $myresult[$id_string]  = true;
    }
    ////////////////// END Loop de cada rule



    $mycond = trim($antihacker_cond[$i]);
    $name_this_rule = $antihacker_name_rule[$i];

    /*
    Condition Not Found: filesize < 15KB and 4 of them
    Name of rule: php_killnc
    */

    if ($name_this_rule == 'php_killnc' and $mycond == 'filesize < 15KB and 4 of them' and  substr($file, 0, 5) !== 'page:') {
      // antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
      // die('debug '.var_dump($file));
      //string(36) "/home/wptoolsp/public_html/.htaccess" debug 
      // string(36) "/home/wptoolsp/public_html/.htaccess" debug 

      // page: Fix Low WordPress Memory Limit

    }



    $newArray = array_values($myresult);
    if ($mycond == "any of them" or $mycond == "1 of them") {

      if ($name_this_rule == 'phpmailer') {


        $w = array();

        $w[] = "YWRkQWRkcmVzcygndHNlZ2Fkb3JhQHlhaG9vLmNvbSc=";
        $w[] = "MWFmOTg2MDlhZGY3OTZiMjFjOWZjNzM1ZTMxYzU3Yjc=";
        $w[] = "dXBsb2QgU3VjZXNzIEJ5IHc0bDNYelkz";
        $w[] = "QiBMIEUgUyBTIEUgRCBTIEkgTiBOIEUgUg==";
        $w[] = "Qmxlc3NlRCBNQUlMRVIgMjAxNA==";


        for ($n = 0; $n < count($w); $n++) {
          if (strpos($str, base64_decode($w[$n])) !== false) {
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
            break;
          }
        }
      } else {
        for ($k = 0; $k < count($newArray); $k++) {
          if ($newArray[$k]) {
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
          }
        }
      }
    } elseif ($mycond == chr(36) . "a0" or $mycond == chr(36) . "a" or $mycond == chr(36) . "s0") {
      if ($newArray[0])
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
    } elseif ($mycond == chr(36) . "a0 and " . chr(36) . "a1") {
      if ($newArray[0] and $newArray[1])
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
    } elseif ($mycond == '$a0') {
      if ($newArray[$k] == true) {
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
      }
    } elseif ($mycond == chr(36) . "a0 and " . chr(36) . "a1 and " . chr(36) . "a2") {
      if ($newArray[0] and $newArray[1] and $newArray[2]) {
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
      }
    } elseif ($mycond == chr(36) . "a0 and " . chr(36) . "a1 and " . chr(36) . "a2 and " . chr(36) . "a3") {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 4) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == chr(36) . "a0 and " . chr(36) . "a1 and " . chr(36) . "a2 and " . chr(36) . "a3 and " . chr(36) . "a4") {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 5) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == "filesize < 15KB and 4 of them" and substr($file, 0, 5) !== 'page:') {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 4 and filesize($file) < 15 * 1024) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == "2 of them") {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 2) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == "3 of them") {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 3) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == "4 of them") {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 4) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == "5 of them") {
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= 5) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond ==  "all of them" or strpos($mycond, 'all of (' . chr(36) . 's*)') !== false) {
      //     all of ($s*)
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == false) {
          break;
        }
      }
    } elseif (substr($mycond, -7) == 'of them' and strlen($mycond) < 13) {
      $onlyNumeric = filter_var($mycond, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k] == true) {
          $ctd++;
        }
        if ($ctd >= $onlyNumeric) {
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif (substr($mycond, 0, 8) == 'filesize' and substr($file, 0, 5) !== 'page:') {
      $oper = substr($mycond, 9, 1);
      $mat[0] = filter_var($mycond, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      if ($oper == '<') {
        if (filesize($file) < $mat[0] * 1024) {
          if (strpos($mycond, 'all of them') !== false) {
            $found = true;
            for ($k = 0; $k < count($newArray); $k++) {
              if ($newArray[$k] == false) {
                $found = false;
              }
            }
            if ($found) {
              antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
            }
          }
        }
      } elseif ($oper == '>') {
        if (filesize($file) > $mat[0] * 1024) {
          //die(var_dump(filesize($file)));
        }
        $txt = 'Error 9999. ' . $mycond;
        antihacker_record_debug($txt);
      }
    } elseif (strpos($mycond, 'mz at 0 and any of') !== false) {
      if (substr($str, 0, 2) == 'MZ') {
        for ($k = 0; $k < count($newArray); $k++) {
          if ($newArray[$k] == true) {
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
          }
        }
      }
    } elseif (strpos($mycond, 'magic at 0) and any of (') !== false) {
      // $magic = {47 49 46 38 ?? 61} // GIF8a
      if (substr($str, 0, 4) == 'GIF8') {
        for ($k = 0; $k < count($newArray); $k++) {
          if ($newArray[$k] == true) {
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
          }
        }
      }
    } elseif (strpos($mycond, 'mz at 0 and ') !== false and strpos($mycond, 'php and any of ($string') !== false) {
      // not $mz at 0 and $php and any of ($string*)

      $php = 'string1 = "eval(gzinflate(str_rot13(base64_decode(';

      if (strpos($str, $php) === false)
        continue;

      if (substr($str, 0, 2) != 'MZ') {
        for ($k = 0; $k < count($newArray); $k++) {
          if ($newArray[$k] == true) {
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
          }
        }
      }
    } elseif (strpos($mycond, 'is_elf and all of') !== false) {
      if (substr($str, 0, 3) == 'ELF') {
        for ($k = 0; $k < count($newArray); $k++) {
          if ($newArray[$k] != true) {
            break;
          }
        }
        die(var_dump(esc_attr(substr($str, 0, 3))));
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
      }
      //                     ($magic at 0) and (any of ($string*))
    } elseif (strpos($mycond, 'magic at 0) and (any of') !== false or strpos($mycond, 'magic at 0) and 1 of (') !== false or strpos($mycond, 'magic at 0) and (any of (' . chr(36) . 'string)') !== false) {
      // 3 juntas
      if (substr($str, 0, 4) == 'GIF8') {
        // $magic = {47 49 46 38 ?? 61} // GIF8a
        for ($k = 0; $k < count($newArray); $k++) {
          if ($newArray[$k]) {
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
          }
        }
      }
    } elseif (strpos($mycond, 'gif at 0) or') !== false) {
      // Gif   (($gif at 0) or
      // NOTICE [8] exif_imagetype(): Read error! Notice on line 791 in file /home/wptoolsp/public_html/wp-content/plugins/antihacker/scan/functions_scan.php


      if (is_file($file)) {

        try {
          $work_filesize = @filesize($file);
          if ($work_filesize === FALSE) {
            $img_type = 99;
            $txt =  'Error reading ' . __LINE__ . ' : ' . $file;
            antihacker_record_debug($txt);
            // return;
            // continue;
          } else {

            if (filesize($file) > 11)
              $img_type = exif_imagetype($file);
            else {

              $img_type = 99;
              $txt =  'Error reading ' . __LINE__ . ' : ' . $file;
              antihacker_record_debug($txt);
            }
          }
        } catch (Exception $e) {
          // echo 'Message: ' .$e->getMessage();
          $img_type = 99;

          $txt =  'Error reading ' . __LINE__ . ' : ' . $file;

          // $size = filesize($file);
          // $txt .=  PHP_EOL . 'Size: ' . $size;
          antihacker_record_debug($txt);

          // continue;
          // return;
        }
      } else {

        $img_type = 99;

        if (substr($file, 0, 5) !== 'page:') {
          $txt =  'Error reading ' . __LINE__ . ' : ' . $file;
          antihacker_record_debug($txt);
        }
      }


      // $img_type = exif_imagetype($file);

      if ($img_type == 1) {
        if (strpos($str, '<?php') !== false)
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
      }
    } elseif ($mycond == "2 of (" . chr(36) . "s*) and not " . chr(36) . "fn") {
      // 2 of ($s*) and not $fn
      if (strpos($file, 'backup') !== false)
        continue;
      $ctd = 0;
      for ($k = 0; $k < count($newArray); $k++) {
        if ($newArray[$k]) {
          $ctd++;
          if ($ctd == 2)
            antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        }
      }
    } elseif ($mycond == "1 of (" . chr(36) . "s*) and " . chr(36) . "sAuthor") {
      $wstr = "POST['dd'])?'checked':'').\">DB<input";
      if (strpos($str, 'Author = "ShAnKaR"') !== false and strpos($str, $wstr) !== false)
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
    } elseif ($mycond == "1 of (" . chr(36) . "s*) or all of (" . chr(36) . "x*) or all of (" . chr(36) . "y*)") {


      //  1 of ($s*) or all of ($x*) or all of ($y*)

      $s0 = "ZmlsZV9nZXRfY29udGVudHMoImh0dHA6Ly9wYXN0ZWJpbi5jb20=";
      $s1 = "eGN1cmwoJ2h0dHA6Ly9wYXN0ZWJpbi5jb20vZG93bmxvYWQucGhw";
      $s2 = "eGN1cmwoJ2h0dHA6Ly9wYXN0ZWJpbi5jb20vcmF3LnBocA==";

      $x0 = "Y29udGVudCl7dW5saW5rKCdldmV4LnBocCcpOw==";
      $x1 = "ZmgyID0gZm9wZW4oImV2ZXgucGhwIiwgJ2EnKTs=";
      $y0 = "ZmlsZV9wdXRfY29udGVudHMocHRo";
      $y1 = "echo \"";
      $y2 = "c3RyX3JlcGxhY2UoJyogQHBhY2thZ2UgV29yZHByZXNzJyw=";



      if (strpos($str, base64_decode($s0)) !== false or strpos($str, base64_decode($s1)) !== false or strpos($str, base64_decode($s2)) !== false) {
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        continue;
      }
      if (strpos($str, base64_decode($x0)) !== false and strpos($str, base64_decode($x1)) !== false) {
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        continue;
      }
      if (strpos($str, base64_decode($y0)) !== false and strpos($str, base64_decode($y1)) !== false and strpos($str, base64_decode($y2)) !== false) {
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
        continue;
      }


      //} elseif ($mycond == '$GLOBALS["') {
      //  #global > 30
    } elseif ($mycond == '#global > 30') {
      $q = substr_count($str, chr(36) . 'GLOBALS["'); // 2
      if ($q > 30)
        antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
    } elseif ($mycond == 'uint16(0) == 0x4b50 and filesize < 2KB and all of them' and substr($file, 0, 5) !== 'page:') {
      for ($k = 0; $k < count($newArray); $k++) {
        if (!$newArray[$k]) {
          continue;
        }
        if (filesize($file) < 2 * 1024)
          antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);
      }
    } else {

      if (substr($file, 0, 5) !== 'page:') {
        $txt = 'Condition Not Found: ' . $mycond;
        $txt .= PHP_EOL;
        $txt .= 'Name of rule: ' . $name_this_rule;
        $txt .= PHP_EOL;
        $txt .= 'Name of file: ' . $file;
        antihacker_record_debug($txt);
      }

      // antihacker_record_match($file, $name_this_rule, $mycond,  __LINE__);




    }
  }
  if (count($match) == 0) {
    return NULL;
    return $match;
  }
}










function antihacker_find_pattern($s)
{
  $pos = strpos($s, '=');
  $pattern = trim(substr($s, $pos + 1));
  $pattern = str_replace("nocase", "", $pattern);
  if (substr($pattern, 0, 1) == '{') {
    $pattern = substr($pattern, 1);
    $pos = strpos($pattern, '}');
    $pattern = substr($pattern, 0, $pos);
  }
  return $pattern;
}
function antihacker_scan($file)
{
  if (empty($file))
    return;
  if (is_dir($file)) {
    return;
  }
  if (!is_file($file)) {
    $txt =  'Error not is file: ' . $file;
    antihacker_record_debug($txt);
    return;
  }
  if (strpos($file, 'antihacker.php') !== false)
    return;
  if (strpos($file, 'functions_scan.php') !== false)
    return;
  if (strpos($file, 'rules.txt') !== false)
    return;

  $size = filesize($file);
  if (gettype($size) == 'string') {
    $txt =  'Error reading (-2): ' . $file;
    $txt .=  PHP_EOL . 'Size: ' . $size;
    antihacker_record_debug($txt);
    return;
  }
  if ($size < 1) {
    // $txt =  'Possible error reading (warning type -1): ' . $file;
    $txt =  'Warning reading file: ' . $file;
    $txt .=  PHP_EOL . 'File Size: ' . $size;
    antihacker_record_debug($txt);
    return;
  }
  if ($size > 20000 * 1024) {
    $txt =  PHP_EOL . 'File scan fail, because file is too big (suspicious): ' . $file . PHP_EOL;
    $txt .= 'File Size: ' . antihacker_getHumanReadableSize($size);
    // antihacker_record_debug($txt);
    antihacker_record_log($txt);
    return;
  }
  /*
  //    2000000
  if ($size > 2000 * 1024) {
    $fp = fopen($file, "r");
    if (!$fp) {
      $txt = 'Error Open (-2): ' . $file;
      $txt .= PHP_EOL . 'File Size: ' . $size;
      antihacker_record_debug($txt);
      return;
      // die('fail to open ' . $file);
    }
    // $filesize = get_file_size($file);
    $filesize = $size;
    // $fp = @fopen($file, "r");
    // die(var_dump($filesize ));
    $chunk_size = (1 << 24); // 16MB arbitrary
    $chunk_size =  (500 * 1024);
    $position = 0;
    $firstline = fgets($fp, 100);
    // die($firstline);
    // if handle $fp to file was created, go ahead
    // if ($fp) {
    while (!feof($fp)) {
      // move pointer to $position in file
      fseek($fp, $position);
      // take a slice of $chunk_size bytes
      $chunk = fread($fp, $chunk_size);
      die(strlen($chunk));
      // searching the end of last full text line
      $last_lf_pos = strrpos($chunk, "\n");
      // $buffer will contain full lines of text
      // starting from $position to $last_lf_pos
      if($position != 0)
       $buffer = $firstline;
      else 
       $buffer = '';
      $buffer .= mb_substr($chunk, 0, $last_lf_pos);
     // die(strlen($buffer));
      ////////////////////////////////////////////////////
      //// ... DO SOMETHING WITH THIS BUFFER HERE ... ////
      ////////////////////////////////////////////////////
      // $body = fread($fileh, $size);
      antihacker_find_match($buffer, $file);
      // Move $position
      $position += $last_lf_pos;
      // if remaining is less than $chunk_size, make $chunk_size equal remaining
      if (($position + $chunk_size) > $filesize) $chunk_size = $filesize - $position;
      $buffer = NULL;
    }
    fclose($fp);
    // }
  } 
    */
  $fp = @fopen($file, "r");
  if (!$fp) {
    $txt = 'Error Open: ' . $file;
    antihacker_record_debug($txt);
    return;
  }
  $body = fread($fp, $size);
  antihacker_find_match($body, $file);
  fclose($fp);
}
function antihacker_getHumanReadableSize($bytes)
{
  if ($bytes > 0) {
    $base = floor(log($bytes) / log(1024));
    $units = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"); //units of measurement
    return number_format(($bytes / pow(1024, floor($base))), 3) . " $units[$base]";
  } else return "0 bytes";
}
function antihacker_get_rules()
{
  global $wpdb;
  /*
CREATE TABLE `wp_ah_rules` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `strings` text NOT NULL,
  `cond` text NOT NULL,
  `descri` text NOT NULL,
  `autor` text NOT NULL,
  `url` text NOT NULL,
  `obs` text NOT NULL,
  `flag` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    */
  $table_name = $wpdb->prefix . "ah_rules";

  $query = "select name, cond, strings from " . $table_name . " ORDER BY id";
  $results = $wpdb->get_results($query, ARRAY_A);
  //$results = $wpdb->get_results($wpdb->prepare("SELECT name, cond, strings FROM %s ORDER BY id", $table_name), ARRAY_A);

  $antihacker_rules = array();
  $antihacker_cond = array();
  $antihacker_name_rule = array();
  foreach ($results as $row) {


    //$antihacker_name_rule[] = trim($row["name"]);
    //$antihacker_cond[] = trim($row["cond"]);
    //$antihacker_rules[] = trim($row["strings"]);

    $antihacker_name_rule[] = strrev(trim($row["name"]));
    $antihacker_cond[] = strrev(trim($row["cond"]));
    $antihacker_rules[] = trim($row["strings"]);


  }
  return array($antihacker_name_rule, $antihacker_rules, $antihacker_cond);
}
function  antihacker_get_qfiles()
{
  global $wpdb;
  global $antihacker_bill_debug;
  if ($antihacker_bill_debug)
    return 500;


  $table_name = $wpdb->prefix . "ah_scan";
  $query = "select qfiles from $table_name ORDER BY id DESC limit 1";
  return $wpdb->get_var($query);
}
function antihacker_get_scan_status()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan";
  $query = "select mystatus from $table_name ORDER BY id DESC limit 1";
  return $wpdb->get_var($query);
}
function antihacker_scan_inic()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan";
  $query = "TRUNCATE TABLE " . $table_name;
  $r = $wpdb->query($query);

  $query = "INSERT INTO " . $table_name . " (`mystatus`) VALUES ('starting')";
  $r = $wpdb->query($query);

  /*
  $r = $wpdb->query($wpdb->prepare(
    "INSERT INTO `$table_name` 
    (mystatus)
    VALUES (%s)", 'starting'));
  */



  $table_name = $wpdb->prefix . "ah_scan_files";
  $query = "TRUNCATE TABLE " . $table_name;
  $r = $wpdb->query($query);


  antihacker_unflag();
}
function antihacker_truncate_scan_table()
{
  if ( ! isset( $_POST['antihacker_scan_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['antihacker_scan_nonce'] ), 'antihacker_scan_nonce' ) ) {
    wp_die('Nonce Fail!');
  }

    if (current_user_can('administrator')) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ah_scan";
        $query = "TRUNCATE TABLE " . $table_name;
        $r = $wpdb->query($query);
        wp_die('Table clean!');
    }
}



function antihacker_record_match($file, $name_this_rule, $mycond, $line)
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_scan";
  $query = "select malware from $table_name ORDER BY id DESC limit 1";
  $malware = $wpdb->get_var($query);
  $content = $malware . PHP_EOL . __("Found Possible Malware:", "antihacker") . ' ' .  $name_this_rule . PHP_EOL . __("on file:", "antihacker") . ' ' . $file;
  $content .= PHP_EOL . '------------------------------';

  /*
  $query = "UPDATE " . $table_name . " set `malware` = '" . $content . "'";
  $r = $wpdb->query($query);
  */

  $r = $wpdb->query($wpdb->prepare(
    "UPDATE  `$table_name`
     SET malware = %s",
    $content
  ));




  $query = "select debug from $table_name ORDER BY id DESC limit 1";
  $malware = $wpdb->get_var($query);
  $content = $malware . PHP_EOL . __("Found Possible Malware:", "antihacker") . '  ' .  $name_this_rule . PHP_EOL . "on file: " . $file;
  $content .= PHP_EOL . '  ' . __('Condiction:', 'antihacker') . ' ' . $mycond;
  $content .= PHP_EOL . '  ' . __("Code:", "antihacker") . ' '  . $line;
  $content .= PHP_EOL . '------------------------------';

  /*
  $query = "UPDATE " . $table_name . " set `debug` = '" . $content . "'";
  $r = $wpdb->query($query);
  */


  $r = $wpdb->query($wpdb->prepare(
    "UPDATE  `$table_name`
     SET debug = %s",
    $content
  ));
}
function antihacker_record_debug($text)
{
  global $wpdb;
  $txt = PHP_EOL . date('Y-m-d H:i:s') . ' ' . PHP_EOL;
  $txt .=  __('Memory Usage Now:', 'antihacker');
  $txt .= function_exists('memory_get_usage') ? antihacker_getHumanReadableSize(round(memory_get_usage(), 0)) : 0;
  $txt .= PHP_EOL;
  $txt .=  __('Memory Peak Usage:', 'antihacker') . ' ';
  $txt .=  antihacker_getHumanReadableSize(memory_get_peak_usage());
  $txt .=  PHP_EOL . $text . PHP_EOL;
  $txt .= '------------------------------';
  $table_name = $wpdb->prefix . "ah_scan";
  $query = "select debug from $table_name ORDER BY id DESC limit 1";
  $debug = $wpdb->get_var($query);
  $content = $debug . $txt;

  /*
  $query = "UPDATE " . $table_name . " set `debug` = '" . $content . "'";
  $r = $wpdb->query($query);
  */

  $r = $wpdb->query($wpdb->prepare(
    "UPDATE  `$table_name` SET debug = %s",
    $content
  ));
}
function antihacker_record_log($text)
{
  global $wpdb;
  $txt = PHP_EOL . date('Y-m-d H:i:s') . ' ' . $text . PHP_EOL;
  $txt .= '------------------------------';
  $table_name = $wpdb->prefix . "ah_scan";
  $query = "select log from $table_name ORDER BY id DESC limit 1";
  $log = $wpdb->get_var($query);
  $content = $log . $txt;
  /*
  $query = "UPDATE " . $table_name . " set `log` = '" . $content . "'";
  $r = $wpdb->query($query);
  */
  $r = $wpdb->query($wpdb->prepare(
    "UPDATE  `$table_name`
     SET log = %s",
    $content
  ));
}
