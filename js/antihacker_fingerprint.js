jQuery(document).ready(function($) {
    var $wsize = window.screen ? screen.width : 0;

    jQuery.ajax({
        url: ajaxurl,
        data: {
            'action': 'antihacker_grava_fingerprint',
            'fingerprint': $wsize
        },
        success: function(data) {
            // This outputs the result of the ajax request
            //console.log(data);
        },
        error: function(errorThrown) {
            console.error("AJAX request failed:", errorThrown);
        }
    });  
});
