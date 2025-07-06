(function ($) {
    'use strict';
    $(document).ready(function () {
        $("#uacf7-conversational-form").each(function () {
            var $this = $(this);
            const form_id = $this.find("input[name=_wpcf7]").val();
            const form_type = $this.attr('form-type');

            if (form_type === 'uacf7-conversational') {
                const $wraps = $this.find('.uacf7_conversational_item_wrap');
                const $activeWrap = $this.find('.uacf7_conversational_item_wrap.active');
                const $last_wrap = $this.find('.uacf7_conversational_item_wrap.uacf7-last-item');
                // Find the input field inside $last_wrap
                const $submitInput = $last_wrap.find('input.wpcf7-submit');
                const $repeater_count = jQuery(this).find('.uacf7-repeater-count').val();

                // Loop through each uacf7_conversational_item_wrap
                $wraps.each(function (index) {
                    var $wrap = $(this);

                    // Define button label based on index
                    var buttonValue;
                    if (index === 0) {
                        buttonValue = `<button data-target="${index}" id="uacf7_specific_button" class="uacf7_default" type="button" value="Start">Start</button>`;  // First button is 'Start'
                    } else if (index === $wraps.length - 2) {
                        buttonValue = `<input id="uacf7_specific_button" data-target="${index}" class="${$submitInput.attr('class')} uacf7_default" type="submit" value="Submit"> <span class="uacf7-ajax-loader"></span>`;  // Second to last button is 'Submit'
                    } else {
                        buttonValue = `<button data-target="${index}" id="uacf7_specific_button" class="uacf7_default" type="button">Next <span class="uacf7-ajax-loader"></span></button>`;
                    }

                    // Skip adding the button if it's the last item
                    if (index === $wraps.length - 1) {
                        return; // Skip the last item
                    }

                    var $specificButton = `<div class="uacf7_specific_button_wrap uacf7_default">
                            ${buttonValue}
                            <a class="uacf7-enter-desc">Press <b>Enter ↵</b></a>
                        </div>`;

                    // Append the button to the current wrap
                    $wrap.append($specificButton);
                });

                // Add active class to the first wrap by default
                $wraps.first().addClass('active');

                // Handle button click
                $this.on('click', '#uacf7_specific_button', function (e) {
                    e.preventDefault();

                    var targetIndex = $(this).data('target');
                    var $currentWrap = $wraps.eq(targetIndex);
                    var $nextWrap = $wraps.eq(targetIndex + 1);

                    if ($(this).val() == 'Submit') {
                        setTimeout(function () {
                            // Remove active class from current wrap
                            $submitInput.trigger('click')
                            $currentWrap.removeClass('active');
                            if ($nextWrap.length) {
                                $nextWrap.addClass('active');
                            }
                        }, 1000);

                    } else if ($(this).val() == 'Start') {
                        $currentWrap.removeClass('active');
                        if ($nextWrap.length) {
                            $nextWrap.addClass('active');
                            // Focus the first input field in the next wrap
                            $nextWrap.find('input:first').focus();
                        }
                    } else {
                        validateFields($currentWrap, $nextWrap, form_id, $repeater_count, $wraps);
                    }

                });

                // Keydown event handler for the Enter key
                $this.on('keydown', function (e) {
                    // Prevent the default action of Enter key
                    e.preventDefault();

                    if (e.key === 'Enter') {
                        // console.log("Enter press");
                        // Find the currently active wrap
                        var $currentWrap = $wraps.filter('.active');

                        // Find the corresponding specific button in the active wrap
                        var $button = $currentWrap.find('#uacf7_specific_button');

                        if ($button.length) {
                            // Simulate the button click
                            $button.trigger('click');
                        }
                    }
                });

                // On window load, set the first button as active and simulate the Enter key
                $(window).on('load', function () {
                    var $firstWrap = $wraps.first();

                    // Mark the first wrap as active if not already
                    if (!$firstWrap.hasClass('active')) {
                        $firstWrap.addClass('active');
                    }

                    // Focus the first input field or button
                    var $firstInputOrButton = $firstWrap.find('input:first, #uacf7_specific_button:first');
                    if ($firstInputOrButton.length) {
                        $firstInputOrButton.focus();
                    }
                });

                // Handle radio and checkbox selection
                $wraps.find('input[type="radio"]').on('change', function () {
                    var $thisInput = $(this);
                    if ($thisInput.is(':checked')) {

                        // Check if the input is checked
                        $thisInput.closest('.wpcf7-list-item').toggleClass('animate-flicker');

                        setTimeout(function () {
                            // Find the currently active wrap
                            var $currentWrap = $wraps.filter('.active');
                            // Find the corresponding specific button in the active wrap
                            var $button = $currentWrap.find('#uacf7_specific_button');
                            $button.trigger('click');
                        }, 200);
                    }
                });

                // Thank you div
                var $thankyou_div = $("<div>", {
                    class: 'uacf7_conversational_thankyou',
                    text: conversational_ajax.conversational_thanks // Set the inner text to the thank you message
                });
                // Append the hidden input to the $lastitem
                $last_wrap.append($thankyou_div);
            }
        });

        function validateFields($currentWrap, $nextWrap, form_id, $repeater_count, $wraps) {
            const uniqueFields = new Set();

            // Collect current step field names
            $currentWrap.find('.wpcf7-form-control').each(function () {
                const fieldName = this.name ? this.name.replace('[]', '') : null;
                if (fieldName) {
                    uniqueFields.add(fieldName);
                }
            });

            // Handle input fields explicitly for validation
            $currentWrap.find('input').each(function () {
                const fieldName = this.name ? this.name.replace('[]', '') : null;
                if (fieldName && jQuery(this).is('[type="checkbox"], [type="radio"]') && !jQuery(`[name="${this.name}"]:checked`).val()) {
                    uniqueFields.add(fieldName);
                } else if (!jQuery(this).val() && !jQuery(this).is('[type="checkbox"], [type="radio"]')) {
                    uniqueFields.add(this.name);
                }
            });

            // Prepare AJAX payload
            const validationFields = Array.from(uniqueFields).map(fieldName => {
                const $field = jQuery(`[name="${fieldName}"], [name="${fieldName}[]"]`);
                if (!$field.val() || $field.is('[type="checkbox"], [type="radio"]') && !$field.is(':checked')) {
                    return `${$field[0].localName}:${fieldName}`;
                }
                return null;
            }).filter(Boolean);

            var data = 'action=' + 'uacf7_cons_fields_validation' +
                '&' + 'form_id=' + form_id +
                '&' + 'validation_fields=' + validationFields +
                '&' + 'ajax_nonce=' + conversational_ajax.nonce;

            // Send AJAX validation request
            jQuery.ajax({
                url: conversational_ajax.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: data,
                beforeSend: function () {
                    $currentWrap.find(".uacf7_specific_button_wrap").find('.uacf7-ajax-loader').addClass('is-active');
                },
                success: function (response) {
                    $currentWrap.find(".uacf7_specific_button_wrap").find('.uacf7-ajax-loader').removeClass('is-active');
                    var $form = jQuery('form');
                    clear_error_messages($form, $currentWrap);
                    try {
                        if (response.is_valid) {
                            // Proceed to next wrap if it exists and validation passed
                            if ($nextWrap.length) {
                                // Remove the 'animate-flicker' class from all inputs
                                $currentWrap.find('.animate-flicker').removeClass('animate-flicker');
                                // Remove the 'animate-flicker' class from all inputs
                                $wraps.find('.animate-flicker').removeClass('animate-flicker');
                                // Remove active class from current wrap
                                $currentWrap.removeClass('active');
                                $nextWrap.addClass('active');

                                // Focus on the input in the next wrap
                                setTimeout(function () {
                                    var $activeInput = $nextWrap.find('input, textarea, select').first();
                                    // If no text input, textarea, or select is found, check for other input types like radio or checkbox
                                    if (!$activeInput.length) {
                                        $activeInput = $nextWrap.find('input[type="radio"], input[type="checkbox"]').first();
                                    }
                                    // Focus the first found input
                                    if ($activeInput.length) {
                                        $activeInput.focus();
                                    }

                                    if (!$activeInput.hasClass('wpcf7-validates-as-required')) {
                                        $activeInput.closest('.uacf7_specific_button').html('Skip');
                                    }

                                }, 100);
                            }

                        } else {
                            // Handle validation errors
                            jQuery.each(response.invalid_fields, function (i, field) {
                                const $target = jQuery(field.into, 'form');
                                $target.find('.wpcf7-form-control').addClass('wpcf7-not-valid');
                                $target.find('[aria-invalid]').attr('aria-invalid', 'true');
                                $target.append(`<span class="wpcf7-not-valid-tip" aria-hidden="true">${field.message}</span>`);
                            });
                        }
                    } catch (e) {
                        console.error('Error processing validation response:', e);
                    }
                },
                error: function (xhr) {
                    console.error('Error in AJAX request:', xhr);
                },
            });

        }

        function clear_error_messages($form, uacf7_current_step) {
            $form.removeClass('invalid');
            jQuery('.wpcf7-response-output', $form).removeClass('wpcf7-validation-errors');
            jQuery('.wpcf7-form-control', uacf7_current_step).removeClass('wpcf7-not-valid');
            jQuery('[aria-invalid]', uacf7_current_step).attr('aria-invalid', 'false');
            jQuery('.wpcf7-not-valid-tip', uacf7_current_step).remove();
        }
    });

})(jQuery);