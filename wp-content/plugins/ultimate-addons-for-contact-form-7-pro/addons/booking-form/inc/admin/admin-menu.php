<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


//Booking settings section

add_action( 'admin_init', 'uacf7_booking_page_init' );
function uacf7_booking_page_init() {
	add_settings_section(
		'uacf7_booking_setting_section', // id
		__( 'Booking settings:', 'ultimate-addons-cf7' ), // title
		'uacf7_booking_form_section_info', // callback
		'ultimate-booking-form-admin' // page
	);

	register_setting(
		'uacf7_booking_form_option', // option_group
		'uacf7_booking_form_option_name', // option_name
		'uacf7_booking_sanitize' // sanitize_callback
	);

	add_settings_field(
		'uacf7_booking_calendar_key', //id
		__( 'Google Calendar Key', 'ultimate-addons-cf7' ), //title 
		'uacf7_booking_calendar_key_callback',
		'ultimate-booking-form-admin', // page
		'uacf7_booking_setting_section'
	);
	add_settings_field(
		'uacf7_booking_calendar_id', //id
		__( 'Google Calendar ID', 'ultimate-addons-cf7' ), //title 
		'uacf7_booking_calendar_id_callback',
		'ultimate-booking-form-admin', // page
		'uacf7_booking_setting_section'
	);

	do_action( 'uacf7_settings_field' );
}


/* Booking / Appointment tab */

add_action( 'uacf7_admin_tab_button', 'add_booking_form_tab', 10 );
function add_booking_form_tab() {
	?>
	<a class="tablinks" onclick="uacf7_settings_tab(event, 'uacf7_booking_form')">Booking / Appointment</a>
<?php
}


/* Booking tab content */

add_action( 'uacf7_admin_tab_content', 'add_booking_form_tab_content' );
function add_booking_form_tab_content() {
	?>
	<div id="uacf7_booking_form" class="uacf7-tabcontent uacf7-booking_form">

		<form method="post" action="options.php">
			<?php
			settings_fields( 'uacf7_booking_form_option' );
			do_settings_sections( 'ultimate-booking-form-admin' );
			submit_button();
			?>
		</form>

	</div>
	<?php
}

// Booking option Sanitize
function uacf7_booking_sanitize( $input ) {
	$sanitary_values = array();
	if ( isset( $input['uacf7_booking_calendar_key'] ) ) {

		file_put_contents( BFCF7_PATH . "/third-party/credentials.json", $input['uacf7_booking_calendar_key'] );
		$sanitary_values['uacf7_booking_calendar_key'] = $input['uacf7_booking_calendar_key'];
	}
	if ( isset( $input['uacf7_booking_calendar_id'] ) ) {
		$sanitary_values['uacf7_booking_calendar_id'] = $input['uacf7_booking_calendar_id'];
	}
	return apply_filters( 'uacf7_save_booking_form_menu', $sanitary_values, $input );
}

// Booking settings Callback

function uacf7_booking_form_section_info() {
	// noting
}

// Google Calender Key Callback

function uacf7_booking_calendar_key_callback() {
	$val = get_option( 'uacf7_booking_form_option_name' );
	if ( is_array( $val ) && isset( $val['uacf7_booking_calendar_key'] ) ) {
		$val = $val['uacf7_booking_calendar_key'];
	} else {
		$val = '';
	}
	echo '<label class="uacf7_booking_calendar_key" for="uacf7_booking_calendar_key">
				<textarea id="w3review" name="uacf7_booking_form_option_name[uacf7_booking_calendar_key]" rows="4" cols="50">' . $val . '</textarea> 
		</label>';
}

// Google Calander ID Callback

function uacf7_booking_calendar_id_callback() {
	$val = get_option( 'uacf7_booking_form_option_name' );
	if ( is_array( $val ) && isset( $val['uacf7_booking_calendar_id'] ) ) {
		$val = $val['uacf7_booking_calendar_id'];
	} else {
		$val = '';
	}
	echo '<label class="" for="uacf7_booking_calendar_id">
				<input type="text" class="regular-text code" name="uacf7_booking_form_option_name[uacf7_booking_calendar_id]" id="uacf7_booking_calendar_id" value="' . $val . '"> 
			</label>';
}

?>