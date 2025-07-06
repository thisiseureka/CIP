<?php
/**
 * Plugin Name: UACF7 Addon - Global Styler
 * Plugin URI: https://cf7addons.com/
 * Description: Global Common Styler for Contact form 7 Forms. No need to style each form separately.
 * Version: 1.0.4
 * Author: Themefic
 * Author URI: https://themefic.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ultimate-addons-cf7
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'uacf7_global_settings_init' );
function uacf7_global_settings_init() {
	// add_action('admin_menu', 'ultimate_global_settings_page');
	// add_action('admin_enqueue_scripts', 'ultimate_global_settings_admin_scripts');
	add_action( 'wp_head', 'uacf7_global_stylesheet' );
	add_action( 'wp_head', 'uacf7_form_preview_stylesheet' );

}


/*
 * Enqueue scripts
 */
function ultimate_global_settings_admin_scripts() {
	wp_enqueue_style( 'uacf7-gs-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
	wp_enqueue_script( 'uacf7-gs-script', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array(), null, true );

	wp_enqueue_style( 'wp-codemirror' );
	$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
	$var = array(
		'cm_settings' => $cm_settings
	);
	wp_localize_script( 'uacf7-gs-script', 'uacf7_gs', $var );
}




/**
 * Global options
 */
if ( ! function_exists( 'uacf7_settings_options_global_styler' ) ) {
	function uacf7_settings_options_global_styler( $value ) {
		// uacf7_print_r($value);
		//   

		$global_styler = array(
			'global_form_styler' => array(
				'title' => __( 'Global Form Styler', 'ultimate-addons-cf7' ),
				'icon' => 'fa fa-cog',
				'fields' => array(
				),
			),
			'global_form_styler_label' => array(
				'title' => __( 'Label Options', 'ultimate-addons-cf7' ),
				'parent' => 'global_form_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_uacf7style_label_color' => array(
						'id' => 'uacf7_uacf7style_label_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 50,
					),
					'uacf7_uacf7style_label_background_color' => array(
						'id' => 'uacf7_uacf7style_label_background_color',
						'type' => 'color',
						'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 50,
					),
					'uacf7_uacf7style_label_font_style' => array(
						'id' => 'uacf7_uacf7style_label_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7style_label_font_weight' => array(
						'id' => 'uacf7_uacf7style_label_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7style_label_font_size' => array(
						'id' => 'uacf7_uacf7style_label_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Placeholder Font Size (in px)', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_label_font_family' => array(
						'id' => 'uacf7_uacf7style_label_font_family',
						'type' => 'text',
						'label' => __( 'Font Name ', 'ultimate-addons-cf7' ),
						'subtitle' => __( " E.g. Roboto, sans-serif (Do not add special characters like '' or ;) ", "ultimate-addons-cf7" ),
						'placeholder' => __( 'Enter Placeholder Font Name ', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'styler_heading_label_padding' => array(
						'id' => 'styler_heading_label_padding',
						'type' => 'heading',
						'title' => __( 'Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_label_padding_top' => array(
						'id' => 'uacf7_uacf7style_label_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_label_padding_right' => array(
						'id' => 'uacf7_uacf7style_label_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_label_padding_bottom' => array(
						'id' => 'uacf7_uacf7style_label_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_label_padding_left' => array(
						'id' => 'uacf7_uacf7style_label_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'styler_heading_label_margin' => array(
						'id' => 'styler_heading_label_margin',
						'type' => 'heading',
						'title' => __( 'Margin (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_label_margin_top' => array(
						'id' => 'uacf7_uacf7style_label_margin_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_label_margin_right' => array(
						'id' => 'uacf7_uacf7style_label_margin_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_label_margin_bottom' => array(
						'id' => 'uacf7_uacf7style_label_margin_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_label_margin_left' => array(
						'id' => 'uacf7_uacf7style_label_margin_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
				),
			),
			'global_form_styler_input' => array(
				'title' => __( 'Input Field Options', 'ultimate-addons-cf7' ),
				'parent' => 'global_form_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_uacf7style_input_color' => array(
						'id' => 'uacf7_uacf7style_input_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_background_color' => array(
						'id' => 'uacf7_uacf7style_input_background_color',
						'type' => 'color',
						'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_font_style' => array(
						'id' => 'uacf7_uacf7style_input_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_font_weight' => array(
						'id' => 'uacf7_uacf7style_input_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_font_size' => array(
						'id' => 'uacf7_uacf7style_input_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Placeholder Font Size (in px)', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_font_family' => array(
						'id' => 'uacf7_uacf7style_input_font_family',
						'type' => 'text',
						'label' => __( 'Font Name ', 'ultimate-addons-cf7' ),
						'subtitle' => __( " E.g. Roboto, sans-serif (Do not add special characters like '' or ;) ", "ultimate-addons-cf7" ),
						'placeholder' => __( 'Enter Placeholder Font Name ', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_font_size' => array(
						'id' => 'uacf7_uacf7style_input_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Input Font Size', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_font_family' => array(
						'id' => 'uacf7_uacf7style_input_font_family',
						'type' => 'text',
						'label' => __( 'Font Name ', 'ultimate-addons-cf7' ),
						'subtitle' => __( " E.g. Roboto, sans-serif (Do not add special characters like '' or ;) ", "ultimate-addons-cf7" ),
						'placeholder' => __( 'Enter Input Font Name ', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_input_height' => array(
						'id' => 'uacf7_uacf7style_input_height',
						'type' => 'number',
						'label' => __( 'Input Height (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Input Height', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),

					'uacf7_uacf7style_textarea_input_height' => array(
						'id' => 'uacf7_uacf7style_textarea_input_height',
						'type' => 'number',
						'label' => __( 'Input (Textarea) Height (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Textarea Height', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'styler_heading_input_padding' => array(
						'id' => 'styler_heading_input_padding',
						'type' => 'heading',
						'title' => __( 'Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_input_padding_top' => array(
						'id' => 'uacf7_uacf7style_input_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_padding_right' => array(
						'id' => 'uacf7_uacf7style_input_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_padding_bottom' => array(
						'id' => 'uacf7_uacf7style_input_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_padding_left' => array(
						'id' => 'uacf7_uacf7style_input_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'styler_heading_input_margin' => array(
						'id' => 'styler_heading_input_margin',
						'type' => 'heading',
						'title' => __( 'Margin (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_input_margin_top' => array(
						'id' => 'uacf7_uacf7style_input_margin_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_margin_right' => array(
						'id' => 'uacf7_uacf7style_input_margin_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_margin_bottom' => array(
						'id' => 'uacf7_uacf7style_input_margin_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_margin_left' => array(
						'id' => 'uacf7_uacf7style_input_margin_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'styler_heading_input_border' => array(
						'id' => 'styler_heading_input_border',
						'type' => 'heading',
						'title' => __( 'Border ', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_input_border_width' => array(
						'id' => 'uacf7_uacf7style_input_border_width',
						'type' => 'number',
						'label' => __( 'Border Width (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_border_style' => array(
						'id' => 'uacf7_uacf7style_input_border_style',
						'type' => 'select',
						'label' => __( 'Border Style ', 'ultimate-addons-cf7' ),
						'options' => array(
							'' => 'Select Border Style',
							'none' => 'None',
							'dotted' => "Dotted",
							'dashed' => "Dashed",
							'solid' => "Solid",
							'double' => "Double",
						),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_border_radius' => array(
						'id' => 'uacf7_uacf7style_input_border_radius',
						'type' => 'number',
						'label' => __( 'Border Radius (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border radius', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_input_border_color' => array(
						'id' => 'uacf7_uacf7style_input_border_color',
						'type' => 'color',
						'label' => __( 'Border Color', 'ultimate-addons-cf7' ),
						// 'subtitle'     => __( 'Customize Placeholder Color Options', 'ultimate-addons-cf7' ), 
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						// 'colors' => array(
						//     'uacf7_uacf7style_label_color' => 'Color',
						//     'uacf7_uacf7style_label_background_color' => 'Background Color', 
						// ), 
						'field_width' => 25,
					),
				),
			),
			'global_form_styler_button' => array(
				'title' => __( 'Submit Button Options', 'ultimate-addons-cf7' ),
				'parent' => 'global_form_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_uacf7style_btn_color' => array(
						'id' => 'uacf7_uacf7style_btn_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_color_hover' => array(
						'id' => 'uacf7_uacf7style_btn_color_hover',
						'type' => 'color',
						'label' => __( 'Text Color (hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_background_color' => array(
						'id' => 'uacf7_uacf7style_btn_background_color',
						'type' => 'color',
						'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_background_color_hover' => array(
						'id' => 'uacf7_uacf7style_btn_background_color_hover',
						'type' => 'color',
						'label' => __( 'Background Color (hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_font_size' => array(
						'id' => 'uacf7_uacf7style_btn_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_btn_font_style' => array(
						'id' => 'uacf7_uacf7style_btn_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7style_btn_font_weight' => array(
						'id' => 'uacf7_uacf7style_btn_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7style_btn_width' => array(
						'id' => 'uacf7_uacf7style_btn_width',
						'type' => 'text',
						'label' => __( 'Width (in px or %)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 100px or 100%.', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7style_btn_border_style' => array(
						'id' => 'uacf7_uacf7style_btn_border_style',
						'type' => 'select',
						'label' => __( 'Border Style ', 'ultimate-addons-cf7' ),
						'options' => array(
							'none' => 'None',
							'dotted' => "Dotted",
							'dashed' => "Dashed",
							'solid' => "Solid",
							'double' => "Double",
						),
						'field_width' => 33,
					),
					'uacf7_uacf7style_btn_border_width' => array(
						'id' => 'uacf7_uacf7style_btn_border_width',
						'type' => 'number',
						'label' => __( 'Border Width (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Button border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 33,
					),
					'uacf7_uacf7style_btn_border_radius' => array(
						'id' => 'uacf7_uacf7style_btn_border_radius',
						'type' => 'number',
						'label' => __( 'Border Radius (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Button border radius', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 33,
					),
					'uacf7_uacf7style_btn_border_color' => array(
						'id' => 'uacf7_uacf7style_btn_border_color',
						'type' => 'color',
						'label' => __( 'Border Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => false,
						// 'colors' => array(
						//     'uacf7_uacf7style_btn_color' => 'Color',
						//     'uacf7_uacf7style_btn_color_hover' => 'Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color' => 'Background Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color_hover' => 'Background Color (hover)', 
						// ),  
						'field_width' => 50,
					),
					'uacf7_uacf7style_btn_border_color_hover' => array(
						'id' => 'uacf7_uacf7style_btn_border_color_hover',
						'type' => 'color',
						'label' => __( 'Border Color (Hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						// 'colors' => array(
						//     'uacf7_uacf7style_btn_color' => 'Color',
						//     'uacf7_uacf7style_btn_color_hover' => 'Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color' => 'Background Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color_hover' => 'Background Color (hover)', 
						// ),  
						'field_width' => 50,
					),
					'uacf7_uacf7style_btn_padding' => array(
						'id' => 'uacf7_uacf7style_btn_padding',
						'type' => 'heading',
						'title' => __( 'Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_btn_padding_top' => array(
						'id' => 'uacf7_uacf7style_btn_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_padding_right' => array(
						'id' => 'uacf7_uacf7style_btn_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_padding_bottom' => array(
						'id' => 'uacf7_uacf7style_btn_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_padding_left' => array(
						'id' => 'uacf7_uacf7style_btn_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_margin' => array(
						'id' => 'uacf7_uacf7style_btn_margin',
						'type' => 'heading',
						'title' => __( 'Margin (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7style_btn_margin_top' => array(
						'id' => 'uacf7_uacf7style_btn_margin_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_margin_right' => array(
						'id' => 'uacf7_uacf7style_btn_margin_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_margin_bottom' => array(
						'id' => 'uacf7_uacf7style_btn_margin_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7style_btn_margin_left' => array(
						'id' => 'uacf7_uacf7style_btn_margin_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
				),
			),
			'global_form_styler_css' => array(
				'title' => __( 'Custom CSS', 'ultimate-addons-cf7' ),
				'parent' => 'global_form_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_uacf7style_ua_custom_css' => array(
						'id' => 'uacf7_uacf7style_ua_custom_css',
						'type' => 'code_editor',
						'label' => __( 'Custom CSS', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'Enter Your Custom CSS', 'ultimate-addons-cf7' ),
					),

				),
			),
		);
		$value = array_merge( $value, $global_styler );

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_global_styler', 14, 2 );
}


function uacf7_global_stylesheet() {
	$properties = '';

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		// get existing value
		$uacf7_global_settings_styles = uacf7_settings();
		if ( isset( $uacf7_global_settings_styles['uacf7_enable_uacf7style_global'] ) && $uacf7_global_settings_styles['uacf7_enable_uacf7style_global'] == true ) {

			$label_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_color'] : '';
			$label_background_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_background_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_background_color'] : '';

			$label_font_size = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_font_size'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_font_size'] : '';

			$label_font_family = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_font_family'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_font_family'] : '';
			$label_font_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_font_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_font_style'] : '';
			$label_font_weight = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_font_weight'] : '';
			$label_padding_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_top'] : '';
			$label_padding_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_right'] : '';
			$label_padding_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_bottom'] : '';
			$label_padding_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_padding_left'] : '';
			$label_margin_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_top'] : '';
			$label_margin_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_right'] : '';
			$label_margin_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_bottom'] : '';
			$label_margin_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_label_margin_left'] : '';

			$input_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_color'] : '';
			$input_background_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_background_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_background_color'] : '';
			$input_font_size = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_font_size'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_font_size'] : '';
			$input_font_family = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_font_family'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_font_family'] : '';
			$input_font_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_font_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_font_style'] : '';
			$input_font_weight = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_font_weight'] : '';
			$input_height = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_height'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_height'] : '';
			$input_border_width = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_border_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_border_width'] : '';
			$input_border_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_border_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_border_color'] : '';
			$input_border_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_border_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_border_style'] : '';
			$input_border_radius = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_border_radius'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_border_radius'] : '';
			$textarea_input_height = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_textarea_input_height'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_textarea_input_height'] : '';
			$input_padding_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_top'] : '';
			$input_padding_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_right'] : '';
			$input_padding_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_bottom'] : '';
			$input_padding_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_padding_left'] : '';
			$input_margin_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_top'] : '';
			$input_margin_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_right'] : '';
			$input_margin_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_bottom'] : '';
			$input_margin_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_input_margin_left'] : '';

			$btn_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_color'] : '';
			$btn_background_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_background_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_background_color'] : '';
			$btn_font_size = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_font_size'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_font_size'] : '';
			$btn_font_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_font_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_font_style'] : '';
			$btn_font_weight = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_font_weight'] : '';
			$btn_border_width = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_width'] : '';
			$btn_border_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_color'] : '';
			$btn_border_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_style'] : '';
			$btn_border_radius = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_radius'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_radius'] : '';
			$btn_width = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_width'] : '';
			$btn_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_color_hover'] : '';
			$btn_background_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_background_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_background_color_hover'] : '';
			$btn_border_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_border_color_hover'] : '';
			$btn_padding_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_top'] : '';
			$btn_padding_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_right'] : '';
			$btn_padding_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_bottom'] : '';
			$btn_padding_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_padding_left'] : '';
			$btn_margin_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_top'] : '';
			$btn_margin_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_right'] : '';
			$btn_margin_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_bottom'] : '';
			$btn_margin_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_margin_left'] : '';

			$ua_custom_css = ! empty( $uacf7_global_settings_styles['uacf7_uacf7style_ua_custom_css'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_ua_custom_css'] : '';
			?>
			<style>
				.wpcf7 label {
					<?php
					// Color
					if ( ! empty( $label_color ) ) {
						echo 'color: ' . esc_attr( $label_color ) . ';';
					}

					// Background color
					if ( ! empty( $label_background_color ) ) {
						echo 'background-color: ' . esc_attr( $label_background_color ) . ';';
					}

					// Font size
					if ( ! empty( $label_font_size ) ) {
						echo 'font-size: ' . esc_attr( $label_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $label_font_family ) ) {
						echo 'font-family: ' . esc_attr( $label_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $label_font_style ) ) {
						echo 'font-style: ' . esc_attr( $label_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $label_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $label_font_weight ) . ';';
					}

					// Padding
					if ( ! empty( $label_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $label_padding_top ) . 'px;';
					}
					if ( ! empty( $label_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $label_padding_right ) . 'px;';
					}
					if ( ! empty( $label_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $label_padding_bottom ) . 'px;';
					}
					if ( ! empty( $label_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $label_padding_left ) . 'px;';
					}

					// Margin
					if ( ! empty( $label_margin_top ) ) {
						echo 'margin-top: ' . esc_attr( $label_margin_top ) . 'px;';
					}
					if ( ! empty( $label_margin_right ) ) {
						echo 'margin-right: ' . esc_attr( $label_margin_right ) . 'px;';
					}
					if ( ! empty( $label_margin_bottom ) ) {
						echo 'margin-bottom: ' . esc_attr( $label_margin_bottom ) . 'px;';
					}
					if ( ! empty( $label_margin_left ) ) {
						echo 'margin-left: ' . esc_attr( $label_margin_left ) . 'px;';
					}

					?>
				}

				.wpcf7 input[type="email"],
				.wpcf7 input[type="number"],
				.wpcf7 input[type="password"],
				.wpcf7 input[type="search"],
				.wpcf7 input[type="tel"],
				.wpcf7 input[type="text"],
				.wpcf7 input[type="url"],
				.wpcf7 input[type="date"],
				.wpcf7 select,
				.wpcf7 textarea {
					<?php
					// Color
					if ( ! empty( $input_color ) ) {
						echo 'color: ' . esc_attr( $input_color ) . ';';
					}

					// Background color
					if ( ! empty( $input_background_color ) ) {
						echo 'background-color: ' . esc_attr( $input_background_color ) . ';';
					}

					// Font size
					if ( ! empty( $input_font_size ) ) {
						echo 'font-size: ' . esc_attr( $input_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $input_font_family ) ) {
						echo 'font-family: ' . esc_attr( $input_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $input_font_style ) ) {
						echo 'font-style: ' . esc_attr( $input_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $input_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $input_font_weight ) . ';';
					}

					// Input height
					if ( ! empty( $input_height ) ) {
						echo 'height: ' . esc_attr( $input_height ) . 'px;';
					}

					// Border properties
					if ( ! empty( $input_border_width ) ) {
						echo 'border-width: ' . esc_attr( $input_border_width ) . 'px;';
					}
					if ( ! empty( $input_border_color ) ) {
						echo 'border-color: ' . esc_attr( $input_border_color ) . ';';
					}
					if ( ! empty( $input_border_style ) ) {
						echo 'border-style: ' . esc_attr( $input_border_style ) . ';';
					}
					if ( ! empty( $input_border_radius ) ) {
						echo 'border-radius: ' . esc_attr( $input_border_radius ) . 'px;';
					}

					// Padding
					if ( ! empty( $input_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $input_padding_top ) . 'px;';
					}
					if ( ! empty( $input_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $input_padding_right ) . 'px;';
					}
					if ( ! empty( $input_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $input_padding_bottom ) . 'px;';
					}
					if ( ! empty( $input_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $input_padding_left ) . 'px;';
					}

					// Margin
					if ( ! empty( $input_margin_top ) ) {
						echo 'margin-top: ' . esc_attr( $input_margin_top ) . 'px;';
					}
					if ( ! empty( $input_margin_right ) ) {
						echo 'margin-right: ' . esc_attr( $input_margin_right ) . 'px;';
					}
					if ( ! empty( $input_margin_bottom ) ) {
						echo 'margin-bottom: ' . esc_attr( $input_margin_bottom ) . 'px;';
					}
					if ( ! empty( $input_margin_left ) ) {
						echo 'margin-left: ' . esc_attr( $input_margin_left ) . 'px;';
					}
					?>
				}

				.wpcf7 .wpcf7-radio span,
				.wpcf7 .wpcf7-checkbox span {
					<?php
					// Text color
					if ( ! empty( $input_color ) ) {
						echo 'color: ' . esc_attr( $input_color ) . ';';
					}

					// Font size
					if ( ! empty( $input_font_size ) ) {
						echo 'font-size: ' . esc_attr( $input_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $input_font_family ) ) {
						echo 'font-family: ' . esc_attr( $input_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $input_font_style ) ) {
						echo 'font-style: ' . esc_attr( $input_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $input_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $input_font_weight ) . ';';
					}
					?>
				}

				.wpcf7 textarea {
					<?php
					// Textarea input height
					if ( ! empty( $textarea_input_height ) ) {
						echo 'height: ' . esc_attr( $textarea_input_height ) . 'px;';
					}
					?>
				}

				.wpcf7-form-control-wrap select {
					width: 100%;
				}

				.wpcf7 input[type="submit"] {
					<?php
					// Text color
					if ( ! empty( $btn_color ) ) {
						echo 'color: ' . esc_attr( $btn_color ) . ';';
					}

					// Background color
					if ( ! empty( $btn_background_color ) ) {
						echo 'background-color: ' . esc_attr( $btn_background_color ) . ';';
					}

					// Font size
					if ( ! empty( $btn_font_size ) ) {
						echo 'font-size: ' . esc_attr( $btn_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $btn_font_family ) ) {
						echo 'font-family: ' . esc_attr( $btn_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $btn_font_style ) ) {
						echo 'font-style: ' . esc_attr( $btn_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $btn_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $btn_font_weight ) . ';';
					}

					// Border width
					if ( ! empty( $btn_border_width ) ) {
						echo 'border-width: ' . esc_attr( $btn_border_width ) . 'px;';
					}

					// Border color
					if ( ! empty( $btn_border_color ) ) {
						echo 'border-color: ' . esc_attr( $btn_border_color ) . ';';
					}

					// Border style
					if ( ! empty( $btn_border_style ) ) {
						echo 'border-style: ' . esc_attr( $btn_border_style ) . ';';
					}

					// Border radius
					if ( ! empty( $btn_border_radius ) ) {
						echo 'border-radius: ' . esc_attr( $btn_border_radius ) . 'px;';
					}

					// Button width
					if ( ! empty( $btn_width ) ) {
						echo 'width: ' . esc_attr( $btn_width ) . ';';
					}

					// Padding
					if ( ! empty( $btn_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $btn_padding_top ) . 'px;';
					}
					if ( ! empty( $btn_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $btn_padding_right ) . 'px;';
					}
					if ( ! empty( $btn_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $btn_padding_bottom ) . 'px;';
					}
					if ( ! empty( $btn_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $btn_padding_left ) . 'px;';
					}

					// Margin
					if ( ! empty( $btn_margin_top ) ) {
						echo 'margin-top: ' . esc_attr( $btn_margin_top ) . 'px;';
					}
					if ( ! empty( $btn_margin_right ) ) {
						echo 'margin-right: ' . esc_attr( $btn_margin_right ) . 'px;';
					}
					if ( ! empty( $btn_margin_bottom ) ) {
						echo 'margin-bottom: ' . esc_attr( $btn_margin_bottom ) . 'px;';
					}
					if ( ! empty( $btn_margin_left ) ) {
						echo 'margin-left: ' . esc_attr( $btn_margin_left ) . 'px;';
					}
					?>
				}

				.wpcf7 input[type="submit"]:hover {
					<?php
					// Text color on hover
					if ( ! empty( $btn_color_hover ) ) {
						echo 'color: ' . esc_attr( $btn_color_hover ) . ';';
					}

					// Background color on hover
					if ( ! empty( $btn_background_color_hover ) ) {
						echo 'background-color: ' . esc_attr( $btn_background_color_hover ) . ';';
					}

					// Border color on hover
					if ( ! empty( $btn_border_color_hover ) ) {
						echo 'border-color: ' . esc_attr( $btn_border_color_hover ) . ';';
					}
					?>
				}

				<?php echo esc_attr( $ua_custom_css );
				?>
			</style>
			<?php
		}
	}


	return $properties;
}

/**
 * Form Preview options
 */
if ( ! function_exists( 'uacf7_settings_options_form_submission_preview_styler' ) ) {
	function uacf7_settings_options_form_submission_preview_styler( $value ) {
		// uacf7_print_r($value);
		//   

		$form_preview_styler = array(
			'form_submission_preview_styler' => array(
				'title' => __( 'Form Preview Styler', 'ultimate-addons-cf7' ),
				'icon' => 'fa fa-palette',
				'fields' => array(
				),
			),
			'form_preview_button_styler' => array(
				'title' => __( 'Preview Button Style', 'ultimate-addons-cf7' ),
				'parent' => 'form_submission_preview_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_uacf7_preview_style_btn_color' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'default' => '#000',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_color_hover' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_color_hover',
						'type' => 'color',
						'label' => __( 'Text Color (hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_background_color' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_background_color',
						'type' => 'color',
						'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_background_color_hover' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_background_color_hover',
						'type' => 'color',
						'label' => __( 'Background Color (hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_font_size' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 50,
						'default' => '16'
					),
					'uacf7_uacf7_preview_style_btn_font_style' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_btn_font_weight' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_btn_width' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_width',
						'type' => 'text',
						'label' => __( 'Width (in px or %)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 100px or 100%.', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_btn_border_style' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_border_style',
						'type' => 'select',
						'label' => __( 'Border Style ', 'ultimate-addons-cf7' ),
						'options' => array(
							'none' => 'None',
							'dotted' => "Dotted",
							'dashed' => "Dashed",
							'solid' => "Solid",
							'double' => "Double",
						),
						'default' => 'solid',
						'field_width' => 33,
					),
					'uacf7_uacf7_preview_style_btn_border_width' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_border_width',
						'type' => 'number',
						'label' => __( 'Border Width (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Button border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 33,
						'default' => '1'
					),
					'uacf7_uacf7_preview_style_btn_border_radius' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_border_radius',
						'type' => 'number',
						'label' => __( 'Border Radius (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Button border radius', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 33,
					),
					'uacf7_uacf7_preview_style_btn_border_color' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_border_color',
						'type' => 'color',
						'label' => __( 'Border Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'default' => '#000',
						'multiple' => false,
						'inline' => false,
						// 'colors' => array(
						//     'uacf7_uacf7style_btn_color' => 'Color',
						//     'uacf7_uacf7style_btn_color_hover' => 'Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color' => 'Background Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color_hover' => 'Background Color (hover)', 
						// ),  
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_btn_border_color_hover' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_border_color_hover',
						'type' => 'color',
						'label' => __( 'Border Color (Hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						// 'colors' => array(
						//     'uacf7_uacf7style_btn_color' => 'Color',
						//     'uacf7_uacf7style_btn_color_hover' => 'Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color' => 'Background Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color_hover' => 'Background Color (hover)', 
						// ),  
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_btn_padding' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_padding',
						'type' => 'heading',
						'title' => __( 'Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7_preview_style_btn_padding_top' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_padding_right' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_padding_bottom' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_padding_left' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_margin' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_margin',
						'type' => 'heading',
						'title' => __( 'Margin (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7_preview_style_btn_margin_top' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_margin_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_margin_right' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_margin_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
						'default' => '10'
					),
					'uacf7_uacf7_preview_style_btn_margin_bottom' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_margin_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_btn_margin_left' => array(
						'id' => 'uacf7_uacf7_preview_style_btn_margin_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
				),
			),
			'form_preview_heading_styler_label' => array(
				'title' => __( 'Heading Style ', 'ultimate-addons-cf7' ),
				'parent' => 'form_submission_preview_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_preview_style_heading_color' => array(
						'id' => 'uacf7_preview_style_heading_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 50,
					),
					'uacf7_preview_style_heading_background_color' => array(
						'id' => 'uacf7_preview_style_heading_background_color',
						'type' => 'color',
						'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 50,
					),
					'uacf7_preview_style_heading_font_style' => array(
						'id' => 'uacf7_preview_style_heading_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_preview_style_heading_font_weight' => array(
						'id' => 'uacf7_preview_style_heading_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_preview_style_heading_font_size' => array(
						'id' => 'uacf7_preview_style_heading_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Placeholder Font Size (in px)', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_preview_style_heading_font_family' => array(
						'id' => 'uacf7_preview_style_heading_font_family',
						'type' => 'text',
						'label' => __( 'Font Name ', 'ultimate-addons-cf7' ),
						'subtitle' => __( " E.g. Roboto, sans-serif (Do not add special characters like '' or ;) ", "ultimate-addons-cf7" ),
						'placeholder' => __( 'Enter Placeholder Font Name ', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_preview_styler_heading_padding' => array(
						'id' => 'uacf7_preview_styler_heading_padding',
						'type' => 'heading',
						'title' => __( 'Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_preview_style_heading_padding_top' => array(
						'id' => 'uacf7_preview_style_heading_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_heading_padding_right' => array(
						'id' => 'uacf7_preview_style_heading_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_heading_padding_bottom' => array(
						'id' => 'uacf7_preview_style_heading_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_heading_padding_left' => array(
						'id' => 'uacf7_preview_style_heading_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'styler_heading_label_margin' => array(
						'id' => 'styler_heading_label_margin',
						'type' => 'heading',
						'title' => __( 'Margin (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
					),
					'uacf7_preview_style_heading_margin_top' => array(
						'id' => 'uacf7_preview_style_heading_margin_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_heading_margin_right' => array(
						'id' => 'uacf7_preview_style_heading_margin_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_heading_margin_bottom' => array(
						'id' => 'uacf7_preview_style_heading_margin_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_heading_margin_left' => array(
						'id' => 'uacf7_preview_style_heading_margin_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
				),
			),
			'form_preview_modal_styler' => array(
				'title' => __( 'Modal Style', 'ultimate-addons-cf7' ),
				'parent' => 'form_submission_preview_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_preview_style_modal_text_color' => array(
						'id' => 'uacf7_preview_style_modal_text_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_background_color' => array(
						'id' => 'uacf7_preview_style_modal_background_color',
						'type' => 'color',
						'label' => __( 'Modal Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_table_text_color' => array(
						'id' => 'uacf7_preview_style_modal_table_text_color',
						'type' => 'color',
						'label' => __( 'Table Head Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_table_background_color' => array(
						'id' => 'uacf7_preview_style_modal_table_background_color',
						'type' => 'color',
						'label' => __( 'Table Head Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
						'default' => '#f2f2f2'
					),
					'uacf7_preview_style_modal_font_style' => array(
						'id' => 'uacf7_preview_style_modal_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_preview_style_modal_font_weight' => array(
						'id' => 'uacf7_preview_style_modal_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_preview_style_modal_font_size' => array(
						'id' => 'uacf7_preview_style_modal_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Placeholder Font Size (in px)', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_preview_style_modal_font_family' => array(
						'id' => 'uacf7_preview_style_modal_font_family',
						'type' => 'text',
						'label' => __( 'Font Name ', 'ultimate-addons-cf7' ),
						'subtitle' => __( " E.g. Roboto, sans-serif (Do not add special characters like '' or ;) ", "ultimate-addons-cf7" ),
						'placeholder' => __( 'Enter Placeholder Font Name ', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'styler_heading_table_column_padding' => array(
						'id' => 'styler_heading_table_column_padding',
						'type' => 'heading',
						'title' => __( 'Table Column Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_preview_style_table_column_padding_top' => array(
						'id' => 'uacf7_preview_style_table_column_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
						'default' => '8',
					),
					'uacf7_preview_style_table_column_padding_right' => array(
						'id' => 'uacf7_preview_style_table_column_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
						'default' => '8',
					),
					'uacf7_preview_style_table_column_padding_bottom' => array(
						'id' => 'uacf7_preview_style_table_column_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
						'default' => '8'
					),
					'uacf7_preview_style_table_column_padding_left' => array(
						'id' => 'uacf7_preview_style_table_column_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
						'default' => '8'
					),
					'styler_heading_table_border' => array(
						'id' => 'styler_heading_input_border',
						'type' => 'heading',
						'title' => __( 'Table Border ', 'ultimate-addons-cf7' ),
					),
					'uacf7_preview_style_table_border_width' => array(
						'id' => 'uacf7_preview_style_table_border_width',
						'type' => 'number',
						'label' => __( 'Border Width (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
						'field_width' => 25,
						'default' => '1',
					),
					'uacf7_preview_style_table_border_style' => array(
						'id' => 'uacf7_preview_style_table_border_style',
						'type' => 'select',
						'label' => __( 'Border Style ', 'ultimate-addons-cf7' ),
						'options' => array(
							'' => 'Select Border Style',
							'none' => 'None',
							'dotted' => "Dotted",
							'dashed' => "Dashed",
							'solid' => "Solid",
							'double' => "Double",
						),
						'field_width' => 25,
						'default' => 'solid',
					),
					'uacf7_preview_style_table_border_radius' => array(
						'id' => 'uacf7_preview_style_table_border_radius',
						'type' => 'number',
						'label' => __( 'Border Radius (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border radius', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_table_border_color' => array(
						'id' => 'uacf7_preview_style_table_border_color',
						'type' => 'color',
						'label' => __( 'Border Color', 'ultimate-addons-cf7' ),
						// 'subtitle'     => __( 'Customize Placeholder Color Options', 'ultimate-addons-cf7' ), 
						'class' => 'tf-field-class',
						'default' => '#ddd',
						'multiple' => false,
						'inline' => true,
						// 'colors' => array(
						//     'uacf7_uacf7style_label_color' => 'Color',
						//     'uacf7_uacf7style_label_background_color' => 'Background Color', 
						// ), 
						'field_width' => 25,
					),
					'styler_heading_input_padding' => array(
						'id' => 'styler_heading_input_padding',
						'type' => 'heading',
						'title' => __( 'Modal Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_preview_style_modal_padding_top' => array(
						'id' => 'uacf7_preview_style_modal_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_padding_right' => array(
						'id' => 'uacf7_preview_style_modal_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_padding_bottom' => array(
						'id' => 'uacf7_preview_style_modal_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_padding_left' => array(
						'id' => 'uacf7_preview_style_modal_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'styler_heading_input_border' => array(
						'id' => 'styler_heading_input_border',
						'type' => 'heading',
						'title' => __( 'Modal Border ', 'ultimate-addons-cf7' ),
					),
					'uacf7_preview_style_modal_border_width' => array(
						'id' => 'uacf7_preview_style_modal_border_width',
						'type' => 'number',
						'label' => __( 'Border Width (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_border_style' => array(
						'id' => 'uacf7_preview_style_modal_border_style',
						'type' => 'select',
						'label' => __( 'Border Style ', 'ultimate-addons-cf7' ),
						'options' => array(
							'' => 'Select Border Style',
							'none' => 'None',
							'dotted' => "Dotted",
							'dashed' => "Dashed",
							'solid' => "Solid",
							'double' => "Double",
						),
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_border_radius' => array(
						'id' => 'uacf7_preview_style_modal_border_radius',
						'type' => 'number',
						'label' => __( 'Border Radius (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border radius', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_preview_style_modal_border_color' => array(
						'id' => 'uacf7_preview_style_modal_border_color',
						'type' => 'color',
						'label' => __( 'Border Color', 'ultimate-addons-cf7' ),
						// 'subtitle'     => __( 'Customize Placeholder Color Options', 'ultimate-addons-cf7' ), 
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						// 'colors' => array(
						//     'uacf7_uacf7style_label_color' => 'Color',
						//     'uacf7_uacf7style_label_background_color' => 'Background Color', 
						// ), 
						'field_width' => 25,
					),
				),
			),
			'form_preview_modal_submit_button_styler' => array(
				'title' => __( 'Submit Button Style', 'ultimate-addons-cf7' ),
				'parent' => 'form_submission_preview_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_uacf7_preview_style_submit_btn_color' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_color',
						'type' => 'color',
						'label' => __( 'Text Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_color_hover' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_color_hover',
						'type' => 'color',
						'label' => __( 'Text Color (hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_background_color' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_background_color',
						'type' => 'color',
						'label' => __( 'Background Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_background_color_hover' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_background_color_hover',
						'type' => 'color',
						'label' => __( 'Background Color (hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_font_size' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_font_size',
						'type' => 'number',
						'label' => __( 'Font Size (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( 'E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_submit_btn_font_style' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_font_style',
						'type' => 'select',
						'label' => __( 'Font Style', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal',
							'italic' => "Italic",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_submit_btn_font_weight' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_font_weight',
						'type' => 'select',
						'label' => __( 'Font Weight', 'ultimate-addons-cf7' ),
						'options' => array(
							'normal' => 'Normal / 400',
							'300' => "300",
							'500' => "500",
							'700' => "700",
							'900' => "900",
						),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_submit_btn_width' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_width',
						'type' => 'text',
						'label' => __( 'Width (in px or %)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter input border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 100px or 100%.', 'ultimate-addons-cf7' ),
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_submit_btn_border_style' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_border_style',
						'type' => 'select',
						'label' => __( 'Border Style ', 'ultimate-addons-cf7' ),
						'options' => array(
							'none' => 'None',
							'dotted' => "Dotted",
							'dashed' => "Dashed",
							'solid' => "Solid",
							'double' => "Double",
						),
						'field_width' => 33,
					),
					'uacf7_uacf7_preview_style_submit_btn_border_width' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_border_width',
						'type' => 'number',
						'label' => __( 'Border Width (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Button border width', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 33,
					),
					'uacf7_uacf7_preview_style_submit_btn_border_radius' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_border_radius',
						'type' => 'number',
						'label' => __( 'Border Radius (in px)', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Enter Button border radius', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
						'field_width' => 33,
					),
					'uacf7_uacf7_preview_style_submit_btn_border_color' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_border_color',
						'type' => 'color',
						'label' => __( 'Border Color', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => false,
						// 'colors' => array(
						//     'uacf7_uacf7style_btn_color' => 'Color',
						//     'uacf7_uacf7style_btn_color_hover' => 'Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color' => 'Background Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color_hover' => 'Background Color (hover)', 
						// ),  
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_submit_btn_border_color_hover' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_border_color_hover',
						'type' => 'color',
						'label' => __( 'Border Color (Hover)', 'ultimate-addons-cf7' ),
						'class' => 'tf-field-class',
						// 'default' => '#ffffff',
						'multiple' => false,
						'inline' => true,
						// 'colors' => array(
						//     'uacf7_uacf7style_btn_color' => 'Color',
						//     'uacf7_uacf7style_btn_color_hover' => 'Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color' => 'Background Color (hover)', 
						//     'uacf7_uacf7style_btn_background_color_hover' => 'Background Color (hover)', 
						// ),  
						'field_width' => 50,
					),
					'uacf7_uacf7_preview_style_submit_btn_padding' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_padding',
						'type' => 'heading',
						'title' => __( 'Padding (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16 (Do not add px or em).', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7_preview_style_submit_btn_padding_top' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_padding_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_padding_right' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_padding_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_padding_bottom' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_padding_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_padding_left' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_padding_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_margin' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_margin',
						'type' => 'heading',
						'title' => __( 'Margin (in px)', 'ultimate-addons-cf7' ),
						'content' => __( ' E.g. 16(Do not add px or em ). ', 'ultimate-addons-cf7' ),
					),
					'uacf7_uacf7_preview_style_submit_btn_margin_top' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_margin_top',
						'type' => 'number',
						'label' => __( 'Top', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Top', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_margin_right' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_margin_right',
						'type' => 'number',
						'label' => __( 'Right', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Right', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_margin_bottom' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_margin_bottom',
						'type' => 'number',
						'label' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Bottom', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
					'uacf7_uacf7_preview_style_submit_btn_margin_left' => array(
						'id' => 'uacf7_uacf7_preview_style_submit_btn_margin_left',
						'type' => 'number',
						'label' => __( 'Left', 'ultimate-addons-cf7' ),
						'placeholder' => __( 'Left', 'ultimate-addons-cf7' ),
						'field_width' => 25,
					),
				),
			),
			'form_preview_styler_css' => array(
				'title' => __( 'Custom CSS', 'ultimate-addons-cf7' ),
				'parent' => 'form_submission_preview_styler',
				'icon' => 'fa fa-cog',
				'fields' => array(
					'uacf7_style_preview_custom_css' => array(
						'id' => 'uacf7_style_preview_custom_css',
						'type' => 'code_editor',
						'label' => __( 'Custom CSS', 'ultimate-addons-cf7' ),
						'subtitle' => __( 'Enter Your Custom CSS', 'ultimate-addons-cf7' ),
					),

				),
			),
		);
		$value = array_merge( $value, $form_preview_styler );

		return $value;
	}
	add_filter( 'uacf7_settings_options', 'uacf7_settings_options_form_submission_preview_styler', 14, 2 );
}


function uacf7_form_preview_stylesheet() {
	$properties = '';

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		// get existing value
		$uacf7_global_settings_styles = uacf7_settings();

		if ( isset( $uacf7_global_settings_styles['uacf7_enable_form_submission_preview_pro'] ) && $uacf7_global_settings_styles['uacf7_enable_form_submission_preview_pro'] == true ) {

			$heading_color = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_color'] : '';
			$heading_background_color = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_background_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_background_color'] : '';
			$heading_font_size = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_font_size'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_font_size'] : '';
			$heading_font_family = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_font_family'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_font_family'] : '';
			$heading_font_style = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_font_style'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_font_style'] : '';
			$heading_font_weight = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_font_weight'] : '';
			$heading_padding_top = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_top'] : '';
			$heading_padding_right = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_right'] : '';
			$heading_padding_bottom = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_bottom'] : '';
			$heading_padding_left = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_padding_left'] : '';
			$heading_margin_top = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_top'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_top'] : '';
			$heading_margin_right = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_right'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_right'] : '';
			$heading_margin_bottom = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_bottom'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_bottom'] : '';
			$heading_margin_left = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_left'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_heading_margin_left'] : '';

			$modal_text_color       = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_text_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_text_color'] : '';
			$modal_background_color = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_background_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_background_color'] : '';
			$modal_font_size        = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_font_size'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_font_size'] : '';
			$modal_font_family      = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_font_family'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_font_family'] : '';
			$modal_font_style       = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_font_style'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_font_style'] : '';
			$modal_font_weight      = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_font_weight'] : '';
			$modal_border_width     = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_border_width'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_border_width'] : '';
			$modal_border_color     = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_border_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_border_color'] : '';
			$modal_border_style     = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_border_style'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_border_style'] : '';
			$modal_border_radius    = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_border_radius'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_border_radius'] : '';
			
			$modal_table_text_color            = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_table_text_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_table_text_color'] : '';
			$modal_table_background_color      = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_table_background_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_table_background_color'] : '';
			$modal_table_column_padding_top    = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_top'] : '';
			$modal_table_column_padding_right  = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_right'] : '';
			$modal_table_column_padding_bottom = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_bottom'] : '';
			$modal_table_column_padding_left   = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_column_padding_left'] : '';
			$modal_table_border_width          = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_border_width'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_border_width'] : '';
			$modal_table_border_color          = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_border_color'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_border_color'] : '';
			$modal_table_border_style          = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_border_style'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_border_style'] : '';
			$modal_table_border_radius         = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_table_border_radius'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_table_border_radius'] : '';

			$modal_padding_top      = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_top'] : '';
			$modal_padding_right    = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_right'] : '';
			$modal_padding_bottom   = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_bottom'] : '';
			$modal_padding_left     = ! empty( $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_preview_style_modal_padding_left'] : '';

			$btn_color                  = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_color'] : '';
			$btn_background_color       = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_background_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_background_color'] : '';
			$btn_font_size              = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_font_size'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_font_size'] : '';
			$btn_font_style             = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_font_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_font_style'] : '';
			$btn_font_weight            = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_font_weight'] : '';
			$btn_border_width           = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_width'] : '';
			$btn_border_color           = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_color'] : '';
			$btn_border_style           = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_style'] : '';
			$btn_border_radius          = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_radius'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_radius'] : '';

			$btn_width                  = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_width'] : '';
			$btn_color_hover            = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_color_hover'] : '';
			$btn_background_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_background_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_background_color_hover'] : '';
			$btn_border_color_hover     = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_border_color_hover'] : '';
			$btn_padding_top            = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_top'] : '';
			$btn_padding_right          = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_right'] : '';
			$btn_padding_bottom         = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_bottom'] : '';
			$btn_padding_left           = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_padding_left'] : '';
			$btn_margin_top             = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_top'] : '';
			$btn_margin_right           = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_right'] : '';
			$btn_margin_bottom          = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_bottom'] : '';
			$btn_margin_left            = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_margin_left'] : '';
			
			$submit_btn_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7style_btn_color'] : '';
			$submit_btn_background_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_background_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_background_color'] : '';
			$submit_btn_font_size = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_font_size'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_font_size'] : '';
			$submit_btn_font_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_font_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_font_style'] : '';
			$submit_btn_font_weight = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_font_weight'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_font_weight'] : '';
			$submit_btn_border_width = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_width'] : '';
			$submit_btn_border_color = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_color'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_color'] : '';
			$submit_btn_border_style = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_style'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_style'] : '';
			$submit_btn_border_radius = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_radius'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_radius'] : '';
			$submit_btn_width = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_width'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_btn_width'] : '';
			$submit_btn_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_color_hover'] : '';
			$submit_btn_background_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_background_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_background_color_hover'] : '';
			$submit_btn_border_color_hover = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_color_hover'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_border_color_hover'] : '';
			$submit_btn_padding_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_top'] : '';
			$submit_btn_padding_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_right'] : '';
			$submit_btn_padding_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_bottom'] : '';
			$submit_btn_padding_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_padding_left'] : '';
			$submit_btn_margin_top = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_top'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_top'] : '';
			$submit_btn_margin_right = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_right'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_right'] : '';
			$submit_btn_margin_bottom = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_bottom'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_bottom'] : '';
			$submit_btn_margin_left = ! empty( $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_left'] ) ? $uacf7_global_settings_styles['uacf7_uacf7_preview_style_submit_btn_margin_left'] : '';
			
			$ua_custom_css = ! empty( $uacf7_global_settings_styles['uacf7_style_preview_custom_css'] ) ? $uacf7_global_settings_styles['uacf7_style_preview_custom_css'] : '';
			
			?>
			<style>
				.wpcf7 .uacf7-preview-btn{
					<?php
					// Text color
					if ( ! empty( $btn_color ) ) {
						echo 'color: ' . esc_attr( $btn_color ) . ';';
					}

					// Background color
					if ( ! empty( $btn_background_color ) ) {
						echo 'background-color: ' . esc_attr( $btn_background_color ) . ';';
					}

					// Font size
					if ( ! empty( $btn_font_size ) ) {
						echo 'font-size: ' . esc_attr( $btn_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $btn_font_family ) ) {
						echo 'font-family: ' . esc_attr( $btn_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $btn_font_style ) ) {
						echo 'font-style: ' . esc_attr( $btn_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $btn_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $btn_font_weight ) . ';';
					}

					// Border width
					if ( ! empty( $btn_border_width ) ) {
						echo 'border-width: ' . esc_attr( $btn_border_width ) . 'px;';
					}

					// Border color
					if ( ! empty( $btn_border_color ) ) {
						echo 'border-color: ' . esc_attr( $btn_border_color ) . ';';
					}

					// Border style
					if ( ! empty( $btn_border_style ) ) {
						echo 'border-style: ' . esc_attr( $btn_border_style ) . ';';
					}

					// Border radius
					if ( ! empty( $btn_border_radius ) ) {
						echo 'border-radius: ' . esc_attr( $btn_border_radius ) . 'px;';
					}

					// Button width
					if ( ! empty( $btn_width ) ) {
						echo 'width: ' . esc_attr( $btn_width ) . ';';
					}

					// Padding
					if ( ! empty( $btn_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $btn_padding_top ) . 'px;';
					}
					if ( ! empty( $btn_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $btn_padding_right ) . 'px;';
					}
					if ( ! empty( $btn_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $btn_padding_bottom ) . 'px;';
					}
					if ( ! empty( $btn_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $btn_padding_left ) . 'px;';
					}

					// Margin
					if ( ! empty( $btn_margin_top ) ) {
						echo 'margin-top: ' . esc_attr( $btn_margin_top ) . 'px;';
					}
					if ( ! empty( $btn_margin_right ) ) {
						echo 'margin-right: ' . esc_attr( $btn_margin_right ) . 'px;';
					}
					if ( ! empty( $btn_margin_bottom ) ) {
						echo 'margin-bottom: ' . esc_attr( $btn_margin_bottom ) . 'px;';
					}
					if ( ! empty( $btn_margin_left ) ) {
						echo 'margin-left: ' . esc_attr( $btn_margin_left ) . 'px;';
					}
					?>
				}

				.wpcf7 .uacf7-preview-btn:hover {
					<?php
					// Text color on hover
					if ( ! empty( $btn_color_hover ) ) {
						echo 'color: ' . esc_attr( $btn_color_hover ) . ';';
					}

					// Background color on hover
					if ( ! empty( $btn_background_color_hover ) ) {
						echo 'background-color: ' . esc_attr( $btn_background_color_hover ) . ';';
					}

					// Border color on hover
					if ( ! empty( $btn_border_color_hover ) ) {
						echo 'border-color: ' . esc_attr( $btn_border_color_hover ) . ';';
					}
					?>
				}


				.uacf7-preview-modal .uacf7-submit-btn{
					<?php
					// Text color
					if ( ! empty( $submit_btn_color ) ) {
						echo 'color: ' . esc_attr( $submit_btn_color ) . ';';
					}

					// Background color
					if ( ! empty( $submit_btn_background_color ) ) {
						echo 'background-color: ' . esc_attr( $submit_btn_background_color ) . ';';
					}

					// Font size
					if ( ! empty( $submit_btn_font_size ) ) {
						echo 'font-size: ' . esc_attr( $submit_btn_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $submit_btn_font_family ) ) {
						echo 'font-family: ' . esc_attr( $submit_btn_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $submit_btn_font_style ) ) {
						echo 'font-style: ' . esc_attr( $submit_btn_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $submit_btn_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $submit_btn_font_weight ) . ';';
					}

					// Border width
					if ( ! empty( $submit_btn_border_width ) ) {
						echo 'border-width: ' . esc_attr( $submit_btn_border_width ) . 'px;';
					}

					// Border color
					if ( ! empty( $submit_btn_border_color ) ) {
						echo 'border-color: ' . esc_attr( $submit_btn_border_color ) . ';';
					}

					// Border style
					if ( ! empty( $submit_btn_border_style ) ) {
						echo 'border-style: ' . esc_attr( $submit_btn_border_style ) . ';';
					}

					// Border radius
					if ( ! empty( $submit_btn_border_radius ) ) {
						echo 'border-radius: ' . esc_attr( $submit_btn_border_radius ) . 'px;';
					}

					// Button width
					if ( ! empty( $submit_btn_width ) ) {
						echo 'width: ' . esc_attr( $submit_btn_width ) . ';';
					}

					// Padding
					if ( ! empty( $submit_btn_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $submit_btn_padding_top ) . 'px;';
					}
					if ( ! empty( $submit_btn_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $submit_btn_padding_right ) . 'px;';
					}
					if ( ! empty( $submit_btn_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $submit_btn_padding_bottom ) . 'px;';
					}
					if ( ! empty( $submit_btn_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $submit_btn_padding_left ) . 'px;';
					}

					// Margin
					if ( ! empty( $submit_btn_margin_top ) ) {
						echo 'margin-top: ' . esc_attr( $submit_btn_margin_top ) . 'px;';
					}
					if ( ! empty( $submit_btn_margin_right ) ) {
						echo 'margin-right: ' . esc_attr( $submit_btn_margin_right ) . 'px;';
					}
					if ( ! empty( $submit_btn_margin_bottom ) ) {
						echo 'margin-bottom: ' . esc_attr( $submit_btn_margin_bottom ) . 'px;';
					}
					if ( ! empty( $submit_btn_margin_left ) ) {
						echo 'margin-left: ' . esc_attr( $submit_btn_margin_left ) . 'px;';
					}
					?>
				}

				.uacf7-preview-modal .uacf7-submit-btn:hover {
					<?php
					// Text color on hover
					if ( ! empty( $submit_btn_color_hover ) ) {
						echo 'color: ' . esc_attr( $submit_btn_color_hover ) . ';';
					}

					// Background color on hover
					if ( ! empty( $submit_btn_background_color_hover ) ) {
						echo 'background-color: ' . esc_attr( $submit_btn_background_color_hover ) . ';';
					}

					// Border color on hover
					if ( ! empty( $submit_btn_border_color_hover ) ) {
						echo 'border-color: ' . esc_attr( $submit_btn_border_color_hover ) . ';';
					}
					?>
				}

				.uacf7-preview-modal h2 {
					<?php
					// Color
					if ( ! empty( $heading_color ) ) {
						echo 'color: ' . esc_attr( $heading_color ) . ';';
					}

					// Background color
					if ( ! empty( $heading_background_color ) ) {
						echo 'background-color: ' . esc_attr( $heading_background_color ) . ';';
					}

					// Font size
					if ( ! empty( $heading_font_size ) ) {
						echo 'font-size: ' . esc_attr( $heading_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $heading_font_family ) ) {
						echo 'font-family: ' . esc_attr( $heading_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $heading_font_style ) ) {
						echo 'font-style: ' . esc_attr( $heading_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $heading_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $heading_font_weight ) . ';';
					}

					// Padding
					if ( ! empty( $heading_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $heading_padding_top ) . 'px;';
					}
					if ( ! empty( $heading_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $heading_padding_right ) . 'px;';
					}
					if ( ! empty( $heading_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $heading_padding_bottom ) . 'px;';
					}
					if ( ! empty( $heading_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $heading_padding_left ) . 'px;';
					}

					// Margin
					if ( ! empty( $heading_margin_top ) ) {
						echo 'margin-top: ' . esc_attr( $heading_margin_top ) . 'px;';
					}
					if ( ! empty( $heading_margin_right ) ) {
						echo 'margin-right: ' . esc_attr( $heading_margin_right ) . 'px;';
					}
					if ( ! empty( $heading_margin_bottom ) ) {
						echo 'margin-bottom: ' . esc_attr( $heading_margin_bottom ) . 'px;';
					}
					if ( ! empty( $heading_margin_left ) ) {
						echo 'margin-left: ' . esc_attr( $heading_margin_left ) . 'px;';
					}

					?>
				}

				.uacf7-preview-modal .uacf7-preview-modal-content{
					<?php
					// Textarea input height
					if ( ! empty( $modal_background_color ) ) {
						echo 'background-color: ' . esc_attr( $modal_background_color ) . ';';
					}
					// Border properties
					if ( ! empty( $modal_border_width ) ) {
						echo 'border-width: ' . esc_attr( $modal_border_width ) . 'px;';
					}
					if ( ! empty( $modal_border_color ) ) {
						echo 'border-color: ' . esc_attr( $modal_border_color ) . ';';
					}
					if ( ! empty( $modal_border_style ) ) {
						echo 'border-style: ' . esc_attr( $modal_border_style ) . ';';
					}
					if ( ! empty( $modal_border_radius ) ) {
						echo 'border-radius: ' . esc_attr( $modal_border_radius ) . 'px;';
					}

					// Padding
					if ( ! empty( $modal_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $modal_padding_top ) . 'px;';
					}
					if ( ! empty( $modal_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $modal_padding_right ) . 'px;';
					}
					if ( ! empty( $modal_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $modal_padding_bottom ) . 'px;';
					}
					if ( ! empty( $modal_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $modal_padding_left ) . 'px;';
					}

					?>
				}
				.uacf7-preview-modal tr th{
					<?php
					if ( ! empty( $modal_table_background_color ) ) {
						echo 'background-color: ' . esc_attr( $modal_table_background_color ) . ';';
					}

					if ( ! empty( $modal_table_text_color ) ) {
						echo 'color: ' . esc_attr( $modal_table_text_color ) . ' !important;';
					}
					?>
				}
				.uacf7-preview-modal tr th,
				.uacf7-preview-modal tr td{
					<?php
					// Textarea input height
					if ( ! empty( $modal_text_color ) ) {
						echo 'color: ' . esc_attr( $modal_text_color ) . ';';
					}
					// Font size
					if ( ! empty( $modal_font_size ) ) {
						echo 'font-size: ' . esc_attr( $modal_font_size ) . 'px;';
					}

					// Font family
					if ( ! empty( $modal_font_family ) ) {
						echo 'font-family: ' . esc_attr( $modal_font_family ) . ';';
					}

					// Font style
					if ( ! empty( $modal_font_style ) ) {
						echo 'font-style: ' . esc_attr( $modal_font_style ) . ';';
					}

					// Font weight
					if ( ! empty( $modal_font_weight ) ) {
						echo 'font-weight: ' . esc_attr( $modal_font_weight ) . ';';
					}
					
					// Border properties
					if ( ! empty( $modal_table_border_width ) ) {
						echo 'border-width: ' . esc_attr( $modal_table_border_width ) . 'px;';
					}
					if ( ! empty( $modal_table_border_color ) ) {
						echo 'border-color: ' . esc_attr( $modal_table_border_color ) . ';';
					}
					if ( ! empty( $modal_table_border_style ) ) {
						echo 'border-style: ' . esc_attr( $modal_table_border_style ) . ';';
					}
					if ( ! empty( $modal_table_border_radius ) ) {
						echo 'border-radius: ' . esc_attr( $modal_table_border_radius ) . 'px;';
					}

					// Padding
					if ( ! empty( $modal_table_column_padding_top ) ) {
						echo 'padding-top: ' . esc_attr( $modal_table_column_padding_top ) . 'px;';
					}
					if ( ! empty( $modal_table_column_padding_right ) ) {
						echo 'padding-right: ' . esc_attr( $modal_table_column_padding_right ) . 'px;';
					}
					if ( ! empty( $modal_table_column_padding_bottom ) ) {
						echo 'padding-bottom: ' . esc_attr( $modal_table_column_padding_bottom ) . 'px;';
					}
					if ( ! empty( $modal_table_column_padding_left ) ) {
						echo 'padding-left: ' . esc_attr( $modal_table_column_padding_left ) . 'px;';
					}
					

					?>
				}
				

				<?php echo esc_attr( $ua_custom_css );
				?>
			</style>
			<?php
		}
	}


	return $properties;
}
