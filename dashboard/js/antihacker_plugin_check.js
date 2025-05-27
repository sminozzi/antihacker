jQuery(document).ready(function($) {
   // console.log('Carregou Verificar...');
    function anti_hacker_performPluginCheckAndSendEmail() {
        // console.log('Verificar...');
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'antihacker_check_plugins_and_display_results',
            },
            success: function(response) {
                if (response.success) {
                    // console.log('successful:', response.data);
                    $('#notifications-tab').hide();
                    $("#antihacker_spinner").hide();
                    $('#result-container').html(response.data);
                    // $('#result-container').show();
                } else {
                    console.error('failed:', response.data);
                    $('#notifications-tab').hide();
                    $("#antihacker_spinner").hide();
                    var msg = "It was not possible to establish a connection with the WordPress site at the moment. Please try again later."
                    $('#result-container').html(msg);
                }
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    }
    // Vincula a função ao clique do botão
    $('#check-plugins-button').on('click', function() {
        // Call the function with step 1 when the button is clicked
       $('#notifications-tab').hide();
       $('#result-container').show();
       // $('#notifications-tab').addClass('loading');
       anti_hacker_performPluginCheckAndSendEmail();
       //console.log('clicked...');
    });
}); // jquery
