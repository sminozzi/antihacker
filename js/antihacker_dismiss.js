jQuery(document).ready(function ($) {
   // console.log('loaded dismiss AH!');
   // jQuery(document).on('click', '#antihacker_an2 .notice-dismiss', function( event ) {

    
    jQuery('*').click(function( event ) {
       // console.log(event.target.nodeName); 
       // console.log(event.target.id); 
       // console.log(jQuery(this).closest('div').attr('id'))
     });
     
    // jQuery('#antihacker_an2 .notice-dismiss').click(function( event ) {
    jQuery('#antihacker_an2').click(function( event ) {

        //alert('1');
        //console.log('OK111111!');
        //console.log(ajaxurl);

        antihacker_setCookie('antihacker_dismiss_language', '1', '1');

        /*
        jQuery.ajax({
            type:"post",
            url:ajaxurl,
            data:{
            action:'antihacker_dismiss_notice2'
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
        */



        jQuery.ajax({
            url: ajaxurl,
            data: {
              action : 'antihacker_dismiss_notice2',
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            /*
            error: function (errorThrown) {
                // console.log(errorThrown);
                console.log('error');
            }
            */
            error: function (request, status, error) {
                console.log(request.responseText);
                console.log(error);
                console.log(status);
            }
        });

    });

    function antihacker_setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }


});