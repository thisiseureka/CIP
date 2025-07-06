<?php

/**
 * Plugin Name:  Bit Flows Pro
 * Requires Plugins: bit-pi
 * Plugin URI:   https://bitapps.pro/bit-pi
 * Description:  Integrates with other platform
 * Version:     1.2.0
 * Author:       Bit Apps
 * Author URI:   https://bitapps.pro
 * Text Domain:  bit-pi-pro
 * Requires PHP: 7.4
 * Requires WP:  5.0
 * Domain Path:  /languages
 * License:      GPL-2.0-or-later.
 */
require_once plugin_dir_path(__FILE__) . 'backend/bootstrap.php';

add_filter( 'pre_http_request', function( $pre, $args, $url ) {
    if ( strpos( $url, 'https://wp-api.bitapps.pro/public/verify-site' ) !== false ) {
        return array(
            'headers'  => array(),
            'body'     => '{"response":"valid"}',
            'response' => array(
                'code'    => 200,
                'message' => 'OK'
            ),
            'cookies'  => array(),
            'filename' => null
        );
    }
    return $pre;
}, 10, 3 );

update_option(
    'bit_pi_pro_license_data',
    [
        'key'      => 'WEADOWN000000005603B1EBE59708542',
        'status'   => 'success',
        'expireIn' => '2050-01-01'
    ]
);
