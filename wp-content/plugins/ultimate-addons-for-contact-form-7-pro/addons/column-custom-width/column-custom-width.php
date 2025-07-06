<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_COLUMN_PRO {

	public function __construct() {
		
		add_action( 'init', array( $this, 'uacf7_custom_column_width_init' ) );
	}

	public function uacf7_custom_column_width_init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'uacf7_column_custom_width_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'uacf7_column_custom_width_style' ) );
		add_filter( 'uacf7_column_custom_width', array( $this, 'uacf7_column_custom_width' ),  5, 3 );
	}


	public function uacf7_column_custom_width_scripts() {
		wp_enqueue_script( 'uacf7-column-width', plugin_dir_url( __FILE__ ) . 'assets/admin-script.js', array( 'jquery' ), null, true );
		wp_enqueue_style( 'uacf7-column-width', plugin_dir_url( __FILE__ ) . 'assets/admin-column-style .css' );
	}

	public function uacf7_column_custom_width_style() {
		wp_enqueue_style( 'uacf7-column-width', plugin_dir_url( __FILE__ ) . 'assets/column-style.css' );
	}

	public function uacf7_column_custom_width( $html, $class, $width ) {

		$ucaf7_column_class = $uacf7_column_custom_width = '';

		if ( $class == '' ) {
			$ucaf7_column_class = 'uacf7-column-custom-width';
		}
		if ( $width != '' ) {
			return '<div style="width:' . esc_attr( $width ) . '" class="' . $ucaf7_column_class . '">';
		} else {
			return '<div class="' . esc_attr( $class ) . '">';
		}

	}


}

new UACF7_COLUMN_PRO();



