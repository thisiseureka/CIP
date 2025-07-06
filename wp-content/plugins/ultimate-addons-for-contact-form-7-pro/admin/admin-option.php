<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( apply_filters( 'uacf7_checked_license_status', '' ) == false ) {
	return false;
}

/**
 * Addons - Conditional Redirect Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_redirection_pro' ) ) {
	function uacf7_settings_options_redirection_pro( $option ) {

		// if(apply_filters('uacf7_checked_license_status', '') == false){
		//     return $option;
		// }

		$option['general_addons']['fields']['uacf7_enable_redirection_pro']['is_pro'] = false;
		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_redirection_pro', 11, 2 );

}

/**
 * Addons - Conditional Field Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_conditional_field_pro' ) ) {
	function uacf7_settings_options_conditional_field_pro( $option ) {

		$option['general_addons']['fields']['uacf7_enable_conditional_field_pro']['is_pro'] = false;
		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_conditional_field_pro', 10, 1 );
}

/**
 * Addons - Column Custom Width Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_custom_column' ) ) {
	function uacf7_settings_options_custom_column( $option ) {

		$option['general_addons']['fields']['uacf7_enable_field_column_pro']['is_pro'] = false;
		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_custom_column', 10, 1 );
}


/**
 * Addons - Booking form Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_booking' ) ) {
	function uacf7_settings_options_booking( $option ) {
		$google_calendar = array(
			'title' => __( 'Google Calendar API', 'ultimate-addons-cf7' ),
			'icon' => 'fa fa-cog',
			'parent' => 'api_integration',
			'fields' => array(
				'uacf7_booking_calendar_key' => array(
					'id' => 'uacf7_booking_calendar_key',
					'type' => 'textarea',
					'label' => __( 'Google Calendar Key', 'ultimate-addons-cf7' ),
					'default' => false,
				),
				'uacf7_booking_calendar_id' => array(
					'id' => 'uacf7_booking_calendar_id',
					'type' => 'text',
					'label' => __( 'Google Calendar ID', 'ultimate-addons-cf7' ),
					'default' => false,
				),
			),
		);
		$option['google_calendar'] = $google_calendar;
		$option['general_addons']['fields']['uacf7_enable_booking_form']['is_pro'] = false;
		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_booking', 11, 2 );
}

/**
 * Addons - Column Custom Width Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_global_styler_options' ) ) {
	function uacf7_settings_options_global_styler_options( $option ) {


		$option['general_addons']['fields']['uacf7_enable_uacf7style_global']['is_pro'] = false;

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_global_styler_options', 14, 1 );
}

/**
 * Addons - Column Custom Width Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_post_submission' ) ) {
	function uacf7_settings_options_post_submission( $option ) {

		if ( apply_filters( 'uacf7_checked_license_status', '' ) != false ) {

			$option['general_addons']['fields']['uacf7_enable_post_submission']['is_pro'] = false;

		}

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_post_submission', 16, 2 );
}

/**
 * Addons - Form Submission Preview Pro
 * @author Jewel Hossain
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_form_submission_preview_pro' ) ) {
	function uacf7_settings_options_form_submission_preview_pro( $option ) {

		if ( apply_filters( 'uacf7_checked_license_status', '' ) != false ) {

			$option['general_addons']['fields']['uacf7_enable_form_submission_preview_pro']['is_pro'] = false;

		}

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_form_submission_preview_pro', 16, 2 );
}

/**
 * Addons - Form Save & Continue Later
 * @author Jewel Hossain
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_form_enable_save_and_continue' ) ) {
	function uacf7_settings_options_form_enable_save_and_continue( $option ) {
		if ( apply_filters( 'uacf7_checked_license_status', '' ) != false ) {

			$option['general_addons']['fields']['uacf7_enable_save_and_continue_pro']['is_pro'] = false;

		}

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_form_enable_save_and_continue', 16, 2 );
}

/**
 * Addons - PDf Generator Pro Form Download Option
 * @author Jewel Hossain
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_pdf_generator_field_pro' ) ) {
	function uacf7_settings_options_pdf_generator_field_pro( $option ) {

		if ( apply_filters( 'uacf7_checked_license_status', '' ) != false ) {

			$option['general_addons']['fields']['uacf7_enable_pdf_generator_field_pro']['is_pro'] = false;

		}

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_pdf_generator_field_pro', 16, 2 );
}

/**
 * Addons - Conversational Form Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_conversational_form' ) ) {
	function uacf7_settings_options_conversational_form( $option ) {

		$option['general_addons']['fields']['uacf7_enable_conversational_form']['is_pro'] = false;

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_conversational_form', 19, 2 );
}

/**
 * Addons - Star Ratting Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_star_ratting_pro' ) ) {
	function uacf7_settings_options_star_ratting_pro( $value ) {

		$value['extra_fields_addons']['fields']['uacf7_enable_star_rating_pro']['is_pro'] = false;

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_star_ratting_pro', 20, 2 );
}

/**
 * Addons - Database Pro
 * @author M Hemel Hasan
 * @param $option
 * @since 1.6.1
 */
if ( ! function_exists( 'uacf7_settings_options_database_pro' ) ) {
	function uacf7_settings_options_database_pro( $value ) {

		$value['general_addons']['fields']['uacf7_enable_database_pro']['is_pro'] = false;

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_database_pro', 20, 2 );
}

/**
 * Addons - Weekly Email Summary
 * @author M Hemel Hasan
 * @param $option
 * @since 1.7.9
 */
if ( ! function_exists( 'uacf7_settings_options_mailwkeely_pro' ) ) {
	function uacf7_settings_options_mailwkeely_pro( $value ) {

		$value['general_addons']['fields']['uacf7_enable_mailwkeely_pro']['is_pro'] = false;

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_mailwkeely_pro', 20, 2 );
}

/**
 * Addons - MailChimp Pro
 * @author M Hemel Hasan
 * @param $option
 * @since 1.6.7
 */
if ( ! function_exists( 'uacf7_settings_options_mailchimp_pro' ) ) {
	function uacf7_settings_options_mailchimp_pro( $value ) {

		$value['general_addons']['fields']['uacf7_enable_mailchimp_pro']['is_pro'] = false;

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_mailchimp_pro', 20, 2 );
}

/**
 * Addons - Range Slider Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_range_slider_pro' ) ) {
	function uacf7_settings_options_range_slider_pro( $option ) {

		$option['extra_fields_addons']['fields']['uacf7_enable_range_slider_pro']['is_pro'] = false;

		return $option;
	}

	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_range_slider_pro', 21, 2 );
}

/**
 * Addons - IP GEO Location Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_ip_geolocation' ) ) {
	function uacf7_settings_options_ip_geolocation( $value ) {

		$value['extra_fields_addons']['fields']['uacf7_enable_ip_geo_fields']['is_pro'] = false;

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_ip_geolocation', 22, 2 );
}

/**
 * Addons - IP GEO Location Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_repeater' ) ) {
	function uacf7_settings_options_repeater( $option ) {

		$option['extra_fields_addons']['fields']['uacf7_enable_repeater_field']['is_pro'] = false;

		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_repeater', 24, 2 );
}

/**
 * Addons - Product Dropdown Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_product_dropdown' ) ) {

	function uacf7_settings_options_product_dropdown( $option ) {

		$option['wooCommerce_integration']['fields']['uacf7_enable_product_dropdown_pro']['is_pro'] = false;

		return $option;
	}

	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_product_dropdown', 32, 2 );
}

/**
 * Addons - WooCommerce Checkout Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_product_dropdown' ) ) {

	function uacf7_settings_options_product_dropdown( $option ) {

		$option['wooCommerce_integration']['fields']['uacf7_enable_product_dropdown_pro']['is_pro'] = false;

		return $option;
	}

	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_product_dropdown', 32, 2 );
}

/**
 * Addons - WooCommerce Checkout Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_woo_checkout' ) ) {

	function uacf7_settings_options_woo_checkout( $option ) {


		$option['wooCommerce_integration']['fields']['uacf7_enable_product_auto_cart']['is_pro'] = false;

		return $option;
	}

	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_woo_checkout', 25, 2 );
}

/**
 * Addons - WooCommerce Checkout Pro
 * @author Sydur Rahman
 * @param $option
 * @since 1.5.4
 */
if ( ! function_exists( 'uacf7_settings_options_multistep_pro' ) ) {

	// Settings options Update 
	function uacf7_settings_options_multistep_pro( $option ) {

		$option['general_addons']['fields']['uacf7_enable_multistep_pro']['is_pro'] = false;
		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_multistep_pro', 10, 1 );

}

/**
 * Addons - Spam Protection Pro
 * @author M Hemel Hasan
 * @param $option
 * @since 1.6.2
 */
if ( ! function_exists( 'uacf7_settings_options_spam_protection_pro' ) ) {
	// Settings options Update 
	function uacf7_settings_options_spam_protection_pro( $option ) {

		$option['extra_fields_addons']['fields']['uacf7_enable_spam_protection_pro']['is_pro'] = false;
		return $option;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_spam_protection_pro', 10, 1 );

}