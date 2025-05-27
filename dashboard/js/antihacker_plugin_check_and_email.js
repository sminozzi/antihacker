jQuery(document).ready(function($) {


    console.log('Carregou Verificar...');



    function ah_performPluginCheckAndSendEmail(step) {

        console.log('Verificar...');


        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: '$antihacker_check_plugins_and_display_results',

                step: step
            },
            success: function(response) {
                if (response.success) {
                    console.log('Step ' + step + ' successful:', response.data);

                    // If step 1 is successful, call step 2
                    if (step === 1) {
                        ah_performPluginCheckAndSendEmail(2);
                    }
                } else {
                    console.error('Step ' + step + ' failed:', response.data);
                }
            },
            error: function(error) {
                console.error('AJAX error:', error);
            }
        });
    }

    // Vincula a função ao clique do botão
    jQuery('#check-plugins-button').on('click', function() {
        // Call the function with step 1 when the button is clicked
       ah_performPluginCheckAndSendEmail(1);
       console.log('clicou');
    });

}); // jquery
