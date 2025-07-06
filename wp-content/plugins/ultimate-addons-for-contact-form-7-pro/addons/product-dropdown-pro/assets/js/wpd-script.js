;(function ($) {
    'use strict';

    $(document).ready(function () {
        $("input[type='checkbox']").click(function () {  
            $(this).parent().toggleClass("active");
        });
        $(".single-product-grid input[type='radio']").click(function () {  
            $('.absulate-hover').removeClass("active");
            $(this).parent().toggleClass("active");
        });

        $(document).on( 'click', '.single-product-grid input', function (e) {
            
            $('.uacf7-wpd-quick-view').remove();   
            var $this = $(this);
            if ($this.is(':checked') == false) {
                return 
            }
            var $product_type = $this.attr('product-type');
            if($product_type == 'variable'){
                e.preventDefault();
                $('.single-product-grid').find('.uacf7-wpd-quick-view').remove();
                var product_id = $this.attr('product-id'); 
                $.ajax({
                     type: "post",
                     url:  uacf7_wpd_params.ajax_url,
                     data: {
                         action: "uacf7_wpd_variable_product_quick_view",
                         security: uacf7_wpd_params.ajax_nonce,
                         product_id: product_id,
                     },
                     beforeSend: function (data) {
                        $this.closest('.single-product-grid .s-product-img').append('<div class="loading">  <img src="'+uacf7_wpd_params.ajax_loader+'" alt="">  <div>');
                         $(".ins-quick-view").block();
                     },
                     success: function (data) {
                        $this.closest('.single-product-grid .s-product-img ').find('.loading').remove(); 
                         $this.closest('.single-product-grid').append('<div class="uacf7-wpd-quick-view">'+data+'</div>'); 
                         $('.single-product-grid').find('.uacf7-wpd-quick-view quantity, .uacf7-wpd-quick-view .single_add_to_cart_button ').remove();
                         $('.single-product-grid').find('.single_variation_wrap ').append('<button  class="uacf7-wpd-variant-select"><i class="fa fa-shopping-cart"></i> Select</button>');
                         
                    },
                     error: function (data) {
                         console.log(data);
                     },
                 }); 
            }
           
        });

        $(document).on('click','.uacf7-wpd-variant-select',function(e){
            e.preventDefault();
            var $this = $(this);
            var $selected = '';
            var variant = [];
            var attr_selected = true;
            $this.closest('.uacf7-wpd-quick-view').find('select').each(function(){
                var $this = $(this);
                if($this.find('option:selected').val() == ''){ 
                    alert('Please select some product options before adding this product to your cart.');
                    attr_selected = false;
                    return false
                }
                $selected += $this.find('option:selected').val()+ ' - ';   
                variant.push({ variant_name : $this.attr('name'), variant_value : $this.find('option:selected').val()}); 
            });  
            if(variant != '' ){
                variant = JSON.stringify(variant)
            }
            $this.closest('.single-product-grid').find('.absulate-hover input').attr('variation-data',variant); 
            var product_title = $this.closest('.single-product-grid').find('.s-product-content h5 a').html();
            var product_price = $this.closest('.single_variation_wrap').find('ins .woocommerce-Price-amount').text();
            if(product_price == ''){
                var product_price = $this.closest('.single_variation_wrap').find('.woocommerce-Price-amount').text();
            }
            // alert(product_price);
            $this.closest('.single-product-grid').find('.absulate-hover input').attr('product-price', product_price.replace(/[^a-zA-Z0-9.]/g, ''));
            $this.closest('.single-product-grid').find('.absulate-hover input').val(product_title+' - '+ $selected + ' '+product_price).prop('checked', true);
            var variation_id = $this.closest('.single-product-grid').find('.variation_id').val();
            $this.closest('.single-product-grid').find('.absulate-hover input').attr('variation-id', variation_id);

            uacf7_show_product_price($this.closest('.uacf7-show-porduct-price'));

            if(attr_selected == true){
                $('.uacf7-wpd-quick-view').remove(); 
            }
           
            

        });
             

        // Show Product Dropdown
        function uacf7_show_product_price($this){ 
            var items = []
            $this.find("input:checked").each(function(){
                if(typeof $(this).attr('product-price') !== "undefined" ){
                    items.push($(this).attr('product-price'));
                }    
            }); 
            var total = 0;
            for (var i = 0; i < items.length; i++) {
                total += items[i] << 0;
            } 
            $this.find('.product_total_amount').html(total);
           
        }

        $('.uacf7-show-porduct-price').each(function(){
            var $this = $(this);
            $this.find('input').click(function(){ 
                uacf7_show_product_price($this)
            });
         })



        //Script for Select2

        var forms  = $(".wpcf7");

        forms.each(function(){

            var formId = $(this).find('input[name="_wpcf7"]').val(); 

            var uacf7_form = $(`.uacf7-form-${formId}`).find('.wpcf7-uacf7_product_dropdown');
            var select2_type = uacf7_form.attr('uacf7-select2-type');
            var last = $('.uacf7_repeater').find('.uacf7_repeater_sub_field').find('textarea');

            $(document).ready(function () {
                if(select2_type === 'single'){
                    $(uacf7_form).select2();
                }
                if(select2_type === 'multiple'){
                    uacf7_form.select2({
                        closeOnSelect: false,
                        theme: "classic"
                        
                    });

                }
            });

        });
       
    });
    
})(jQuery);
