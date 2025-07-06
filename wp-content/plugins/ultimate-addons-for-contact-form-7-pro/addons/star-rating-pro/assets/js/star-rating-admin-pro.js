; (function ($) {
    'use strict';
    $(document).ready(function () {
        $('#uacf7_review_form_id').change(function () {
            var $this = $(this);
            var form_id = $this.val();
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'uacf7_ajax_star_rating_form_tag',
                    form_id: form_id,
                },
                success: function (data) {
                    $('#uacf7_reviewer_name, #uacf7_reviewer_image, #uacf7_review_title, #uacf7_review_rating, #uacf7_review_desc').html(data);
                }
            });
        });

        // Star Review Option form data updated on Ajax
        $('#uacf7_review_opt\\[review_metabox\\]\\[uacf7_review_form_id\\]').change(function () {
            var $this = $(this);
            var form_id = $this.val();
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'uacf7_ajax_star_rating_form_tag',
                    form_id: form_id,
                },
                success: function (data) {
                    $('#uacf7_review_opt\\[review_metabox\\]\\[uacf7_reviewer_name\\], #uacf7_review_opt\\[review_metabox\\]\\[uacf7_reviewer_image\\], #uacf7_review_opt\\[review_metabox\\]\\[uacf7_review_title\\], #uacf7_review_opt\\[review_metabox\\]\\[uacf7_review_rating\\], #uacf7_review_opt\\[review_metabox\\]\\[uacf7_review_desc\\]').html(data);
                }
            });
        });


        $('.star_is_review').click(function (e) {
            var id = $(this).val();
            if ($(this).is(':checked')) {
                var is_checked = 1;
            } else {
                var is_checked = 0;
            }

            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'uacf7_ajax_star_rating_is_review',
                    id: id,
                    is_checked: is_checked,
                },
                success: function (data) {
                }
            });

        });

        // Custom Css Area
        // wp.codeEditor.initialize($('#uacf7_review_custom_css'), uacf7_review_custom_css);
    });


})(jQuery);