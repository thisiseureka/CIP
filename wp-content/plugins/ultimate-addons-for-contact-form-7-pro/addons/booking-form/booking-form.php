<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

/**
 * Including Plugin file
 * 
 * @since 1.0
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Other defines
define( 'BFCF7_URL', plugin_dir_url( __FILE__ ) );
define( 'BFCF7_PATH', plugin_dir_path( __FILE__ ) );
define( 'BFCF7_ASSETS_URL', BFCF7_URL . 'assets/' );
define( 'BFCF7_ADMIN_URL', BFCF7_URL . 'admin/' );
// add_filter( 'wpcf7_load_js', '__return_false' );



/**
 * Including Booking Option Setting  File
 *
 * @since 1.0.6
 */

require_once( 'inc/admin/admin-menu.php' );




/**
 * Including Booking Google api Integration File
 *
 * @since 1.0.6
 */

require_once( 'inc/functions.php' );


//Filter for enabeling Booking fields

if ( ! function_exists( 'uacf7_enable_booking_form_filter' ) ) {
	add_filter( 'uacf7_enable_booking_form', 'uacf7_enable_booking_form_filter' );
	function uacf7_enable_booking_form_filter( $x ) {
		if ( function_exists( 'uacf7_checked' ) ) {
			return uacf7_checked( 'uacf7_enable_booking_form' );
		} else {
			return '';
		}
	}
}


/**
 * Check if Ultimate Booking Form is active in the settings, and if it isn't, disable the plugin.
 *
 * @since 1.0
 */
function bfcf7_checked( $name ) {
	//Get settings option
	$uacf7_options = get_option( 'uacf7_settings' );
	if ( isset( $uacf7_options[ $name ] ) && $uacf7_options[ $name ] == true ) {
		return true;
	} else {
		return false;
	}
}
if ( bfcf7_checked( 'uacf7_enable_booking_form' ) != true ) {
	return;
}



// Define BFCF7.
if ( ! defined( 'BFCF7' ) ) {
	define( 'BFCF7', '1.1.5' );
}

/**
 * Enqueue Admin scripts
 * 
 * @since 1.0
 */

if ( ! function_exists( 'bfcf7_enqueue_admin_scripts' ) ) {
	function bfcf7_enqueue_admin_scripts() {

		// flatpickr
		wp_enqueue_script( 'flatpickr', BFCF7_ASSETS_URL . 'flatpickr/flatpickr.min.js', array( 'jquery' ), '4.6.9', true );
		wp_enqueue_script( 'flatpickr-range', BFCF7_ASSETS_URL . 'flatpickr/rangePlugin.min.js', array( 'jquery' ), '4.6.9', true );
		// jquery-timepicker
		wp_enqueue_script( 'jquery.timepicker', BFCF7_ADMIN_URL . 'js/jquery.timepicker.min.js', array( 'jquery' ), '1.13.18', true );
		// Custom
		wp_enqueue_style( 'bfcf7', BFCF7_ADMIN_URL . 'css/admin.css', '', date( "his" ) );
		wp_enqueue_script( 'bfcf7', BFCF7_ADMIN_URL . 'js/admin.js', array( 'jquery' ), date( "his" ), true );
		wp_localize_script( 'bfcf7', 'bfcf7_params',
			array(
				'bfcf7_nonce' => wp_create_nonce( 'updates' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}
	add_action( 'admin_enqueue_scripts', 'bfcf7_enqueue_admin_scripts' );
}

/**
 * Check if Contact Form 7 is active, and if it isn't, disable the plugin.
 *
 * @since 1.0
 */
if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
	return;
}

/**
 * Dequeue frontend scripts to avoid conflict
 * 
 * @since 1.0
 */
if ( ! function_exists( 'bfcf7_dequeue_scripts' ) ) {
	function bfcf7_dequeue_scripts() {

		wp_deregister_script( 'flatpickr' );
		wp_dequeue_script( 'flatpickr' );
		wp_deregister_script( 'jquery.timepicker' );
		wp_dequeue_script( 'jquery.timepicker' );

	}
	add_filter( 'wp_enqueue_scripts', 'bfcf7_dequeue_scripts', 9999 );
}

/**
 * Enqueue frontend scripts
 * 
 * @since 1.0
 */
if ( ! function_exists( 'bfcf7_enqueue_scripts' ) ) {
	function bfcf7_enqueue_scripts() {

		// flatpickr
		wp_enqueue_script( 'flatpickr', BFCF7_ASSETS_URL . 'flatpickr/flatpickr.min.js', array( 'jquery' ), '4.6.9', true );
		// jquery-timepicker
		wp_enqueue_script( 'jquery.timepicker', BFCF7_ASSETS_URL . 'jquery-timepicker/jquery.timepicker.min.js', array( 'jquery' ), '1.13.18', true );
		// Custom
		wp_enqueue_style( 'bfcf7', BFCF7_ASSETS_URL . 'css/custom.css', '', date( "his" ) );
		wp_enqueue_script( 'bfcf7', BFCF7_ASSETS_URL . 'js/custom.js', array( 'jquery' ), date( "his" ), true );

		$checkout_url = '';
		if ( function_exists( 'wc_get_checkout_url' ) ) {
			$checkout_url = wc_get_checkout_url();
		}

		$cart_url = '';
		if ( function_exists( 'wc_get_cart_url' ) ) {
			$cart_url = wc_get_cart_url();
		}
		wp_localize_script( 'bfcf7', 'bfcf7_pro_object',
			array(
				'checkout_page' => $checkout_url,
				'cart_page' => $cart_url,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

	}
	add_filter( 'wp_enqueue_scripts', 'bfcf7_enqueue_scripts', 99999 );
}

/**
 * Add form tag
 * 
 * @since 1.0
 */
if ( ! function_exists( 'bfcf7_form_tag' ) ) {
	function bfcf7_form_tag() {
		wpcf7_add_form_tag( array( 'uacf7_booking_form_date', 'uacf7_booking_form_date*' ), 'uacf7_booking_form_date_tag_handler', array( 'name-attr' => true ) );
		wpcf7_add_form_tag( array( 'uacf7_booking_form_time', 'uacf7_booking_form_time*' ), 'uacf7_booking_form_time_tag_handler', array( 'name-attr' => true ) );
	}
	add_action( 'wpcf7_init', 'bfcf7_form_tag' );
}

/**
 * Form Tag handler
 * uacf7_booking_form_date
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_booking_form_date_tag_handler' ) ) {
	function uacf7_booking_form_date_tag_handler( $tag ) {


		$wpcf7 = WPCF7_ContactForm::get_current();

		$form_id = $wpcf7->id();
		// Booking Post Meta
		$booking = uacf7_get_form_option( $form_id, 'booking' );
		// get saved value      
		$bf_enable = isset( $booking['bf_enable'] ) ? $booking['bf_enable'] : false;

		$bf_duplicate_status = ! empty( $booking['bf_duplicate_status'] ) ? $booking['bf_duplicate_status'] : false;
		$event_date = ! empty( $booking['event_date'] ) ? $booking['event_date'] : '';
		$event_time = ! empty( $booking['event_time'] ) ? $booking['event_time'] : '';


		// Frontend values
		$date_mode_front = ! empty( $booking['date_mode_front'] ) ? $booking['date_mode_front'] : 'single';
		$bf_date_theme = ! empty( $booking['bf_date_theme'] ) ? $booking['bf_date_theme'] : 'single';

		// Allowed dates
		$bf_allowed_date = ! empty( $booking['bf_allowed_date'] ) ? $booking['bf_allowed_date'] : 'always';
		$allowed_start_date = ! empty( $booking['allowed_start_date'] ) ? $booking['allowed_start_date'] : '';
		$allowed_end_date = ! empty( $booking['allowed_end_date'] ) ? $booking['allowed_end_date'] : '';
		$allowed_specific_date = ! empty( $booking['allowed_specific_date'] ) ? $booking['allowed_specific_date'] : '';
		$allowed_specific_date = explode( ',', $allowed_specific_date );


		$min_date = ! empty( $booking['allowed_min_max_date']['from'] ) ? $booking['allowed_min_max_date']['from'] : '';
		$max_date = ! empty( $booking['allowed_min_max_date']['to'] ) ? $booking['allowed_min_max_date']['to'] : '';
		// Disabled dates 

		$disable_day_1 = is_array( $booking['disable_day'] ) && in_array( 1, $booking['disable_day'] ) ? 1 : 8;
		$disable_day_2 = is_array( $booking['disable_day'] ) && in_array( 2, $booking['disable_day'] ) ? 2 : 8;
		$disable_day_3 = is_array( $booking['disable_day'] ) && in_array( 3, $booking['disable_day'] ) ? 3 : 8;
		$disable_day_4 = is_array( $booking['disable_day'] ) && in_array( 4, $booking['disable_day'] ) ? 4 : 8;
		$disable_day_5 = is_array( $booking['disable_day'] ) && in_array( 5, $booking['disable_day'] ) ? 5 : 8;
		$disable_day_6 = is_array( $booking['disable_day'] ) && in_array( 6, $booking['disable_day'] ) ? 6 : 8;
		$disable_day_0 = is_array( $booking['disable_day'] ) && in_array( 0, $booking['disable_day'] ) ? 0 : 8;
		// Uacf7 Print Rr

		$disabled_start_date = ! empty( $booking['disabled_date']['from'] ) ? $booking['disabled_date']['from'] : '';
		$disabled_end_date = ! empty( $booking['disabled_date']['to'] ) ? $booking['disabled_date']['to'] : '';
		$disabled_specific_date = ! empty( $booking['disabled_specific_date'] ) ? $booking['disabled_specific_date'] : '';
		$disabled_specific_date = explode( ',', $disabled_specific_date );

		$time_one_step = ! empty( $booking['time_one_step'] ) ? $booking['time_one_step'] : 30;
		$time_two_step = ! empty( $booking['time_two_step'] ) ? $booking['time_two_step'] : '';


		// WooCommerce
		$bf_woo = ! empty( $booking['bf_woo'] ) ? $booking['bf_woo'] : '';
		$bf_product = ! empty( $booking['bf_product'] ) ? $booking['bf_product'] : '';
		$bf_product_id = ! empty( $booking['bf_product_id'] ) ? $booking['bf_product_id'] : '';
		$bf_product_name = ! empty( $booking['bf_product_name'] ) ? $booking['bf_product_name'] : '';
		$bf_product_price = ! empty( $booking['bf_product_price'] ) ? $booking['bf_product_price'] : '';



		if ( $bf_duplicate_status == true ) {
			global $wpdb;
			$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "uacf7_form WHERE  form_id = %s", $form_id ) );
			$store_data = [];
			if ( count( $data ) > 0 ) {
				foreach ( $data as $value ) {
					$booking_data = json_decode( $value->form_value );
					if ( ! empty( $booking_data->$event_date ) ) {
						$rep_form_time = $booking_data->$event_time;
						if ( substr( $rep_form_time, -2 ) == 'am' || substr( $rep_form_time, -2 ) == 'pm' ) {
							$rep_to_time = substr_replace( $rep_form_time, '', -2 );
						} else {
							$rep_to_time = $rep_form_time;
						}

						list( $hour, $minute ) = explode( ':', $rep_to_time );

						$all_seconds = 0;
						$all_seconds += $hour * 3600;
						$all_seconds += $minute * 60;
						$all_seconds += $time_one_step * 60;
						$all_seconds += 00;
						$total_minutes = floor( $all_seconds / 60 );
						$seconds = $all_seconds % 60;
						$hours = floor( $total_minutes / 60 );
						$minutes = $total_minutes % 60;


						$rep_to_time = sprintf( '%02d:%02d', $hours, $minutes );
						if ( substr( $rep_form_time, -2 ) == 'am' || substr( $rep_form_time, -2 ) == 'pm' ) {
							$rep_to_time = $rep_to_time . substr( $rep_form_time, -2 );
						}

						$disable_data = [];
						$disable_data[] = $rep_form_time;

						if ( $rep_to_time == '13:00am' ) {
							$rep_to_time = '1:00am';
						} else if ( $rep_to_time == '13:00pm' ) {
							$rep_to_time = '1:00pm';
						}

						$disable_data[] = $rep_to_time;

						if ( isset( $store_data[ $booking_data->$event_date ] ) ) {
							array_push( $store_data[ $booking_data->$event_date ], $disable_data );
						} else {
							$store_data[ $booking_data->$event_date ] = [ $disable_data ];
						}
					}
				}
			}

			$store_data = json_encode( $store_data, true );

		} else {
			$store_data = '';
		}



		$booking_date = array(
			'bf_enable' => $bf_enable,
			'date_mode_front' => $date_mode_front,
			'bf_date_theme' => $bf_date_theme,
			'bf_allowed_date' => $bf_allowed_date,
			'allowed_start_date' => $allowed_start_date,
			'allowed_end_date' => $allowed_end_date,
			'allowed_specific_date' => $allowed_specific_date,
			'min_date' => $min_date,
			'max_date' => $max_date,
			'disable_day_1' => $disable_day_1,
			'disable_day_2' => $disable_day_2,
			'disable_day_3' => $disable_day_3,
			'disable_day_4' => $disable_day_4,
			'disable_day_5' => $disable_day_5,
			'disable_day_6' => $disable_day_6,
			'disable_day_0' => $disable_day_0,
			'disabled_start_date' => $disabled_start_date,
			'disabled_end_date' => $disabled_end_date,
			'disabled_specific_date' => $disabled_specific_date,
			'bf_woo' => $bf_woo,
			'bf_product' => $bf_product,
			'bf_product_id' => $bf_product_id,
			'bf_product_name' => $bf_product_name,
			'bf_product_price' => $bf_product_price,
			'store_data' => $store_data,
		);


		if ( empty( $tag->name ) ) {
			return '';
		}
		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		$class .= ' wpcf7-validates-as-date';

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$atts['class'] = $tag->get_class_option( $class );
		$atts['class'] .= ' bf-form-input-date';
		$atts['date-data'] = json_encode( $booking_date );
		$atts['id'] = $tag->name;
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );
		$atts['min'] = $tag->get_date_option( 'min' );
		$atts['max'] = $tag->get_date_option( 'max' );
		$atts['step'] = $tag->get_option( 'step', 'int', true );

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		if ( $validation_error ) {
			$atts['aria-invalid'] = 'true';
			$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
				$tag->name
			);
		} else {
			$atts['aria-invalid'] = 'false';
		}

		$value = (string) reset( $tag->values );

		$value = $tag->get_default_option( $value );

		if ( $value ) {
			$datetime_obj = date_create_immutable(
				preg_replace( '/[_]+/', ' ', $value ),
				wp_timezone()
			);

			if ( $datetime_obj ) {
				$value = $datetime_obj->format( 'Y-m-d' );
			}
		}

		$value = wpcf7_get_hangover( $tag->name, $value );

		$atts['value'] = $value;

		if ( wpcf7_support_html5() ) {
			$atts['type'] = $tag->basetype;
		} else {
			$atts['type'] = 'text';
		}

		$atts['name'] = $tag->name;

		$atts = wpcf7_format_atts( $atts );

		$html = sprintf(
			'<span class="wpcf7-form-control-wrap %1$s"><input %2$s autocomplete="off" />%3$s</span>',
			sanitize_html_class( $tag->name ), $atts, $validation_error
		);

		return $html;
	}
}

/**
 * Form Tag handler
 * uacf7_booking_form_time
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_booking_form_time_tag_handler' ) ) {
	function uacf7_booking_form_time_tag_handler( $tag ) {
		if ( empty( $tag->name ) ) {
			return '';
		}

		$wpcf7 = WPCF7_ContactForm::get_current();

		$form_id = $wpcf7->id();

		// Booking Post Meta
		$booking = uacf7_get_form_option( $form_id, 'booking' );

		// Allowed Times 
		$bf_allowed_time = ! empty( $booking['bf_allowed_time'] ) ? $booking['bf_allowed_time'] : 'always';
		$time_day_1 = ! empty( $booking['allowed_time_day'][0] ) ? $booking['allowed_time_day'][0] : '';
		$time_day_2 = ! empty( $booking['allowed_time_day'][1] ) ? $booking['allowed_time_day'][1] : '';
		$time_day_3 = ! empty( $booking['allowed_time_day'][2] ) ? $booking['allowed_time_day'][2] : '';
		$time_day_4 = ! empty( $booking['allowed_time_day'][3] ) ? $booking['allowed_time_day'][3] : '';
		$time_day_5 = ! empty( $booking['allowed_time_day'][4] ) ? $booking['allowed_time_day'][4] : '';
		$time_day_6 = ! empty( $booking['allowed_time_day'][5] ) ? $booking['allowed_time_day'][5] : '';
		$time_day_0 = ! empty( $booking['allowed_time_day'][6] ) ? $booking['allowed_time_day'][6] : '';

		$min_day_time = ! empty( $booking['min_day_time'] ) ? $booking['min_day_time'] : '';
		$max_day_time = ! empty( $booking['max_day_time'] ) ? $booking['max_day_time'] : '';
		$specific_date_time = ! empty( $booking['specific_date_time'] ) ? $booking['specific_date_time'] : '';

		//Time
		$time_format_front = ! empty( $booking['time_format_front'] ) ? $booking['time_format_front'] : 'g:ia';
		$min_time = ! empty( $booking['min_time'] ) ? $booking['min_time'] : '';
		$max_time = ! empty( $booking['max_time'] ) ? $booking['max_time'] : '';
		$from_dis_time = ! empty( $booking['from_dis_time'] ) ? $booking['from_dis_time'] : '';
		$to_dis_time = ! empty( $booking['to_dis_time'] ) ? $booking['to_dis_time'] : '';
		$time_one_step = ! empty( $booking['time_one_step'] ) ? $booking['time_one_step'] : '30';
		$time_two_step = ! empty( $booking['time_two_step'] ) ? $booking['time_two_step'] : '';



		$booking_time = array(
			'bf_allowed_time' => $bf_allowed_time,
			'time_day_1' => $time_day_1,
			'time_day_2' => $time_day_2,
			'time_day_3' => $time_day_3,
			'time_day_4' => $time_day_4,
			'time_day_5' => $time_day_5,
			'time_day_6' => $time_day_6,
			'time_day_0' => $time_day_0,
			'min_day_time' => $min_day_time,
			'max_day_time' => $max_day_time,
			'specific_date_time' => $specific_date_time,
			'time_format_front' => $time_format_front,
			'min_time' => $min_time,
			'max_time' => $max_time,
			'from_dis_time' => $from_dis_time,
			'to_dis_time' => $to_dis_time,
			'time_one_step' => $time_one_step,
			'time_two_step' => $time_two_step,

		);


		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		$class .= ' wpcf7-validates-as-date';

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$atts['class'] = $tag->get_class_option( $class );
		$atts['class'] .= ' bf-form-input-time';
		$atts['time-data'] = json_encode( $booking_time );
		$atts['id'] = $tag->name;
		$atts['data-date'] = '0';
		$atts['data-time-min'] = '0';
		$atts['data-time-max'] = '0';
		$atts['min'] = $tag->get_date_option( 'min' );
		$atts['max'] = $tag->get_date_option( 'max' );

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		if ( $validation_error ) {
			$atts['aria-invalid'] = 'true';
			$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
				$tag->name
			);
		} else {
			$atts['aria-invalid'] = 'false';
		}

		$value = (string) reset( $tag->values );

		$value = $tag->get_default_option( $value );

		if ( $value ) {
			$datetime_obj = date_create_immutable(
				preg_replace( '/[_]+/', ' ', $value ),
				wp_timezone()
			);

			if ( $datetime_obj ) {
				$value = $datetime_obj->format( 'g:ia' );
			}
		}

		$value = wpcf7_get_hangover( $tag->name, $value );

		$atts['value'] = $value;

		if ( wpcf7_support_html5() ) {
			$atts['type'] = $tag->basetype;
		} else {
			$atts['type'] = 'text';
		}

		$atts['name'] = $tag->name;

		$atts = wpcf7_format_atts( $atts );

		// Showing time zone
		$timezone_string = get_option( 'timezone_string' );
		$offset = (float) get_option( 'gmt_offset' );
		$hours = (int) $offset;
		$minutes = ( $offset - $hours );
		$sign = ( $offset < 0 ) ? '-' : '+';
		$abs_hour = abs( $hours );
		$abs_mins = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
		$timezone = $timezone_string ? $timezone_string . ' [' . $tz_offset . ']' : $tz_offset;

		$html = sprintf(
			'<span class="wpcf7-form-control-wrap %1$s"><input %2$s autocomplete="off" />%3$s</span>',
			sanitize_html_class( $tag->name ), $atts, $validation_error
		);

		$html .= __( "Timezone: ", "ultimate-addons-cf7" ) . '' . $timezone;

		return $html;
	}
}

/**
 * Custom Validation Filter
 * modified from contact form 7
 * 
 * @since 1.0
 */
// Validation filter for date
add_filter( 'wpcf7_validate_uacf7_booking_form_date', 'uacf7_date_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_uacf7_booking_form_date*', 'uacf7_date_validation_filter', 10, 2 );
function uacf7_date_validation_filter( $result, $tag ) {
	$name = $tag->name;

	$value = isset( $_POST[ $name ] )
		? trim( strtr( (string) $_POST[ $name ], "\n", " " ) )
		: '';

	if ( $tag->is_required() and '' === $value ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	}

	return $result;
}

// Validation filter for time
add_filter( 'wpcf7_validate_uacf7_booking_form_time', 'uacf7_time_validation_filter', 10, 2 );
add_filter( 'wpcf7_validate_uacf7_booking_form_time*', 'uacf7_time_validation_filter', 10, 2 );
function uacf7_time_validation_filter( $result, $tag ) {
	$name = $tag->name;

	$value = isset( $_POST[ $name ] )
		? trim( strtr( (string) $_POST[ $name ], "\n", " " ) )
		: '';

	if ( $value ) {
		$datetime_obj = date_create_immutable(
			preg_replace( '/[_]+/', ' ', $value ),
			wp_timezone()
		);

		if ( $datetime_obj ) {
			$value = $datetime_obj->format( 'g:ia' );
		}
	}

	if ( $tag->is_required() and '' === $value ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
	} elseif ( '' !== $value and ! uacf7_is_time( $value, 'g:ia' ) ) {
		$result->invalidate( $tag, wpcf7_get_message( 'invalid_time' ) );
	}

	return $result;
}

// Validation message for time
add_filter( 'wpcf7_messages', 'uacf7_time_messages', 10, 1 );
function uacf7_time_messages( $messages ) {
	return array_merge( $messages, array(
		'invalid_time' => array(
			'description' => __( "Time format that the sender entered is invalid", 'ultimate-addons-cf7' ),
			'default' => __( "The time format is incorrect.", 'ultimate-addons-cf7' ),
		),
	) );
}
// is_time function
function uacf7_is_time( string $date, string $format = 'Y-m-d' ) {
	$dateObj = DateTime::createFromFormat( $format, $date );
	return $dateObj && $dateObj->format( $format ) == $date;
}

/**
 * Add form tag generator
 * 
 * @since 1.0
 */
if ( ! function_exists( 'tag_generator' ) ) {
	function tag_generator() {
		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'uacf7_booking_form_date',
			__( 'Booking Form Date', 'ultimate-addons-cf7' ),
			'tg_panel_booking_form_date',
			array( 'version' => '2' )
		);
		$tag_generator->add(
			'uacf7_booking_form_time',
			__( 'Booking Form Time', 'ultimate-addons-cf7' ),
			'tg_panel_booking_form_time',
			array( 'version' => '2' )
		);

	}
	add_action( 'admin_init', 'tag_generator' );
}

/**
 * Form tag generator handler
 * uacf7_booking_form_date
 * 
 * @since 1.0
 */
if ( ! function_exists( 'tg_panel_booking_form_date' ) ) {
	function tg_panel_booking_form_date( $cf, $options ) {

		$field_types = array(
			'uacf7_booking_form_date' => array(
				'display_name' => __( 'Booking Form Date', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Booking Form Date', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );

		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_booking_form_date']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_booking_form_date']['description'],
				array(
					'a' => array( 'href' => true ),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);

			echo $description;
			?></p>
			<div class="uacf7-doc-notice">
				<?php echo sprintf(
					__( 'Confused? Check our Documentation on  %1s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-booking-form/" target="_blank">Booking Form</a>'
				); ?>
			</div>
		</header>
		<div class="control-box">

			<?php

			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_booking_form_date' => $field_types['uacf7_booking_form_date']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );

			?>


			<div class="uacf7-doc-notice uacf7-guide">
				<?php echo _e( "To activate the form, enable it from the Booking Form tab located
				under the Ultimate Addons for CF7 Options. This tab also contains additional settings.", "ultimate-addons-cf7"); ?>
			</div>

		</div>
		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}
}

/**
 * Form tag generator handler
 * uacf7_booking_form_time
 * 
 * @since 1.0
 */
if ( ! function_exists( 'tg_panel_booking_form_time' ) ) {
	function tg_panel_booking_form_time( $cf, $options ) {
		$field_types = array(
			'uacf7_booking_form_time' => array(
				'display_name' => __( 'Booking Form Time', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Booking Form Time', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );

		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_booking_form_time']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_booking_form_time']['description'],
				array(
					'a' => array( 'href' => true ),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);

			echo $description;
			?></p>
			<div class="uacf7-doc-notice">
				<?php echo sprintf(
					__( 'Confused? Check our Documentation on  %1s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-booking-form/" target="_blank">Booking Form</a>'
				); ?>
			</div>
		</header>
		<div class="control-box">
			<?php

			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_booking_form_time' => $field_types['uacf7_booking_form_time']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );

			?>
			<fieldset>
				<div class="uacf7-doc-notice uacf7-guide">
					<?php echo __( 'To activate the form, enable it from the \'Booking Form\' tab located under the Ultimate Addons for CF7 Options. This tab also contains additional settings', 'ultimate-addons-cf7' ) ?>
				</div>
			</fieldset>
		</div>
		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}
}

/**
 * Create tab panel
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_bf_add_panel' ) ) {
	function uacf7_bf_add_panel( $panels ) {
		$panels['uacf7-bf-panel'] = array(
			'title' => __( 'Ultimate Booking Form', 'ultimate-addons-cf7' ),
			'callback' => 'uacf7_create_bf_panel_fields',
		);
		return $panels;
	}
	// add_action( 'wpcf7_editor_panels', 'uacf7_bf_add_panel' );
}

/**
 * Tab panel fields
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_create_bf_panel_fields' ) ) {
	function uacf7_create_bf_panel_fields( $post ) {

		// get saved value      
		$bf_enable = ! empty( get_post_meta( $post->id(), 'bf_enable', true ) ) ? get_post_meta( $post->id(), 'bf_enable', true ) : '';
		$bf_duplicate_status = ! empty( get_post_meta( $post->id(), 'bf_duplicate_status', true ) ) ? get_post_meta( $post->id(), 'bf_duplicate_status', true ) : '';

		// Frontend values
		$date_mode_front = ! empty( get_post_meta( $post->id(), 'date_mode_front', true ) ) ? get_post_meta( $post->id(), 'date_mode_front', true ) : 'single';
		$bf_date_theme = ! empty( get_post_meta( $post->id(), 'bf_date_theme', true ) ) ? get_post_meta( $post->id(), 'bf_date_theme', true ) : '';

		// Allowed dates
		$bf_allowed_date = ! empty( get_post_meta( $post->id(), 'bf_allowed_date', true ) ) ? get_post_meta( $post->id(), 'bf_allowed_date', true ) : 'always';
		$allowed_start_date = ! empty( get_post_meta( $post->id(), 'allowed_start_date', true ) ) ? get_post_meta( $post->id(), 'allowed_start_date', true ) : '';
		$allowed_end_date = ! empty( get_post_meta( $post->id(), 'allowed_end_date', true ) ) ? get_post_meta( $post->id(), 'allowed_end_date', true ) : '';
		$allowed_specific_date = ! empty( get_post_meta( $post->id(), 'allowed_specific_date', true ) ) ? get_post_meta( $post->id(), 'allowed_specific_date', true ) : '';
		$min_date = ! empty( get_post_meta( $post->id(), 'min_date', true ) ) ? get_post_meta( $post->id(), 'min_date', true ) : '';
		$max_date = ! empty( get_post_meta( $post->id(), 'max_date', true ) ) ? get_post_meta( $post->id(), 'max_date', true ) : '';
		// Disabled dates
		$disable_day_1 = ! empty( get_post_meta( $post->id(), 'disable_day_1', true ) ) ? get_post_meta( $post->id(), 'disable_day_1', true ) : '';
		$disable_day_2 = ! empty( get_post_meta( $post->id(), 'disable_day_2', true ) ) ? get_post_meta( $post->id(), 'disable_day_2', true ) : '';
		$disable_day_3 = ! empty( get_post_meta( $post->id(), 'disable_day_3', true ) ) ? get_post_meta( $post->id(), 'disable_day_3', true ) : '';
		$disable_day_4 = ! empty( get_post_meta( $post->id(), 'disable_day_4', true ) ) ? get_post_meta( $post->id(), 'disable_day_4', true ) : '';
		$disable_day_5 = ! empty( get_post_meta( $post->id(), 'disable_day_5', true ) ) ? get_post_meta( $post->id(), 'disable_day_5', true ) : '';
		$disable_day_6 = ! empty( get_post_meta( $post->id(), 'disable_day_6', true ) ) ? get_post_meta( $post->id(), 'disable_day_6', true ) : '';
		$disable_day_0 = get_post_meta( $post->id(), 'disable_day_0', true ) != '' ? get_post_meta( $post->id(), 'disable_day_0', true ) : '';
		$disabled_start_date = ! empty( get_post_meta( $post->id(), 'disabled_start_date', true ) ) ? get_post_meta( $post->id(), 'disabled_start_date', true ) : '';
		$disabled_end_date = ! empty( get_post_meta( $post->id(), 'disabled_end_date', true ) ) ? get_post_meta( $post->id(), 'disabled_end_date', true ) : '';
		$disabled_specific_date = ! empty( get_post_meta( $post->id(), 'disabled_specific_date', true ) ) ? get_post_meta( $post->id(), 'disabled_specific_date', true ) : '';

		// // Allowed Times 
		$bf_allowed_time = ! empty( get_post_meta( $post->id(), 'bf_allowed_time', true ) ) ? get_post_meta( $post->id(), 'bf_allowed_time', true ) : 'always';
		$time_day_1 = ! empty( get_post_meta( $post->id(), 'time_day_1', true ) ) ? get_post_meta( $post->id(), 'time_day_1', true ) : '';
		$time_day_2 = ! empty( get_post_meta( $post->id(), 'time_day_2', true ) ) ? get_post_meta( $post->id(), 'time_day_2', true ) : '';
		$time_day_3 = ! empty( get_post_meta( $post->id(), 'time_day_3', true ) ) ? get_post_meta( $post->id(), 'time_day_3', true ) : '';
		$time_day_4 = ! empty( get_post_meta( $post->id(), 'time_day_4', true ) ) ? get_post_meta( $post->id(), 'time_day_4', true ) : '';
		$time_day_5 = ! empty( get_post_meta( $post->id(), 'time_day_5', true ) ) ? get_post_meta( $post->id(), 'time_day_5', true ) : '';
		$time_day_6 = ! empty( get_post_meta( $post->id(), 'time_day_6', true ) ) ? get_post_meta( $post->id(), 'time_day_6', true ) : '';
		$time_day_0 = get_post_meta( $post->id(), 'time_day_0', true ) != '' ? get_post_meta( $post->id(), 'time_day_0', true ) : '';

		$min_day_time = ! empty( get_post_meta( $post->id(), 'min_day_time', true ) ) ? get_post_meta( $post->id(), 'min_day_time', true ) : '';
		$max_day_time = ! empty( get_post_meta( $post->id(), 'max_day_time', true ) ) ? get_post_meta( $post->id(), 'max_day_time', true ) : '';
		$specific_date_time = ! empty( get_post_meta( $post->id(), 'specific_date_time', true ) ) ? get_post_meta( $post->id(), 'specific_date_time', true ) : '';
		// Time
		$time_format_front = ! empty( get_post_meta( $post->id(), 'time_format_front', true ) ) ? get_post_meta( $post->id(), 'time_format_front', true ) : '';
		$min_time = ! empty( get_post_meta( $post->id(), 'min_time', true ) ) ? get_post_meta( $post->id(), 'min_time', true ) : '';
		$max_time = ! empty( get_post_meta( $post->id(), 'max_time', true ) ) ? get_post_meta( $post->id(), 'max_time', true ) : '';
		$from_dis_time = ! empty( get_post_meta( $post->id(), 'from_dis_time', true ) ) ? get_post_meta( $post->id(), 'from_dis_time', true ) : '';
		$to_dis_time = ! empty( get_post_meta( $post->id(), 'to_dis_time', true ) ) ? get_post_meta( $post->id(), 'to_dis_time', true ) : '';
		$time_one_step = ! empty( get_post_meta( $post->id(), 'time_one_step', true ) ) ? get_post_meta( $post->id(), 'time_one_step', true ) : '';
		$time_two_step = ! empty( get_post_meta( $post->id(), 'time_two_step', true ) ) ? get_post_meta( $post->id(), 'time_two_step', true ) : '';
		//$ = !empty(get_post_meta( $post->id(), '', true )) ? get_post_meta( $post->id(), '', true ) : '';


		// WooCommerce
		$bf_woo = ! empty( get_post_meta( $post->id(), 'bf_woo', true ) ) ? get_post_meta( $post->id(), 'bf_woo', true ) : '';
		$bf_product = ! empty( get_post_meta( $post->id(), 'bf_product', true ) ) ? get_post_meta( $post->id(), 'bf_product', true ) : '';
		$bf_product_id = ! empty( get_post_meta( $post->id(), 'bf_product_id', true ) ) ? get_post_meta( $post->id(), 'bf_product_id', true ) : '';
		$bf_product_name = ! empty( get_post_meta( $post->id(), 'bf_product_name', true ) ) ? get_post_meta( $post->id(), 'bf_product_name', true ) : '';
		$bf_product_price = ! empty( get_post_meta( $post->id(), 'bf_product_price', true ) ) ? get_post_meta( $post->id(), 'bf_product_price', true ) : '';
		//$ = !empty(get_post_meta( $post->id(), '', true )) ? get_post_meta( $post->id(), '', true ) : '';

		// Event Calendar Issue : 
		$calendar_event_enable = ! empty( get_post_meta( $post->id(), 'calendar_event_enable', true ) ) ? get_post_meta( $post->id(), 'calendar_event_enable', true ) : '';
		$event_email = ! empty( get_post_meta( $post->id(), 'event_email', true ) ) ? get_post_meta( $post->id(), 'event_email', true ) : '';
		$event_date = ! empty( get_post_meta( $post->id(), 'event_date', true ) ) ? get_post_meta( $post->id(), 'event_date', true ) : '';
		$event_time = ! empty( get_post_meta( $post->id(), 'event_time', true ) ) ? get_post_meta( $post->id(), 'event_time', true ) : '';
		$event_summary = ! empty( get_post_meta( $post->id(), 'event_summary', true ) ) ? get_post_meta( $post->id(), 'event_summary', true ) : '';

		?>
		<div class="ultimate-bf-admin">
			<h1><?php _e( 'Ultimate Booking Form', 'ultimate-addons-cf7' ); ?></h1>
			<div class="uacf7-doc-notice">Confused? Check our Documentation on <a
					href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-booking-form/" target="_blank">Booking
					Form</a>.</div>
			<fieldset>
				<div class="main-block">
					<div class="sub-block">
						<h3><?php _e( 'Enable/Disable Booking Form', 'ultimate-addons-cf7' ); ?></h3>
						<label for="bf-enable">
							<input class="bf-enable" id="bf_enable" name="bf_enable" type="checkbox" value="1" <?php checked( '1', $bf_enable, true ); ?>> <?php _e( 'Enable Booking Form', 'ultimate-addons-cf7' ); ?>
						</label>
					</div>
				</div>
				<div class="main-block">
					<div class="sub-block">
						<h3><?php _e( 'Enable/Disable Duplicate Booking', 'ultimate-addons-cf7' ); ?></h3>
						<label for="bf_duplicate_status">
							<input class="bf-enable" id="bf_duplicate_status" name="bf_duplicate_status" type="checkbox"
								value="1" <?php checked( '1', $bf_duplicate_status, true ); ?>>
							<?php _e( 'Disable Duplicate Booking Form', 'ultimate-addons-cf7' ); ?>
						</label>
					</div>
				</div>
				<div class="main-block">
					<div class="sub-block">
						<h3><?php _e( 'Enable/Disable Calendar Event', 'ultimate-addons-cf7' ); ?></h3>
						<label for="bf-enable">
							<input class="calendar-enable" id="calendar_event_enable" name="calendar_event_enable"
								type="checkbox" value="1" <?php checked( '1', $calendar_event_enable, true ); ?>>
							<?php _e( 'Enable Calendar Event', 'ultimate-addons-cf7' ); ?>
						</label>
						<br>
						<br>
						<?php $all_fields = $post->scan_form_tags(); ?>
						<table>
							<tr>
								<td>
									<div class="sub-block">
										<label><?php _e( 'Event Email', 'ultimate-addons-cf7' ); ?></label>
										<select name="event_email" id="event_email">
											<?php
											$all_tags = $post->scan_form_tags( array( 'type' => 'email', 'type' => 'email*' ) );
											foreach ( $all_tags as $tag ) {
												echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $event_email, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
											}
											?>
										</select>
									</div>
								</td>
								<td>
									<div class="sub-block">
										<label><?php _e( 'Event Summary', 'ultimate-addons-cf7' ); ?></label>
										<select name="event_summary" id="event_summary">
											<?php
											foreach ( $all_fields as $tag ) {
												if ( $tag['type'] != 'submit' ) {
													echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $event_summary, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
												}
											}
											?>
										</select>
									</div>
								</td>
								<td>
									<div class="sub-block">
										<label><?php _e( 'Event Date', 'ultimate-addons-cf7' ); ?></label>
										<select name="event_date" id="event_date">
											<?php
											foreach ( $all_fields as $tag ) {
												if ( $tag['type'] != 'submit' ) {
													echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $event_date, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
												}
											}
											?>
										</select>
									</div>
								</td>
								<td>
									<div class="sub-block">
										<label><?php _e( 'Event Time', 'ultimate-addons-cf7' ); ?></label>
										<select name="event_time" id="event_time">
											<?php
											foreach ( $all_fields as $tag ) {
												if ( $tag['type'] != 'submit' ) {
													echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $event_time, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
												}
											}
											?>
										</select>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>



				<h2><?php _e( 'Date Configuration', 'ultimate-addons-cf7' ); ?></h2>

				<div class="main-block">
					<h3><?php _e( 'Date Settings for Frontend', 'ultimate-addons-cf7' ); ?></h3>
					<div class="sub-block">
						<h4><?php _e( 'Date Selection Mode', 'ultimate-addons-cf7' ); ?></h4>
						<label><input type="radio" name="date_mode_front" class="" id="" value="single" <?php if ( $date_mode_front == 'single' ) {
							echo 'checked';
						} ?> />
							<?php _e( 'Single date', 'ultimate-addons-cf7' ); ?></label>
						<label><input type="radio" name="date_mode_front" class="" id="" value="range" <?php if ( $date_mode_front == 'range' ) {
							echo 'checked';
						} ?> />
							<?php _e( 'Range of date', 'ultimate-addons-cf7' ); ?></label>
					</div>

					<div class="sub-block">
						<h4><?php _e( 'Calendar Theme', 'ultimate-addons-cf7' ); ?></h4>
						<label><?php _e( 'Select Theme', 'ultimate-addons-cf7' ); ?></label>
						<select name="bf_date_theme" id="">
							<option value="default" <?php selected( "default", $bf_date_theme ); ?>>Default</option>
							<option value="dark" <?php selected( "dark", $bf_date_theme ); ?>>Dark</option>
							<option value="material_blue" <?php selected( "material_blue", $bf_date_theme ); ?>>Material Blue
							</option>
							<option value="material_green" <?php selected( "material_green", $bf_date_theme ); ?>>Material Green
							</option>
							<option value="material_red" <?php selected( "material_red", $bf_date_theme ); ?>>Material Red
							</option>
							<option value="material_orange" <?php selected( "material_orange", $bf_date_theme ); ?>>Material
								Orange</option>
							<option value="airbnb" <?php selected( "airbnb", $bf_date_theme ); ?>>Airbnb</option>
							<option value="confetti" <?php selected( "confetti", $bf_date_theme ); ?>>Confetti</option>
						</select>
					</div>
				</div>

				<div class="main-block">
					<h3><?php _e( 'Allowed Dates', 'ultimate-addons-cf7' ); ?></h3>
					<div class="sub-block">
						<h4><?php _e( 'Allowed Dates', 'ultimate-addons-cf7' ); ?></h4>
						<label for=""><input type="radio" name="bf_allowed_date" class="" id="bf-date-always" value="always"
								<?php if ( $bf_allowed_date == 'always' ) {
									echo 'checked';
								} ?> />
							<?php _e( 'Always', 'ultimate-addons-cf7' ); ?></label>
						<label for=""><input type="radio" name="bf_allowed_date" class="" id="bf-date-range" value="range" <?php if ( $bf_allowed_date == 'range' ) {
							echo 'checked';
						} ?> />
							<?php _e( 'Ranges', 'ultimate-addons-cf7' ); ?></label>
						<label for=""><input type="radio" name="bf_allowed_date" class="" id="bf-date-specific" value="specific"
								<?php if ( $bf_allowed_date == 'specific' ) {
									echo 'checked';
								} ?> />
							<?php _e( 'Specific', 'ultimate-addons-cf7' ); ?></label>
					</div>

					<div class="sub-block allowed-date-range">
						<h4><?php _e( 'Select Minimum & Maximum Date', 'ultimate-addons-cf7' ); ?></h4>
						<div class="bf-inline">
							<p class="description">
								<label for="min-date">From<br>
									<input type="text" name="min_date" class="min-date" id="min-date" placeholder=""
										autocomplete="off" <?php echo ! empty( $min_date ) ? 'value="' . $min_date . '"' : ''; ?> />
								</label>
							</p>
						</div>
						<div class="bf-inline">
							<p class="description">
								<label for="max-date">To<br>
									<input type="text" name="max_date" class="max-date" id="max-date" placeholder=""
										autocomplete="off" <?php echo ! empty( $max_date ) ? 'value="' . $max_date . '"' : ''; ?> />
								</label>
							</p>
						</div>
					</div>

					<div class="sub-block allowed-specific-date">
						<h4><?php _e( 'Specific Date', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="allowed-specific-date">
								<input type="text" name="allowed_specific_date" class="allowed-specific-date large-text"
									id="allowed-specific-date" placeholder="" autocomplete="off" <?php echo ! empty( $allowed_specific_date ) ? 'value="' . $allowed_specific_date . '"' : ''; ?> />
							</label>
						</p>
					</div>
				</div>

				<div class="main-block cond-disabled-date">
					<h3><?php _e( 'Disabled Dates', 'ultimate-addons-cf7' ); ?></h3>
					<div class="sub-block">
						<h4><?php _e( 'Select day to disable', 'ultimate-addons-cf7' ); ?></h4>
						<label for="disable-day-1"><input class="" id="disable-day-1" name="disable_day_1" type="checkbox"
								value="1" <?php checked( '1', $disable_day_1, true ); ?>>
							<?php _e( 'Monday', 'ultimate-addons-cf7' ); ?></label>
						<label for="disable-day-2"><input class="" id="disable-day-2" name="disable_day_2" type="checkbox"
								value="2" <?php checked( '2', $disable_day_2, true ); ?>>
							<?php _e( 'Tuesday', 'ultimate-addons-cf7' ); ?></label>
						<label for="disable-day-3"><input class="" id="disable-day-3" name="disable_day_3" type="checkbox"
								value="3" <?php checked( '3', $disable_day_3, true ); ?>>
							<?php _e( 'Wednesday', 'ultimate-addons-cf7' ); ?></label>
						<label for="disable-day-4"><input class="" id="disable-day-4" name="disable_day_4" type="checkbox"
								value="4" <?php checked( '4', $disable_day_4, true ); ?>>
							<?php _e( 'Thursday', 'ultimate-addons-cf7' ); ?></label>
						<label for="disable-day-5"><input class="" id="disable-day-5" name="disable_day_5" type="checkbox"
								value="5" <?php checked( '5', $disable_day_5, true ); ?>>
							<?php _e( 'Friday', 'ultimate-addons-cf7' ); ?></label>
						<label for="disable-day-6"><input class="" id="disable-day-6" name="disable_day_6" type="checkbox"
								value="6" <?php checked( '6', $disable_day_6, true ); ?>>
							<?php _e( 'Saturday', 'ultimate-addons-cf7' ); ?></label>
						<label for="disable-day-0"><input class="" id="disable-day-0" name="disable_day_0" type="checkbox"
								value="0" <?php checked( '0', $disable_day_0, true ); ?>>
							<?php _e( 'Sunday', 'ultimate-addons-cf7' ); ?></label>
					</div>

					<div class="sub-block ">
						<h4><?php _e( 'Select a date range to disable', 'ultimate-addons-cf7' ); ?></h4>
						<div class="bf-inline">
							<p class="description">
								<label for="disabled-start-date">From<br>
									<input type="text" name="disabled_start_date" class="disabled-start-date"
										id="disabled-start-date" placeholder="" autocomplete="off" <?php echo ! empty( $disabled_start_date ) ? 'value="' . $disabled_start_date . '"' : ''; ?> />
								</label>
							</p>
						</div>
						<div class="bf-inline">
							<p class="description">
								<label for="disabled-end-date">To<br>
									<input type="text" name="disabled_end_date" class="disabled-end-date" id="disabled-end-date"
										placeholder="" autocomplete="off" <?php echo ! empty( $disabled_end_date ) ? 'value="' . $disabled_end_date . '"' : ''; ?> />
								</label>
							</p>
						</div>
					</div>

					<div class="sub-block">
						<h4><?php _e( 'Disable Specific Dates', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="disabled-specific-date">
								<input type="text" name="disabled_specific_date" class="disabled-specific-date large-text"
									id="disabled-specific-date" placeholder="" autocomplete="off" <?php echo ! empty( $disabled_specific_date ) ? 'value="' . $disabled_specific_date . '"' : ''; ?> />
							</label>
						</p>
					</div>
				</div>

				<h2><?php _e( 'Time Configuration', 'ultimate-addons-cf7' ); ?></h2>

				<div class="main-block">
					<h3><?php _e( 'Time Settings', 'ultimate-addons-cf7' ); ?></h3>
					<div class="sub-block">
						<h4><?php _e( 'Time Format for Frontend', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="time-format-front">
								<input type="text" name="time_format_front" class="" id="time-format-front" placeholder="g:ia"
									autocomplete="off" <?php echo ! empty( $time_format_front ) ? 'value="' . $time_format_front . '"' : ''; ?> /><br>
								Default: g:ia . For 24 hours format use H:i . You can find more format <a
									href="https://www.php.net/manual/en/function.date.php" target="_blank">here</a>
							</label>
						</p>
					</div>
					<div class="sub-block">
						<h4><?php _e( 'Select start & end time limit', 'ultimate-addons-cf7' ); ?></h4>
						<div class="bf-inline">
							<p class="description">
								<label for="min-time">Min<br>
									<input type="text" name="min_time" class="" id="min-time" placeholder="" autocomplete="off"
										<?php echo ! empty( $min_time ) ? 'value="' . $min_time . '"' : ''; ?> />
								</label>
							</p>
						</div>
						<div class="bf-inline">
							<p class="description">
								<label for="max-time">Max<br>
									<input type="text" name="max_time" class="" id="max-time" placeholder="" autocomplete="off"
										<?php echo ! empty( $max_time ) ? 'value="' . $max_time . '"' : ''; ?> />
								</label>
							</p>
						</div>
					</div>
					<div class="sub-block">
						<h4><?php _e( 'Disable Time Range', 'ultimate-addons-cf7' ); ?></h4>
						<div class="bf-inline">
							<p class="description">
								<label for="from-dis-time">From<br>
									<input type="text" name="from_dis_time" class="" id="from-dis-time" placeholder=""
										autocomplete="off" <?php echo ! empty( $from_dis_time ) ? 'value="' . $from_dis_time . '"' : ''; ?> />
								</label>
							</p>
						</div>
						<div class="bf-inline">
							<p class="description">
								<label for="to-dis-time">To<br>
									<input type="text" name="to_dis_time" class="" id="to-dis-time" placeholder=""
										autocomplete="off" <?php echo ! empty( $to_dis_time ) ? 'value="' . $to_dis_time . '"' : ''; ?> />
								</label>
							</p>
						</div>
					</div>
					<div class="sub-block">
						<h4><?php _e( 'Time Interval', 'ultimate-addons-cf7' ); ?></h4>
						<div class="bf-inline">
							<p class="description">
								<label for="time-one-step">Time Duration<br>
									<input type="text" name="time_one_step" class="" id="time-one-step" placeholder=""
										autocomplete="off" <?php echo ! empty( $time_one_step ) ? 'value="' . $time_one_step . '"' : ''; ?> /><br>
									Default: 30
								</label>
							</p>
						</div>
						<div class="bf-inline">
							<p class="description">
								<label for="time-two-step">Time Break<br>
									<input type="text" name="time_two_step" class="" id="time-two-step" placeholder=""
										autocomplete="off" <?php echo ! empty( $time_two_step ) ? 'value="' . $time_two_step . '"' : ''; ?> /><br>
									Default: Blank
								</label>
							</p>
						</div>
					</div>
				</div>
				<div class="main-block">
					<h3><?php _e( 'Allowed Time', 'ultimate-addons-cf7' ); ?></h3>
					<div class="sub-block">
						<h4><?php _e( 'Allowed Dates', 'ultimate-addons-cf7' ); ?></h4>
						<label for=""><input type="radio" name="bf_allowed_time" class="" id="bf-time-always" value="always"
								<?php if ( $bf_allowed_time == 'always' ) {
									echo 'checked';
								} ?> />
							<?php _e( 'Always', 'ultimate-addons-cf7' ); ?></label>
						<label for=""><input type="radio" name="bf_allowed_time" class="" id="bf-time-day" value="day" <?php if ( $bf_allowed_time == 'day' ) {
							echo 'checked';
						} ?> />
							<?php _e( 'Day', 'ultimate-addons-cf7' ); ?></label>
						<label for=""><input type="radio" name="bf_allowed_time" class="" id="bf-time-specific" value="specific"
								<?php if ( $bf_allowed_time == 'specific' ) {
									echo 'checked';
								} ?> />
							<?php _e( 'Specific', 'ultimate-addons-cf7' ); ?></label>
					</div>
					<div class="sub-block allowed-day-time-date">
						<h4><?php _e( 'Select day', 'ultimate-addons-cf7' ); ?></h4>
						<label for="time-day-1"><input class="" id="time-day-1" name="time_day_1" type="checkbox" value="1"
								<?php checked( '1', $time_day_1, true ); ?>>
							<?php _e( 'Monday', 'ultimate-addons-cf7' ); ?></label>
						<label for="time-day-2"><input class="" id="time-day-2" name="time_day_2" type="checkbox" value="2"
								<?php checked( '2', $time_day_2, true ); ?>>
							<?php _e( 'Tuesday', 'ultimate-addons-cf7' ); ?></label>
						<label for="time-day-3"><input class="" id="time-day-3" name="time_day_3" type="checkbox" value="3"
								<?php checked( '3', $time_day_3, true ); ?>>
							<?php _e( 'Wednesday', 'ultimate-addons-cf7' ); ?></label>
						<label for="time-day-4"><input class="" id="time-day-4" name="time_day_4" type="checkbox" value="4"
								<?php checked( '4', $time_day_4, true ); ?>>
							<?php _e( 'Thursday', 'ultimate-addons-cf7' ); ?></label>
						<label for="time-day-5"><input class="" id="time-day-5" name="time_day_5" type="checkbox" value="5"
								<?php checked( '5', $time_day_5, true ); ?>>
							<?php _e( 'Friday', 'ultimate-addons-cf7' ); ?></label>
						<label for="time-day-6"><input class="" id="time-day-6" name="time_day_6" type="checkbox" value="6"
								<?php checked( '6', $time_day_6, true ); ?>>
							<?php _e( 'Saturday', 'ultimate-addons-cf7' ); ?></label>
						<label for="time-day-0"><input class="" id="time-day-0" name="time_day_0" type="checkbox" value="0"
								<?php checked( '0', $time_day_0, true ); ?>>
							<?php _e( 'Sunday', 'ultimate-addons-cf7' ); ?></label>
					</div>

					<div class="sub-block allowed-specific-time-date">
						<h4><?php _e( 'Specific Dates', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="specific-date-time">
								<input type="text" name="specific_date_time" class="specific-date-time large-text"
									id="specific-date-time" placeholder="" autocomplete="off" <?php echo ! empty( $specific_date_time ) ? 'value="' . $specific_date_time . '"' : ''; ?> />
							</label>
						</p>
					</div>
					<div class="sub-block allowed-time-date">
						<h4><?php _e( 'Select start & end time limit', 'ultimate-addons-cf7' ); ?></h4>

						<div class="bf-inline">
							<p class="description">
								<label for="min-day-time">Min<br>
									<input type="text" name="min_day_time" class="" id="min-day-time" placeholder=""
										autocomplete="off" <?php echo ! empty( $min_day_time ) ? 'value="' . $min_day_time . '"' : ''; ?> />
								</label>
							</p>
						</div>
						<div class="bf-inline">
							<p class="description">
								<label for="max-day-time">Max<br>
									<input type="text" name="max_day_time" class="" id="max-day-time" placeholder=""
										autocomplete="off" <?php echo ! empty( $max_day_time ) ? 'value="' . $max_day_time . '"' : ''; ?> />
								</label>
							</p>
						</div>
					</div>
				</div>

				<h2><?php _e( 'WooCommerce Configuration', 'ultimate-addons-cf7' ); ?></h2>
				<?php
				if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
					$woo_activation = false;
				} else {
					$woo_activation = true;
				}

				?>

				<div class="main-block">
					<div class="sub-block">
						<h3><?php _e( 'Enable/Disable WooCommerce Integration', 'ultimate-addons-cf7' ); ?></h3>
						<label for="bf-woo">
							<input class="" <?php if ( $woo_activation == false ) {
								echo "disabled";
							} ?> id="bf-woo"
								name="bf_woo" type="checkbox" value="1" <?php checked( '1', $bf_woo, true ); ?>>
							<?php _e( 'Enable WooCommerce', 'ultimate-addons-cf7' ); ?>
							<?php if ( $woo_activation == false ) {
								echo ' <a style="color:red" target="_blank" href="' . admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) . '">( WooCommerce need to active )</a>';
							} ?>
						</label>
					</div>

					<div class="sub-block cond-product-conf">
						<h4><?php _e( 'Select Product', 'ultimate-addons-cf7' ); ?></h4>
						<label for="bf-product-exist"><input type="radio" name="bf_product" class="" id="bf-product-exist"
								value="exist" <?php if ( $bf_product == 'exist' ) {
									echo 'checked';
								} ?> />
							<?php _e( 'Existing Product', 'ultimate-addons-cf7' ); ?></label>
						<label for="bf-product-custom"><input type="radio" name="bf_product" class="" id="bf-product-custom"
								value="custom" <?php if ( $bf_product == 'custom' ) {
									echo 'checked';
								} ?> />
							<?php _e( 'Custom Product', 'ultimate-addons-cf7' ); ?></label>
					</div>
					<div class="sub-block product-exist">
						<h4><?php _e( 'Product ID', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="bf-product-id">
								<input type="text" name="bf_product_id" class="" id="bf-product-id" placeholder=""
									autocomplete="off" <?php echo ! empty( $bf_product_id ) ? 'value="' . $bf_product_id . '"' : ''; ?> /><br>
								Only one product id is allowed
							</label>
						</p>
					</div>
					<div class="sub-block product-custom">
						<h4><?php _e( 'Product Name', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="bf-product-name">
								<input type="text" name="bf_product_name" class="large-text" id="bf-product-name" placeholder=""
									autocomplete="off" <?php echo ! empty( $bf_product_name ) ? 'value="' . $bf_product_name . '"' : ''; ?> />
							</label>
						</p>
					</div>
					<div class="sub-block product-custom">
						<h4><?php _e( 'Product Price', 'ultimate-addons-cf7' ); ?></h4>
						<p class="description">
							<label for="bf-product-price">
								<input type="text" name="bf_product_price" class="" id="bf-product-price" placeholder=""
									autocomplete="off" <?php echo ! empty( $bf_product_price ) ? 'value="' . $bf_product_price . '"' : ''; ?> />
							</label>
						</p>
					</div>
				</div>
			</fieldset>
		</div>
		<script>
			<?php
			// Date conditional
	
			if ( $bf_allowed_date == 'always' ) {
				echo 'var bf_allowed_date = "always";';
			} elseif ( $bf_allowed_date == 'range' ) {
				echo 'var bf_allowed_date = "range";';
			} elseif ( $bf_allowed_date == 'specific' ) {
				echo 'var bf_allowed_date = "specific";';
			} else {
				echo 'var bf_allowed_date = "";';
			}

			// Time conditional
			if ( $bf_allowed_time == 'always' ) {
				echo 'var bf_allowed_time = "always";';
			} elseif ( $bf_allowed_time == 'day' ) {
				echo 'var bf_allowed_time = "day";';
			} elseif ( $bf_allowed_time == 'specific' ) {
				echo 'var bf_allowed_time = "specific";';
			} else {
				echo 'var bf_allowed_time = "";';
			}

			// WooCommerce conditional
			echo $bf_woo ? 'var bf_woo = "1";' : 'var bf_woo = "0";';

			if ( $bf_product == 'exist' ) {
				echo 'var bf_product = "exist";';
			} elseif ( $bf_product == 'custom' ) {
				echo 'var bf_product = "custom";';
			} else {
				echo 'var bf_product = "";';
			}
			?> 
		</script>
		<?php
		wp_nonce_field( 'uacf7_bf_nonce_action', 'uacf7_bf_nonce' );
	}
}



/**
 * Show some properties in the frontend of Contact Form 7 form
 * 
 * Initiate calendar with time in the frontend form
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_bf_properties' ) ) {
	function uacf7_bf_properties( $properties, $post ) {

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

			$form = $properties['form'];
			// get saved value      
			$booking = uacf7_get_form_option( $post->id(), 'booking' );
			$bf_enable = ! empty( $booking['bf_enable'] ) ? $booking['bf_enable'] : false;
			$bf_date_theme = ! empty( $booking['bf_date_theme'] ) ? $booking['bf_date_theme'] : '';

			ob_start();
			echo $form;
			if ( $bf_enable == true ) {
				if ( $bf_date_theme != 'default' ) {
					echo '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url( __FILE__ ) . 'assets/flatpickr/themes/' . $bf_date_theme . '.css">';
				}
			}

			$properties['form'] = ob_get_clean();

		}

		return $properties;
	}
	add_filter( 'wpcf7_contact_form_properties', 'uacf7_bf_properties', 10, 2 );
}

/**
 * Product add to cart after submiting form by ajax
 * 
 * Create custom WooCommerce product if no product is provided
 * 
 * Add booking date & time as cart item data
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_bf_ajax_add_to_cart_product' ) ) {
	add_action( 'wp_ajax_uacf7_bf_ajax_add_to_cart_product', 'uacf7_bf_ajax_add_to_cart_product' );
	add_action( 'wp_ajax_nopriv_uacf7_bf_ajax_add_to_cart_product', 'uacf7_bf_ajax_add_to_cart_product' );
	function uacf7_bf_ajax_add_to_cart_product() {

		$bf_product = sanitize_text_field( $_POST['bf_product'] );

		if ( $bf_product == 'exist' ) {
			$product_id = sanitize_text_field( $_POST['product_id'] );
		} elseif ( $bf_product == 'custom' ) {

			$product_name = sanitize_text_field( $_POST['product_name'] );
			$product_price = sanitize_text_field( $_POST['product_price'] );

			// Add Product
			$product_arr = array(
				'post_title' => $product_name,
				'post_type' => 'product',
				'post_status' => 'publish',
				'post_password' => '1111114455',
				'meta_input' => array(
					'_price' => $product_price,
					'_regular_price' => $product_price,
					'_visibility' => 'visible',
					'_virtual' => 'yes',
					'_sold_individually' => 'yes',
				)
			);

			$product_id = post_exists( $product_name, '', '', 'product' );
			if ( $product_id ) {
			} else {
				$product_id = wp_insert_post( $product_arr );
			}

		}

		// Get booking date from form
		if ( isset( $_POST['booking_date'] ) && ! empty( $_POST['booking_date'] ) ) {
			$booking_date = sanitize_text_field( $_POST['booking_date'] );
		} else {
			$booking_date = 'N/A';
		}

		// Get booking time from form
		if ( isset( $_POST['booking_time'] ) && ! empty( $_POST['booking_time'] ) ) {
			$booking_time = sanitize_text_field( $_POST['booking_time'] );
		} else {
			$booking_time = 'N/A';
		}

		/*
		 * Custom cart item data
		 */
		$cart_item_data = array();
		$cart_item_data['booking_date'] = $booking_date;
		$cart_item_data['booking_time'] = $booking_time;

		/*
		 * Add to cart
		 */
		$product_cart_id = WC()->cart->generate_cart_id( $product_id );
		if ( ! WC()->cart->find_product_in_cart( $product_cart_id ) ) {
			WC()->cart->add_to_cart( $product_id, '1', '0', array(), $cart_item_data );
		}
		die();

	}
}

/**
 * Display booking date & time in cart and checkout
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_bf_display_cart_item_custom_meta_data' ) ) {
	function uacf7_bf_display_cart_item_custom_meta_data( $item_data, $cart_item ) {
		if ( isset( $cart_item['booking_date'] ) && ! empty( $cart_item['booking_date'] ) ) {
			$item_data[] = array(
				'key' => 'Booking Date',
				'value' => $cart_item['booking_date'],
			);
		}
		if ( isset( $cart_item['booking_time'] ) && ! empty( $cart_item['booking_time'] ) ) {
			$item_data[] = array(
				'key' => 'Booking Time',
				'value' => $cart_item['booking_time'],
			);
		}
		return $item_data;
	}
	add_filter( 'woocommerce_get_item_data', 'uacf7_bf_display_cart_item_custom_meta_data', 10, 2 );
}


/**
 * Add booking date & time as order item meta
 * 
 * @since 1.0
 */
if ( ! function_exists( 'uacf7_bf_save_cart_item_custom_meta_as_order_item_meta' ) ) {
	function uacf7_bf_save_cart_item_custom_meta_as_order_item_meta( $item, $cart_item_key, $values, $order ) {

		if ( isset( $values['booking_date'] ) && ! empty( $values['booking_date'] ) ) {
			$item->update_meta_data( 'Booking Date', $values['booking_date'] );
		}
		if ( isset( $values['booking_time'] ) && ! empty( $values['booking_time'] ) ) {
			$item->update_meta_data( 'Booking Time', $values['booking_time'] );
		}
	}
	add_action( 'woocommerce_checkout_create_order_line_item', 'uacf7_bf_save_cart_item_custom_meta_as_order_item_meta', 10, 4 );
}


/**
 * Called when WooCommerce is inactive to display an inactive notice.
 *
 * @since 1.0
 */
function bfcf7_woocommerce_inactive_notice() {
	if ( current_user_can( 'activate_plugins' ) ) {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
			?>

			<div id="message" class="error">
				<p><?php printf( __( 'UACF7 Addon - Booking / Appointment Form requires %1$s WooCommerce %2$s to be activated.', 'ultimate-addons-cf7-pro' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ); ?>
				</p>
				<p><a class="install-now button tf-install"
						data-plugin-slug="woocommerce"><?php esc_attr_e( 'Install Now', 'ultimate-addons-cf7-pro' ); ?></a></p>
			</div>

			<?php
		} elseif ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
			?>

			<div id="message" class="error">
				<p><?php printf( __( 'Ultimate Booking Form requires %1$s WooCommerce %2$s to be activated.', 'ultimate-addons-cf7-pro' ), '<strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a></strong>' ); ?>
				</p>
				<p><a href="<?php echo get_admin_url(); ?>plugins.php?_wpnonce=<?php echo wp_create_nonce( 'activate-plugin_woocommerce/woocommerce.php' ); ?>&action=activate&plugin=woocommerce/woocommerce.php"
						class="button activate-now button-primary"><?php esc_attr_e( 'Activate', 'ultimate-addons-cf7-pro' ); ?></a>
				</p>
			</div>

			<?php
		} elseif ( version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
			?>

			<div id="message" class="error">
				<p><?php printf( __( '%sUltimate Booking Form is inactive.%s This plugin requires WooCommerce 2.5 or newer. Please %supdate WooCommerce to version 2.5 or newer%s', 'ultimate-addons-cf7-pro' ), '<strong>', '</strong>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?>
				</p>
			</div>

			<?php
		}
	}
}


/**
 * Form Generator AI Hooks And Callback Functions
 * @since 1.1.6
 */
if ( ! function_exists( 'uacf7_booking_ai_form_dropdown_callback' ) ) {

	add_filter( 'uacf7_booking_ai_form_dropdown', 'uacf7_booking_ai_form_dropdown_callback', 10, 2 );

	function uacf7_booking_ai_form_dropdown_callback() {
		return [ "value" => "booking", "label" => "Booking" ];
	}
}

if ( ! function_exists( 'uacf7_service_booking_form_dropdown' ) ) {

	add_filter( 'uacf7_service_booking_form_dropdown', 'uacf7_service_booking_form_dropdown', 10, 2 );

	function uacf7_service_booking_form_dropdown() {
		return [ "value" => "service-booking", "label" => "Service Booking " ];
	}
}

if ( ! function_exists( 'uacf7_appointment_form_dropdown' ) ) {

	add_filter( 'uacf7_appointment_form_dropdown', 'uacf7_appointment_form_dropdown', 10, 2 );

	function uacf7_appointment_form_dropdown() {
		return [ "value" => "appointment-form", "label" => "Appointment" ];
	}
}

// Form Generator AI Hooks And Callback Functions
if ( ! function_exists( 'uacf7_booking_form_ai_generator' ) ) {

	add_filter( 'uacf7_booking_form_ai_generator', 'uacf7_booking_form_ai_generator_callback', 10, 2 );

	function uacf7_booking_form_ai_generator_callback( $value, $uacf7_default ) {

		if ( $uacf7_default[1] == 'booking' ) {

			$value = '<label> Your name
    [text* your-name] </label> 
<label> Your email
    [email* your-email] </label>  
<label> Booking Date
    [uacf7_booking_form_date* uacf7_booking_form_date] </label> 
<label> Booking Time
    [uacf7_booking_form_time* uacf7_booking_form_time] </label> 
<label> Your message (optional)
    [textarea your-message] </label> 
[submit "Submit"]';

		} else if ( $uacf7_default[1] == 'service-booking' ) {

			$value = '<label> Your Name
[text* your-name placeholder "John Doe"] </label> 
<label> Your Email
    [email* your-email placeholder "john.doe@example.com"] </label> 
<label> Contact Number
    [tel* your-phone placeholder "+1 234 567 8910"] </label> 
<label> Preferred Date of Service
    [uacf7_booking_form_date* service_date] </label> 
<label> Type of Service Required
    [select service-type "Cleaning" "Maintenance" "Consultation" "Repair" "Other"] </label> 
<label> Preferred Time Slot
    [uacf7_booking_form_time* service_time] </label> 
<label> Address for Service
    [textarea service-address placeholder "123 Main St, City, ZIP"] </label> 
<label> Additional Instructions or Requirements
    [textarea instructions placeholder "Any additional information or specific requirements"] </label> 
[submit "Book Your Service"]';

		} else if ( $uacf7_default[1] == 'appointment-form' ) {

			$value = '<label> Full Name
    [text* full-name placeholder "Jane Smith"] </label> 
<label> Email Address
    [email* email-address placeholder "jane.smith@example.com"] </label> 
<label> Phone Number
    [tel* phone-number placeholder "+1 234 567 8910"] </label> 
<label> Preferred Date of Appointment
    [uacf7_booking_form_date* appointment-date] </label> 
<label> Preferred Time Slot
    [uacf7_booking_form_time* appointment-time]  </label> 
<label> Reason for Appointment
    [textarea reason placeholder "Describe the reason for your appointment"] </label> 
<label> Do you have any specific doctor in mind?
    [select doctor-choice "Any Available" "Dr. John Doe" "Dr. Jane Smith" "Dr. Richard Roe"] </label> 
<label> Additional Notes
    [textarea additional-notes placeholder "Any additional information or specific needs"] </label> 

[submit "Schedule Appointment"]';

		}
		return $value;

	}
}

// // /** TF Option Code */
function uacf7_post_meta_options_booking_form( $value, $post_id ) {

	$booking = apply_filters( 'uacf7_post_meta_options_booking_form_pro', $data = array(
		'title' => __( 'Booking Form', 'ultimate-addons-cf7' ),
		'icon' => 'fa-solid fa-book',
		'checked_field' => 'bf_enable',
		'fields' => array(
			'bf_hydra_notice_callback' => array(
				'id' => 'bf_hydra_notice_callback',
				'type' => 'callback',
				'function' => 'uacf7_booking_hydra_callback',
				'argument' => $post_id,
			),
			'bf_enable_heading' => array(
				'id' => 'bf_enable_heading',
				'type' => 'heading',
				'label' => __( 'Booking/Appointment Form Settings', 'ultimate-addons-cf7' ),
				'subtitle' => sprintf(
					__( 'Create a booking or appointment form using Contact Form 7, including calendar and time options, with WooCommerce payment support. See Demo %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://cf7addons.com/preview/contact-form-7-booking-form/" target="_blank" rel="noopener">Example</a>'
				)
			),
			array(
				'id' => 'booking-docs',
				'type' => 'notice',
				'style' => 'success',
				'content' => sprintf(
					__( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-booking-form/" target="_blank" rel="noopener">Booking / Appointment Form</a>'
				)
			),

			'bf_enable' => array(
				'id' => 'bf_enable',
				'type' => 'switch',
				'label' => __( ' Enable Booking Form ', 'ultimate-addons-cf7' ),
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'default' => false,
				'field_width' => 100,
			),

			'booking_form_options_heading' => array(
				'id' => 'booking_form_options_heading',
				'type' => 'heading',
				'label' => __( 'Booking Form Option ', 'ultimate-addons-cf7' ),
			),
			'bf_duplicate_status' => array(
				'id' => 'bf_duplicate_status',
				'type' => 'switch',
				'label' => __( ' Disable Duplicate Booking ', 'ultimate-addons-cf7' ),
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'default' => false,
				'field_width' => 50,
			),

			'calendar_event_enable' => array(
				'id' => 'calendar_event_enable',
				'label' => __( ' Enable Calender Event ', 'ultimate-addons-cf7' ),
				'type' => 'switch',
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'default' => false,
				'field_width' => 50,
			),
			'event_email' => array(
				'id' => 'event_email',
				'type' => 'select',
				'label' => __( 'Booking Mail', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select an email field that will be used as the "Booking Email".', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'query_args' => array(
					'post_id' => $post_id,
					'specific' => 'email',
				),
				'options' => 'uacf7',
				'field_width' => 50,
			),
			'event_summary' => array(
				'id' => 'event_summary',
				'type' => 'select',
				'label' => __( 'Booking Summary', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select a text or textarea field that will be used as the "Booking Summary".', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'query_args' => array(
					'post_id' => $post_id,
					'exclude' => [ 'email', 'submit', 'uacf7_booking_form_date', 'uacf7_booking_form_time' ],
				),
				'options' => 'uacf7',
				'field_width' => 50,
			),
			'event_date' => array(
				'id' => 'event_date',
				'type' => 'select',
				'label' => __( 'Booking Date', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select a field that will be used as the "Booking Date".', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'query_args' => array(
					'post_id' => $post_id,
					'specific' => 'uacf7_booking_form_date',
				),
				'options' => 'uacf7',
				'field_width' => 50,
			),
			'event_time' => array(
				'id' => 'event_time',
				'type' => 'select',
				'label' => __( 'Booking Time', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select a field that will be used as the "Booking Time".', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'query_args' => array(
					'post_id' => $post_id,
					'specific' => 'uacf7_booking_form_time',
				),
				'options' => 'uacf7',
				'field_width' => 50,
			),
			'uacf7_date_settings_for_frontend' => array(
				'id' => 'uacf7_date_settings_for_frontend',
				'type' => 'heading',
				'label' => __( 'Frontend Settings', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'The Settings on this section will be applied to the frontend form.', 'ultimate-addons-cf7' ),
			),
			'date_mode_front' => array(
				'id' => 'date_mode_front',
				'type' => 'radio',
				'label' => __( 'User can select:', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'options' => array(
					'single' => 'Single Date',
					'range' => 'Range of Date',
				),
				'default' => 'option-1',
				'inline' => true,
			),
			'bf_date_theme' => array(
				'id' => 'bf_date_theme',
				'type' => 'select',
				'label' => __( 'Calender Theme', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Choose the Theme or Design of the Calendar.', 'ultimate-addons-cf7' ),
				'multiple' => true,
				'inline' => true,
				'options' => array(
					'default' => 'Default',
					'dark' => 'Dark',
					'material_blue' => 'Material Blue',
					'material_green' => 'Material Green',
					'material_red' => 'Material Red',
					'material_orange' => 'Material Orange',
					'airbnb' => 'Airbnb',
					'confetti' => 'Confetti',

				),
			),

			'uacf7_date_settings_available' => array(
				'id' => 'uacf7_date_settings_available',
				'type' => 'heading',
				'label' => __( 'Booking Availability', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select when booking will be Available.', 'ultimate-addons-cf7' ),
			),

			'bf_allowed_date' => array(
				'id' => 'bf_allowed_date',
				'type' => 'radio',
				'label' => __( 'Choose your Booking Dates', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'options' => array(
					'always' => 'Always Available',
					'range' => 'Date Range',
					'specific' => 'Specific Date',
				),
				'default' => 'always',
				'inline' => true,
			),

			'allowed_min_max_date' => array(
				'id' => 'allowed_min_max_date',
				'type' => 'date',
				'class' => 'tf-field-class',
				'format' => 'Y/m/d',
				'range' => true,
				'label_from' => 'Start Date',
				'label_to' => 'End Date',
				'multiple' => true,
				'dependency' => array( 'bf_allowed_date', '==', 'range' ),
			),
			'allowed_specific_date' => array(
				'id' => 'allowed_specific_date',
				'type' => 'date',
				'label' => __( 'Choose your Date', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'format' => 'Y/m/d',
				'dependency' => array( 'bf_allowed_date', '==', 'specific' ),
			),

			'uacf7_date_settings_disable' => array(
				'id' => 'uacf7_date_settings_disable',
				'type' => 'heading',
				'label' => __( 'Booking Unavailability', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select when booking will be unavailable.', 'ultimate-addons-cf7' ),
			),

			'"disable_day' => array(
				'id' => 'disable_day',
				'type' => 'checkbox',
				'label' => __( 'Disable Day(s)', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Choose the day(s) on which booking will be unavailable.', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'options' => array(
					'1' => 'Monday',
					'2' => 'Tuesday',
					'3' => 'Wednesday',
					'4' => 'Thursday',
					'5' => 'Friday',
					'6' => 'Saturday',
					'0' => 'Sunday',
				),
				'default' => 'always',
				'multiple' => true,
				'inline' => true,
			),
			'disabled_date' => array(
				'id' => 'disabled_date',
				'type' => 'date',
				'label' => __( 'Disable Date(s)', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select the date range during which booking will be unavailable', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'format' => 'Y/m/d',
				'range' => true,
				'label_from' => 'Start Date',
				'label_to' => 'End Date',
				'multiple' => true,
			),
			'disabled_specific_date' => array(
				'id' => 'disabled_specific_date',
				'type' => 'date',
				'label' => __( 'Disable Booking on a Specific Date', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'format' => 'Y/m/d',
				'range' => false,
				'label_from' => 'Start Date',
				'label_to' => 'End Date',
				'multiple' => true,
			),
			'uacf7_time_settings' => array(
				'id' => 'uacf7_time_settings',
				'type' => 'heading',
				'label' => __( 'Time Settings', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
			),

			'time_format_front' => array(
				'id' => 'time_format_front',
				'type' => 'text',
				'label' => __( 'Time Format for Frontend', 'ultimate-addons-cf7' ),
				'placeholder' => __( 'g:ia', 'ultimate-addons-cf7' ),
				'description' => __( 'Default: g:ia . For 24 hours format use H:i . You can find more format <a href="URL_HERE">here</a>', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
			),
			'uacf7_time_limit_start_end' => array(
				'id' => 'uacf7_time_limit_start_end',
				'type' => 'heading',
				'label' => __( 'Select Start & End Time', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class heading-inner',

			),
			'min_time' => array(
				'id' => 'min_time',
				'type' => 'time',
				'label' => __( 'Start Time', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,

			),
			'max_time' => array(
				'id' => 'max_time',
				'type' => 'time',
				'label' => __( 'End Time', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,

			),
			'uacf7_disable_time_range_heading' => array(
				'id' => 'uacf7_disable_time_range',
				'type' => 'heading',
				'label' => __( 'Disable Time Range', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'The booking will be unavailable within this Time Range.', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class heading-inner',

			),
			'from_dis_time' => array(
				'id' => 'from_dis_time',
				'type' => 'time',
				'label' => __( 'From', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,

			),
			'to_dis_time' => array(
				'id' => 'to_dis_time',
				'type' => 'time',
				'label' => __( 'To', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,

			),
			'uacf7_time_interval' => array(
				'id' => 'uacf7_time_interval',
				'type' => 'heading',
				'label' => __( 'Time Interval', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class heading-inner',

			),
			'time_one_step' => array(
				'id' => 'time_one_step',
				'type' => 'number',
				'label' => __( 'Time Duration (in minutes)', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Set time duration for each booking session/slot.', 'ultimate-addons-cf7' ),
				'description' => __( 'Default: 30', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,

			),
			'time_two_step' => array(
				'id' => 'time_two_step',
				'type' => 'number',
				'label' => __( 'Time Break', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Add Intervals between each booking if needed.', 'ultimate-addons-cf7' ),
				'description' => __( 'Default: Blank', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,

			),
			'uacf7_allowed_time_heading' => array(
				'id' => 'uacf7_allowed_time_heading',
				'type' => 'heading',
				'label' => __( 'Availablity based on Time', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',

			),
			'bf_allowed_time' => array(
				'id' => 'bf_allowed_time',
				'type' => 'radio',
				'label' => __( 'Choose your Booking Time', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'inline' => true,
				'options' => array(
					'always' => 'Always Available',
					'day' => 'Day',
					'specific' => 'Specific',
				)

			),

			'allowed_time_day' => array(
				'id' => 'allowed_time_day',
				'type' => 'checkbox',
				'label' => __( 'Select Day', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'inline' => true,
				'options' => array(
					'1' => 'Monday',
					'2' => 'Tuesday',
					'3' => 'Wednesday',
					'4' => 'Thursday',
					'5' => 'Friday',
					'6' => 'Saturday',
					'0' => 'Sunday',
				),
				'multiple' => true,
				'dependency' => array( 'bf_allowed_time', '==', 'day' )

			),
			'specific_date_time' => array(
				'id' => 'specific_date_time',
				'type' => 'date',
				'label' => __( 'Specific Dates', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'format' => 'Y/m/d',
				'range' => false,
				'label_from' => 'Start Date',
				'label_to' => 'End Date',
				'multiple' => true,
				'dependency' => array( 'bf_allowed_time', '==', 'specific' ),
			),


			'uacf7_allowed_time_limit_heading' => array(
				'id' => 'uacf7_allowed_time_limit_heading',
				'type' => 'heading',
				'label' => __( 'Select Start & End Time', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class heading-inner',
				'dependency' => array( 'bf_allowed_time', '!=', 'always' )

			),
			'min_day_time' => array(
				'id' => 'min_day_time',
				'type' => 'time',
				'label' => __( 'From', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,
				'dependency' => array( 'bf_allowed_time', '!=', 'always' )

			),
			'max_day_time' => array(
				'id' => 'max_day_time',
				'type' => 'time',
				'label' => __( 'To', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'field_width' => 50,
				'dependency' => array( 'bf_allowed_time', '!=', 'always' )

			),


			'uacf7_bf_wooCommerce' => array(
				'id' => 'uacf7_bf_wooCommerce',
				'type' => 'heading',
				'label' => __( 'WooCommerce Configuration', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',

			),

			'bf_woo' => array(
				'id' => 'bf_woo',
				'type' => 'switch',
				'label' => __( ' Enable WooCommerce Integration', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Activate this option if you wish to take payments for your bookings.', 'ultimate-addons-cf7' ),
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'default' => false
			),


			'bf_product' => array(
				'id' => 'bf_product',
				'type' => 'radio',
				'label' => __( ' Select Product', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'You have the option to select an existing product or add a Custom Product to use for bookings.', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'options' => array(
					'exist' => 'Existing Product',
					'custom' => ' Custom Product',
				),
				'default' => 'exist'
			),
			'bf_product_id' => array(
				'id' => 'bf_product_id',
				'type' => 'text',
				'label' => __( ' Product ID', 'ultimate-addons-cf7' ),
				'description' => __( 'Only one product ID is allowed', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'dependency' => array( 'bf_product', '==', 'exist' )
			),
			'bf_product_name' => array(
				'id' => 'bf_product_name',
				'type' => 'text',
				'label' => __( 'Booking Name', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'dependency' => array( 'bf_product', '==', 'custom' ),
				'field_width' => 50,
			),
			'bf_product_price' => array(
				'id' => 'bf_product_price',
				'type' => 'number',
				'label' => __( 'Booking Price', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'dependency' => array( 'bf_product', '==', 'custom' ),
				'field_width' => 50,
			),



		),


	), $post_id );

	$value['booking'] = $booking;
	return $value;
}

add_filter( 'uacf7_post_meta_options', 'uacf7_post_meta_options_booking_form', 15, 2 );