<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Register the Database pro Menu class
 * Database Pro prefix $uacf7dp
 * Author M Hemel hasan
 * @return database admin Page
 */

class UACF7_DATABASE_PRO_ADMIN_Single_Entries {
	public function __construct() {
		add_filter( 'uacf7dp_entries_single_page', array( $this, 'uacf7dp_entries_single_view_page_view_fun' ), 10, 1 );
	}

	public function uacf7dp_entries_single_view_page_view_fun( $page ) {
		$page = $this->uacf7dp_data_entries_single_page();
		return $page;
	}

	public function truncateString( $string, $length = 50 ) {
		// Check if the string length exceeds the specified length
		if ( strlen( $string ) > $length ) {
			// Truncate the string and add ellipsis
			return substr( $string, 0, $length ) . '...';
		}
		// Return the original string if it doesn't exceed the specified length
		return $string;
	}

	public function uacf7dp_data_entries_single_page() {

		$icons = UACF7_PRO_PATH_ADDONS . '/database-pro/admin/icons.php';
		if ( file_exists( $icons ) ) {
			require_once $icons;
		}

		$connection_type = ! empty( uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] ) ? uacf7_settings( "uacf7dp_email_piping_tap" )["uacf7dp_connection_type"] : 'imap';

		if($connection_type == 'imap') {
			$getmail_data = get_option( 'uacf7db_ep_imap_is_active' );
		} else {
			$getmail_data = get_option( 'uacf7dp_mp_gmail_connection_data' );
		}

		$uacf7dp_admin_menu = new UACF7_DATABASE_PRO_ADMIN_MENU();
		global $wpdb;
		$cf7d_entry_order_by = '`data_id` DESC';

		// In your PHP file for the target page (ultimate-addons-db)
		$form_id = isset( $_GET['form_id'] ) ? $_GET['form_id'] : null;
		$entries = isset( $_GET['entries'] ) ? $_GET['entries'] : null;

		$get_form_data = $wpdb->prepare(
			"SELECT * 
			FROM {$wpdb->prefix}uacf7dp_data_entry 
			WHERE `cf7_form_id` = %d 
				AND data_id IN (
					SELECT data_id 
					FROM (
						SELECT data_id 
						FROM {$wpdb->prefix}uacf7dp_data_entry 
						WHERE `cf7_form_id` = %d 
						GROUP BY `data_id` 
						ORDER BY %s
					) AS temp_table
				)
			ORDER BY %s",
			$form_id,
			$form_id,
			$cf7d_entry_order_by,
			$cf7d_entry_order_by
		);

		$form_data = $wpdb->get_results( $get_form_data );

		$uacf7dp_sortable = $uacf7dp_admin_menu->uacf7dp_data_sortable( $form_data );

		$fields = $uacf7dp_admin_menu->uacf7dp_get_db_fields( $form_id );

		$gettingsomething = apply_filters( 'uacf7dp_column_default_fields', $uacf7dp_sortable, $fields );

		$filteredArray = array_filter( $gettingsomething, function ($item) use ($entries) {
			return isset( $item['id'] ) && $item['id'] == $entries;
		} );

		$filteredItem = current( $filteredArray );

		// Get Form details 
		$ContactForm = WPCF7_ContactForm::get_instance( $form_id );
		$form_fields = $ContactForm->scan_form_tags();

		// filter out signature tag
		$uacf7_signature_tag = [];
		foreach ( $form_fields as $field ) {
			if ( $field->type == 'uacf7_signature*' || $field->type == 'uacf7_signature' ) {
				$uacf7_signature_tag[] = $field->name;
			}
		}

		// Email
		$uacf7_mail_tag = [];
		foreach ( $form_fields as $field ) {
			if ( $field->type == 'email*' || $field->type == 'email' ) {
				$uacf7_mail_tag[] = $field->name;
			}
		}

		// Signature image  
		$encryptionKey = 'AES-256-CBC';
		// Files Paths
		$upload_dir = wp_upload_dir();
		$dir = $upload_dir['baseurl'];
		$signaturepath = $upload_dir['basedir'];
		$replace_dir = '/uacf7-uploads/';

		if ( ! empty( $filteredItem ) ) {
			$get_mail_data = $wpdb->prepare(
				"SELECT * 
				FROM {$wpdb->prefix}uacf7dp_mail
				WHERE `cf7_form_id` = %d 
				AND `data_id` = %d", // Use = for equality check
				$filteredItem['cf7_form_id'],
				$filteredItem['id']
			);
		}

		$mail_return = $wpdb->get_results( $get_mail_data );
		ob_start();
		?>
		<div class="uacf7dp_entries_single_view_page">

			<div id="uacf7dp_addons_header" class="uacf7dp-tabcontent">
				<img src="<?php echo UACF7_PRO_ADDONS ?>/database-pro/assets/img/ultimate-logo.png" alt="logo" />
				<h4 class="uacf7dp_entries_single_view_heading">
					<a class="active" href="?page=ultimate-addons-db&form_id=<?php echo $filteredItem['cf7_form_id'] ?>&nonce=">
						<?php echo esc_html__( get_the_title( $filteredItem['cf7_form_id'] ), 'ultimate-addons-cf7' ) ?>
						/ ID
						<?php echo esc_html__( '#' . $filteredItem['id'], 'ultimate-addons-cf7' ) ?>
					</a>
				</h4>
			</div> <!-- End Header area -->

			<div class="uacf7dp_entries_single_view_page_container">
				<div class="uacf7dp_entries_single_view_page_header">
					<div class="uacf7dp_entries_head_btn">
						<ul class="uacf7dp_btn_group">
							<li class="uacf7dp_btn_group_item">
								<span class="uacf7dp_head_btn">
									<img src="<?php echo UACF7_PRO_ADDONS ?>/database-pro/assets/img/arrow-left.png"
										alt="thum" />
									<span>Back</span>
								</span>
							</li>
						</ul>
					</div>
				</div>

				<div class="uacf7dp_entries_single_view_details">

					<div class="uacf7dp_form_entries_details">
						<p class="uacf7dp_entries_details_top">Details</p>
						<?php
						foreach ( $filteredItem as $key => $value ) {
							// Skip these keys
							if ( $key === 'status' || $key === 'id' || $key === 'cf7_form_id' || $key === 'submit_ip' || $key === 'submit_time' || $key === 'submit_browser' || $key === 'submit_os' || $key === 'submit_date' ) {
								continue;
							}

							if ( in_array( $key, $uacf7_signature_tag ) ) {

								if ( empty( $value ) ) {
									continue;
								}

								$pathInfo = pathinfo( $value );
								$extension = strtolower( $pathInfo['extension'] );
								$decryptedData = $this->decrypt_and_display( $signaturepath . $value, $encryptionKey );

								if ( $decryptedData !== null ) {
									$imageData = 'data:image/jpeg;base64,' . base64_encode( $decryptedData );
								}

								// Check old data
								if ( $extension == 'enc' ) {
									$srcAttribute = $imageData;  // Set to empty or another value if needed
								} else {
									$srcAttribute = $value;
								}
								?>
								<div class="uacf7dp_each_entries">
									<span class="uacf7dp_entrie_label">
										<?php echo $key ?>
									</span>

									<span class="uacf7dp_entrie_value">
										<button id="signature_view_btn"> View </button>
										<a class="" href="<?php echo $srcAttribute ?>" download="decrypted_image.jpg">
											<button class="signature_download_btn">Download</button>
										</a>
									</span>

									<div class="signature_view_pops">
										<img class="signature_view_pops_img" src="<?php echo $srcAttribute ?>" />
									</div>
								</div>
								<?php
							} else {
								if ( strstr( $value, $replace_dir ) ) {
									$value = str_replace( $replace_dir, "", $value );
									?>
									<div class="uacf7dp_each_entries">
										<span class="uacf7dp_entrie_label">
											<?php echo $key ?>
										</span>
										<span class="uacf7dp_entrie_value">
											<a href="<?php echo esc_url( $dir . $replace_dir . $value ) ?>" target="_blank">
												<?php echo $value ?>
											</a>
										</span>
									</div>
									<?php
								} else {
									?>
									<div class="uacf7dp_each_entries">
										<span class="uacf7dp_entrie_label">
											<?php echo $key ?>
										</span>
										<span class="uacf7dp_entrie_value">
											<?php echo $value ?>
										</span>
									</div>
									<?php
								}
							}
						} ?>
					</div>
					<div class="uacf7dp_form_submission_details">
						<p class="uacf7dp_entries_submission_details_top">Log Details
							<?php echo esc_html__( '#' . $filteredItem['id'], 'ultimate-addons-cf7' ); ?>
						</p>
						<div class="uacf7dp_submission_entries_wrapper">
							<?php
							foreach ( $filteredItem as $key => $value ) {
								if ( $key === 'submit_ip' || $key === 'submit_browser' || $key === 'submit_os' || $key === 'submit_date' || $key === 'submit_time' ) {
									?>
									<div class="uacf7dp_entrie_submit_info">
										<?php
										if ( $key === 'submit_ip' ) {
											echo $ip_icon, $value;
										} elseif ( $key === 'submit_browser' ) {
											echo $browser_icon, $value;
										} elseif ( $key === 'submit_os' ) {
											echo $drives_icon, $value;
										} elseif ( $key === 'submit_date' ) {
											echo $date_icon, $value;
										} elseif ( $key === 'submit_time' ) {
											echo $time_icon, $value;
										}
										?>
									</div>
									<?php
								} else {
									continue;
								}
							}
							?>
						</div>
					</div>

				</div>
			</div>

			<?php
			if ( $uacf7_mail_tag ) { ?>
				<!-- $send_icon -->
				<div class="uacf7dp_entire_reply_mail_Warp">
					<div class="uacf7dp_entire_reply_mail_box">
						<div class="uacf7dp_entire_reply_mail_head">
							<p><span>To:</span> <?php echo $filteredItem[ $uacf7_mail_tag[0] ] ?></p>
							<?php if ( is_array($getmail_data) && isset($getmail_data["is-active"]) && $getmail_data["is-active"] ) { ?>
								<button id="uacf7dp_entire_reply_mail_head_btn"><?php echo $sync_icons ?> Refresh</button>
							<?php } ?>
						</div>
						<div class="accordion">
							<?php
							if ( $mail_return ) {
								foreach ( $mail_return as $data ) { ?>
									<div class="accordion-item">
										<div class="accordion-header" id="heading<?php echo $data->id ?>">
											<button class="accordion-button" id="accordion-button" type="button" aria-expanded="false"
												aria-controls="collapse<?php echo $data->id ?>">
												<?php  
												$mailread = $data->mail_status == 'Email Receives successfully!'  
												? $mail_arrow_rotate . $data->form_mail_user . '<span>―</span>'  
												: $mail_arrow . $data->form_mail_user . '<span>―</span>';
												?> 
												<?php
												echo $mailread;
												echo $this->truncateString( $data->mail_subject );
												?>
											</button>
										</div>
										<div id="collapse<?php echo $data->id ?>" class="accordion-collapse">
											<div class="accordion-body">
												<?php echo $data->mail_body ?>
											</div>
										</div>
									</div>
								<?php }
							} ?>
						</div>

						<form id="uacf7dp_entire_reply_mail_send" method="post" action="">
							<div class="uacf7dp_entire_reply_mail_send_accordion">
								<input type="hidden" name="cf7_form_id" value="<?php echo $filteredItem['cf7_form_id'] ?>" />
								<input type="hidden" name="entries_id" value="<?php echo $filteredItem['id'] ?>" />
								<input hidden type="email" placeholder="Receiver Email" name="receiver_email"
									value="<?php echo $filteredItem[ $uacf7_mail_tag[0] ] ?>" required />
								<input hidden type="text" placeholder="Subject" name="email_subject"
									value="<?php echo get_the_title( $filteredItem['cf7_form_id'] ) ?> Response" required />
								<textarea name="email_message" placeholder="Message" required></textarea>
							</div>
							<button type="submit" name="send_email" class="button button-primary">
								Send <?php echo $send_icon; ?>
							</button>
						</form>
					</div>

					<div id="loadding_Mail">
						<div class="loadding"></div>
						<p>Mail sending...</p>
					</div>
				</div>
				<?php
			} else { ?>
				<div class="uacf7dp_entire_reply_mail_notSupport">
					<div class="uacf7dp_entire_notSupport_wrap">
						<p>I regret to inform you that the email reply function is currently unavailable on this form.</p>
					</div>
				</div>

			<?php } ?>

		</div>
		<?php
		$uacf7dp_data_single_entries = ob_get_clean();
		return $uacf7dp_data_single_entries;

		// var_dump( $filteredItem );
		// var_dump( $mail_return );
	}

	public function decrypt_and_display( $inputFile, $key ) {

		if ( ! file_exists( $inputFile ) ) {
			die( "Error: The file does not exist." );
		}

		// Read the encrypted content
		$encryptedFileContent = file_get_contents( $inputFile );

		if ( $encryptedFileContent === false ) {
			die( "Error: Unable to read file content." );
		}

		// Extract IV
		$ivSize = openssl_cipher_iv_length( 'aes-256-cbc' );
		$iv = substr( $encryptedFileContent, 0, $ivSize );

		// Extract encrypted data
		$encryptedData = substr( $encryptedFileContent, $ivSize );

		// Decrypt the data
		$decryptedData = openssl_decrypt( $encryptedData, 'aes-256-cbc', $key, 0, $iv );

		// Output the decrypted data directly
		//header( 'Content-Type: image/jpg' ); // Adjust content type based on your file type
		return $decryptedData;
	}

}

new UACF7_DATABASE_PRO_ADMIN_Single_Entries();