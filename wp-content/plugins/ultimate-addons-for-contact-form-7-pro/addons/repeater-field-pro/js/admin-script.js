; (function ($) {
    'use strict';

    // jQuery('.uarepeater-tag-insert').on('click', function () {

    //     var add = jQuery('.uarepeater-add').val();
    //     var remove = jQuery('.uarepeater-remove').val();
    //     var name = jQuery('#tag-generator-panel-uarepeater-name').val();

    //     if (add != '') {
    //         var addName = ' add "' + add + '"';
    //     } else {
    //         var addName = '';
    //     }

    //     if (remove != '') {
    //         var removeName = ' remove "' + remove + '"';
    //     } else {
    //         var removeName = '';
    //     }

    //     var options = addName + removeName;

    //     var tag = '[uarepeater ' + name + '' + options + '][/uarepeater]';

    //     jQuery('.uarepeater-tag').val(tag);
    //     jQuery('.uarepeater-tag-name').val(tag);
    //     jQuery('.uarepeater-insert-tag-btn').trigger('click');
    // });

    $(document).ready(function () {
        // Listen for clicks on the button that opens the dialog
        $(document).on('click', '[data-taggen="open-dialog"]', function () {
            var targetDialogId = $(this).data('target'); // Get the target dialog ID
            var $dialog = $('#' + targetDialogId); // Find the dialog element

            // Check if the dialog is for the "uarepeater" tag
            if ($dialog.find('form[data-id="uarepeater"]').length > 0) {
                // Define a function to generate the tag dynamically
                function updateTag() {
                    var add = $dialog.find('.uarepeater-add').val();
                    var remove = $dialog.find('.uarepeater-remove').val();
                    var name = $dialog.find('[data-tag-part="name"]').val();

                    // Build the add and remove options
                    var addName = add ? ' add "' + add + '"' : '';
                    var removeName = remove ? ' remove "' + remove + '"' : '';
                    var options = addName + removeName;

                    // Generate the tag
                    var tag = '[uarepeater ' + name + '' + options + '][/uarepeater]';

                    // Set the generated tag into the dialog's tag input field
                    var $tagInput = $dialog.find('input[data-tag-part="tag"]');
                    $tagInput.val(tag);
                }

                updateTag();

                // Attach change event listeners to the relevant input fields
                $dialog.on('.uarepeater-add, .uarepeater-remove, [data-tag-part="name"]').on('change', function () {
                    updateTag();
                });

            }
        });
    });

})(jQuery)
