<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_IP_GEOLOCATION {

	/*
	 * Construct function
	 */
	public function __construct() {


		require_once 'inc/functions.php';

		add_action( 'wpcf7_init', array( $this, 'add_shortcodes' ) );

		add_action( 'admin_init', array( $this, 'tag_generator' ) );

		add_filter( 'wpcf7_validate_uacf7_city', array( $this, 'wpcf7_fields_validation_filter' ), 10, 2 );
		add_filter( 'wpcf7_validate_uacf7_city*', array( $this, 'wpcf7_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_state', array( $this, 'wpcf7_fields_validation_filter' ), 10, 2 );
		add_filter( 'wpcf7_validate_uacf7_state*', array( $this, 'wpcf7_fields_validation_filter' ), 10, 2 );

		add_filter( 'wpcf7_validate_uacf7_zip', array( $this, 'wpcf7_fields_validation_filter' ), 10, 2 );
		add_filter( 'wpcf7_validate_uacf7_zip*', array( $this, 'wpcf7_fields_validation_filter' ), 10, 2 );
		// add_action( 'wpcf7_swv_create_schema', array( $this, 'uacf7_swv_add_geo_location_rules' ), 10, 2 );

		add_filter( 'uacf7_tag_generator_country_autocomplete_field', array( $this, 'uacf7_tag_generator_country_autocomplete_field' ), 10, 2 );
		add_filter( 'uacf7_tag_generator_default_country_field', array( $this, 'uacf7_tag_generator_default_country_field' ), 10, 2 );
		add_filter( 'uacf7_get_country_attr', array( $this, 'uacf7_get_country_attr' ), 10, 3 );

		add_filter( 'uacf7_tag_generator_dynamic_selection', [ $this, 'uacf7_tag_generator_dynamic_selection' ], 10, 2 );
		// add_filter( 'uacf7_api_based_country_filter', [ $this, 'uacf7_api_based_country_filter' ], 10, 2 );

		add_filter( 'uacf7_save_admin_menu', array( $this, 'uacf7_save_ip_geo_fields' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'uacf7_enqueue_scripts' ) );

		//

	}

	//Pro IP GEO location scripts
	public function uacf7_enqueue_scripts() {
		wp_enqueue_script( 'uacf7-api-script', plugin_dir_url( __FILE__ ) . 'assets/js/all-country-info.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'uacf7-geo-api-openstreetmap-script', plugin_dir_url( __FILE__ ) . 'assets/js/geo-api.js', array( 'jquery' ), null, true );
		wp_localize_script( 'uacf7-api-script', 'all_country_script',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'plugin_dir_url' => plugin_dir_url( __FILE__ ),
			)
		);
	}

	/*
	 * Form tag
	 */
	public function add_shortcodes() {

		wpcf7_add_form_tag( array( 'uacf7_city', 'uacf7_city*' ),
			array( $this, 'tag_handler_city' ), array( 'name-attr' => true ) );

		wpcf7_add_form_tag( array( 'uacf7_state', 'uacf7_state*' ),
			array( $this, 'tag_handler_state' ), array( 'name-attr' => true ) );

		wpcf7_add_form_tag( array( 'uacf7_zip', 'uacf7_zip*' ),
			array( $this, 'tag_handler_zip' ), array( 'name-attr' => true ) );
	}

	public function tag_handler_city( $tag ) {

		if ( empty( $tag->name ) ) {
			return '';
		}

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

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
		} else {
			//$atts['size'] = 40;
		}
		/** Dynamic Selection from API Based on Country & State */
		$ds_city = $tag->has_option( 'ds_city' );
		if ( $ds_city ) {
			$atts['ds_city'] = 'true';
		}

		/** Auto Complete */
		$city_auto_complete = $tag->has_option( 'city_auto_complete' );


		if ( $city_auto_complete ) {
			$atts['city_auto_complete'] = 'true';
		}

		$placeholder = $tag->get_option( 'placeholder' );
		if ( ! empty( $placeholder ) ) {
			$placeholder = $placeholder[0];
		} else {
			$placeholder = '';
		}


		$atts = wpcf7_format_atts( $atts );

		ob_start();
		?>
		<span data-name="<?php echo esc_attr( $tag->name ); ?>" id="uacf7_city"
			class="wpcf7-form-control-wrap <?php echo sanitize_html_class( $tag->name ); ?>">

			<style>
				@keyframes spin {
					0% {
						transform: rotate(0deg);
					}

					100% {
						transform: rotate(360deg);
					}
				}
			</style>

			<div id="preloader"
				style="display:none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border: 8px solid #f3f3f3; border-radius: 50%; border-top: 8px solid #3498db; width: 60px; height: 60px; animation: spin 2s linear infinite; z-index: 999999;">
			</div>

			<?php if ( $ds_city ) { ?>
				<select <?php echo $atts; ?> id="uacf7_city_api">
					<option value="">Select a City</option>
				</select>
			<?php } else { ?>

				<span>
					<input placeholder="<?php echo esc_html( $placeholder ); ?>"
						id="uacf7_city_<?php echo esc_attr( $tag->name ); ?>" type="text" <?php echo $atts; ?>>
				</span>
				<span><?php echo $validation_error; ?></span>

			<?php } ?>

		</span>
		<?php

		$countries = ob_get_clean();

		return $countries;
	}




	/** API Based Country Showing */

	public function uacf7_api_based_country_filter( $val, $atts ) { ?>
		<select <?php echo $atts; ?> id="uacf7_country_api">
			<option value="">Select a Country</option>
		</select>
	<?php }

	public function tag_handler_state( $tag ) {

		if ( empty( $tag->name ) ) {
			return '';
		}


		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

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
		} else {
			//$atts['size'] = 40;
		}

		$ds_state = $tag->has_option( 'ds_state' );
		if ( $ds_state ) {
			$atts['ds_state'] = 'true';
		}

		/** Auto Complete */
		$state_auto_complete = $tag->has_option( 'state_auto_complete' );


		if ( $state_auto_complete ) {
			$atts['state_auto_complete'] = 'true';
		}


		$placeholder = $tag->get_option( 'placeholder' );
		if ( ! empty( $placeholder ) ) {
			$placeholder = $placeholder[0];
		} else {
			$placeholder = '';
		}

		$atts = wpcf7_format_atts( $atts );

		ob_start();
		?>
		<span data-name="<?php echo esc_attr( $tag->name ); ?>" id="uacf7_state"
			class="wpcf7-form-control-wrap <?php echo sanitize_html_class( $tag->name ); ?>">

			<div id="preloader"
				style="display:none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border: 8px solid #f3f3f3; border-radius: 50%; border-top: 8px solid #3498db; width: 60px; height: 60px; animation: spin 2s linear infinite; z-index: 999999;">
			</div>

			<?php if ( $ds_state ) { ?>
				<select <?php echo $atts; ?> id="uacf7_state_api">
					<option value="">Select a State</option>
				</select>
			<?php } else { ?>
				<span>
					<input placeholder="<?php echo esc_html( $placeholder ); ?>"
						id="uacf7_state_<?php echo esc_attr( $tag->name ); ?>" type="text" <?php echo $atts; ?>>
				</span>
				<span><?php echo $validation_error; ?></span>

			<?php } ?>



		</span>
		<?php

		$countries = ob_get_clean();

		return $countries;
	}

	public function tag_handler_zip( $tag ) {

		if ( empty( $tag->name ) ) {
			return '';
		}


		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

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
		} else {
			//$atts['size'] = 40;
		}

		/** Auto Complete */
		$zip_auto_complete = $tag->has_option( 'zip_auto_complete' );


		if ( $zip_auto_complete ) {
			$atts['zip_auto_complete'] = 'true';
		}

		$placeholder = $tag->get_option( 'placeholder' );
		if ( ! empty( $placeholder ) ) {
			$placeholder = $placeholder[0];
		} else {
			$placeholder = '';
		}

		$atts = wpcf7_format_atts( $atts );

		ob_start();
		?>
		<span data-name="<?php echo esc_attr( $tag->name ); ?>" id="uacf7_zip"
			class="wpcf7-form-control-wrap <?php echo sanitize_html_class( $tag->name ); ?>">
			<div id="preloader"
				style="display:none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); border: 8px solid #f3f3f3; border-radius: 50%; border-top: 8px solid #3498db; width: 60px; height: 60px; animation: spin 2s linear infinite; z-index: 999999;">
			</div>
			<span>
				<input placeholder="<?php echo esc_html( $placeholder ); ?>"
					id="uacf7_zip_<?php echo esc_attr( $tag->name ); ?>" type="text" <?php echo $atts; ?>>
			</span>
			<span><?php echo $validation_error; ?></span>
		</span>
		<?php

		$countries = ob_get_clean();

		return $countries;
	}

	public function wpcf7_fields_validation_filter( $result, $tag ) {
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
	 * Generate tags
	 */
	public function tag_generator() {
		$tag_generator = WPCF7_TagGenerator::get_instance();

		$tag_generator->add(
			'uacf7_city',
			__( 'City', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_city' ),
			array( 'version' => '2' )
		);
		$tag_generator->add(
			'uacf7_state',
			__( 'State', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_state' ),
			array( 'version' => '2' )
		);
		$tag_generator->add(
			'uacf7_zip',
			__( 'Zip Code', 'ultimate-addons-cf7' ),
			array( $this, 'tg_pane_zip' ),
			array( 'version' => '2' )
		);

	}

	static function tg_pane_city( $contact_form, $options ) {
		$field_types = array(
			'uacf7_city' => array(
				'display_name' => __( 'City', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate City', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>

		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_city']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_city']['description'],
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
					__( 'Confused? Check our Documentation on  %1s and %2s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-country-dropdown-with-flag/" target="_blank">Country Dropdown</a>',
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-autocomplete/" target="_blank">IP Geo Fields (Autocomplete)</a>'
				); ?>
			</div>
		</header>

		<div class="control-box">
			<?php
			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_city' => $field_types['uacf7_city']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );
			$tgg->print( 'class_attr' );
			?>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Placeholder', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" type="text" data-tag-option='placeholder:' />
			</fieldset>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Auto complete', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" id="city_auto_complete" type="checkbox" data-tag-option="city_auto_complete"
					class="option" value="on" />
				<p>Autocomplete City field using user's network IP.</p>
			</fieldset>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Dynamic Selection', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" id="ds_city" type="checkbox" data-tag-option="ds_city" class="option"
					value="on" />
				<?php echo esc_html( __( "Automatically retrieve the city based on the selected country and state. Note: This function works only if the 'Dynamic Selection for State' option is activated.", "ultimate-addons-cf7" ) ); ?>

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

	static function tg_pane_state( $contact_form, $options ) {
		$field_types = array(
			'uacf7_state' => array(
				'display_name' => __( 'State', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate State', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>

		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_state']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_state']['description'],
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
					__( 'Confused? Check our Documentation on  %1s and %2s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-country-dropdown-with-flag/" target="_blank">Country Dropdown</a>',
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-autocomplete/" target="_blank">IP Geo Fields (Autocomplete)</a>'
				); ?>
			</div>
		</header>

		<div class="control-box">
			<?php
			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_state' => $field_types['uacf7_state']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );
			$tgg->print( 'class_attr' );
			?>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Placeholder', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" type="text" data-tag-option='placeholder:' />
			</fieldset>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Auto complete', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" id="state_auto_complete" type="checkbox" data-tag-option="state_auto_complete"
					class="option" value="on" />
				<p>Autocomplete State field using user's network IP.</p>
			</fieldset>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Dynamic Selection', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" id="ds_state" type="checkbox" data-tag-option="ds_state" />
				<?php echo esc_html( __( "Dynamically obtain the state based on the chosen country. Note: This feature is operational only if the 'Dynamic Selection for Country' option is enabled.", "ultimate-addons-cf7" ) ); ?>
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

	static function tg_pane_zip( $contact_form, $options ) {
		$field_types = array(
			'uacf7_zip' => array(
				'display_name' => __( 'Zip Code', 'ultimate-addons-cf7' ),
				'heading' => __( 'Generate Zip Code', 'ultimate-addons-cf7' ),
				'description' => __( '', 'ultimate-addons-cf7' ),
			),
		);

		$tgg = new WPCF7_TagGeneratorGenerator( $options['content'] );
		?>

		<header class="description-box">
			<h3><?php
			echo esc_html( $field_types['uacf7_zip']['heading'] );
			?></h3>

			<p><?php
			$description = wp_kses(
				$field_types['uacf7_zip']['description'],
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
					__( 'Confused? Check our Documentation on  %1s and %2s', 'ultimate-addons-cf7' ),
					'<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-country-dropdown-with-flag/" target="_blank">Country Dropdown</a>',
					'<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-autocomplete/" target="_blank">IP Geo Fields (Autocomplete)</a>'
				); ?>
			</div>
		</header>

		<div class="control-box">
			<?php
			$tgg->print( 'field_type', array(
				'with_required' => true,
				'select_options' => array(
					'uacf7_zip' => $field_types['uacf7_zip']['display_name'],
				),
			) );

			$tgg->print( 'field_name' );
			$tgg->print( 'class_attr' );

			?>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Placeholder', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" type="text" data-tag-option='placeholder:' />
			</fieldset>

			<fieldset>
				<legend>
					<?php echo esc_html( __( 'Auto complete', 'ultimate-addons-cf7' ) ); ?>
				</legend>

				<input data-tag-part="option" id="zip_auto_complete" type="checkbox" data-tag-option="zip_auto_complete" />
				<p>Autocomplete zip code field using user's network IP.</p>
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

	public function uacf7_tag_generator_country_autocomplete_field( $field ) {
		?>
		<legend>
			<?php echo esc_html( __( 'Auto complete', 'ultimate-addons-cf7' ) ); ?>
		</legend>

		<input type="checkbox" data-tag-part="option" data-tag-option="country_auto_complete" />
		<?php echo esc_html( __( "Autocomplete country using user's network IP.", "ultimate-addons-cf7" ) ); ?>
	<?php
	}

	/** Dynamic Selections States */

	public function uacf7_tag_generator_dynamic_selection( $field ) {
		?>
		<legend>
			<?php echo esc_html( __( 'Dynamic Selection', 'ultimate-addons-cf7' ) ); ?>
		</legend>
		<input type="checkbox" class="option" data-tag-part="option" data-tag-option="ds_country" />
		<?php echo esc_html( __( "Dynamically Populate Countries, States, and Cities", "ultimate-addons-cf7" ) ); ?>
	<?php
	}


	public function uacf7_tag_generator_default_country_field( $field ) {
		?>

		<legend>
			<?php echo esc_html( __( 'Show Specific Countries', 'ultimate-addons-cf7' ) ); ?>
		</legend>

		<textarea data-tag-part="value" class="values" name="values" id="tag-generator-panel-product-id" cols="30" rows="10" placeholder="Ex : US"></textarea>
		
		<br> Please enter the selected Country ISO codes, one code per line.</a>


		<legend for="tag-defaul-panel-text-class"><?php echo esc_html( __( 'Default Country', 'ultimate-addons-cf7' ) ); ?>
		</legend>

		<!-- default:bd -->
		<input type="text" data-tag-part="option" data-tag-option='default:' class="defaultvalue oneline option"
			id="tag-defaul-panel-text-class" placeholder="Ex : US">
		</td>

		<?php
	}

	/*
	 * Default country and selected country display attr
	 */
	public function uacf7_get_country_attr( $atts, $tag ) {
		$default = $tag->get_option( 'default' );
		$autocomplete = $tag->get_option( 'autocomplete', '', true );
		$values = $tag->values;
		$ip = getVisIpAddr();
		$addr = @unserialize( file_get_contents( 'http://ip-api.com/php/' . $ip ) );
		if ( $autocomplete == 'true' && ! empty( $addr['countryCode'] ) ) {
			$country = strtolower( $addr['countryCode'] );
		} else {
			$country = '';
		}

		if ( is_array( $values ) ) {
			$values = array_map( 'strtolower', $values );
		}
		if ( ! empty( $default ) ) {
			$default = $default[0];
		} else {
			$default = '';
		}
		if ( $country != '' ) {
			$atts['country-code'] = $country;
		} else {
			$atts['country-code'] = strtolower( $default );
		}

		$atts['only-countries'] = json_encode( $values );
		return $atts;

	}

	/*
		Admin menu- save ip geo 
	*/
	public function uacf7_save_ip_geo_fields( $sanitary_values, $input ) {

		if ( isset( $input['uacf7_enable_ip_geo_fields'] ) ) {
			$sanitary_values['uacf7_enable_ip_geo_fields'] = $input['uacf7_enable_ip_geo_fields'];
		}
		return $sanitary_values;
	}

}
new UACF7_IP_GEOLOCATION();


