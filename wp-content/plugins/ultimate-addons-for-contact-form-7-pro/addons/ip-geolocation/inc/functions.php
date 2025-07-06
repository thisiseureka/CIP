<?php

//Visitor IP address
function getVisIpAddr() {

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
		return $_SERVER['HTTP_CLIENT_IP']; 
	} 
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
		return $_SERVER['HTTP_X_FORWARDED_FOR']; 
	} 
	else { 
		return $_SERVER['REMOTE_ADDR']; 
	} 
}

//Default country code
add_action( 'uacf7_country_dropdown_atts', 'uacf7_country_dropdown_atts_add_autocomplete', 10);
function uacf7_country_dropdown_atts_add_autocomplete($tag) {
	$ip = getVisIpAddr();
	$addr = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
	
	$autocomplete = $tag->get_option( 'autocomplete', '', true );
	
	if( $autocomplete == 'true' && !empty($addr['countryCode']) ){
    	echo 'country-code="'.strtolower($addr['countryCode']).'"';
	}
}

//Filter for enabeling geo fields
add_filter('uacf7_enable_ip_geo_fields', 'uacf7_enable_ip_geo_fields_filter');
function uacf7_enable_ip_geo_fields_filter($x){
	if(function_exists('uacf7_checked')){
		return uacf7_checked('uacf7_enable_ip_geo_fields');
	}else{
		return '';
	}
}