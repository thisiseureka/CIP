<?php

namespace ElementPack\Modules\PostGallery\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use ElementPack\Utils;

use ElementPack\Base\Module_Base;
use ElementPack\Traits\Global_Mask_Controls;
use ElementPack\Traits\Global_Widget_Controls;
use ElementPack\Includes\Controls\GroupQuery\Group_Control_Query;



use ElementPack\Modules\PostGallery\Skins;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly
class Post_Gallery extends Module_Base {
	use Group_Control_Query;
	use Global_Mask_Controls;
	use Global_Widget_Controls;

	private $_query = null;

	public function get_name() {
		return 'bdt-post-gallery';
	}

	public function get_title() {
		return BDTEP . esc_html__( 'Post Gallery', 'bdthemes-element-pack' );
	}

	public function get_icon() {
		return 'bdt-wi-post-gallery';
	}

	public function get_categories() {
		return [ 'element-pack' ];
	}

	public function get_keywords() {
		return [ 'post', 'gallery', 'blog', 'recent', 'news' ];
	}

	public function get_style_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'ep-styles' ];
		} else {
			return [ 'ep-font', 'ep-post-gallery' ];
		}
	}

	public function get_script_depends() {
		if ( $this->ep_is_edit_mode() ) {
			return [ 'imagesloaded', 'tilt', 'ep-scripts' ];
		} else {
			return [ 'imagesloaded', 'tilt', 'ep-post-gallery' ];
		}
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/iScykjTKlNA';
	}

	public function register_skins() {
		$this->add_skin( new Skins\Skin_Abetis( $this ) );
		$this->add_skin( new Skins\Skin_Fedara( $this ) );
		$this->add_skin( new Skins\Skin_Trosia( $this ) );
	}

	public function get_query() {
		return $this->_query;
	}

	public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }
	protected function is_dynamic_content(): bool {
		return true;
	}
	
	public function register_controls() {
		$this->register_section_controls();
	}

	private function register_section_controls() {
		$this->start_controls_section(
			'section_content_layout',
			[ 
				'label' => esc_html__( 'Layout', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[ 
				'label'              => esc_html__( 'Columns', 'bdthemes-element-pack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => [ 
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_pagination',
			[ 
				'label' => esc_html__( 'Pagination', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[ 
				'name'         => 'thumbnail_size',
				'label'        => esc_html__( 'Image Size', 'bdthemes-element-pack' ),
				'exclude'      => [ 'custom' ],
				'default'      => 'medium',
				'prefix_class' => 'bdt-post-gallery--thumbnail-size-',
			]
		);

		$this->add_control(
			'image_mask_popover',
			[ 
				'label'        => esc_html__( 'Image Mask', 'bdthemes-element-pack' ) . BDTEP_NC,
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'render_type'  => 'template',
				'return_value' => 'yes',
			]
		);

		//Global Image Mask Controls
		$this->register_image_mask_controls();

		$this->add_control(
			'masonry',
			[ 
				'label'       => esc_html__( 'Masonry', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'Masonry will not work if you not set filter.', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => [ 
					'columns!' => '1',
				],
				'prefix_class' => 'bdt-pg-masonry-',
				'render_type'  => 'template',
			]
		);

		$this->add_responsive_control(
			'item_ratio',
			[ 
				'label'     => esc_html__( 'Item Height', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 250,
				],
				'range'     => [ 
					'px' => [ 
						'min'  => 50,
						'max'  => 500,
						'step' => 5,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-gallery-thumbnail img' => 'height: {{SIZE}}px',
				],
				'condition' => [ 
					'masonry!' => 'yes',

				],
			]
		);

		$this->end_controls_section();

		//New Query Builder Settings
		$this->start_controls_section(
			'section_post_query_builder',
			[ 
				'label' => __( 'Query', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->register_query_builder_controls();

		$this->end_controls_section();


		$this->start_controls_section(
			'filter_bar',
			[ 
				'label' => esc_html__( 'Filter Bar', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'show_filter_bar',
			[ 
				'label' => esc_html__( 'Show', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$post_types = $this->getGroupControlQueryPostTypes();

		foreach ( $post_types as $key => $post_type ) {
			$taxonomies = $this->get_taxonomies( $key );
			if ( ! $taxonomies[ $key ] ) {
				continue;
			}
			$this->add_control(
				'taxonomy_' . $key,
				[ 
					'label'     => __( 'Taxonomies', 'bdthemes-element-pack' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => $taxonomies[ $key ],
					'default'   => key( $taxonomies[ $key ] ),
					'condition' => [ 
						'posts_source'    => $key,
						'show_filter_bar' => 'yes'
					],
				]
			);
		}

		$this->add_control(
			'active_hash',
			[ 
				'label'     => esc_html__( 'Hash Location', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => [ 
					'show_filter_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'hash_top_offset',
			[ 
				'label'      => esc_html__( 'Top Offset ', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '' ],
				'range'      => [ 
					'px' => [ 
						'min'  => 1,
						'max'  => 1000,
						'step' => 5,
					],

				],
				'default'    => [ 
					'unit' => 'px',
					'size' => 70,
				],
				'condition'  => [ 
					'active_hash'     => 'yes',
					'show_filter_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'hash_scrollspy_time',
			[ 
				'label'      => esc_html__( 'Scrollspy Time', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'ms', '' ],
				'range'      => [ 
					'px' => [ 
						'min'  => 500,
						'max'  => 5000,
						'step' => 1000,
					],
				],
				'default'    => [ 
					'unit' => 'px',
					'size' => 1000,
				],
				'condition'  => [ 
					'active_hash'     => 'yes',
					'show_filter_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_custom_text',
			[ 
				'label'       => esc_html__( 'Custom Text', 'bdthemes-element-pack' ) . BDTEP_NC,
				'description' => esc_html__( 'If you active this option. You can change (All, Filter) text without translator plugin. If you wish you can use translator plugin also.', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
				'condition'   => [ 
					'show_filter_bar' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_custom_text_all',
			[ 
				'label'     => __( 'Custom Text (All)', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => __( 'All', 'bdthemes-element-pack' ),
				'condition' => [ 
					'show_filter_bar'    => 'yes',
					'filter_custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_custom_text_filter',
			[ 
				'label'     => __( 'Custom Text (Filter)', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [ 'active' => true ],
				'default'   => __( 'Filter', 'bdthemes-element-pack' ),
				'condition' => [ 
					'show_filter_bar'    => 'yes',
					'filter_custom_text' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_additional',
			[ 
				'label' => esc_html__( 'Additional', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'overlay_animation',
			[ 
				'label'     => esc_html__( 'Overlay Animation', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => element_pack_transition_options(),
				'condition' => [ 
					'_skin!' => 'bdt-trosia',
				],
			]
		);

		$this->add_control(
			'show_title',
			[ 
				'label'   => esc_html__( 'Title', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_title_link',
			[ 
				'label'     => esc_html__( 'Title Link', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [ 
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_tag',
			[ 
				'label'     => esc_html__( 'Title HTML Tag', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => element_pack_title_tags(),
				'default'   => 'h4',
				'condition' => [ 
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_excerpt',
			[ 
				'label' => esc_html__( 'Show Text', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'excerpt_limit',
			[ 
				'label'       => esc_html__( 'Text Limit', 'bdthemes-element-pack' ),
				'description' => esc_html__( 'It\'s just work for main content, but not working with excerpt. If you set 0 so you will get full main content.', 'bdthemes-element-pack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 10,
				'condition'   => [ 
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
            'ellipsis',
            [
                'label' => esc_html__('Ellipsis', 'bdthemes-element-pack') . BDTEP_NC,
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'show_excerpt' => 'yes',
					'excerpt_limit!' => [0, ''],
                ],
				'ai' => [
                    'active' => false,
                ],
            ]
        );

		$this->add_control(
			'strip_shortcode',
			[ 
				'label'     => esc_html__( 'Strip Shortcode', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [ 
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_category',
			[ 
				'label' => esc_html__( 'Tags', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'show_link',
			[ 
				'label'   => esc_html__( 'Show Link', 'bdthemes-element-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [ 
					'post'     => esc_html__( 'Details Link', 'bdthemes-element-pack' ),
					'lightbox' => esc_html__( 'Lightbox Link', 'bdthemes-element-pack' ),
					'both'     => esc_html__( 'Both', 'bdthemes-element-pack' ),
					'none'     => esc_html__( 'None', 'bdthemes-element-pack' ),
				],
			]
		);

		$this->add_control(
			'external_link',
			[ 
				'label'      => esc_html__( 'Show in new Tab (Details Link/Title)', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => [ 
					'relation' => 'or',
					'terms'    => [ 
						[ 
							'name'  => 'show_title',
							'value' => 'yes'
						],
						[ 
							'name'  => 'show_link',
							'value' => 'post'
						],
						[ 
							'name'  => 'show_link',
							'value' => 'both'
						],
					]
				],
			]
		);

		$this->add_control(
			'link_type',
			[ 
				'label'     => esc_html__( 'Link Type', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon',
				'options'   => [ 
					'icon' => esc_html__( 'Icon', 'bdthemes-element-pack' ),
					'text' => esc_html__( 'Text', 'bdthemes-element-pack' ),
				],
				'condition' => [ 
					'show_link!' => 'none',
				]
			]
		);

		$this->add_control(
			'post_link_text',
			[
				'label'       => esc_html__('Details Link Text', 'bdthemes-element-pack') . BDTEP_NC,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__('VIEW', 'bdthemes-element-pack'),
				'condition' => [
					'show_link' => [ 'post', 'both' ],
					'link_type'		=> 'text',
				],
				'label_block' => false
			]
		);

        $this->add_control(
			'lightbox_link_text',
			[
				'label'       => esc_html__('Lightbox Link Text', 'bdthemes-element-pack') . BDTEP_NC,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__('ZOOM', 'bdthemes-element-pack'),
				'condition' => [
					'show_link' => [ 'lightbox', 'both' ],
					'link_type'		=> 'text',
				],
				'label_block' => false
			]
		);

		$this->add_control(
			'tilt_show',
			[ 
				'label' => esc_html__( 'Tilt Effect', 'bdthemes-element-pack' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'tilt_scale',
			[ 
				'label'     => esc_html__( 'Zoom on Hover', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'tilt_show' => 'yes',
				]
			]
		);

		$this->add_control(
			'lightbox_animation',
			[ 
				'label'     => esc_html__( 'Lightbox Animation', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide',
				'options'   => [ 
					'slide' => esc_html__( 'Slide', 'bdthemes-element-pack' ),
					'fade'  => esc_html__( 'Fade', 'bdthemes-element-pack' ),
					'scale' => esc_html__( 'Scale', 'bdthemes-element-pack' ),
				],
				'condition' => [ 
					'show_link' => [ 'both', 'lightbox' ],
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'lightbox_autoplay',
			[ 
				'label'     => __( 'Lightbox Autoplay', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'show_link' => [ 'both', 'lightbox' ],
				]
			]
		);

		$this->add_control(
			'lightbox_pause',
			[ 
				'label'     => __( 'Lightbox Pause on Hover', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [ 
					'show_link'         => [ 'both', 'lightbox' ],
					'lightbox_autoplay' => 'yes'
				],

			]
		);

		$this->add_control(
			'grid_animation_type',
			[ 
				'label'     => esc_html__( 'Grid Entrance Animation', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => element_pack_transition_options(),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'grid_anim_delay',
			[ 
				'label'      => esc_html__( 'Animation delay', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'ms', '' ],
				'range'      => [ 
					'ms' => [ 
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
				],
				'default'    => [ 
					'unit' => 'ms',
					'size' => 300,
				],
				'condition'  => [ 
					'grid_animation_type!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design_layout',
			[ 
				'label' => esc_html__( 'Items', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[ 
				'label'     => esc_html__( 'Column Gap', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 30,
				],
				'range'     => [ 
					'px' => [ 
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery.bdt-grid'     => 'margin-left: -{{SIZE}}px',
					'{{WRAPPER}} .bdt-post-gallery.bdt-grid > *' => 'padding-left: {{SIZE}}px',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[ 
				'label'     => esc_html__( 'Row Gap', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ 
					'size' => 30,
				],
				'range'     => [ 
					'px' => [ 
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery.bdt-grid'     => 'margin-top: -{{SIZE}}px',
					'{{WRAPPER}} .bdt-post-gallery.bdt-grid > *' => 'margin-top: {{SIZE}}px',
					'{{WRAPPER}}.bdt-pg-masonry-yes .bdt-post-gallery-wrapper' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'item_border_radius',
			[ 
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-thumbnail, {{WRAPPER}} .bdt-post-gallery .bdt-overlay, {{WRAPPER}} .bdt-post-gallery .bdt-post-gallery-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ 
					'_skin' => '',
				],
			]
		);

		$this->add_control(
			'item_skin_border_radius',
			[ 
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay'           => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				],
				'condition'  => [ 
					'_skin!' => '',
				],
			]
		);

		$this->add_control(
			'overlay_blur_effect',
			[ 
				'label'       => esc_html__( 'Glassmorphism', 'bdthemes-element-pack' ) . BDTEP_NC,
				'type'        => Controls_Manager::SWITCHER,
				'description' => sprintf( __( 'This feature will not work in the Firefox browser untill you enable browser compatibility so please %1s look here %2s', 'bdthemes-element-pack' ), '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter#Browser_compatibility" target="_blank">', '</a>' ),
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'overlay_blur_level',
			[ 
				'label'     => __( 'Blur Level', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'min'  => 0,
						'step' => 1,
						'max'  => 50,
					]
				],
				'default'   => [ 
					'size' => 5
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay' => 'backdrop-filter: blur({{SIZE}}px); -webkit-backdrop-filter: blur({{SIZE}}px);'
				],
				'condition' => [ 
					'overlay_blur_effect' => 'yes'
				]
			]
		);

		$this->add_control(
			'overlay_background',
			[ 
				'label'     => esc_html__( 'Overlay Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay'           => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-post-gallery .bdt-post-gallery-desc' => 'background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,{{VALUE)}} 70%); background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,{{VALUE)}} 70%);',
				],
			]
		);

		$this->add_control(
			'overlay_gap',
			[ 
				'label'     => esc_html__( 'Overlay Gap', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item .bdt-overlay' => 'margin: {{SIZE}}px',
				],
				'condition' => [ 
					'_skin!' => 'bdt-trosia',
				],
			]
		);

		$this->add_control(
			'overlay_content_alignment',
			[ 
				'label'        => __( 'Overlay Content Alignment', 'bdthemes-element-pack' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [ 
					'left'   => [ 
						'title' => __( 'Left', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [ 
						'title' => __( 'Center', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [ 
						'title' => __( 'Right', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => 'center',
				'prefix_class' => 'bdt-custom-gallery-skin-fedara-style-',
				'selectors'    => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay' => 'text-align: {{VALUE}}',
				],
				'separator'    => 'before',
				'condition'    => [ 
					'_skin!' => 'bdt-trosia',
				],
			]
		);

		$this->add_control(
			'overlay_content_position',
			[ 
				'label'                => __( 'Overlay Content Vertical Position', 'bdthemes-element-pack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [ 
					'top'    => [ 
						'title' => __( 'Top', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [ 
						'title' => __( 'Middle', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [ 
						'title' => __( 'Bottom', 'bdthemes-element-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [ 
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'default'              => 'middle',
				'selectors'            => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-overlay' => 'justify-content: {{VALUE}}',
				],
				'separator'            => 'after',
				'condition'            => [ 
					'_skin!' => 'bdt-trosia',
				],
			]
		);

		$this->add_control(
			'title_color',
			[ 
				'label'     => esc_html__( 'Title Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item .bdt-gallery-item-title' => 'color: {{VALUE}};',
				],
				'condition' => [ 
					'show_title' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				//'scheme'    => Schemes\Typography::TYPOGRAPHY_1,
				'selector'  => '{{WRAPPER}} .bdt-gallery-item .bdt-gallery-item-title',
				'condition' => [ 
					'show_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			[ 
				'label'     => esc_html__( 'Text', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-post-gallery-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[ 
				'label'     => esc_html__( 'Margin', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-post-gallery-excerpt' => 'margin: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'     => 'excerpt_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-post-gallery .bdt-post-gallery-excerpt',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[ 
				'label'     => esc_html__( 'Button', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_link!' => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[ 
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[ 
				'label'     => esc_html__( 'Background Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[ 
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[ 
				'name'        => 'border',
				'label'       => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[ 
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_padding',
			[ 
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'      => 'typography',
				'label'     => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'selector'  => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link',
				'condition' => [ 
					'link_type' => 'text',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[ 
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'hover_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[ 
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 
					'border_border!' => '',
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-link:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_category',
			[ 
				'label'     => esc_html__( 'Tag', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_category' => 'yes',
				]
			]
		);

		$this->add_control(
			'category_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'category_background',
			[ 
				'label'     => esc_html__( 'Background', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[ 
				'name'        => 'tag_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag'
			]
		);

		$this->add_responsive_control(
			'tag_radius',
			[ 
				'label'      => esc_html__( 'Border Radius', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[ 
				'name'     => 'tag_shadow',
				'selector' => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag',
			]
		);
		
		$this->add_responsive_control(
			'tag_margin',
			[ 
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tag_padding',
			[ 
				'label'      => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [ 
					'{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'     => 'category_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				'selector' => '{{WRAPPER}} .bdt-post-gallery .bdt-gallery-item-tag',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_pagination',
			[ 
				'label'     => esc_html__( 'Pagination', 'bdthemes-element-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 
					'show_pagination' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_pagination_style' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			[ 
				'label' => esc_html__( 'Normal', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li a, {{WRAPPER}} ul.bdt-pagination li span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'      => 'pagination_background',
				'selector'  => '{{WRAPPER}} ul.bdt-pagination li a',
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[ 
				'name'     => 'pagination_border',
				'label'    => esc_html__( 'Border', 'bdthemes-element-pack' ),
				'selector' => '{{WRAPPER}} ul.bdt-pagination li a',
			]
		);

		$this->add_responsive_control(
			'pagination_offset',
			[ 
				'label'     => esc_html__( 'Offset', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-pagination' => 'margin-top: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_space',
			[ 
				'label'     => esc_html__( 'Spacing', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-pagination'     => 'margin-left: {{SIZE}}px;',
					'{{WRAPPER}} .bdt-pagination > *' => 'padding-left: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[ 
				'label'     => esc_html__( 'Padding', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li a' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_radius',
			[ 
				'label'     => esc_html__( 'Radius', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_arrow_size',
			[ 
				'label'     => esc_html__( 'Arrow Size', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li a svg' => 'height: {{SIZE}}px; width: auto;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[ 
				'name'     => 'pagination_typography',
				'label'    => esc_html__( 'Typography', 'bdthemes-element-pack' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} ul.bdt-pagination li a, {{WRAPPER}} ul.bdt-pagination li span',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			[ 
				'label' => esc_html__( 'Hover', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'pagination_hover_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_hover_border_color',
			[ 
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li a:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'     => 'pagination_hover_background',
				'selector' => '{{WRAPPER}} ul.bdt-pagination li a:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_active',
			[ 
				'label' => esc_html__( 'Active', 'bdthemes-element-pack' ),
			]
		);

		$this->add_control(
			'pagination_active_color',
			[ 
				'label'     => esc_html__( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li.bdt-active a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'pagination_active_border_color',
			[ 
				'label'     => esc_html__( 'Border Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} ul.bdt-pagination li.bdt-active a' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[ 
				'name'     => 'pagination_active_background',
				'selector' => '{{WRAPPER}} ul.bdt-pagination li.bdt-active a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		$this->register_style_controls_filter();
	}


	/**
	 * Get post query builder arguments
	 */
	public function query_posts( $posts_per_page ) {
		$settings = $this->get_settings();

		$args = [];
		if ( $posts_per_page ) {
			$args['posts_per_page'] = $posts_per_page;
			$args['paged']          = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
		}

		$default = $this->getGroupControlQueryArgs();
		$args    = array_merge( $default, $args );

		$this->_query = new \WP_Query( $args );
	}


	public function get_taxonomies( $post_type = '' ) {
		$_taxonomies = [];
		if ( $post_type ) {
			$taxonomies              = get_taxonomies( [ 'public' => true, 'object_type' => [ $post_type ] ], 'object' );
			$tax                     = array_diff_key( wp_list_pluck( $taxonomies, 'label', 'name' ), [] );
			$_taxonomies[ $post_type ] = count( $tax ) !== 0 ? $tax : '';
		}
		return $_taxonomies;
	}


	public function render() {
		$settings = $this->get_settings_for_display();

		$this->query_posts( $settings['posts_per_page'] );

		$wp_query = $this->get_query();

		if ( ! $wp_query->found_posts ) {
			return;
		}

		$this->render_header();

		while ( $wp_query->have_posts() ) {
			$wp_query->the_post();

			$this->render_post();
		}

		$this->render_footer();

		if ( $settings['show_pagination'] ) { ?>
			<div class="ep-pagination">
				<?php element_pack_post_pagination( $wp_query ); ?>
			</div>
			<?php
		}

		wp_reset_postdata();
	}

	public function render_thumbnail() {
		$settings = $this->get_settings_for_display();

		$settings['thumbnail_size'] = [ 
			'id' => get_post_thumbnail_id(),
		];

		$image_src           = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail_size' );
		$placeholder_img_src = Utils::get_placeholder_image_src();

		$image_mask = $settings['image_mask_popover'] == 'yes' ? ' bdt-image-mask' : '';
		$this->add_render_attribute( 'image-wrap', 'class', 'bdt-gallery-thumbnail' . $image_mask );

		?>
		<div <?php $this->print_render_attribute_string( 'image-wrap' ); ?>>
			<?php
			if ( ! $image_src ) {
				printf( '<img src="%1$s" alt="%2$s">', esc_url( $placeholder_img_src ), esc_html( get_the_title() ) );
			} else {
				print( wp_get_attachment_image(
					get_post_thumbnail_id(),
					$settings['thumbnail_size_size'],
					false,
					[ 
						'alt' => esc_html( get_the_title() )
					]
				) );
			}
			?>
		</div>
		<?php
	}

	protected function filter_menu_categories() {
		$settings           = $this->get_settings_for_display();
		$include_Categories = $settings['posts_include_term_ids'];
		$exclude_Categories = $settings['posts_exclude_term_ids'];
		$post_options       = [];

		$taxonomy = element_pack_get_taxonomy_by_post_type($settings['posts_source']);

		$params          = [ 
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'include'    => $include_Categories,
			'exclude'    => $exclude_Categories,
		];

		$post_categories = get_terms( $params );

		if ( is_wp_error( $post_categories ) ) {
			return $post_options;
		}

		if ( false !== $post_categories and is_array( $post_categories ) ) {
			foreach ( $post_categories as $category ) {
				$post_options[ $category->slug ] = $category->name;
			}
		}

		return $post_options;
	}

	public function render_filter_menu() {
		$settings   = $this->get_settings_for_display();
		$categories = $this->filter_menu_categories();
		$this->add_render_attribute(
			[ 
				'post-gallery-hash-data' => [ 
					'data-hash-settings' => [ 
						wp_json_encode(
							array_filter( [ 
								"id"                => '#bdt-post-gallery-' . $this->get_id(),
								'activeHash'        => isset( $settings['active_hash'] ) ? $settings['active_hash'] : false,
								'hashTopOffset'     => isset( $settings['hash_top_offset']['size'] ) ? $settings['hash_top_offset']['size'] : 0,
								'hashScrollspyTime' => isset( $settings['hash_scrollspy_time']['size'] ) ? $settings['hash_scrollspy_time']['size'] : 0,
							] )
						),
					],
				],
			]
		);

		?>
		<div id="bdt-post-gallery-<?php echo esc_attr( $this->get_id() ); ?>" class="bdt-ep-grid-filters-wrapper" <?php $this->print_render_attribute_string( 'post-gallery-hash-data' ); ?>>

			<button class="bdt-button bdt-button-default bdt-hidden@m" type="button">
				<?php if ( isset( $settings['filter_custom_text'] ) && ( $settings['filter_custom_text'] != 'yes' ) ) : ?>
					<?php esc_html_e( 'Filter', 'bdthemes-element-pack' ); ?>
				<?php else : ?>
					<?php esc_html_e( $settings['filter_custom_text_filter'], 'bdthemes-element-pack' ); ?>
				<?php endif; ?>
			</button>

			<div data-bdt-dropdown="mode: click;" class="bdt-dropdown bdt-margin-remove-top bdt-margin-remove-bottom bdt-drop">
				<ul class="bdt-nav bdt-dropdown-nav">

					<?php if ( isset( $settings['filter_custom_text'] ) && ( $settings['filter_custom_text'] != 'yes' ) ) : ?>
						<li class="bdt-active bdt-ep-grid-filter">
							<a href="#" class="bdt-option" data-slug>
								<?php esc_html_e( 'All', 'bdthemes-element-pack' ); ?>
							</a>
						</li>
					<?php else : ?>
						<li class="bdt-active bdt-ep-grid-filter">
							<a href="#" class="bdt-option" data-slug>
								<?php esc_html_e( $settings['filter_custom_text_all'], 'bdthemes-element-pack' ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php foreach ( $categories as $slug => $category ) { ?>
						<li class="bdt-ep-grid-filter">
							<a href="#" class="bdt-option" data-slug="<?php echo esc_attr( trim( $slug ) ); ?>">
								<?php echo esc_html( $category ); ?>
							</a>
						</li>
					<?php } ?>

				</ul>
			</div>

			<ul id="bdt-ep-grid-filters<?php echo esc_attr( $this->get_id() ); ?>" class="bdt-ep-grid-filters bdt-visible@m"
				data-bdt-margin>
				<li class="bdt-ep-grid-filter bdt-active">
					<?php if ( isset( $settings['filter_custom_text'] ) && ( $settings['filter_custom_text'] != 'yes' ) ) : ?>
						<a href="javascript:void(0);" class="bdt-option" data-slug>
							<?php esc_html_e( 'All', 'bdthemes-element-pack' ); ?>
						</a>
					<?php else : ?>
						<a href="javascript:void(0);" class="bdt-option" data-slug>
							<?php esc_html_e( $settings['filter_custom_text_all'], 'bdthemes-element-pack' ); ?>
						</a>
					<?php endif; ?>
				</li>

				<?php foreach ( $categories as $slug => $category ) { ?>
					<li class="bdt-ep-grid-filter">
						<a href="javascript:void(0);" class="bdt-option" data-slug="<?php echo esc_attr( trim( $slug ) ); ?>">
							<?php echo esc_html( $category ); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}

	public function render_title() {
		$settings = $this->get_settings_for_display();
		if ( ! $settings['show_title'] ) {
			return;
		}
		$tag    = $settings['title_tag'];
		$target = ( $settings['external_link'] ) ? 'target="_blank"' : '';

		$title_link = ( $settings['show_title_link'] ) ? get_the_permalink() : 'javascript:void(0);';

		?>
		<a class="bdt-post-gallery-title-link" href="<?php echo esc_url( $title_link ); ?>" <?php echo esc_attr( $target ); ?>>
			<<?php echo esc_attr( Utils::get_valid_html_tag( $tag ) ); ?> class="bdt-gallery-item-title bdt-margin-remove">
				<?php the_title() ?>
			</<?php echo esc_attr( Utils::get_valid_html_tag( $tag ) ); ?>>
		</a>
		<?php
	}

	public function render_excerpt() {
		$settings = $this->get_settings_for_display();
		if ( ! $this->get_settings( 'show_excerpt' ) ) {
			return;
		}

		$strip_shortcode = $this->get_settings_for_display( 'strip_shortcode' );
		$ellipsis = isset($settings['ellipsis']) ? $settings['ellipsis'] : '' ;

		?>
		<div class="bdt-post-gallery-excerpt">
			<?php
			if ( has_excerpt() ) {
				the_excerpt();
			} else {
				echo wp_kses_post(element_pack_custom_excerpt( $this->get_settings_for_display( 'excerpt_limit' ), $strip_shortcode, $ellipsis ));
			}
			?>
		</div>
		<?php

	}

	public function render_categories_names() {
		$settings = $this->get_settings_for_display();
		if ( ! $settings['show_category'] ) {
			return;
		}

		global $post;

		$tags = get_the_terms($post->ID, $settings['posts_source'] . '_tag');

		if ( ! $tags or is_wp_error($tags)) {
			return;
		}

		$separator  = '<span class="bdt-gallery-item-tag-separator"></span>';
		$tags_array = [];

		foreach ( $tags as $tag ) {
			$tags_array[] = '<span class="bdt-gallery-item-tag">' . esc_html( $tag->name ) . '</span>';
		}

		?>
		<div class="bdt-gallery-item-tags">
			<?php echo wp_kses_post( implode( $separator, $tags_array ) ); ?>
		</div>
		<?php
	}

	public function render_overlay() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			[ 
				'overlay-settings' => [ 
					'class' => [ 
						'bdt-position-cover',
						'bdt-overlay',
						'bdt-overlay-default',
						$settings['overlay_animation'] ? 'bdt-transition-' . $settings['overlay_animation'] : ''
					]
				]
			],
			'',
			'',
			true
		);

		?>
		<div <?php $this->print_render_attribute_string( 'overlay-settings' ); ?>>
			<div class="bdt-post-gallery-content">
				<div class="bdt-gallery-content-inner">
					<?php
					$this->render_title();
					$this->render_excerpt();
					$this->render_categories_names();


					$placeholder_img_src = Utils::get_placeholder_image_src();

					$img_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

					if ( ! $img_url ) {
						$img_url = $placeholder_img_src;
					} else {
						$img_url = $img_url[0];
					}

					$this->add_render_attribute(
						[ 
							'lightbox-settings' => [ 
								'class'                        => [ 
									'bdt-gallery-item-link',
									'bdt-gallery-lightbox-item',
									( 'icon' == $settings['link_type'] ) ? 'bdt-link-icon' : 'bdt-link-text'
								],
								'data-elementor-open-lightbox' => 'no',
								'data-caption'                 => wp_kses_post(get_the_title()),
								'href'                         => esc_url( $img_url )
							]
						],
						'',
						'',
						true
					);

					if ( 'none' !== $settings['show_link'] ) : ?>
						<div class="bdt-flex-inline bdt-gallery-item-link-wrapper">
							<?php if ( ( 'lightbox' == $settings['show_link'] ) || ( 'both' == $settings['show_link'] ) ) : ?>
								<a <?php $this->print_render_attribute_string( 'lightbox-settings' ); ?>>
									<?php if ( 'icon' == $settings['link_type'] ) : ?>
										<i class="ep-icon-search" aria-hidden="true"></i>
									<?php elseif ( 'text' == $settings['link_type'] && $settings['lightbox_link_text'] ) : ?>
										<span>
											<?php esc_html_e( $settings['lightbox_link_text'], 'bdthemes-element-pack' ); ?>
										</span>
									<?php endif; ?>
								</a>
							<?php endif; ?>

							<?php if ( ( 'post' == $settings['show_link'] ) || ( 'both' == $settings['show_link'] ) ) : ?>
								<?php
								$link_type_class = ( 'icon' == $settings['link_type'] ) ? ' bdt-link-icon' : ' bdt-link-text';
								$target          = ( $settings['external_link'] ) ? 'target="_blank"' : '';

								?>
								<a class="bdt-gallery-item-link<?php echo esc_attr( $link_type_class ); ?>"
									href="<?php echo esc_url(get_permalink()); ?>" <?php echo esc_attr( $target ); ?>>
									<?php if ( 'icon' == $settings['link_type'] ) : ?>
										<i class="ep-icon-link" aria-hidden="true"></i>
										<?php elseif ( 'text' == $settings['link_type'] && $settings['post_link_text'] ) : ?>
										<span>
											<?php esc_html_e( $settings['post_link_text'], 'bdthemes-element-pack' ); ?>
										</span>
									<?php endif; ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_header( $skin = 'default' ) {
		$settings = $this->get_settings_for_display();
		$id       = 'bdt-post-gallery' . $this->get_id();

		$this->add_render_attribute( 'post-gallery-wrapper', 'class', 'bdt-post-gallery-wrapper' );

		$this->add_render_attribute( 'post-gallery', 'id', esc_attr( $id ) );

		$this->add_render_attribute( 'post-gallery', 'class', [ 'bdt-post-gallery', 'bdt-ep-grid-filter-container', 'bdt-post-gallery-skin-' . $skin ] );

		$this->add_render_attribute( 'post-gallery', 'data-bdt-grid', '' );
		$this->add_render_attribute( 'post-gallery', 'class', [ 'bdt-grid', 'bdt-grid-medium' ] );

		if ( $settings['masonry'] ) {
			$this->add_render_attribute( 'post-gallery', 'data-bdt-grid', 'masonry: true' );
		}

		if ( 'lightbox' === $settings['show_link'] or 'both' === $settings['show_link'] ) {
			$this->add_render_attribute( 'post-gallery', 'data-bdt-lightbox', 'toggle: .bdt-gallery-lightbox-item; animation:' . $settings['lightbox_animation'] . ';' );
			if ( $settings['lightbox_autoplay'] ) {
				$this->add_render_attribute( 'post-gallery', 'data-bdt-lightbox', 'autoplay: 500;' );

				if ( $settings['lightbox_pause'] ) {
					$this->add_render_attribute( 'post-gallery', 'data-bdt-lightbox', 'pause-on-hover: true;' );
				}
			}
		}

		$data_settings = [
			'id'       => '#' . $id,
			'tilt_show' => (isset($settings['tilt_show']) && $settings['tilt_show'] == 'yes') ? true : false,
			'tilt_scale' => (isset($settings['tilt_scale']) && $settings['tilt_scale'] == 'yes') ? true : false,
		];
		
		if ($settings['show_filter_bar']) {
			$taxonomy = element_pack_get_taxonomy_by_post_type($settings['posts_source']);
			
			$defaults = [
				'show_title'          => 'no',
				'title_tag'          => 'h3',
				'show_excerpt'       => 'no',
				'excerpt_limit'      => 20,
				'strip_shortcode'     => 'no',
				'show_link'           => 'no',
				'external_link'      => 'no',
				'lightbox_link_text'  => '',
				'post_link_text'      => '',
				'link_type'           => 'icon',
				'show_title_link'     => 'no',
				'show_category'       => 'no',
				'show_image'         => 'no',
				'show_date'           => 'no',
				'columns_mobile'     => 1,
				'columns_tablet'      => 2,
				'columns'            => 3,
				'overlay_animation'   => 'fade',
				'_skin'   => '',
			];
			
			$data_settings = array_merge($data_settings, array_filter([
				'taxonomy'        => $taxonomy,
				'post-type'       => $settings['posts_source'],
				'order'           => $settings['posts_order'],
				'orderby'         => $settings['posts_orderby'],
				'posts_per_page'  => $settings['posts_per_page'],
			] + array_intersect_key($settings, $defaults) + $defaults));
		}
		
		$this->add_render_attribute(
			'post-gallery',
			[
				'data-settings' => [
					wp_json_encode($data_settings),
				],
			]
		);


		?>
		<div <?php $this->print_render_attribute_string( 'post-gallery-wrapper' ); ?>>

			<?php
			if ( $settings['show_filter_bar'] ) {
				$this->render_filter_menu();
			}

			if ( $settings['grid_animation_type'] !== '' ) {
				$this->add_render_attribute( 'post-gallery', 'bdt-scrollspy', 'cls: bdt-animation-' . esc_attr( $settings['grid_animation_type'] ) . ';' );
				$this->add_render_attribute( 'post-gallery', 'bdt-scrollspy', 'delay: ' . esc_attr( $settings['grid_anim_delay']['size'] ) . ';' );
				$this->add_render_attribute( 'post-gallery', 'bdt-scrollspy', 'target: > .bdt-gallery-item' . ';' );
			}

			?>
			<div <?php $this->print_render_attribute_string( 'post-gallery' ); ?>>

				<?php
	}

	public function render_footer() {
		?>

			</div>
		</div>
		<?php
	}

	public function render_post() {
		$settings = $this->get_settings_for_display();
		if ( $settings['tilt_show'] ) {
			$this->add_render_attribute( 'post-gallery-item-inner', 'data-tilt', '', true );
			if ( $settings['tilt_scale'] ) {
				$this->add_render_attribute( 'post-gallery-item-inner', 'data-tilt-scale', '1.2', true );
			}
		}

		$this->add_render_attribute( 'post-gallery-item', 'class', 'bdt-gallery-item bdt-transition-toggle', true );

		$columns_mobile = isset( $settings['columns_mobile'] ) ? $settings['columns_mobile'] : 1;
		$columns_tablet = isset( $settings['columns_tablet'] ) ? $settings['columns_tablet'] : 2;
		$columns        = isset( $settings['columns'] ) ? $settings['columns'] : 3;

		$this->add_render_attribute( 'post-gallery-item', 'class', 'bdt-width-1-' . $columns_mobile );
		$this->add_render_attribute( 'post-gallery-item', 'class', 'bdt-width-1-' . $columns_tablet . '@s' );
		$this->add_render_attribute( 'post-gallery-item', 'class', 'bdt-width-1-' . $columns . '@m' );
		?>
		<div <?php $this->print_render_attribute_string( 'post-gallery-item' ); ?>>
			<div class="bdt-post-gallery-inner" <?php $this->print_render_attribute_string( 'post-gallery-item-inner' ); ?>>
				<?php
				$this->render_thumbnail();
				$this->render_overlay();
				?>
			</div>
		</div>
		<?php
	}


	public function filter_menu_terms() {
		$settings    = $this->get_settings_for_display();
		$taxonomy    = element_pack_get_taxonomy_by_post_type($settings['posts_source']);
		$categories  = get_the_terms( get_the_ID(), $taxonomy );
		$_categories = [];
		if ( $categories ) {
			foreach ( $categories as $category ) {
				$_categories[ $category->slug ] = $category->slug;
			}
		}
		return implode( ' ', $_categories );
	}
}
