<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_POST_SUBMISSION {

	private $hidden_fields = array();
	private $user_info = null; 

	/*
	 * Construct function
	 */
	public function __construct() {
		// add_action( 'wpcf7_init', array( $this, 'add_shortcodes' ) );
		$this->add_shortcodes();
		add_action( 'admin_init', array( $this, 'tag_generator' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );

		// add_action( 'wpcf7_editor_panels', array( $this, 'uacf7_cf_add_panel' ) );

		add_filter( 'wpcf7_validate_uacf7_post_title', array( $this, 'uacf7_post_submission_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_title*', array( $this, 'uacf7_post_submission_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_taxonomy', array( $this, 'uacf7_post_submission_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_taxonomy*', array( $this, 'uacf7_post_submission_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_content', array( $this, 'uacf7_post_submission_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_content*', array( $this, 'uacf7_post_submission_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_thumbnail', array( $this, 'uacf7_post_submission_thumbnail_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_post_thumbnail*', array( $this, 'uacf7_post_submission_thumbnail_validation_filter' ), 10, 2 );

		// Not needed 
		// add_action( 'wpcf7_after_save', array( $this, 'uacf7_save_contact_form' ) );

		add_action( 'wpcf7_before_send_mail', array( $this, 'process_post_submit' ) );

		//  For Generator Ai post submission hook
		add_filter( 'uacf7_blog_submission_ai_form_dropdown', array( $this, 'uacf7_blog_submission_ai_form_dropdown' ), 10, 2 );

		add_filter( 'uacf7_post_submission_form_ai_generator', array( $this, 'uacf7_post_submission_form_ai_generator' ), 10, 2 );

		add_filter( 'wpcf7_validate', array( $this, 'validate_guest_submission' ), 10, 2 );

		// add_filter( 'wpcf7_load_js', '__return_false' );

		$this->load_user_info();

	}

	private function load_user_info() {
        if ( is_user_logged_in() ) {
            $this->user_info = wp_get_current_user(); // Retrieves the logged-in user info
        } else {
            $this->user_info = null; // No user info for non-logged-in users
        }
    }

    // Getter for user info (returns user object if logged in, null otherwise)
    public function get_user_info() {
        return $this->user_info;
    }


	public function add_shortcodes() {

		wpcf7_add_form_tag( array( 'uacf7_post_content', 'uacf7_post_content*' ), array( $this, 'uacf7_post_content' ), true );

		wpcf7_add_form_tag( array( 'uacf7_post_thumbnail', 'uacf7_post_thumbnail*' ), array( $this, 'uacf7_post_thumbnail' ), true );

		wpcf7_add_form_tag( array( 'uacf7_post_title', 'uacf7_post_title*' ), array( $this, 'uacf7_post_title' ), true );

		wpcf7_add_form_tag( array( 'uacf7_post_taxonomy', 'uacf7_post_taxonomy*' ),
			array( $this, 'uacf7_post_taxonomy' ), true );
	}

	/*
	 * Generate tag
	 */
	public function tag_generator() {

		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'uacf7_post_title',
			__( 'Post Title', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_post_title' ),
			array( 'version' => '2' )
		);
		$tag_generator->add(
			'uacf7_post_content',
			__( 'Post Content', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_post_content' ),
			array( 'version' => '2' )
		);
		$tag_generator->add(
			'uacf7_post_thumbnail',
			__( 'Post Thumbnail', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_post_thumbnail' ),
			array( 'version' => '2' )
		);

		$tag_generator->add(
			'uacf7_post_taxonomy',
			__( 'Post Taxonomy/Category', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_post_taxonomy' ),
			array( 'version' => '2' )
		);

	}

	/*
	 * Enqueue scripts
	 */
	public function enqueue_script() {

		wp_enqueue_style( 'uacf7-post-submission', plugin_dir_url( __FILE__ ) . '../assets/post-submission.css' );

		wp_enqueue_style( 'uacf7-select2-style', plugin_dir_url( __FILE__ ) . '../assets/select2.min.css' );

		wp_enqueue_script( 'uacf7-select2', plugin_dir_url( __FILE__ ) . '../assets/select2.js', array( 'jquery' ), null, true );

		wp_enqueue_script( 'uacf7-post-submission-script', plugin_dir_url( __FILE__ ) . '../assets/script.js', array( 'jquery' ), null, true );
	}

	/*
	 * Create tab panel
	 */
	public function uacf7_cf_add_panel( $panels ) {

		$panels['uacf7-post-submission-panel'] = array(
			'title' => __( 'Ultimate Post Submission', 'ultimate-post-submission' ),
			'callback' => array( $this, 'uacf7_create_post_submission_panel_fields' ),
		);
		return $panels;
	}

	public function uacf7_create_post_submission_panel_fields( $post ) {

		$nonrequired_tags = $post->scan_form_tags( array( 'type' => 'uacf7_post_taxonomy' ) );

		$required_tags = $post->scan_form_tags( array( 'type' => 'uacf7_post_taxonomy*' ) );

		$all_tags = array_merge( $nonrequired_tags, $required_tags );

		//$tag->get_option( 'tabindex', 'signed_int', true );
		$tax_names = array();
		$name_and_taxonomy = array();
		foreach ( $all_tags as $tag ) {

			$name_and_taxonomy[ $tag['name'] ] = $tag->get_option( 'tax', '', true );

			$tax_names[] = $tag['name'];

		}

		update_post_meta( $post->id(), 'tax_names', $tax_names );
		update_post_meta( $post->id(), 'post_taxonomies', $name_and_taxonomy );

		?>
		<h2>Post submission form</h2>
		<?php $enable_post_submission = get_post_meta( $post->id(), 'enable_post_submission', true ); ?>
		<input type="checkbox" name="enable_post_submission" value="yes" <?php checked( 'yes', $enable_post_submission ); ?>>
		Enable

		<div class="uacf7-doc-notice">
			<?php echo sprintf(
				__( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
				'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-to-post-type/" target="_blank">documentation</a>'
			); ?>
		</div>

		<h2>Select post type</h2>

		<?php
		$post_types = get_post_types();
		$saved_post = ! empty( get_post_meta( $post->id(), 'post_submission_post_type', true ) ) ? get_post_meta( $post->id(), 'post_submission_post_type', true ) : 'post';

		if ( $post_types ) { // If there are any custom public post types.
			?>
			<select name="post_submission_post_type">
				<?php
				foreach ( $post_types as $post_type ) {
					?>
					<option value="<?php echo $post_type; ?>" <?php selected( $post_type, $saved_post ); ?>> <?php echo $post_type; ?>
					</option>
					<?php
				}
				?>
			</select>
			<?php
		}

		$post_status = ! empty( get_post_meta( $post->id(), 'post_submission_post_status', true ) ) ? get_post_meta( $post->id(), 'post_submission_post_status', true ) : 'publish';
		?>
		<h2>Post status</h2>
		<select name="post_submission_post_status">
			<option value="publish" <?php selected( 'publish', $post_status ); ?>>Publish</option>
			<option value="draft" <?php selected( 'draft', $post_status ); ?>>Draft</option>
			<option value="pending" <?php selected( 'pending', $post_status ); ?>>Pending</option>
		</select>

		<?php
		wp_nonce_field( 'uacf7_post_submission_nonce_action', 'uacf7_post_submission_nonce' );

	}

	/*
	 * Check validation for custom form fields
	 */
	public function uacf7_post_submission_fields_validation_filter( $result, $tag ) {
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

	/*
	 * Check validation for file uploading field
	 */
	public function uacf7_post_submission_thumbnail_validation_filter( $result, $tag ) {
		$name = $tag->name;
		$id = $tag->get_id_option();

		$file = isset( $_FILES[ $name ] ) ? $_FILES[ $name ] : null;

		// if ( $file && isset( $file['error'] ) && UPLOAD_ERR_NO_FILE !== $file['error'] ) {
		// 	$result->invalidate( $tag, wpcf7_get_message( 'upload_failed_php_error' ) );
		// 	return $result;
		// }

		// if ( is_null( $file ) ) {
		// 	// Log and handle the case where no file is provided
		// 	error_log( "File variable is null" );
		// 	$result->invalidate( $tag, 'No file was uploaded.' );
		// 	return $result;
		// }

		// if ( isset( $file['error'] ) ) {
		// 	if ( UPLOAD_ERR_NO_FILE !== $file['error'] ) {
		// 		// Log the specific file error for debugging
		// 		error_log( "File upload error: " . $file['error'] );

		// 		// Invalidate the form field with a specific error message
		// 		$result->invalidate( $tag, wpcf7_get_message( 'upload_failed_php_error' ) );
		// 		return $result;
		// 	}
		// } else {
		// 	// Handle the case where 'error' is not set in the file array
		// 	error_log( "File error key is not set" );
		// 	$result->invalidate( $tag, 'File upload error: Unknown error.' );
		// 	return $result;
		// }

		if ( empty( $file['tmp_name'] ) and $tag->is_required() ) {
			$result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
			return $result;
		}

		if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
			return $result;
		}

		/* File type validation */
		$file_type_pattern = wpcf7_acceptable_filetypes(
			$tag->get_option( 'filetypes' ), 'regex' );

		$file_type_pattern = '/\.(' . $file_type_pattern . ')$/i';

		if ( ! preg_match( $file_type_pattern, $file['name'] ) ) {
			$result->invalidate( $tag,
				wpcf7_get_message( 'upload_file_type_invalid' ) );
			return $result;
		}

		/* File size validation */

		$allowed_size = $tag->get_limit_option();

		if ( $allowed_size < $file['size'] ) {
			$result->invalidate( $tag, wpcf7_get_message( 'upload_file_too_large' ) );
			return $result;
		}

		return $result;
	}

	/*
	 * Save from fields
	 */
	public function uacf7_save_contact_form( $form ) {

		if ( ! isset( $_POST ) || empty( $_POST ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['uacf7_post_submission_nonce'], 'uacf7_post_submission_nonce_action' ) ) {
			return;
		}

		update_post_meta( $form->id(), 'post_submission_post_type', $_POST['post_submission_post_type'] );

		update_post_meta( $form->id(), 'enable_post_submission', $_POST['enable_post_submission'] );

		update_post_meta( $form->id(), 'post_submission_post_status', $_POST['post_submission_post_status'] );
	}

	/*
	 * Field: Post taxonomy
	 */
	public function uacf7_post_taxonomy( $tag ) {

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$class .= ' uacf7_post_taxonomy';

		$atts['class'] = $tag->get_class_option( $class );

		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$multiple = $tag->has_option( 'multiple' );

		if ( $multiple ) {

			$atts['multiple'] = 'multiple';

			$atts['data-placeholder'] = esc_attr__( 'Select category', 'ultimate-post-submission' );

		}

		//Field name
		$field_name = $tag->name;

		$taxonomy = $tag->get_option( 'tax', '', true );

		$atts = wpcf7_format_atts( $atts );

		$drop_down_category = '<span class="wpcf7-form-control-wrap uacf7_post_taxonomy_wraper ' . $field_name . '">';

		$drop_down_category .= wp_dropdown_categories(
			array(
				'show_option_none' => __( '', 'ultimate-post-submission' ),
				'hierarchical' => 1,
				'hide_empty' => 0,
				'name' => $tag->name . '[]',
				'id' => $field_name,
				'taxonomy' => $taxonomy,
				'echo' => 0,
			)
		);

		$drop_down_category .= $validation_error . '</span>';

		$html = str_replace( '<select', '<select ' . $atts, $drop_down_category );

		return $html;
	}

	/*
	 * Field: Post title
	 */
	public function uacf7_post_title( $tag ) {
		ob_start();

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$atts['class'] = $tag->get_class_option( $class );

		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );


		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$atts = wpcf7_format_atts( $atts );

		echo '<span class="wpcf7-form-control-wrap post_title">';

		echo '<input type="text" size="40" name="post_title" ' . $atts . '>';

		echo $validation_error . '</span>';

		return ob_get_clean();
	}

	/*
	 * Field: Post content
	 */
	public function uacf7_post_content( $tag ) {
		ob_start();

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$atts['class'] = $tag->get_class_option( $class );

		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$atts = wpcf7_format_atts( $atts );

		$args = array(
			'tinymce' => array(
				'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo'
			)
		);

		echo '<span class="wpcf7-form-control-wrap post_content">';

		wp_editor( '', 'uacf7_post_content', array( 'textarea_name' => 'post_content', 'media_buttons' => true, 'quicktags' => true, 'editor_height' => 250, 'teeny' => true, 'editor_class' => $class ) );

		//echo '<textarea name="post_content" cols="30" rows="10" '.$atts.'></textarea>';

		echo $validation_error . '</span>';

		return ob_get_clean();
	}

	/*
	 * Field: Post thumbnail
	 */
	function uacf7_post_thumbnail( $tag ) {
		ob_start();

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$atts['class'] = $tag->get_class_option( $class );

		$atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$atts = wpcf7_format_atts( $atts );

		echo '<span class="wpcf7-form-control-wrap post_thumbnail">';

		echo '<input type="file" size="40" name="post_thumbnail" ' . $atts . '>';

		echo $validation_error . '</span>';

		return ob_get_clean();
	}

	/*
	 * Tag generators
	 */
	static function tg_pane_post_taxonomy( $contact_form, $options ) {
		$field_types = array(
			'uacf7_post_taxonomy' => array(
				'display_name' => __( 'Post Taxonomy/ Category', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Post Taxonomy / Category', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_post_taxonomy']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_post_taxonomy']['description'],
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
					__( 'Confused? Check our Documentation on  %1s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-to-post-type/" target="_blank">Post Submission</a>'
				); ?>
			</div>
			<div class="uacf7-doc-notice uacf7-guide">
				<?php echo _e( 'To activate the feature, enable it from the "Post Submission" tab located under the Ultimate Addons for CF7 Options. This tab also contains additional settings.', 'ultimate-addons-cf7' ) ?>
			</div>
		</header>
		<div class="control-box">
			<?php

			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_post_taxonomy' => $field_types['uacf7_post_taxonomy']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );
			?>
			<fieldset>
				<legend>
					<?php echo _e( 'Taxonomy name', 'ultimate-addons-cf7'); ?>
				</legend>

				<input type="text" data-tag-part="option" data-tag-option="tax:" name="tax" value="category" id="tag-generator-panel-text-name" />
				<div>
					<input type="checkbox" data-tag-part="option" data-tag-option="multiple" name="multiple"  id="tag_generator_panel_select_multiple" /> 
					Allow Multiple Selection
				</div>
			</fieldset>

			<?php $tgg->print( 'class_attr' ); ?>
		</div>

		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );
			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}

	static function tg_pane_post_title( $contact_form, $options ) {
		$field_types = array(
			'uacf7_post_title' => array(
				'display_name' => __( 'Post Title', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Post Title', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_post_title']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_post_title']['description'],
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
					__( 'Confused? Check our Documentation on  %1s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-to-post-type/" target="_blank">Post Submission</a>'
				); ?>
			</div>

			<div class="uacf7-doc-notice uacf7-guide">
				<?php echo esc_html__( 'To activate the feature, enable it from the "Post Submission" tab
					located under the Ultimate Addons for CF7 Options. This tab also contains additional settings.', 'ultimate-addons-cf7' );
				?>
			</div>
		</header>
		<div class="control-box">
			<?php

			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_post_title' => $field_types['uacf7_post_title']['display_name'],
				),
			) );
			?>

			<fieldset>
				<legend>
					<?php _e( 'Name', 'ultimate-addons-cf7' ); ?>
				</legend>
				<input type="text" data-tag-part="option" data-tag-option="post_title" placeholder="post_title" readonly>
			</fieldset>

			<?php $tgg->print( 'class_attr' ); ?>
		</div>

		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}

	static function tg_pane_post_content( $contact_form, $options ) {
		$field_types = array(
			'uacf7_post_content' => array(
				'display_name' => __( 'Post Content', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Post Content', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_post_content']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_post_content']['description'],
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
					__( 'Confused? Check our Documentation on  %1s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-to-post-type/" target="_blank">Post Submission</a>'
				); ?>
			</div>

			<div class="uacf7-doc-notice uacf7-guide">
				<?php echo esc_html__( 'To activate the feature, enable it from the "Post Submission" tab
					located under the Ultimate Addons for CF7 Options. This tab also contains additional settings.', 'ultimate-addons-cf7' );
				?>
			</div>
		</header>
		<div class="control-box">
			<?php

			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_post_content' => $field_types['uacf7_post_content']['display_name'],
				),
			) );

			?>

			<fieldset>
				<legend>
					<?php _e( 'Name', 'ultimate-addons-cf7' ); ?>
				</legend>
				<input type="text" data-tag-part="option" data-tag-option="post_content" placeholder="post_content" readonly>
			</fieldset>

			<?php $tgg->print( 'class_attr' ); ?>
		</div>

		<footer class="insert-box">
			<?php
			$tgg->print( 'insert_box_content' );

			$tgg->print( 'mail_tag_tip' );
			?>
		</footer>
		<?php
	}

	static function tg_pane_post_thumbnail( $contact_form, $options ) {
		$field_types = array(
			'uacf7_post_thumbnail' => array(
				'display_name' => __( 'Post Thumbnail', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Post Thumbnail', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>
		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_post_thumbnail']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_post_thumbnail']['description'],
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
					__( 'Confused? Check our Documentation on  %1s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-to-post-type/" target="_blank">Post Submission</a>'
				); ?>
			</div>
			<div class="uacf7-doc-notice uacf7-guide">
				<?php echo _e( 'To activate the feature, enable it from the Post Submission tab
					located under the Ultimate Addons for CF7 Options. This tab also contains additional settings.', 'ultimate-addons-cf7' ) ?>
			</div>
		</header>
		<div class="control-box">
			<?php
			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_post_thumbnail' => $field_types['uacf7_post_thumbnail']['display_name'],
				),
			) );
			?>

			<fieldset>
				<legend>
					<?php _e( 'Name', 'ultimate-addons-cf7' ); ?>
				</legend>
				<input type="text" data-tag-part="option" data-tag-option="post_thumbnail" placeholder="post_thumbnail"
					readonly>
			</fieldset>

			<?php $tgg->print( 'class_attr' ); ?>

			<fieldset>
				<legend id="<?php echo esc_attr( $tgg->ref( 'filetypes-option-legend' ) ); ?>"><?php
					   echo esc_html( __( 'Acceptable file types', 'ultimate-addons-cf7' ) );
					   ?></legend>
				<label><?php
				echo sprintf(
					'<span %1$s>%2$s</span><br />',
					wpcf7_format_atts( array(
						'id' => $tgg->ref( 'filetypes-option-description' ),
					) ),
					esc_html( __( "Pipe-separated file types list. You can use file extensions and MIME types.", 'ultimate-addons-cf7' ) )
				);

				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'pattern' => '[0-9a-z*\/\|]*',
						'value' => 'image/*',
						'aria-labelledby' => $tgg->ref( 'filetypes-option-legend' ),
						'aria-describedby' => $tgg->ref( 'filetypes-option-description' ),
						'data-tag-part' => 'option',
						'data-tag-option' => 'filetypes:',
					) )
				);
				?></label>
			</fieldset>

			<fieldset>
				<legend id="<?php echo esc_attr( $tgg->ref( 'limit-option-legend' ) ); ?>"><?php
					   echo esc_html( __( 'File size limit', 'ultimate-addons-cf7' ) );
					   ?></legend>
				<label><?php
				echo sprintf(
					'<span %1$s>%2$s</span><br />',
					wpcf7_format_atts( array(
						'id' => $tgg->ref( 'limit-option-description' ),
					) ),
					esc_html( __( "In bytes. You can use kb and mb suffixes.", 'ultimate-addons-cf7' ) )
				);

				echo sprintf(
					'<input %s />',
					wpcf7_format_atts( array(
						'type' => 'text',
						'pattern' => '[1-9][0-9]*([kKmM]?[bB])?',
						'value' => '1mb',
						'aria-labelledby' => $tgg->ref( 'limit-option-legend' ),
						'aria-describedby' => $tgg->ref( 'limit-option-description' ),
						'data-tag-part' => 'option',
						'data-tag-option' => 'limit:',
					) )
				);
				?></label>
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

	public function validate_guest_submission( $result, $tag ) {
		$submission = WPCF7_Submission::get_instance();
		
		if ( $submission ) {
			$cf7 = $submission->get_contact_form();
			$form_id = $cf7->id(); 
	
			// Get the form options
			$post_submission = uacf7_get_form_option( $form_id, 'post_submission' );
			$enable_post_submission = isset( $post_submission['enable_post_submission'] ) ? $post_submission['enable_post_submission'] : false;
			$enable_guest_post = isset( $post_submission['enable_guest_post'] ) ? $post_submission['enable_guest_post'] : false;

			// Check if guest post is disabled and user is not logged in
			if ( $enable_post_submission && ! $enable_guest_post && !$this->get_user_info() ) {
				$custom_message = __( 'Guest posting is disabled for this form. Please log in to submit the form.', 'ultimate-addons-cf7' );

				$result->invalidate( '', $custom_message );

				add_filter( 'wpcf7_ajax_json_echo', function( $response, $result ) use ( $custom_message ) {
					if ( isset( $response['status'] ) && $response['status'] === 'validation_failed' ) {
						$response['message'] = $custom_message;
					}
					return $response;
				}, 10, 2 );
				
			}
		}
	
		return $result;
	}

	/*
	 * Process post submission
	 */
	public function process_post_submit( $cf7 ) {

		$post_submission = uacf7_get_form_option( $cf7->id(), 'post_submission' );
		
		$enable_post_submission = isset( $post_submission['enable_post_submission'] ) ? $post_submission['enable_post_submission'] : false;

		$enable_guest_post = isset( $post_submission['enable_guest_post'] ) ? $post_submission['enable_guest_post'] : false;

		$post_publish_under = isset( $post_submission['post_publish_under'] ) ? $post_submission['post_publish_under'] : '';
		$user_id = 1;

		if ( $enable_post_submission && !$enable_guest_post && !$this->get_user_info() ) {
			$submission = WPCF7_Submission::get_instance();

			if ( $submission ) {

				$submission->set_status( 'validation_failed' );
				$response_message = __( 'Guest posting is disabled for this form. Please log in to continue.', 'ultimate-addons-cf7' );
				$submission->set_response( $response_message );

			}
	
			return;
		}
		
		if( $this->get_user_info()){
			$user_id = ($post_publish_under === 'current_user') ? $this->get_user_info()->data->ID : 1;
		}else if($enable_guest_post && !$this->get_user_info()){
			$user_id = 1;
		}


		if ( $enable_post_submission == true ) :

			$submission = WPCF7_Submission::get_instance();

			if ( $submission ) {

				$posted_data = $submission->get_posted_data();
				$title = isset( $posted_data['post_title'] ) ? $posted_data['post_title'] : '';
				$meta_input = array();
				$tax_names = array();
				$post_taxonomies = array();

				$nonrequired_tags = $cf7->scan_form_tags( array( 'type' => 'uacf7_post_taxonomy' ) );

				$required_tags = $cf7->scan_form_tags( array( 'type' => 'uacf7_post_taxonomy*' ) );

				$all_tags = array_merge( $nonrequired_tags, $required_tags );

				//$tag->get_option( 'tabindex', 'signed_int', true );

				foreach ( $all_tags as $tag ) {

					$post_taxonomies[ $tag['name'] ] = $tag->get_option( 'tax', '', true );

					$tax_names[] = $tag['name'];

				}


				foreach ( $posted_data as $keyval => $posted ) {
					
					// beaf_print_r( $keyval, $posted );
					if ( $keyval != 'post_title' && $keyval != 'post_content' && $keyval != 'post_thumbnail' && $keyval != '_wpcf7' && $keyval != '_wpcf7_version' && $keyval != '_wpcf7_locale' && $keyval != '_wpcf7_unit_tag' && $keyval != '_wpcf7_container_post' ) {

						if ( ! in_array( $keyval, $tax_names ) ) {

							$meta_input[ $keyval ] = $posted;
						}

					}
				}

				$post_type = ! empty( $post_submission['post_submission_post_type'] ) ? $post_submission['post_submission_post_type'] : 'post';
				$post_status = ! empty( $post_submission['post_submission_post_status'] ) ? $post_submission['post_submission_post_status'] : 'publish';

				$post_data = array(
					'post_type' => $post_type,
					'post_title' => wp_strip_all_tags( $title ),
					'post_content' => isset( $posted_data['post_content'] ) ? $this->sanitize_post_content_links( $posted_data['post_content'] ) : '',
					'post_status' => $post_status,
					'post_author' => $user_id,
					'meta_input' => $meta_input
				);

				// Insert the post into the database
				$post_id = wp_insert_post( $post_data );


				foreach ( $post_taxonomies as $taxonomy_name => $taxonomy ) {

					$category_ids = $posted_data[ $taxonomy_name ];

					if ( is_array( $category_ids ) ) {
						if ( ! empty( array_filter( $category_ids ) ) ) {

							$cats = array();

							foreach ( $category_ids as $key => $category_id ) {

								$cats[] = intval( $category_id );

							}
						}
					} else {
						$cats = $category_ids;
					}

					wp_set_object_terms( $post_id, $cats, $taxonomy );

				}

				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );

				$attachment_id = media_handle_upload( 'post_thumbnail', 0 );

				if ( is_wp_error( $attachment_id ) ) {
					// There was an error uploading the image.
				} else {
					// The image was uploaded successfully!
					set_post_thumbnail( $post_id, $attachment_id );
				}

			}

		endif;
	}

	/**
	 * Sanitize links inside the post content.
	 */
	private function sanitize_post_content_links( $content ) {
		
		$allowed_tags = wp_kses_allowed_html( 'post' );

		// Ensure all links are safe
		$allowed_tags['a']['rel'] = true;
		$allowed_tags['a']['target'] = true;

		return wp_kses( $content, $allowed_tags );
	}



	/**
	 * Form Generator AI Hooks And Callback Functions
	 * @since 1.1.6
	 */

	public function uacf7_blog_submission_ai_form_dropdown() {
		return [ "value" => "blog", "label" => "Blog Submission" ];
	}

	public function uacf7_post_submission_form_ai_generator( $value, $uacf7_default ) {

		$value = '<label> Post Title
						[uacf7_post_title* post_title] </label> 
					<label> Post Content
						[uacf7_post_content* post_content] </label> 
					<label> Post Thumbnail
						[uacf7_post_thumbnail* post_thumbnail limit:2mb filetypes:jpg|jpeg|png]<br><small>We have enabled all file support. For demo purpose, on this form, please upload jpg, jpeg or png only.</small></label> 
					<label> Post Category (You can select multiple)
						[uacf7_post_taxonomy* post-taxonomy-351 tax:category multiple] </label> 
					<label> Author Name
						[text* authorname] </label> 
					<label> Author Bio
						[textarea* authorbio] </label> 
					<label> Author Facebook URL
						[url* facebookurl] </label> 
					<label> Author Twitter URL
						[url* twitterurl] </label> 
					<label> Your Email
						[email* email-892]<br><small>This field will not be published. This is for further communication purpose.</small> </label> 
					[submit "Submit"]';

		return $value;

	}


}
new UACF7_POST_SUBMISSION();
