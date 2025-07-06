<?php

if ( ( defined( "RSSSL_DISABLE_REGION_BLOCK" ) && RSSSL_DISABLE_REGION_BLOCK ) || (defined( 'RSSSL_SAFE_MODE' ) && RSSSL_SAFE_MODE)  || ! file_exists( $plugin_dir ) ) {
	return;
}


/**
 * Blocks or allows access based on the country of the visitor's IP address.
 *
 * @param  array  $countries_blocked  An array of country codes representing the countries to block.
 * @param  array  $white_list  An array of IP addresses to whitelist, allowing access regardless of country.
 * @param  string  $db_file  The path to the IP-to-country database file.
 *
 * @return bool Whether access should be blocked (true) or allowed (false) based on the visitor's country.
 */
function rsssl_block_countries( array $countries_blocked, array $white_list, string $db_file, string $plugin_dir, string $country_detection_file, $ip_fetcher_file ): bool {
	// Skip country blocking if the Wordpress Cron is running.
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		return false;
	}

    require_once $country_detection_file;
    require_once $ip_fetcher_file;

	$ip_fetcher = new RSSSL\Pro\Security\WordPress\Limitlogin\Rsssl_IP_Fetcher();
	$ip_address = $ip_fetcher->get_ip_address()[0];

	// If there is no IP address, we can't determine the country.
	if ( empty( $ip_address ) ) {
		return false;
	}

	$country_code = RSSSL\Pro\Security\WordPress\Limitlogin\Rsssl_Country_Detection::get_country_by_ip_headers( $db_file, $ip_address );

	$is_blocked = in_array( $country_code, $countries_blocked, true );
	$is_whitelisted = $ip_fetcher->is_ip_address_in_range( $white_list, $ip_address );

	// Block only if the country is blocked and the IP is not whitelisted.
	return $is_blocked && ! $is_whitelisted;
}

if ( rsssl_block_countries( $countries_blocked, $white_list, $db_file, $plugin_dir, $country_detection_file, $ip_fetcher_file ) ) {
	$dir = dirname( __DIR__, 3 );
	$block_url = "$dir/assets/templates/403-page.php";
	http_response_code( 403 );
	require_once $block_url;
	exit;
}
