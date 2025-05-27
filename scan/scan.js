/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-15 09:17:42
 * @ Modified time: 2021-03-22 08:26:22
 * */
jQuery(document).ready(function ($) {

    // console.log('scan.js');

    $('html, body').scrollTop(0);

    $("#antihacker-scan-button_ok").hide();
    $(".spinner").addClass("is-active");
    $("#antihacker-resume-button").hide();

    $("#antihacker-pause-button").click(function () {
        $(".spinner").hide();
        $("#antihacker-scan-button_ok").hide();
        $("#antihacker-scan-cancel").hide();
        $("#antihacker-pause-button").hide();
        $("#antihacker-resume-button").show();
        $("#antihacker_scan_msg").html('Job paused. Click resume...');
        $('html, body').scrollTop(0);
    });

    $("#antihacker-resume-button").click(function () {
        $(".spinner").show();
        $("#antihacker-scan-button_ok").hide();
        $("#antihacker-scan-cancel").show();
        $("#antihacker-pause-button").show();
        $("#antihacker-resume-button").hide();
        $("#antihacker_scan_msg").html('Working... Please, wait...');
        $('html, body').scrollTop(0);
    });

    $("#antihacker-scan-cancel").click(function () {
        $("#antihacker-pause-button").hide();
        $(".spinner").addClass("is-active");
        $("#antihacker_wrap_trickbox").html("Job Aborted by the user.");
        $("#antihacker_scan_msg").html("Wait...Cleaning...");
        $("#antihacker-scan-cancel").hide();




        function sleep(milliseconds) {
            const date = Date.now();
            let currentDate = null;
            do {
                currentDate = Date.now();
            } while (currentDate - date < milliseconds);
        }
        var antihacker_nonce = $('#antihacker_scan_nonce').val();
        //console.log(antihacker_nonce);
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                'action': 'antihacker_truncate_scan_table',
                'antihacker_scan_nonce' : antihacker_nonce
            },
            success: function (data) {
                sleep(3000);
                //setTimeout(function () {
                console.log('Clean...');
                console.log(data);
                //self.parent.tb_remove();
                parent.location.reload(1);
            },
            error: function (xhr, status, error) {
                console.log('Ajax Error: '+error);
                console.log('Status: '+status);
                console.log('Error Status Code: '+xhr.status);
            },
            timeout: 10000
        });
        setTimeout(function () {
            //self.parent.tb_remove();
            parent.location.reload(1);
        }, 10000);
    });

    function antihacker_scan_run() {

        //         console.log('12345');
        var radValue = $(".speed:checked").val();
        var nonce = $('#antihacker_scan_nonce').val();

        if ($("#antihacker-scan-cancel").is(':visible') == false)
            return;

        if ($("#antihacker-scan-id").is(':visible')) {
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: {
                    'action': 'antihacker_ajax_scan',
                    'speed': radValue,
                    'security_nonce': nonce // Include your nonce here
                },
                success: function (data) {
                    // This outputs the result of the ajax request
                    console.log(data);

                    if (typeof (data) == 'string') {
                        var images_path = $('#images_path').val();

                        if (data.includes("step 1")) {
                            $('#antihacker_3steps').attr('src', images_path + 'steps1.png');
                        }
                        if (data.includes("step 2")) {
                            $('#antihacker_3steps').attr('src', images_path + 'steps2.png');
                        }
                        if (data.includes("step 3")) {
                            $('#antihacker_3steps').attr('src', images_path + 'steps3.png');
                        }
                    }


                    if (data == 'End of Job!') {
                        //self.parent.tb_remove();
                        //parent.location.reload(1);
                        $("#antihacker-scan-cancel").hide();
                        // $("#antihacker_scan_msg").html("End");
                        $("#antihacker-scan-button_ok").show();
                        setTimeout(function () {
                            // self.parent.tb_remove();
                            parent.location.reload(1);
                        }, 5000);
                        // $('#TB_window').click(tb_remove);
                    }
                    $("#antihacker_scan_msg").html(data);
                },
                error: function (xhr, status, error) {
                    console.log('Ajax Error: ' + error);
                    console.log('Status: ' + status);
                    console.log('Error Status Code: ' + xhr.status);


                },
                timeout: 40000
            });
        }
    }
    $("#antihacker-scan-ok").click(function () {

        // console.log('12----------345');

        $("#antihacker-scan-id").slideDown();
        $("#antihacker-scan-bkg").css("opacity","0.25");


        var radValue = $(".speed:checked").val();


        var $frequency = 40000;

        if (radValue == 'very_slow') {
            $frequency = 90000;
        }
        if (radValue == 'slow') {
            $frequency = 60000;
        }
        if (radValue == 'normal') {
            $frequency = 40000;
        }
        if (radValue == 'fast') {
            $frequency = 20000;
        }
        if (radValue == 'very_fast') {
            $frequency = 10000;
        }


        setInterval(antihacker_scan_run, $frequency);
       // antihacker_scan();

    });


});