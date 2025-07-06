(function ($) {


    /**
     * License Activate
     * 
     * Ajax
     */
    $(document).on('click', '.uacf7-license-activate #submit', function (e) {
        e.preventDefault();

        // $('.tf-option-form').submit();

        //after 3 seconds page will be reloaded
        // setTimeout(function() {
        //     location.reload();
        // } , 3000);

        var current = $(this);
        var license_key = $("input[name='UltimateAddonsforContactForm7Pro_lic_Key']").val();
        var license_email = $("input[name='UltimateAddonsforContactForm7Pro_lic_email").val();
        var data = {
            action: 'uacf7_pro_act_license',
            license_key: license_key,
            license_email: license_email,
        };

        jQuery.post(uacf7_admin_params.ajax_url, data, function (response) {
            //console.log(response.data.activateUrl);
        })
            .success(function (response) {
                //console.log(response);
                location.reload();
            });
    });


    /**
        * License Deactivate
        * 
        * Ajax
        */
    $(document).on('click', '.uacf7_el-license-container #submit', function (e) {
        e.preventDefault();

        var current = $(this);

        var data = {
            action: 'uacf7_pro_deact_license',
        };

        jQuery.post(uacf7_admin_params.ajax_url, data, function (response) {
            //console.log(response);
            //console.log(response.data.activateUrl);
        })
            .success(function (response) {
                //console.log(response);
                location.reload();
            });
    });

    /**
     *  Addon migration process
     * 
     */

    $(document).on('click', '.uacf7-migration-lets-start', function (e) {
        e.preventDefault();
        $('.uacf7-notice-migration-steps.step-1').fadeOut(500, function () {
            $('.uacf7-notice-migration-steps.step-2').fadeIn(500);
            $('.uacf7-notice-migration-steps.step-1').removeClass('active');
            $('.uacf7-notice-migration-steps.step-2').addClass('active');
        });
    });
    $(document).on('click', '.uacf7-start-migrate', function (e) {

        e.preventDefault();
        var current = $(this);
        var pre_loader = '<span class="spinner is-active"></span>';
        current.parent().append(pre_loader);
        var data = {
            action: 'uacf7_pro_existing_addons_migration',
            nonce: uacf7_pro_admin.nonce,
        };

        jQuery.post(uacf7_pro_admin.ajaxurl, data, function (response) {
            // console.log(response); 
        })
            .success(function (response) {
                current.parent().find('.spinner').remove();
                //console.log(response);
                location.reload();
            });
    });
    $(document).on('change', '.uacf7-single-addon-setting .uacf7-addon-input-field', function (e) {
        e.preventDefault();

        var child = $(this).attr('data-child');
        var is_pro = $(this).attr('data-is-pro');

        if (child != '') {
            if ($(this).is(':checked')) {
                if (!$('#' + child).is(':checked')) {

                    $('#' + child).prop('checked', true);
                    $('#' + child).val(1);

                    $(".tf-option-form.tf-ajax-save").submit();
                }
            } else {
                if (is_pro != 'pro') {

                    $('#' + child).prop('checked', false);
                    $('#' + child).val(0);

                    $(".tf-option-form.tf-ajax-save").submit();
                }
            }
        }
    });
})(jQuery);
