<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $wp_version;

if (version_compare($wp_version, '5.2') >= 0) {
    antihacker_health();
} else {
    return;
}


function antihacker_health()
{
    global $antihacker_memory_result;

    $memory = antihacker_check_memory();

    if (isset($memory["msg_type"]) && $memory["msg_type"] == "notok"){
        $memory = 'Unable to Check!';
        return;
     }

    if (preg_match('/(\d+)\s*([A-Za-z]+)$/', WP_MEMORY_LIMIT, $matches)) {
        $mb = $matches[2];
    } else {
        $mb = 'M';
    }

    ob_start();
    echo 'Current memory WordPress Limit: ' . esc_attr($memory['wp_limit']) . esc_attr($mb) .
        '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';

    echo '<span style="color:red;">';
    echo 'Your usage now: ' . esc_attr($memory['usage']) .
        'MB &nbsp;&nbsp;&nbsp;';
    echo '</span>';
    echo '<br />';
    echo '</strong>';
    $antihacker_memory_result = ob_get_contents();
    ob_end_clean();
    function antihacker_add_memory_test($tests)
    {
        $tests['direct']['memory_plugin'] = array(
            'label' => esc_attr__('My Memory Test', 'antihacker'),
            'test' => 'antihacker_memory_test',
        );
        return $tests;
    }
    $perc = $memory['usage'] / $memory['wp_limit'];
    if ($perc > .7) {
        add_filter('site_status_tests', 'antihacker_add_memory_test');
    }
    function antihacker_memory_test()
    {
        global $antihacker_memory_result;
        $result = array(
            'badge' => array(
                'label' => __('Critical', 'antihacker'), // Performance
                'color' => 'red', // orange',
            ),
            'test' => 'Bill_plugin',
            'status' => 'critical',
            'label' => __('Low WordPress Memory Limit in wp-config file', 'antihacker'),
            'description' => $antihacker_memory_result . '  ' . sprintf(
                '<p>%s</p>',
                __('Run your site with low memory available, can result in behaving slowly, or pages fail to load, you get random white screens of death or 500 internal server error. Basically, the more content, features and plugins you add to your site, the bigger your memory limit has to be. Increase the WP Memory Limit is a standard practice in WordPress. You can manually increase memory limit in WordPress by editing the wp-config.php file. You can find instructions in the official WordPress documentation (Increasing memory allocated to PHP). Just click the link below: ','antihacker')
            ),
            'actions' => sprintf(
                '<p><a href="%s">%s</a></p>',
                'https://codex.wordpress.org/Editing_wp-config.php',
                __('WordPress Help Page','antihacker')
            ),
        );
        return $result;
    }
}
