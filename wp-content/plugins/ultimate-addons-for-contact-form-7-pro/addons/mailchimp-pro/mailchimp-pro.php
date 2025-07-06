<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** initialise the UACF7 Mailhimp Pro class
 * MAILCHIMP_PRO Pro prefix $uacf7
 * @author M Hemel hasan
 * @return MAILCHIMP_PRO
 */
class UACF7_MAILCHIMP_PRO {
	public function __construct() {
		if ( class_exists( 'Ultimate_Addons_CF7_PRO' ) && apply_filters( 'uacf7_checked_license_status', '' ) != false ) {
			add_filter( 'uacf7_post_meta_options_mailchimp_pro', [ $this, 'uacf7_post_meta_options_mailchimp_pro' ], 24, 2 );

			// Form tag ShortCodes
			add_action( 'wpcf7_init', [ $this, 'add_shortcodes' ] );
			// Tag Generator 
			add_action( 'admin_init', [ $this, 'tag_generator' ] );
			// Tag Validation
			add_filter( 'wpcf7_validate_uacf7_mailchimp_acceptance', [ $this, 'uacf7_mailchimp_checkbox_validation_filter' ], 10, 2 );
			add_filter( 'wpcf7_validate_uacf7_mailchimp_acceptance*', [ $this, 'uacf7_mailchimp_checkbox_validation_filter' ], 10, 2 );

			add_filter( 'uacf7_mailchimp_subscribe_info_sent', [ $this, 'uacf7_mailchimp_subscribe_checkbox_status' ], 10, 2 );
		}
	}

	/** Enable UACF7 Mailhimp Pro class
	 * @return Fields
	 */
	public function uacf7_post_meta_options_mailchimp_pro( $data, $post_id ) {
		$data['fields']['uacf7_mailchimp_form_acceptance']['is_pro'] = false;
		return $data;
	}

	/**
	 * Form tag ShortCode
	 * @param array $options
	 * @return array
	 * @since 1.7.2
	 */
	public function add_shortcodes() {
		wpcf7_add_form_tag( [ 'uacf7_mailchimp_acceptance', 'uacf7_mailchimp_acceptance*' ],
			[ $this, 'tag_handler_callback' ], [ 'name-attr' => true ] );
	}

	/**
	 * Form tag ShortCode Call back handler
	 * @return array
	 * @since 1.7.2
	 */
	public function tag_handler_callback( $tag ) {

		if ( empty( $tag->name ) ) {
			return '';
		}

		$validation_error = wpcf7_get_validation_error( $tag->name );
		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		if ( in_array( 'checked', $tag->options ) ) {
			$atts['checked'] = 'checked';
		}
		$atts[''] = $tag->get_class_option( $class );
		$atts['class'] = $tag->get_class_option( $class );
		$atts['id'] = $tag->get_id_option();
		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$atts['name'] = $tag->name;

		$size = $tag->get_option( 'size', 'int', true );

		if ( $size ) {
			$atts['size'] = $size;
		}

		$value = ! empty( $tag->get_option( 'value', '', true ) ) ? $tag->get_option( 'value', '', true ) : '1';
		$checked = ( in_array( 'checked', $tag->options ) ) ? 'checked' : '';

		$mailchimp_attr = apply_filters( 'uacf7_get_country_attr', $atts, $tag );
		$atts = wpcf7_format_atts( $mailchimp_attr );

		ob_start(); ?>

		<span class="wpcf7-form-control-wrap" data-name="<?php echo esc_attr( $tag->name ) ?>" style="display: inline-block;">
			<span <?php echo esc_attr( $atts ) ?>>
				<span class=" wpcf7-list-item first last">
					<input class="<?php echo esc_attr( $tag->get_class_option( $class ) ) ?>" type="checkbox" <?php echo esc_attr( $checked ) ?> name="<?php echo esc_attr( $tag->name ) ?>"
						value="<?php echo esc_attr( $value ) ?>">
				</span>
			</span>
		</span>

		<?php
		return ob_get_clean();
	}

	/**
	 * Form tag generator
	 * @since 1.7.2
	 */
	public function tag_generator() {

		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'uacf7_mailchimp_acceptance',
			__( 'Mailchimp Acceptance', 'ultimate-addons-cf7' ),
			array( $this, 'tg_panel_mailchimp_subscribe_checkbox' ),
			array( 'version' => '2' )
		);

	}

	/**
	 * Mailchimp Subscribe Checkbox Tag Generator Panel
	 * @since 1.7.2
	 */
	public function tg_panel_mailchimp_subscribe_checkbox( $contact_form, $options ) {
		$field_types = array(
			'uacf7_mailchimp_acceptance' => array(
				'display_name' => __( 'Mailchimp Acceptance', 'ultimate-addons-cf7' ),
				'heading' => __( 'Mailchimp Acceptance', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );

		$id = $contact_form->id();

		// Get Mailchimp settings from the form options
		$mailchimp = uacf7_get_form_option( $id, 'mailchimp' );

		$mailchimp_enable = isset( $mailchimp['uacf7_mailchimp_form_enable'] ) ? $mailchimp['uacf7_mailchimp_form_enable'] : '';
		$mailchimp_acceptance_enable = isset( $mailchimp['uacf7_mailchimp_form_acceptance'] ) ? $mailchimp['uacf7_mailchimp_form_acceptance'] : '';


		// Check if both mailchimp_enable and mailchimp_acceptance_enable are true
		if ( $mailchimp_enable && $mailchimp_acceptance_enable ) {
			?>
			<header class="description-box">
				<h3><?php
				echo esc_html( $field_types['uacf7_mailchimp_acceptance']['heading'] );
				?></h3>

				<p><?php
				$description = wp_kses(
					$field_types['uacf7_mailchimp_acceptance']['description'],
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
						__( 'Not sure how to set this? Check our step by step %1s.', 'ultimate-addons-cf7' ),
						'<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-star-rating-field/" target="_blank">documentation</a>'
					); ?>
				</div>
			</header>
			<div class="control-box">
				<?php

				$tgg->print( 'field_type', array(
					'with_required' => true,
					'select_options' => array(
						'uacf7_mailchimp_acceptance' => $field_types['uacf7_mailchimp_acceptance']['display_name'],
					),
				) );

				$tgg->print( 'field_name' );
				?>

				<fieldset>
					<legend class="screen-reader-text">
						<?php _e( 'Mark the checkbox as selected by default', 'ultimate-addons-cf7' ); ?>
					</legend>

					<input type="checkbox" data-tag-part="option" data-tag-option="checked" name="checked" />
					<?php _e( 'Mark the checkbox as selected by default', 'ultimate-addons-cf7' ); ?>

				</fieldset>
			</div>

			<footer class="insert-box">
				<?php
				$tgg->print( 'insert_box_content' );

				$tgg->print( 'mail_tag_tip' );
				?>
			</footer>
			<?php
		} else {
			// Show a message when either Mailchimp or acceptance isn't enabled
			echo '<header class="description-box"><div class="uacf7-warning-message">';
			echo '<p>' . __( 'Please enable Mailchimp and Acceptance to use this feature.<br> If you already active then please save the form first to use this feature', 'ultimate-addons-cf7'
			);
			echo '</p>';
			echo '</div>';
			echo '</header>';
		}

	}

	/**
	 * Tag Validation
	 * @since 1.7.2
	 */
	public function uacf7_mailchimp_checkbox_validation_filter( $result, $tag ) {
		$name = $tag->name;

		if ( isset( $_POST[ $name ] )
			and is_array( $_POST[ $name ] ) ) {
			foreach ( $_POST[ $name ] as $key => $value ) {
				if ( '' === $value ) {
					unset( $_POST[ $name ][ $key ] );
				}
			}
		}

		$empty = ! isset( $_POST[ $name ] ) || empty( $_POST[ $name ] ) && '0' !== $_POST[ $name ];

		if ( $tag->is_required() and $empty ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
		}

		return $result;
	}

	/**
	 * Check if this checked or not
	 * @since 1.7.2
	 */
	public function uacf7_mailchimp_subscribe_checkbox_status( $id, $wpcf ) {

		$posted_data = $wpcf->get_posted_data();

		// Get Mailchimp settings from the form options
		$mailchimp = uacf7_get_form_option( $id, 'mailchimp' );

		$mailchimp_enable = isset( $mailchimp['uacf7_mailchimp_form_enable'] ) ? $mailchimp['uacf7_mailchimp_form_enable'] : '';
		$mailchimp_acceptance_enable = isset( $mailchimp['uacf7_mailchimp_form_acceptance'] ) ? $mailchimp['uacf7_mailchimp_form_acceptance'] : '';


		$tags = $wpcf->get_contact_form()->scan_form_tags();
		$tag_name = '';
		foreach ( $tags as $tag ) {
			if ( $tag->basetype == 'uacf7_mailchimp_acceptance' ) {
				$tag_name = $tag->name;
			}
		}

		if ( $mailchimp_enable && $mailchimp_acceptance_enable ) {
			if ( $tag_name != '' && isset( $posted_data[ $tag_name ] ) && $posted_data[ $tag_name ] != '' ) {
				$status = true;
			} else {
				$status = false;
			}
		} else {
			$status = true;
		}

		return $status;
	}
}

new UACF7_MAILCHIMP_PRO();