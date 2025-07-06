document.addEventListener('wpcf7mailsent', function (event) { 
    if (event.detail.status == 'mail_sent' && uacf7_pro_object.product_dropdown == true && uacf7_pro_object.auto_cart == true ) { 
        var $product = [];

        jQuery('.wpcf7-form.sent .uacf7_auto_cart_'+event.detail.contactFormId+' .wpcf7-uacf7_product_dropdown').find('option:selected').each(function () {
      
            var product_id =jQuery(this).attr('product-id');
            var variation_id =jQuery(this).attr('variation-id');  
            
            var data = {
                product_id: product_id,
                variation_id: variation_id, 
            };
            $product.push(data); 

        });   
        if(typeof $product !== 'undefined'  ){ 
            
            jQuery('.wpcf7-form.sent .uacf7_auto_cart_'+event.detail.contactFormId+' .single-product-grid').find('input:checked').each(function () {
                var product_id = jQuery(this).attr('product-id'); 
                var variation_id =jQuery(this).attr('variation-id');  
                var variations =jQuery(this).attr('variation-data');  
                var data = {
                    product_id: product_id,
                    variation_id: variation_id,
                    variation_data: variations,
                };
                $product.push(data); 
                
            });
        } 
        // return false;

        if ( typeof $product !== 'undefined' && $product.length != 0 ) {  
            jQuery.ajax({
                url: uacf7_pro_object.ajaxurl,
                type: 'post',
                data: {
                    action: 'uacf7_ajax_add_to_cart_product',
                    product_ids: $product,
                },
                success: function (data) {  
                    if( uacf7_pro_object.redirect_to[event.detail.contactFormId] == 'cart' ){
                        location.href = uacf7_pro_object.cart_page;
                    }
                    if( uacf7_pro_object.redirect_to[event.detail.contactFormId] == 'checkout' ){
                        location.href = uacf7_pro_object.checkout_page;
                    }
                },
                error: function (jqXHR, exception) {
                    var error_msg = '';
                    if (jqXHR.status === 0) {
                        var error_msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        var error_msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        var error_msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        var error_msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        var error_msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        var error_msg = 'Ajax request aborted.';
                    } else {
                        var error_msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert(error_msg);
                }
            });
        }
    }

}, false);
