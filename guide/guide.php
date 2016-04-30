<?php

/**
 * @author William Sergio Minossi
 * @copyright 2016
 */



$ah_help = '
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
1)&nbsp;
Open the Plugin  General Settings Tab and add your IP address to the whitelist 
field (if necessary) and click save changes. You can see your current&nbsp; IP address at that 
page.</p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
2)
<strong style="box-sizing: inherit; border: 0px; font-family: inherit; font-size: 16px; font-style: inherit; font-weight: 600; margin: 0px; outline: 0px; padding: 0px; vertical-align: baseline;">
What Happens if someone not whitelisted try to login (or i change my ip)?</strong></p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
Your login page will request your wordpress user email and will send to you one 
alert email someone not whitelisted just made login. If the email is correct, the 
login go through. Then, by security, not show your wordpress user email at your 
page.</p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
To avoid receive the alert email, just add your IP to whitelist. Please, read 
above (1).</p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
The email alert will be send to your wordpress user email. You can change this 
email by click over the tab <b><font size="3">email settings</font></b> at the plugin management page.</p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
3)
If you forget your wordpress email, just to confirm,&nbsp; you can click over the<span class="Apple-converted-space">&nbsp;</span><em style="box-sizing: inherit; border: 0px; font-family: inherit; font-size: 16px; font-style: italic; font-weight: inherit; margin: 0px; outline: 0px; padding: 0px; vertical-align: baseline;">Forgot 
the password</em><span class="Apple-converted-space">&nbsp;</span>link at wordpress 
login page and they will send you one email.</p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
4)
If necessary, (you are unable to login) you can remove this plugin by FTP.&nbsp;Go to 
folder: wp-content/plugins/ and remove the folder AntiHacker with all files.</p>
<p style="box-sizing: inherit; border: 0px; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-style: normal; font-weight: normal; margin: 0px 0px 1.6842em; outline: 0px; padding: 0px; vertical-align: baseline; color: rgb(71, 71, 71); font-variant: normal; letter-spacing: normal; line-height: 31.9998px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; ">
5) Update your\'s Email Alerts at Notification Settings. (Alerts about failed logins and succesfful logins)</p>
';

?>
