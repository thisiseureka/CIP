jQuery(document).ready(function(){
    //Select 2
    jQuery(".uacf7_post_taxonomy").select2();

    //Wp editor content
    if (typeof tinyMCE !== 'undefined' && tinyMCE.get('uacf7_post_content')) {
        tinymce.get('uacf7_post_content').on('keyup input blur click paste',function(e){
            var tiny = tinyMCE.get('uacf7_post_content').getContent();
            if (jQuery('#uacf7_post_content').length > 0 && typeof tiny === 'string' ) {
                jQuery('#uacf7_post_content').val(tiny);
            }
        });
    }

});