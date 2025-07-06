<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'uacf7_conditional_field_pro_init', 20 );
function uacf7_conditional_field_pro_init(){
     
    add_action( 'wp_enqueue_scripts', 'uacf7_conditional_field_pro_scripts' ); 
}

function uacf7_conditional_field_pro_scripts() {
    
    wp_enqueue_script( 'uacf7-conditional-pro-script', plugin_dir_url( __FILE__ ) . 'js/script.js', array('uacf7-cf-script'), null, true );
}
 
