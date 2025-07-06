<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * form save and continue later
 * @author Jewel Hossain
 * @since 1.8.0
 */

class UACF7_Save_And_Continue {
    
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'uacf7_save_data';

        /*
		 * Creating tables after active the plugin or active the addon
		 */
		add_action( 'admin_init', array( $this, 'uacf7_register_activation' ), 11, 2 );

        add_action( 'init', array($this , 'uacf7_save_and_continue_init') );
        
        add_filter( 'uacf7_post_meta_options', array($this , 'uacf7_post_meta_options_form_save_and_continue'), 16, 2 );
        
        add_action('uacf7_cleanup_cron', [$this, 'delete_expired_entries']);

        // Schedule cleanup task
        if (!wp_next_scheduled('uacf7_cleanup_cron')) {
            wp_schedule_event(time(), 'daily', 'uacf7_cleanup_cron');
        }
    }

    public function uacf7_save_and_continue_init(){
    
        load_plugin_textdomain( 'ultimate-form-save-and-continue', false, basename( dirname( __FILE__ ) ) . '/languages' ); 

        add_action( 'wp_enqueue_scripts', array($this , 'uacf7_save_and_continue_scripts') );
         
        add_filter( 'wpcf7_form_elements', array($this , 'add_save_button_to_cf7_form') );
        add_filter( 'wpcf7_form_elements', array($this , 'add_confirm_message') );
        
        add_filter('wpcf7_form_elements', array($this, 'prefill_form_with_saved_data'), 20 );
        add_filter('wpcf7_form_elements', array($this, 'prefill_form_with_saved_data_repeater'), 9 );
        add_action('wp_ajax_save_form_data', [$this, 'save_form_data']);
        add_action('wp_ajax_nopriv_save_form_data', [$this, 'save_form_data']);

        // Hooks and actions
        add_action('wp_ajax_uacf7_send_resume_email', [$this, 'send_resume_link']);
        add_action('wp_ajax_nopriv_uacf7_send_resume_email', [$this, 'send_resume_link']);
        
    }

    /*
    * enqueue scripts
    */
    public function uacf7_save_and_continue_scripts() {

        wp_enqueue_style( 'uacf7-form-save-and-continue', plugin_dir_url( __FILE__ ) . 'assets/save-and-continue.css' );
        wp_enqueue_style( 'uacf7-save-and-waitme', plugin_dir_url( __FILE__ ) . 'assets/waitMe.min.css' );
        wp_enqueue_script( 'uacf7-form-save-and-continue', plugin_dir_url( __FILE__ ) . 'assets/save-and-continue.js', array('jquery'), null, true );
        wp_enqueue_script( 'uacf7-save-and-waitme', plugin_dir_url( __FILE__ ) . 'assets/waitMe.min.js', array('jquery'), null, true );

        wp_localize_script( 'uacf7-form-save-and-continue', 'saveAndContinue', [ 
            "ajaxurl" => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'uacf7-form-save-and-continue-nonce' ),
        ] );

        wp_localize_script('uacf7-form-save-and-continue', 'cf7SaveAndResume', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('send_resume_link_nonce'),
        ]);
        
    }

    public function add_save_button_to_cf7_form( $form ) {

        $contact_form = WPCF7_ContactForm::get_current();
    
        $form_id = $contact_form->id(); 
        
        $uacf7_sac_settings = uacf7_get_form_option( $form_id, 'form_save_and_continue' );
        $uacf7_sac_enable = isset( $uacf7_sac_settings['uacf7_enable_form_save_and_continue'] ) ? $uacf7_sac_settings['uacf7_enable_form_save_and_continue'] : '';
        $uacf7_sac_link_text  = isset( $uacf7_sac_settings['uacf7_save_and_continue_link_text'] ) && !empty($uacf7_sac_settings['uacf7_save_and_continue_link_text']) ? $uacf7_sac_settings['uacf7_save_and_continue_link_text'] : __('Save and Continue Later', 'ultimate-addons-cf7-pro');
        $uacf7_confirmation_message = isset( $uacf7_sac_settings['uacf7_save_and_continue_confirmation_message'] ) ? $uacf7_sac_settings['uacf7_save_and_continue_confirmation_message'] : '';

        if($uacf7_sac_enable){
            $form = preg_replace('/(<input[^>]+type="submit"[^>]+>)/', '$0 <a href="" class="uacf7-save-and-continue-btn">'. $uacf7_sac_link_text .'</a>', $form);
        }
    
        return $form;
    }

    public function add_confirm_message($form){

        $contact_form = WPCF7_ContactForm::get_current();
    
        if (!$contact_form) {
            return $form;
        }

        $form_id = $contact_form->id(); 
        
        $uacf7_sac_settings = uacf7_get_form_option( $form_id, 'form_save_and_continue' );

        $uacf7_sac_settings                       = uacf7_get_form_option( $form_id, 'form_save_and_continue' );
        $uacf7_sac_enable                         = isset( $uacf7_sac_settings['uacf7_enable_form_save_and_continue'] ) ? $uacf7_sac_settings['uacf7_enable_form_save_and_continue'] : '';
        $uacf7_form_save_and_continue_enable_link = isset( $uacf7_sac_settings['uacf7_form_save_and_continue_enable_link'] ) ? $uacf7_sac_settings['uacf7_form_save_and_continue_enable_link'] : '';
        $uacf7_sac_enable_email_notification      = isset( $uacf7_sac_settings['uacf7_form_save_and_continue_enable_email_notification'] ) ? $uacf7_sac_settings['uacf7_form_save_and_continue_enable_email_notification'] : '';
        
        if ($uacf7_sac_enable) {
            $uacf7_confirmation_message = isset($uacf7_sac_settings['uacf7_save_and_continue_confirmation_message']) ? $uacf7_sac_settings['uacf7_save_and_continue_confirmation_message'] : 'Your progress has been saved.';
    
            $resume_url_container = '';
            $save_email_form = '';

            // Conditionally add resume-url-container div
            if ($uacf7_form_save_and_continue_enable_link) {
                $resume_url_container = '
                    <div class="resume-url-container">
                        <label for="resume-url">Copy Link</label>
                        <input id="resume-url" type="text" class="resume-url" value="" readonly>
                        <span class="copy-resume-url-btn"><img src="' . plugin_dir_url( __FILE__ ) . 'assets/copy-icon.svg' . '"></span>
                    </div>';
            }

            // Conditionally add uacf7-save-email-form div
            if ($uacf7_sac_enable_email_notification) {
                $save_email_form = '
                    <div class="uacf7-save-email-form">
                        <label for="resume_email">' . __('Email', 'ultimate-addons-cf7-pro') . ' *</label>
                        <input id="resume_email" type="email" name="resume_email" placeholder="' . __('Enter your email', 'ultimate-addons-cf7-pro') . '" required>
                        <span class="email-error-message" style="color: red; display: none;">' . __('Please provide a valid email.', 'ultimate-addons-cf7-pro') . '</span>
                        <a href="" class="uacf7-submit-email disabled">' . __('Send Link', 'ultimate-addons-cf7-pro') . '</a>
                        <p class="thank-you-message" style="display: none;">' . __('Thank you! The link has been sent to your email.', 'ultimate-addons-cf7-pro') . '</p>
                    </div>';
            }

            // Combine everything into a confirmation div
            $confirmation_div = '
                <div class="uacf7-save-confirmation" style="display: none;" data-form-id="' . esc_attr($form_id) . '">
                    <div class="message">
                        ' . nl2br($uacf7_confirmation_message) . '
                        ' . $resume_url_container . '
                        ' . $save_email_form . '
                    </div>
                </div>';

            $form .= $confirmation_div;
        }
    
        return $form;

    }

    public function uacf7_register_activation() {
        
		if ( ! $this->uacf7_check_tables_existence() ) {
			$this->create_save_data_table();
		}

		//Creating tables when plugin is active
		register_activation_hook( UACF7_PRO_FILE, [ $this, 'create_save_data_table' ] );

	}

    /**
	 * It's check if table are create or not 
	 * @return bool
	 */
	public function uacf7_check_tables_existence() {
		global $wpdb;

		$uacf7_table = $this->table_name;

		// Check if tables exist
		$table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$uacf7_table'" ) == $uacf7_table;

		return $table_exists;
	}

    function create_save_data_table() {
        global $wpdb;
    
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            form_data LONGTEXT NOT NULL,
            user_token varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY user_token (user_token)
        ) $charset_collate;";
    
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function uacf7_save_form_data( $contact_form ) {
        if ( isset( $_POST['save_and_continue'] ) ) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'uacf7_save_data';
    
            $form_data = array_map('sanitize_text_field', $_POST);
            $user_token = $_POST['save_and_continue_token'] ?? md5(uniqid(rand(), true));
    
            $existing_entry = $wpdb->get_var(
                $wpdb->prepare("SELECT id FROM $table_name WHERE user_token = %s", $user_token)
            );
    
            if ( $existing_entry ) {
                $wpdb->update(
                    $table_name,
                    [ 'form_data' => json_encode($form_data) ],
                    [ 'user_token' => $user_token ]
                );
            } else {
                $wpdb->insert(
                    $table_name,
                    [
                        'form_data' => json_encode($form_data),
                        'user_token' => $user_token,
                    ]
                );
            }
    
            $resume_link = add_query_arg('token', $user_token, get_permalink());
            wp_send_json_success([
                'resume_link' => $resume_link,
            ]);
            exit;
        }
    }

    /**
     * Handle AJAX request to save form data.
    */
    public function save_form_data() {
        
        if (
            !isset($_POST['nonce']) ||
            !wp_verify_nonce($_POST['nonce'], 'uacf7-form-save-and-continue-nonce')
        ) {
            wp_send_json_error(['message' => __('Invalid nonce', 'ultimate-addons-cf7-pro')], 403);
            return;
        }

        // Get the form data
        $form_data = isset($_POST['form_data']) ? json_decode(stripslashes($_POST['form_data']), true) : null;
        if (empty($form_data) || !is_array($form_data)) {
            wp_send_json_error(['message' => __('Invalid form data', 'ultimate-addons-cf7-pro')], 400);
        }

        global $wpdb;

        $user_token = bin2hex(random_bytes(16));

        $wpdb->insert($this->table_name, [
            'user_token' => $user_token,
            'form_data'  => json_encode($form_data),
            'created_at' => current_time('mysql')
        ]);

        if ($wpdb->last_error) {
            wp_send_json_error(['message' => __( 'Database error: ', 'ultimate-addons-cf7-pro' ) . $wpdb->last_error], 500);
            return;
        }
        $current_url = wp_get_referer();

        $resume_link = add_query_arg('resume_entry', $user_token, $current_url);

        wp_send_json_success([
            'resume_link' => $resume_link,
        ]);

        wp_die();
    }
    
    /**
     * Handle AJAX request to send the resume link via email.
     */
    public function send_resume_link() {
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'send_resume_link_nonce')) {
            wp_send_json_error(['message' => __('Nonce verification failed.', 'ultimate-addons-cf7-pro')], 403);
        }
    
        $email       = sanitize_email($_POST['email']);
        $resume_link = esc_url_raw($_POST['resume_link']);
    
        $form_id      = isset($_POST['form_id']) ? intval($_POST['form_id']) : 0;
        $contact_form = WPCF7_ContactForm::get_instance($form_id);
        $form_name    = isset($contact_form) ? $contact_form->title() : 'Unknown Form';
    
        if (!is_email($email)) {
            wp_send_json_error(['message' => __('Invalid email address.', 'ultimate-addons-cf7-pro')], 400);
        }
        
        $uacf7_sac_settings                  = uacf7_get_form_option($form_id, 'form_save_and_continue');
        $uacf7_sac_enable                    = isset($uacf7_sac_settings['uacf7_enable_form_save_and_continue']) ? $uacf7_sac_settings['uacf7_enable_form_save_and_continue'] : '';
        $uacf7_sac_enable_email_notification = isset($uacf7_sac_settings['uacf7_form_save_and_continue_enable_email_notification']) ? $uacf7_sac_settings['uacf7_form_save_and_continue_enable_email_notification'] : '';
        
        if ($uacf7_sac_enable && $uacf7_sac_enable_email_notification) {
            $uacf7_thanks_message       = isset($uacf7_sac_settings['uacf7_save_and_continue_email_confirmation_message']) ? $uacf7_sac_settings['uacf7_save_and_continue_email_confirmation_message'] : '';
            $uacf7_email_template_message = isset($uacf7_sac_settings['uacf7_save_and_continue_email_notification_message']) ? $uacf7_sac_settings['uacf7_save_and_continue_email_notification_message'] : '';
    
            $uacf7_email_template_message = str_replace('{form_name}', esc_html($form_name), $uacf7_email_template_message);
    
            $uacf7_email_template_message = str_replace(
                '{resume_link}',
                '<a href="' . esc_url($resume_link) . '" style="display: inline-block; background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; border-radius: 5px;" target="_blank">Resume Form</a>',
                $uacf7_email_template_message
            );
    
            // Use nl2br to preserve newlines
            $uacf7_email_template_message = nl2br($uacf7_email_template_message);
    
            // HTML Email Template
            $html_email_template = '
                <!DOCTYPE html>
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; margin: 0; padding: 0; }
                        .email-container { max-width: 600px; margin: 20px auto; background: #ffffff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                        .email-header { font-size: 18px; font-weight: bold; margin-bottom: 20px; }
                        .email-content { font-size: 16px; margin-bottom: 20px; line-height: 1.8; }
                        .email-footer { font-size: 12px; color: #777; margin-top: 20px; text-align: center; }
                        a { color: #4CAF50; text-decoration: none; }
                        a:hover { text-decoration: underline; }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="email-content">
                            ' . $uacf7_email_template_message . '
                        </div>
                        <div class="email-footer">
                           ' . __(' This email was sent by ', 'ultimate-addons-cf7-pro') . ' ' . get_bloginfo('name') . ' ' . __(' If you have any questions, please contact us.', 'ultimate-addons-cf7-pro') .'
                        </div>
                    </div>
                </body>
                </html>
            ';
    
            // Send the email
            $subject = 'Resume Your Form Submission';
            $headers = ['Content-Type: text/html; charset=UTF-8'];
    
            if (wp_mail($email, $subject, $html_email_template, $headers)) {
                wp_send_json_success(['message' => esc_html__( $uacf7_thanks_message, 'ultimate-addons-cf7-pro')]);
            } else {
                wp_send_json_error(['message' => __('Failed to send email. Please try again.', 'ultimate-addons-cf7-pro')]);
            }
        }
    }
    

    /**
     * Prefill the form with saved data based on token.
     */
    public function prefill_form_with_saved_data($content) {
        // error_log("Actual content: " . $content);
        if (isset($_GET['resume_entry'])) {
            $token = sanitize_text_field($_GET['resume_entry']);
            global $wpdb;
    
            $result = $wpdb->get_row($wpdb->prepare(
                "SELECT form_data FROM $this->table_name WHERE user_token = %s",
                $token
            ));

            $form = WPCF7_ContactForm::get_current();

            if ($result) {
                $saved_data = json_decode($result->form_data);
                if(isset($saved_data->_wpcf7) && $saved_data->_wpcf7 == $form->id()){
                    foreach ($saved_data as $key => $value) {
                        // Skip hidden/meta fields
                        if (strpos($key, '_') === 0 || (strpos($key, 'uarepeater-') === 0 && is_object($value))) {
                            continue;
                        }

                        // Handle the uacf7_post_taxonomy field separately
                        if (strpos($key, 'uacf7_post_taxonomy') !== false) {
                            if (is_array($value)) {
                                // Multi-select taxonomy field
                                foreach ($value as $taxonomy_id) {
                                    $escaped_taxonomy_id = esc_attr($taxonomy_id);
                                
                                    // Match the option with the specific value and add "selected"
                                    $content = preg_replace_callback(
                                        '/(<option[^>]*\bvalue="' . preg_quote($escaped_taxonomy_id, '/') . '"[^>]*)>/',
                                        function ($matches) {
                                            if (strpos($matches[1], 'selected') === false) {
                                                return $matches[1] . ' selected="selected">';
                                            }
                                            return $matches[1] . '>';
                                        },
                                        $content
                                    );
                                }
                            }
                            continue; 
                        }
                        // Handle Select2 (multi-select and single-select)
                        if (is_array($value)) {
                            // Multi-select fields
                            foreach ($value as $option) {
                                $escaped_option = esc_attr($option);
                                $content = preg_replace(
                                    '/(<select[^>]*\bname="' . preg_quote($key, '/') . '[^>]*>.*?<option[^>]*\bvalue="' . preg_quote($escaped_option, '/') . '")/',
                                    '${1} selected="selected"',
                                    $content
                                );
                            }
                        } else {
                            // Single-select fields
                            $escaped_value = esc_attr($value);
                            $content = preg_replace_callback(
                                '/(<select[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*>.*?<option[^>]*\bvalue="' . preg_quote($escaped_value, '/') . '")/s',
                                function ($matches) {
                                    if (strpos($matches[1], 'selected="selected"') === false) {
                                        return $matches[1] . ' selected="selected"';
                                    }
                                    return $matches[1];
                                },
                                $content
                            );
                        }

                        // Check for signature field dynamically (signature-related key)
                        if (strpos($key, 'signature') !== false && !empty($value)) {
                            
                            // $content = preg_replace(
                            //     '/(<canvas[^>]*id="' . preg_quote($key, '/') . '"[^>]*>)/',
                            //     '${1}<script>document.getElementById("' . $key . '").toDataURL="' . esc_js($value) . '";</script>',
                            //     $content
                            // );
                            

                        } else if (is_array($value)) {
                            // For checkboxes (array values)
                            foreach ($value as $k => $v) {
                                $escaped_v = esc_attr($v);

                                $content = preg_replace(
                                    '/(<input[^>]*\btype="checkbox"[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*\bvalue="' . preg_quote($escaped_v, '/') . '")/',
                                    '${1} checked="checked"',
                                    $content
                                );

                            }

                        } elseif (preg_match('/^uacf7_range_slider/', $key)) {
                            // For range sliders (both single and double-handle)
                            if (strpos($value, '-') !== false) {
                                // Double-handle range slider
                                $values = explode('-', $value);
                                $min_value = trim($values[0]);
                                $max_value = trim($values[1]);

                                // Update the hidden input field value (min_value - max_value)
                                $escaped_value = esc_attr($value);
                                $content = preg_replace_callback(
                                    '/(<input[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*)>/',
                                    function ($matches) use ($escaped_value) {
                                        if (strpos($matches[1], 'value="') !== false) {
                                            return preg_replace('/\bvalue="[^"]*"/', 'value="' . $escaped_value . '"', $matches[1]) . '>';
                                        } else {
                                            return $matches[1] . ' value="' . $escaped_value . '">';
                                        }
                                    },
                                    $content
                                );

                                // Update the slider handles' positions
                                $content = preg_replace(
                                    '/(<span[^>]*\bclass="ui-slider-handle[^"]*"[^>]*style="left: )[^;]+(;[^>]*><\/span>)/',
                                    '${1}' . esc_attr($min_value) . '%${2}',
                                    $content,
                                    1 // Update the first handle
                                );

                                $content = preg_replace(
                                    '/(<span[^>]*\bclass="ui-slider-handle[^"]*"[^>]*style="left: )[^;]+(;[^>]*><\/span>)/',
                                    '${1}' . esc_attr($max_value) . '%${2}',
                                    $content,
                                    1 // Update the second handle
                                );

                                // Update the displayed value in the span (min_value - max_value)
                                $content = preg_replace(
                                    '/(<span[^>]*\bclass="uacf7-amount"[^>]*>)(.*?)(<\/span>)/',
                                    '${1}' . esc_html($min_value) . ' - ' . esc_html($max_value) . '${3}',
                                    $content
                                );
                            } else {
                                // Single-handle range slider
                                $escaped_value = esc_attr($value);

                                $content = preg_replace_callback(
                                    '/(<input[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*)>/',
                                    function ($matches) use ($escaped_value) {
                                        if (strpos($matches[1], 'value="') !== false) {
                                            return preg_replace('/\bvalue="[^"]*"/', 'value="' . $escaped_value . '"', $matches[1]) . '>';
                                        } else {
                                            return $matches[1] . ' value="' . $escaped_value . '">';
                                        }
                                    },
                                    $content
                                );

                                // Update the displayed value in the span
                                $content = preg_replace(
                                    '/(<span[^>]*\bclass="[^"]*' . preg_quote($key, '/') . '-value[^"]*"[^>]*>)(.*?)(<\/span>)/',
                                    '${1}' . esc_html($value) . '${3}',
                                    $content
                                );
                            }

                        } else {
                            
                            // Sanitize value
                            $escaped_value = esc_attr($value);

                            $content = preg_replace(
                                '/(<input[^>]*\btype="checkbox"[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*\bvalue="' . preg_quote($escaped_value, '/') . '")/',
                                '${1} checked="checked"',
                                $content
                            );

                            $content = preg_replace(
                                '/(<input[^>]*\btype="radio"[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*\bvalue="' . preg_quote($escaped_value, '/') . '")/',
                                '${1} checked="checked"',
                                $content
                            );

                            // Update input fields (replace or add value attribute)
                            $content = preg_replace_callback(
                                '/(<input[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*)>/',
                                function ($matches) use ($escaped_value) {
                                    if (strpos($matches[1], 'value="') !== false) {
                                        return preg_replace('/\bvalue="[^"]*"/', 'value="' . $escaped_value . '"', $matches[1]) . '>';
                                    } else {
                                        return $matches[1] . ' value="' . $escaped_value . '">';
                                    }
                                },
                                $content
                            );

                            // Update textareas (inner content)
                            $content = preg_replace(
                                '/(<textarea[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*>)(.*?)(<\/textarea>)/s',
                                '${1}' . esc_html($value) . '${3}',
                                $content
                            );

                            // Update select fields (set selected option)
                            $content = preg_replace(
                                '/(<select[^>]*\bname="' . preg_quote($key, '/') . '"[^>]*>.*?<option[^>]*\bvalue="' . preg_quote($value, '/') . '")/',
                                '${1} selected="selected"',
                                $content
                            );
                        }

                    }
                }

                // error_log("Updated content: " . $content);
            }
        }
        return $content;
    }

    public function prefill_form_with_saved_data_repeater($content) {
    
        if (isset($_GET['resume_entry'])) {
            $token = sanitize_text_field($_GET['resume_entry']);
            global $wpdb;
    
            $result = $wpdb->get_row($wpdb->prepare(
                "SELECT form_data FROM $this->table_name WHERE user_token = %s",
                $token
            ));
    
            $form = WPCF7_ContactForm::get_current();
    
            if ($result) {
                $saved_data = json_decode($result->form_data, true);
                // uacf7_print_r($saved_data);
    
                if (isset($saved_data['_wpcf7']) && $saved_data['_wpcf7'] == $form->id()) {
                    // Check if repeaters exist
                    if (!empty($saved_data['_uacf7_repeaters'])) {
                        $repeaters = json_decode($saved_data['_uacf7_repeaters']);
    
                        foreach ($repeaters as $repeater_id) {
                            if (isset($saved_data[$repeater_id])) {
                                $repeater_data = json_encode($saved_data[$repeater_id]); 
    
                                // Find and update the corresponding repeater div
                                if (preg_match('/<div class="uacf7_repeater"[^>]*uacf7-repeater-id="' . preg_quote($repeater_id, '/') . '"[^>]*>/', $content)) {
                                    $content = preg_replace(
                                        '/(<div class="uacf7_repeater"[^>]*uacf7-repeater-id="' . preg_quote($repeater_id, '/') . '"[^>]*)(>)/',
                                        '${1} repeater-data=\'' . esc_attr($repeater_data) . '\'${2}',
                                        $content
                                    );
    
                                }
                            }
                        }
                    }
                }
            }
        }
    
        return $content;
    }
    

    /**
    * Delete expired entries from the database (older than 30 days).
    */
    public function delete_expired_entries() {
        global $wpdb;
        $result = $wpdb->query("DELETE FROM {$this->table_name} WHERE created_at < NOW() - INTERVAL 30 DAY");

        if ($result === false) {
            error_log('Error executing DELETE query for expired entries.');
        } else {
            error_log('Successfully deleted expired entries.');
        }
        
    }

    public function uacf7_post_meta_options_form_save_and_continue( $value, $post_id){

        $post_submission = apply_filters('uacf7_post_meta_options_form_save_and_continue_pro', $data = array(
            'title'         => __( 'Form Save & Continue', 'ultimate-addons-cf7' ),
            'icon'          => 'fas fa-save',
            'checked_field' => 'uacf7_enable_form_save_and_continue',
            'fields'        => array( 
                'uacf7_form_save_and_continue_label' => array(
                    'id'       => 'uacf7_form_save_and_continue_label',
                    'type'     => 'heading',
                    'label'    => __( 'Form Save And Continue Later', 'ultimate-addons-cf7' ),
                    'subtitle' => sprintf(
                        __( 'Save your form data before submit and resume later form where you left.  See Demo %1s.', 'ultimate-addons-cf7' ),
                         '<a href="https://cf7addons.com/preview/save-and-contine/" target="_blank" rel="noopener">Example</a>'
                                  )
                      ),
                'uacf7_form_save_and_continue_docs' => array(
                    'id'      => 'uacf7_form_save_and_continue_docs',
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => sprintf( 
                        __( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
                        '<a href="https://themefic.com/docs/uacf7/pro-addons/save-and-continue/" target="_blank" rel="noopener">Form Save And Continue</a>'
                    )
                ),
                'uacf7_enable_form_save_and_continue' => array(
                    'id'          => 'uacf7_enable_form_save_and_continue',
                    'type'        => 'switch',
                    'label'       => __( 'Enable Form Save & Continue', 'ultimate-addons-cf7' ),
                    'label_on'    => __( 'Yes', 'ultimate-addons-cf7' ),
                    'label_off'   => __( 'No', 'ultimate-addons-cf7' ),
                    'default'     => false,
                    'field_width' => 100,
                ),
                'uacf7_save_and_continue_options' => array(
					'id' => 'uacf7_save_and_continue_options',
					'type' => 'heading',
					'label' => __( 'Save & Continue Options', 'ultimate-addons-cf7' ),
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
				),
				'uacf7_save_and_continue_link_text' => array(
					'id' => 'uacf7_save_and_continue_link_text',
					'type' => 'text',
					'label' => __( 'Link Text', 'ultimate-addons-cf7' ),
					'subtitle' => __( "For instance, if you enter 'Save & Resume Later' as the link text, the resulting link text will be named 'Save & Resume Later'.", 'ultimate-addons-cf7' ),
					'placeholder' => __( 'E.g. Save & Resume Later', 'ultimate-addons-cf7' ),
					'field_width' => 100,
                    'default' => __('Save and resume later', 'ultimate-addons-cf7'),
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
				),
                'uacf7_save_and_continue_confirmation_message' => array(
					'id' => 'uacf7_save_and_continue_confirmation_message',
					'label' => __( 'Confirmation Page', 'ultimate-addons-cf7' ),
					'subtitle' => __( 'This message is displayed once the user clicks the save and continue and provides instructions for resuming.', 'ultimate-addons-cf7' ),
					'type' => 'editor',
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
                    'default' => __(
                        'Your form entry has been saved, and a unique link has been generated for you to resume completing this form.<br><br>
                        If you prefer, you can enter your email address to receive the link directly via email.<br><br>
                        Please note: This link should not be shared and will expire in 30 days. Afterwards, your form entry will be deleted.',
                        'ultimate-addons-cf7'
                    ),
				),
                'uacf7_form_save_and_continue_enable_link' => array(
                    'id'          => 'uacf7_form_save_and_continue_enable_link',
                    'type'        => 'switch',
                    'label'       => __( 'Enable Save & Continue Link', 'ultimate-addons-cf7' ),
                    'label_on'    => __( 'Yes', 'ultimate-addons-cf7' ),
                    'label_off'   => __( 'No', 'ultimate-addons-cf7' ),
                    'default'     => True,
                    'field_width' => 100,
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
                ),
                'uacf7_form_save_and_continue_enable_email_notification' => array(
                    'id'          => 'uacf7_form_save_and_continue_enable_email_notification',
                    'type'        => 'switch',
                    'label'       => __( 'Enable Email Notification', 'ultimate-addons-cf7' ),
                    'label_on'    => __( 'Yes', 'ultimate-addons-cf7' ),
                    'label_off'   => __( 'No', 'ultimate-addons-cf7' ),
                    'default'     => true,
                    'field_width' => 100,
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
                ),
                'uacf7_save_and_continue_email_settings' => array(
					'id' => 'uacf7_save_and_continue_email_settings',
					'type' => 'heading',
					'label' => __( 'Email Settings', 'ultimate-addons-cf7' ),
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
				),
                'uacf7_save_and_continue_email_notification_message' => array(
					'id' => 'uacf7_save_and_continue_email_notification_message',
					'label' => __( 'Email Notification Message', 'ultimate-addons-cf7' ),
					'subtitle' => __( 'This is the save and continue email template.', 'ultimate-addons-cf7' ),
					'type' => 'editor',
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
                    'default' => __(
                        "Thank you for starting your form submission with us. {form_name} progress has been saved, and you can resume completing the form at your convenience.<br><br>
                        To continue where you left off, please click the button below:<br>
                        <strong>{resume_link}</strong><br><br>
                        Note: This link is unique to you and should not be shared, and the link will expire in 30 days.",
                        'ultimate-addons-cf7'
                    ),
				),
                'uacf7_save_and_continue_email_notification_form_tags' => array(
                    'id'      => 'uacf7_save_and_continue_email_notification_form_tags',
                    'type'    => 'notice',
                    'style'   => 'success',
                    'content' => sprintf( 
                        __( '<h3>Mail Tags : {form_name} {resume_link}</h3>', 'ultimate-addons-cf7' ),
                        ''
                    ),
					'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
				),
                'uacf7_save_and_continue_email_confirmation_message' => array(
					'id' => 'uacf7_save_and_continue_email_confirmation_message',
					'label' => __( 'Display Confirmation Message ', 'ultimate-addons-cf7' ),
					'subtitle' => __( 'This message is displayed once the user clicks the send link button.', 'ultimate-addons-cf7' ),
					'type' => 'editor',
                    'dependency' => array(
							array( 'uacf7_enable_form_save_and_continue', '==', true ),
						),
                    'default' => __('A link to resume this form has been sent to the email address provided.<br><br>
                    Please remember, the link should not be shared and will expire in 30 days.', 'ultimate-addons-cf7'),
				),
    
            ),
                
    
        ), $post_id);
    
        $value['form_save_and_continue'] = $post_submission; 
        return $value;
    }


}
 
new UACF7_Save_And_Continue(); 


