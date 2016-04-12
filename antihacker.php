<?php /*
Plugin Name: AntiHacker 
Plugin URI: http://antihackerplugin.com
Description: Anti Hacker Plugin. Restrict access to login page to whitelisted IP addresses.
Version: 1.1
Text Domain: anti-hacker
Domain Path: /lang
Author: Bill Minozzi
Author URI: http://billminozzi.com
License:     GPL2
Copyright (c) 2015 Bill Minozzi

 
Antihacker is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Antihacker is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Antihacker. If not, see {License URI}.


Permission is hereby granted, free of charge subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/




if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
    

define('AHPATH', plugin_dir_path(__file__) );

    
// Add settings link on plugin page
function antihacker_plugin_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=anti-hacker">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'antihacker_plugin_settings_link' );


require_once (AHPATH . "settings/load-plugin.php");
require_once (AHPATH . "functions/functions.php");

$my_whitelist = trim(get_option('my_whitelist'));
$amy_whitelist = explode(PHP_EOL, $my_whitelist);
$ip = trim(findip());



$admin_email = get_option( 'my_email_to' ); 
    
;
require_once (AHPATH . "settings/options/plugin_options_tabbed.php");


// clean email field if is wrong...
if( ! empty($admin_email))
    if ( ! is_email($admin_email)) {

        $admin_email = '';
        update_option('my_email_to', '');

    }



if (! whitelisted($ip, $amy_whitelist)) {
    
   
    add_action('login_form', 'email_display');

    add_action('wp_authenticate_user', 'validate_email_field', 10, 2);

    function validate_email_field($user, $password)
    {
        global $myemail;

        if (!is_email($myemail))
            return new WP_Error('wrong_email', 'Please, fill out the email field!');
        else
           {
            
                $args = array(
                );
                
                // The Query
                $user_query = new WP_User_Query( array ( 'orderby' => 'registered', 'order' => 'ASC' ) );
                // User Loop
                if ( ! empty( $user_query->results ) ) {
                	foreach ( $user_query->results as $user ) {
                        
                        if(strtolower(trim($user->user_email)) == $myemail )
                                 return $user;
    
                	}
                } else {
                	// echo 'No users found.';
                }
                   
                    return new WP_Error( 'wrong_email', 'email not found!');
     
            
           } 
            
            
            return $user;

    }
    


} /* endif if (! whitelisted($ip, $my_whitelist)) */


register_activation_hook( __FILE__, 'ah_activated' );
add_action('wp_login', 'successful_login');

 ?>