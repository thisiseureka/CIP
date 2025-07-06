<?php

/*
 * Star Review Option added on Review post meta
 * @author M Hemel Hasan
 */

add_filter( 'uacf7_post_meta_review_opt', 'uacf7_post_meta_review_opt', 12, 2 );


// Add Star Review Options
function uacf7_post_meta_review_opt( $value, $post_id ) {

	// var_dump( $post_id );
	$uacf7_review_opt = get_post_meta( $post_id, 'uacf7_review_opt', true );
	$form_id = isset( $uacf7_review_opt['review_metabox']['uacf7_review_form_id'] ) ? $uacf7_review_opt['review_metabox']['uacf7_review_form_id'] : 0;

	$reviewmeta = apply_filters( 'uacf7_post_meta_review_opt_pro', $data = [ 
		'title' => __( 'UACF7 Review Options', 'ultimate-addons-cf7' ),
		'icon' => 'far fa-star',
		'fields' => [ 

			'uacf7_post_meta_review_opt_headding' => [ 
				'id' => 'uacf7_post_meta_review_opt_headding',
				'type' => 'heading',
				'label' => __( 'Review Option', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'This option help you to set review all options.', 'ultimate-addons-cf7' ),
				'content' => sprintf(
					__( 'Not sure how to set this? Check our step by step  %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-star-rating-field-pro/" target="_blank">documentation</a>'
				)
			],

			'uacf7_hide_disable_review' => [ 
				'id' => 'uacf7_hide_disable_review',
				'type' => 'switch',
				'label' => __( ' Disable Auto Publish ', 'ultimate-addons-cf7' ),
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'field_width' => 33,
				'default' => false
			],

			'uacf7_show_review_form' => [ 
				'id' => 'uacf7_show_review_form',
				'type' => 'switch',
				'label' => __( ' Show Form ', 'ultimate-addons-cf7' ),
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'field_width' => 33,
				'default' => false
			],

			'uacf7_review_carousel' => [ 
				'id' => 'uacf7_review_carousel',
				'type' => 'switch',
				'label' => __( ' Carousel ', 'ultimate-addons-cf7' ),
				'label_on' => __( 'Yes', 'ultimate-addons-cf7' ),
				'label_off' => __( 'No', 'ultimate-addons-cf7' ),
				'field_width' => 33,
				'default' => false
			],

			'uacf7_review_text_align' => [ 
				'id' => 'uacf7_review_text_align',
				'type' => 'radio',
				// 'class' => 'padding-bottom0',
				'label' => __( 'Text Align', 'ultimate-addons-cf7' ),
				'options' => [ 
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right',
				],
				'default' => 'left',
				'inline' => true,
			],

			'uacf7_review_form_id' => [ 
				'id' => 'uacf7_review_form_id',
				'type' => 'select',
				'label' => __( 'Select A Form', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select A Form', 'ultimate-addons-cf7' ),
				'options' => 'posts',
				'query_args' => [ 
					'post_type' => 'wpcf7_contact_form',
					'posts_per_page' => -1,
				],
				'field_width' => 50,
			],

			'uacf7_reviewer_name' => [ 
				'id' => 'uacf7_reviewer_name',
				'type' => 'select',
				'label' => __( 'Reviewer Name', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select Reviewer Name', 'ultimate-addons-cf7' ),
				'options' => 'uacf7',
				'query_args' => [ 
					'post_id' => $form_id,
					'exclude' => [ 'submit', 'conditional' ],
				],
				'field_width' => 50,
			],

			'uacf7_reviewer_image' => [ 
				'id' => 'uacf7_reviewer_image',
				'type' => 'select',
				'label' => __( 'Reviewer Image', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select Reviewer Image', 'ultimate-addons-cf7' ),
				'options' => 'uacf7',
				'query_args' => [ 
					'post_id' => $form_id,
					'exclude' => [ 'submit', 'conditional' ],
				],
				'field_width' => 50,
			],

			'uacf7_review_title' => [ 
				'id' => 'uacf7_review_title',
				'type' => 'select',
				'label' => __( 'Review Title', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select Review Title', 'ultimate-addons-cf7' ),
				'options' => 'uacf7',
				'query_args' => [ 
					'post_id' => $form_id,
					'exclude' => [ 'submit', 'conditional' ],
				],
				'field_width' => 50,
			],

			'uacf7_review_rating' => [ 
				'id' => 'uacf7_review_rating',
				'type' => 'select',
				'label' => __( 'Review Rating', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select Review Rating', 'ultimate-addons-cf7' ),
				'options' => 'uacf7',
				'query_args' => [ 
					'post_id' => $form_id,
					'exclude' => [ 'submit', 'conditional' ],
				],
				'field_width' => 50,
			],

			'uacf7_review_desc' => [ 
				'id' => 'uacf7_review_desc',
				'type' => 'select',
				'label' => __( 'Review Desc', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select Review Desc', 'ultimate-addons-cf7' ),
				'options' => 'uacf7',
				'query_args' => [ 
					'post_id' => $form_id,
					'exclude' => [ 'submit', 'conditional' ],
				],
				'field_width' => 50,
			],

			'uacf7_review_extra_class' => [ 
				'id' => 'uacf7_review_extra_class',
				'type' => 'text',
				'label' => __( 'Extra Class', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Enter extra class', 'ultimate-addons-cf7' ),
				'field_width' => 50,
			],

			'uacf7_review_column' => [ 
				'id' => 'uacf7_review_column',
				'type' => 'select',
				'label' => __( 'Column', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Select Reviews Column', 'ultimate-addons-cf7' ),
				'options' => [ 
					'1' => 'Column',
					'2' => '2 Column',
					'3' => '3 Column',
					'4' => '4 Column',
				],

				'field_width' => 50,
			],
		],
	], $post_id );
	$value['review_metabox'] = $reviewmeta;
	return $value;
}


/*
 * Get Form Tag using ajax
 */
if ( ! function_exists( 'uacf7_ajax_star_rating_form_tag' ) ) {
	function uacf7_ajax_star_rating_form_tag() {
		$form_id = $_POST['form_id'];
		$ContactForm = WPCF7_ContactForm::get_instance( $form_id );
		$form_fields = $ContactForm->scan_form_tags();
		$options = '<option value="">Select Field</option>';
		foreach ( $form_fields as $tag ) {
			if ( $tag['type'] != 'submit' ) {
				$options .= '<option value="' . esc_attr( $tag['name'] ) . '" >' . esc_attr( $tag['name'] ) . '</option>';
			}
		}
		echo $options;
		wp_die();
	}
	add_action( 'wp_ajax_uacf7_ajax_star_rating_form_tag', 'uacf7_ajax_star_rating_form_tag' );
	add_action( 'wp_ajax_nopriv_uacf7_ajax_star_rating_form_tag', 'uacf7_ajax_star_rating_form_tag' );
}



?>