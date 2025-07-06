<?php
/*
Admin menu- Save post submission menu
*/
add_filter( 'uacf7_save_admin_menu', 'uacf7_save_post_submission', 10, 2 );
function uacf7_save_post_submission( $sanitary_values, $input ){
    
    if ( isset( $input['uacf7_enable_post_submission'] ) ) {
        $sanitary_values['uacf7_enable_post_submission'] = $input['uacf7_enable_post_submission'];
    }
    return $sanitary_values;
}