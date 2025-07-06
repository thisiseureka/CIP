<?php

/* Add evend on google calendar after sent mail */
if ( ! function_exists( 'uacf7_booking_calendar_key_save_callback' ) ) {
	function uacf7_booking_calendar_key_save_callback( $data ) {
		// echo BFCF7_PATH."/third-party/credentials.json";
		file_put_contents( BFCF7_PATH . "/third-party/credentials.json", $data );

	}
	add_action( 'uacf7_booking_calendar_key_save', 'uacf7_booking_calendar_key_save_callback' );
}

add_action( 'wpcf7_mail_sent', 'uacf7_after_sent_mail' );
function uacf7_after_sent_mail( $cf7 ) {
	$booking = uacf7_get_form_option( $cf7->id(), 'booking' );

	$calendar_event_enable = ! empty( $booking['calendar_event_enable'] ) ? $booking['calendar_event_enable'] : '';
	$event_email = ! empty( $booking['event_email'] ) ? $booking['event_email'] : '';
	$event_date = ! empty( $booking['event_date'] ) ? $booking['event_date'] : '';
	$event_time = ! empty( $booking['event_time'] ) ? $booking['event_time'] : '';
	$event_summary = ! empty( $booking['event_summary'] ) ? $booking['event_summary'] : '';
	$time_format_front = ! empty( $booking['time_format_front'] ) ? $booking['time_format_front'] : '';
	$date_mode_front = ! empty( $booking['date_mode_front'] ) ? $booking['date_mode_front'] : '';
	$time_one_step = ! empty( $booking['time_one_step'] ) ? $booking['time_one_step'] : '30';


	$calendar_api_option = get_option( 'uacf7_settings' );

	if ( isset( $calendar_api_option['uacf7_booking_calendar_key'] ) && isset( $calendar_api_option['uacf7_booking_calendar_id'] ) ) {
		$calendar_key = $calendar_api_option['uacf7_booking_calendar_key'];
		$calendar_id = $calendar_api_option['uacf7_booking_calendar_id'];

	} else {
		$calendar_key = '';
		$calendar_id = '';
	}

	// google calendar required info
	if ( $calendar_event_enable == true && ! empty( $calendar_key ) && ! empty( $calendar_id ) ) {

		$submission = WPCF7_Submission::get_instance();

		if ( $submission ) {

			// form submission data 
			$form_data = $submission->get_posted_data();

			// Check if event date and time are empty or the is currant date
			if ( ! empty( $form_data[ $event_date ] ) && ! empty( $form_data[ $event_time ] ) ) {

				$event_email = $form_data[ $event_email ];
				$event_time = $form_data[ $event_time ];
				$event_date = explode( " to ", $form_data[ $event_date ] );

				// $event_time = '3:30am'; 
				// $event_date =  explode(" to ", '2022-01-07');  
				$event_summary = $form_data[ $event_summary ];

				// event date
				if ( count( $event_date ) == 2 ) {
					$start_date = $event_date[0];
					$end_date = $event_date[1];
				} else {
					$start_date = $event_date[0];
					$end_date = $event_date[0];
				} // event date


				// event time
				if ( ! empty( $time_format_front ) ) {
					$start_time = date( $time_format_front, strtotime( $event_time ) );
					$end_time = date( $time_format_front, strtotime( $event_time . ' +' . $time_one_step . ' minutes' ) );
				} else {
					$start_time = date( "G:i", strtotime( $event_time ) );
					$end_time = date( "G:i", strtotime( $event_time . ' +' . $time_one_step . ' minutes' ) );
				}   // event time


				// time zone
				$timezone_string = get_option( 'timezone_string' );
				$offset = (float) get_option( 'gmt_offset' );
				$hours = (int) $offset;
				$minutes = ( $offset - $hours );
				$sign = ( $offset < 0 ) ? '-' : '+';
				$abs_hour = abs( $hours );
				$abs_mins = abs( $minutes * 60 );
				$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );


				require_once( BFCF7_PATH . 'third-party/vendor/autoload.php' );

				$client = new Google_Client();
				putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . BFCF7_PATH . 'third-party/credentials.json' );
				$client->useApplicationDefaultCredentials();
				$client->setApplicationName( "test_calendar" );
				$client->setScopes( Google_Service_Calendar::CALENDAR );
				$client->setAccessType( 'online' );

				$service = new Google_Service_Calendar( $client );

				$event = new Google_Service_Calendar_Event( array( // event array 
					'summary' => get_the_title( $cf7->id() ),
					'description' => 'Email: ' . $event_email . ' <br> Description :' . $event_summary,
					'start' => array(
						'dateTime' => '' . $start_date . 'T' . $start_time . ':00' . $tz_offset . '',
						'timeZone' => $timezone_string,
					),
					'end' => array(
						'dateTime' => '' . $end_date . 'T' . $end_time . ':00' . $tz_offset . '',
						'timeZone' => $timezone_string,
					),
				) );
				$event = $service->events->insert( $calendar_id, $event ); // event sent into google calendar

				return;
			} else {
				return;
			} // google calendar requried info 
		}

	} // google calendar requried info 
	// echo $dafds;
	// exit;
}

if(!function_exists('uacf7_booking_hydra_callback')){
	function uacf7_booking_hydra_callback() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'hydra-booking/hydra-booking.php' ) ) {
			return;
		}
		?>

		<div class="hydra-notice uacf7-booking-pro-notice" style="width: auto;">
			<div class="notice-text" style="width: 70%;">
				<p>We're introducing <b>Hydra Booking Pro</b> for a more powerful booking experience! As we shift our focus to this enhanced solution, updates for this Booking/Appointment addon will be discontinued soon.</p>
				<p style="margin-top: 5px;"><b>As a valued Pro user, you can get Hydra Booking Pro at no extra cost!</b></p>
			</div>
			<div class="notice-button" style="width: 30%;">
				<p>
					<span>
					<img src="<?php echo UACF7_URL; ?>assets/img/person-1.png" alt="user">
					<img src="<?php echo UACF7_URL; ?>assets/img/person-2.png" alt="person">
					<img src="<?php echo UACF7_URL; ?>assets/img/person-3.png" alt="person">
					</span>	
					Loved by many
				</p>
				<a href="<?php echo esc_url( 'https://portal.themefic.com/my-account/downloads/' ); ?>" class="hydra-button" target="_blank">Get Hydra Booking Now</a>
			</div>
		</div>

		<?php

	}
}

?>