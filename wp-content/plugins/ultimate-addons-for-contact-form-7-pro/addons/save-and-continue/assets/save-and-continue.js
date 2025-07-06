jQuery(document).ready(function ($) {

    $('.uacf7-save-and-continue-btn').on('click', function (e) {
        e.preventDefault();

        const $form = $(this).closest('form');

        const formDataArray = $form.serializeArray();
        const formDataObject = {};

        // Process form data
        formDataArray.forEach(field => {
            if (field.name.endsWith('[]')) {
                // Handle checkboxes with array names
                if (!formDataObject[field.name]) {
                    formDataObject[field.name] = [];
                }
                formDataObject[field.name].push(field.value);
            } else {
                // Handle regular fields
                formDataObject[field.name] = field.value;
            }
        });

        // Add Select2 field values explicitly
        $form.find('select').each(function () {
            const fieldName = $(this).attr('name');
            const fieldValue = $(this).val();

            if (fieldName) {
                // If it's a multi-select, ensure an array format
                if (Array.isArray(fieldValue)) {
                    formDataObject[fieldName] = fieldValue;
                } else {
                    formDataObject[fieldName] = fieldValue || '';
                }
            }
        });

        // $('.signature-pad').each(function () {
        //     const fieldName = $(this).data('field-name');
        //     const canvas = document.getElementById('uacf7_signature-' + fieldName.split('-')[1]);
        
        //     // Ensure the element is a valid canvas
        //     if (canvas instanceof HTMLCanvasElement) {
        //         const signatureData = canvas.toDataURL();
        //         // console.log(signatureData);  
        //         formDataObject[fieldName] = signatureData; 
        //     } else {
        //         console.error(`Element with id "uacf7_signature-${fieldName.split('-')[1]}" is not a valid canvas.`);
        //     }
        // });

        $form.find('.uacf7_repeater').each(function () {
            const repeaterId = $(this).attr('uacf7-repeater-id');
            if (repeaterId) {
                const repeaterData = {};
        
                $(this).find('input, select, textarea').each(function () {
                    const fieldName = $(this).attr('name');
                    let fieldValue;
        
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        // Handle Select2 field (single or multiple)
                        fieldValue = $(this).val(); // Gets single value or array for multi-select
                    } else if ($(this).is(':checkbox')) {
                        // Handle checkboxes
                        if (!repeaterData[fieldName]) {
                            repeaterData[fieldName] = [];
                        }
                        if ($(this).is(':checked')) {
                            repeaterData[fieldName].push($(this).val());
                        }
                    } else if ($(this).is(':radio')) {
                        // Handle radio buttons
                        if ($(this).is(':checked')) {
                            fieldValue = $(this).val();
                        }
                    } else {
                        // Handle other inputs
                        fieldValue = $(this).val();
                    }
        
                    if (fieldName && fieldValue !== undefined) {
                        repeaterData[fieldName] = fieldValue;
                    }
                });
        
                formDataObject[repeaterId] = repeaterData;
            }
        });
        
        

        run_waitme($(this), formDataObject._wpcf7);

        $.ajax({
            url: saveAndContinue.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_form_data',
                form_data: JSON.stringify(formDataObject),
                nonce: saveAndContinue.nonce
            },
            success: function (response) {

                if (response.success) {
                    $('.uacf7-form-'+formDataObject._wpcf7).waitMe('hide');
                    $('.uacf7-save-confirmation .resume-url').val(response.data.resume_link);
                    $('.uacf7-save-confirmation').closest('form').find('.uacf7-form-'+formDataObject._wpcf7).hide();
                    $('.uacf7-save-confirmation').show();
                } else {
                    alert('Failed to save the form. Please try again.');
                    $('.uacf7-form-'+formDataObject._wpcf7).waitMe('hide');
                }
            },
            error: function () {
                alert('An error occurred. Please try again.');
            },
        });

    });

    function run_waitme(el, formId){

        el.closest('form').find('.uacf7-form-'+ formId).waitMe({
            effect : 'bounce',
            text : '',
            bg : 'rgba(255, 255, 255, 0.7)',
            color : '#000',
            maxSize : '',
            waitTime : -1,
            textPos : 'horizontal',
            fontSize : '',
            source : '',
            onClose : function() {}   
        });

    }

    // Handle email submission
    $(document).on('click', '.uacf7-submit-email', function (e) {

        const $link = $(this);

        // Prevent action if the link is "disabled"
        if ($link.hasClass('disabled')) {
            e.preventDefault();
            return;
        }

        e.preventDefault();

        const $formContainer = $(this).closest('.uacf7-save-confirmation');
        const formId = $formContainer.data('form-id');
        const email = $formContainer.find('#resume_email').val();
        const resumeUrl = $formContainer.find('.resume-url').val();

        // Validate the email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            $formContainer.find('.email-error-message').slideDown();
            return;
        }
        $formContainer.find('.email-error-message').slideUp();

        // Disable the "Send Link" button while processing
        $link.addClass('disabled').text('Sending...');

        // Make an AJAX request to send the email
        $.ajax({
            url: cf7SaveAndResume.ajax_url,
            type: 'POST',
            data: {
                action: 'uacf7_send_resume_email',
                email: email,
                form_id: formId,
                resume_link: resumeUrl,
                security: cf7SaveAndResume.nonce,
            },
            success: function (response) {
                if (response.success) {
                    $formContainer.find('.message').html(response.data.message);
                    $formContainer.find('#resume_email').val('');

                } else {
                    
                    $formContainer.find('.thank-you-message')
                    .text(response.data.message)
                    .css('color', 'red')
                    .slideDown();

                }

                $link.addClass('disabled').text('Send Link');
            },
            error: function () {
                alert('An error occurred. Please try again.');
                // Re-enable the "Send Link" button
                $link.removeClass('disabled').text('Send Link');
            },
        });
    });

    jQuery(document).ready(function($) {
        $('.uacf7_repeater').each(function() {
            var $repeater = $(this);
            var repeaterId = $repeater.attr('uacf7-repeater-id');
    
            if (repeaterId && repeaterId.startsWith('uarepeater-')) {
                var repeaterData = JSON.parse($repeater.attr('repeater-data'));
                var clickCount = parseInt(repeaterData[repeaterId + '_count']);
    
                // Trigger clicks to match repeater count
                for (var i = 0; i < clickCount - 1; i++) {
                    $repeater.find('.uacf7_repeater_add').trigger('click');
                }
    
                // Populate fields with saved data
                $repeater.find('.uacf7_repeater_sub_field').each(function(index) {
                    $(this).find('input, select, textarea').each(function() {
                        const fieldName = $(this).attr('name');
    
                        if (fieldName in repeaterData) {
                            const $field = $(this);
                            const savedValue = repeaterData[fieldName];
    
                            if ($field.hasClass('select2-hidden-accessible')) {
                                // Handle Select2 fields
                                if (Array.isArray(savedValue)) {
                                    $field.val(savedValue).trigger('change');
                                } else {
                                    $field.val(savedValue).trigger('change'); 
                                }
                            } else if ($field.is('input[type="radio"]')) {
                                // Handle radio buttons
                                $field.filter('[value="' + savedValue + '"]').prop('checked', true);
                            } else if ($field.is('input[type="checkbox"]')) {
                                // Handle checkboxes
                                if (Array.isArray(savedValue)) {
                                    $field.each(function() {
                                        if (savedValue.includes($(this).val())) {
                                            $(this).prop('checked', true);
                                        }
                                    });
                                }
                            } else if ($field.is('input[type="date"]')) {
    
                                $field.val(savedValue);
    
                                if ($field[0]._flatpickr) {
                                    $field[0]._flatpickr.setDate(savedValue, true);
                                } else {
                                    // Initialize Flatpickr if not already initialized
                                    $field.flatpickr({
                                        defaultDate: savedValue,
                                        onReady: function(selectedDates, dateStr, instance) {
                                            instance.setDate(savedValue, true); 
                                        },
                                    });
                                }
                            } else if ($field.is('input[type="time"]')) {
    
                                // Apply time format and set value
                                if ($field.val() === "") {
                                    $field.val(savedValue);
                                } else {
                                    $field.val(savedValue); 
                                }
                            } else if ($field.is('input[type="range"]')) {
                                const $valueSpan = $field
                                    .closest('.uacf7-slidecontainer')
                                    .siblings('span')
                                    .filter(function () {
                                        return $(this).attr('class')?.includes('-value');
                                    });
    
                                if ($valueSpan.length) {
                                    $valueSpan.text(savedValue);
                                }
    
                                $field.on('input', function () {
                                    const newValue = $field.val();
                                    if ($valueSpan.length) {
                                        $valueSpan.text(newValue);
                                    }
                                });
                            } else if ($field.hasClass('multistep_slide')) {
                                // Handle double-handle sliders
                                const sliderValues = savedValue.split('-').map(val => parseInt(val.trim(), 10));
                                const $sliderWrapper = $field.closest('.uacf7-slider-handle');
                                const $sliderRange = $sliderWrapper.find('.ui-slider-range');
                                const $handles = $sliderWrapper.find('.ui-slider-handle');
                                const min = parseInt($sliderWrapper.data('min'), 10);
                                const max = parseInt($sliderWrapper.data('max'), 10);

                                if (sliderValues.length === 2) {
                                    const percentageStart = ((sliderValues[0] - min) / (max - min)) * 100;
                                    const percentageEnd = ((sliderValues[1] - min) / (max - min)) * 100;

                                    // Update slider range inline styles
                                    $sliderRange.css({
                                        left: `${percentageStart}%`,
                                        width: `${percentageEnd - percentageStart}%`,
                                    });

                                    // Update handles inline styles
                                    $handles.eq(0).css('left', `${percentageStart}%`);
                                    $handles.eq(1).css('left', `${percentageEnd}%`);

                                    // Update slider display text
                                    $sliderWrapper.find('.uacf7-amount').text(`${sliderValues[0]} - ${sliderValues[1]}`);
                                    $field.val(savedValue);
                                }
                            }  else {
                                // Handle other fields (text, textarea, etc.)
                                $field.val(savedValue);
                            }
                        }
                    });
                });
            }
        });
    });
    
    

    // Handle Copy Link Button Click
    $('.copy-resume-url-btn').on('click', function () {
        const $resumeUrlInput = $(this).siblings('.resume-url');

        $resumeUrlInput.select();
        document.execCommand('copy');

        alert('Link copied to clipboard!');
    });

    // check resume email validation
    const $emailInput = $('#resume_email');
    const $sendButton = $('.uacf7-submit-email');
    const $errorMessage = $('.email-error-message');

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    $emailInput.on('input', function () {
        const email = $emailInput.val();
    
        if (isValidEmail(email)) {
            $errorMessage.hide();
            $sendButton.removeClass('disabled').text('Send Link');
        } else {
            $errorMessage.text('Please provide a valid email.').show();
            $sendButton.addClass('disabled').text('Send Link');
        }
    });
    
});

