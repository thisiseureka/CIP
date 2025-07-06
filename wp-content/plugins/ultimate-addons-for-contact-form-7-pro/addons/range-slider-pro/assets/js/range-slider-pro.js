; (function ($) {
    'use strict';
    
    $( document ).ready(function() { 
        var handel_width = range_handle.handle_width;
        var handle_height = range_handle.handle_height; 
      // Style One
      $(document).on('input', '.range_slider', function() {
          var range_value = $(this).val();
          var max = $(this).attr("max");
          var min = $(this).attr("min"); 
          var newValue = Number( (range_value - min) * 100 / (max - min) );  
          $(this).parent().parent().find('#range_value span').html( range_value );
          $(this).css( 'background', 'linear-gradient(to right, var(--uacf7-slider-Selection-Color) 0%, var(--uacf7-slider-Selection-Color) '+newValue +'%, #d3d3d3 ' + newValue + '%, #d3d3d3 100%)' );
      });
   
      
      // Style Two
      $( ".style-2 .range_slider" ).each(function() { 
                var ratio_100 = Number((handel_width / 100) );
                var ratio_2 = Number((handel_width / 2) );
                var max = $(this).attr("max");
                var min = $(this).attr("min");
                var range_value = $(this).val();
                var newValue = Number( (range_value - min) * 100 / (max - min) );
                var  newPosition = ratio_2 - (newValue * ratio_100);
                $(this).parent().parent().find('#range_value').css( 'left',  'calc('+newValue+'% + ('+newPosition+'px))' );  
            $(document).on('input', '.style-2 .range_slider', function() { 
                range_value = $(this).val(); 
                newValue = Number( (range_value - min) * 100 / (max - min) );
                newPosition = ratio_2 - (newValue * ratio_100);
                $(this).parent().parent().find('#range_value').css( 'left',  'calc('+newValue+'% + ('+newPosition+'px))' );  
                $(this).parent().parent().find('#range_value span').html( range_value );
                $(this).css( 'background', 'linear-gradient(to right, var(--uacf7-slider-Selection-Color) 0%, var(--uacf7-slider-Selection-Color) '+newValue +'%, #d3d3d3 ' + newValue + '%, #d3d3d3 100%)' );
            }); 
      });
       
      


    // style 3 with step
    $( ".single-slider" ).each(function() {
        var width = $('.demo-output').width(); 
        var handle = $(this).data("handle"); 
        var label = $(this).data("label"); 
        var min = $(this).data("min");
        var max = $(this).data("max");
        var def = $(this).data("default");
        var steps = $(this).data("steps");
        var scale  = steps.split(",") 
        var step = $(this).data("step");
        if(handle == 2){
            $(this).jRange({
              from: 0,
              to: max,
              step: step,
              scale: scale,
              format: '%s', 
              width: width,
              showLabels: true,
              isRange : true
          });
        }else{
            $(this).jRange({
              from: 0,
              to: max,
              step: step,
              scale: scale,
              width: width,
              format: '%s', 
              showLabels: true,
              snap: true
          });
        }
 
        $(this).change(function() {
          $(this).trigger('keyup');
        });

    });
   
   
    
  }); 
  })(jQuery);