<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
include("calcula_stats_pie2.php");


if(!isset($antihacker_results10[0]['Bots']) or ! isset($antihacker_results10[0]['Humans'])) 
   return;




  echo '<script type="text/javascript">';
  echo 'var antihacker_pie2 = [';

  $label = esc_attr__("Bots","antihacker"); 
  echo '{label: "'.esc_attr($label).'", data: '.esc_attr($antihacker_results10[0]['Bots']).', color: "#FF0000" },';
  $label = esc_attr__("Humans","antihacker"); 
 echo '{label: "'.esc_attr($label).'", data: '.esc_attr($antihacker_results10[0]['Humans']).', color: "#00A36A" }';

echo '];';


?>


function antihacker_labelFormatter(label, series) {
  return "<div style='font-size:15px;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
};

var antihacker_pie2_options = {
    series: {
        pie: {
            show: true,
            innerRadius: 0.4,
            label: {
                show: true,
                formatter: antihacker_labelFormatter,
                
            }
        }
    },

    legend: {
    show: false,

  }

};
jQuery(document).ready(function () {
  jQuery.plot(jQuery("#antihacker_flot-placeholder_pie2"), antihacker_pie2, antihacker_pie2_options);
});
</script>
<div id="antihacker_flot-placeholder_pie2" style="width:250px;height:140px;margin:-20px 0 auto"></div>
