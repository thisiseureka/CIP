<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* Register the Database pro Menu class
 * Database Pro prefix $uacf7dp
 * Author M Hemel hasan
 * @return database admin menu
 */

class UACF7_DATABASE_PRO_ADMIN_MENU {

	public function __construct() {
		add_filter( 'uacf7_database_admin_page', array( $this, 'uacf7_create_database_page_pro' ), 10, 1 );

		add_action( 'wp_ajax_uacf7dp_get_table_data', [ $this, 'ajax_get_table_data' ] );
		add_action( 'wp_ajax_nopriv_uacf7dp_get_table_data', array( $this, 'ajax_get_table_data' ) );

		add_action( 'wp_ajax_uacf7dp_deleted_table_datas', [ $this, 'uacf7dp_deleted_table_datas' ] );
		add_action( 'wp_ajax_nopriv_uacf7dp_deleted_table_datas', array( $this, 'uacf7dp_deleted_table_datas' ) );

		// For Viwe the data on popup
		add_action( 'wp_ajax_uacf7dp_view_table_data', [ $this, 'uacf7dp_view_table_data' ] );
		add_action( 'wp_ajax_nopriv_uacf7dp_view_table_data', array( $this, 'uacf7dp_view_table_data' ) );

		// uacf7dp_entire_reply_mail
		add_action( 'wp_ajax_uacf7dp_entire_reply_mail', [ $this, 'uacf7dp_entire_reply_mail' ] );
		add_action( 'wp_ajax_nopriv_uacf7dp_entire_reply_mail', array( $this, 'uacf7dp_entire_reply_mail' ) );
	}

	public function uacf7_create_database_page_pro( $page ) {

		// In your PHP file for the target page (ultimate-addons-db)
		$form_id = isset( $_GET['form_id'] ) ? $_GET['form_id'] : null;
		$entries = isset( $_GET['entries'] ) ? $_GET['entries'] : null;


		if ( $form_id !== null && $entries !== null ) {
			$page = array( $this, 'uacf7dp_entries_single_view_page' );
		} else {
			$page = array( $this, 'uacf7_create_database_page_pro_callback' );
		}

		return $page;
	}

	public function ajax_get_table_data() {
		uacf7dp_checkNonce();
		global $wpdb;
		$cf7d_entry_order_by = '`data_id` DESC';
		$form_id = isset( $_POST['form_id'] ) && $_POST['form_id'] >= 0 ? intval( $_POST['form_id'] ) : 0;

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

		$uacf7dp_sortable = $this->uacf7dp_data_sortable( $form_data );

		$fields = $this->uacf7dp_get_db_fields( $form_id );

		$orgFieldsData = apply_filters( 'uacf7dp_column_default_fields', $uacf7dp_sortable, $fields );

		wp_send_json_success(
			array(
				'fields' => $fields,
				'data_sorted' => $orgFieldsData,
			)
		);
		wp_die();
	}

	public function uacf7dp_entries_single_view_page() {
		echo apply_filters( 'uacf7dp_entries_single_page', array( $this, 'nono' ) );
	}

	public function nono() {
		echo 'No data found';
	}

	public function uacf7dp_entire_reply_mail() {
		global $wpdb;
		// nonce verify
		uacf7dp_checkNonce();

		$receiver_email = sanitize_email( $_POST['data']['receiver_email'] );
		$email_subject = sanitize_text_field( $_POST['data']['email_subject'] );
		$email_message = sanitize_textarea_field( $_POST['data']['email_message'] );
		$cf7_form_id = intval( $_POST['data']['cf7_form_id'] );
		$data_id = intval( $_POST['data']['entries_id'] );

		$data = $_POST['data'];

		// Capability check
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to perform this action.' );
		}

		if ( isset( $receiver_email ) ) {
			$to = $receiver_email;
			$subject = $email_subject . ' / ID #' . $cf7_form_id . '/' . $data_id;
			$message = wp_kses_post( $email_message );

			$form_mail_user = get_option('admin_name', 'Admin');

			$result = wp_mail( $to, $subject, $message);

			$submit_time = date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) );

			if ( $result ) {
				$mail_status = 'Email sent successfully!';

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'uacf7dp_mail(`data_id`, `cf7_form_id`, `mail_status`, `form_mail`, `form_mail_user`, `mail_subject`, `mail_body`, `submit_time`) VALUES (%d, %d, %s, %s, %s, %s, %s, %s)', $data_id, $cf7_form_id, $mail_status, $to, $form_mail_user, $subject, $message, $submit_time ) );
			} else {
				$mail_status = 'Error sending email. Check your server configurations';
			}
		}

		wp_send_json_success(
			array(
				'message' => $mail_status,
				'data' => $data,
			)
		);
		wp_die();

	}

	public function uacf7_create_database_page_pro_callback() {
		global $wpdb;

		$form_id = isset( $_GET['form_id'] ) ? $_GET['form_id'] : null;

		$list_forms = get_posts(
			array(
				'post_type' => 'wpcf7_contact_form',
				'posts_per_page' => -1
			)
		);

		?>
		<div id="uacf7dp_addons_pages">
			<div id="loading">
				<div class="loading"></div>
			</div>
			<div id="uacf7dp_addons_header" class="uacf7dp-tabcontent">
				<img src="<?php echo UACF7_PRO_ADDONS ?>/database-pro/assets/img/ultimate-logo.png" alt="logo" />
				<h4 class="uacf7dp_main-heading">
					<?php echo esc_html__( 'Database', 'ultimate-addons-cf7' ); ?>
				</h4>
				<div class="uacf7dp_header-form">
					<h4>
						<?php echo esc_html__( 'Select form', 'ultimate-addons-cf7' ); ?>
					</h4>

					<select name="select_from_submit" id="select_from_submit">
						<option value=" 0" <?php selected( isset( $_POST['form-id'] ) && $_POST['form-id'] == 0 ); ?>>
							<?php echo esc_html__( 'Select form', 'ultimate-addons-cf7' ); ?>
						</option>
						<?php
						foreach ( $list_forms as $form ) {
							// count number of data
							$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM " . $wpdb->prefix . "uacf7dp_data WHERE cf7_form_id = %d", $form->ID ) );

							echo '<option value="' . esc_attr( $form->ID ) . '" ' . selected( isset( $_POST['form-id'] ) && $_POST['form-id'] == $form->ID, true ) . '>';
							echo esc_attr( $form->post_title ) . ' ( ' . $count . ' )';
							echo '</option>';
						}
						?>
					</select>
				</div>
			</div>

			<div id="uacf7dp_table_container_wrap">

				<div id="uacf7dp_table_container" class="uacf7dp-table-responsive">
					<table id="uacf7dp-database-tablePro"></table>
				</div>
				<div class="uacf7dp_table_empty">
					<img src="<?php echo UACF7_PRO_ADDONS ?>/database-pro/assets/img/select.png" alt="thum" />
					<p>
						<span>To view data, please select a form</span>
						Once selected, the data will be displayed on the screen.
						The data can be filtered according to the
						desired parameters. The data can also be exported into a spreadsheet for further analysis.
					</p>
				</div>
			</div>

			<section class="uacf7_popup_preview">
				<div class="uacf7_popup_preview_content">

					<div id="uacf7_popup_wrap">
						<div class="db_popup_view">
							<div class="close" title="Exit Full Screen">╳</div>
							<div id="db_view_wrap">
							</div>
						</div>
					</div>
				</div>
			</section>

		</div>
		<?php
	}

	// PopUp Data view Processing
	public function uacf7dp_view_table_data() {
		global $wpdb;

		// Capability check
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'You do not have permission to perform this action.' );
		}

		// nonce verify
		uacf7dp_checkNonce();

		// Get from Table
		$form_id = isset( $_POST['cf7_form_id'] ) && $_POST['cf7_form_id'] >= 0 ? intval( $_POST['cf7_form_id'] ) : 0;
		$all_data = isset( $_POST['all_data'] ) && is_array( $_POST['all_data'] ) ? $_POST['all_data'] : null;

		$encryptionKey = 'AES-256-CBC';

		// Get Form details 
		$ContactForm = WPCF7_ContactForm::get_instance( $form_id );
		$form_fields = $ContactForm->scan_form_tags();

		// Files Paths
		$upload_dir = wp_upload_dir();
		$dir = $upload_dir['baseurl'];
		$signaturepath = $upload_dir['basedir'];
		$replace_dir = '/uacf7-uploads/';

		// filter out signature tag
		$uacf7_signature_tag = [];


		foreach ( $form_fields as $field ) {
			if ( $field->type == 'uacf7_signature*' || $field->type == 'uacf7_signature' ) {
				$uacf7_signature_tag[] = $field->name;
			}
		}

		$html = '<div class="db-view-wrap"> 
                    <h3>' . get_the_title( $form_id ) . '</h3>
                    <span>' . esc_html( $all_data['submit_time'] ) . '</span>
                    <table class="wp-list-table widefat fixed striped table-view-list">';
		$html .= '<tr> <th><strong>Fields</strong></th><th><strong>Values</strong> </th> </tr>';
		foreach ( $all_data as $key => $value ) {

			// Skip these keys
			if ( $key === 'status' || $key === 'id' || $key === 'cf7_form_id' ) {
				continue;
			}

			if ( is_array( $value ) ) {
				$value = implode( ", ", $value );
			}

			if ( in_array( $key, $uacf7_signature_tag ) ) {

				if ( empty( $value ) ) {
					continue;
				}

				$pathInfo = pathinfo( $value );
				$extension = strtolower( $pathInfo['extension'] );
				$fileNameWithoutExtension = pathinfo( $value, PATHINFO_FILENAME );

				// Image Loaded
				$token = md5( uniqid() );
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

				$html .= '
					<tr> 
						<td>
							<strong>' . esc_attr( $key ) . '</strong>
						</td> 
						<td>
							<button id="signature_view_btn">' . esc_html( 'View' ) . '</button>
							<a class="" href="' . $srcAttribute . '" download="' . $fileNameWithoutExtension . '">
								<button class="signature_download_btn">Download</button>
							</a>
						</td>
					</tr>
					<div class="signature_view_pops">
						<img class="signature_view_pops_img"  src="' . $srcAttribute . '"/>
					</div>
					';
			} else {
				if ( strstr( $value, $replace_dir ) ) {
					$value = str_replace( $replace_dir, "", $value );
					$html .= '<tr> <td><strong>' . esc_attr( $key ) . '</strong></td> <td><a href="' . esc_url( $dir . $replace_dir . $value ) . '" target="_blank">' . esc_html( $value ) . '</a></td> </tr>';
				} else {
					$html .= '<tr> <td><strong>' . esc_attr( $key ) . '</strong></td> <td>' . esc_html( $value ) . '</td> </tr>';
				}
			}

		}

		$html .= '</table></div>';

		echo $html;
		wp_die();
	}

	public function uacf7dp_data_sortable( $form_data ) {
		$result = [];

		foreach ( $form_data as $item ) {
			$dataId = $item->data_id;

			// If the array for this data_id doesn't exist, create it
			if ( ! isset( $result[ $dataId ] ) ) {
				$result[ $dataId ] = [];
			}

			// Add the item data to the array for this data_id
			$result[ $dataId ][] = [ 
				'id' => $item->id,
				'cf7_form_id' => $item->cf7_form_id,
				'data_id' => $item->data_id,
				'fields_name' => $item->fields_name,
				'value' => $item->value,
			];
		}

		return $result;
	}

	public function uacf7dp_get_db_fields( $form_id ) {
		global $wpdb;
		$sql = sprintf( 'SELECT `fields_name` FROM `' . $wpdb->prefix . 'uacf7dp_data_entry` WHERE cf7_form_id = %d GROUP BY `fields_name`', $form_id );
		$data = $wpdb->get_results( $sql );

		$fields = array();
		foreach ( $data as $k => $v ) {
			$fields[ $v->fields_name ] = $v->fields_name;
		}
		if ( $fields ) {
			$fields = apply_filters( 'uacf7dp_adminSide_fields', $fields, $form_id );
		}

		$Finalfields = array_merge( $fields, array( 'id' => 'id', 'cf7_form_id' => 'cf7_form_id' ) );

		return $Finalfields;
	}

	public function uacf7dp_deleted_table_datas() {
		uacf7dp_checkNonce();
		global $wpdb;

		$form_id = isset( $_POST['cf7_form_id'] ) && $_POST['cf7_form_id'] >= 0 ? intval( $_POST['cf7_form_id'] ) : 0;
		$data_id = isset( $_POST['data_id'] ) && $_POST['data_id'] >= 0 ? intval( $_POST['data_id'] ) : 0;

		// Check if the provided IDs are valid
		if ( $form_id <= 0 || $data_id <= 0 ) {
			wp_send_json_error( array( 'message' => 'Invalid cf7_form_id or data_id.' ) );
		}
		$wpdb->delete( "{$wpdb->prefix}uacf7dp_data", array( 'cf7_form_id' => $form_id, 'data_id' => $data_id ) );

		// Delete from wp_uacf7dp_data_entry
		$wpdb->delete( "{$wpdb->prefix}uacf7dp_data_entry", array( 'cf7_form_id' => $form_id, 'data_id' => $data_id ) );

		wp_send_json_success( array( 'message' => 'Data processed successfully' ) );
		wp_die();
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


new UACF7_DATABASE_PRO_ADMIN_MENU();
