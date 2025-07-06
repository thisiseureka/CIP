<?php

/** initialise the Database pro class
 * Database Pro prefix $uacf7dp
 * @author M Hemel hasan
 * @return database Mail Piping
 */

class UACF7DP_Settings {
    public function __construct() {
        add_filter( 'uacf7_settings_options', [$this,'uacf7dp_settings_options_email_setting'], 16, 2 );

        // Email Piping Settings option load
		add_action( 'uacf7dp_email_piping_tap_after_tab_content', [ $this, 'uacf7dp_test_connection_button' ]);
    }

    public function uacf7dp_settings_options_email_setting($value){
        $uacf7dp_email_pipgs = array(
            'uacf7dp_email_piping_menu' => array(
				'title'     => __( 'Email Configuration', 'ultimate-addons-cf7' ),
				'icon'      => 'fa-solid fa-envelope-circle-check',
				'fields'    => array(
				),
			),
            
            'uacf7dp_email_piping_setting' => array(
                'id'        => 'uacf7dp_email_piping_setting',
                'parent'    => 'uacf7dp_email_piping_menu',
                'title'     => __('Email Piping Settings', 'ultimate-addons-cf7'),
                'icon'      => 'fa-solid fa-envelopes-bulk',
                'fields'    => array(
                    array(
                        'id'        => 'uacf7dp_imap_settings_official_docs',
                        'type'      => 'notice',
                        'style'     => 'success',
                        'content'   => __( 'Anything confusing? Refer to the official documentation for detailed instructions on configuring your IMAP server / Gmail Api with UACF7 Database Pro', 'ultimate-addons-cf7' ) . ' <a href="https://themefic.com/docs/uacf7/pro-addons/email-piping/" target="_blank" class="tf-small-btn"><strong>' . __( 'Read Documentation', 'ultimate-addons-cf7' ) . '</strong></a>',
                    ),

                    array(
                        'id'        => 'uacf7dp_email_piping_tap',
                        'type'      => 'tab',
                        'label'     => esc_html__( 'Email Piping Settings', 'ultimate-addons-cf7' ),
                        'tabs'      => array(
                            array(
                                'id'     => 'uacf7dp_ep_connections',
                                'title'  => __( 'Connection Settings', 'ultimate-addons-cf7' ),
                                'icon'   => 'fa fa-gear',
                                'fields' => array(
                                    array(
                                        'id'            => 'uacf7dp_connection_type',
                                        'type'          => 'select',
                                        'label'         => __( 'Email Connection Type', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Select the type of email connection. Choose between Gmail or IMAP based on your email provider', 'ultimate-addons-cf7' ),
                                        'options'       => array(
                                            'imap'      => __( 'IMAP', 'ultimate-addons-cf7' ),
                                            'gmail'     => __( 'Gmail', 'ultimate-addons-cf7' ),
                                        ),
                                        'default'       => 'admin',
                                    ),

                                    // IMAP Settings
                                    array(
                                        'id'            => 'uacf7dp_imap_email_address',
                                        'type'          => 'text',
                                        'label'         => __( 'Email Address', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the email address that you want to connect to the IMAP server for incoming mail tracking', 'ultimate-addons-cf7' ),
                                        'placeholder'   => __( 'Enter the email address', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'imap' ),
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_imap_email_password',
                                        'type'          => 'password',
                                        'label'         => __( 'Password', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the password for your IMAP email address to authenticate and connect to your IMAP server', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'placeholder'   => __( 'Enter the password', 'ultimate-addons-cf7' ),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'imap' ),
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_imap_email_server',
                                        'type'          => 'text',
                                        'label'         => __( 'Incoming Mail Server', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the IMAP server address that your email provider uses for receiving emails. This server will be used to track and manage incoming messages from your IMAP account.', 'ultimate-addons-cf7' ),
                                        'placeholder'   => __( 'Mail Server Address', 'ultimate-addons-cf7' ),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'imap' ),
                                        ),
                                    ),

                                    array(
                                        'id'            => 'uacf7dp_imp_connection_type',
                                        'type'          => 'select',
                                        'label'         => __( 'Connection Type', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Select the connection type (SSL, TLS, or None) for securing the communication between your WordPress site and the IMAP server.', 'ultimate-addons-cf7' ),
                                        'options'       => array(
                                            'ssl'       => __( 'SSL', 'ultimate-addons-cf7' ),
                                            'tls'       => __( 'TLS', 'ultimate-addons-cf7' ),
                                            'none'      => __( 'None', 'ultimate-addons-cf7' ),
                                        ),
                                        'field_width'   => 50,
                                        'default'       => 'ssl',
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'imap' ),
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_imp_connection_port',
                                        'type'          => 'number',
                                        'label'         => __( 'Connection Port', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the port number used by your IMAP server for incoming connections. Typically, port 993 is used for IMAP with SSL, and port 143 is used for IMAP without encryption', 'ultimate-addons-cf7' ),
                                        'default'       => '993',
                                        'field_width'   => 50,
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'imap' ),
                                        ),
                                    ),

                                    // Gmail Settings
                                    array(
                                        'id'            => 'uacf7dp_gmail_address',
                                        'type'          => 'text',
                                        'label'         => __( 'Gmail Address', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the Gmail address that the plugin will use to track incoming emails', 'ultimate-addons-cf7' ),
                                        'placeholder'   => __( 'Enter the gmail address', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'gmail' ),
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_gmail_client',
                                        'type'          => 'text',
                                        'label'         => __( 'Client ID', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the Client ID from Google Cloud to authenticate your Gmail and enable secure email access', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'placeholder'   => __( 'Enter Client ID', 'ultimate-addons-cf7' ),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'gmail' ),
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_gmail_client_secret',
                                        'type'          => 'text',
                                        'label'         => __( 'Gmail Client Secret Key', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter the Client Secret Key associated with your Gmail API credentials. This key works with the Client ID to securely authenticate your Gmail account and allow the plugin to access and track incoming emails', 'ultimate-addons-cf7' ),
                                        'placeholder'   => __( 'Enter the secrete key', 'ultimate-addons-cf7' ),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'gmail' ),
                                        ),
                                    ),
                                    array(
                                        'id'            => 'uacf7dp_gmail_auth_origin',
                                        'type'          => 'text',
                                        'label'         => __( 'Authorized Javascript Origin', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Copy this URL and paste it into the "Authorized JavaScript Origin" field in your Google Cloud Console to complete the OAuth setup for Gmail API integration', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'value'         => site_url(),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'gmail' ),
                                        ),
                                        'attributes'    => array(
                                            'readonly'  => 'readonly',
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_gmail_redirect_url',
                                        'type'          => 'text',
                                        'label'         => __( 'Authorized Redirect URI', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Copy this URL and paste it into the "Authorized Redirect URI" field in your Google Cloud Console. This URI is used to redirect users after authentication during the OAuth process', 'ultimate-addons-cf7' ),
                                        'value'         => esc_url( admin_url('admin.php') ),
                                        'field_width'   => 50,
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'gmail' ),
                                        ),
                                        'attributes'    => array(
                                            'readonly'  => 'readonly',
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_micro_client',
                                        'type'          => 'text',
                                        'label'         => __( 'Client ID', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'placeholder'   => __( 'Enter Client ID', 'ultimate-addons-cf7' ),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'micro' ),
                                        ),
                                    ),
                                    
                                    array(
                                        'id'            => 'uacf7dp_micro_client_secret',
                                        'type'          => 'text',
                                        'label'         => __( 'Client Secret', 'ultimate-addons-cf7' ),
                                        'field_width'   => 50,
                                        'placeholder'   => __( 'Enter Client Secret', 'ultimate-addons-cf7' ),
                                        'dependency'    => array(
                                            array( 'uacf7dp_connection_type', '==', 'micro' ),
                                        ),
                                    ),
                                )
                            ),
                            array(
                                'id'     => 'uacf7dp_ep_general_settings',
                                'title'  => __( 'General Settings', 'ultimate-addons-cf7' ),
                                'icon'   => 'fa fa-gear',
                                'fields' => array(
                                    array(
                                        'id'            => 'uacf7dp_email_body_type',
                                        'type'          => 'select',
                                        'label'         => __( 'Email Body Preference', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Choose whether to retrieve emails in HTML or plain text format. HTML provides rich formatting, while plain text ensures simplicity and faster processing', 'ultimate-addons-cf7' ),
                                        'options'       => array(
                                                'html'  => __( 'HTML', 'ultimate-addons-cf7' ),
                                                'text'  => __( 'Plain Text', 'ultimate-addons-cf7' ),
                                            ),
                                        'default'       => 'text',
                                    ),
                                    array(
                                        'id'            => 'uacf7dp_time_frequency',
                                        'type'          => 'number',
                                        'label'         => __( 'Time Frequency (In Minute)', 'ultimate-addons-cf7' ),
                                        'subtitle'      => __( 'Enter how often the system should check for new emails. The frequency determines how regularly the plugin will connect to the mail server to retrieve and process incoming messages.', 'ultimate-addons-cf7' ),
                                        'default'       => 5,
                                        'attributes'    => array(
                                            'max'       => 59,
                                        ),
                                    ),
                                )
                            )
                        )
                    )
                )
            )
        );

        $value = array_merge( $value, $uacf7dp_email_pipgs );

        return $value;
    }

    public function uacf7dp_test_connection_button() {
        $connection_type = !empty( uacf7_settings("uacf7dp_email_piping_tap")["uacf7dp_connection_type"] ) ? uacf7_settings("uacf7dp_email_piping_tap")["uacf7dp_connection_type"] : 'imap';

        if( $connection_type == 'imap' ) {
            $connection_data = get_option('uacf7db_ep_imap_is_active');
        } else if( $connection_type == 'gmail' ) {
            $connection_data = get_option('uacf7dp_mp_gmail_connection_data');
        }

        $connection_status = !empty($connection_data) ? (!empty($connection_data["is-active"]) && $connection_data["is-active"] == 1 ? 'connection-success' : 'connection-failed') : '';

        if( !empty( uacf7_settings("uacf7dp_email_piping_tap")["uacf7dp_gmail_address"]) || !empty( uacf7_settings("uacf7dp_email_piping_tap")["uacf7dp_imap_email_address"]) ) {
        ?>
            <div class="uacf7dp-connection-setting-footer">
                <div class="uacf7dp-test-connection-status">
                    <a class="uacf7dp-connection-check-btn" id="uacf7dp-test-connection"><?php _e('Test Connection', 'ultimate-addons-cf7') ?></a>
                    <div class="uacf7dp-connection-result <?php echo $connection_status ?>"><?php echo !empty($connection_data["connection"]) ? esc_html($connection_data["connection"]) : esc_html__("Not Connected") ?></div>
                </div>
            </div>
            
        <?php
        }
    }

}

new UACF7DP_Settings();
