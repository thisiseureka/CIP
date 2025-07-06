<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_SPAM_PROTECTION_PRO {
	public function __construct() {

		if ( class_exists( 'Ultimate_Addons_CF7_PRO' ) && apply_filters( 'uacf7_checked_license_status', '' ) != false ) {
			add_filter( 'uacf7_post_meta_options_spam_protection_pro', [ $this, 'uacf7_post_meta_options_spam_protection_pro' ], 24, 2 );
		}

		add_action( 'init', array( $this, 'uacf7_spam_protection_pro_init' ) );
		// add_action('wpcf7_before_send_mail', array($this, 'uacf7_spam_protection_word_filter'), 99, 1);
		add_action( 'wp_enqueue_scripts', array( $this, 'uacf7_spam_protection_scripts_pro' ), 50, 10 );
		add_action( 'wp_ajax_uacf7_spam_action', array( $this, 'uacf7_spam_action_ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_uacf7_spam_action', array( $this, 'uacf7_spam_action_ajax_callback' ) );

		// add_filter( 'wpcf7_load_js', '__return_false' );
	}



	public function uacf7_spam_protection_scripts_pro() {
		wp_enqueue_script( 'uacf7-spam-protection', plugins_url( 'assets/js/spam-protection-script.js', __FILE__ ), [ 'jquery' ], WPCF7_VERSION, true );
		wp_localize_script( 'uacf7-spam-protection', 'uacf7_spam_pro_obj', [ 
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'nonce'                 => wp_create_nonce( 'nonce_for_spam_protection' ),
			'tooFastMessage'        => __( 'Too fast submission is not acceptable! Please wait %s seconds!', 'ultimate-addons-cf7' ),
			'fieldsRequiredMessage' => __( 'Please fill out all required fields before submitting.', 'ultimate-addons-cf7' ),
			'emailValidationMessage' => __( 'This email address is not allowed.', 'ultimate-addons-cf7' ),
		] );

	}

	public function uacf7_spam_action_ajax_callback() {

		$form_id                                = $_POST['form_id'];
		$data                                   = uacf7_get_form_option($form_id, 'spam_protection');
		$uacf7_minimum_time_limit               = $data['uacf7_minimum_time_limit'];
		$uacf7_spam_email_protection_type       = $data['uacf7_spam_email_protection_type'];
		$uacf7_spam_email_protection_allow_list = $data['uacf7_spam_email_protection_allow_list'];
		$uacf7_spam_email_protection_deny_list  = $data['uacf7_spam_email_protection_deny_list'];
	
		wp_send_json([
			'uacf7_minimum_time_limit'               => $uacf7_minimum_time_limit,
			'uacf7_spam_email_protection_type'       => $uacf7_spam_email_protection_type,
			'uacf7_spam_email_protection_allow_list' => $uacf7_spam_email_protection_allow_list,
			'uacf7_spam_email_protection_deny_list'  => $uacf7_spam_email_protection_deny_list
		]);

	}


	public function uacf7_spam_protection_pro_init() {
		if ( class_exists( 'Ultimate_Addons_CF7_PRO' ) && apply_filters( 'uacf7_checked_license_status', '' ) != false ) {

		} else {
			add_action( 'admin_notices', array( $this, 'admin_notice_error' ) );
		}
	}


	public function admin_notice_error() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php printf(
				// Translators: %1$s and %2$s are placeholders for opening and closing <strong> tags respectively. %3$s and %4$s are placeholders for opening and closing <strong> tags respectively.
				esc_html__( '%1$sUACF7 Addon - Spam Protection Pro%2$s requires %3$sUltimate Addons for Contact Form 7 Pro%4$s to be activated.', 'ultimate-addons-cf7-pro' ),
				'<strong>',
				'</strong>',
				'<strong>',
				'</strong>'
			); ?></p>
		</div>
		<?php
	}

	public function uacf7_post_meta_options_spam_protection_pro( $data, $post_id ) {

		$data['fields']['uacf7_minimum_time_limit']['is_pro'] = false;
		$data['fields']['uacf7_spam_email_protection_type']['is_pro'] = false;
		$data['fields']['uacf7_spam_email_protection_allow_list']['is_pro'] = false;
		$data['fields']['uacf7_spam_email_protection_deny_list']['is_pro'] = false;
		// $data['fields']['uacf7_word_filter']['is_pro'] = false;
		// $data['fields']['uacf7_ip_block']['is_pro'] = false;
		// $data['fields']['uacf7_blocked_countries']['is_pro'] = false;
		return $data;
	}

	public function uacf7_spam_protection_word_filter( $contact_form ) {

		$submission = WPCF7_Submission::get_instance();
		$wpcf7 = WPCF7_ContactForm::get_current();
		$form_id = $wpcf7->id();
		$uacf7_spam_protection = uacf7_get_form_option( $form_id, 'spam_protection' );

		$uacf7_word_filter = isset( $uacf7_spam_protection['uacf7_word_filter'] ) ? $uacf7_spam_protection['uacf7_word_filter'] : '';
		$uacf7_ip_filter = isset( $uacf7_spam_protection['uacf7_ip_block'] ) ? $uacf7_spam_protection['uacf7_ip_block'] : '';
		$uacf7_countries_filter = isset( $uacf7_spam_protection['uacf7_blocked_countries'] ) ? $uacf7_spam_protection['uacf7_blocked_countries'] : '';

		$trimmed_words = preg_replace( '/\s*,\s*/', ',', $uacf7_word_filter );
		$trimmed_ips = preg_replace( '/\s*,\s*/', ',', $uacf7_ip_filter );
		$trimmed_countries = preg_replace( '/\s*,\s*/', ',', $uacf7_countries_filter );

		$webmaster_given_words = preg_split( '/[, ]+/', $trimmed_words, -1, PREG_SPLIT_NO_EMPTY );
		$webmaster_given_ips = explode( ',', $trimmed_ips );
		$webmaster_given_countries_raw = explode( ',', $trimmed_countries );
		$webmaster_given_countries = array_map( 'strtolower', $webmaster_given_countries_raw );

		$user_current_ip = ( isset( $_SERVER['X_FORWARDED_FOR'] ) ) ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		$addr = wp_remote_get( 'http://ip-api.com/php/' . $user_current_ip );
		$addr_body = wp_remote_retrieve_body( $addr );
		$addr = unserialize( $addr_body );
		$user_country = isset( $addr['countryCode'] ) ? $addr['countryCode'] : '';
		$user_current_country = strtolower( $user_country );

		if ( $submission ) {
			$uacf7_data = $submission->get_posted_data();
			$usergiven_all_data = array_values( $uacf7_data );

			if ( isset( $uacf7_spam_protection['uacf7_spam_protection_enable'] ) && $uacf7_spam_protection['uacf7_spam_protection_enable'] == '1' ) {
				$posted_data = array();

				foreach ( $usergiven_all_data as $value ) {
					// Ensure $value is a string
					if ( is_array( $value ) ) {
						// If $value is an array, handle it appropriately
						// For example, you might want to implode it into a string
						$value = implode( ' ', $value );
					}

					// If $value is not a string and not an array, skip it
					if ( ! is_string( $value ) ) {
						continue;
					}

					// Now $value is guaranteed to be a string
					$words = explode( ' ', $value );
					$posted_data = array_merge( $posted_data, $words );
				}

				$uacf7_word_match = array_intersect( $webmaster_given_words, $posted_data );
				$is_word = count( $uacf7_word_match );


				add_filter( 'wpcf7_skip_mail', '__return_false' );

				// By Word
				if ( $is_word > 0 ) {
					add_filter( 'wpcf7_skip_mail', '__return_true' );
				}

				// By Country
				if ( ! empty( $user_current_country ) && in_array( $user_current_country, $webmaster_given_countries ) ) {
					add_filter( 'wpcf7_skip_mail', '__return_true' );
				}

				//By IP
				if ( ! empty( $user_current_ip ) && in_array( $user_current_ip, $webmaster_given_ips ) ) {
					add_filter( 'wpcf7_skip_mail', '__return_true' );
				}

			}

		}

	}
}

new UACF7_SPAM_PROTECTION_PRO();