/*
* Multistep script
* Button text
*/
jQuery( document ).ready(function(){
    
    jQuery('.uacf7-step').each(function(){
        $next_btn = jQuery(this).attr('next-btn-text');
        $prev_btn = jQuery(this).attr('prev-btn-text');
        
        if( $next_btn != '' ){
            jQuery('.uacf7-next',this).text($next_btn);
        }
        if( $prev_btn != '' ){
            jQuery('.uacf7-prev',this).text($prev_btn);
        }
        
    });
    
    jQuery('.wpcf7-form').each(function(){
        jQuery('.steps-step a.uacf7-btn-active', this).parent().addClass('step-complete');
        
        jQuery(".steps-step a", this).on('click', function(){
            jQuery(this).parent().addClass('step-complete');
            jQuery(this).parent().prevAll('.steps-step').addClass('step-complete');
            jQuery(this).parent().nextAll('.steps-step').removeClass('step-complete');
        });
    });
    
});

document.addEventListener("DOMContentLoaded", function () {
    const stepsContainer = document.querySelector(".uacf7-steps.steps-form.progressbar-style-9, .uacf7-steps.steps-form.progressbar-style-8");
    if (!stepsContainer) return;

    const stepsRow = stepsContainer.querySelector(".steps-row.setup-panel");
    const leftBtn = document.querySelector(".left-btn");
    const rightBtn = document.querySelector(".right-btn");

    const scrollStep = 200;

    function updateArrows() {
        const maxScrollLeft = stepsContainer.scrollWidth - stepsContainer.clientWidth;

        if (stepsRow.scrollWidth > stepsContainer.clientWidth) {
            rightBtn.style.display = "flex"; 
        } else {
            rightBtn.style.display = "none";
            leftBtn.style.display = "none";
        }

        if (stepsContainer.scrollLeft > 0) {
            leftBtn.style.display = "flex";
        } else {
            leftBtn.style.display = "none";
        }

        if (stepsContainer.scrollLeft >= maxScrollLeft - 1) {
            rightBtn.style.display = "none";
        }
    }

    rightBtn.addEventListener("click", function () {
        stepsContainer.scrollBy({ left: scrollStep, behavior: "smooth" });
    });

    leftBtn.addEventListener("click", function () {
        stepsContainer.scrollBy({ left: -scrollStep, behavior: "smooth" });
    });

    stepsContainer.addEventListener("scroll", updateArrows);

    // Run on page load
    updateArrows();
    window.addEventListener("resize", updateArrows);
});
