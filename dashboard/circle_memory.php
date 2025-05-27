<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 08:45:18
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<style>
    prg-cont.canvas {
        width: 125px !important;
    }
</style>
<center>
    <div class="prg-cont rad-prg" id="indicatorContainer2" style="width:125px; height:125px"></div>
</center>
<?php
//$initValue = 62;
//
?>
<script>
    jQuery('#indicatorContainer2').radialIndicator({
        barColor: 'red',
        /*  '#87CEEB', */
        barWidth: 10,
        initValue: <?php echo esc_attr($initValue); ?>,
        roundCorner: true,
        percentage: true,
        radius: 50,
        barWidth: 10,
        barColor: {
            0: '#33CC33',
            60: '#33CC33',
            61: '#FFD700',
            75: '#FF0000',
            100: '#FF0000'
        },
    });
</script>