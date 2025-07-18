<?php

namespace ElementPack\Includes;

/**
 * Element_Pack_WPML class
 */

 if (!defined('ABSPATH')) exit; // Exit if accessed directly
class Element_Pack_WPML {

	/**
	 * A reference to an instance of this class.
	 * @since 3.1.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Constructor for the class
	 */
	public function init() {

		// WPML String Translation plugin exist check
		if ( defined( 'WPML_ST_VERSION' ) ) {
			add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'add_translatable_nodes' ) );
		}

	}

	/**
	 * Load wpml required repeater class files.
	 * @return void
	 */
	public function load_wpml_modules() {

		include_once( BDTEP_INC_PATH . 'compatiblity/wpml/wpml-module-with-items.php' );

		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-member.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-accordion.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-google-maps.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-business-hours.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-chart.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-circle-menu.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-custom-carousel.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-custom-gallery.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-device-slider.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-iconnav.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-marker.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-open-street-map.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-panel-slider.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-price-list.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-scrollnav.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-slider.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-slideshow.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-social-share.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-timeline.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-tabs.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-user-login.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-video-gallery.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-advanced-progress-bar.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-circle-info.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-fancy-icons.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-fancy-list.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-fancy-slider.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-fancy-tabs.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-honeycombs.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-hover-box.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-hover-video.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-image-accordion.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-image-expand.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-logo-carousel.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-logo-grid.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-profile-card.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-vertical-menu.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-static-carousel.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-comparison-list.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-content-switcher.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-image-stack.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-interactive-tabs.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-product-carousel.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-product-grid.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-review-card-carousel.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-review-card-grid.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-sub-menu.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-static-grid-tab.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-slinky-vertical-menu.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-icon-mobile-menu.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-edd-tabs.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-brand-grid.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-brand-carousel.php' );
		require_once( BDTEP_INC_PATH . 'compatiblity/wpml/class-wpml-element-pack-advanced-calculator.php' );
	}

	/**
	 * Add element pack translation nodes
	 * @param array $nodes_to_translate
	 * @return array
	 */
	public function add_translatable_nodes( $nodes_to_translate ) {

		$this->load_wpml_modules();

		$nodes_to_translate[ 'bdt-accordion' ] = [
			'conditions' => [ 'widgetType' => 'bdt-accordion' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Accordion',
		];

		$nodes_to_translate[ 'bdt-advanced-button' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-button' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-advanced-counter' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-counter' ],
			'fields'     => [
				[
					'field'       => 'content_text',
					'type'        => esc_html__( 'Counter Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'counter_prefix',
					'type'        => esc_html__( 'Prefix', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'counter_suffix',
					'type'        => esc_html__( 'Suffix', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-advanced-gmap' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-gmap' ],
			'fields'     => [
				[
					'field'       => 'search_placeholder_text',
					'type'        => esc_html__( 'Placeholder Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'avd_google_map_style',
					'type'        => esc_html__( 'Style Json Code', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_GoogleMaps',
		];

		$nodes_to_translate[ 'bdt-advanced-heading' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-heading' ],
			'fields'     => [
				[
					'field'       => 'sub_heading',
					'type'        => esc_html__( 'Sub Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'main_heading',
					'type'        => esc_html__( 'Main Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'split_text',
					'type'        => esc_html__( 'Splilt Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'advanced_heading',
					'type'        => esc_html__( 'Splilt Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-age-gate' ] = [
			'conditions' => [ 'widgetType' => 'bdt-age-gate' ],
			'fields'     => [
				[
					'field'       => 'form_placeholder',
					'type'        => esc_html__( 'Form Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text_yes',
					'type'        => esc_html__( 'Button Text (Yes)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text_no',
					'type'        => esc_html__( 'Button Text (No)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'age_invalid_msg',
					'type'        => esc_html__( 'Age Invalid Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'header',
					'type'        => esc_html__( 'Header Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'content',
					'type'        => esc_html__( 'ModalContent', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'footer',
					'type'        => esc_html__( 'Footer Text', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-advanced-icon-box' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-icon-box' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_title_text',
					'type'        => esc_html__( 'Sub Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Readmore Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-advanced-image-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-image-gallery' ],
			'fields'     => [
				[
					'field'       => 'gallery_link_text',
					'type'        => esc_html__( 'Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-advanced-progress-bar' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-progress-bar' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Advanced_Progress_Bar',
		];

		$nodes_to_translate[ 'bdt-animated-heading' ] = [
			'conditions' => [ 'widgetType' => 'bdt-animated-heading' ],
			'fields'     => [
				[
					'field'       => 'pre_heading',
					'type'        => esc_html__( 'Prefix Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'animated_heading',
					'type'        => esc_html__( 'Animated Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'post_heading',
					'type'        => esc_html__( 'Post Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-audio-player' ] = [
			'conditions' => [ 'widgetType' => 'bdt-audio-player' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Audio Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'author_name',
					'type'        => esc_html__( 'Author Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-business-hours' ] = [
			'conditions' => [ 'widgetType' => 'bdt-business-hours' ],
			'fields'     => [
				[
					'field'       => 'dynamic_open_day',
					'type'        => esc_html__( 'Open Status', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'dynamic_close_day',
					'type'        => esc_html__( 'Close Status', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Business_Hours',
		];

		$nodes_to_translate[ 'bdt-circle-info' ] = [
			'conditions' => [ 'widgetType' => 'bdt-circle-info' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Circle_Info',
		];

		$nodes_to_translate[ 'bdt-call-out' ] = [
			'conditions' => [ 'widgetType' => 'bdt-call-out' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-carousel' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-chart' ] = [
			'conditions' => [ 'widgetType' => 'bdt-chart' ],
			'fields'     => [
				[
					'field'       => 'labels',
					'type'        => esc_html__( 'Label Values', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'single_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'single_datasets',
					'type'        => esc_html__( 'Data', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Chart',
		];

		$nodes_to_translate[ 'bdt-circle-menu' ] = [
			'conditions' => [ 'widgetType' => 'bdt-circle-menu' ],
			'fields'     => [
				[
					'field'       => 'tooltip_text',
					'type'        => esc_html__( 'Tooltip Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Circle_Menu',
		];

		$nodes_to_translate[ 'bdt-contact-form' ] = [
			'conditions' => [ 'widgetType' => 'bdt-contact-form' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'user_name_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'user_name_placeholder',
					'type'        => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'contact_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'contact_placeholder',
					'type'        => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'subject_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'subject_placeholder',
					'type'        => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_address_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_placeholder',
					'type'        => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'message_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'message_placeholder',
					'type'        => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'additional_message',
					'type'        => esc_html__( 'Message', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-cookie-consent' ] = [
			'conditions' => [ 'widgetType' => 'bdt-cookie-consent' ],
			'fields'     => [
				[
					'field'       => 'message',
					'type'        => __( 'Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'learn_more_text',
					'type'        => __( 'Learn More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'button_text',
					'type'        => __( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-animated-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-animated-card' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_title_text',
					'type'        => esc_html__( 'Sub Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-advanced-calculator' ] = [
			'conditions' => [ 'widgetType' => 'bdt-advanced-calculator' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'form_result_text',
					'type'        => esc_html__( 'Result Text', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'form_result_error',
					'type'        => esc_html__( 'Error Text', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],

			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Advanced_Calculator',
		];

		$nodes_to_translate[ 'bdt-barcode' ] = [
			'conditions' => [ 'widgetType' => 'bdt-barcode' ],
			'fields'     => [
				[
					'field'       => 'ep_barcode_content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'ep_barcode_label_text',
					'type'        => esc_html__( 'Label Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-breadcrumbs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-breadcrumbs' ],
			'fields'     => [
				[
					'field'       => 'breadcrumbs_separator',
					'type'        => esc_html__( 'Separator', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'home_page_text',
					'type'        => esc_html__( 'Home Page', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-coupon-code' ] = [
			'conditions' => [ 'widgetType' => 'bdt-coupon-code' ],
			'fields'     => [
				[
					'field'       => 'coupon_text',
					'type'        => esc_html__( 'Coupon Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'coupon_placeholder',
					'type'        => esc_html__( 'Coupon Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-countdown' ] = [
			'conditions' => [ 'widgetType' => 'bdt-countdown' ],
			'fields'     => [
				[
					'field'       => 'label_days',
					'type'        => esc_html__( 'Days', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'label_hours',
					'type'        => esc_html__( 'Hours', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'label_minutes',
					'type'        => esc_html__( 'Minutes', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'label_seconds',
					'type'        => esc_html__( 'Seconds', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'end_message',
					'type'        => esc_html__( 'End Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-download-monitor' ] = [
			'conditions' => [ 'widgetType' => 'bdt-download-monitor' ],
			'fields'     => [
				[
					'field'       => 'alt_title',
					'type'        => esc_html__( 'Alternative Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-custom-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-custom-carousel' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Custom_Carousel',
		];

		$nodes_to_translate[ 'bdt-custom-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-custom-gallery' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Custom_Gallery',
		];

		$nodes_to_translate[ 'bdt-device-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-device-slider' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Device_Slider',
		];

		$nodes_to_translate[ 'bdt-download-monitor' ] = [
			'conditions' => [ 'widgetType' => 'bdt-download-monitor' ],
			'fields'     => [
				[
					'field'       => 'alt_title',
					'type'        => esc_html__( 'Alternative Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-dropbar' ] = [
			'conditions' => [ 'widgetType' => 'bdt-dropbar' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-dual-button' ] = [
			'conditions' => [ 'widgetType' => 'bdt-dual-button' ],
			'fields'     => [
				[
					'field'       => 'middle_text',
					'type'        => esc_html__( 'Middle Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_a_text',
					'type'        => esc_html__( 'Button A Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_a_onclick_event',
					'type'        => esc_html__( 'Button A OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_b_text',
					'type'        => esc_html__( 'Button B Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_b_onclick_event',
					'type'        => esc_html__( 'OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-faq' ] = [
			'conditions' => [ 'widgetType' => 'bdt-faq' ],
			'fields'     => [
				[
					'field'       => 'more_button_button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-fancy-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-fancy-card' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-featured-box' ] = [
			'conditions' => [ 'widgetType' => 'bdt-featured-box' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_title_text',
					'type'        => esc_html__( 'Sub Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-interactive-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-interactive-card' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_title_text',
					'type'        => esc_html__( 'Sub Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-lottie-icon-box' ] = [
			'conditions' => [ 'widgetType' => 'bdt-lottie-icon-box' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_title_text',
					'type'        => esc_html__( 'Sub Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-step-flow' ] = [
			'conditions' => [ 'widgetType' => 'bdt-step-flow' ],
			'fields'     => [
				[
					'field'       => 'title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-lottie-image' ] = [
			'conditions' => [ 'widgetType' => 'bdt-lottie-image' ],
			'fields'     => [
				[
					'field'       => 'caption',
					'type'        => esc_html__( 'Custom Caption', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-svg-image' ] = [
			'conditions' => [ 'widgetType' => 'bdt-svg-image' ],
			'fields'     => [
				[
					'field'       => 'caption',
					'type'        => esc_html__( 'Custom Caption', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-fancy-icons' ] = [
			'conditions' => [ 'widgetType' => 'bdt-fancy-icons' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Fancy_Icons',
		];

		$nodes_to_translate[ 'bdt-fancy-list' ] = [
			'conditions' => [ 'widgetType' => 'bdt-fancy-list' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Fancy_List',
		];

		$nodes_to_translate[ 'bdt-fancy-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-fancy-slider' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Fancy_Slider',
		];

		$nodes_to_translate[ 'bdt-fancy-tabs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-fancy-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Fancy_Tabs',
		];

		$nodes_to_translate[ 'bdt-honeycombs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-honeycombs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Honeycombs',
		];

		$nodes_to_translate[ 'bdt-hover-box' ] = [
			'conditions' => [ 'widgetType' => 'bdt-hover-box' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Hover_Box',
		];

		$nodes_to_translate[ 'bdt-hover-video' ] = [
			'conditions' => [ 'widgetType' => 'bdt-hover-video' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Hover_Video',
		];

		$nodes_to_translate[ 'bdt-image-accordion' ] = [
			'conditions' => [ 'widgetType' => 'bdt-image-accordion' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Image_Accordion',
		];

		$nodes_to_translate[ 'bdt-image-expand' ] = [
			'conditions' => [ 'widgetType' => 'bdt-image-expand' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Image_Expand',
		];

		$nodes_to_translate[ 'bdt-logo-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-logo-carousel' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Logo_Carousel',
		];

		$nodes_to_translate[ 'bdt-logo-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-logo-grid' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Logo_Grid',
		];

		$nodes_to_translate[ 'bdt-vertical-menu' ] = [
			'conditions' => [ 'widgetType' => 'bdt-vertical-menu' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Vertical_Menu',
		];

		$nodes_to_translate[ 'bdt-profile-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-profile-card' ],
			'fields'     => [
				[
					'field'       => 'profile_badge_text',
					'type'        => esc_html__( 'Badge', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_name',
					'type'        => esc_html__( 'Profile Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_username',
					'type'        => esc_html__( 'User Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'profile_posts',
					'type'        => esc_html__( 'Counter Text One', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_posts_number',
					'type'        => esc_html__( 'Counter Number One', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_followers',
					'type'        => esc_html__( 'Counter Text Two', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_followers_number',
					'type'        => esc_html__( 'Counter Number Two', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_following',
					'type'        => esc_html__( 'Counter Text Three', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_following_number',
					'type'        => esc_html__( 'Counter Number Three', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'profile_button_text',
					'type'        => esc_html__( 'Follow Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'instagram_posts',
					'type'        => esc_html__( 'Instagram Posts', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'instagram_followers',
					'type'        => esc_html__( 'Instagram Followers', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'instagram_following',
					'type'        => esc_html__( 'Instagram Following', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'instagram_button_text',
					'type'        => esc_html__( 'Instagram Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'blog_posts',
					'type'        => esc_html__( 'Blog Posts', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'blog_post_comments',
					'type'        => esc_html__( 'Blog Posts Comments', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'blog_button_text',
					'type'        => esc_html__( 'Blog Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Profile_Card',
		];

		$nodes_to_translate[ 'bdt-flip-box' ] = [
			'conditions' => [ 'widgetType' => 'bdt-flip-box' ],
			'fields'     => [
				[
					'field'       => 'front_title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'front_description_text',
					'type'        => esc_html__( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'back_title_text',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'back_description_text',
					'type'        => esc_html__( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-helpdesk' ] = [
			'conditions' => [ 'widgetType' => 'bdt-helpdesk' ],
			'fields'     => [
				[
					'field'       => 'helpdesk_title',
					'type'        => esc_html__( 'Main Icon Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messenger_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'messenger_onclick_event',
					'type'        => esc_html__( 'OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'whatsapp_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'whatsapp_onclick_event',
					'type'        => esc_html__( 'OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'telegram_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'telegram_onclick_event',
					'type'        => esc_html__( 'OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'custom_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'custom_onclick_event',
					'type'        => esc_html__( 'OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'mailto_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'mailto_subject',
					'type'        => esc_html__( 'Subject', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'mailto_body',
					'type'        => esc_html__( 'Body Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'mailto_onclick_event',
					'type'        => esc_html__( 'OnClick Event', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-iconnav' ] = [
			'conditions' => [ 'widgetType' => 'bdt-iconnav' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_IconNav',
		];

		$nodes_to_translate[ 'bdt-image-compare' ] = [
			'conditions' => [ 'widgetType' => 'bdt-image-compare' ],
			'fields'     => [
				[
					'field'       => 'before_label',
					'type'        => esc_html__( 'Before Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'after_label',
					'type'        => esc_html__( 'After Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-instagram' ] = [
			'conditions' => [ 'widgetType' => 'bdt-instagram' ],
			'fields'     => [
				[
					'field'       => 'username',
					'type'        => esc_html__( 'Username', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'follow_me_text',
					'type'        => esc_html__( 'Follow Me Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-instagram-feed' ] = [
			'conditions' => [ 'widgetType' => 'bdt-instagram-feed' ],
			'fields'     => [
				[
					'field'       => 'buttontext',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'followtext',
					'type'        => esc_html__( 'Follow Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'lightbox' ] = [
			'conditions' => [ 'widgetType' => 'lightbox' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'content_caption',
					'type'        => esc_html__( 'Content Caption', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-mailchimp' ] = [
			'conditions' => [ 'widgetType' => 'bdt-mailchimp' ],
			'fields'     => [
				[
					'field'       => 'before_text',
					'type'        => esc_html__( 'Before Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_field_placeholder',
					'type'        => esc_html__( 'Email Field Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'after_text',
					'type'        => esc_html__( 'After Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-marker' ] = [
			'conditions' => [ 'widgetType' => 'bdt-marker' ],
			'fields'     => [
				[
					'field'       => 'caption',
					'type'        => esc_html__( 'Caption', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Marker',
		];

		$nodes_to_translate[ 'bdt-member' ] = [
			'conditions' => [ 'widgetType' => 'bdt-member' ],
			'fields'     => [
				[
					'field'       => 'name',
					'type'        => esc_html__( 'Member Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_text',
					'type'        => esc_html__( 'Member Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'role',
					'type'        => esc_html__( 'Member Role', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],

			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Team_Member',
		];

		$nodes_to_translate[ 'bdt-modal' ] = [
			'conditions' => [ 'widgetType' => 'bdt-modal' ],
			'fields'     => [
				[
					'field'       => 'modal_custom_id',
					'type'        => esc_html__( 'Modal Selector', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'header',
					'type'        => esc_html__( 'Header', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'footer',
					'type'        => esc_html__( 'Footer', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-news-ticker' ] = [
			'conditions' => [ 'widgetType' => 'bdt-news-ticker' ],
			'fields'     => [
				[
					'field'       => 'news_label',
					'type'        => esc_html__( 'Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-offcanvas' ] = [
			'conditions' => [ 'widgetType' => 'bdt-offcanvas' ],
			'fields'     => [
				[
					'field'       => 'offcanvas_custom_id',
					'type'        => esc_html__( 'Offcanvas Selector', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'custom_content_before',
					'type'        => esc_html__( 'Custom Content Before', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'custom_content_after',
					'type'        => esc_html__( 'Custom Content After', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-open-street-map' ] = [
			'conditions' => [ 'widgetType' => 'bdt-open-street-map' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Open_Street_Map',
		];

		$nodes_to_translate[ 'bdt-panel-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-panel-slider' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Panel_Slider',
		];

		$nodes_to_translate[ 'bdt-post-block' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-block' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-block-modern' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-block-modern' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-card' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-grid' ],
			'fields'     => [
				[
					'field'       => 'tags_string',
					'type'        => esc_html__( 'Tags String', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-grid-tab' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-grid-tab' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-testimonial-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-testimonial-grid' ],
			'fields'     => [
				[
					'field'       => 'filter_custom_text_all',
					'type'        => esc_html__( 'Custom Text (All)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'filter_custom_text_filter',
					'type'        => esc_html__( 'Custom Text (Filter)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-tags-cloud' ] = [
			'conditions' => [ 'widgetType' => 'bdt-tags-cloud' ],
			'fields'     => [
				[
					'field'       => 'custom_post_type_input',
					'type'        => esc_html__( 'Custom Post Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-slider' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-price-list' ] = [
			'conditions' => [ 'widgetType' => 'bdt-price-list' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Price_List',
		];

		$nodes_to_translate[ 'bdt-price-table' ] = [
			'conditions' => [ 'widgetType' => 'bdt-price-table' ],
			'fields'     => [
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'sub_heading',
					'type'        => esc_html__( 'Subtitle', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'currency_symbol_custom',
					'type'        => esc_html__( 'Custom Symbol', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'price',
					'type'        => esc_html__( 'Price', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'period',
					'type'        => esc_html__( 'Period', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'item_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'tooltip_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'footer_additional_info',
					'type'        => esc_html__( 'Additional Info', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'ribbon_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-progress-pie' ] = [
			'conditions' => [ 'widgetType' => 'bdt-progress-pie' ],
			'fields'     => [
				[
					'field'       => 'percent',
					'type'        => esc_html__( 'Percent', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Progress Pie Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'before',
					'type'        => esc_html__( 'Before Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Middle Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'after',
					'type'        => esc_html__( 'After Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-protected-content' ] = [
			'conditions' => [ 'widgetType' => 'bdt-protected-content' ],
			'fields'     => [
				[
					'field'       => 'content_password',
					'type'        => esc_html__( 'Set Password', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'protected_custom_content',
					'type'        => esc_html__( 'Custom Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'warning_message_template',
					'type'        => esc_html__( 'Enter Template ID', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'warning_message_anywhere_template',
					'type'        => esc_html__( 'Enter Template ID', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'warning_message_text',
					'type'        => esc_html__( 'Custom Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-qrcode' ] = [
			'conditions' => [ 'widgetType' => 'bdt-qrcode' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'label',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-scroll-button' ] = [
			'conditions' => [ 'widgetType' => 'bdt-scroll-button' ],
			'fields'     => [
				[
					'field'       => 'scroll_button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'section_id',
					'type'        => esc_html__( 'Section ID', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-scroll-image' ] = [
			'conditions' => [ 'widgetType' => 'bdt-scroll-image' ],
			'fields'     => [
				[
					'field'       => 'caption',
					'type'        => esc_html__( 'Caption', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'badge_text',
					'type'        => esc_html__( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-scrollnav' ] = [
			'conditions' => [ 'widgetType' => 'bdt-scrollnav' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Scrollnav',
		];

		$nodes_to_translate[ 'bdt-search' ] = [
			'conditions' => [ 'widgetType' => 'bdt-search' ],
			'fields'     => [
				[
					'field'       => 'placeholder',
					'type'        => esc_html__( 'Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Search Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-slider' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Slider',
		];

		$nodes_to_translate[ 'bdt-slideshow' ] = [
			'conditions' => [ 'widgetType' => 'bdt-slideshow' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Slideshow',
		];

		$nodes_to_translate[ 'bdt-social-share' ] = [
			'conditions' => [ 'widgetType' => 'bdt-social-share' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Social_Share',
		];

		$nodes_to_translate[ 'bdt-switcher' ] = [
			'conditions' => [ 'widgetType' => 'bdt-switcher' ],
			'fields'     => [
				[
					'field'       => 'switch_a_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'switch_b_title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'switch_a_content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'switch_b_content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-table' ] = [
			'conditions' => [ 'widgetType' => 'bdt-table' ],
			'fields'     => [
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-table-of-content' ] = [
			'conditions' => [ 'widgetType' => 'bdt-table-of-content' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'context',
					'type'        => esc_html__( 'Index Area (any class/id selector)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'toc_index_header',
					'type'        => esc_html__( 'Index Header Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'toc_sticky_edge',
					'type'        => esc_html__( 'Scroll Until', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-tabs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Tabs',
		];

		$nodes_to_translate[ 'bdt-thumb-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-thumb-gallery' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-timeline' ] = [
			'conditions' => [ 'widgetType' => 'bdt-timeline' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Timeline',
		];

		$nodes_to_translate[ 'bdt-toggle' ] = [
			'conditions' => [ 'widgetType' => 'bdt-toggle' ],
			'fields'     => [
				[
					'field'       => 'toggle_title',
					'type'        => esc_html__( 'Normal Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'toggle_open_title',
					'type'        => esc_html__( 'Opened Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'toggle_content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-trailer-box' ] = [
			'conditions' => [ 'widgetType' => 'bdt-trailer-box' ],
			'fields'     => [
				[
					'field'       => 'pre_title',
					'type'        => esc_html__( 'Pre Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-user-login' ] = [
			'conditions' => [ 'widgetType' => 'bdt-user-login' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'logged_in_custom_message',
					'type'        => esc_html__( 'Custom Message', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'user_label',
					'type'        => esc_html__( 'Username Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'user_placeholder',
					'type'        => esc_html__( 'Username Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'password_label',
					'type'        => esc_html__( 'Password Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'password_placeholder',
					'type'        => esc_html__( 'Password Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_User_Login',
		];

		$nodes_to_translate[ 'bdt-user-register' ] = [
			'conditions' => [ 'widgetType' => 'bdt-user-register' ],
			'fields'     => [
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'first_name_label',
					'type'        => esc_html__( 'First Name Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'first_name_placeholder',
					'type'        => esc_html__( 'First Name Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'last_name_label',
					'type'        => esc_html__( 'Last Name Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'last_name_placeholder',
					'type'        => esc_html__( 'Last Name Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_label',
					'type'        => esc_html__( 'Email Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'email_placeholder',
					'type'        => esc_html__( 'Email Placeholder', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'additional_message',
					'type'        => esc_html__( 'Additional Message', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-video-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-video-gallery' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Video_Gallery',
		];

		$nodes_to_translate[ 'bdt-video-player' ] = [
			'conditions' => [ 'widgetType' => 'bdt-video-player' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'source',
					'type'        => esc_html__( 'Video Source', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-weather' ] = [
			'conditions' => [ 'widgetType' => 'bdt-weather' ],
			'fields'     => [
				[
					'field'       => 'location',
					'type'        => esc_html__( 'Location', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'country',
					'type'        => esc_html__( 'Country (optional)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-wc-add-to-cart' ] = [
			'conditions' => [ 'widgetType' => 'bdt-wc-add-to-cart' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Add to Cart Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-wc-slider' ] = [
			'conditions' => [ 'widgetType' => 'bdt-wc-slider' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-static-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-static-carousel' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Static_Carousel',
		];

		$nodes_to_translate[ 'bdt-content-switcher' ] = [
			'conditions' => [ 'widgetType' => 'bdt-content-switcher' ],
			'fields'     => [
				[
					'field'       => 'badge_text',
					'type'        => __( 'Badge Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Content_Switcher',
		];

		$nodes_to_translate[ 'bdt-dark-mode' ] = [
			'conditions' => [ 'widgetType' => 'bdt-dark-mode' ],
			'fields'     => [
				[
					'field'       => 'ignore_element',
					'type'        => __( 'Ignore Elements', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$nodes_to_translate[ 'bdt-comparison-list' ] = [
			'conditions' => [ 'widgetType' => 'bdt-comparison-list' ],
			'fields'     => [
				[
					'field'       => 'comparison_list_title',
					'type'        => __( 'Feature Title', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Comparison_List',
		];

		$nodes_to_translate[ 'bdt-cursor-effects' ] = [
			'conditions' => [ 'widgetType' => 'bdt-cursor-effects' ],
			'fields'     => [
				[
					'field'       => 'element_pack_cursor_effects_text_label',
					'type'        => __( 'Text Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-cart' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-cart' ],
			'fields'     => [
				[
					'field'       => 'cart_action_button_text',
					'type'        => __( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-mini-cart' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-mini-cart' ],
			'fields'     => [
				[
					'field'       => 'custom_widget_cart_title',
					'type'        => __( 'Cart Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'custom_content_before',
					'type'        => __( 'Custom Content Before', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'custom_content_after',
					'type'        => __( 'Custom Content After', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-product' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-product' ],
			'fields'     => [
				[
					'field'       => 'filter_custom_text_all',
					'type'        => __( 'Custom Text (All)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'filter_custom_text_filter',
					'type'        => __( 'Custom Text (Filter)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-floating-knowledgebase' ] = [
			'conditions' => [ 'widgetType' => 'bdt-floating-knowledgebase' ],
			'fields'     => [
				[
					'field'       => 'helper_text_heading_label',
					'type'        => __( 'Heading Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'helper_text_label',
					'type'        => __( 'Text Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'support_link_text',
					'type'        => __( 'Support Link Label', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'no_search_result',
					'type'        => __( 'No Search Result Text', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'title',
					'type'        => __( 'Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description',
					'type'        => __( 'Description', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-receipt' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-receipt' ],
			'fields'     => [
				[
					'field'       => 'error',
					'type'        => __( 'Error Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'success',
					'type'        => __( 'Success Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-totals' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-totals' ],
			'fields'     => [
				[
					'field'       => 'total_goal',
					'type'        => __( 'Goal Amount', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'message',
					'type'        => __( 'Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'link_text',
					'type'        => __( 'Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];

		$nodes_to_translate[ 'bdt-image-stack' ] = [
			'conditions' => [ 'widgetType' => 'bdt-image-stack' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Image_Stack',
		];

		$nodes_to_translate[ 'bdt-interactive-tabs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-interactive-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Interactive_Tabs',
		];

		$nodes_to_translate[ 'bdt-notification' ] = [
			'conditions' => [ 'widgetType' => 'bdt-notification' ],
			'fields'     => [
				[
					'field'       => 'notification_content',
					'type'        => esc_html__( 'Content', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-gallery' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-gallery' ],
			'fields'     => [
				[
					'field'       => 'post_link_text',
					'type'        => esc_html__( 'Details Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'lightbox_link_text',
					'type'        => esc_html__( 'Lightbox Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'filter_custom_text_all',
					'type'        => esc_html__( 'Custom Text (All)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'filter_custom_text_filter',
					'type'        => esc_html__( 'Custom Text (Filter)', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-post-list' ] = [
			'conditions' => [ 'widgetType' => 'bdt-post-list' ],
			'fields'     => [
				[
					'field'       => 'header_title_text',
					'type'        => esc_html__( 'Filter Bar Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-product-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-product-carousel' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Product_Carousel',
		];

		$nodes_to_translate[ 'bdt-product-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-product-grid' ],
			'fields'     => [
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Product_Grid',
		];

		$nodes_to_translate[ 'bdt-review-card-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-review-card-grid' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Review_Card_Grid',
		];

		$nodes_to_translate[ 'bdt-review-card-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-review-card-carousel' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Review_Card_Carousel',
		];

		$nodes_to_translate[ 'bdt-review-card' ] = [
			'conditions' => [ 'widgetType' => 'bdt-review-card' ],
			'fields'     => [
				[
					'field'       => 'reviewer_name',
					'type'        => esc_html__( 'Reviewer Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'reviewer_job_title',
					'type'        => esc_html__( 'Reviewer Job Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'review_text',
					'type'        => esc_html__( 'Review Text', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		$nodes_to_translate[ 'bdt-total-count' ] = [
			'conditions' => [ 'widgetType' => 'bdt-total-count' ],
			'fields'     => [
				[
					'field'       => 'content_text',
					'type'        => esc_html__( 'Count Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'counter_prefix',
					'type'        => esc_html__( 'Prefix', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'counter_suffix',
					'type'        => esc_html__( 'Suffix', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-time-zone' ] = [
			'conditions' => [ 'widgetType' => 'bdt-time-zone' ],
			'fields'     => [
				[
					'field'       => 'input_country',
					'type'        => esc_html__( 'Country Name', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-sub-menu' ] = [
			'conditions' => [ 'widgetType' => 'bdt-sub-menu' ],
			'fields'     => [
				[
					'field'       => 'submenu_header_title',
					'type'        => esc_html__( 'Submenu Header Title', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Sub_Menu',
		];

		$nodes_to_translate[ 'bdt-static-grid-tab' ] = [
			'conditions' => [ 'widgetType' => 'bdt-static-grid-tab' ],
			'fields'     => [
				[
					'field'       => 'title',
					'type'        => esc_html__('Title', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'text',
					'type'        => esc_html__('Text', 'bdthemes-element-pack'),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__('Read More Text', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Static_Grid_Tab',
		];

		$nodes_to_translate[ 'bdt-slinky-vertical-menu' ] = [
			'conditions' => [ 'widgetType' => 'bdt-slinky-vertical-menu' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Slinky_Vertical_Menu',
		];

		$nodes_to_translate[ 'bdt-remote-arrows' ] = [
			'conditions' => [ 'widgetType' => 'bdt-remote-arrows' ],
			'fields'     => [
				[
					'field'       => 'next_text',
					'type'        => esc_html__('Next Text', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'prev_text',
					'type'        => esc_html__('Previous Text', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-reading-timer' ] = [
			'conditions' => [ 'widgetType' => 'bdt-reading-timer' ],
			'fields'     => [
				[
					'field'       => 'reading_timer_minute_text',
					'type'        => esc_html__('Minute Text', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'reading_timer_seconds_text',
					'type'        => esc_html__('Seconds Text', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-icon-mobile-menu' ] = [
			'conditions' => [ 'widgetType' => 'bdt-icon-mobile-menu' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Icon_Mobile_Menu',
		];

		$nodes_to_translate[ 'bdt-google-reviews' ] = [
			'conditions' => [ 'widgetType' => 'bdt-google-reviews' ],
			'fields'     => [
				[
					'field'       => 'custom_lang',
					'type'        => esc_html__('Custom Language', 'bdthemes-element-pack'),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-form' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-form' ],
			'fields'     => [
				[
					'field'       => 'continue_button_title',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-give-donor-wall' ] = [
			'conditions' => [ 'widgetType' => 'bdt-give-donor-wall' ],
			'fields'     => [
				[
					'field'       => 'loadmore_text',
					'type'        => esc_html__( 'Load More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'readmore_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-facebook-feed-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-facebook-feed-carousel' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-facebook-feed' ] = [
			'conditions' => [ 'widgetType' => 'bdt-facebook-feed' ],
			'fields'     => [
				[
					'field'       => 'read_more_text',
					'type'        => esc_html__( 'Read More Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-edd-tabs' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-tabs' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_EDD_Tabs',
		];

		$nodes_to_translate[ 'bdt-edd-checkout' ] = [
			'conditions' => [ 'widgetType' => 'bdt-edd-checkout' ],
			'fields'     => [
				[
					'field'       => 'checkout_action_button_text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-creative-button' ] = [
			'conditions' => [ 'widgetType' => 'bdt-creative-button' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Button Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-charitable-registration' ] = [
			'conditions' => [ 'widgetType' => 'bdt-charitable-registration' ],
			'fields'     => [
				[
					'field'       => 'logged_in_message',
					'type'        => esc_html__( 'Logged In Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'registration_link_text',
					'type'        => esc_html__( 'Registration Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-charitable-login' ] = [
			'conditions' => [ 'widgetType' => 'bdt-charitable-login' ],
			'fields'     => [
				[
					'field'       => 'logged_in_message',
					'type'        => esc_html__( 'Logged In Message', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'registration_link_text',
					'type'        => esc_html__( 'Registration Link Text', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-calendly' ] = [
			'conditions' => [ 'widgetType' => 'bdt-calendly' ],
			'fields'     => [
				[
					'field'       => 'calendly_username',
					'type'        => esc_html__( 'Calendly Username', 'bdthemes-element-pack' ),
					'editor_type' => 'LINE',
				],
			],
		];

		$nodes_to_translate[ 'bdt-brand-grid' ] = [
			'conditions' => [ 'widgetType' => 'bdt-brand-grid' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Brand_Grid',
		];

		$nodes_to_translate[ 'bdt-brand-carousel' ] = [
			'conditions' => [ 'widgetType' => 'bdt-brand-carousel' ],
			'fields'     => [],
			'integration-class' => __NAMESPACE__ . '\\WPML_ElementPack_Brand_Carousel',
		];

		$nodes_to_translate[ 'bdt-animated-link' ] = [
			'conditions' => [ 'widgetType' => 'bdt-animated-link' ],
			'fields'     => [
				[
					'field'       => 'pre_heading',
					'type'        => esc_html__( 'Prefix Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'animated_heading',
					'type'        => esc_html__( 'Animated Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'post_heading',
					'type'        => esc_html__( 'Post Heading', 'bdthemes-element-pack' ),
					'editor_type' => 'AREA',
				],
			],
		];

		return $nodes_to_translate;
	}

	/**
	 * Returns the instance.
	 * @since  3.1.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
}
