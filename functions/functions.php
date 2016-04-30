<?php

/**
 * @author William Sergio Minossi
 * @copyright 2016
 */

if(is_admin())
{
    if(isset($_GET['page'])){
        if ($_GET['page'] == 'anti-hacker')
          {
              add_filter('contextual_help', 'ah_contextual_help', 10, 3);
              
              function ah_contextual_help($contextual_help, $screen_id, $screen)
                {
                
                    $myhelp = '<br> Improve system security and help prevent unauthorized access to your account.';
                    $myhelp .= '<br />Read the StartUp guide at Anti Hacker Settings page.';
                    $myhelp .= '<br />Visit the <a href="http://antihackerplugin.com" target="_blank">plugin site</a> for more details.';
                     
                    $screen->add_help_tab(array(
                        'id' => 'wptuts-overview-tab',
                        'title' => __('Overview', 'plugin_domain'),
                        'content' => '<p>' . $myhelp . '</p>',
                        ));
                    return $contextual_help;
                } 
    
          }
    }
  
} 

function findip()
{

    $ip = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $ip = trim($ip);

    if (!empty($ip))
        return $ip;
    else
        return 'unknow';
        
 }
 
 function whitelisted($ip, $amy_whitelist)
{

    for ($i = 0; $i < count($amy_whitelist); $i++) {


        if (trim($amy_whitelist[$i]) == $ip)
            return 1;

    }
    return 0;

}



function successful_login($user_login)
{

    global $amy_whitelist;
    global $my_radio_all_logins;
    global $ip;
    global $admin_email;

    
    if (whitelisted($ip, $amy_whitelist) and $my_radio_all_logins <> 'Yes' )
        { return 1;}

            
        $dt = date("Y-m-d H:i:s");
        $dom = $_SERVER['SERVER_NAME'];
    
        $msg = 'This email was sent from your website '.$dom. ' by the AntiHacker plugin. <br> ';
    
        $msg .= 'Date : ' . $dt . '<br>';
        $msg .= 'Ip: ' . $ip . '<br>';
        $msg .= 'Domain: ' . $dom . '<br>';
        $msg .= 'Role: ' . $user_login;
        $msg .= '<br>';
        $msg .= 'Add this IP to your withelist to stop this email and change your Notification Settings.';  
        
        $email_from = 'wordpress@'.$dom;
    
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
        $headers .= "From: ".$email_from. "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        $to = $admin_email;
        $subject = 'Login Successful at: '.$dom;
    
        wp_mail( $to, $subject, $msg, $headers, '' );
    
  
    
    return 1;

}


function ah_activated()
{
    
     $ip = findip() ;
     global $my_whitelist;
    
    if(is_admin())
    {
        
        if (empty($my_whitelist)) {
            
            
            if ( get_option( 'my_whitelist' ) !== false ) {
                
               $return = update_option('my_whitelist', $ip);
            }
            else
           {
                $return = add_option('my_whitelist', $ip);
           }
    
        }
       
        
     }
}

    
    
function email_display()
    { ?>
        <!-- <INPUT TYPE=CHECKBOX NAME="my_captcha">Yes, i'm a human! -->
        My Wordpress user email:
        <br />
        <input type="text" id="myemail" name="myemail" value="" placeholder="" size="100" />
        <br />
        <?     
    }
    
    
    
    
    
function failed_login($user_login)
{

    global $amy_whitelist;
    global $my_checkbox_all_failed;
    global $ip;
    global $admin_email;
    
    
    if (whitelisted($ip, $amy_whitelist) and $my_checkbox_all_failed <> '1' )
        { return;}
    
            
        $dt = date("Y-m-d H:i:s");
        $dom = $_SERVER['SERVER_NAME'];
    
        $msg = 'This email was sent from your website '.$dom. ' by the AntiHacker plugin. <br> ';
    
        $msg .= 'Date : ' . $dt . '<br>';
        $msg .= 'Ip: ' . $ip . '<br>';
        $msg .= 'Domain: ' . $dom . '<br>';
        $msg .= 'Role: ' . $user_login;
        $msg .= '<br>';
        $msg .= 'Failed login'; 
 
        
        $email_from = 'wordpress@'.$dom;
    
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
        $headers .= "From: ".$email_from. "\r\n" . 'Reply-To: ' . $user_login . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        $to = $admin_email;
        $subject = 'Failed Login at: '.$dom;
    
        wp_mail( $to, $subject, $msg, $headers, '' );
    
   
    
    return;

}

?>
