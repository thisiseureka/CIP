<?php

if ( ! function_exists( 'uacf7_addons_included_pro' ) ) {

	function uacf7_addons_included_pro() {
		$option = get_option( 'uacf7_settings' );
		$uacf7_existing_plugin_status = get_option( 'uacf7_existing_plugin_status' );
		

		if ( apply_filters( 'uacf7_checked_license_status', '' ) == false || $uacf7_existing_plugin_status != 'done' ) {
			
			return;
		} 
		//Addon - Ultimate redirect
		if ( isset( $option['uacf7_enable_redirection'] ) && $option['uacf7_enable_redirection'] == true ) {
			require_once( 'conditional-redirect/conditional-redirect.php' );
		}

		//Addon - Ultimate Conditional Field
		if ( isset( $option['uacf7_enable_conditional_field_pro'] ) && $option['uacf7_enable_conditional_field_pro'] == true ) {
			require_once( 'conditional-field-pro/conditional-field-pro.php' );
		}

		//Addon - Ultimate Column Custom Width
		if ( isset( $option['uacf7_enable_field_column_pro'] ) && $option['uacf7_enable_field_column_pro'] == true ) {
			require_once( 'column-custom-width/column-custom-width.php' );
		}

		// //Addon - Ultimate Global Settings
		if ( isset( $option['uacf7_enable_uacf7style_global'] ) && $option['uacf7_enable_uacf7style_global'] == true ) {
			require_once( 'global-settings/global-settings.php' );
		}

		//Addon - Ultimate Booking Form
		if ( isset( $option['uacf7_enable_booking_form'] ) && $option['uacf7_enable_booking_form'] == true ) {
			require_once( 'booking-form/booking-form.php' );
		}

		//Addon - Ultimate Post Submission
		if ( isset( $option['uacf7_enable_post_submission'] ) && $option['uacf7_enable_post_submission'] == true ) {
			require_once( 'post-submission/post-submission.php' );
		}

		//Addon - Form Submission Preview
		if ( isset( $option['uacf7_enable_form_submission_preview_pro'] ) && $option['uacf7_enable_form_submission_preview_pro'] == true ) {
			require_once( 'submission-preview/submission-preview.php' );
		}

		//Addon - Form Save & Continue
		if ( isset( $option['uacf7_enable_save_and_continue_pro'] ) && $option['uacf7_enable_save_and_continue_pro'] == true ) {
			require_once( 'save-and-continue/save-and-continue.php' );
		}

		//Addon - PDF Generator Pro Form Download Option
		if ( isset( $option['uacf7_enable_pdf_generator_field_pro'] ) && $option['uacf7_enable_pdf_generator_field_pro'] == true ) {
			require_once( 'pdf-generator-pro/pdf-generator-pro.php' );
		}

		//Addon - Conversational Forms
		if ( isset( $option['uacf7_enable_conversational_form'] ) && $option['uacf7_enable_conversational_form'] == true ) {
			require_once( 'conversational-form/conversational-form.php' );
			// under dev
			// require_once( 'uacf7-conversatinal/uacf7-conversatinal.php' );
		}

		//Addon - Ultimate Star Rating
		if ( isset( $option['uacf7_enable_star_rating_pro'] ) && $option['uacf7_enable_star_rating_pro'] == true ) {
			require_once( 'star-rating-pro/star-rating-pro.php' );
		}

		//Addon - Range Slider Pro
		if ( isset( $option['uacf7_enable_range_slider_pro'] ) && $option['uacf7_enable_range_slider_pro'] == true ) {
			require_once( 'range-slider-pro/range-slider-pro.php' );
		}

		//Addon - IP Geolocation
		if ( isset( $option['uacf7_enable_ip_geo_fields'] ) && $option['uacf7_enable_ip_geo_fields'] == true ) {
			require_once( 'ip-geolocation/ip-geolocation.php' );
		}

		//Addon - Ultimate Repeater
		if ( isset( $option['uacf7_enable_repeater_field'] ) && $option['uacf7_enable_repeater_field'] == true ) {
			require_once( 'repeater-field-pro/repeater-field-pro.php' );
		}

		//Addon - Ultimate Product Dropdown
		if ( isset( $option['uacf7_enable_product_dropdown_pro'] ) && $option['uacf7_enable_product_dropdown_pro'] == true ) {
			require_once( 'product-dropdown-pro/product-dropdown-pro.php' );
		}

		//Addon - Ultimate WooCommerce Checkout
		if ( isset( $option['uacf7_enable_product_auto_cart'] ) && $option['uacf7_enable_product_auto_cart'] == true ) {
			require_once( 'woocommerce-checkout/woocommerce-checkout.php' );
		}

		//Addon - Ultimate Multistep
		if ( isset( $option['uacf7_enable_multistep_pro'] ) && $option['uacf7_enable_multistep_pro'] == true ) {
			require_once( 'multistep-pro/multistep-pro.php' );
		}

		//Addon - Ultimate Database Pro
		if ( isset( $option['uacf7_enable_database_pro'] ) && $option['uacf7_enable_database_pro'] == true ) {
			require_once( 'database-pro/database-pro.php' );
		}

		//Addon - Ultimate Database Pro
		if ( isset( $option['uacf7_enable_mailwkeely_pro'] ) && $option['uacf7_enable_mailwkeely_pro'] == true ) {
			require_once( 'uacf7-weekly-email-summary/uacf7-weekly-email-summary.php' );
		} else {
			// Clear the scheduled event during deactivation or reset
			function uacf7_clear_scheduled_event() {
				$timestamp_weekly = wp_next_scheduled('uacf7_weekly_submission_report_event');
				$timestamp_daily = wp_next_scheduled('uacf7_daily_email_summary_event');

				if ($timestamp_weekly) {
					wp_unschedule_event($timestamp_weekly, 'uacf7_weekly_submission_report_event');
				}
				if ($timestamp_daily) {
					wp_unschedule_event($timestamp_daily, 'uacf7_daily_email_summary_event');
				}

			}
			uacf7_clear_scheduled_event();
			register_deactivation_hook(UACF7_PRO_FILE, 'uacf7_clear_scheduled_event');
		}

		//Addon - Spam Protection Pro
		if ( isset( $option['uacf7_enable_spam_protection_pro'] ) && $option['uacf7_enable_spam_protection_pro'] == true ) {
			require_once( 'spam-protection-pro/ultimate-spam-protection-pro.php' );
		}

		//Addon - Spam Protection Pro
		if ( isset( $option['uacf7_enable_mailchimp_pro'] ) && $option['uacf7_enable_mailchimp_pro'] == true ) {
			require_once( 'mailchimp-pro/mailchimp-pro.php' );
		}

	}
}

uacf7_addons_included_pro();