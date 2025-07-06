<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'uacf7_repeater_init' );
function uacf7_repeater_init() {


	add_filter( 'uacf7_save_admin_menu', 'uacf7_save_repeater_field_cb', 10, 2 );
	add_filter( 'uacf7_pdf_generator_replace_data', 'uacf7_pdf_generator_replace_data', 10, 3 );
	add_action( 'admin_enqueue_scripts', 'uacf7_repeater_admin_style' );


	global $pagenow;
	if ( isset( $_GET['page'] ) ) {
		if ( ( $pagenow == 'admin.php' ) && ( $_GET['page'] == 'wpcf7' ) || ( $_GET['page'] == 'wpcf7-new' ) ) {
			add_action( 'admin_enqueue_scripts', 'uacf7_repeater_admin_script' );
		}
	}

	add_action( 'wp_enqueue_scripts', 'uacf7_repeater_script' );
	if ( function_exists( 'wpcf7_add_form_tag' ) ) {
		wpcf7_add_form_tag( 'uarepeater', 'uacf7_repeater_tag_handler', false );
	}
	add_action( 'admin_init', 'uacf7_repeater_tag_generator' );
	add_action( "wpcf7_before_send_mail", 'uacf7_before_send_mail', 10, 3 );
	add_action( 'wpcf7_form_hidden_fields', 'uacf7_repeater_form_hidden_fields', 10, 1 );
	// add_action('wpcf7_editor_panels', 'uacf7_add_repeater_panel');
	add_action( 'wpcf7_contact_form', 'uacf7_generate_repeater_html', 11 );
	add_filter( 'uacf7_enable_repeater_field', 'uacf7_enable_repeater_field_checked' );
	// add_action('wpcf7_after_save', 'uacf7_repeater_save_meta');

	//  For Generator Ai Repeater Form hook
	add_filter( 'uacf7_repeater_ai_form_dropdown', 'uacf7_repeater_ai_form_dropdown', 10, 2 );

	add_filter( 'uacf7_repeater_form_ai_generator', 'uacf7_repeater_form_ai_generator', 10, 2 );
}

function uacf7_repeater_admin_style() {
	wp_enqueue_style( 'uacf7-repeater-pro', plugin_dir_url( __FILE__ ) . '/css/repeater-pro-style.css' );
}
/*
Admin menu- Enable repeater
 */
function uacf7_save_repeater_field_cb( $sanitary_values, $input ) {

	if ( isset( $input['uacf7_enable_repeater_field'] ) ) {
		$sanitary_values['uacf7_enable_repeater_field'] = $input['uacf7_enable_repeater_field'];
	}
	return $sanitary_values;
}

/*
 * Return Checked attribute
 */
function uacf7_enable_repeater_field_checked( $x ) {
	return uacf7_checked( 'uacf7_enable_repeater_field' );
}

function uacf7_repeater_admin_script() {
	wp_enqueue_script( 'uacf7-repeater-admin', plugin_dir_url( __FILE__ ) . 'js/admin-script.js', array( 'jquery' ), null, true );
	wp_enqueue_style( 'uacf7-repeater-admin-style', plugin_dir_url( __FILE__ ) . '/css/repeater-admin-style.css' );
}

function uacf7_repeater_script() {
	wp_enqueue_script( 'uacf7-repeater', plugin_dir_url( __FILE__ ) . 'js/repeater-scripts.js', array( 'jquery' ), null, true );

	wp_enqueue_style( 'uacf7-repeater-style', plugin_dir_url( __FILE__ ) . '/css/repeater-style.css' );
}

function uacf7_repeater_tag_handler( $tag ) {
	ob_start();
	?>
	<?php $tag->content; ?>
	<?php
	return ob_get_clean();
}

function uacf7_repeater_tag_generator() {

	$tag_generator = WPCF7_TagGenerator::get_instance();

	$tag_generator->add(
		'uarepeater',
		__( 'Ultimate Repeater', 'ultimate-addons-cf7' ),
		'uacf7_tg_pane_repeater',
		array( 'version' => '2' )
	);
}



function uacf7_post_meta_options_repeater( $value, $post_id ) {
	if ( uacf7_settings( 'uacf7_enable_repeater_field' ) != true ) {
		return $value;
	}

	// if($post_id != 0){ 
	// 	$ContactForm = WPCF7_ContactForm::get_instance($post_id); 
	// 	$tags = $ContactForm->scan_form_tags(); 
	// 	$repeater = count ($tags);
	// }else{
	// 	// $repeater = '';
	// } 
	$repeater = apply_filters( 'uacf7_post_meta_options_repeater_pro', $data = array(
		'title' => __( 'Repeater Field', 'ultimate-addons-cf7' ),
		'icon' => 'fa-solid fa-repeat',
		'checked_field' => 'repeater_count',
		'fields' => array(
			'uacf7_repeater_heading' => array(
				'id' => 'uacf7_repeater_heading',
				'type' => 'heading',
				'label' => __( 'Repeater Field Settings', 'ultimate-addons-cf7' ),
				'subtitle' => sprintf(
					__( 'Add a repeater field to Contact Form 7 to repeat various fields, like text, files, checkboxes, text-areas, etc., with mail tag support. See Demo %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://cf7addons.com/preview/repeater-field-for-contact-form-7/" target="_blank" rel="noopener">Example</a>'
				)
			),
			'repeater_docs' => array(
				'id' => 'repeater_docs',
				'type' => 'notice',
				'style' => 'success',
				'content' => sprintf(
					__( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-repeatable-fields/" target="_blank" rel="noopener">Repeater Field</a>'
				)
			),
			'repeater_form_options_heading' => array(
				'id' => 'repeater_form_options_heading',
				'type' => 'heading',
				'label' => __( 'Repeater Field Option ', 'ultimate-addons-cf7' ),
			),
			'repeater_count' => array(
				'id' => 'repeater_count',
				'type' => 'repeater',
				// 'max' => $repeater,
				'label' => __( 'Maximum limit of repeatable group(s) ', 'ultimate-addons-cf7' ),
				'subtitle' => __( 'Set the maximum number of repetitions for each repeatable group. For instance, if you specify "10", a user can repeat that specific group up to 10 times on the form.', 'ultimate-addons-cf7' ),
				'class' => 'tf-field-class',
				'fields' => array(
					'field_name' => array(
						'id' => 'field_name',
						'type' => 'select',
						'label' => __( 'Repeatable Field Group', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'options' => 'uacf7',
						'query_args' => array(
							'post_id' => $post_id,
							'specific' => 'uarepeater',
						),
						'field_width' => '50',
					),
					'max_repeate' => array(
						'id' => 'max_repeate',
						'type' => 'number',
						'label' => __( 'Maximum Limit', 'ultimate-addons-cf7' ),
						'field_width' => '50',
						'placeholder' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
				),
			),

		),

	), $post_id );

	$value['repeater'] = $repeater;
	return $value;
}

add_filter( 'uacf7_post_meta_options', 'uacf7_post_meta_options_repeater', 24, 2 );

/*
 * Function create tab panel
 */
function uacf7_add_repeater_panel( $panels ) {
	$panels['uacf7-repeater-panel'] = array(
		'title' => __( 'UACF7 Repeater', 'ultimate-addons-cf7' ),
		'callback' => 'uacf7_create_repeater_panel_fields',
	);
	return $panels;
}

function uacf7_create_repeater_panel_fields( $post ) {
	?>
	<fieldset>
		<div class="uacf7-field" name="uacf7_uarepeater_group">
			<h3>Maximum limit of repeatable group(s)</h3>
			<?php
			$all_groups = $post->scan_form_tags( array( 'type' => 'uarepeater' ) );
			$num = 1;
			foreach ( $all_groups as $tag ) {
				$attrs = explode( ' ', $tag['attr'] );
				$max_repeat = ! empty( get_post_meta( $post->id(), $attrs[0], true ) ) ? get_post_meta( $post->id(), $attrs[0], true ) : '';
				?>
				<label for="<?php echo esc_attr( $attrs[0] ); ?>"><strong><?php echo 'Group ' . $num . ' (' . esc_html( $attrs[0] ) . ')'; ?>
						Max repeat : </strong> <input id="<?php echo esc_attr( $attrs[0] ); ?>" type="number" placeholder="1"
						min="2" value="<?php echo esc_attr( $max_repeat ); ?>"
						name="<?php echo esc_attr( $attrs[0] ); ?>"></label><br><br>
				<?php
				$num++;
			}
			?>
		</div>
		<?php wp_nonce_field( 'uacf7_repeater_nonce_action', 'uacf7_repeater_nonce' ); ?>
	</fieldset>
	<?php
}

function uacf7_tg_pane_repeater( $contact_form, $options ) {

	$field_types = array(
		'uarepeater' => array(
			'display_name' => __( 'Repeater', 'ultimate-addons-cf7' ),
			'heading' => __( 'Repeater', 'ultimate-addons-cf7' ),
			'description' => __( '', 'ultimate-addons-cf7' ),
		),
	);

	$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
	?>
	<header class="description-box">
		<h3><?php
		echo esc_html( $field_types['uarepeater']['heading'] );
		?></h3>

		<p><?php
		$description = wp_kses(
			$field_types['uarepeater']['description'],
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
				'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-repeatable-fields/" target="_blank">Repeater Field</a>'
			); ?>
		</div>
		<div class="uacf7-doc-notice uacf7-guide">
			<?php echo _e( 'Check "Repeater Field" tab located under the Ultimate Addons for CF7
				Options for additional settings.', 'ultimate-addons-cf7' ) ?>
		</div>
	</header>
	<div class="control-box">
		<?php

		$tgg->print( 'field_type', array(
			'select_options' => array(
				'uarepeater' => $field_types['uarepeater']['display_name'],
			),
		) );

		$tgg->print( 'field_name' );
		?>
		<fieldset>
			<legend>
				<?php echo esc_html__( 'Add Button Text', 'ultimate-addons-cf7' ); ?>
			</legend>

			<input type="text" name="" class="tg-name oneline uarepeater-add" value="Add more"
				id="tag-generator-panel-uarepeater-nae">
		</fieldset>

		<fieldset>
			<legend>
				<?php echo esc_html__( 'Remove Button Text', 'ultimate-addons-cf7' ); ?>
			</legend>

			<input type="text" name="" class="tg-name oneline uarepeater-remove" value="Remove"
				id="tag-generator-panel-uarepeater-n">
		</fieldset>
	</div>

	<footer class="insert-box">
		<?php $tgg->print( 'insert_box_content' ); ?>
	</footer>
	<?php
}

function uacf7_before_send_mail( $form, $abort, $submission ) {

	$props = $form->get_properties();
	$mails = [ 'mail', 'mail_2', 'messages' ];

	foreach ( $mails as $mail ) {
		if ( ! is_array( $props[ $mail ] ) ) {
			continue;
		}
		foreach ( $props[ $mail ] as $key => $val ) {

			$pattern = '@\[[\s]*([a-zA-Z_][0-9a-zA-Z:._-]*)[\s]*\](.*?)\[[\s]*/[\s]*\1[\s]*\]@s';

			$props[ $mail ][ $key ] = preg_replace_callback( $pattern, 'uacf7_replace_mail_template', $val );

		}

	}

	$form->set_properties( $props );
}

function uacf7_replace_mail_template( $matches ) {
	$name = $matches[1];

	$name_parts = explode( '__', $name );

	$name_root = array_shift( $name_parts );
	$name_suffix = implode( '__', $name_parts );

	$content = $matches[2];

	$repeaters = json_decode( stripslashes( $_POST['_uacf7_repeaters'] ) );

	if ( $repeaters !== null && in_array( $name, $repeaters ) ) {

		$original_name = explode( '__', $name )[0];

		$inner_template = $content;

		ob_start();

		$num_subs = $_POST[ $name . '_count' ];

		for ( $i = 1; $i <= $num_subs; $i++ ) {
			$str = preg_replace( [ "/\[{$original_name}\:title[^\]]*?\]/" ], $i, $inner_template );

			echo preg_replace( "/\[([^\s^\]]*?)([\s\]]+)([^\]]*?)/", "[$1__{$i}$2", $str );
		}

		$underscored_content = ob_get_clean();

		$pattern = '@\[[\s]*([a-zA-Z_][0-9a-zA-Z:._-]*)[\s]*\](.*?)\[[\s]*/[\s]*\1[\s]*\]@s';

		return preg_replace_callback( $pattern, 'uacf7_replace_mail_template', $underscored_content );

	} else {

		return $matches[0];

	}
}

function uacf7_repeater_form_hidden_fields( $hidden_fields ) {

	$current_form = wpcf7_get_current_contact_form();
	$current_form_id = $current_form->id();

	$all_groups = $current_form->scan_form_tags( array( 'type' => 'uarepeater' ) );

	$tag_name = '';
	foreach ( $all_groups as $tag ) {
		$tag_name = $tag['name'];
	}
	$uacf7_hidden_fields = array(
		'_uacf7_repeaters' => $tag_name,
		'_uacf7_options' => '',
	);

	return array_merge( $hidden_fields, $uacf7_hidden_fields );
}

function uacf7_generate_repeater_html( $contact_form ) {

	$posting_form = isset( $_POST['_uacf7_options'] );

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		$form = $contact_form->prop( 'form' );
		$mail = $contact_form->prop( 'mail' );
		$mail_2 = $contact_form->prop( 'mail_2' );
		$current_form_id = $contact_form->id();

		$form_parts = preg_split( '/(\[\/?uarepeater(?:\]|\s.*?\]))/', $form, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE );

		$add_button_default_text = "Add";
		$remove_button_default_text = "Remove";
		$add_button_text = [];
		$remove_button_text = [];
		$repeater_stack = [];

		ob_start();

		$stack = array();

		foreach ( $form_parts as $form_part ) {
			if ( substr( $form_part, 0, 12 ) == '[uarepeater ' ) {
				//$tag_parts = explode(' ',rtrim($form_part,']'));
				$tag_parts = str_getcsv( rtrim( $form_part, ']' ), ' ' );

				array_shift( $tag_parts );

				$tag_id = $tag_parts[0];
				$tag_html_type = 'div';
				$tag_html_data = array();

				$repeaters = ! empty( $_POST['_uacf7_repeaters'] ) ? json_decode( stripslashes( $_POST['_uacf7_repeaters'] ) ) : array();

				array_push( $repeaters, $tag_id );
				array_push( $repeater_stack, $tag_id );

				$add_button_text[] = $add_button_default_text;
				$remove_button_text[] = $remove_button_default_text;

				foreach ( $tag_parts as $i => $tag_part ) {
					$tag_part_arr = explode( ':', $tag_part );
					if ( $i == 0 ) {
						continue;
					} else if ( $tag_part == 'add' ) {
						array_pop( $add_button_text );
						$add_button_text[] = $tag_parts[ $i + 1 ];
						next( $tag_parts );
						continue;
					} else if ( $tag_part == 'remove' ) {
						array_pop( $remove_button_text );
						$remove_button_text[] = $tag_parts[ $i + 1 ];
						next( $tag_parts );
						continue;
					}

				}

				array_push( $stack, $tag_html_type );
				$repeater = uacf7_get_form_option( $current_form_id, 'repeater' );
				// uacf7_print_r($repeater);
				$max_repeat = '';

				if ( isset( $repeater['repeater_count'] ) && is_array( $repeater['repeater_count'] ) ) {
					foreach ( $repeater['repeater_count'] as $key => $value ) {
						if ( $value['field_name'] == $tag_id ) {
							$max_repeat = $value['max_repeate'];
						}
					}
				}

				echo '<' . $tag_html_type . ' class="uacf7_repeater" repeat="' . $max_repeat . '" uacf7-repeater-id="' . $tag_id . '"><div class="uacf7_repeater_sub_fields"><div class="uacf7_repeater_sub_field">';
			} else if ( $form_part == '[/uarepeater]' ) {
				$tag_id = array_pop( $repeater_stack );
				echo '<button type="button" class="uacf7_repeater_remove">' . array_pop( $remove_button_text ) . '</button></div></div><div class="uacf7_repeater_controls"><input type="hidden" class="uacf7-repeater-count" name="' . $tag_id . '_count" value=""><span class="uacf7_repeater_button_wraper"><button type="button" class="uacf7_repeater_add">' . array_pop( $add_button_text ) . '</button></span></div></' . array_pop( $stack ) . '>';
			} else {

				//echo $form_part; 
				if ( $posting_form && end( $repeater_stack ) ) {
					$rep_id = end( $repeater_stack );
					$num_subs = sanitize_text_field( $_POST[ $rep_id . '_count' ] );
					for ( $i = 1; $i <= $num_subs; $i++ ) {
						$replaced_form_part = preg_replace( '/\[([^\s]*)\s*([^\s^\]]*)/', '[\1 \2__' . $i, $form_part );
						echo $replaced_form_part;
					}
				} else {
					echo $form_part;
				}
			}
		}

		$form = ob_get_clean();

		$contact_form->set_properties( array(
			'form' => $form,
			'mail' => $mail,
			'mail_2' => $mail_2,
		) );
	}
}

/*
 * Save meta
 */
function uacf7_repeater_save_meta( $post ) {
	if ( ! isset( $_POST ) || empty( $_POST ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['uacf7_repeater_nonce'], 'uacf7_repeater_nonce_action' ) ) {
		return;
	}
	$all_repeaters = $post->scan_form_tags( array( 'type' => 'uarepeater' ) );

	$max_repeat = [];
	foreach ( $all_repeaters as $tag ) {
		$attrs = explode( ' ', $tag['attr'] );
		update_post_meta( $post->id(), $attrs[0], sanitize_text_field( $_POST[ $attrs[0] ] ) );
	}

}

if ( ! function_exists( 'uacf7_pdf_generator_replace_data' ) ) {
	function uacf7_pdf_generator_replace_data( $repeater_value, $repeaters, $customize_pdf ) {
		$replace_re_key = [];
		$replace_re_value = [];
		$repeater_data = [];

		foreach ( $repeaters as $key => $value ) {
			$starting_word = '[' . $value . ']';
			$ending_word = '[/' . $value . ']';
			$str = $customize_pdf;

			$subtring_start = strpos( $str, $starting_word );
			$subtring_start += strlen( $starting_word );
			$size = strpos( $str, $ending_word, $subtring_start ) - $subtring_start;

			$content = substr( $str, $subtring_start, $size );

			$repeater_data[ $value ]['start'] = '[' . $value . ']';
			$repeater_data[ $value ]['end'] = '[/' . $value . ']';
			$repeater_data[ $value ]['title'] = '[' . $value . ':title]';
			$repeater_data[ $value ]['content'] = $content;

			$replace_keys = [];

		}

		foreach ( $repeater_data as $key => $value ) {
			$repeater = '';
			$repeater_data[ $key ]['count'] = '';
			#empty field before loop start
			foreach ( $repeater_value as $r_key => $r_value ) {
				if ( strpos( $value['content'], '[' . $r_key . ']' ) !== false ) {
					$repeater_data[ $key ]['count'] = count( $r_value );
					$repeater_data[ $key ]['field'][ $r_key ] = $r_key;
				}
			}
			$replace_re_key[] = $value['start'] . $value['content'] . $value['end'];
			for ( $x = 1; $x <= $repeater_data[ $key ]['count']; $x++ ) {

				$str = str_replace( $value['title'], $x, $value['content'] );
				$repeater .= str_replace( ']', '__' . $x . ']', $str );
			}
			$replace_re_value[] = $repeater;
		}
		return [ 
			'replace_re_key' => $replace_re_key,
			'replace_re_value' => $replace_re_value,
		];
	}
}

/**
 * Form Generator AI Hooks And Callback Functions
 * @since 1.1.6
 */
if ( ! function_exists( 'uacf7_repeater_ai_form_dropdown' ) ) {
	function uacf7_repeater_ai_form_dropdown() {
		return [ "value" => "repeater", "label" => "Repeater" ];
	}
}

// Form Generator AI Hooks And Callback Functions
if ( ! function_exists( 'uacf7_repeater_form_ai_generator' ) ) {
	function uacf7_repeater_form_ai_generator( $value, $uacf7_default ) {

		$value = '<label> Your name </label>
[text* your-name]
<div class="uacf7-repeatborder">
[uarepeater uarepeater-511 add "Add another set +"]
<div><h4 class="uacf7-repeater-title">Repeater Set</h4>
<p>The border added on this area is for demo purpose, To differentiate the repeater area from normal fields. It is not part of the plugin.</p>
<label> Choose an Option </label>
[radio radio-33 use_label_element default:1 "Option A" "Option B"]
<label> Do you want to join the meeting? </label>
[select menu-296 "Yes" "No"]
</div>
[/uarepeater]
</div>
<label> Your email </label>
[email* your-email]
[submit "Submit"]';

		return $value;

	}
}