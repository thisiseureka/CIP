;(function ($) {
    'use strict';
    $( document ).ready(function() {
        $( ".uacf7-star-ratting" ).each(function() {   
         

            var data_star = $(this).parent().find('.uacf7-star-ratting input[type=radio]:checked').attr('data-star');  
            $(this).parent().parent().find('.emoji-'+data_star+'').addClass('active'); 
            $(this).parent().find('.uacf7-star-ratting.style-5 .uacf7-star-'+data_star+' i').css('transform', 'scale(1.4)'); 
            $(this).parent().find('.uacf7-star-ratting.style-9 .uacf7-star-'+data_star+'').addClass('border-right'); 
            for (let i = 0; i <= data_star; i++) {   
                $(this).find('.uacf7-star-'+i+'').addClass("active");
                $(this).find('.uacf7-star-'+i+'').addClass("checked"); 
               
            } 
            for (let i = 5; i > data_star; i--) {   
                $(this).parent().parent().find('.uacf7-star-'+i+'').removeClass("active");
                $(this).parent().parent().find('.uacf7-star-'+i+'').removeClass("checked");
            } 

            // On hover

            $(".uacf7-star").hover(function () {
                var data_star = $(this).attr('data-star'); 
                for (let i = 0; i <= data_star; i++) {   
                    $(this).parent().find('.uacf7-star-'+i+' .uacf7-icon').addClass("active"); 
                } 
                for (let i = 5; i > data_star; i--) {   
                    $(this).parent().find('.uacf7-star-'+i+' .uacf7-icon').removeClass("active"); 
                } 
                
             },
             function () { 
                $(this).parent().find('.uacf7-icon').removeClass("active")
              }
             );


            //  On Click  
            $(document).on('click', '.uacf7-icon', function(){  
                // on load Selected
                $(this).parent().find('.uacf7-star').removeClass("addClass");
                $(this).parent().parent().find('input').prop('checked', false);  

                var data_star =  $(this).parent().attr('data-star');  
                $(this).parent().find('icon').addClass('active');
                for (let i = 0; i <= data_star; i++) {   
                    $(this).parent().parent().find('.uacf7-star-'+i+'').addClass("active");
                    $(this).parent().parent().find('.uacf7-star-'+i+'').addClass("checked");
                } 
                for (let i = 5; i > data_star; i--) {   
                    $(this).parent().parent().find('.uacf7-star-'+i+'').removeClass("active");
                    $(this).parent().parent().find('.uacf7-star-'+i+'').removeClass("checked");
                    
                } 
                $(this).parent().find('input').prop('checked', true);   
            });

        
        });
         

        // Style three
        $(document).on('click', '.uacf7-star-ratting-wrap.style-3 .uacf7-icon', function(){   
            var data_star =  $(this).parent().attr('data-star');  
            $(this).parent().parent().parent().find('.emoji').removeClass('active');
            $(this).parent().parent().parent().find('.emoji-'+data_star+'').addClass('active'); 
            $(this).parent().find('input').attr('checked', true); 
        }); 

        // Style four
        $(document).on('click', '.uacf7-star-ratting-wrap.style-4 .uacf7-icon', function(){    
            var data_star =  $(this).parent().attr('data-star');  
            $(this).parent().parent().parent().find('.emoji').removeClass('active');
            $(this).parent().parent().parent().find('.emoji-'+data_star+'').addClass('active'); 
            $(this).parent().find('input').attr('checked', true); 

        });

        // Style Five 
        $(document).on('click', '.uacf7-star-ratting.style-5 .uacf7-icon', function(){ 
            $(this).parent().parent().find('.uacf7-icon i').css('transform', 'scale(1)');  
            $(this).find('i').css('transform', 'scale(1.4)');   
            $(this).parent().find('input').attr('checked', true); 
        });

        // Style six
        $(document).on('click', '.uacf7-star-ratting.style-6 .uacf7-icon', function(){  
            $(this).find('i').css('transform', 'scale(1)');  
            // $(this).find('i').css('transform', 'scale(1.4)');   
            $(this).parent().parent().find('.uacf7-star.uacf7-star-disabled').removeClass("active");
            $(this).parent().parent().find('i').css('transform', 'scale(1)'); 
        });
        $(document).on('click', '.uacf7-star-ratting.style-6 .uacf7-star.uacf7-star-disabled .uacf7-icon', function(){   
            $(this).parent().addClass("active");
            $(this).find('i').css('transform', 'scale(1.4)');  
            $$(this).parent().parent().find(".uacf7-star input").removeAttr('checked'); 

        });

        // Stye nine

        $( ".uacf7-star-ratting.style-9" ).each(function() {   
            $(this).find(".uacf7-icon").click(function(){   
                $(this).parent().parent().find('.uacf7-star').removeClass('border-right');  
                $(this).parent().addClass("active");
                $(this).parent().addClass("border-right"); 

            });
        }); 

        // Style ten  
        
        $( ".uacf7-star-ratting.style-10" ).each(function() {   
            var data_star1 = $(this).find('input').attr('data-star1');
            var data_star5 = $(this).find('input').attr('data-star5');
            var data_selected = $(this).find('input').attr('data-selected');
           
            $(this).find('.uacf7-star-10').rateYo({
                rating: data_selected,
                numStars: 5,
                precision: 2,
                minValue: data_star1,
                maxValue: data_star5
              }).on("rateyo.set", function (e, data) {  
                var get_input = $(this).parent().find('input').val(data.rating); 
                // $(this).parent().find('input').trigger('keyup');
              });
        });

        // Star Review Carousel
        $('.ueacf7-review-carousel').each(function(){
            var $this = $(this);
            var column = $(this).attr("data-column")
            $this.owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                lazyLoad: true,
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:2
                    },
                    1000:{
                        items:column
                    }
                }
            })
        });
        
       
    });


   
 
})(jQuery);