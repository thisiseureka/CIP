<?php

//Imap Connection
require_once UACF7_PRO_PATH_ADDONS . '/database-pro/imap/vendor/autoload.php';
use PhpImap\Mailbox;

class ImapConnection {

	private $connection;

	function __construct() {
		add_action( "wp_ajax_uacf7dp_test_imap_connection", array( $this, "uacf7dp_test_imap_connection_callback" ) );
		add_action( "wp_ajax_uacf7dp_single_imap_sync", array( $this, "uacf7dp_single_imap_sync_callback" ) );
		add_action( 'init', array( $this, 'uacf7dp_init_imap_connection' ) );
	}

	public function uacf7dp_test_imap_connection_callback() {
		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'uacf7dpe-nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce.' ] );
			wp_die();
		}

		$connection_data = array();
		$imap_email = ! empty( $_POST['email'] ) ? sanitize_text_field( $_POST["email"] ) : '';
		$imap_password = ! empty( $_POST['password'] ) ? sanitize_text_field( $_POST["password"] ) : '';
		$imap_server = ! empty( $_POST['server'] ) ? sanitize_text_field( $_POST["server"] ) : '';
		$imap_connection_port = ! empty( $_POST['connection_port'] ) ? sanitize_text_field( $_POST["connection_port"] ) : '';
		$imap_connection_type = ! empty( $_POST['connection_type'] ) ? sanitize_text_field( $_POST["connection_type"] ) : '';

		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'uacf7dpe-nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce.' ] );
			wp_die();
		}


		$response = array();

		if ( empty( $imap_email ) || empty( $imap_password ) || empty( $imap_server ) || empty( $imap_connection_port ) || empty( $imap_connection_type ) ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'All fields are required.', 'ultimate-addons-cf7' );
			wp_send_json( $response );
			wp_reset_postdata();
			wp_die();
		}

		if ( function_exists( 'imap_open' ) && extension_loaded( 'imap' ) ) {
			$connection = @imap_open( "{{$imap_server}:{$imap_connection_port}/imap/{$imap_connection_type}/novalidate-cert}INBOX", $imap_email, $imap_password, 0, 0 );

			if ( $connection ) {
				$uids = imap_search( $connection, 'ALL', SE_UID );
				$last_uid = $uids ? $uids[ count( $uids ) - 1 ] : 0;
				$response['status'] = 'success';
				$response['message'] = esc_html__( 'Connection Successful', 'ultimate-addons-cf7' );
				update_option( 'uacf7db_ep_last_uid', $last_uid );

				$connection_data["connection"] = esc_html__( 'Connection Successful', 'ultimate-addons-cf7' );
				$connection_data["is-active"] = 1;
			} else {
				$response['status'] = 'error';
				$response['message'] = esc_html__( 'Connection Failed: ', 'ultimate-addons-cf7' ) . imap_last_error();

				$connection_data['connection'] = esc_html__( 'Connection Failed: ', 'ultimate-addons-cf7' ) . imap_last_error();
				$connection_data["is-active"] = 0;
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'PHP-IMAP Extension Not Installed. Enable it from your cPanel or contact to your host provider.', 'ultimate-addons-cf7' );
			$connection_data['connection'] = esc_html__( 'PHP-IMAP Extension Not Installed. Enable it from your cPanel or contact to your host provider.', 'ultimate-addons-cf7' );
			$connection_data["is-active"] = 0;
		}

		update_option( 'uacf7db_ep_imap_is_active', $connection_data );
		wp_send_json( $response );
		wp_reset_postdata();
		wp_die();
	}

	public function uacf7dp_single_imap_sync_callback() {

		if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( $_POST['_nonce'], 'uacf7dpe-nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid nonce.' ] );
			wp_die();
		}

		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if ( $connection_type !== 'imap' ) {
			return;
		}

		$response = array();
		$connection = get_option( 'uacf7db_ep_imap_is_active' );

		if ( empty( $connection ) || ! empty( $connection["is-active"] ) && $connection["is-active"] == 0 ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'IMAP Connection is not active.', 'ultimate-addons-cf7' );
			wp_send_json( $response );
			wp_reset_postdata();
			wp_die();
		}

		$imap_email = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_address"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_address"] : '';

		$imap_password = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_password"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_password"] : '';

		$imap_server = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_server"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_server"] : '';

		$imap_connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_type"] : '';

		$imap_connection_port = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_port"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_port"] : '';

		if ( empty( $imap_email ) && empty( $imap_password ) && empty( $imap_server ) && empty( $imap_connection_port ) && empty( $imap_connection_type ) ) {
			$response['status'] = 'error';
			$response['message'] = esc_html__( 'IMAP Connection is not active.', 'ultimate-addons-cf7' );
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

	function uacf7dp_init_imap_connection() {
		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if ( $connection_type !== 'imap' ) {
			return;
		}

		$page = ! empty( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';

		if ( $page == 'ultimate-addons-db' ) {
			self::init();
		}
	}

	// Need to add function to save the data in database
	static function init() {

		$connection = get_option( 'uacf7db_ep_imap_is_active' );

		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if ( $connection_type !== 'imap' ) {
			return;
		}

		if ( empty( $connection ) || ! empty( $connection["is-active"] ) && $connection["is-active"] == 0 ) {
			return;
		}

		$imap_email = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_address"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_address"] : '';

		$imap_password = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_password"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_password"] : '';

		$imap_server = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_server"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imap_email_server"] : '';

		$imap_connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_type"] : '';

		$imap_connection_port = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_port"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_imp_connection_port"] : '';

		if ( empty( $imap_email ) && empty( $imap_password ) && empty( $imap_server ) && empty( $imap_connection_port ) && empty( $imap_connection_type ) ) {
			return;
		}

		$mailbox = new Mailbox(
			"{{$imap_server}:{$imap_connection_port}/imap/{$imap_connection_type}/novalidate-cert}INBOX",
			$imap_email,
			$imap_password
		);

		$last_uid = get_option( 'uacf7db_ep_last_uid', 0 );
		$next_uid = $last_uid + 1;

		try {
			$all_mails = @imap_fetch_overview( $mailbox->getImapStream(), $next_uid . ':*', FT_UID );
			$all_uids = array();
			$mail_data = array();
			$count = 0;

			foreach ( $all_mails as $overview ) {
				$all_uids[] = $overview->uid;

				if ( $overview->uid == $next_uid ) {
					continue;
				}
				$count++;

				if ( $count > 10 ) {
					break;
				}

			}

			if ( count( $all_uids ) > 0 ) {
				foreach ( $all_uids as $uid ) {
					$last_uid = get_option( 'uacf7db_ep_last_uid', 0 );
					if ( $last_uid < $uid ) {
						update_option( 'uacf7db_ep_last_uid', $uid );
					} else {
						break;
					}
					$mail = $mailbox->getMail( $uid );
					$mail_data[] = self::get_mail_data( $mail, $mailbox );
				}
			} else {
				$mail = $mailbox->getMail( $next_uid );
				$mail_data[] = self::get_mail_data( $mail, $mailbox );
			}

			$mailbox->disconnect();

		} catch (\Exception $ex) {
			if ( preg_match( '/AUTHENTICATIONFAILED/', $ex->getMessage() ) ) {
				$connection["is-active"] = 0;
				$connection["connection"] = $ex->getMessage();
				update_option( "uacf7db_ep_imap_is_active", $connection );
			}
		}

		set_mail_data_from_db( $mail_data );
	}

	static function get_mail_data( $mail, $mailbox ) {
		$mail_data = array();

		$text_message = ! empty( $mail->textPlain ) ? $mail->textPlain : '';
		$text_message = preg_replace( '#(^\w.+:\n)?(^>.*(\n|$))#mi', "", $mail->textPlain );
		$text_message = preg_replace( '#([\w].\s\d{4}\S\d{2}\S.+)#mi', '', $text_message );
		$text_message = preg_replace( '/\nOn(.*?)wrote:(.*?)$/si', '', $text_message );

		$text_html = ! empty( $mail->textHtml ) ? $mail->textHtml : '';
		$text_html = preg_replace( '/<br><div class="gmail_quote">.*?<\/blockquote><\/div>/s', '', $text_html );

		if ( ! empty( $mail ) ) {
			$mail_data["to_address"] = ! empty( $mail->to ) ? array_keys( $mail->to ) : '';
			$mail_data["cc_address"] = ! empty( $mail->cc ) ? array_keys( $mail->cc ) : '';
			$mail_data["reply_user"] = $mail->fromName ? $mail->fromName : '';
			$mail_data["reply_to_mail"] = ! empty( $mail->replyTo ) ? array_keys( $mail->replyTo )[0] : '';
			$mail_data["reply_mail"] = ! empty( $mail->replyTo ) ? array_keys( $mail->replyTo )[0] : $mail->fromAddress;
			$mail_data["reply_subject"] = $mail->subject;
			$mail_data["reply_message_html"] = wp_kses_post( $text_html );
			$mail_data["reply_message_text"] = esc_html( $text_message );
			$mail_data["source"] = 'imap';
			$mail_data["submit_time"] = $mail->date;
			$mail_data["type"] = "response";
			// $mail_data["references"] = $mail->References;

			if ( ! empty( $mail->messageId ) ) {
				$mail_data["message_id"] = preg_replace( '/<([^>]+)>/', '$1', $mail->messageId );
			}

			// if ( ! empty( $mail->headers["references"] ) ) {
			// 	$mail_data["references"] = esc_html( $mail->headers["references"] );
			// }
		}

		return $mail_data;
	}
}

new ImapConnection();