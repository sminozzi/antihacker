<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$last_checked = get_option('antihacker_googlesafe_checked', '0');
if (empty($antihacker_checkversion)) {
    $days = 7;
} else {
    $days = 1;
}
$write = time(); //  - (8 * 24 * 3600);

if ($last_checked == '0' or ($last_checked + ($days * 24 * 3600)) < time()) {



    if (!add_option('antihacker_googlesafe_checked', $write)) {
        update_option('antihacker_googlesafe_checked', $write);
    }


    $antihacker_safebrowsing = antihacker_safebrowsing();

    if ($antihacker_safebrowsing == '0' or $antihacker_safebrowsing == '1') {
        if (!add_option('antihacker_safebrowsing', $antihacker_safebrowsing))
            update_option('antihacker_safebrowsing', $antihacker_safebrowsing);
    } else
        $antihacker_safebrowsing = '1';
} else {


    $antihacker_safebrowsing = trim(sanitize_text_field(get_option('$antihacker_safebrowsing', '1')));
}


// $antihacker_safebrowsing = '1';

if ($antihacker_safebrowsing == '0') {
    // echo 'Blocked';

    echo '<img src="' . esc_attr(ANTIHACKERIMAGES) . '/noktick.png" width="100" >';
    echo '<br />';
    echo '<br />';
    echo esc_attr__('Site Blacklisted by Google', 'antihacker');
    echo '<br />';
} elseif ($antihacker_safebrowsing == '1') {
    echo '<img src="' . esc_attr(ANTIHACKERIMAGES). '/oktick.png" width="100" >';
    echo '<br />';
    echo '<br />';
    echo esc_attr__('Site NOT Blacklisted by Google', 'antihacker');
    echo '<br />';
} else {
    echo '';
    echo esc_attr__('Fail response from Google, please, try again later!', 'antihacker');
}

return;


function antihacker_safebrowsing()
{
    $last_checked = time();

    ob_start();
    $domain_name = get_site_url();
    $urlParts = parse_url($domain_name);
    $domain_name = preg_replace('/^www\./', '', $urlParts['host']);

    // Debug
    //$domain_name = 'testsafebrowsing.appspot.com/s/phishing.html';
    //$domain_name = 'testsafebrowsing.appspot.com/s/malware.html';


    $myarray = array(
        'last_checked' => $last_checked,
        'version' => ANTIHACKERVERSION,
        'domain_name' => $domain_name,
    );



    $url = "https://antihackerplugin.com/api/httpapi-gb.php";
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 15,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $myarray,
        'cookies' => array()
    ));
    if (is_wp_error($response)) {

        // $error_message = $response->get_error_message();

        ob_end_clean();

        // echo "Something went wrong: $error_message"; 

        return '-9';
    }

    // 0 = blocked
    // 1 = OK
    // outro negativo = fail

    $r = trim($response['body']);
    $r = json_decode($r, true);




    ob_end_clean();

    return $r;
}
