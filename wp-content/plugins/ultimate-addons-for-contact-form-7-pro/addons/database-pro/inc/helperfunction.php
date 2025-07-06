<?php


function insult_mail_data( $data_id, $cf7_form_id, $mail_status, $to, $form_mail_user, $subject, $message, $submit_time ) {
	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'uacf7dp_mail(`data_id`, `cf7_form_id`, `mail_status`, `form_mail`, `form_mail_user`, `mail_subject`, `mail_body`, `submit_time`) VALUES (%d, %d, %s, %s, %s, %s, %s, %s)', $data_id, $cf7_form_id, $mail_status, $to, $form_mail_user, $subject, $message, $submit_time ) );
}


function set_mail_data_from_db( $mail_data ) {
	$pattern = '/ID #(\d+)\/(\d+)/';
    $mail_status = 'Email Receives successfully!';

	if ( ! empty( $mail_data ) && is_array( $mail_data ) ) {
		foreach ( $mail_data as $mail ) {

            if($mail['source'] == "gmail"){
                if ( ! empty( $mail['reply_subject'] ) && preg_match($pattern, $mail['reply_subject'], $matches) ) {
                    $data_id = $matches[2]; // Extracts "45"
                    $form_id = $matches[1]; // Extracts "123"
                    $reply_mail = $mail['reply_mail']; // Extracts "45"
                    $reply_subject = $mail["reply_subject"];
                    $reply_message = $mail['reply_message_text']; // Extracts "45"
                    $submit_time = $mail['submit_time']; // Extracts "45"
					$reply_user = $mail["reply_user"];

					insult_mail_data( $data_id, $form_id, $mail_status, $reply_mail, $reply_user, $reply_subject, $reply_message, $submit_time);
                }
            }

			if ( $mail['source'] == "imap" ) {

				if ( ! empty( $mail['reply_subject'] ) && preg_match( $pattern, $mail['reply_subject'], $matches ) ) {
					$data_id = $matches[2]; // Extracts "45"
					$form_id = $matches[1]; // Extracts "123"
					$reply_mail = $mail['reply_mail']; 
                    $reply_user = $mail["reply_user"];
					$reply_subject = $mail["reply_subject"];
					$reply_message = $mail['reply_message_text']; 
					$submit_time = $mail['submit_time'];
                    insult_mail_data($data_id, $form_id, $mail_status, $reply_mail, $reply_user, $reply_subject, $reply_message, $submit_time);
				}
			}
		}
	}
}

