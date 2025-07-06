<?php

require_once 'helperfunction.php';

/**
 * The Gmail Connection class.
 */
class GmailConnection {

	static $access_token;


	public function __construct() {
		add_action( "wp_ajax_uacf7dp_test_gmail_connection", array( $this, "uacf7dp_test_gmail_connection_callback" ) );
		add_action( "wp_ajax_uacf7dp_single_gmail_sync", array( $this, "uacf7dp_single_gmail_sync_callback" ) );
		add_action( "admin_init", array( $this, "uacf7dp_process_gmail_oauth_url" ) );
		add_action( 'init', array( $this, 'init_gmail_connection' ) );
	}

	public function uacf7dp_test_gmail_connection_callback() {
		$client_id = ! empty( $_POST['client_id'] ) ? sanitize_text_field( $_POST["client_id"] ) : '';
		$client_secret = ! empty( $_POST['client_secret'] ) ? sanitize_text_field( $_POST["client_secret"] ) : '';
		$gmail = ! empty( $_POST['email'] ) ? sanitize_text_field( $_POST["email"] ) : '';

		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'uacf7dpe-nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce.' ] );
			wp_die();
		}

		// initialize the connection.
		set_transient( 'uacf7dp_tra_gmail_connection_init', 'yes', MINUTE_IN_SECONDS * 10 );

		$response = array();

		if ( empty( $client_id ) || empty( $client_secret ) || empty( $gmail ) ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'All fields are required.', 'ultimate-addons-cf7' );
			wp_send_json( $response );
			wp_reset_postdata();
			wp_die();
		}

		$gmail_pattern = '/^[a-zA-Z0-9._%+-]+@gmail\.com$/s';
		if ( ! preg_match( $gmail_pattern, $gmail ) ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'Please enter a valid Gmail address.', 'ultimate-addons-cf7' );
			wp_send_json( $response );
			wp_reset_postdata();
			wp_die();
		}

		// Google oAuth URL.
		$url = 'https://accounts.google.com/o/oauth2/v2/auth';
		$url .= '?scope=' . esc_url_raw( 'https://www.googleapis.com/auth/gmail.readonly' );
		$url .= '&access_type=offline';
		$url .= '&redirect_uri=' . esc_url_raw( admin_url( 'admin.php' ) );
		$url .= '&response_type=code';
		$url .= '&state=uacf7dp_gmail_auth,' . wp_create_nonce( 'uacf7dpe-nonce' );
		$url .= '&client_id=' . $client_id;

		$response['status'] = 'success';
		$response['message'] = esc_html__( 'Connection Successful', 'ultimate-addons-cf7' );
		$response['url'] = $url;

		wp_send_json( $response );
		wp_reset_postdata();
		wp_die();
	}

	public function uacf7dp_process_gmail_oauth_url() {
		$state = isset( $_REQUEST['state'] ) ? array_filter( array_map( 'sanitize_text_field', explode( ',', sanitize_text_field( wp_unslash( $_REQUEST['state'] ) ) ) ) ) : array();
		if ( ! ( isset( $state[0] ) && $state[0] == 'uacf7dp_gmail_auth' ) ) {
			return;
		}

		// verify nonce.
		$nonce = isset( $state[1] ) ? $state[1] : '';
		if ( wp_verify_nonce( $nonce, 'uacf7dpe-nonce' ) != 1 ) {
			wp_die( 'Unauthorized request!' );
		}

		$code = isset( $_REQUEST['code'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['code'] ) ) : '';
		if ( empty( $code ) ) {
			wp_die( 'Bad request!' );
		}

		$client_id = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client"] : '';

		$client_secret = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client_secret"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client_secret"] : '';

		$gmail_address = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_address"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_address"] : '';

		$is_valid = true;
		$gmail = array(
			'connection' => 'Connected',
		);

		// Get Refresh and Access Tokens.
		$url = 'https://www.googleapis.com/oauth2/v4/token';
		$response = wp_remote_post(
			$url,
			array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array(
						'client_id' => $client_id,
						'client_secret' => $client_secret,
						'redirect_uri' => admin_url( 'admin.php' ),
						'code' => $code,
						'grant_type' => 'authorization_code',
					),
				'cookies' => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			$is_valid = false;
			$gmail["connection"] = $response->get_error_message();
		}

		if ( $is_valid ) {

			$access = json_decode( $response['body'], true );
			if ( isset( $access['refresh_token'] ) ) {

				$gmail['refresh-token'] = $access['refresh_token'];

			} else {

				$is_valid = false;
				$gmail['connection'] = 'Refresh token not found!';
			}
		}

		$response = wp_remote_post(
			'https://www.googleapis.com/gmail/v1/users/' . $gmail_address . '/profile',
			array(
				'method' => 'GET',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array(
						'access_token' => $access['access_token'],
					),
				'cookies' => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			$is_valid = false;
			$gmail['connection'] = $response->get_error_message();
		}

		if ( $is_valid ) {
			$profile = json_decode( $response['body'], true );
			if ( isset( $profile['historyId'] ) ) {
				$gmail['history-id'] = $profile['historyId'];
			} else {
				$is_valid = false;
				$gmail['connection'] = 'History ID not found!';
			}
		}

		if ( $is_valid ) {
			$gmail['is-active'] = 1;
		}
		?>
		<script>
			window.location.href = "<?php echo esc_url( admin_url( 'admin.php' ) ); ?>?page=uacf7_settings#tab=uacf7dp_email_piping_setting";
		</script>
		<?php

		update_option( 'uacf7dp_mp_gmail_connection_data', $gmail );
		delete_transient( 'uacf7dp_tra_gmail_connection_init' );

	}

	public function uacf7dp_single_gmail_sync_callback() {
		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'uacf7dpe-nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce.' ] );
			wp_die();
		}
		
		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if ( $connection_type !== 'gmail' ) {
			return;
		}

		$gmail_data = get_option( 'uacf7dp_mp_gmail_connection_data' );
		$response = array();

		if ( empty( $gmail_data ) && $gmail_data['connection'] !== 'connected' ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'Gmail Connection is not active.', 'ultimate-addons-cf7' );
			wp_send_json( $response );
			wp_reset_postdata();
			wp_die();
		}

		$client_id = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client"] : '';

		$client_secret = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client_secret"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client_secret"] : '';

		$gmail_address = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_address"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_address"] : '';

		if ( empty( $client_id ) || empty( $client_secret ) || empty( $gmail_address ) ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'Gmail Connection is not active.', 'ultimate-addons-cf7' );
			wp_send_json( $response );
			wp_reset_postdata();
			wp_die();
		}

		self::init();

		$response['status'] = 'success';
		$response['message'] = esc_html__( 'Sync Completed', 'ultimate-addons-cf7' );
		wp_send_json( $response );
		wp_reset_postdata();
		wp_die();

	}

	function init_gmail_connection() {
		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if ( $connection_type !== 'gmail' ) {
			return;
		}

		$page = ! empty( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		if($page == 'ultimate-addons-db' ) {
			self::init();
		}
	}

	public static function init() {
		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if ( $connection_type !== 'gmail' ) {
			return;
		}

		$gmail_data = get_option( 'uacf7dp_mp_gmail_connection_data' );

		if(!$gmail_data) {
			return;
		}

		$client_id = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client"] : '';

		$client_secret = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client_secret"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_client_secret"] : '';

		$gmail_address = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_address"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_gmail_address"] : '';

		if ( empty( $gmail_data ) && $gmail_data['connection'] !== 'connected' ) {
			return;
		}

		// get access token using refresh token.
		$response = wp_remote_post(
			'https://www.googleapis.com/oauth2/v4/token',
			array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array(
						'client_id' => $client_id,
						'client_secret' => $client_secret,
						'refresh_token' => ! empty( $gmail_data['refresh-token'] ) ? $gmail_data['refresh-token'] : '',
						'grant_type' => 'refresh_token',
					),
				'cookies' => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			return;
		}

		$access = json_decode( $response['body'], true );
		self::$access_token = ! empty( $access['access_token'] ) ? $access['access_token'] : '';

		// get new messeges.
		$response = wp_remote_post(
			'https://www.googleapis.com/gmail/v1/users/' . $gmail_address . '/history',
			array(
				'method' => 'GET',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array(
						'access_token' => self::$access_token,
						'startHistoryId' => ! empty( $gmail_data['history-id'] ) ? $gmail_data['history-id'] : 0,
						'historyTypes' => 'messageAdded',
						'labelId' => 'INBOX',
					),
				'cookies' => array(),
			)
		);

		if ( is_wp_error( $response ) ) {
			return;
		}

		$history = json_decode( $response['body'], true );

		if ( ! isset( $history['history'] ) ) {
			return;
		}

		$counter = 0;
		$message_data = array();
		if ( is_array( $history['history'] ) ) {
			foreach ( $history['history'] as $history_item ) {
				$gmail_data['history-id'] = $history_item['id'];
				update_option( 'uacf7dp_mp_gmail_connection_data', $gmail_data );

				$update_data = array();

				foreach ( $history_item["messagesAdded"] as $message ) {

					if ( ! isset( $message['message'] ) ) {
						return;
					}

					$message_id = $message['message']['id'];

					$response = wp_remote_post(
						'https://www.googleapis.com/gmail/v1/users/' . $gmail_address . '/messages/' . $message_id,
						array(
							'method' => 'GET',
							'timeout' => 45,
							'redirection' => 5,
							'httpversion' => '1.0',
							'blocking' => true,
							'headers' => array(),
							'body' => array(
									'access_token' => self::$access_token,
								),
							'cookies' => array(),
						)
					);

					if ( is_wp_error( $response ) ) {
						return;
					}

					$message = json_decode( $response['body'], true );
					$update_data[] = $message['payload'];

					$message_data[] = self::parse_email_data( $message['payload'], $message_id );

					++$counter;
					if ( $counter > 10 ) {
						break;
					}
				}
			}
		}
		set_mail_data_from_db( $message_data );
	}

	static function parse_email_data( $payload, $message_id ) {
		$headers = $payload['headers'];
		$mail_data = array();

		if ( ! empty( self::get_to_address( $headers ) ) ) {
			$mail_data["to_address"] = self::get_to_address( $headers );
		}

		if ( ! empty( self::get_cc_addresses( $headers ) ) ) {
			$mail_data["cc_address"] = self::get_cc_addresses( $headers );
		}

		if ( ! empty( self::get_cc_addresses( $headers ) ) ) {
			$mail_data["cc_address"] = self::get_cc_addresses( $headers );
		}

		if ( ! empty( self::set_from_user( $headers )["from_name"] ) ) {
			$mail_data["reply_user"] = self::set_from_user( $headers )["from_name"];
		}

		if ( ! empty( self::set_from_user( $headers )["reply_to"] ) ) {
			$mail_data["reply_to"] = self::set_from_user( $headers )["reply_to"];
		}

		if ( ! empty( self::set_from_user( $headers )["from_email"] ) ) {
			$mail_data["reply_mail"] = self::set_from_user( $headers )["from_email"];
		}

		if ( ! empty( self::get_header_value( $headers, 'Subject' ) ) ) {
			$mail_data["reply_subject"] = self::get_header_value( $headers, 'Subject' );
		}

		if ( ! empty( self::set_body( $payload ) ) ) {
			$mail_body = self::set_body( $payload );

			$mail_data["reply_message_html"] = $mail_body["html"];
			$mail_data["reply_message_text"] = esc_html( $mail_body["text"] );

		}

		if ( ! empty( self::get_header_value( $headers, 'Message-ID' ) ) ) {
			$msg_id = self::get_header_value( $headers, 'Message-ID' );
			$msg_id = preg_replace( '/<([^>]+)>/', '$1', $msg_id );

			$mail_data["message_id"] = $msg_id;
		}

		if ( ! empty( self::get_header_value( $headers, 'References' ) ) ) {
			$msg_reference = self::get_header_value( $headers, 'References' );

			$mail_data["references"] = esc_html( $msg_reference );
		}

		if ( self::get_header_value( $headers, 'Date' ) ) {
			$originalDate = self::get_header_value( $headers, 'Date' );
			$date = \DateTime::createFromFormat( 'D, d M Y H:i:s O', $originalDate );

			// Check if the date was parsed correctly
			if ( $date ) {
				$formattedDate = $date->format( 'Y-m-d H:i:s' );
				$mail_data["submit_time"] = $formattedDate;
			}
		}

		$mail_data["source"] = 'gmail';
		$mail_data["type"] = "response";

		return $mail_data;
	}

	static function get_header_value( $headers, $name ) {
		foreach ( $headers as $header ) {
			if ( $header['name'] == $name ) {
				return $header['value'];
			}
		}
	}

	private static function get_to_address( $headers ) {
		$to_emails = array();
		$to = self::get_header_value( $headers, 'To' );

		preg_match_all( '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $to, $matches );
		foreach ( $matches[0] as $email_address ) {
			$to_addresses[] = $email_address;
		}
		return $to_emails;
	}

	private static function get_cc_addresses( $headers ) {

		$cc_addresses = array();
		$text = self::get_header_value( $headers, 'Cc' );
		! empty( $text ) ? preg_match_all( '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $text, $matches ) : array();
		if ( ! empty( $matches ) && is_array( $matches ) ) {
			foreach ( $matches[0] as $email_address ) {
				$cc_addresses[] = $email_address;
			}
		}

		$text = self::get_header_value( $headers, 'CC' );
		! empty( $text ) ? preg_match_all( '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $text, $matches ) : array();
		if ( ! empty( $matches ) && is_array( $matches ) ) {
			foreach ( $matches[0] as $email_address ) {
				$cc_addresses[] = $email_address;
			}
		}

		return $cc_addresses;
	}

	private static function set_from_user( $headers ) {

		$text = self::get_header_value( $headers, 'From' );
		$email = array();
		$email["reply_to"] = self::get_header_value( $headers, 'Reply-To' );
		if (
			preg_match( '/^"([\s\S]+)"\s?<(\S+)>$/i', $text, $matches ) ||
			preg_match( '/^([\s\S]+)\s?<(\S+)>$/i', $text, $matches )
		) {

			$email["from_name"] = trim( $matches[1] );
			$email["from_email"] = $email["reply_to"] ? $email["reply_to"] : trim( $matches[2] );

		} else {

			preg_match( '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $text, $matches );
			$email["from_name"] = $matches[0];
			$email["from_email"] = $email["reply_to"] ? $email["reply_to"] : $matches[0];
		}

		return $email;
	}

	private static function set_body( $payload ) {

		$found_body = array(
			'text' => '',
			'html' => '',
		);

		$parts = isset( $payload['parts'] ) ? $payload['parts'] : array();

		// parse html.
		foreach ( $parts as $part ) {

			if ( $part['mimeType'] === 'text/html' && $part['body'] ) {
				$description = preg_replace( '/(<(script|style)\b[^>]*>).*?(<\/\2>)/s', '', self::decode_body( $part['body']['data'] ) );
				$description = preg_replace(
					array( '/<div(.*?)>/s', '/<\/div>/s', '/<table(.*?)>/s', '/<tbody(.*?)>/s', '/<tr(.*?)>/s', '/<th(.*?)>/s', '/<td(.*?)>/s', '/<pre(.*?)>/s', '/<\/pre>/s' ),
					array( '<p>', '</p>', '<table>', '<tbody>', '<tr>', '<th>', '<td>', '<p>', '</p>' ),
					$description
				);
				$description = preg_replace( '/<blockquote\b[^>]*>(.*?)<\/blockquote>/is', '', $description );
				$description = preg_replace( '/<p>On\s+\w{3},\s+\w{3}\s+\d{1,2},\s+\d{4}.*?<\/p>/is', '', $description );
				$found_body['html'] = wp_kses_post( $description );
				break;
			}

			if ( isset( $part['parts'] ) ) {

				foreach ( $part['parts'] as $p ) {

					if ( $p['mimeType'] === 'text/html' && $p['body'] ) {
						$description = preg_replace( '/(<(script|style)\b[^>]*>).*?(<\/\2>)/s', '', self::decode_body( $p['body']['data'] ) );
						$description = preg_replace(
							array( '/<div(.*?)>/s', '/<\/div>/s', '/<table(.*?)>/s', '/<tbody(.*?)>/s', '/<tr(.*?)>/s', '/<th(.*?)>/s', '/<td(.*?)>/s', '/<pre(.*?)>/s', '/<\/pre>/s' ),
							array( '<p>', '</p>', '<table>', '<tbody>', '<tr>', '<th>', '<td>', '<p>', '</p>' ),
							$description
						);
						$description = preg_replace( '/<blockquote\b[^>]*>(.*?)<\/blockquote>/is', '', $description );
						$description = preg_replace( '/<p>On\s+\w{3},\s+\w{3}\s+\d{1,2},\s+\d{4}.*?<\/p>/is', '', $description );
						$found_body['html'] = wp_kses_post( $description );
						break;
					}

					if ( $found_body['html'] ) {
						break;
					}
					if ( isset( $p['parts'] ) ) {
						foreach ( $p['parts'] as $pt ) {

							if ( $pt['mimeType'] === 'text/html' && $pt['body'] ) {
								$description = preg_replace( '/(<(script|style)\b[^>]*>).*?(<\/\2>)/s', '', self::decode_body( $pt['body']['data'] ) );
								$description = preg_replace(
									array( '/<div(.*?)>/s', '/<\/div>/s', '/<table(.*?)>/s', '/<tbody(.*?)>/s', '/<tr(.*?)>/s', '/<th(.*?)>/s', '/<td(.*?)>/s', '/<pre(.*?)>/s', '/<\/pre>/s' ),
									array( '<p>', '</p>', '<table>', '<tbody>', '<tr>', '<th>', '<td>', '<p>', '</p>' ),
									$description
								);
								$description = preg_replace( '/<blockquote\b[^>]*>(.*?)<\/blockquote>/is', '', $description );
								$description = preg_replace( '/<p>On\s+\w{3},\s+\w{3}\s+\d{1,2},\s+\d{4}.*?<\/p>/is', '', $description );
								$found_body['html'] = wp_kses_post( $description );
								break;
							}

							if ( $found_body['html'] ) {
								break;
							}
						}
					}
				}
			}

			if ( $found_body['html'] ) {
				break;
			}
		}

		// parse plain text.
		foreach ( $parts as $part ) {

			if ( $part['mimeType'] === 'text/plain' && $part['body'] ) {
				$text_message = preg_replace( '#(^\w.+:\n)?(^>.*(\n|$))#mis', "", self::decode_body( $part['body']['data'] ) );
				$text_message = preg_replace( '/\nOn(.*?)wrote:(.*?)$.+/sim', '', $text_message );
				$found_body['text'] = $text_message;
				break;
			}

			if ( isset( $part['parts'] ) ) {

				foreach ( $part['parts'] as $p ) {

					if ( $p['mimeType'] === 'text/plain' && $p['body'] ) {
						$found_body['text'] = self::decode_body( $p['body']['data'] );
						break;
					}

					if ( $found_body['text'] ) {
						break;
					}

					if ( isset( $p['parts'] ) ) {
						foreach ( $p['parts'] as $pt ) {
							if ( $pt['mimeType'] === 'text/plain' && $pt['body'] ) {
								$text_message = preg_replace( '#(^\w.+:\n)?(^>.*(\n|$))#mis', "", self::decode_body( $pt['body']['data'] ) );
								$text_message = preg_replace( '/\nOn(.*?)wrote:(.*?)$.+/sim', '', $text_message );
								$found_body['text'] = $text_message;
								break;
							}

							if ( $found_body['text'] ) {
								break;
							}
						}
					}
				}
			}
		}

		if ( ! $found_body['text'] && ! $found_body['html'] ) {

			$body = $payload['body'];
			$body_content = isset( $body['data'] ) ? self::decode_body( $body['data'] ) : '';

			if ( ! ( strpos( $body_content, '<html' ) > -1 || strpos( $body_content, '<body' ) > -1 ) ) {
				$found_body['text'] = $body_content;
			} else {
				$found_body['html'] = $body_content;
			}
		}
		return $found_body;
	}

	private static function decode_body( $body ) {

		$raw_data = $body;
		$sanitized_data = strtr( $raw_data, '-_', '+/' );
		$decoded_message = base64_decode( $sanitized_data );
		if ( ! $decoded_message ) {
			$decoded_message = false;
		}
		return $decoded_message;
	}
}

new GmailConnection();