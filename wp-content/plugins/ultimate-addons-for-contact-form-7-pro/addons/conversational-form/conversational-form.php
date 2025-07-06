<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class UACF7_CONVERSATIONAL_FORM_PRO {

	/**
	 * Instance of this class.
	 * @author   Sydur Rahman
	 * @since    1.0.0
	 *
	 */

	public function __construct() {

		// Enqueue Script
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
		// add_action( 'admin_enqueue_scripts', array($this, 'wp_enqueue_admin_script' ) ); 

		// Tag Generator 
		add_action( 'admin_init', array( $this, 'tag_generator' ) );

		// Conversational Tag Handler
		wpcf7_add_form_tag( 'uacf7_conversational_start', array( $this, 'uacf7_conversational_start_tag_handler' ), true );
		wpcf7_add_form_tag( 'uacf7_conversational_end', array( $this, 'uacf7_conversational_end_tag_handler' ), false );

		// Conversational ajax validation
		add_action( 'wp_ajax_uacf7_conversational_step_validation', array( $this, 'uacf7_conversational_step_validation' ) );
		add_action( 'wp_ajax_nopriv_uacf7_conversational_step_validation', array( $this, 'uacf7_conversational_step_validation' ) );

		// Conversational properties
		add_filter( 'wpcf7_contact_form_properties', array( $this, 'uacf7_properties' ), 10, 2 );


		// Conversational form Thank You message
		add_action( 'wp_ajax_uacf7_conversational_thankyou_message', array( $this, 'uacf7_conversational_thankyou_message' ) );
		add_action( 'wp_ajax_nopriv_uacf7_conversational_thankyou_message', array( $this, 'uacf7_conversational_thankyou_message' ) );

		//  For Generator Ai post submission hook
		add_filter( 'uacf7_conversational_appointment_form_dropdown', array( $this, 'uacf7_conversational_appointment_form_dropdown' ), 10, 2 );
		add_filter( 'uacf7_conversational_interview_form_dropdown', array( $this, 'uacf7_conversational_interview_form_dropdown' ), 10, 2 );

		add_filter( 'uacf7_conversational_form_ai_generator', array( $this, 'uacf7_conversational_form_ai_generator' ), 10, 2 );

		// Post Meta Option
		add_filter( 'uacf7_post_meta_options', array( $this, 'uacf7_post_meta_options_conversational_form' ), 19, 2 );

	}

	function uacf7_post_meta_options_conversational_form( $value, $post_id ) {

		if ( uacf7_settings( 'uacf7_enable_conversational_form' ) != true ) {
			return $value;
		}

		if ( $post_id != 0 ) {
			$ContactForm = WPCF7_ContactForm::get_instance( $post_id );
			$tags = $ContactForm->scan_form_tags( array( 'basetype' => 'uacf7_conversational_start' ) );
			$uacf7_conversational_steps_limit = count( $tags );
		} else {
			$uacf7_conversational_steps_limit = 0;
		}

		$conversational_form = apply_filters( 'uacf7_post_meta_options_conversational_form_pro', $data = array(
			'title' => __( 'Conversational Form', 'ultimate-addons-cf7' ),
			'icon' => 'fa-solid fa-message',
			'checked_field' => 'uacf7_is_conversational',
			'fields' => array(

				'uacf7_conversational_form' => array(
					'id' => 'uacf7_conversational_form',
					'type' => 'heading',
					'label' => __( 'Conversational Form Settings', 'ultimate-addons-cf7' ),
					'subtitle' => sprintf(
						__( 'Create interactive, engaging forms that mimic a conversational experience.  See Demo %1s.', 'ultimate-addons-cf7' ),
						'<a href="https://cf7addons.com/preview/conversational-form-for-contact-form-7/" target="_blank" rel="noopener">Example</a>'
					)
				),
				'conversational_forms_docs' => array(
					'id' => 'conversational_forms_docs',
					'type' => 'notice',
					'style' => 'success',
					'content' => sprintf(
						__( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
						'<a href="https://themefic.com/docs/uacf7/pro-addons/conversational-form-for-contact-form-7/" target="_blank" rel="noopener">Conversational Form</a>'
					)
				),
				'uacf7_is_conversational' => array(
					'id' => 'uacf7_is_conversational',
					'type' => 'switch',
					'label' => __( ' Enable Conversational Form ', 'ultimate-addons-cf7' ),
					'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
					'label_off' => __( 'No', 'ultimate-addons-cf7' ),
					'default' => false
				),
				'uacf7_enable_conversatinal_form_options_heading' => array(
					'id' => 'uacf7_enable_conversatinal_form_options_heading',
					'type' => 'heading',
					'label' => __( 'Conversatinal Option ', 'ultimate-addons-cf7' ),
				),
				'uacf7_full_screen' => array(
					'id' => 'uacf7_full_screen',
					'type' => 'switch',
					'label' => __( 'Enable Full screen', 'ultimate-addons-cf7' ),
					'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
					'label_off' => __( 'No', 'ultimate-addons-cf7' ),
					'default' => false,
					'field_width' => 50,
				),
				'uacf7_enable_progress_bar' => array(
					'id' => 'uacf7_enable_progress_bar',
					'type' => 'switch',
					'label' => __( 'Enable Progress bar', 'ultimate-addons-cf7' ),
					'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
					'label_off' => __( 'No', 'ultimate-addons-cf7' ),
					'default' => false,
					'field_width' => 50,
				),
				'uacf7_conversational_intro' => array(
					'id' => 'uacf7_conversational_intro',
					'type' => 'switch',
					'label' => __( 'Display Form intro', 'ultimate-addons-cf7' ),
					'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
					'label_off' => __( 'No', 'ultimate-addons-cf7' ),
					'default' => false,
					'field_width' => 50,
				),
				'uacf7_conversational_thankyou' => array(
					'id' => 'uacf7_conversational_thankyou',
					'type' => 'switch',
					'label' => __( 'Display Thank you message', 'ultimate-addons-cf7' ),
					'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
					'label_off' => __( 'No', 'ultimate-addons-cf7' ),
					'default' => false,
					'field_width' => 50,
				),

				'uacf7_conversational_style' => array(
					'id' => 'uacf7_conversational_style',
					'type' => 'select',
					'label' => __( ' Select Form Template:', 'ultimate-addons-cf7' ),
					'multiple' => false,
					'inline' => true,
					'options' => array(
						'style-1' => 'Default',
						'style-2' => 'Style Two',
						'style-3' => 'Style Three',
					),
				),

				// 'uacf7_conversational_style' => array(
				// 	'id'        => 'uacf7_conversational_style',
				//     'type'     => 'imageselect',
				// 	'label'     => __( ' Select Layout :', 'ultimate-addons-cf7' ),
				// 	'description'     => __( 'See live demo examples here: <a href="URL_TO_LIVE_DEMO" target="_blank">Live demo</a>. Check our step by step <a href="URL_TO_DOCUMENTATION" target="_blank">documentation</a>.', 'ultimate-addons-cf7' ),
				//     'multiple' 		=> true,
				//     'inline'   		=> true,
				//     'options' => array(
				//         'design-1' 				=> array(
				//             'title'			=> 'Design 1',
				//            'url' 			=> UACF7_PATH."/assets/admin/images/template/default-hotel.jpg",
				//         ),
				//         'default' 			=> array(
				//             'title'			=> 'Defult',
				//             'url' 			=> UACF7_PATH."/assets/admin/images/template/default-hotel.jpg",
				//         ),
				//      ), 
				// ),

				'uacf7_conversational_bg_color' => array(
					'id' => 'uacf7_conversational_bg_color',
					'type' => 'color',
					'label' => __( 'Form Background Color ', 'ultimate-addons-cf7' ),
					'field_width' => 33
				),
				'uacf7_conversational_button_color' => array(
					'id' => 'uacf7_conversational_button_color',
					'type' => 'color',
					'label' => __( 'Button Text Color ', 'ultimate-addons-cf7' ),
					'field_width' => 33
				),
				'uacf7_conversational_button_bg_color' => array(
					'id' => 'uacf7_conversational_button_bg_color',
					'type' => 'color',
					'label' => __( 'Button Background Color ', 'ultimate-addons-cf7' ),
					'field_width' => 33
				),
				'uacf7_conversational_bg_image' => array(
					'id' => 'uacf7_conversational_bg_image',
					'type' => 'image',
					'label' => __( 'Background Image ', 'ultimate-addons-cf7' ),
				),
				'uacf7_conversational_form_progressbar_option' => array(
					'id' => 'uacf7_conversational_form_progressbar_option',
					'type' => 'heading',
					'label' => __( 'Progressbar Option', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_enable_progress_bar', '==', true ),
				),
				'uacf7_progress_bar_height' => array(
					'id' => 'uacf7_progress_bar_height',
					'type' => 'number',
					'label' => __( 'Height', 'ultimate-addons-cf7' ),
					'placeholder' => __( 'Default: 4px', 'ultimate-addons-cf7' ),
					'description' => __( 'Do not use "px". Just add the number.', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_enable_progress_bar', '==', true ),
					'field_width' => 33,
				),
				'uacf7_progress_bar_bg_color' => array(
					'id' => 'uacf7_progress_bar_bg_color',
					'type' => 'color',
					'label' => __( 'Background Color ', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_enable_progress_bar', '==', true ),
					'field_width' => 33,
				),
				'uacf7_progress_bar_completed_bg_color' => array(
					'id' => 'uacf7_progress_bar_completed_bg_color',
					'type' => 'color',
					'label' => __( 'Completed Background Color ', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_enable_progress_bar', '==', true ),
					'field_width' => 33,
				),

				'uacf7_conversational_form_into_heading' => array(
					'id' => 'uacf7_conversational_form_into_heading',
					'type' => 'heading',
					'label' => __( 'Intro Option', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
				),
				'uacf7_conversational_intro_title' => array(
					'id' => 'uacf7_conversational_intro_title',
					'type' => 'text',
					'label' => __( 'Intro Title', 'ultimate-addons-cf7' ),
					'placeholder' => __( 'Intro Title', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
				),
				'uacf7_conversational_intro_button' => array(
					'id' => 'uacf7_conversational_intro_button',
					'type' => 'text',
					'label' => __( 'Button label', 'ultimate-addons-cf7' ),
					'placeholder' => __( 'Button Label', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
					'field_width' => 33,
				),
				'uacf7_conversational_intro_bg_color' => array(
					'id' => 'uacf7_conversational_intro_bg_color',
					'type' => 'color',
					'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
					'field_width' => 33,
				),
				'uacf7_conversational_intro_text_color' => array(
					'id' => 'uacf7_conversational_intro_text_color',
					'type' => 'color',
					'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
					'field_width' => 33,
				),
				'uacf7_conversational_intro_image' => array(
					'id' => 'uacf7_conversational_intro_image',
					'type' => 'image',
					'label' => __( 'Image ', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
				),
				'uacf7_conversational_intro_message' => array(
					'id' => 'uacf7_conversational_intro_message',
					'type' => 'editor',
					'label' => __( 'Intro Content', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_intro', '==', true ),
				),

				'uacf7_conversational_form_thankyou_heading' => array(
					'id' => 'uacf7_conversational_form_thankyou_heading',
					'type' => 'heading',
					'label' => __( 'Thank You Option', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
				),
				'uacf7_conversational_thank_you_title' => array(
					'id' => 'uacf7_conversational_thank_you_title',
					'type' => 'text',
					'label' => __( 'Thank you title', 'ultimate-addons-cf7' ),
					'placeholder' => __( 'Thank you Title', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
				),
				'uacf7_conversational_thank_you_button' => array(
					'id' => 'uacf7_conversational_thank_you_button',
					'type' => 'text',
					'label' => __( 'Button label', 'ultimate-addons-cf7' ),
					'placeholder' => __( 'Thank you button Label', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
					'field_width' => 25,
				),
				'uacf7_conversational_thank_you_url' => array(
					'id' => 'uacf7_conversational_thank_you_url',
					'type' => 'text',
					'label' => __( 'Button URL', 'ultimate-addons-cf7' ),
					'placeholder' => __( 'Thank you button URL', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
					'field_width' => 25,
				),
				'uacf7_conversational_thankyou_bg_color' => array(
					'id' => 'uacf7_conversational_thankyou_bg_color',
					'type' => 'color',
					'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
					'field_width' => 25,
				),
				'uacf7_conversational_thankyou_text_color' => array(
					'id' => 'uacf7_conversational_thankyou_text_color',
					'type' => 'color',
					'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
					'field_width' => 25,
				),
				'uacf7_conversational_thankyou_image' => array(
					'id' => 'uacf7_conversational_thankyou_image',
					'type' => 'image',
					'label' => __( 'Image ', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
				),
				'uacf7_conversational_thank_you_message' => array(
					'id' => 'uacf7_conversational_thank_you_message',
					'type' => 'editor',
					'label' => __( 'Thank You Content', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_thankyou', '==', true ),
				),
				'uacf7_conversational_step_option_heading' => array(
					'id' => 'uacf7_conversational_step_option_heading',
					'type' => 'heading',
					'label' => __( 'Conversational Steps Option', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_style', '!=', 'style-1' ),
				),
				'uacf7_conversational_steps' => array(
					'id' => 'uacf7_conversational_steps',
					'type' => 'repeater',
					'label' => __( 'Steps', 'ultimate-addons-cf7' ),
					'dependency' => array( 'uacf7_conversational_style', '!=', 'style-1' ),
					'max' => $uacf7_conversational_steps_limit,
					'fields' => array(
						'steps_name' => array(
							'id' => 'steps_name',
							'type' => 'select',
							'label' => __( 'Conversational Step', 'ultimate-addons-cf7' ),
							// 'placeholder' => __( 'Conversational Step', 'ultimate-addons-cf7' ), 
							'options' => 'uacf7',
							'query_args' => array(
								'post_id' => $post_id,
								'specific' => 'uacf7_conversational_start',
							),
						),
						'content_position' => array(
							'id' => 'content_position',
							'type' => 'select',
							'label' => __( 'Content Position', 'ultimate-addons-cf7' ),
							// 'placeholder' => __( 'Content Position', 'ultimate-addons-cf7' ), 
							'options' => array(
								'left' => 'Left',
								'right' => 'Right',
							),
							'field_width' => 33,
						),
						'background_color' => array(
							'id' => 'background_color',
							'type' => 'color',
							'label' => __( 'background color', 'ultimate-addons-cf7' ),
							'placeholder' => __( 'background color', 'ultimate-addons-cf7' ),
							'field_width' => 33,
						),
						'content_color' => array(
							'id' => 'content_color',
							'type' => 'color',
							'label' => __( 'Content color', 'ultimate-addons-cf7' ),
							'placeholder' => __( 'Content color', 'ultimate-addons-cf7' ),
							'field_width' => 33,
						),
						'field_image' => array(
							'id' => 'field_image',
							'type' => 'image',
							'label' => __( 'Conversational Field Image', 'ultimate-addons-cf7' ),
							'field_width' => 100,
						),
					),
				),
				'custom_conv_css_heading' => array(
					'id' => 'custom_conv_css_heading',
					'type' => 'heading',
					'label' => __( 'Custom CSS', 'ultimate-addons-cf7' ),
				),

				'custom_conv_css' => array(
					'id' => 'custom_conv_css',
					'type' => 'code_editor',
					'label' => __( 'Custom CSS', 'ultimate-addons-cf7' ),
					'subtitle' => __( 'If you wish to add custom CSS, you can do so from this section.', 'ultimate-addons-cf7' ),
					'settings' => array(
						'theme' => 'monokai',
						'mode' => 'css',
					),
				),
			),

		), $post_id );

		$value['conversational_form'] = $conversational_form;
		return $value;
	}



	/**
	 * Enqueue script Admin
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function wp_enqueue_admin_script() {
		// jQuery
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'uacf7-conversational-admin', plugin_dir_url( __FILE__ ) . '/assets/css/uacf7-conversational-admin.css' );
		wp_enqueue_script( 'uacf7-conversational-admin', plugin_dir_url( __FILE__ ) . '/assets/js/uacf7-conversational-admin.js', array( 'jquery' ), null, true );
		$conv_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		wp_localize_script( 'jquery', 'conv_settings', $conv_settings );

	}

	/**
	 * Enqueue script Frontend
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function enqueue_script() {
		wp_enqueue_script( 'uacf7-gsap-animation', plugin_dir_url( __FILE__ ) . 'assets/js/gsap.min.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'uacf7-conversational-js', plugin_dir_url( __FILE__ ) . 'assets/js/uacf7-conversational.js', array( 'jquery' ), null, true );
		wp_enqueue_style( 'uacf7-conversational-style', plugin_dir_url( __FILE__ ) . 'assets/css/uacf7-conversational.css' );
		wp_localize_script( 'uacf7-conversational-js', 'conversational_ajax',
			array(
				'admin_url' => get_admin_url() . '/admin.php',
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin_dir_url' => plugin_dir_url( __FILE__ ),
				'nonce' => wp_create_nonce( 'uacf7_conversational' ),
			)
		);

	}

	/** 
	 * Start tag handler
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */
	public function uacf7_conversational_start_tag_handler( $tag ) {
		ob_start();
		$form_current = \WPCF7_ContactForm::get_current();
		$conversational = uacf7_get_form_option( $form_current->id(), 'conversational_form' );
		$uacf7_conversational_style = isset( $conversational['uacf7_conversational_style'] ) ? $conversational['uacf7_conversational_style'] : 'style-1';
		$uacf7_conversational_intro = isset( $conversational['uacf7_conversational_intro'] ) ? $conversational['uacf7_conversational_intro'] : false;
		$intro_title = isset( $conversational['uacf7_conversational_intro_title'] ) ? $conversational['uacf7_conversational_intro_title'] : '';
		$intro_subtitle = isset( $conversational['uacf7_conversational_intro_subtitle'] ) ? $conversational['uacf7_conversational_intro_subtitle'] : '';
		$intro_button = isset( $conversational['uacf7_conversational_intro_button'] ) ? $conversational['uacf7_conversational_intro_button'] : '';
		$intro_button_color = isset( $conversational['uacf7_conversational_intro_button_color'] ) ? $conversational['uacf7_conversational_intro_button_color'] : '';
		$intro_button_bg_color = isset( $conversational['uacf7_conversational_intro_button_bg_color'] ) ? $conversational['uacf7_conversational_intro_button_bg_color'] : '';
		$class = $uacf7_conversational_intro == true ? 'uacf7-conv-single-intro' : '';
		$style = ! empty( $conversational['uacf7_conversational_style'] ) ? $conversational['uacf7_conversational_style'] : '';
		$uacf7_conversational_field = ! empty( $conversational['uacf7_conversational_field'] ) ? $conversational['uacf7_conversational_field'] : '';

		$field_data = array();
		if ( is_array( $uacf7_conversational_field ) ) {
			foreach ( $uacf7_conversational_field as $key => $value ) {
				if ( $value->steps_name == $tag->name ) {
					$field_data = $value;
				}
			}
		}


		$content_position = isset( $field_data['content_position'] ) && $field_data['content_position'] == 'left' && ( $style == 'style-3' || $style == 'style-2' ) ? 'row-reverse' : 'unset';
		$field_width = isset( $field_data['field_image'] ) && $field_data['field_image'] != '' ? '100%' : '70%;';
		$field_bg = isset( $field_data['background_color'] ) && ( $style == 'style-3' || $style == 'style-2' ) ? esc_attr( $field_data['background_color'] ) : '';
		$content_color = isset( $field_data['content_color'] ) && ( $style == 'style-3' || $style == 'style-2' ) ? esc_attr( $field_data['content_color'] ) : '';
		$field_image = isset( $field_data['field_image'] ) && $field_data['field_image'] != '' && ( $style == 'style-3' || $style == 'style-2' ) ? '<div class="uacf7-single-intro-img" style="background-image: url(' . $field_data['field_image'] . '); width:' . esc_attr( $field_width ) . ' !important;"></div>' : '';
		// Print the result

		if ( $content_color != '' ) {
			$content_color_css = '
            <style> 
                .uacf7-conv-single-field#' . $tag->name . ' .uacf7-conv-title,
                .uacf7-conv-single-field#' . $tag->name . ' label,
                .uacf7-conv-single-field#' . $tag->name . ' h1,
                .uacf7-conv-single-field#' . $tag->name . ' h2,
                .uacf7-conv-single-field#' . $tag->name . ' h3,
                .uacf7-conv-single-field#' . $tag->name . ' input,
                .uacf7-conv-single-field#' . $tag->name . ' p{
                    color: ' . $content_color . ' !important;
                }
            </style>';
		} else {
			$content_color_css = '';
		}


		if ( isset( $tag->values ) && ! empty( $tag->values ) ) {
			$step_counter = ! empty( $tag->get_option( 'step', '', true ) ) ? $tag->get_option( 'step', '', true ) : '';
			if ( ! empty( $step_counter ) ) {
				$counter_svg = $style == 'style-2' ? '<svg height="10" width="11"><path d="M7.586 5L4.293 1.707 5.707.293 10.414 5 5.707 9.707 4.293 8.293z"></path><path d="M8 4v2H0V4z"></path></svg> ' : '';
				$title_icon_counter = '<span class="title_icon_counter">' . $step_counter . $counter_svg . ' </span>';
			} else {
				$title_icon_counter = '';
			}
			$title = ' <span class="uacf7-conv-title">' . $title_icon_counter . esc_html( $tag->values[0] ) . '</span> ';
		} else {
			$title = '';
		}
		echo '<div id="' . esc_attr( $tag->name ) . '" form-id="' . esc_attr( $form_current->id() ) . '" class="uacf7-conv-single-field ' . esc_attr( $class ) . '" style="flex-direction: ' . esc_attr( $content_position ) . ';" data-step="0" data-step-status="not-completed" > ' . $content_color_css . $field_image . '<div class="uacf7-conv-single-intro-wrap" style="background-color: ' . $field_bg . '; width:' . esc_attr( $field_width ) . ' !important;"><div class="uacf7-conv-single-field-inner" >' . $title;
		return ob_get_clean();
	}

	/**
	 * End tag handler
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function uacf7_conversational_end_tag_handler( $tag ) {
		ob_start();
		$form_current = \WPCF7_ContactForm::get_current();
		$button = $tag->get_option( 'button', '', true );
		$preloader = plugin_dir_url( __FILE__ ) . 'assets/img/conv-preloader.svg';
		if ( $button == 'yes' ) {
			$conversational = uacf7_get_form_option( $form_current->id(), 'conversational_form' );
			$button_color = isset( $conversational['uacf7_conversational_button_color'] ) ? $conversational['uacf7_conversational_button_color'] : '';
			$button_bg_color = isset( $conversational['uacf7_conversational_button_bg_color'] ) ? $conversational['uacf7_conversational_button_bg_color'] : '';

			if ( isset( $tag->values ) && ! empty( $tag->values ) ) {
				$title = esc_html( $tag->values[0] );
			} else {
				$title = 'submit';
			}

			$button = '<button class="uacf7-conv-next uacf7-conv-next-step" style="color:' . $button_color . '; background-color:' . $button_bg_color . '" data-step="0">' . $title . ' 
              <span class="uacf7-conv-button-icon">  
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3.57422 12.6668L7.41643 8.82463C7.52556 8.71683 7.61216 8.58843 7.67129 8.4469C7.73043 8.30536 7.76089 8.15356 7.76089 8.00016C7.76089 7.84676 7.73043 7.69496 7.67129 7.55343C7.61216 7.4119 7.52556 7.2835 7.41643 7.1757L3.57422 3.3335" stroke="#FFFBEB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M8.24023 12.6668L12.0824 8.82463C12.1916 8.71683 12.2782 8.58843 12.3373 8.4469C12.3964 8.30536 12.4269 8.15356 12.4269 8.00016C12.4269 7.84676 12.3964 7.69496 12.3373 7.55343C12.2782 7.4119 12.1916 7.2835 12.0824 7.1757L8.24023 3.3335" stroke="#FFFBEB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg> 
              </span>
             <span class="wpcf7-spinner uacf7-conv-ajax-loader"><img src="' . esc_attr( $preloader ) . '" alt=""></span></button>';
		} else {
			$button = '<span class="wpcf7-spinner uacf7-conv-ajax-loader" ><img src="' . esc_attr( $preloader ) . '" alt=""></span>';
		}
		echo $button . '</div> </div> </div>';
		return ob_get_clean();
	}

	/**
	 * Tag generator
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function tag_generator() {

		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'uacf7_conversational_start',
			__( 'Conversational Start', 'ultimate-addons-cf7' ),
			array( $this, 'tg_panel_conversational_start' ),
			array( 'version' => '2' )
		);
		$tag_generator->add(
			'uacf7_conversational_end',
			__( 'Conversational End', 'ultimate-addons-cf7' ),
			array( $this, 'tg_panel_conversational_end' ),
			array( 'version' => '2' )
		);

	}

	/**
	 * Start tag generator panel
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function tg_panel_conversational_start( $contact_form, $options ) {

		$field_types = array(
			'uacf7_conversational_start' => array(
				'display_name' => __( 'Conversational Start', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Conversational Start Tag Step', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );

		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_conversational_start']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_conversational_start']['description'],
				array(
					'a' => array( 'href' => true ),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);

			echo $description;
			?></p>
			<div class="uacf7-doc-notice">
				<?php echo sprintf(
					__( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/conversational-form-for-contact-form-7/" target="_blank">Conversational Form</a>'
				); ?>
			</div>
		</header>

		<div class="control-box">
			<?php

			$tgg->print( 'field_type', array(
				'with_required' => false,
				'select_options' => array(
					'uacf7_conversational_start' => $field_types['uacf7_conversational_start']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );

			$tgg->print( 'default_value', [ 
				'type' => 'text',
				'title' => __( 'Title', 'ultimate-addons-cf7' ),
				'use_content' => false,
			] );
			?>

			<fieldset>
				<legend>
					<?php _e( 'Step Number', 'ultimate-addons-cf7' ); ?>
				</legend>
				<input type="number" data-tag-part="option" data-tag-option="step:" name="step">
			</fieldset>

			<div class="uacf7-doc-notice uacf7-guide">
				<?php echo esc_html( __( 'To activate the form, enable it from the "Conversational Form" tab located under the Ultimate Addons for CF7 Options. This tab also contains additional settings.', 'ultimate-addons-cf7' ) ); ?>
			</div>

		</div>

		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}

	/**
	 * End tag generator panel
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function tg_panel_conversational_end( $contact_form, $options ) {

		$field_types = array(
			'uacf7_conversational_end' => array(
				'display_name' => __( 'Conversational End', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Conversational End Tag', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_conversational_end']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_conversational_end']['description'],
				array(
					'a' => array( 'href' => true ),
					'strong' => array(),
				),
				array( 'http', 'https' )
			);

			echo $description;
			?></p>
			<div class="uacf7-doc-notice">
				<?php echo sprintf(
					__( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/conversational-form-for-contact-form-7/" target="_blank">Conversational Form</a>'
				); ?>
			</div>
		</header>

		<div class="control-box">
			<?php
			$tgg->print( 'field_type', array(
				'with_required' => false,
				'select_options' => array(
					'uacf7_conversational_end' => $field_types['uacf7_conversational_end']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );

			$tgg->print( 'default_value', [ 
				'type' => 'text',
				'title' => __( 'Button Name', 'ultimate-addons-cf7' ),
				'with_placeholder' => false,
				'use_content' => false,
			] );

			?>
			<fieldset>
				<legend>
					<?php echo esc_html__( "Display Button", "ultimate-addons-cf7" ); ?>
				</legend>

				<label for="yes">
					<input type="radio" data-tag-part="option" data-tag-option="button:" name="button" id="yes" value="yes" checked>
					<?php echo esc_html__( 'Yes', 'ultimate-addons-cf7' ); ?>
				</label>

				<label for="no">
					<input type="radio" data-tag-part="option" data-tag-option="button:" name="button" id="no" value="no">
					<?php echo esc_html__( 'No', 'ultimate-addons-cf7' ); ?>
				</label>
			</fieldset>
		</div>

		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}



	/**
	 * UACF7 Conversational properties
	 * @author Sydur Rahman
	 * @param $form
	 * @since 1.0.0
	 */

	public function uacf7_properties( $properties, $form ) {

		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$all_steps = $form->scan_form_tags( array( 'type' => 'uacf7_conversational_end' ) );

			$conversational = uacf7_get_form_option( $form->id(), 'conversational_form' );

			$uacf7_is_conversational = isset( $conversational['uacf7_is_conversational'] ) ? $conversational['uacf7_is_conversational'] : false;
			if ( ! empty( $all_steps ) && isset( $uacf7_is_conversational ) && $uacf7_is_conversational == true ) {
				$style = isset( $conversational['uacf7_conversational_style'] ) ? $conversational['uacf7_conversational_style'] : 'style-1';
				$uacf7_full_screen = isset( $conversational['uacf7_full_screen'] ) ? $conversational['uacf7_full_screen'] : false;
				$is_intro = isset( $conversational['uacf7_conversational_intro'] ) ? $conversational['uacf7_conversational_intro'] : false;
				$uacf7_conversational_thankyou = isset( $conversational['uacf7_conversational_thankyou'] ) ? $conversational['uacf7_conversational_thankyou'] : false;
				$uacf7_conversational_bg_color = isset( $conversational['uacf7_conversational_bg_color'] ) ? $conversational['uacf7_conversational_bg_color'] : '';

				$content_color = isset( $conversational['uacf7_conversational_intro_text_color'] ) ? $conversational['uacf7_conversational_intro_text_color'] : '';
				$bg_color = isset( $conversational['uacf7_conversational_intro_bg_color'] ) ? $conversational['uacf7_conversational_intro_bg_color'] : '';
				$uacf7_conversational_bg_image = isset( $conversational['uacf7_conversational_bg_image'] ) ? $conversational['uacf7_conversational_bg_image'] : '';
				$intro_title = isset( $conversational['uacf7_conversational_intro_title'] ) ? $conversational['uacf7_conversational_intro_title'] : '';
				$uacf7_conversational_intro_image = isset( $conversational['uacf7_conversational_intro_image'] ) ? $conversational['uacf7_conversational_intro_image'] : '';
				$uacf7_conversational_intro_message = isset( $conversational['uacf7_conversational_intro_message'] ) ? $conversational['uacf7_conversational_intro_message'] : '';
				$intro_button = isset( $conversational['uacf7_conversational_intro_button'] ) ? $conversational['uacf7_conversational_intro_button'] : '';
				$custom_conv_css = isset( $conversational['custom_conv_css'] ) ? $conversational['custom_conv_css'] : '';

				// Progress bar 
				$uacf7_enable_progress_bar = isset( $conversational['uacf7_enable_progress_bar'] ) ? $conversational['uacf7_enable_progress_bar'] : false;
				$uacf7_progress_bar_height = isset( $conversational['uacf7_progress_bar_height'] ) ? $conversational['uacf7_progress_bar_height'] : '';
				$uacf7_progress_bar_bg_color = isset( $conversational['uacf7_progress_bar_bg_color'] ) ? $conversational['uacf7_progress_bar_bg_color'] : '';
				$uacf7_progress_bar_completed_bg_color = isset( $conversational['uacf7_progress_bar_completed_bg_color'] ) ? $conversational['uacf7_progress_bar_completed_bg_color'] : '';
				$uacf7_conversational_button_color = isset( $conversational['uacf7_conversational_button_color'] ) ? $conversational['uacf7_conversational_button_color'] : '';
				$uacf7_conversational_button_bg_color = isset( $conversational['uacf7_conversational_button_bg_color'] ) ? $conversational['uacf7_conversational_button_bg_color'] : '';
				$uacf7_conversational_button_color = isset( $conversational['uacf7_conversational_button_color'] ) ? $conversational['uacf7_conversational_button_color'] : '';
				$uacf7_conversational_button_bg_color = isset( $conversational['uacf7_conversational_button_bg_color'] ) ? $conversational['uacf7_conversational_button_bg_color'] : '';

				$thankyou_content_color = isset( $conversational['uacf7_conversational_thankyou_text_color'] ) ? $conversational['uacf7_conversational_thankyou_text_color'] : '';

				$conv_up_down_display = $is_intro == true ? 'none' : 'block';

				if ( $content_color != '' ) {
					$custom_conv_css .= ' 
                        .uacf7-conv-single-intro.intro-first .uacf7-conv-title,
                        .uacf7-conv-single-intro.intro-first .uacf7-conv-intro-text,
                        .uacf7-conv-single-intro.intro-first label,
                        .uacf7-conv-single-intro.intro-first h1,
                        .uacf7-conv-single-intro.intro-first h2,
                        .uacf7-conv-single-intro.intro-first h3,
                        .uacf7-conv-single-intro.intro-first input,
                        .uacf7-conv-single-intro.intro-first p{
                            color: ' . esc_attr( $content_color ) . ' !important;
                        } 
                    ';
				}

				if ( $thankyou_content_color != '' ) {
					$custom_conv_css .= '  
                        .uacf7-conv-single-thankyou.intro-first .uacf7-conv-title,
                        .uacf7-conv-single-thankyou.intro-first .uacf7-conv-thankyou-text,
                        .uacf7-conv-single-thankyou.intro-first label,
                        .uacf7-conv-single-thankyou.intro-first h1,
                        .uacf7-conv-single-thankyou.intro-first h2,
                        .uacf7-conv-single-thankyou.intro-first h3,
                        .uacf7-conv-single-thankyou.intro-first h4,
                        .uacf7-conv-single-thankyou.intro-first input,
                        .uacf7-conv-single-thankyou.intro-first p{
                            color: ' . esc_attr( $thankyou_content_color ) . ' !important;
                        }  
                     ';
				}

				$custom_conv_css .= ' 
                    :root {
                        --uacf7-conv-button-color: ' . esc_attr( $uacf7_conversational_button_color ) . ';
                        --uacf7-conv-button-bg: ' . esc_attr( $uacf7_conversational_button_bg_color ) . ';
                    }
                    .wpcf7-submit {
                        color: ' . esc_attr( $uacf7_conversational_button_color ) . ' !important;
                        background-color: ' . esc_attr( $uacf7_conversational_button_bg_color ) . ' !important;
                    }
                    .uacf7-form-' . $form->id() . '{
                        height: 100%;
                    }
                    .uacf7-conv-form-wrap{
                        background-color: ' . esc_attr( $uacf7_conversational_bg_color ) . ' !important;
                    }
                    
                ';
				$full_screan = ( $uacf7_full_screen == true ) ? 'uacf7-conv-full-screen' : '';

				$conv_form = $form->prop( 'form' );
				$mail = $form->prop( 'mail' );
				$mail_2 = $form->prop( 'mail_2' );
				$form_parts = preg_split( '/(\[\/?uacf7_conversational_end(?:\]|\s.*?\]))/', $conv_form, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

				// Thank you content status
				$thankyou = $uacf7_conversational_thankyou != '' ? $uacf7_conversational_thankyou : false;

				$intro_button = ( $intro_button == '' ) ? 'Start' : $intro_button;

				ob_start();
				$count = 1;
				echo '<div class="uacf7-conv-form-wrap ' . $style . ' ' . $full_screan . '" data-thankyou="' . esc_attr( $thankyou ) . '" style="background-image: url(' . $uacf7_conversational_bg_image . ');">';
				if ( $custom_conv_css != '' ) {
					echo '<style>' . $custom_conv_css . '</style>';
				}
				if ( $is_intro == true ) {
					$intro_button = ( $intro_button == '' ) ? 'Start' : $intro_button;

					echo '<div class="uacf7-conv-single-intro intro-first"> <div class="uacf7-conv-single-intro-wrap" style="background-color: ' . esc_attr( $bg_color ) . ';"><div class="uacf7-conv-single-field-inner">';
					echo '<h2 class="uacf7-conv-intro-title">' . $intro_title . '</h2>';
					echo '<div class="uacf7-conv-intro-text"> <p>' . $uacf7_conversational_intro_message . '</p></div>';
					echo '<button class="uacf7-conv-intro-button" type="submit" style="color:' . esc_attr( $uacf7_conversational_button_color ) . '; background-color:' . esc_attr( $uacf7_conversational_button_bg_color ) . '"> ' . esc_html( $intro_button ) . ' 
                        <span class="uacf7-conv-button-icon">  
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.57422 12.6668L7.41643 8.82463C7.52556 8.71683 7.61216 8.58843 7.67129 8.4469C7.73043 8.30536 7.76089 8.15356 7.76089 8.00016C7.76089 7.84676 7.73043 7.69496 7.67129 7.55343C7.61216 7.4119 7.52556 7.2835 7.41643 7.1757L3.57422 3.3335" stroke="#FFFBEB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.24023 12.6668L12.0824 8.82463C12.1916 8.71683 12.2782 8.58843 12.3373 8.4469C12.3964 8.30536 12.4269 8.15356 12.4269 8.00016C12.4269 7.84676 12.3964 7.69496 12.3373 7.55343C12.2782 7.4119 12.1916 7.2835 12.0824 7.1757L8.24023 3.3335" stroke="#FFFBEB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg> 
                        </span>
                    </button></div></div>';
					if ( $uacf7_conversational_intro_image != '' ) {
						echo '<div class="uacf7-conv-intro-image" ><img src="' . esc_attr( $uacf7_conversational_intro_image ) . '" alt="intro image"></div>';
					}
					echo '</div>';
				}
				foreach ( $form_parts as $form_part ) {
					echo $form_part;
				}
				echo '<div class="uacf7-conv-up-down" style="display:' . esc_attr( $conv_up_down_display ) . ';">
                        <span class="uacf7-conv-up" data-complete-steps="[]"><svg width="13" height="8" viewBox="0 0 13 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.36396 2.8284L1.41422 7.7782L0 6.364L6.36396 0L12.728 6.364L11.3138 7.7782L6.36396 2.8284Z" fill="black"/>
                        </svg></span>
                        <span class="uacf7-conv-down"><svg width="13" height="8" viewBox="0 0 13 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.36396 5.1714L11.3138 0.22168L12.728 1.63589L6.36396 7.9999L0 1.63589L1.41422 0.22168L6.36396 5.1714Z" fill="black"/>
                        </svg>
                        </span>
                    </div>';
				if ( $uacf7_enable_progress_bar == true ) {
					echo '<span class="uacf7-conv-progress" style="background-color: ' . esc_attr( $uacf7_progress_bar_bg_color ) . '; height: ' . esc_attr( $uacf7_progress_bar_height ) . 'px;" ><span  style="background-color: ' . esc_attr( $uacf7_progress_bar_completed_bg_color ) . ';" class="uacf7-conv-progress-completed"></span></span>';
				}
				echo '</div>';
				?>
				<?php
				$conversational_form = ob_get_clean();
				$properties['form'] = $conversational_form;
				// $form->set_properties( array(
				//     'form'   => $conversational_form,
				//     'mail'   => $mail,
				//     'mail_2' => $mail_2
				// ));

			}
		}

		return $properties;


	}

	/**
	 * UACF7 Conversational Form validation
	 * @author Sydur Rahman
	 * @since 1.0.0
	 */

	public function uacf7_conversational_step_validation() {
		if ( ! wp_verify_nonce( $_REQUEST['ajax_nonce'], 'uacf7_conversational' ) ) {
			exit( esc_html__( "Security error", 'ultimate-addons-cf7' ) );
		}

		$current_step_fields = explode( ',', $_REQUEST['current_fields_to_check'] );

		// Validation with Repeater 
		$validation_fields = explode( ',', $_REQUEST['validation_fields'] );
		$tag_name = [];
		$tag_validation = [];
		$tag_type = [];
		$file_error = [];
		$count = '1';
		for ( $x = 0; $x < count( $validation_fields ); $x++ ) {
			$field = explode( ':', $validation_fields[ $x ] );
			$name = $field[1];
			$name_array = explode( "__", $field[1] );
			$replace = '__' . $count . '';
			$tag_name[] = $name_array[0];
			$tag_validation[ $field[0] . $x ] = $name;
			$tag_type[] = $field[0];
			$count++;
		}
		$form = wpcf7_contact_form( $_REQUEST['form_id'] );
		$all_form_tags = $form->scan_form_tags();
		$invalid_fields = false;
		require_once WPCF7_PLUGIN_DIR . '/includes/validation.php';
		$result = new \WPCF7_Validation();
		$tags = array_filter(
			$all_form_tags, function ($v, $k) use ($tag_name) {
				return in_array( $v->name, $tag_name );
			}, ARRAY_FILTER_USE_BOTH
		);
		$form->validate_schema(
			array(
				'text' => true,
				'file' => false,
				'field' => $tag_name,
			),
			$result
		);
		foreach ( $tags as $tag ) {
			$type = $tag->type;
			if ( 'file' != $type && 'file*' != $type ) {
				$result = apply_filters( "wpcf7_validate_{$type}", $result, $tag );

			} elseif ( 'file*' === $type || 'file' === $type ) {
				$fdir = $_REQUEST[ $tag->name ];
				if ( $fdir ) {
					$_FILES[ $tag->name ] = array(
						'name' => wp_basename( $fdir ),
						'tmp_name' => $fdir,
					);
				}
				$file = $_FILES[ $tag->name ];
				//$file = $_REQUEST[$tag->name];
				$args = array(
					'tag' => $tag,
					'name' => $tag->name,
					'required' => $tag->is_required(),
					'filetypes' => $tag->get_option( 'filetypes' ),
					'limit' => $tag->get_limit_option(),
				);
				$args['schema'] = $form->get_schema();
				$new_files = wpcf7_unship_uploaded_file( $file, $args );
				if ( is_wp_error( $new_files ) ) {
					$result->invalidate( $tag, $new_files );
				}
				$result = apply_filters( "wpcf7_validate_{$type}", $result, $tag, array( 'uploaded_files' => $new_files, ) );

				if ( isset( $_REQUEST[ $tag->name . '_size' ] ) ) {
					$file_size = $_REQUEST[ $tag->name . '_size' ];
					// echo $file_size;
					if ( $file_size > $tag->get_limit_option() ) {
						$file_error = array(
							'into' => 'span.wpcf7-form-control-wrap[data-name = ' . $tag->name . ']',
							'message' => 'The uploaded file is too large.',
							'idref' => null,
						);
					}
				}

			}

		}
		// $result = apply_filters('wpcf7_validate', $result, $tags); 
		$is_valid = $result->is_valid();
		if ( ! $is_valid ) {
			$invalid_fields = $this->prepare_invalid_form_fields( $result, $tag_validation );
		}
		if ( ! empty( $file_error ) ) {
			$invalid_fields[] = $file_error;
		}
		if ( ! empty( $invalid_fields ) ) {
			$is_valid = false;
		}
		echo ( json_encode( array(
			'is_valid' => $is_valid,
			'invalid_fields' => $invalid_fields,
			'result' => $tag_validation,
		)
		)
		);
		wp_die();
	}

	/**
	 * Prepare invalid form fields
	 * @author Sydur Rahman 
	 * @param $result
	 * @param $tag_validation
	 * @return array
	 * @since 1.0.0
	 */

	private function prepare_invalid_form_fields( $result, $tag_validation ) {
		$invalid_fields = array();

		// Validation with Repeater 
		$count = 1;
		$invalid_data = [];
		foreach ( (array) $result->get_invalid_fields() as $name => $field ) {
			$invalid_data[ $name ] = array(
				'name' => $name,
				'message' => $field['reason'],
				'idref' => $field['idref'],
			);
		}
		foreach ( $tag_validation as $key => $value ) {
			$name = explode( "__", $value );
			$name = $name[0];
			if ( ! empty( $invalid_data[ $name ] ) ) {
				$field = $invalid_data[ $name ];
				$invalid_fields[] = array(
					'into' => 'span.wpcf7-form-control-wrap[data-name = ' . $value . ']',
					'message' => $field['message'],
					'idref' => $field['idref'],
				);
			}
		}
		return $invalid_fields;
	}


	/**
	 * Thankyou section for conversational form
	 * @author Sydur Rahman  
	 * @param $form_id
	 * @return html
	 * @since 1.0.0
	 */

	public function uacf7_conversational_thankyou_message() {
		$form_id = intval( $_POST['form_id'] ); // data id 
		if ( $form_id != '' || $form_id != 0 ) {
			$conversational = uacf7_get_form_option( $form_id, 'conversational_form' );
			$thankyou_title = isset( $conversational['uacf7_conversational_thank_you_title'] ) ? $conversational['uacf7_conversational_thank_you_title'] : '';
			$thankyou_message = isset( $conversational['uacf7_conversational_thank_you_message'] ) ? $conversational['uacf7_conversational_thank_you_message'] : '';
			$uacf7_conversational_button_color = isset( $conversational['uacf7_conversational_button_color'] ) ? $conversational['uacf7_conversational_button_color'] : '';
			$uacf7_conversational_button_bg_color = isset( $conversational['uacf7_conversational_button_bg_color'] ) ? $conversational['uacf7_conversational_button_bg_color'] : '';
			$thank_you_button = isset( $conversational['uacf7_conversational_thank_you_button'] ) ? $conversational['uacf7_conversational_thank_you_button'] : '';
			$thank_you_url = isset( $conversational['uacf7_conversational_thank_you_url'] ) ? $conversational['uacf7_conversational_thank_you_url'] : '';
			$custom_conv_css = isset( $conversational['custom_conv_css'] ) ? $conversational['custom_conv_css'] : '';
			$bg_color = isset( $conversational['uacf7_conversational_thankyou_bg_color'] ) ? $conversational['uacf7_conversational_thankyou_bg_color'] : '';
			$uacf7_conversational_thankyou_image = isset( $conversational['uacf7_conversational_thankyou_image'] ) ? $conversational['uacf7_conversational_thankyou_image'] : '';
			$width = $uacf7_conversational_thankyou_image != '' ? '100% !important' : '60% !important';

			ob_start();
			echo '<style>' . $custom_conv_css . '</style>';
			echo '<div class="uacf7-conv-single-thankyou intro-first"> <div class="uacf7-conv-single-thankyou-wrap" style="width:' . $width . '; background-color: ' . esc_attr( $bg_color ) . '"><div class="uacf7-conv-single-field-inner">';
			echo '<h4 class="uacf7-conv-thankyou-title">' . $thankyou_title . '</h4>';
			echo '<div class="uacf7-conv-thankyou-text">' . $thankyou_message . '</div>';
			if ( $thank_you_button != '' ) {
				echo '<a href="' . esc_attr( $thank_you_url ) . '" class="uacf7-conv-thankyou-button" type="submit" style=" color:' . esc_attr( $uacf7_conversational_button_color ) . '; background-color:' . esc_attr( $uacf7_conversational_button_bg_color ) . '"> ' . esc_html( $thank_you_button ) . '
                    <span class="uacf7-conv-button-icon">  
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.57422 12.6668L7.41643 8.82463C7.52556 8.71683 7.61216 8.58843 7.67129 8.4469C7.73043 8.30536 7.76089 8.15356 7.76089 8.00016C7.76089 7.84676 7.73043 7.69496 7.67129 7.55343C7.61216 7.4119 7.52556 7.2835 7.41643 7.1757L3.57422 3.3335" stroke="#FFFBEB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.24023 12.6668L12.0824 8.82463C12.1916 8.71683 12.2782 8.58843 12.3373 8.4469C12.3964 8.30536 12.4269 8.15356 12.4269 8.00016C12.4269 7.84676 12.3964 7.69496 12.3373 7.55343C12.2782 7.4119 12.1916 7.2835 12.0824 7.1757L8.24023 3.3335" stroke="#FFFBEB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg> 
                    </span>
                </a>';


			}
			echo '</div></div>';
			if ( $uacf7_conversational_thankyou_image != '' ) {
				echo '<div style="width:' . esc_attr( $width ) . ';" class="uacf7-conv-intro-image"><img src="' . esc_attr( $uacf7_conversational_thankyou_image ) . '" alt="intro image"></div>';
			}
			echo '</div>';
			$data = [ 
				'html' => ob_get_clean(),
				'form_id' => $form_id,
			];
			echo wp_send_json( $data );

		}


	}



	/**
	 * Form Generator AI Hooks And Callback Functions
	 * @since 1.0.1
	 */

	public function uacf7_conversational_interview_form_dropdown() {
		return [ "value" => "conversational-interview-form", "label" => "Conversational Interview Process" ];
	}

	public function uacf7_conversational_appointment_form_dropdown() {
		return [ "value" => "conversational-appointment-form", "label" => "Conversational Appointment Booking " ];
	}

	public function uacf7_conversational_form_ai_generator( $value, $uacf7_default ) {

		if ( $uacf7_default[1] == 'conversational-appointment-form' ) {
			// if($uacf7_default[1] == 'booking'){

			$value = '[uacf7_conversational_start uacf7_conversational_start-390 step:01 "Alright, lets start with the basics. What your name?"]
<label> <span class="conv_label">First name is </span>
   <span class="conv_field"> [text* first-name autocomplete:name] </span></label> 
<label> <span class="conv_label">Last name is  </span>
    <span class="conv_field">[text* last-name autocomplete:name]</span> </label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-390 "Thanks, and what is your email?"]
<h3>We will send your appointment update here for safekeeping</h3>
<label> My Email is
    [email* your-email autocomplete:email] </label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-3999 "Great! What is your phone number?"]
<h3>We will send appointment update here for safekeeping</h3>
<label> My number is
      [select menu-771 "select one" "select two" "select three"] </label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-390 "Great! What is your phone number?"]
<h3>We will send appointment update here for safekeeping</h3>
<label> My number is
     [tel* tel-365] </label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-390 "When do you want that booking for? We will send you an email to confirm a time."]
<label> [date* date-338] </label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-390 "And which exact service do you want to book ?"]
<label> [checkbox checkbox-491 use_label_element "Interested in (service #1) - $700" "Interested in (service #2) - $500" "Interested in (service #3) - $200" "Interested in (service #4) - $400"]</label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-390 "If you have any special requests or want to leave us a note, now is the time."]
 <h3>We will send appointment update here for safekeeping</h3>
 <label> Your answer
    [text* answer autocomplete:name] </label>
[uacf7_conversational_end button:yes "OK"] 
[uacf7_conversational_start uacf7_conversational_start-390 "Would you be okay with being contacted by phone as well?*"]
<label> [radio radio-692 use_label_element default:1 "Yes, you can call or message me" "Yes, you can message me" "No, just email me"] </label> 
[submit "Submit"]
[uacf7_conversational_end button:no"OK"]';
		} else if ( $uacf7_default[1] == 'conversational-interview-form' ) {

			$value = '[uacf7_conversational_start uacf7_conversational_start-104 "What is your department?"]
[checkbox* checkbox-773 use_label_element "Admin" "Finance" "IT"]
[uacf7_conversational_end button:yes "Ok"] 
[uacf7_conversational_start uacf7_conversational_start-105 "What is your name?"]
<label>[text* your-name autocomplete:name]</label>
[uacf7_conversational_end button:yes "Ok"] 
[uacf7_conversational_start uacf7_conversational_start-106 "Let us know your Email *"]
<h3>Please Provide your Email Address</h3>
<label> [email* your-email autocomplete:email] </label>
[uacf7_conversational_end button:yes "Ok"] 
[uacf7_conversational_start uacf7_conversational_start-109 "Your favorite travel destination?"]
<label>  [text* text-destination] </label>
[uacf7_conversational_end button:yes "Ok"] 
[uacf7_conversational_start uacf7_conversational_start-108 "Rate Your English Communication out of 5"]
<label>  [uacf7_star_rating* rating icon:star2 "default"] </label>
[submit "Submit"]
[uacf7_conversational_end button:no "Ok"]';

		}
		return $value;

	}

}

/**
 * UACF7 Conversational Form Pro
 * @author Sydur Rahman
 * @since 1.0.0
 */
new UACF7_CONVERSATIONAL_FORM_PRO();