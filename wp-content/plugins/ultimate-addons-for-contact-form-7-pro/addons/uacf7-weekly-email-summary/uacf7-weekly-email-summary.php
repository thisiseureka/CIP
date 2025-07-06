<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** initialise the Weekly Email Summary class
 * Weekly Email Summary prefix $uacf7wep
 * @author M Hemel hasan
 * @return UACF7_Weekly_Email_Summary
 */

class UACF7_Weekly_Email_Summary {
	public function __construct() {
		add_filter( 'cron_schedules', [ $this, 'uacf7_custom_cron_schedules' ] );

		add_action( 'init', [ $this, 'uacf7_schedule_weekly_submission_report' ] );
		add_action( 'uacf7_weekly_submission_report_event', [ $this, 'uacf7_weekly_submission_report' ] );
        
        // Hook the daily email summary function to the scheduled event
		add_action( 'init', [ $this, 'uacf7_schedule_daily_email_summary' ] );
		add_action( 'uacf7_daily_email_summary_event', [ $this, 'uacf7_daily_email_summary' ] );

		add_filter( 'uacf7_settings_options', [ $this, 'uacf7_settings_options_email_summary' ], 14, 2 );
	}

	private function uacf7_cf7_form_title_by_id( $form_id ) {
		// Get the form post object by ID
		$form_post = get_post( $form_id );

		// Check if the post exists and is of type 'wpcf7_contact_form'
		if ( $form_post && $form_post->post_type === 'wpcf7_contact_form' ) {
			return $form_post->post_title; // Return the form title
		}

		return null; // Return null if form not found or invalid post type
	}

	private function uacf7_settings( $option = '' ) {

		$value = get_option( 'uacf7_settings' );

		if ( empty( $option ) ) {
			return $value;
		}

		if ( isset( $value[ $option ] ) ) {
			return $value[ $option ];
		} else {
			return false;
		}
	}

    // Add a custom cron schedule for weekly email summary
    public function uacf7_custom_cron_schedules( $schedules ) {
		if ( ! isset( $schedules['weekly'] ) ) {
			$schedules['weekly'] = array(
				'interval' => WEEK_IN_SECONDS, // 604800 seconds
				'display' => __( 'Once Weekly' ),
			);
		}
		return $schedules;
	}

    // Schedule the Weekly email summary
	public function uacf7_schedule_weekly_submission_report() {
		if ( ! wp_next_scheduled( 'uacf7_weekly_submission_report_event' ) ) {
            // Get the WordPress timezone setting
            $wp_timezone = wp_timezone();
            // Create a DateTime object for next Sunday 11:59 PM in WP timezone
            $date = new DateTime('next Sunday 23:59:00', $wp_timezone);
            $timestamp = $date->getTimestamp();

			wp_schedule_event( $timestamp, 'weekly', 'uacf7_weekly_submission_report_event' );
		}
	}

	// Schedule the daily email summary
	public function uacf7_schedule_daily_email_summary() {
		if ( ! wp_next_scheduled( 'uacf7_daily_email_summary_event' ) ) {
            $wp_timezone = wp_timezone();
			// Create a DateTime object for today 11:59 PM in WP timezone
            $date = new DateTime('today 23:59:00', $wp_timezone);
            // Convert it to UTC timestamp
            $timestamp = $date->getTimestamp();
            // Schedule the event for today at 11:59 PM
			wp_schedule_event( $timestamp, 'daily', 'uacf7_daily_email_summary_event' );
		}
	}

    private function uacf7wep_email_template($results, $reportType) {

        // Site info 
		$site_name = get_bloginfo( 'name' );
		$site_url = get_bloginfo( 'url' );

        // Generate the table content for the email
		$tableRows = '';
		// Output the results
		if ( ! empty( $results ) ) {
			$output = 'Form submission summary for today:<br>';
			$output .= '<table border="1"><tr><th>Form ID</th><th>Submission Date</th><th>Submission Count</th></tr>';
			foreach ( $results as $row ) {
				$formTitle = esc_html( $this->uacf7_cf7_form_title_by_id( $row->form_id ) );
				$submissionCount = esc_html( $row->submission_count );

				$tableRows .= "
                    <tr>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$formTitle}</td>
                        <td style='border: 1px solid #ddd; padding: 8px;'>{$submissionCount}</td>
                    </tr>
                ";
			}

		} else {
			$tableRows = "
                <tr>
                    <td colspan='3' style='border: 1px solid #ddd; padding: 8px; text-align: center;'>No submissions found for this {$reportType}.</td>
                </tr>
            ";
		}

		// Email template
		$emailBody = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f7f7f7;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border-radius: 10px;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    }
                    .header {
                        background-color: #6200ea;
                        color: #ffffff;
                        text-align: center;
                        padding: 20px 40px;
                        border-top-left-radius: 10px;
                        border-top-right-radius: 10px;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 20px;
                    }
                    .body {
                        padding: 20px 40px;
                    }
                    .body h2 {
                        color: #333333;
                        font-size: 18px;
                        margin-bottom: 10px;
                    }
                    .body table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .body th, .body td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    .body th {
                        background-color: #f2f2f2;
                        color: #333333;
                    }
                    .footer {
                        text-align: center;
                        padding: 20px 40px;
                        font-size: 12px;
                        color: #999999;
                    }
                    .footer a {
                        color: #6200ea;
                        text-decoration: none;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>{$reportType} Submission Report</h1>
                    </div>
                    <div class='body'>
                        <h2>Hello There,</h2>
                        <p>Here's the summary of your form submissions:</p>
                        <table>
                            <tr>
                                <th>Form Name</th>
                                <th>Entries</th>
                            </tr>
                            {$tableRows}
                        </table>
                    </div>
                    <div class='footer'>
                        This email was generated from Ultimate Addons CF7 at <a href='{$site_url}'>{$site_name}</a>.
                    </div>
                </div>
            </body>
            </html>
        ";

        return $emailBody;
    }

	public function uacf7_weekly_submission_report() {
		global $wpdb;
		$uacf7wep_settings = $this->uacf7_settings( 'uacf7_enable_database_field' );
        $uacf7wep_option = !empty($this->uacf7_settings( 'uacf7wep_submission_report' )) ? $this->uacf7_settings( 'uacf7wep_submission_report' ) : 0;

        // Get the settings
		$uacf7wep_report_type = !empty($this->uacf7_settings( 'uacf7wep_submission_report_type' )) ? $this->uacf7_settings( 'uacf7wep_submission_report_type' ) : 'weekly';
		$uacf7wep_report_sent_to = !empty($this->uacf7_settings( 'uacf7wep_submission_sent_to' )) ? $this->uacf7_settings( 'uacf7wep_submission_sent_to' ) : 'admin';
        $subject = !empty($this->uacf7_settings( 'uacf7wep_submission_sent_to_subject' )) ? $this->uacf7_settings( 'uacf7wep_submission_sent_to_subject' ) : 'Weekly Submission Report';

        // Site Info 
        $site_admin_email = get_bloginfo( 'admin_email' );

		if ( ! $uacf7wep_settings ) {
			return;
		}

        if( ! $uacf7wep_option ){
            return;
        }

        if( $uacf7wep_report_sent_to == 'custom' ) {
            $to = !empty($this->uacf7_settings( 'uacf7wep_submission_sent_to_custom' )) ? $this->uacf7_settings( 'uacf7wep_submission_sent_to_custom' ) : $site_admin_email;
        } else {
            $to = $site_admin_email;
        }

        // Get the current date and time in WordPress's timezone
        $today = current_time('mysql'); // Provides date and time in Y-m-d H:i:s format
        $today_date = date('Y-m-d', strtotime($today));

		// Calculate the week number based on WordPress timezone
        $weekly = $wpdb->prepare("
            SELECT 
                form_id,
                YEARWEEK(form_date, 1) AS week_number, 
                COUNT(*) AS submission_count
            FROM 
                {$wpdb->prefix}uacf7_form
            WHERE 
                YEARWEEK(form_date, 1) = YEARWEEK(%s, 1)
            GROUP BY 
                form_id, 
                YEARWEEK(form_date, 1)
            ORDER BY 
                form_id, week_number;
        ", $today_date);

		$weeklyResults = $wpdb->get_results( $weekly );
        $emailBody = $this->uacf7wep_email_template($weeklyResults, 'Weekly');

        if( $uacf7wep_report_type == 'weekly' ) {
            // Send the email
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
            );
            wp_mail( $to, $subject, $emailBody, $headers );
        }
	}

	public function uacf7_daily_email_summary() {
		global $wpdb;
		$uacf7db_settings = $this->uacf7_settings( 'uacf7_enable_database_field' );
		$uacf7wep_option = !empty($this->uacf7_settings( 'uacf7wep_submission_report' )) ? $this->uacf7_settings( 'uacf7wep_submission_report' ) : 0;

        // Get the settings
		$uacf7wep_report_type = !empty($this->uacf7_settings( 'uacf7wep_submission_report_type' )) ? $this->uacf7_settings( 'uacf7wep_submission_report_type' ) : 'weekly';
		$uacf7wep_report_sent_to = !empty($this->uacf7_settings( 'uacf7wep_submission_sent_to' )) ? $this->uacf7_settings( 'uacf7wep_submission_sent_to' ) : 'admin';
        $subject = !empty($this->uacf7_settings( 'uacf7wep_submission_sent_to_subject' )) ? $this->uacf7_settings( 'uacf7wep_submission_sent_to_subject' ) : 'Daily Submission Report';
        
        // Site info 
        $site_admin_email = get_bloginfo( 'admin_email' );

        if ( ! $uacf7db_settings ) {
			return;
		}

        if ( ! $uacf7wep_option ) {
			return;
		}

        if($uacf7wep_report_sent_to == 'custom') {
            $to = !empty($this->uacf7_settings( 'uacf7wep_submission_sent_to_custom' )) ? $this->uacf7_settings( 'uacf7wep_submission_sent_to_custom' ) : $site_admin_email;
        } else {
            $to = $site_admin_email;
        }

        // Get today's date in WordPress's timezone
        $today = current_time('Y-m-d');
        $dailyquery = $wpdb->prepare("
            SELECT 
                form_id, 
                DATE(form_date) AS submission_date, 
                COUNT(*) AS submission_count
            FROM 
                {$wpdb->prefix}uacf7_form
            WHERE 
                DATE(form_date) = %s
            GROUP BY 
                form_id, DATE(form_date)
            ORDER BY 
                form_id, submission_date;
        ", $today);

		$dailyResults = $wpdb->get_results( $dailyquery );
        $emailBody = $this->uacf7wep_email_template($dailyResults, 'Daily');

        if($uacf7wep_report_type == 'daily') {
            // Send the email
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
            );
            wp_mail( $to, $subject, $emailBody, $headers );
        }
	}

	public function uacf7_settings_options_email_summary( $value ) {
		$options = array(
            'uacf7dp_email_piping_menu' => array(
				'title'     => __( 'Email Configuration', 'ultimate-addons-cf7' ),
				'icon'      => 'fa-solid fa-envelope-circle-check',
				'fields'    => array(
				),
			),

			'uacf7_enable_email_summary' => array(
				'title' => __( 'Weekly Email Summary', 'ultimate-addons-cf7' ),
                'id' => 'uacf7_enable_email_summary',
				'icon' => 'fa-solid fa-envelope-open-text',
				'parent' => 'uacf7dp_email_piping_menu',
				'fields' => array(
                    array(
                        'id'        => 'uacf7wep_email_summary_official_docs',
                        'type'      => 'notice',
                        'style'     => 'success',
                        'title'     => __( 'Weekly Email Summary Settings', 'ultimate-addons-cf7' ),
                        'content'   => __( 'Receive detailed daily or weekly reports on form entries. <a href="https://cf7addons.com/preview/weekly-email-summary/" target="_blank" class="tf-small-btn"><strong>' . __( 'View live demo', 'ultimate-addons-cf7' ) . '</strong></a><br><strong>Note:</strong> Email Summaries require the <a href="' . get_admin_url() .'/admin.php?page=uacf7_addons" target="_blank" class="tf-small-btn"><strong>' . __( 'Database Addon', 'ultimate-addons-cf7' ) . '</strong></a>  to be active to function. Ensure it is enabled.', 'ultimate-addons-cf7' ),
                    ),

					'uacf7wep_submission_report' => array(
						'id' => 'uacf7wep_submission_report',
						'type' => 'switch',
						'label' => __( 'Enable Weekly Email Summary', 'ultimate-addons-cf7' ),
						'default' => 0,
					),

					'uacf7wep_submission_report_type' => array(
						'id' => 'uacf7wep_submission_report_type',
						'type' => 'select',
						'label' => __( 'Frequency', 'ultimate-addons-cf7' ),
						'options' => array(
							'daily' => 'Daily',
							'weekly' => 'Weekly',
						),
						'default' => 'weekly',
						'field_width' => 50,
                        'dependency' => array(
							'uacf7wep_submission_report', '==', 1,
						),
					),

					'uacf7wep_submission_sent_to' => array(
						'id' => 'uacf7wep_submission_sent_to',
						'type' => 'select',
						'label' => __( 'Mail Send To', 'ultimate-addons-cf7' ),
						'options' => array(
							'admin' => 'Site Admin',
							'custom' => 'Custom',
						),
						'inline' => true,
						'default' => 'admin',
						'field_width' => 50,
                        'dependency' => array(
							'uacf7wep_submission_report', '==', 1,
						),
					),

					'uacf7wep_submission_sent_to_custom' => array(
						'id' => 'uacf7wep_submission_sent_to_custom',
						'type' => 'text',
						'label' => __( 'Mail Address', 'ultimate-addons-cf7' ),
                        'placeholder' => __( 'Site@mail.com', 'ultimate-addons-cf7' ),
						'dependency' => array(
							['uacf7wep_submission_sent_to', '==', 'custom'],
                            ['uacf7wep_submission_report', '==', 1,]
                        ),
					),

                    'uacf7wep_submission_sent_to_subject' => array(
						'id' => 'uacf7wep_submission_sent_to_subject',
						'type' => 'text',
						'label' => __( 'Subject Line', 'ultimate-addons-cf7' ),
                        'placeholder' => __( 'Weekly Submission Report', 'ultimate-addons-cf7' ),
                        'dependency' => array(
							'uacf7wep_submission_report', '==', 1,
						),
					),
				),
			),
		);

		$value = array_merge( $value, $options );

		return $value;
	}

}

new UACF7_Weekly_Email_Summary();