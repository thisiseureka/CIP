jQuery(document).ready(function($) {
    $(document).on('click', '.uacf7-preview-btn', function(e) {
        e.preventDefault();

        var form = $(this).closest('form');
        var formData = '';
        var processedFieldNames = [];

        // Create a table to display the form data
        formData += '<table border="1" style="width: 100%; margin-top: 10px;">';
        formData += '<thead><tr><th>Label</th><th>Value</th></tr></thead><tbody>';

        // Iterate over all input fields (including text, email, radio buttons, checkboxes, textareas, select, etc.)
        form.find('input:not([type="hidden"]:not(#uacf7-amount)), textarea, select').each(function() {
            var field = $(this);
            var fieldId = field.attr('id');

            // Check for a label associated with the field
            var label = '';
            if (fieldId) {
                label = $('label[for="' + fieldId + '"]').text();
            }

            // If there's no label found via the 'for' attribute, try to get the label text from the parent or closest label
            if (!label && field.attr('type') !== 'submit') {
                if(field.is(':checkbox') || field.is(':radio')){
                    label = $(this).closest('.wpcf7-form-control-wrap').parent('label').clone().children().remove().end().text().trim() || field.attr('name') || field.attr('id');
                    label = label.replace('[]', '');
                }else{
                    label = $(this).closest('label').clone().children().remove().end().text().trim() || field.attr('name') || field.attr('id');
                }
            }

            fieldValue = escapeHtml(field.val());

            // Skip fields that have a parent with the "quicktags-toolbar" class
            if (field.closest('.quicktags-toolbar').length > 0) {
                return true;
            }

            // If the field is a signature canvas, capture the canvas data
            if (field.closest('label').find('canvas').length) {
                var canvas = field.closest('label').find('canvas')[0];
                if (canvas && canvas.toDataURL) {
                    var canvasData = canvas.toDataURL();
                    fieldValue = '<img src="' + canvasData + '" alt="' + escapeHtml("Signature") + '" style="max-width: 300px; max-height: 100px;">'; 
                }
            }

            if (field.hasClass('flatpickr-monthDropdown-months') || field.hasClass('cur-year')) {
                return true;
            }

            // Handle Rating (radio button fields)
            if (field.attr('type') === 'radio' && field.closest('.wpcf7-form-control').hasClass('uacf7-rating')) {
                var selectedRating = 0;
                var ratingHtml = '';
                var fieldName = field.attr('name');

                if (processedFieldNames.indexOf(fieldName) === -1) {

                    field.closest('.wpcf7-form-control').find('input[type="radio"]:checked').each(function() {
                        var value = $(this).val();
                        if (parseInt(value) > selectedRating) {
                            selectedRating = parseInt(value);
                        }
                    });

                    for (var i = 1; i <= 5; i++) {
                        ratingHtml += '<span class="icon">'+ $(this).siblings('span').html() +'</span>';
                        if (i === selectedRating) break;
                    }

                    fieldValue = ratingHtml;
                    processedFieldNames.push(fieldName);

                }else{

                    return true;

                }

            }

            // Handle checkboxes
            if (field.is(':checkbox')) {

                if(field.prop('checked')){
                    var fieldName = field.attr('name').replace('[]', '');
                    if (true) {
                        var selectedValues = [];
                        var checkboxValue = $(this).val();
                        selectedValues.push(escapeHtml(checkboxValue));

                        fieldValue = selectedValues.join(', ');
                        processedFieldNames.push(fieldName);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
                
            }


            if (field.is(':radio') && !field.closest('.wpcf7-form-control').hasClass('uacf7-rating')) {
                if(field.prop('checked')){
                    var fieldName = field.attr('name');
                    if (processedFieldNames.indexOf(fieldName) === -1) {
                        var selectedRadio = field.filter(':checked').val();
                        fieldValue = escapeHtml(selectedRadio);
                        processedFieldNames.push(fieldName);
                    } else {
    
                        return true;
                    }
                }else{

                    return true;

                }
            }

            // Only add fields that have a value, and exclude submit buttons
            if (fieldValue !== undefined && fieldValue !== "" && field.attr('type') !== 'submit') {
                formData += '<tr><td>' + escapeHtml(label) + '</td><td>' + fieldValue + '</td></tr>';
            }
        });

        formData += '</tbody></table>';

        let formType = $('#uacf7-conversational-form').attr('form-type');
        let previewSubmitBtn = '<button class="uacf7-submit-btn">Submit <i class="far fa-paper-plane"></i></button>';
        if(formType === 'uacf7-conversational'){
            previewSubmitBtn = '';
        }

        // Create the modal HTML
        var modalHTML = '<div class="uacf7-preview-modal">' +
                            '<div class="uacf7-preview-modal-content">' +
                                '<span class="uacf7-close-btn">&times;</span>' +
                                '<h2>Form Preview</h2>' +
                                formData +
                                '<div class="uacf7-modal-buttons">' +
                                    '<button class="uacf7-back-btn"><i class="fas fa-undo"></i> Back</button>' + previewSubmitBtn +
                                '</div>' +
                            '</div>' +
                         '</div>';

        // Append the modal to the body
        $('body').append(modalHTML);

        // Show the modal
        $('.uacf7-preview-modal').fadeIn();

        // Close modal when clicking the close button
        $('.uacf7-close-btn, .uacf7-back-btn').on('click', function() {
            $('.uacf7-preview-modal').fadeOut(function() {
                $(this).remove();
            });
        });

        // "Submit" button functionality - submit the form
        $('.uacf7-submit-btn').on('click', function() {
            form.find('.wpcf7-submit').click();
            $('.uacf7-preview-modal').fadeOut(function() {
                $(this).remove();
            });
        });

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Close modal if user clicks outside the modal content
        $(window).on('click', function(event) {
            if ($(event.target).hasClass('uacf7-preview-modal')) {
                $('.uacf7-preview-modal').fadeOut(function() {
                    $(this).remove();
                });
            }
        });
    });
});
