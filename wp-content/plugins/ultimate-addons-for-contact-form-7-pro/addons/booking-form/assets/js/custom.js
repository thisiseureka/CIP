/**
 * Add to cart product then redirect to checkout
 * 
 * @since 1.0
 */
          
(function($) {	
    $(document).ready(function(){

        // Initiate date in Contact form 7 
        $('.wpcf7-form').each(function(){
            $this = $(this);

            // Get form id
            var form_id = $this.find('input[name="_wpcf7"]').val(); 

            var date_id = $this.find('.bf-form-input-date').attr('id');
            var date_data = $this.find('.bf-form-input-date').attr('date-data'); 
            if (typeof date_data !== "undefined") {
                date_data = JSON.parse(date_data);  

                // Check if date is enable
                if(date_data.bf_enable == true){
    
                    // Get time id and time data
                    var time_id = $this.find('.bf-form-input-time').attr('id');
                    var time_data = $this.find('.bf-form-input-time').attr('time-data');
                    
                    // Initiate time in Contact form 7
                    var selectedDates = new Date(); 
                    var recent_date = ''+selectedDates.getFullYear()+'-'+( '0' + (selectedDates.getMonth()+1) ).slice( -2 )+'-'+( '0' + (selectedDates.getDate()) ).slice( -2 )+'';   
                    
                    // Disable Duplicate Booking Form
                    var store_data = date_data.store_data;  
                    if (typeof store_data === "undefined" ||  store_data == false) {
                        store_data = '';
                    } 
    
                    // Initiate date Picker IN contact form 7
                    var data_pickr = {
                        enableTime: false,
                        inline: true,
                        dateFormat: "Y-m-d",

                        locale: {
                            "firstDayOfWeek": 1 // start week on Monday
                        }, 
                    
                        mode: date_data.date_mode_front,
                        minDate: "today",
                        disable : [
                            function(date) { 
                                return ( 
                                    date.getDay() == date_data.disable_day_0 || 
                                    date.getDay() == date_data.disable_day_1 || 
                                    date.getDay() == date_data.disable_day_2 || 
                                    date.getDay() == date_data.disable_day_3 || 
                                    date.getDay() == date_data.disable_day_4 || 
                                    date.getDay() == date_data.disable_day_5 || 
                                    date.getDay() == date_data.disable_day_6 
                                ); 
                            },
                                                    
                        ],
                        onChange: function(selectedDates, dateStr, instance) {      
                            // Date timepicker Function called   
                            day_base_timepicker($this, dateStr, store_data, time_data, time_id);  
                            $('#'+date_id).trigger('keyup'); 
                        },
                    }
                    
                    // datepicker disable date start and end
                    if(date_data.disabled_start_date != ''){
                        var disabled_start_date = date_data.disabled_start_date;
                        var disabled_end_date = date_data.disabled_end_date;
                        data_pickr.disable.push({ from: disabled_start_date, to: disabled_end_date });
                                                        
                    }
    
                    // datepicker disable specific date
                    if (date_data.disabled_specific_date) {
                        var disabled_specific_date = date_data.disabled_specific_date; 
                        data_pickr.disable = $.merge(disabled_specific_date, data_pickr.disable); 
                    }
    
                    // datepicker disable specific day
                    if(date_data.bf_allowed_date == 'range'){
                        if(date_data.min_date != ''){
                            data_pickr.minDate = date_data.min_date;
                        }else{
                            data_pickr.minDate = 'today';
                        }
                        if(date_data.max_date != ''){
                            data_pickr.maxDate = date_data.max_date;
                        }else{
                            data_pickr.maxDate = '';
                        }
                        
                    }else if (date_data.bf_allowed_date == 'specific') {
                        data_pickr.enable = date_data.allowed_specific_date
                    } 
    
                    // Initiate date Picker IN contact form 7
                    $("#"+date_id).flatpickr(data_pickr); 
    
                    // Function called
                    day_base_timepicker($this, recent_date, store_data, time_data, time_id);   
    
    
                    //  Add to cart product then redirect to checkout
                    if ( typeof date_data.bf_woo !== 'undefined' && date_data.bf_woo == '1'  ) {  
                        document.addEventListener('wpcf7mailsent', function (event) {
                            if (event.detail.status == 'mail_sent' ) {
                                
                                // Get booking date and time value
                                var booking_date = jQuery("#"+date_id).val();
                                var booking_time = jQuery("#"+time_id).val();
                    
                                var $product = [];
                    
                                if (date_data.bf_product == 'exist') {
                                    var data = {
                                        action: 'uacf7_bf_ajax_add_to_cart_product',
                                        bf_product: date_data.bf_product,
                                        product_id: date_data.bf_product_id,
                                        booking_date: booking_date,
                                        booking_time: booking_time,
                                    };
                                } else if (date_data.bf_product == 'custom'){
                                    var data = {
                                        action: 'uacf7_bf_ajax_add_to_cart_product',
                                        bf_product: date_data.bf_product,
                                        product_name: date_data.bf_product_name,
                                        product_price: date_data.bf_product_price,
                                        booking_date: booking_date,
                                        booking_time: booking_time,
                                    };
                                }
                    
                                    jQuery.ajax({
                                        url: bfcf7_pro_object.ajaxurl,
                                        type: 'post',
                                        data: data,
                                        success: function (data) { 
                                            location.href = bfcf7_pro_object.checkout_page;
                                        },
                                        error: function (jqXHR, exception) {
                                            var error_msg = '';
                                            if (jqXHR.status === 0) {
                                                var error_msg = 'Not connect.\n Verify Network.';
                                            } else if (jqXHR.status == 404) {
                                                var error_msg = 'Requested page not found. [404]';
                                            } else if (jqXHR.status == 500) {
                                                var error_msg = 'Internal Server Error [500].';
                                            } else if (exception === 'parsererror') {
                                                var error_msg = 'Requested JSON parse failed.';
                                            } else if (exception === 'timeout') {
                                                var error_msg = 'Time out error.';
                                            } else if (exception === 'abort') {
                                                var error_msg = 'Ajax request aborted.';
                                            } else {
                                                var error_msg = 'Uncaught Error.\n' + jqXHR.responseText;
                                            }
                                            alert(error_msg);
                                        }
                                    });
                            }
                    
                        }, false); 
                        
                    };
                }
            } 
            

            
        });

        // Time picker function based on day
        function day_base_timepicker($this, dateStr, store_data, time_data, time_id){ 
             
            time_data = JSON.parse(time_data);
            
            // console.log(time_data);
            var selectedDates = new Date(dateStr); 
            var min_time = time_data.min_time; 
            var max_time = time_data.max_time;
            var disable_time_text = [];
            if (typeof store_data === "undefined" ||  store_data == false) {
                store_data = '';
            }else{
                store_data = JSON.parse(store_data);
            }
            var disable_time = store_data[dateStr]; 
            if( typeof disable_time !== "undefined" &&  disable_time.length > 0 && disable_time != false){ 
                disable_time_text = disable_time;
                disable_time_text.push([time_data.from_dis_time , time_data.to_dis_time ]);
            }else{
                disable_time_text.push([time_data.from_dis_time , time_data.to_dis_time ]);
            }  
             
            if(time_data.bf_allowed_time == 'day'){
                if(time_data.time_day_1 == selectedDates.getDay() && selectedDates.getDay() != 0){ min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
                if(time_data.time_day_2 == selectedDates.getDay() && selectedDates.getDay() != 0){  min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
                if(time_data.time_day_3 == selectedDates.getDay() && selectedDates.getDay() != 0){ min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
                if(time_data.time_day_4 == selectedDates.getDay() && selectedDates.getDay() != 0){ min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
                if(time_data.time_day_5 == selectedDates.getDay() && selectedDates.getDay() != 0){ min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
                if(time_data.time_day_6 == selectedDates.getDay() && selectedDates.getDay() != 0){ min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
                if(time_data.time_day_0 == selectedDates.getDay() && selectedDates.getDay() == 0){ min_time =  time_data.min_day_time; max_time = time_data.max_day_time;}
            }else if(time_data.bf_allowed_time == 'specific'){
                var specific_date = time_data.specific_date_time.split(', '); 
                if($.inArray(dateStr, specific_date) != '-1'){ 
                        min_time =  time_data.min_day_time; max_time = time_data.max_day_time;
                } 
            } 
 
            var timepicker = {
                appendTo: '#'+time_id,
                className: "bf-time-picker",
                disableTextInput: true,
                minTime: min_time,
                maxTime: max_time, 
                timeFormat: time_data.time_format_front,
                disableTimeRanges: disable_time_text,
            };

            if (time_data.time_one_step != '' && time_data.time_two_step !='') {
                timepicker.step = function(i) {
                    return (i%2) ? time_data.time_one_step : time_data.time_two_step;
                }
            } else if (time_data.time_one_step !='' && time_data.time_two_step == '') {
                timepicker.step = time_data.time_one_step;
            }

            $('#'+time_id).attr('data-time-min', time_data.min_time);
            $('#'+time_id).attr('data-time-max', time_data.max_time); 
            // Initiate time in Contact form 7
            $('#'+time_id).parent().find('.bf-time-picker').remove();
            $('#'+time_id).timepicker(timepicker);
            $('#'+time_id).trigger("click");
            
        }
    });
})(jQuery);
 
 
// if ( typeof bf_woo !== 'undefined' && bf_woo == '1'  ) {  
//     // if(bf_woo == '1' ){
//         document.addEventListener('wpcf7mailsent', function (event) {
//             if (event.detail.status == 'mail_sent' ) {
    
//                 var booking_date = jQuery("#bf-form-input-date").val();
//                 var booking_time = jQuery("#bf-form-input-time").val();
    
//                 var $product = [];
    
//                 if (bf_product == 'exist') {
//                     var data = {
//                         action: 'uacf7_bf_ajax_add_to_cart_product',
//                         bf_product: bf_product,
//                         product_id: bf_product_id,
//                         booking_date: booking_date,
//                         booking_time: booking_time,
//                     };
//                 } else if (bf_product == 'custom'){
//                     var data = {
//                         action: 'uacf7_bf_ajax_add_to_cart_product',
//                         bf_product: bf_product,
//                         product_name: bf_product_name,
//                         product_price: bf_product_price,
//                         booking_date: booking_date,
//                         booking_time: booking_time,
//                     };
//                 }
    
//                     jQuery.ajax({
//                         url: bfcf7_pro_object.ajaxurl,
//                         type: 'post',
//                         data: data,
//                         success: function (data) { 
//                             location.href = bfcf7_pro_object.checkout_page;
//                         },
//                         error: function (jqXHR, exception) {
//                             var error_msg = '';
//                             if (jqXHR.status === 0) {
//                                 var error_msg = 'Not connect.\n Verify Network.';
//                             } else if (jqXHR.status == 404) {
//                                 var error_msg = 'Requested page not found. [404]';
//                             } else if (jqXHR.status == 500) {
//                                 var error_msg = 'Internal Server Error [500].';
//                             } else if (exception === 'parsererror') {
//                                 var error_msg = 'Requested JSON parse failed.';
//                             } else if (exception === 'timeout') {
//                                 var error_msg = 'Time out error.';
//                             } else if (exception === 'abort') {
//                                 var error_msg = 'Ajax request aborted.';
//                             } else {
//                                 var error_msg = 'Uncaught Error.\n' + jqXHR.responseText;
//                             }
//                             alert(error_msg);
//                         }
//                     });
//             }
    
//         }, false);
//     // }
    
// };