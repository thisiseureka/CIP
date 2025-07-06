<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_RANGE_SLIDER_PRO {

	public function __construct() {
		// Add action to init plugins loaded
		add_action( 'init', array( $this, 'init_plugins_loaded' ), 10, 2 );
	}

	/*
	 * Plugins Lodded
	 */
	public function init_plugins_loaded() {
		add_filter( 'uacf7_range_slider_style_field', array( $this, 'uacf7_range_slider_style_field' ) );
		add_filter( 'uacf7_range_slider_style_pro_feature', array( $this, 'uacf7_range_slider_style_pro_feature' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_slider_scripts' ) );
		add_action( 'wpcf7_contact_form_properties', array( $this, 'uacf7_contact_form_properties_pro' ), 10, 2 );
		add_action( 'wp_head', [ $this, 'uacf7_range_handle_css_properties' ] );
	}


	/*
	 * Tag generator field: Rating Style field
	 */
	public function uacf7_range_slider_style_field() {
		?>

		<fieldset>
			<legend>
				<?php echo esc_html__( 'Range Slider Style', 'ultimate-addons-cf7' ); ?>
			</legend>
			<select name="values" data-tag-part="value" id="tag-generator-panel-range-style">
				<option value="default">Default</option>
				<option value="style-one">Style One</option>
				<option value="style-two">Style Two</option>
				<option value="style-three">Style Three</option>
			</select>
		</fieldset>

		<fieldset>
			<legend>
				<?php echo esc_html__( 'Range Label', 'ultimate-addons-cf7' ); ?>
			</legend>

			<input type="text" data-tag-part="option" data-tag-option="label:" name="label" id="tag-generator-panel-text-min"
				placeholder="" />
		</fieldset>

		<fieldset>
			<legend>
				<?php echo esc_html__( 'Range Separator', 'ultimate-addons-cf7' ); ?>
			</legend>

			<input type="text" data-tag-part="option" data-tag-option="separator:" name="separator"
				class="tg-separator oneline option" id="tag-generator-panel-text-min" placeholder="-" />
		</fieldset>

		<fieldset>
			<legend>
				<?php echo esc_html__( 'Minimum Label', 'ultimate-addons-cf7' ); ?>
			</legend>

			<input type="text" data-tag-part="option" data-tag-option="min_label:" name="min_label"
				id="tag-generator-panel-text-min" placeholder="Min" />
		</fieldset>

		<fieldset>
			<legend>
				<?php echo esc_html__( 'Maximum Label', 'ultimate-addons-cf7' ); ?>
			</legend>
			<input type="text" data-tag-part="option" data-tag-option="max_label:" name="max_label"
				id="tag-generator-panel-text-min" placeholder="Max" />
		</fieldset>
		<?php
	}

	public function uacf7_range_slider_style_pro_feature( $default_layout, $tag ) {
		$validation_error = wpcf7_get_validation_error( $tag->name );
		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();
		$class .= ' uacf7-range-slider';
		$atts['class'] = $class;

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$atts = wpcf7_format_atts( $atts );

		$show_value = ! empty( $tag->get_option( 'show_value', '', true ) ) ? $tag->get_option( 'show_value', '', true ) : 'on';
		$handle = ! empty( $tag->get_option( 'handle', '', true ) ) ? $tag->get_option( 'handle', '', true ) : '1';
		$label = ! empty( $tag->get_option( 'label', '', true ) ) ? $tag->get_option( 'label', '', true ) : '';
		$separator = ! empty( $tag->get_option( 'separator', '', true ) ) ? $tag->get_option( 'separator', '', true ) : '-';
		$min_label = ! empty( $tag->get_option( 'min_label', '', true ) ) ? $tag->get_option( 'min_label', '', true ) : 'Min : ';
		$max_label = ! empty( $tag->get_option( 'max_label', '', true ) ) ? $tag->get_option( 'max_label', '', true ) : 'Max : ';
		$min = ! empty( $tag->get_option( 'min', '', true ) ) ? $tag->get_option( 'min', '', true ) : 0;
		$max = ! empty( $tag->get_option( 'max', '', true ) ) ? $tag->get_option( 'max', '', true ) : 100;
		$default = ! empty( $tag->get_option( 'default', '', true ) ) ? $tag->get_option( 'default', '', true ) : 100;
		$step = ! empty( $tag->get_option( 'step', '', true ) ) ? $tag->get_option( 'step', '', true ) : 1;
		$steps = '0';
		for ( $x = $step; $x <= $max; $x += $step ) {
			$steps .= ',' . $x . '';
		}
		// return array for range style as $values[0]
		if ( $data = (array) $tag->get_data_option() ) {
			$tag->values = array_merge( $tag->values, array_values( $data ) );
		}
		$values = $tag->values;
		$newValue = ( esc_html( $default ) - esc_html( $min ) ) * 100 / ( esc_html( $max ) - esc_html( $min ) );

		if ( empty( $values ) || $values[0] == 'default' ) {
			return $default_layout;
		}

		ob_start();
		if ( $handle == 1 ) {

			if ( $values[0] == 'style-one' ) {
				//start handle
				echo '<div class="' . esc_attr( $tag->name ) . '">';
				if ( $show_value == 'on' ) {
					?>
					<label class="uacf7-slider-label"> (
						<span class="min-max-label"><?php echo esc_html( $min_label ); ?> </span> <span class="range-min">
							<?php echo esc_html( $min ); ?></span>
						<span class="range-label"><?php echo esc_html( $label ); ?> </span>
						<span class="range-separator"><?php echo esc_html( $separator ); ?> </span>
						<span class="min-max-label"><?php echo esc_html( $max_label ); ?> </span> <span class="range-min">
							<?php echo esc_html( $max ) ?></span>
						<span class="range-label"> <?php echo esc_html( $label ); ?></span>
						)</label>
					<?php
				}
				?>
				<div class="range_slider_wrap">
					<div class="range_slider_inner">
						<input type="range" name="<?php echo esc_attr( $tag->name ); ?>"
							style="background: linear-gradient(to right, var(--uacf7-slider-Selection-Color) 0%, var(--uacf7-slider-Selection-Color) <?php echo esc_attr( $newValue ); ?>%, #d3d3d3 <?php echo esc_attr( $newValue ); ?>%, #d3d3d3 100%);"
							min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>"
							value="<?php echo esc_attr( $default ); ?>" class="range_slider" id="range_slider">
					</div>
					<span class="range_absulate" style="left: <?php echo esc_attr( $newValue ); ?>%"
						id="range_value"><span><?php echo esc_attr( $default ); ?></span> <?php echo esc_html( $label ); ?>
					</span>
				</div>
				<span>
					<?php echo $validation_error; ?>
				</span>
				</div>
				<?php
			} elseif ( $values[0] == 'style-two' ) {
				?>
				<div class="range_slider_wrap style-2">
					<div class="range_slider_inner">
						<input type="range" name="<?php echo esc_attr( $tag->name ); ?>"
							style="background: linear-gradient(to right, var(--uacf7-slider-Selection-Color) 0%, var(--uacf7-slider-Selection-Color) <?php echo esc_attr( $newValue ); ?>%, #d3d3d3 <?php echo esc_attr( $newValue ); ?>%, #d3d3d3 100%);"
							min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>"
							value="<?php echo esc_attr( $default ); ?>" class="range_slider" id="range_slider">
					</div>
					<span class="range_absulate" style="left: <?php echo esc_attr( $newValue ); ?>%" id="range_value">
						<span><?php echo esc_attr( $default ); ?></span> <?php echo esc_html( $label ); ?>
					</span>

					<?php if ( $show_value == 'on' ) { ?>
						<label class="uacf7-slider-label"> (
							<span class="min-max-label"><?php echo esc_html( $min_label ); ?> </span> <span class="range-min">
								<?php echo esc_html( $min ); ?></span>
							<span class="range-label"><?php echo esc_html( $label ); ?> </span>
							<span class="range-separator"><?php echo esc_html( $separator ); ?> </span>
							<span class="min-max-label"><?php echo esc_html( $max_label ); ?> </span> <span class="range-min">
								<?php echo esc_html( $max ) ?></span>
							<span class="range-label"> <?php echo esc_html( $label ); ?></span>
							)
						</label>
					<?php } ?>
					<span>
						<?php echo $validation_error; ?>
					</span>
				</div>
				<?php

			} elseif ( $values[0] == 'style-three' ) {
				echo '<div class="' . esc_attr( $tag->name ) . '">';
				?>
				<div class="demo-output">
					<input class="single-slider" name="<?php echo esc_attr( $tag->name ); ?>" class="uacf7-slide_amount"
						data-handle="<?php echo esc_attr( $handle ); ?>" data-steps="<?php echo esc_attr( $steps ); ?>"
						data-step="<?php echo esc_attr( $step ); ?>" data-min="<?php echo esc_attr( $min ); ?>"
						data-max="<?php echo esc_attr( $max ); ?>" data-default="<?php echo esc_attr( $default ); ?>" type="hidden"
						value="<?php echo esc_attr( $default ); ?>" />
				</div>
				<?php
				if ( $show_value == 'on' ) {
					?>
					<label class="uacf7-slider-label"> (
						<span class="min-max-label"><?php echo esc_html( $min_label ); ?> </span> <span class="range-min">
							<?php echo esc_html( $min ); ?></span>
						<span class="range-label"><?php echo esc_html( $label ); ?> </span>
						<span class="range-separator"><?php echo esc_html( $separator ); ?> </span>
						<span class="min-max-label"><?php echo esc_html( $max_label ); ?> </span> <span class="range-min">
							<?php echo esc_html( $max ) ?></span>
						<span class="range-label"> <?php echo esc_html( $label ); ?></span>
						)</label>
					<?php
				} ?>
				<span>
					<?php echo $validation_error; ?>
				</span>
				</div>
				<?php
			}
		} elseif ( $handle == 2 ) {
			if ( $values[0] == 'style-one' ) {
				?>
				<div class="mutli-range style-one">
					<span class="uacf7-slider-handle" data-style="<?php echo esc_attr( $values[0] ); ?>"
						data-handle="<?php echo esc_attr( $handle ); ?>" data-min="<?php echo esc_attr( $min ); ?>"
						data-max="<?php echo esc_attr( $max ); ?>" data-default="<?php echo esc_attr( $default ); ?>">
						<input name="<?php echo esc_attr( $tag->name ) ?>" type="hidden" id="uacf7-amount" class="uacf7-slide_amount"
							readonly>
						<div id="uacf7-slider-range" class="mutli_range_slide"></div>
					</span>
					</span>
					<?php
					if ( $show_value == 'on' ) {
						?>

						<div class="range-value">
							<div class="range-value-field">
								<span><span class="min-max-label"><?php echo esc_html( $min_label ); ?> </span>
									<span class="min-value-<?php echo esc_attr( $values[0] ); ?> show-values">
										<?php echo esc_attr( $min ); ?> </span> <span class="range-label"><?php echo esc_html( $label ); ?>
									</span>
									<span class="range-separator"><?php echo esc_html( $separator ); ?> </span>
									<span class="min-max-label"><?php echo esc_html( $max_label ); ?> </span>
									<span class="max-value-<?php echo esc_attr( $values[0] ); ?> show-values">
										<?php echo esc_attr( $max ); ?> </span> <span class="range-label">
										<?php echo esc_html( $label ); ?></span>
								</span>
							</div>

						</div>
						<?php
					}
					?>
					<span>
						<?php echo $validation_error; ?>
					</span>
				</div>
				<?php
			} elseif ( $values[0] == 'style-two' ) {
				?>
				<div class="mutli-range style-two">
					<span class="uacf7-slider-handle" data-style="<?php echo esc_attr( $values[0] ); ?>"
						data-handle="<?php echo esc_attr( $handle ); ?>" data-min="<?php echo esc_attr( $min ); ?>"
						data-max="<?php echo esc_attr( $max ); ?>" data-default="<?php echo esc_attr( $default ); ?>">
						<input name="<?php echo esc_attr( $tag->name ) ?>" type="hidden" id="uacf7-amount" class="uacf7-slide_amount"
							readonly>
						<div id="uacf7-slider-range" class="mutli_range_slide"></div>
					</span>
					</span>
					<?php
					if ( $show_value == 'on' ) {
						?>
						<div class="range-value">
							<div class="range-value-field min-range">
								<span><span class="min-max-label"><?php echo esc_html( $min_label ); ?> </span>
									<span class="min-value-<?php echo esc_attr( $values[0] ); ?> show-values">
										<?php echo esc_attr( $min ); ?>
									</span> <span class="range-label"><?php echo esc_html( $label ); ?> </span>
							</div>
							<div class="range-value-field max-range">
								<span><span class="min-max-label"><?php echo esc_html( $max_label ); ?> </span>
									<span class="max-value-<?php echo esc_attr( $values[0] ); ?> show-values">
										<?php echo esc_attr( $max ); ?>
									</span> <span class="range-label"><?php echo esc_html( $label ); ?> </span>
							</div>
						</div>
						<?php
					}
					?>
					<span>
						<?php echo $validation_error; ?>
					</span>
				</div>
				<?php
			} elseif ( $values[0] == 'style-three' ) {
				echo '<div class="' . esc_attr( $tag->name ) . '">';
				?>
				<div class="demo-output style-three">
					<input class="single-slider" name="<?php echo esc_attr( $tag->name ); ?>" class="uacf7-slide_amount"
						data-label="<?php echo esc_attr( $label ); ?>" data-handle="<?php echo esc_attr( $handle ); ?>"
						data-steps="<?php echo esc_attr( $steps ); ?>" data-step="<?php echo esc_attr( $step ); ?>"
						data-min="<?php echo esc_attr( $min ); ?>" data-max="<?php echo esc_attr( $max ); ?>"
						data-default="<?php echo esc_attr( $default ); ?>" type="hidden"
						value="<?php echo esc_attr( $min ); ?>, <?php echo esc_attr( $default ); ?>" />
				</div>
				<?php
				if ( $show_value == 'on' ) {
					?>
					<label class="uacf7-slider-label"> (
						<span class="min-max-label"><?php echo esc_html( $min_label ); ?> </span> <span class="range-min">
							<?php echo esc_html( $min ); ?></span>
						<span class="range-label"><?php echo esc_html( $label ); ?> </span>
						<span class="range-separator"><?php echo esc_html( $separator ); ?> </span>
						<span class="min-max-label"><?php echo esc_html( $max_label ); ?> </span> <span class="range-min">
							<?php echo esc_html( $max ) ?></span>
						<span class="range-label"> <?php echo esc_html( $label ); ?></span>
						)</label>
					<?php
				}
				?>
				<span>
					<?php echo $validation_error; ?>
				</span>
				<?php
				echo '</div>';
			}
		} //End handle 2
		return ob_get_clean();

	}


	/**
	 * Enqueue Slider scripts
	 */
	public function enqueue_slider_scripts() {
		wp_enqueue_script( 'range-step', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.range.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'uacf7-range-slider-pro-js', plugin_dir_url( __FILE__ ) . 'assets/js/range-slider-pro.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'range-slider-style-pro-css', plugin_dir_url( __FILE__ ) . 'assets/css/range-slider-pro.css' );

	}

	/**
	 * Contact Form Properties
	 */
	public function uacf7_contact_form_properties_pro( $properties, $cf ) {
		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$form = $properties['form'];


			// Define styling values
			$selection_color = ! empty( get_post_meta( $cf->id(), 'uacf7_range_selection_color', true ) ) ? get_post_meta( $cf->id(), 'uacf7_range_selection_color', true ) : "#1e90ff";
			$handle_width = ! empty( get_post_meta( $cf->id(), 'uacf7_range_handle_width', true ) ) ? get_post_meta( $cf->id(), 'uacf7_range_handle_width', true ) : '24';
			$handle_height = ! empty( get_post_meta( $cf->id(), 'uacf7_range_handle_height', true ) ) ? get_post_meta( $cf->id(), 'uacf7_range_handle_height', true ) : '24';
			$handle_border_radius = ! empty( get_post_meta( $cf->id(), 'uacf7_range_handle_border_radius', true ) ) ? get_post_meta( $cf->id(), 'uacf7_range_handle_border_radius', true ) : '24';
			$handle_color = ! empty( get_post_meta( $cf->id(), 'uacf7_range_handle_color', true ) ) ? get_post_meta( $cf->id(), 'uacf7_range_handle_color', true ) : '#3498db';
			$range_slider_height = ! empty( get_post_meta( $cf->id(), 'uacf7_range_slider_height', true ) ) ? get_post_meta( $cf->id(), 'uacf7_range_slider_height', true ) : 9;
			$handle_dynamic_position = ( $handle_height / 2 - $range_slider_height / 2 ) + 1;

			wp_localize_script( 'uacf7-range-slider-pro-js', 'range_handle', array(
				'handle_width' => $handle_width,
				'handle_height' => $handle_height,
			) );

			// Append the CSS to the form without altering the main form structure
			// $properties['form'] = $properties['form'] . $css;

		}
		return $properties;
	}

	public function uacf7_range_handle_css_properties() {

		wp_register_style( 'uacf7-rangeSlider-dynamic', UACF7_URL . 'addons/range-slider/css/uacf7-range.css', array(), null );

		$image_url_min = plugin_dir_url( __FILE__ ) . 'assets/img/min.png';
		$image_url_max = plugin_dir_url( __FILE__ ) . 'assets/img/max.png';

		// Output the dynamic CSS
		$css = "
			.range_slider_wrap.style-2 .range_slider::-moz-range-thumb {
				background-image: url('{$image_url_min}');
			}
			.range_slider_wrap.style-2 .range_slider::-webkit-slider-thumb {
				background-image: url('{$image_url_min}');
			}
			.style-two.mutli-range .ui-widget-content .ui-state-default {
				background-image: url('{$image_url_max}');
				border: none !important;
				background-size: cover;
				background-position: center;
			}
			.style-two.mutli-range .ui-widget-content .ui-state-default:hover {
				background-image: url('{$image_url_min}');
			}
		";

		// Ensure the stylesheet is enqueued
		wp_add_inline_style( 'uacf7-rangeSlider-dynamic', $css );
		wp_enqueue_style( 'uacf7-rangeSlider-dynamic' ); 
	}

}

$uacf7_range_slider_pro = new UACF7_RANGE_SLIDER_PRO();

