<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
// Define a function to output files in a directory

global $antihacker_report_files;
$antihacker_report_files = array();

function antihacker_outputFiles($path)
{
    global $antihacker_report_files;


    $whitelist = array(
        '.htaccess',
        '403.shtml',
        'BingSiteAuth.xml',
        'favicon.ico',
        'index.php',
        'license.txt',
        'readme.html',
        'robots.txt',
        'sitemap.xml',
        'wp-activate.php',
        'wp-blog-header.php',
        'wp-comments-post.php',
        'wp-config-sample.php',
        'wp-config.php',
        'wp-cron.php',
        'wp-links-opml.php',
        'wp-load.php',
        'wp-login.php',
        'wp-mail.php',
        'wp-settings.php',
        'wp-signup.php',
        'wp-trackback.php',
        'xmlrpc.php'
    );


    // Check directory exists or not
    if (file_exists($path) && is_dir($path)) {
        // Scan the files in this directory
        $result = scandir($path);

        // Filter out the current (.) and parent (..) directories
        $files = array_diff($result, array('.', '..'));


        if (count($files) > 0) {
            // Loop through retuned array
            foreach ($files as $file) {
                if (is_file("$path/$file")) {

                    if (in_array($file, $whitelist))
                        continue;

                    $antihacker_report_files[] = $file;
                } else if (is_dir("$path/$file")) {
                    // Recursively call the function if directories found
                    // outputFiles("$path/$file");
                }
            }
        } 
    } else {
        echo esc_attr__('ERROR on Search the root folder. Maybe no Hosting permissions.','antihacker');
    }
    return;
}

antihacker_outputFiles(ABSPATH);

if (count($antihacker_report_files) > 0) {

    echo esc_attr__('File(s) found on site root folder', 'antihacker').':';
    echo '<br>';

    for ($i = 0; $i < count($antihacker_report_files); $i++) {

        if($i > 9)
        continue;
        
        echo esc_attr($antihacker_report_files[$i]);
        echo '<br>';


    }
    if($i > 9){
        echo '<br>';
        echo esc_attr__('More files found', 'antihacker').'...';
    }
} else
    echo esc_attr__('No extra files found! All Right.', 'antihacker').'...';

return;
