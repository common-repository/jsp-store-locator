

    jQuery(document).ready(function () {

        jQuery('#import_export_form').submit(function () {

            var btn = jQuery(this).find("input[type=submit]:focus" ).val();

            if(btn=="Import"){

                jQuery("#import_or_export").val("Import");

            }

            if(btn=="Export"){

               jQuery("#import_or_export").val("Export");

            }     

            //var data = jQuery(this).serializeArray();

            var myForm = document.getElementById('import_export_form');



            var data = new FormData(myForm);

            //jQuery("#import_export_form").serializeArray();

            event.preventDefault();

            jQuery.ajax({

                        url: PT_Ajax.ajaxurl,

                        type: 'POST',

                        data:  data,

                        dataType: "json",

                        cache: false,

                        processData: false,

                        contentType: false,                        

                        success: function (response) {

                            console.log(response);

                               // if(response.action=="import"){

                               //  jQuery("#import_msg").text(response.rows+" are inserted into database");

                               // } 

                                alert();



                           }

                      });

        });

    });



