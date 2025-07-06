
(function ($) {
    'use strict';

    document.addEventListener('wpcf7mailsent', function(event) {
        var formId = event.detail.contactFormId;
        var pdfUrl = event.detail.apiResponse.pdf_url;
        var form = $(event.target);

        if (form.find('.uacf7-form-' + formId).attr('pdf-download') === '1') {
            var unit_tag = event.detail.unitTag;

            setTimeout(function () {
                var responseOutput = $('#' + unit_tag + ' .wpcf7-response-output');
                var old_message = responseOutput.html();

                if (pdfUrl) {

                    responseOutput.html(old_message);
                    responseOutput.append(
                        '<br><a class="download-lnk-pdf" href="' + pdfUrl + '" target="_blank" download>Download PDF</a>'
                    );

                }
            }, 100);
        }

    }, false);


})(jQuery);
