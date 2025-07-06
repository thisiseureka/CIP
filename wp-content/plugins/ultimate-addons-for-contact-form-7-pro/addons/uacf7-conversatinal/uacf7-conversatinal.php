<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 
 * `UACF7_CONVERSATIONAL_FORM` this customizes the contact form 7 by converting it to a conversational form.
 * @author   MhemelHasan <mhemelhasan>
 * @since    1.7.3
 * 
 */
class UACF7_CONVERSATIONAL_FORM {

	public function __construct() {
		// Post Meta Option
		add_filter( 'uacf7_post_meta_options', [ $this, 'uacf7_post_meta_options_conversational_form' ], 19, 2 );

		// Hook to add custom ID to form attributes
		add_filter( 'wpcf7_form_id_attr', [ $this, 'add_uacf7_conversational_id_to_cf7_form_attributes' ], 5, 2 );

		// Hook to modify the class attribute
		add_filter( 'wpcf7_form_class_attr', [ $this, 'uacf7_conversational_form_class' ] );

		add_filter( 'wpcf7_form_additional_atts', [ $this, 'uacf7_conversational_form_type' ], 10, 3 );

		// Hook to customize the form output
		add_filter( 'wpcf7_form_elements', [ $this, 'uacf7_conversational_customize_cf7_form_element' ] );

		// Enqueue Script
		add_action( 'wp_enqueue_scripts', array( $this, 'uacf7_conversational_enqueue_script' ) );

		// Conversational form validation 
		add_action( 'wp_ajax_uacf7_cons_fields_validation', array( $this, 'uacf7_cons_fields_validation' ) );
		add_action( 'wp_ajax_nopriv_uacf7_cons_fields_validation', array( $this, 'uacf7_cons_fields_validation' ) );
	}

	/**
	 * Add Conversational Form settings to post meta options
	 */
	public function uacf7_post_meta_options_conversational_form( $value, $post_id ) {
		if ( uacf7_settings( 'uacf7_enable_conversational_form' ) != true ) {
			return $value;
		}

		$conversational_form = apply_filters( 'uacf7_post_meta_options_conversational_form_pro', array(
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
				'uacf7_conversational_heading' => array(
					'id' => 'uacf7_conversational_heading',
					'type' => 'text',
					'label' => __( 'Conversational Form Heading', 'ultimate-addons-cf7' ),
					'subtitle' => __( "This will show at the beginning of the from", "ultimate-addons-cf7" ),
					'placeholder' => __( 'Enter your welcome heading ', 'ultimate-addons-cf7' ),
					'field_width' => 50,
					'default' => 'Welcome to UACF7 Conversational.',
					'dependency' => array( 'uacf7_is_conversational', '==', '1' ),
				),
				'uacf7_conversational_subheading' => array(
					'id' => 'uacf7_conversational_subheading',
					'type' => 'text',
					'label' => __( 'Conversational Form Sub-Heading', 'ultimate-addons-cf7' ),
					'subtitle' => __( "This will show at the beginning of the from", "ultimate-addons-cf7" ),
					'placeholder' => __( 'Enter your sub-heading ', 'ultimate-addons-cf7' ),
					'field_width' => 50,
					'default' => 'The most advantageous feature is its capability to transform content from 7 a standard format into a conversational format.',
					'dependency' => array( 'uacf7_is_conversational', '==', '1' ),
				),

				'uacf7_conversational_thankyou_text' => array(
					'id' => 'uacf7_conversational_thankyou_text',
					'type' => 'text',
					'label' => __( 'Conversational Form ThankYou Text', 'ultimate-addons-cf7' ),
					'subtitle' => __( "This will show at the end of the from", "ultimate-addons-cf7" ),
					'placeholder' => __( 'Enter your thankyou text', 'ultimate-addons-cf7' ),
					'field_width' => 50,
					'default' => 'Thank you for your message. We will reach out to you shortly.',
					'dependency' => array( 'uacf7_is_conversational', '==', '1' ),
				),
			),
		), $post_id );

		$value['conversational_form'] = $conversational_form;
		return $value;
	}

	/**
	 * Add custom ID to specific Contact Form 7 form
	 *
	 * @param array $attributes Form attributes.
	 * @param WPCF7_ContactForm $form The form object.
	 * @return array Modified form attributes.
	 */
	public function add_uacf7_conversational_id_to_cf7_form_attributes( $id ) {

		// Get the conversational setting
		$form = \WPCF7_ContactForm::get_current();
		$conversational = uacf7_get_form_option( $form->id(), 'conversational_form' );
		$is_uacf7_conversational_enable = isset( $conversational['uacf7_is_conversational'] ) ? $conversational['uacf7_is_conversational'] : false;

		// Only add the custom ID if the conversational option is enabled
		if ( $is_uacf7_conversational_enable ) {
			return 'uacf7-conversational-form'; // Your custom ID
		}

		return $id; // Default form ID if not enabled
	}

	public function uacf7_conversational_form_class( $class ) {
		// Get the current form and conversational setting
		$form = \WPCF7_ContactForm::get_current();
		$conversational = uacf7_get_form_option( $form->id(), 'conversational_form' );
		$is_uacf7_conversational_enable = isset( $conversational['uacf7_is_conversational'] ) ? $conversational['uacf7_is_conversational'] : false;

		// Only add the custom class if the conversational option is enabled
		if ( $is_uacf7_conversational_enable ) {
			$class .= ' uacf7-conver-default';  // Append the custom class
		}

		return $class; // Return the updated class attribute
	}

	public function uacf7_conversational_form_type( $atts ) {
		// Get the conversational setting
		$form = \WPCF7_ContactForm::get_current();
		$conversational = uacf7_get_form_option( $form->id(), 'conversational_form' );
		$is_uacf7_conversational_enable = isset( $conversational['uacf7_is_conversational'] ) ? $conversational['uacf7_is_conversational'] : false;

		// Only add the custom attributes if the conversational option is enabled
		if ( $is_uacf7_conversational_enable ) {
			// Add a custom form-type data attribute
			$atts['form-type'] = 'uacf7-conversational';  // Add the form-type attribute
		}

		return $atts; // Return updated attributes
	}

	/**
	 * Customize the form output by wrapping each form element with a custom div
	 *
	 * @param string $form The form HTML.
	 * @return string Modified form HTML.
	 */
	public function uacf7_conversational_customize_cf7_form_element( $form ) {
		// Get the conversational setting
		$cf7form = \WPCF7_ContactForm::get_current();
		$conversational = uacf7_get_form_option( $cf7form->id(), 'conversational_form' );
		$is_uacf7_conversational_enable = isset( $conversational['uacf7_is_conversational'] ) ? $conversational['uacf7_is_conversational'] : false;
		$uacf7_conversational_heading = isset( $conversational['uacf7_conversational_heading'] ) ? $conversational['uacf7_conversational_heading'] : '';
		$uacf7_conversational_subheading = isset( $conversational['uacf7_conversational_subheading'] ) ? $conversational['uacf7_conversational_subheading'] : '';

		$uacf7_conversational_thanksheading = isset( $conversational['uacf7_conversational_thankyou_text'] ) ? $conversational['uacf7_conversational_thankyou_text'] : '';

		if ( $is_uacf7_conversational_enable ) {

			// Ensure $form is treated as a string
			if ( is_array( $form ) ) {
				$form = implode( '', $form );
			}

			// Load the form HTML into a DOMDocument
			$dom = new \DOMDocument();
			libxml_use_internal_errors( true );  // Suppress warnings for malformed HTML
			$dom->loadHTML( '<?xml encoding="utf-8" ?>' . $form );
			libxml_clear_errors();

			// Get the outer div that contains all the form elements
			$outerDiv = $dom->getElementsByTagName( 'div' )->item( 0 ); // Assuming the first div is the main wrapper

			// Create a new document fragment to hold the new structure
			$fragment = $dom->createDocumentFragment();

			// Create the fixed wrapper div and add it as the first item
			$fixedWrapper = $dom->createElement( 'div' );
			$fixedWrapper->setAttribute( 'class', 'uacf7_conversational_item_wrap uacf7-first-item' );

			// Add content to the fixed wrapper (Heading and Subheading)
			$heading = $dom->createElement( 'h4', $uacf7_conversational_heading );
			$heading->setAttribute( 'class', 'uacf7-heading' );
			$subHeading = $dom->createElement( 'span', $uacf7_conversational_subheading );

			// Append the heading and subheading to the fixed wrapper
			$fixedWrapper->appendChild( $heading );
			$fixedWrapper->appendChild( $subHeading );

			// Add the fixed wrapper to the fragment as the first element
			$fragment->appendChild( $fixedWrapper );

			$childNodes = iterator_to_array( $outerDiv->childNodes ); // Convert child nodes to an array for easier manipulation

			// Iterate over child nodes of the outer div
			foreach ( $childNodes as $index => $childNode ) {
				// Skip empty text nodes
				if ( $childNode->nodeType === XML_TEXT_NODE && trim( $childNode->nodeValue ) === '' ) {
					continue;
				}

				// Create a new wrapper div for each child node
				$wrapper = $dom->createElement( 'div' );
				$wrapper->setAttribute( 'class', 'uacf7_conversational_item_wrap' );

				// If it's the last child, handle it differently
				if ( $index === count( $childNodes ) - 1 ) {
					// This is the last child, wrap it with the last-item class
					$wrapper->setAttribute( 'class', 'uacf7_conversational_item_wrap uacf7-last-item' );

					// Append the last child to this wrapper
					$wrapper->appendChild( $childNode->cloneNode( true ) ); // Clone to avoid removing from the original

					// Append this wrapper to the fragment
					$fragment->appendChild( $wrapper );
				} else {
					// For all other children, append their own wrapper
					$wrapper->appendChild( $childNode->cloneNode( true ) ); // Clone to avoid removing from the original
					$fragment->appendChild( $wrapper ); // Append the single child wrapper to the fragment
				}
			}

			// Clear the outer div and append the new fragment containing wrapped children
			// $outerDiv->nodeValue = ''; // Clear the content

			$this->clearElementContent( $outerDiv );
			$outerDiv->appendChild( $fragment );

			// Save the updated HTML structure
			$form = $dom->saveHTML( $outerDiv ); // Save the outer div which now contains the new wrappers

			wp_localize_script( 'uacf7-conversational', 'conversational_ajax',
			array(
				'admin_url' => get_admin_url() . '/admin.php',
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin_dir_url' => plugin_dir_url( __FILE__ ),
				'nonce' => wp_create_nonce( 'uacf7_conversational' ),
				'conversational_thanks' => $uacf7_conversational_thanksheading,
			)
		);
			return '<div class="uacf7_conversational_form_wrap">' . $form . '</div>'; // Return the modified form
		} else {
			return $form; // Return the modified form
		}
	}

	// Function to remove all child nodes of a DOM element
	public function clearElementContent( $element ) {
		while ( $element->hasChildNodes() ) {
			$element->removeChild( $element->firstChild );
		}
	}

	public function uacf7_conversational_enqueue_script() {

		wp_enqueue_style( 'uacf7-conversational-form-style', plugin_dir_url( __FILE__ ) . 'assets/css/uacf7-conversational.css' );

		wp_enqueue_script( 'uacf7-conversational', plugin_dir_url( __FILE__ ) . 'assets/js/uacf7-conversational.js', array( 'jquery' ), null, true );
	}

	// Validate field 
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
					'into' => 'span.wpcf7-form-control-wrap[data-name = ' . esc_attr( $value ) . ']',
					'message' => $field['message'],
					'idref' => $field['idref'],
				);
			}
		}
		return $invalid_fields;
	}

	public function uacf7_cons_fields_validation() {

		if ( ! wp_verify_nonce( $_REQUEST['ajax_nonce'], 'uacf7_conversational' ) ) {
			exit( esc_html__( "Security error", 'ultimate-addons-cf7' ) );
		}

		// Extract and sanitize input data
		$validation_fields = array_map( 'sanitize_text_field', explode( ',', $_REQUEST['validation_fields'] ) );
		$form_id = intval( $_REQUEST['form_id'] );

		$tag_name = [];
		$tag_validation = [];
		$tag_type = [];
		$file_error = [];
		$count = '1';
		for ( $x = 0; $x < count( $validation_fields ); $x++ ) {
			$field = explode( ':', $validation_fields[ $x ] );
			$name = isset( $field[1] ) ? $field[1] : '';
			$name_array = explode( "__", $name );
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
					if ( $file_size > $tag->get_limit_option() ) {
						$file_error = array(
							'into' => 'span.wpcf7-form-control-wrap[data-name = ' . esc_attr( $tag->name ) . ']',
							'message' => 'The uploaded file is too large.',
							'idref' => null,
						);
					}
				}
			}
		}

		// Process validation results
		$is_valid = $result->is_valid();
		if ( ! $is_valid ) {
			$invalid_fields = $this->prepare_invalid_form_fields( $result, $tag_validation );
		}
		if ( ! empty( $file_error ) ) {
			$invalid_fields[] = $file_error;
		}
		if ( ! empty( $invalid_fields ) ) {
			$is_valid = false;
		} else {
			$invalid_fields = false;
		}

		wp_send_json( [ 
			'is_valid' => $is_valid,
			'invalid_fields' => $invalid_fields,
		] );
		
		wp_die();
	}
}




/**
 * initiate UACF7 Conversational Form Class
 * @author   MhemelHasan <hemel>
 * @since    1.7.3
 */
new UACF7_CONVERSATIONAL_FORM();