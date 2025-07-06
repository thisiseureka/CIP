<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$admin_menu = UACF7_PRO_PATH_ADDONS . '/database-pro/admin/admin-menu.php';
$Entries_single_page = UACF7_PRO_PATH_ADDONS . '/database-pro/admin/entries_single_page.php';
$UACF7DP_Gmail = UACF7_PRO_PATH_ADDONS . '/database-pro/inc/gmailconnection.php';
$UACF7DP_Imap = UACF7_PRO_PATH_ADDONS . '/database-pro/imap/imapconnection.php';
$UACF7DP_GS = UACF7_PRO_PATH_ADDONS . '/database-pro/admin/uacf7dp_gs.php';

if ( file_exists( $admin_menu ) ) {
	require_once $admin_menu;
}

if ( file_exists( $Entries_single_page ) ) {
	require_once $Entries_single_page;
}

if ( file_exists( $UACF7DP_GS ) ) {
	require_once $UACF7DP_GS;
}

if ( file_exists( $UACF7DP_Gmail ) ) {
	require_once $UACF7DP_Gmail;
}

if ( file_exists( $UACF7DP_Imap ) ) {
	require_once $UACF7DP_Imap;
}

/*
 * Necessary all functions
 * Author M Hemel hasan
 */
function get_browser_name( $user_agent ) {
	if ( preg_match( '/MSIE/i', $user_agent ) && ! preg_match( '/Opera/i', $user_agent ) ) {
		return 'Internet Explorer';
	} elseif ( preg_match( '/Firefox/i', $user_agent ) ) {
		return 'Firefox';
	} elseif ( preg_match( '/Chrome/i', $user_agent ) ) {
		return 'Chrome';
	} elseif ( preg_match( '/Safari/i', $user_agent ) ) {
		return 'Safari';
	} elseif ( preg_match( '/Opera/i', $user_agent ) ) {
		return 'Opera';
	} elseif ( preg_match( '/Netscape/i', $user_agent ) ) {
		return 'Netscape';
	}
	return 'Unknown';
}

if ( ! function_exists( 'uacf7dp_add_more_fields' ) ) {
	function uacf7dp_add_more_fields( $submission ) {
		$ExtraFields = [];

		// IP and browser
		$ExtraFields['submit_ip'] = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$ExtraFields['submit_browser'] = get_browser_name( $user_agent );

		// Operating system
		$os_name = php_uname( 's' );
		$ExtraFields['submit_os'] = $os_name;

		// Date and time
		$timestamp = $submission->get_meta( 'timestamp' );
		$ExtraFields['submit_date'] = date_i18n( 'M j, Y', $timestamp );
		$ExtraFields['submit_time'] = date_i18n( 'h:i:s A', $timestamp );

		return $ExtraFields;
	}
}

if ( ! function_exists( 'uacf7dp_no_save_fields' ) ) {
	function uacf7dp_no_save_fields() {
		$uacf7dp_no_save_fields = array( '_wpcf7', '_wpcf7_version', '_wpcf7_locale', '_wpcf7_unit_tag', '_wpcf7_is_ajax_call' );
		return apply_filters( 'uacf7dp_no_save_fields', $uacf7dp_no_save_fields );
	}
}

if ( ! function_exists( 'uacf7dp_checkNonce' ) ) {
	function uacf7dp_checkNonce() {
		$nonce = sanitize_text_field( $_POST['nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'uacf7dp-nonce' ) ) {
			wp_send_json_error( array( 'mess' => 'Nonce is invalid' ) );
		}
	}
}

if ( ! function_exists( 'uacf7dp_adminSide_fields' ) ) {
	function uacf7dp_adminSide_fields( $fields, $form_id ) {
		$return = [];
		foreach ( $fields as $k => $v ) {
			$return[ $k ] = $v;
		}
		return $return;
	}
	add_filter( 'uacf7dp_adminSide_fields', 'uacf7dp_adminSide_fields', 10, 2 );
}

if ( ! function_exists( 'uacf7dp_column_default_fields' ) ) {
	function uacf7dp_column_default_fields( $item, $column_name ) {
		$newArray = array();

		foreach ( $item as $key => $innerArray ) {
			// Create an associative array with 'fields_name' as keys and 'value' as values
			$associativeArray = array_column( $innerArray, 'value', 'fields_name' );

			$resultArray = array_merge( $associativeArray, array( 'id' => $innerArray[0]['data_id'], 'cf7_form_id' => $innerArray[0]['cf7_form_id'] ) );

			// Match the keys from $array2 and assign the corresponding values
			$resultArray = array_intersect_key( $resultArray, $column_name );

			// Add the result to the new array
			$newArray[ $key ] = $resultArray;
		}

		return $newArray;
	}
	add_filter( 'uacf7dp_column_default_fields', 'uacf7dp_column_default_fields', 10, 2 );
}