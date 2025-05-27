/*
 * @ Author: Bill Minozzi
 * @ Copyright: 2021 www.BillMinozzi.com
 * @ Modified time: 2021-29-11 09:17:42
 * */
jQuery(document).ready(function ($) {

    

    // $(".spinner").addClass("is-active");
    $(".spinner").hide();


    /*
    var ah_dismiss = antihacker_getCookie("ah_dismiss");

   if (ah_dismiss !== undefined){ 
    //  console.log("Found cookie " + ah_dismiss);
    }
   */   
    

    // console.log('vendor-ah');


    $("#antihacker-vendor-ok").click();
    $("#TB_title").hide();

    if (!$("#TB_window").is(':visible')) {
        $("#antihacker-vendor-ok").click();
        // console.log('auto click');
    }


    $("*").click(function (ev) {


      //  ev.preventDefault();

        //  alert('2');
        // console.log('click');
        // console.log(ev.target.id);
         //$(this).attr("class");
        // console.log($(this).attr("class"));





        if (ev.target.id == "bill-vendor-button-ok-ah") {
         //    console.log("Learn More");
            window.location.replace("http://antihackerplugin.com/premium//");
        }


        if (ev.target.id == "bill-vendor-button-again-ah") {
           //  console.log("watch again");
           // $("#bill-banner-ah").get(0).play();
            $("#bill-banner-ah").get(0).play().catch(function () {
                // console.log("Fail to Play.");
                self.parent.tb_remove();
                $('#TB_window').fadeOut();
                $("#TB_closeWindowButton").click();
            });

        }

        if ( ev.target.id == "bill-vendor-button-dismiss-ah" || $(this).attr("class") == "tb-close-icon"  ) {
            // event.preventDefault()
             $("#bill-banner-ah").hide();
            /*  $("#bill-banner-ah").html("Please, wait...") */
             
             $("#antihacker-wait").show();
             $("#antihacker-wait").addClass("is-active");

             console.log('clicked Dimiss !!!!!!');
             antihacker_setCookie('ah_dismiss', '1', '1');

             $("#bill-vendor-button-dismiss-ah").hide();
             $("#bill-vendor-button-again-ah").hide();
             $("#bill-vendor-button-ok-ah").hide();

             $(".spinner").addClass("is-active");
             $(".spinner").show();
            jQuery.ajax({
                method: 'post',
                url: ajaxurl,
                data: {
                    action: "antihacker_antihacker_bill_go_pro_hide2"
                },
                success: function (data) {
                    console.log('OK-dismissed!!!');
                    setTimeout(myFunction, 3000);
                    // return data;
                    
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('error' + errorThrown + ' ' + textStatus);
                }
            });
            //console.log("fechar");

            // setTimeout(myFunction, 3000);
            function myFunction() {
                self.parent.tb_remove();
                $('#TB_window').fadeOut();
                $("#TB_closeWindowButton").click();
            }

        }

    }); // click


    if ($('#bill-banner-ah').length) {
        //  $("#bill-banner-ah").get(0).play();
        $("#bill-banner-ah").get(0).play().catch(function () {
            // console.log("Fail to Play.");
            self.parent.tb_remove();
            $('#TB_window').fadeOut();
            $("#TB_closeWindowButton").click();
        });
    }

    var altura = $("#TB_window").height();


    $("#TB_window").height(260);

    /*var altura = $("#TB_window").height();
    console.log(altura);
    */

    /* $("#TB_window").width(550); */
    $("#TB_window").addClass("bill_TB_window");

/*
    setTimeout(loadAfterTime, 5000)



    function loadAfterTime() { 
    // code you need to execute goes here. 
       $("#TB_window").css({
        height: "320px !important"
      });


    var altura2 = $("#TB_window").height();

       console.log(altura2);
       console.log('Hi2');
    }
*/

function antihacker_setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  
  function antihacker_getCookie(cookieName) {
    let cookie = {};
    document.cookie.split(';').forEach(function(el) {
      let [key,value] = el.split('=');
      cookie[key.trim()] = value;
    })
    return cookie[cookieName];
  }

});