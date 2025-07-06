<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'functions.php';
require_once 'inc/migrator.php';

/** initialise the Database pro class
 * Database Pro prefix $uacf7dp
 * @author M Hemel hasan
 * @return UACF7_DATABASE_PRO
 */
class UACF7_DATABASE_PRO {
	private $uacf7dp_status = '';

	public function __construct() {
		/*
		 * Creating tables and start migrator after active the plugin or active the addon
		 */
		add_action( 'admin_init', array( $this, 'uacf7dp_register_activation' ), 11, 2 );

		// Call the hook
		add_filter( 'uacf7dp_send_form_data_before_insert', [ $this, 'uacf7dp_get_form_data_before_insert' ], 10, 2 );

		// Enqueue necessary files 
		add_action( 'admin_enqueue_scripts', [ $this, 'wp_enqueue_admin_script' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'UACF7dp_ep_enqueue_admin_script' ] );

		// Hook the function to handle the AJAX request
		add_action( 'wp_ajax_update_uacf7dp_database_pro_status', [ $this, 'update_uacf7dp_database_pro_status' ] );

		// add_filter( 'wpcf7_load_js', '__return_false' );
		$this->uacf7dp_check_tables_existence();
	}

	public function uacf7dp_register_activation() {
		// Call the function conditionally
		if ( ! $this->uacf7dp_check_tables_existence() ) {
			$this->uacf7dp_data_table_pro_func();
		}

		$this->uacf7dp_status = get_option( 'uacf7dp_database_pro_status' );
		if ( ! isset( $this->uacf7dp_status ) || $this->uacf7dp_status === 'no' ) {

			// Creating tables after addon active
			$this->uacf7dp_data_table_pro_func();

			// Data migrate free to pro
			$migrater = new UACF7_DBMigrator_PRO();
			$migrater->uacf7dp_check_free_db();

			update_option( 'uacf7dp_database_pro_status', 'done' );
		}

		/*
		 * Creating tables when plugin is active
		 */
		register_activation_hook( UACF7_PRO_FILE, [ $this, 'uacf7dp_data_table_pro_func' ] );
	}

	public function update_uacf7dp_database_pro_status() {
		// Check if the AJAX request is valid
		check_ajax_referer( 'uacf7dp-nonce', 'security' );

		// Get the status from the AJAX request
		$status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : 'no';

		// Update the option in the database
		update_option( 'uacf7dp_database_pro_status', $status );

		// Send a response back to the JavaScript
		wp_send_json_success( array( 'status' => $status ) );
	}


	/**
	 * It's check if table are create or not 
	 * @return bool
	 */
	public function uacf7dp_check_tables_existence() {
		global $wpdb;

		$uacf7dp_table = $wpdb->prefix . 'uacf7dp_data';
		$uacf7dp_mail = $wpdb->prefix . 'uacf7dp_mail';
		$uacf7dp_table_entry = $wpdb->prefix . 'uacf7dp_data_entry';

		// Check if tables exist
		$table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$uacf7dp_table'" ) == $uacf7dp_table &&
			$wpdb->get_var( "SHOW TABLES LIKE '$uacf7dp_mail'" ) == $uacf7dp_mail &&
			$wpdb->get_var( "SHOW TABLES LIKE '$uacf7dp_table_entry'" ) == $uacf7dp_table_entry;


		// cehck Update database column 
		$column_exists = $wpdb->get_var( "SHOW COLUMNS FROM $uacf7dp_mail LIKE 'form_mail_user'" );
		if ( ! $column_exists ) {
			$wpdb->query( "ALTER TABLE $uacf7dp_mail ADD COLUMN `form_mail_user` TEXT NOT NULL" );
		}

		return $table_exists;
	}


	/**
	 * If table not created then this will create the table uacf7dp_data_table_pro_func
	 * @return void
	 */
	public function uacf7dp_data_table_pro_func() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$uacf7dp_table = $wpdb->prefix . 'uacf7dp_data';
		$uacf7dp_mail = $wpdb->prefix . 'uacf7dp_mail';
		$uacf7dp_table_entry = $wpdb->prefix . 'uacf7dp_data_entry';

		// form info table 
		if ( $wpdb->get_var( "show tables like '$uacf7dp_table'" ) != $uacf7dp_table ) {
			$sql = 'CREATE TABLE ' . $uacf7dp_table . ' (
                `data_id` int(11) NOT NULL AUTO_INCREMENT,
				`cf7_form_id` int(11) NOT NULL,
				`submit_ip` int(11) NOT NULL,
                `submit_time` timestamp NOT NULL,
                UNIQUE KEY id (data_id)
                ) ' . $charset_collate . ';';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}

		// form entry table 
		if ( $wpdb->get_var( "show tables like '$uacf7dp_table_entry'" ) != $uacf7dp_table_entry ) {
			$sql = 'CREATE TABLE ' . $uacf7dp_table_entry . ' (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `cf7_form_id` int(11) NOT NULL,
                `data_id` int(11) NOT NULL,
                `fields_name` varchar(250),
                `value` varchar(250),
                UNIQUE KEY id (id)
                ) ' . $charset_collate . ';';
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );

		} else {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';

			maybe_convert_table_to_utf8mb4( $uacf7dp_table_entry );
			$sql = 'ALTER TABLE ' . $uacf7dp_table_entry . ' change fields_name fields_name VARCHAR(250) character set utf8, change value value text character set utf8;';
			$wpdb->query( $sql );
		}

		// Mail info table 
		if ( $wpdb->get_var( "show tables like '$uacf7dp_mail'" ) != $uacf7dp_mail ) {
			$sql = 'CREATE TABLE ' . $uacf7dp_mail . ' (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`data_id` int(11) NOT NULL,
		`cf7_form_id` int(11) NOT NULL,
		`mail_status` text NOT NULL,
		`form_mail` text NOT NULL,
		`form_mail_user` text NOT NULL,
		`mail_subject` VARCHAR(255) NOT NULL,
		`mail_body` text NOT NULL,
		`submit_time` timestamp NOT NULL,
		UNIQUE KEY id (id)
		) ' . $charset_collate . ';';

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
		}
	}

	/**
	 * This will store contact form data to the database
	 * @param mixed $contact_form
	 * @return void
	 */
	public function uacf7dp_get_form_data_before_insert( $insert_data, $extra ) {
		global $wpdb;
		$submission = WPCF7_Submission::get_instance();
		$data = array_merge( $insert_data, $extra );
		$submit_ip = $extra['submit_ip'];
		$submit_time = current_time('mysql');

		$submit_form_id = $submission->get_contact_form()->id();

		$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'uacf7dp_data(`cf7_form_id`, `submit_ip`, `submit_time`) VALUES (%d, %d, %s)', $submit_form_id, $submit_ip, $submit_time ) );
		$data_id = $wpdb->insert_id;

		$uacf7dp_no_save_fields = uacf7dp_no_save_fields();



		foreach ( $data as $k => $v ) {
			if ( in_array( $k, $uacf7dp_no_save_fields ) ) {
				continue;
			} else {
				if ( is_array( $v ) ) {
					$v = implode( "\n", $v );
				}

				$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'uacf7dp_data_entry(`cf7_form_id`, `data_id`, `fields_name`, `value`) VALUES (%d,%d,%s,%s)', $submit_form_id, $data_id, $k, $v ) );
			}
		}
	}

	/**
	 * This will load necessary files
	 * @return void
	 */
	public function wp_enqueue_admin_script( $screen ) {

		$tf_options_screens = array(
			'ultimate-addons_page_ultimate-addons-db',
			'ultimate-addons_page_uacf7_addons',
		);



		if ( in_array( $screen, $tf_options_screens ) ) {
			$url = wp_parse_url( home_url() );

			$option = get_option( 'uacf7_settings' );

			if(isset( $option['uacf7_enable_database_pro'] ) && $option['uacf7_enable_database_pro'] == true ){
				
				// Enqueue jQuery UI
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-core' );
				wp_enqueue_script( 'jquery-ui-widget' );
				wp_enqueue_script( 'jquery-ui-mouse' );
				wp_enqueue_script( 'jquery-ui-sortable' );

				
				// Enqueue DataTables CSS
				wp_enqueue_style( 'database-pro-admin-style', UACF7_PRO_ADDONS . '/database-pro/assets/css/database-pro-style.css' );
				wp_enqueue_style( 'database-pro-table-style', 'https://cdn.datatables.net/v/ju/jqc-1.12.4/jszip-3.10.1/dt-1.13.10/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/r-2.5.0/rr-1.4.1/sc-2.3.0/sl-1.7.0/sr-1.3.0/datatables.min.css' );

				// Enqueue DataTables JS
				wp_enqueue_script( 'database-pro-table-script', 'https://cdn.datatables.net/v/ju/jqc-1.12.4/jszip-3.10.1/dt-1.13.10/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/cr-1.7.0/date-1.5.1/fc-4.3.0/r-2.5.0/rr-1.4.1/sc-2.3.0/sl-1.7.0/sr-1.3.0/datatables.min.js', array( 'jquery' ), null, true );
				// Enqueue PDFMake
				wp_enqueue_script( 'database-pro-pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js', array(), null, true );
				// Enqueue PDFMake Fonts
				wp_enqueue_script( 'database-pro-pdfmake-font', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js', array(), null, true );

				wp_enqueue_script( 'uacf7dp-database-pro-icons-script', UACF7_PRO_ADDONS . '/database-pro/assets/js/icons.js', array(), null, true );
				wp_enqueue_script( 'uacf7dp-database-pro-table-script', UACF7_PRO_ADDONS . '/database-pro/assets/js/database-pro-main.js', array(), null, true );
				wp_localize_script( 'uacf7dp-database-pro-table-script', 'uACF7DP_Pram', array(
					'admin_url' => get_admin_url() . 'admin.php',
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'uacf7dp-nonce' ),
				) );

				wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.3/jquery-ui.min.js', array( 'jquery' ), null, true );

			}
			
		}

	}

	public function UACF7dp_ep_enqueue_admin_script(){

		$url = wp_parse_url( home_url() );

		wp_enqueue_script( 'uacf7dp_email_piping', UACF7_PRO_ADDONS . '/database-pro/assets/js/uacf7bd_mail_piping.js', array(), null, true );
		wp_localize_script( 'uacf7dp_email_piping', 'uACF7DPE_Pram', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'uacf7dpe-nonce' ),
			'site_url' => esc_url( $url['scheme'] . '://' . $url['host'] ),
			'redirect_url' => esc_url( admin_url( 'admin.php' ) ),
			'connection_success' => esc_html__( 'Connection Successful', 'ultimate-addons-cf7' ),
			'uacf7dp_connection_type' => ! empty( uacf7_settings( 'uacf7dp_email_piping_tap' )['uacf7dp_connection_type'] ) ? uacf7_settings( 'uacf7dp_email_piping_tap' )['uacf7dp_connection_type'] : 'imap',
		) );
	}

}


new UACF7_DATABASE_PRO();




