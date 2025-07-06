; (function ($) {
    'use strict';

    var dataId = [];
    jQuery('.uacf7_repeater').each(function () {

        dataId.push('"' + jQuery(this).attr('uacf7-repeater-id') + '"');

    });
    jQuery('input[name="_uacf7_repeaters"]').val('[' + dataId + ']');


    function uacf7_repeater_default_setup() {

        jQuery('.uacf7_repeater_sub_field').each(function () {


            jQuery(this).find('[name]').each(function () {

                var nameVal = jQuery(this).attr('name');
                jQuery(this).attr('uacf-original-name', nameVal);

            });

        });

        jQuery('.uacf7-repeater-title').each(function () {
            var groupTitle = jQuery(this).text();
            jQuery(this).attr('original-title', groupTitle);
        })

    }
    uacf7_repeater_default_setup();


    //By default
    function uacf7_repeater_default_fix() {
        jQuery('.uacf7_repeater').each(function () {

            var $this = jQuery(this);

            //Count
            var count = $this.find('.uacf7_repeater_sub_field').length;

            $this.find('.uacf7_repeater_controls .uacf7-repeater-count').val(count);

            //Fix suffix
            uacf7_repeater_suffix_by_default($this);

        });
    }
    uacf7_repeater_default_fix();

    //Add button on click
    jQuery('.uacf7_repeater_add').on('click', function (e) {
        e.preventDefault();

        var $this = jQuery(this);

        //Count
        var count = $this.parents('.uacf7_repeater').find('.uacf7_repeater_sub_field').length;

        var max_repeat = $this.parents('.uacf7_repeater').attr('repeat');

        var contents = $this.parents('.uacf7_repeater').find('.uacf7_repeater_sub_field:first').clone();

        // Redio Button selection issue
        contents.find("input[type='radio'], input[type='checkbox']").each(function () {
            if (jQuery(this).is("input[type='radio']") || jQuery(this).is("input[type='checkbox']")) {
                var nameVal = jQuery(this).attr('uacf-original-name');

                nameVal = nameVal.replace('[]', '');

                jQuery(this).attr('name', nameVal + '__' + 0 + '[]');

            } else {
                jQuery(this).attr('name', nameVal + '__' + 0);
            }


        });

        // Empty all input and textarea value
        contents.find("input, textarea").each(function () {

            // if(jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox' || jQuery(this).hasClass('wpcf7-uacf7_dynamic_text') === true ){
            //     return;
            // }else{
            //     jQuery(this).val(''); 
            // }

            if (jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox' || jQuery(this).hasClass('wpcf7-uacf7_dynamic_text') === true) {
                return;
            } else {
                jQuery(this).val('');
            }

        });

        // Empty all checkbox value
        contents.find("input[type='checkbox']").each(function () {
            jQuery(this).prop('checked', false);
        });
        contents.find(".wpcf7-uacf7_booking_form_date").each(function () {
            jQuery(this).parent().find('.flatpickr-calendar').remove();
        });
        contents.find(".wpcf7-uacf7_booking_form_time").each(function () {
            jQuery(this).parent().find('.bf-time-picker').remove();
        });

        // Empty all select value
        contents.find("select").each(function () {
            jQuery(this).find("option:selected").prop("selected", false)
        });

        // Remove country-select 
        contents.find("#uacf7_country_select").each(function () {
            // Remove the specific div and all its contents
            var country_div = jQuery(this).find('.country-select.inside');
            var country_input = country_div.find('input');
            country_div.remove();
            jQuery(this).append(country_input);
        });

        // Condition Field repeater condition checked
        var contactFormId = $(this).closest('form.wpcf7-form').find('input[name="_wpcf7"]', this).val();

        if (typeof uacf7_cf_object !== 'undefined') {
            var form = uacf7_cf_object[contactFormId];
            var i = 0;

            if (typeof form !== 'undefined' && form !== '') {
                jQuery(form).each(function () {
                    var uacf7_cf_group = form[i]['uacf7_cf_group'];
                    var uacf7_cf_hs = form[i]['uacf7_cf_hs'];
                    var uacf_cf_condition_for = form[i]['uacf_cf_condition_for'];
                    if (uacf7_cf_hs == 'show') {
                        contents.find('.' + uacf7_cf_group).hide();
                    } else {
                        contents.find('.' + uacf7_cf_group).show();
                    }
                    i++
                });
            }
        }

        //Repeater
        if (count <= (max_repeat - 1)) {
            $this.parents('.uacf7_repeater').find('.uacf7_repeater_sub_fields').append(contents);
        } else if (max_repeat == '') {
            $this.parents('.uacf7_repeater').find('.uacf7_repeater_sub_fields').append(contents);
        } else {
            alert('Maximum repetition limit (' + max_repeat + ') for this group.');
            jQuery(this).attr('disabled', true);
        }

        $this.parents('.uacf7_repeater_controls').find('.uacf7-repeater-count').val(count + 1);

        // Fix suffix
        uacf7_repeater_suffix($this);

        $this.parents('.uacf7_repeater').find('.uacf7_repeater_sub_field .uacf7_repeater_remove').show();

    });

    //Remove button on click
    jQuery(document).on('click', '.uacf7_repeater_remove', function () {

        var $this = jQuery(this);

        //Repeater
        var totalCount = $this.parents('.uacf7_repeater_sub_fields').find('.uacf7_repeater_sub_field').length;

        var curent_repeater = $this.parents('.uacf7_repeater').find('.uacf7_repeater_sub_field').length;

        if (curent_repeater <= 5) {
            jQuery(this).parents('.uacf7_repeater').find('.uacf7_repeater_add').attr('disabled', false);
        }

        if (totalCount == 1) {
            $this.hide();
        } else {
            $this.parents('.uacf7_repeater_sub_field').remove();
        }

        uacf7_repeater_fix_on_remove();

    });

    //suffix function
    function uacf7_repeater_suffix_by_default(thisSelector) {
        var x = 1; thisSelector.find('.uacf7_repeater_sub_field').each(function () {

            //jQuery(this).attr('uacf7_repeater_sub_suffix','__'+x);

            jQuery(this).find('[name]').each(function () {

                var nameVal = jQuery(this).attr('uacf-original-name');

                var oldDigit = x - 1;

                if (jQuery(this).is("input[type='radio']") || jQuery(this).is("input[type='checkbox']")) {

                    nameVal = nameVal.replace('[]', '');

                    jQuery(this).attr('name', nameVal + '__' + x + '[]');

                } else {
                    jQuery(this).attr('name', nameVal + '__' + x);
                }

                jQuery(this).closest('.wpcf7-form-control-wrap').removeClass(nameVal + '__' + (x - oldDigit));
                jQuery(this).closest('.wpcf7-form-control-wrap').addClass(nameVal + '__' + x);
                jQuery(this).closest('.wpcf7-form-control-wrap').attr('data-name', nameVal + '__' + x);

                // Handle '.signature-pad' and its 'canvas'
                var signaturePad = jQuery(this).parent().find('.signature-pad');
                var newSuffix = '__' + x;
                if (signaturePad.length > 0) {
                    // Update 'data-field-name' for the parent '.signature-pad'
                    signaturePad.attr('data-field-name', nameVal + newSuffix);
                    // Update 'data-field-name' for the canvas inside '.signature-pad'
                    signaturePad.find('canvas').attr('data-field-name', nameVal + newSuffix);
                    signaturePad.find('canvas').attr('id', nameVal + newSuffix);
                }
            });

            x++;
        });
    }

    //suffix function
    function uacf7_repeater_suffix(thisSelector) {
        var x = 1; thisSelector.parents('.uacf7_repeater').find('.uacf7_repeater_sub_field').each(function () {

            //jQuery(this).attr('uacf7_repeater_sub_suffix','__'+x);

            jQuery(this).find('[name]').each(function () {

                var nameVal = jQuery(this).attr('uacf-original-name');

                var oldDigit = x - 1;

                if (jQuery(this).is("input[type='radio']") || jQuery(this).is("input[type='checkbox']")) {

                    nameVal = nameVal.replace('[]', '');

                    jQuery(this).attr('name', nameVal + '__' + x + '[]');

                } else {
                    jQuery(this).attr('name', nameVal + '__' + x);
                    if (jQuery(this).hasClass('wpcf7-uacf7_country_dropdown')) {
                        jQuery(this).attr('id', nameVal + '__' + x);
                    }
                }

                jQuery(this).closest('.wpcf7-form-control-wrap').removeClass(nameVal + '__' + (x - oldDigit));
                jQuery(this).closest('.wpcf7-form-control-wrap').addClass(nameVal + '__' + x);
                jQuery(this).closest('.wpcf7-form-control-wrap').attr('data-name', nameVal + '__' + x);

                // Handle '.signature-pad' and its 'canvas'
                var signaturePad = jQuery(this).parent().find('.signature-pad');
                var newSuffix = '__' + x;
                if (signaturePad.length > 0) {
                    // Update 'data-field-name' for the parent '.signature-pad'
                    signaturePad.attr('data-field-name', nameVal + newSuffix);
                    // Update 'data-field-name' for the canvas inside '.signature-pad'
                    signaturePad.find('canvas').attr('data-field-name', nameVal + newSuffix);
                    signaturePad.find('canvas').attr('id', nameVal + newSuffix);
                }
            });

            var repeaterTitle = jQuery('.uacf7-repeater-title', this);

            var origiTitle = repeaterTitle.attr('original-title');

            repeaterTitle.text(origiTitle + ' ' + x);

            x++;
        });
    }

    //Fix on remove
    function uacf7_repeater_fix_on_remove() {

        jQuery('.uacf7_repeater').each(function () {

            var x = 1;
            jQuery(this).find('.uacf7_repeater_sub_field').each(function () {

                //jQuery(this).attr('uacf7_repeater_sub_suffix','__'+x);

                jQuery(this).find('[name]').each(function () {

                    var nameVal = jQuery(this).attr('uacf-original-name');

                    var oldDigit = x - 1;

                    if (jQuery(this).is("input[type='radio']") || jQuery(this).is("input[type='checkbox']")) {

                        nameVal = nameVal.replace('[]', '');

                        jQuery(this).attr('name', nameVal + '__' + x + '[]');

                    } else {
                        jQuery(this).attr('name', nameVal + '__' + x);
                    }

                    var signaturePad = jQuery(this).parent().find('.signature-pad');
                    signaturePad.attr('data-field-name', nameVal + '__' + x);
                    // Update 'data-field-name' for the canvas inside '.signature-pad'
                    signaturePad.find('canvas').attr('data-field-name', nameVal + '__' + x);
                    signaturePad.find('canvas').attr('id', nameVal + '__' + x);

                    jQuery(this).closest('.wpcf7-form-control-wrap').attr('class', 'wpcf7-form-control-wrap ' + nameVal + ' ' + nameVal + '__' + x);
                });

                var repeaterTitle = jQuery('.uacf7-repeater-title', this);

                var origiTitle = repeaterTitle.attr('original-title');

                repeaterTitle.text(origiTitle + ' ' + x);

                x++;
            });

            var count = jQuery(this).find('.uacf7_repeater_sub_field').length;

            jQuery('.uacf7_repeater_controls', this).find('.uacf7-repeater-count').val(count);

        });
    }

})(jQuery);
