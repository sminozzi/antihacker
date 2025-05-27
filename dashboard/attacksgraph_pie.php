<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
include("calcula_stats_pie.php");


$ah_total = 0;
for($i = 0; $i < count($antihacker_results10); $i++)
{
   $ah_total = $ah_total + $antihacker_results10[0]['brute'];
   $ah_total = $ah_total + $antihacker_results10[0]['firewall'];
   $ah_total = $ah_total + $antihacker_results10[0]['enumeration'];
   $ah_total = $ah_total + $antihacker_results10[0]['plugin'];
   $ah_total = $ah_total + $antihacker_results10[0]['theme'];
   $ah_total = $ah_total + $antihacker_results10[0]['false_se'];
   $ah_total = $ah_total + $antihacker_results10[0]['tor'];
   $ah_total = $ah_total + $antihacker_results10[0]['noref'];
   $ah_total = $ah_total + $antihacker_results10[0]['blank'];
   $ah_total = $ah_total + $antihacker_results10[0]['tools'];
   $ah_total = $ah_total + $antihacker_results10[0]['rate'];
}

if($ah_total < 1 ){
    esc_attr_e("Just give us a little time to collect data so we can display it for you here.","stopbadbots");
    return;
}



  echo '<script type="text/javascript">';
  echo 'var antihacker_pie = [';
  echo '{label: "Brute Force Login", data: '.esc_attr($antihacker_results10[0]['brute']).', color: "#005CDE" },';
  echo '{label: "Firewall", data: '.esc_attr($antihacker_results10[0]['firewall']).', color: "#00A36A" },';
  echo '{label: "User Enumeration", data: '.esc_attr($antihacker_results10[0]['enumeration']).', color: "#7D0096" },';



if(!empty($antihacker_checkversion))
{
  echo '{label: "Plugin Vulnerabilities", data: '.esc_attr($antihacker_results10[0]['plugin']).', color: "#9EEFA9" },';
  echo '{label: "Theme Vulnerabilities", data: '.esc_attr($antihacker_results10[0]['theme']).', color: "#DE000F" },';
  echo '{label: "False Search Engine", data: '.esc_attr($antihacker_results10[0]['false_se']).', color: "#ED7B00" },';
  echo '{label: "From Tor", data: '.esc_attr($antihacker_results10[0]['tor']).', color: "#000000" },';

  echo '{label: "Post Without Referrer", data: '.esc_attr($antihacker_results10[0]['noref']).', color: "#CCCCCC" },';
  echo '{label: "Blank User Agent", data: '.esc_attr($antihacker_results10[0]['blank']).', color: "#fcafaf" },';
  echo '{label: "HTTP Tools", data: '.esc_attr($antihacker_results10[0]['tools']).', color: "#768ced" },';
  echo '{label: "Rate Limit Exceeded", data: '.esc_attr($antihacker_results10[0]['rate']).', color: "#ede276" },';

}
else
{
  echo '{label: "Disabled - Plugin Vulnerabilities", data: '.esc_attr($antihacker_results10[0]['plugin']).', color: "#9EEFA9" },';
  echo '{label: "Disabled - Theme Vulnerabilities", data: '.esc_attr($antihacker_results10[0]['theme']).', color: "#DE000F" },';
  echo '{label: "Disabled - False Search Engine", data: '.esc_attr($antihacker_results10[0]['false_se']).', color: "#ED7B00" },';
  echo '{label: "Disabled - From Tor", data: '.esc_attr($antihacker_results10[0]['tor']).', color: "#000000" },';

  echo '{label: "Disabled - Post Without Referrer", data: '.esc_attr($antihacker_results10[0]['noref']).', color: "#CCCCCC" },';
  echo '{label: "Disabled - Blank User Agent", data: '.esc_attr($antihacker_results10[0]['blank']).', color: "#fcafaf" },';
  echo '{label: "Disabled - HTTP Tools", data: '.esc_attr($antihacker_results10[0]['tools']).', color: "#768ced" },';
  echo '{label: "Disabled - Rate Limit Exceeded", data: '.esc_attr($antihacker_results10[0]['rate']).', color: "#ede276" },';

}
  echo '];';
?>
var antihacker_pie_options = {
    series: {
        pie: {
            show: true,
            innerRadius: 0.5,
            label: {
                show: false,
            }
        }
    }
};
jQuery(document).ready(function () {
  jQuery.plot(jQuery("#antihacker_flot-placeholder_pie"), antihacker_pie, antihacker_pie_options);
});
</script>

<div id="antihacker_flot-placeholder_pie" style="width:400px;height:125px;margin:0 auto"></div>