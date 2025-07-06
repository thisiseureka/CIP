; (function ($) {
    'use strict';
    $(document).ready(function () {
        // Focus Intro Button
        $('.uacf7-conv-intro-button').focus();

        // Each Form Wrap
        $(".uacf7-conv-form-wrap").each(function () {
            var $this = $(this);
            var count = 1;
            var form_id = $this.closest('.wpcf7-form').find("input[name=_wpcf7]").val(); 

            var repeater_count = $(this).closest('.wpcf7-form').find('.uacf7-repeater-count').val();


            // Convertion form Intro
            uacf7_conv_form_animation($this.find('.uacf7-conv-single-intro.intro-first'));
            $this.find('.uacf7-conv-single-intro.intro-first .uacf7-conv-intro-button').on("click", function (e) {
                e.preventDefault();
                $(this).closest('.uacf7-conv-single-intro.intro-first').addClass('hide');
                $(this).closest('.uacf7-conv-single-intro.intro-first').hide();
                $this.find('.uacf7-conv-single-field').first().addClass('active');
                uacf7_conv_form_animation($this.find('.uacf7-conv-single-field').first());
                var current_step = $this.find('.uacf7-conv-single-field').first().attr('data-step');
                var prev_step = current_step - 1;
                $(".uacf7-conv-down").attr("data-current-step", current_step);
                $(".uacf7-conv-down").attr("data-prev-step", prev_step);
                $(".uacf7-conv-up").attr("data-current-step", current_step);
                $(".uacf7-conv-up").attr("data-prev-step", prev_step);
                $this.find('.uacf7-conv-up-down').show();
            });

            // Convertion form Intro Enter Key
            $this.find('.uacf7-conv-single-intro.intro-first').bind('keypress', function (e) {
                if (e.keyCode == 10 || e.keyCode == 13) {
                    $(this).closest('.uacf7-conv-single-intro.intro-first').addClass('hide');
                    $(this).closest('.uacf7-conv-single-intro.intro-first').hide();
                    $this.find('.uacf7-conv-single-field').first().addClass('active');
                }
            });

            // Convertion form fields
            var length = $this.find('.uacf7-conv-single-field').length

            $this.find('.uacf7-conv-single-field').each(function () {
                var intro_first = $this.find('.uacf7-conv-single-intro.intro-first');
                if (count == 1 && intro_first.length == 0) {
                    $(this).addClass('active')
                }
                if (count == length) {
                    $(this).find('.uacf7-conv-next').hide();
                    $(this).find('.wpcf7-spinner.uacf7-conv-ajax-loader').hide();

                }
                $(this).addClass('uacf7-conv-step' + count);
                $(this).attr('data-step', count);
                $(this).find('.uacf7-conv-next').attr('data-step', count);
                count++;
            });

            // Convertion product dropdown js support
            $this.find('.product-grid').each(function () {
                $(this).closest('.uacf7-conv-single-field-inner').css('height', '100%');
            });

            // Convertion form next button
            $(this).find(".uacf7-conv-next").on("click", function (e) {
                e.preventDefault();
                var current_step = $(this).attr("data-step");
                conversational_validation($this, current_step, form_id, repeater_count)


            });

            // Radio Button on selected Addclass
            $this.find('.wpcf7-form-control.wpcf7-radio, .wpcf7-form-control.wpcf7-checkbox').each(function () { 
                var $this = $(this);
                $this.find('input[checked="checked"]').parent().addClass('active');
                $this.find('input').on('change', function () {
                    // First, remove the class from all radio buttons 
                    $this.find('input[type="radio"]').parent().removeClass('active');
                    // Then, add the class to the checked radio button
                    if ($(this).is(':checked')) {
                        $(this).parent().addClass('active');
                    }else{
                        $(this).parent().removeClass('active');
                    }
                });
            });

            // Convertion form enter key submit 
            $this.find('.wpcf7-form-control-wrap input, .wpcf7-form-control-wrap textarea, .wpcf7-form-control-wrap select').keypress(function (e) {

                if (e.keyCode == 10 || e.keyCode == 13) {
                    if ($(this).closest(".uacf7-conv-single-field").find('.wpcf7-submit').attr('type') == "submit") {
                        $(this).closest(".uacf7-conv-single-field").find('.wpcf7-submit').trigger('click');
                    } else {
                        var current_step = $(this).closest(".uacf7-conv-single-field").attr("data-step");
                        conversational_validation($this, current_step, form_id, repeater_count);
                    }
                }

            });

            // Convertion form enter key submit
            $(this).closest('.wpcf7-form').bind('keypress', function (e) {

                if (e.keyCode == 10 || e.keyCode == 13) {

                    if (e.target.type == "submit") {
                        return
                    } else {
                        e.preventDefault();
                    }

                    var current_step = $(this).closest(".uacf7-conv-single-field").attr("data-step");
                    conversational_validation($this, current_step, form_id, repeater_count);
                }
            });

        });

        // Previous button
        $(".uacf7-conv-up").on("click", function (e) {
            e.preventDefault(); 
            var form_id = $(this).closest('.wpcf7-form').find("input[name=_wpcf7]").val();
            var $this = $(this).closest('.uacf7-conv-form-wrap');
            var repeater_count = $(this).closest('.wpcf7-form').find('.uacf7-repeater-count').val();

            var prev_step = $(this).attr("data-prev-step");

            if (prev_step == 0) {
                return false;
            }

            
            var current_step = $(this).attr("data-current-step");
            var data_complete_steps = JSON.parse($this.find('.uacf7-conv-up').attr('data-complete-steps'));
            prev_step = data_complete_steps.pop();
            $this.find('.uacf7-conv-up').attr('data-complete-steps', JSON.stringify(data_complete_steps));
            

            $('.uacf7-conv-single-field').removeClass('active');
            $('.uacf7-conv-step' + current_step).attr('data-step-status', 'completed');
            $('.uacf7-conv-single-field').addClass('hide');
            $('.uacf7-conv-step' + prev_step).removeClass('hide');
            $('.uacf7-conv-step' + prev_step).addClass('active');

            if ($('.uacf7-conv-step' + prev_step).parent().hasClass('uacf7_conditional') == true) {
                if ($('.uacf7-conv-step' + prev_step).parent().attr('data-condition') == 'true') {
                    $('.uacf7-conv-step' + prev_step).parent().removeClass('hide');
                }
            } 
            uacf7_conv_progress_bar($('.uacf7-conv-step' + prev_step));
            uacf7_conv_form_animation('.uacf7-conv-step' + prev_step, -200);
            $(this).attr("data-current-step", prev_step);
            $(this).attr("data-prev-step", prev_step - 1);
            $(".uacf7-conv-down").attr("data-current-step", prev_step);
            $(".uacf7-conv-down").attr("data-prev-step", prev_step - 1);
        });

        // Next button
        $(".uacf7-conv-down").on("click", function (e) {
            e.preventDefault();
            var form_id = $(this).closest('.wpcf7-form ').find("input[name=_wpcf7]").val();
            var repeater_count = $(this).closest('.wpcf7-form').find('.uacf7-repeater-count').val();
            var prev_step = $(this).attr("data-prev-step");
            var $this = $(this).closest('.uacf7-conv-form-wrap');
            var current_step = $(this).attr("data-current-step");
            var step_status = $('.uacf7-conv-step' + current_step).attr('data-step-status');
            $('.uacf7-conv-step' + current_step).find('.uacf7-conv-next').trigger('click');
            
        });





        // Convertion form validation
        function conversational_validation($this, current_step, form_id, repeater_count) {
            current_step; 
            var current_step_div = $this.find('.uacf7-conv-step' + current_step);
            var uacf7_current_step_fields = [];

            // if( $('.uacf7-conv-step'+current_step).find('.wpcf7-form-control-wrap').length > 0){

            // Get all fields in current step
            $('.uacf7-conv-step' + current_step).find('.wpcf7-form-control-wrap input').each(function () {
                var Value = jQuery('.wpcf7-form-control-wrap input[name="' + this.name + '"]:checked').val();
                if (jQuery(this).is("input[type='checkbox']")) {
                    if (typeof Value == 'undefined') {
                        var checkboxName = this.name.replace('[]', '');
                        
                        if($.inArray(checkboxName, uacf7_current_step_fields) === -1){ 
                            uacf7_current_step_fields.push(checkboxName);
                        }
                    }

                } else {
                    if (typeof Value == 'undefined') {
                        var checkboxName = this.name;
                        uacf7_current_step_fields.push(checkboxName);
                    }
                }

            });

            // Get all fields in current step : textarea
            $('.uacf7-conv-step' + current_step).find('.wpcf7-form-control-wrap textarea').each(function () {
                var field_name = this.name.replace('[]', '');
                uacf7_current_step_fields.push(field_name);
            });

            function uacf7_onlyUnique(value, index, self) {
                return self.indexOf(value) === index;
            }


            var validation_fields = [];  
            for (let i = 0; i < uacf7_current_step_fields.length; i++) {
                if(uacf7_current_step_fields[i] != ''){ 
                    var type = jQuery("[name="+uacf7_current_step_fields[i]+"]"); 
                    if(typeof type[0] === 'undefined' ){
                        type = jQuery('[name="'+uacf7_current_step_fields[i]+'[]"]'); 
                    } 
                    type = type[0].localName; 
                    // Repeater Validation issue 
                    if( typeof repeater_count != 'undefined' ){ 
                        var value = jQuery("[name="+uacf7_current_step_fields[i]+"]").val();    
                        var valuecheckbox = jQuery("[name="+uacf7_current_step_fields[i]+"][type='checkbox']");
                        if(value == '' || valuecheckbox.length > 0){  
                            validation_fields.push( ''+type+':'+uacf7_current_step_fields[i]+'' ); 
                        }
                    }else{
                        validation_fields.push( ''+type+':'+uacf7_current_step_fields[i]+'' ); 
                    } 
                }
                
            }   
            var uacf7_current_step_fields = uacf7_current_step_fields.filter(uacf7_onlyUnique);
            var fields_to_check_serialized = $(current_step_div).find(".wpcf7-form-control").serialize();
            if ($(current_step_div).find(".wpcf7-form-control[type='file']").length > 0) {
                $(current_step_div).find(".wpcf7-form-control[type='file']").each(function (i, n) {
                    fields_to_check_serialized += "&" + $(this).attr('name') + "=" + $(this).val();
                });
            }
            var data = fields_to_check_serialized +
                '&' + 'action=' + 'uacf7_conversational_step_validation' +
                '&' + 'form_id=' + form_id +
                '&' + 'validation_fields=' + validation_fields +
                '&' + 'current_fields_to_check=' + uacf7_current_step_fields +
                '&' + 'ajax_nonce=' + conversational_ajax.nonce;  
            
            // Ajax call for validation
            $.ajax({
                url: conversational_ajax.ajaxurl,
                type: 'post',
                data: data,
                beforeSend: function () {
                    jQuery($this).find(current_step_div).find('.uacf7-conv-ajax-loader').addClass('active');
                },
                success: function (response) {
                    jQuery($this).find(current_step_div).find('.uacf7-conv-ajax-loader').removeClass('active'); 
                    var json_result = (typeof response === 'object') ? response : JSON.parse(response);
                    var $form = jQuery('form');
                    clear_error_messages($form, current_step_div);

                    try {
                        if (json_result.is_valid) { 
                            var next_step = parseInt(current_step) + 1;
                            if ($this.find('.uacf7-conv-step' + next_step).parent().hasClass('uacf7_conditional') == true) {
                               
                                if ($this.find('.uacf7-conv-step' + next_step).parent().attr('data-condition') != 'true') { 
                                    
                                    $this.find('.uacf7-conv-step' + current_step).attr('data-step-status', 'completed');
                                    var next_step = $this.find('.uacf7_conditional[data-condition="true"]').find('.uacf7-conv-single-field[data-step-status="not-completed"]:first').attr('data-step'); 
                                    if(next_step == undefined){
                                        var nextStep = $this.find('.uacf7_conditional[data-condition="false"]:last').find('.uacf7-conv-single-field').attr('data-step');
                                        next_step = parseInt(nextStep) + 1;
                                    }
                                }  
                                uacf7_conversational_validation_success($this, current_step, next_step); 
                            }else{
                               uacf7_conversational_validation_success($this, current_step, null);  
                            }
                           
                        } else {
                            
                            if(json_result.invalid_fields == false){
                                var next_step = parseInt(current_step) + 1;
                                if ($this.find('.uacf7-conv-step' + next_step).parent().attr('data-condition') != 'true') { 
                                    
                                    $this.find('.uacf7-conv-step' + current_step).attr('data-step-status', 'completed');
                                    var next_step = $this.find('.uacf7_conditional[data-condition="true"]:last').find('.uacf7-conv-single-field[data-step-status="not-completed"]').attr('data-step');
                                   
                                    if(next_step == undefined){
                                        var nextStep = $this.find('.uacf7_conditional[data-condition="false"]:last').find('.uacf7-conv-single-field').attr('data-step');
                                        next_step = parseInt(nextStep) + 1;
                                    } 
                                } 
                             
                                uacf7_conversational_validation_success($this, current_step, next_step); 
                            }else{ 

                                $this.find('.uacf7-conv-step' + next_step).parent().removeClass('hide'); 
                                jQuery.each(json_result.invalid_fields, function (i, n) {
                                    var next_step = parseInt(current_step) + 1;


                                    jQuery(n.into, 'form').each(function () {

                                        jQuery('.wpcf7-form-control', this).addClass('wpcf7-not-valid');
                                        jQuery('[aria-invalid]', this).attr('aria-invalid', 'true');

                                        jQuery(this).parent().append('<span class="wpcf7-not-valid-tip" aria-hidden="true">' + n.message + '</span>');

                                    });
                                });
                            }
                            

                        }
                    } catch (e) {
                        console.log("error: " + e);
                    }
                }
            });
            // }
        }

        // Form Validation success function
        function uacf7_conversational_validation_success($this, current_step, next_step) {  
            // Push completed step into previous step
            if( typeof current_step != 'undefined'){
                var data_completed_step = JSON.parse($this.find('.uacf7-conv-up').attr('data-complete-steps'));
                data_completed_step.push(current_step);
                $this.find('.uacf7-conv-up').attr('data-complete-steps', JSON.stringify(data_completed_step));
            }
            if(next_step == null){ 
                var next_step = parseInt(current_step) + 1;
            }
            $this.find('.uacf7-conv-step' + current_step).removeClass('active');
            
            $this.find('.uacf7-conv-step' + current_step).addClass('hide');
            $this.find('.uacf7-conv-step' + next_step).removeClass('hide');
            $this.find('.uacf7-conv-step' + next_step).addClass('active');
            uacf7_conv_progress_bar($this.find('.uacf7-conv-step' + next_step)); 

            $(".uacf7-conv-step" + next_step + " input, .uacf7-conv-step" + next_step + " select, .uacf7-conv-step" + next_step + " textarea ").first().focus();

            if ($this.find('.uacf7-conv-step' + next_step).parent().hasClass('uacf7_conditional') == true) {
                if ($this.find('.uacf7-conv-step' + next_step).parent().attr('data-condition') == 'true') {
                    $this.find('.uacf7-conv-step' + next_step).parent().removeClass('hide');
                }
            }
            if ($this.find('.uacf7-conv-step' + current_step).parent().hasClass('uacf7_conditional') == true) {
                $this.find('.uacf7-conv-step' + current_step).parent().addClass('hide');
            }
            uacf7_conv_form_animation('.uacf7-conv-step' + next_step);

            if ($(".uacf7-conv-single-field").last().find('.uacf7-conv-single-intro-wrap .wpcf7-response-output').length === 0) {

                $(".uacf7-conv-single-field").last().find('.uacf7-conv-single-intro-wrap').append('<div class="wpcf7-response-output" aria-hidden="true"></div>');
            }


            $(".uacf7-conv-up").attr('data-current-step', next_step);
            $(".uacf7-conv-up").attr('data-prev-step', current_step);
            $(".uacf7-conv-down").attr('data-current-step', next_step);
            $(".uacf7-conv-down").attr('data-prev-step', current_step);
        }

        // clear error messages
        function clear_error_messages($form, uacf7_current_step) {
            $form.removeClass('invalid');
            jQuery('.wpcf7-response-output', $form).removeClass('wpcf7-validation-errors');
            jQuery('.wpcf7-form-control', uacf7_current_step).removeClass('wpcf7-not-valid');
            jQuery('[aria-invalid]', uacf7_current_step).attr('aria-invalid', 'false');
            jQuery('.wpcf7-not-valid-tip', uacf7_current_step).remove();
        }

        // Form Thankyou message
        function uacf7_conversational_mailsent_handler() {
            document.addEventListener('wpcf7mailsent', function (event) {
                var form_id = event.detail.contactFormId;
               
                var $this = $('.uacf7-form-' + form_id).find('.uacf7-conv-form-wrap');
                if (typeof $this === "undefined") {
                    return false;
                }
                var thankyou_status = $this.attr("data-thankyou");
                 
                if (thankyou_status == true) {
                    $.ajax({
                        url: conversational_ajax.ajaxurl,
                        type: 'post',
                        data: {
                            action: 'uacf7_conversational_thankyou_message',
                            form_id: form_id,
                        },
                        success: function (data) {

                            $this.find('.uacf7-conv-single-field').removeClass('active');
                            $this.find('.uacf7-conv-single-field').hide();
                            $this.append(data.html);
                            uacf7_conv_form_animation($this.find('.uacf7-conv-single-thankyou'))
                            $this.find('.uacf7-conv-up-down').hide();
                        }
                    });
                }

            }, false);
        }

        uacf7_conversational_mailsent_handler();


        // Form Animation
        function uacf7_conv_form_animation($this, direction = -200) {
            if ($('.uacf7-conv-form-wrap').hasClass('style-2') == true || $('.uacf7-conv-form-wrap').hasClass('style-3') == true || $('.uacf7-conv-form-wrap').hasClass('style-4') == true) {
                gsap.from($this, {
                    opacity: 0,
                    y: direction,
                    duration: 0.7,
                    delay: 0.2,
                    // ease: "fadeIn",
                });
            }

        }

        // Progress bar
        function uacf7_conv_progress_bar($this) {
            var total_steps = $('.uacf7-conv-form-wrap').find('.uacf7-conv-single-field').length;
            var current_step = $this.attr('data-step');
            var progress = (current_step / total_steps) * 100;
            // $('.uacf7-conv-progress-completed').css('width', progress+'%');
            $('.uacf7-conv-progress-completed').animate({
                width: progress + '%'
            }, 300);
        }
  
    });
})(jQuery);
