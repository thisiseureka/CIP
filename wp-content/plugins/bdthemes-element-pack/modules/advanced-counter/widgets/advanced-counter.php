<?php

namespace ElementPack\Modules\AdvancedCounter\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use ElementPack\Base\Module_Base;
use ElementPack\Utils;

if (!defined('ABSPATH')) {
	exit();
}

class Advanced_Counter extends Module_Base {

	public function get_name() {
		return 'bdt-advanced-counter';
	}

	public function get_title() {
		return BDTEP . esc_html__('Advanced Counter', 'bdthemes-element-pack');
	}

	public function get_icon() {
		return 'bdt-wi-advanced-counter';
	}

	public function get_categories() {
		return ['element-pack'];
	}

	public function get_keywords() {
		return ['advanced', 'counter'];
	}

	public function get_style_depends() {
		if ($this->ep_is_edit_mode()) {
			return ['ep-styles'];
		} else {
			return ['ep-advanced-counter'];
		}
	}

	public function get_script_depends() {
		if ($this->ep_is_edit_mode()) {
			return ['advanced-counter', 'ep-scripts'];
		} else {
			return ['advanced-counter', 'ep-advanced-counter'];
		}
	}

	public function get_custom_help_url() {
		return 'https://youtu.be/Ydok6ImEQvE';
	}

	public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }
	protected function is_dynamic_content(): bool {
		return false;
	}
	
	protected function register_controls() {
		$this->start_controls_section(
			'section_content_counter_box',
			[
				'label' => esc_html__('Counter Layout', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => esc_html__('Show Icon / Image', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label'        => esc_html__('Icon Type', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::CHOOSE,
				'toggle'       => false,
				'default'      => 'icon',
				'prefix_class' => 'bdt-icon-type-',
				'render_type'  => 'template',
				'options'      => [
					'icon'  => [
						'title' => esc_html__('Icon', 'bdthemes-element-pack'),
						'icon'  => 'fas fa-star',
					],
					'image' => [
						'title' => esc_html__('Image', 'bdthemes-element-pack'),
						'icon'  => 'far fa-image',
					],
				],
				'condition'    => [
					'show_icon' => 'yes',
				]
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'       => esc_html__('Icon', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'render_type' => 'template',
				'condition'   => [
					'icon_type' => 'icon',
					'show_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label'       => esc_html__('Image Icon', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::MEDIA,
				'render_type' => 'template',
				'default'     => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'   => [
					'icon_type' => 'image',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'         => 'thumbnail_size',
				'default'      => 'full',
				'condition' => [
					'icon_type' => 'image'
				]
			]
		);

		$this->add_control(
			'count_start',
			[
				'label'       => esc_html__('Counter Start Number', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'placeholder' => esc_html__('Enter your Counter Number', 'bdthemes-element-pack'),
				'dynamic'     => ['active' => true],
			]
		);

		$this->add_control(
			'content_number',
			[
				'label'       => esc_html__('Counter End Number', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 2020,
				'placeholder' => esc_html__('Enter your Counter Number', 'bdthemes-element-pack'),
				'dynamic'     => ['active' => true],
			]
		);

		$this->add_control(
			'show_separator',
			[
				'label' => esc_html__('Separator', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'content_text',
			[
				'label'       => esc_html__('Counter Text', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__('Cool Number', 'bdthemes-element-pack'),
				'placeholder' => esc_html__('Enter your content text', 'bdthemes-element-pack'),
				'dynamic'     => ['active' => true],
			]
		);

		$this->add_control(
			'counter_number_size',
			[
				'label'   => esc_html__('Text HTML Tag', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => element_pack_title_tags(),
			]
		);

		$this->add_control(
			'counter_text_inline',
			[
				'label'     => esc_html__('Text Inline', 'bdthemes-element-pack') . BDTEP_NC,
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'position',
			[
				'label'        => esc_html__('Icon Position', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'top',
				'options'      => [
					'left'  => [
						'title' => esc_html__('Left', 'bdthemes-element-pack'),
						'icon'  => 'eicon-h-align-left',
					],
					'top'   => [
						'title' => esc_html__('Top', 'bdthemes-element-pack'),
						'icon'  => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__('Right', 'bdthemes-element-pack'),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor-position-',
				'toggle'       => false,
				'render_type'  => 'template',
				'condition'    => [
					'show_icon' => 'yes',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'icon_inline',
			[
				'label'     => esc_html__('Icon Inline', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'position' => ['left', 'right'],
				],
			]
		);

		$this->add_control(
			'icon_vertical_alignment',
			[
				'label'        => esc_html__('Icon Vertical Alignment', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'top'    => [
						'title' => esc_html__('Top', 'bdthemes-element-pack'),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__('Middle', 'bdthemes-element-pack'),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__('Bottom', 'bdthemes-element-pack'),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'      => 'top',
				'toggle'       => false,
				'prefix_class' => 'elementor-vertical-align-',
				'condition'    => [
					'position'    => ['left', 'right'],
					'icon_inline' => '',
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__('Alignment', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__('Left', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__('Center', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__('Right', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__('Justified', 'bdthemes-element-pack'),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_offset_toggle',
			[
				'label' => __('Offset', 'bdthemes-element-pack'),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __('None', 'bdthemes-element-pack'),
				'label_on' => __('Custom', 'bdthemes-element-pack'),
				'return_value' => 'yes',
				'condition'      => [
					'show_icon' => 'yes',
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'top_icon_vertical_offset',
			[
				'label'          => esc_html__('Vertical Offset', 'bdthemes-element-pack'),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'condition'      => [
					'position' => 'top',
					'show_icon' => 'yes',
					'icon_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-top-icon-v-offset: -{{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'top_icon_horizontal_offset',
			[
				'label'          => esc_html__('Horizontal Offset', 'bdthemes-element-pack'),
				'type'           => Controls_Manager::SLIDER,
				'range'          => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default'        => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'condition'      => [
					'position' => 'top',
					'show_icon' => 'yes',
					'icon_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon-wrap' => '-webkit-transform: translate({{SIZE}}px, var(--ep-top-icon-v-offset, 0)); transform: translate({{SIZE}}px, var(--ep-top-icon-v-offset, 0));'
				],
			]
		);

		$this->add_responsive_control(
			'left_right_icon_horizontal_offset',
			[
				'label'          => esc_html__('Horizontal Offset', 'bdthemes-element-pack'),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range'          => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'condition'      => [
					'position' => ['left', 'right'],
					'show_icon' => 'yes',
					'icon_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-left-right-icon-h-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'left_right_icon_vertical_offset',
			[
				'label'          => esc_html__('Vertical Offset', 'bdthemes-element-pack'),
				'type'           => Controls_Manager::SLIDER,
				'range'          => [
					'px' => [
						'min' => -200,
						'max' => 200,
					],
				],
				'default'        => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'condition'      => [
					'position' => ['left', 'right'],
					'show_icon' => 'yes',
					'icon_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon-wrap' => '-webkit-transform: translate(var(--ep-left-right-icon-h-offset, 0), {{SIZE}}px); transform: translate(var(--ep-left-right-icon-h-offset, 0), {{SIZE}}px);'
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_additional',
			[
				'label' => esc_html__('Additional Options', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'language_input',
			[
				'label'       => esc_html__('Language', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '0,1,2,3,4,5,6,7,8,9',
				'placeholder' => esc_html__('Enter your language number', 'bdthemes-element-pack'),
				'rows'        => 10,
				'dynamic'     => ['active' => true],
			]
		);

		$this->add_control(
			'decimal_symbol',
			[
				'label'       => esc_html__('Decimal Symbol', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'default'     => '.',
				'placeholder' => esc_html__('Enter your Decimal Symbol', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'decimal_places',
			[
				'label'       => esc_html__('Decimal places', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '0',
				'placeholder' => esc_html__('Enter your Decimal places', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'duration',
			[
				'label'       => esc_html__('Duration', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '2',
				'placeholder' => esc_html__('Enter your Duration', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'use_easing',
			[
				'label'   => esc_html__('Use Easing', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'use_grouping',
			[
				'label'   => esc_html__('Use Grouping', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no'
			]
		);

		$this->add_control(
			'counter_separator',
			[
				'label'       => esc_html__('Separator Symbol', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'default'     => ',',
				'placeholder' => esc_html__('Enter your Decimal places', 'bdthemes-element-pack'),
				'condition'   => [
					'use_grouping' => 'yes',
				]
			]
		);

		$this->add_control(
			'counter_prefix',
			[
				'label'       => esc_html__('Prefix', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter your Prefix', 'bdthemes-element-pack'),
				'dynamic'     => ['active' => true],
			]
		);

		$this->add_control(
			'counter_suffix',
			[
				'label'       => esc_html__('Suffix', 'bdthemes-element-pack'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Enter your Suffix', 'bdthemes-element-pack'),
				'dynamic'     => ['active' => true],
			]
		);

		$this->add_control(
			'indicator',
			[
				'label'     => esc_html__('Indicator', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_indicator',
			[
				'label'     => esc_html__('Indicator', 'bdthemes-element-pack'),
				'condition' => [
					'indicator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_width',
			[
				'label'     => esc_html__('Width', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 10,
						'step' => 2,
						'max'  => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-indicator' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'indicator_offset_toggle',
			[
				'label' => __('Offset', 'bdthemes-element-pack'),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __('None', 'bdthemes-element-pack'),
				'label_on' => __('Custom', 'bdthemes-element-pack'),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'indicator_horizontal_offset',
			[
				'label' => __('Horizontal Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -300,
						'step' => 1,
						'max'  => 300,
					],
				],
				'condition' => [
					'indicator_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-indicator-h-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'indicator_vertical_offset',
			[
				'label' => __('Vertical Offset', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -300,
						'step' => 1,
						'max'  => 300,
					],
				],
				'condition' => [
					'indicator_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-indicator-v-offset: {{SIZE}}px;'
				],
			]
		);

		$this->add_responsive_control(
			'indicator_rotate',
			[
				'label'   => esc_html__('Rotate', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 5,
					],
				],
				'condition' => [
					'indicator_offset_toggle' => 'yes'
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}}' => '--ep-indicator-rotate: {{SIZE}}deg;'
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'section_style_counter_box',
			[
				'label'     => esc_html__('Icon/Image', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);

		$this->start_controls_tabs('icon_colors');

		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => esc_html__('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__('Icon Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon'  => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon svg'  => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_background',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter-icon',
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'      => esc_html__('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter-icon',
			]
		);

		$this->add_responsive_control(
			'icon_radius',
			[
				'label'      => esc_html__('Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
				'condition'  => [
					'icon_radius_advanced_show!' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_radius_advanced_show',
			[
				'label' => esc_html__('Advanced Radius', 'bdthemes-element-pack'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_responsive_control(
			'icon_radius_advanced',
			[
				'label'       => esc_html__('Radius', 'bdthemes-element-pack'),
				'description' => sprintf(__('For example: <b>%1s</b> or Go <a href="%2s" target="_blank">this link</a> and copy and paste the radius value.', 'bdthemes-element-pack'), '75% 25% 43% 57% / 46% 29% 71% 54%', 'https://9elements.github.io/fancy-border-radius/'),
				'type'        => Controls_Manager::TEXT,
				'size_units'  => ['px', '%'],
				'default'     => '75% 25% 43% 57% / 46% 29% 71% 54%',
				'selectors'   => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon'     => 'border-radius: {{VALUE}}; overflow: hidden;',
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon img' => 'border-radius: {{VALUE}}; overflow: hidden;',
				],
				'condition'   => [
					'icon_radius_advanced_show' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter-icon',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_typography',
				'selector'  => '{{WRAPPER}} .bdt-ep-advanced-counter-icon',
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'icon_space',
			[
				'label'     => esc_html__('Spacing', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.elementor-position-right .bdt-ep-advanced-counter-icon-wrap' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-position-left .bdt-ep-advanced-counter-icon-wrap'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.elementor-position-top .bdt-ep-advanced-counter-icon-wrap'   => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .bdt-ep-advanced-counter-icon-wrap'                  => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_fullwidth',
			[
				'label'     => esc_html__('Image Fullwidth', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon' => 'width: 100%;box-sizing: border-box;',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => esc_html__('Size', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'vh', 'vw'],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'image_fullwidth',
							'operator' => '==',
							'value'    => '',
						],
						[
							'name'     => 'icon_type',
							'operator' => '==',
							'value'    => 'icon',
						],
					],
				],
			]
		);

		$this->add_control(
			'rotate',
			[
				'label'     => esc_html__('Rotate', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range'     => [
					'deg' => [
						'max' => 360,
						'min' => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon i'   => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon img' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'icon_background_rotate',
			[
				'label'     => esc_html__('Background Rotate', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
					'unit' => 'deg',
				],
				'range'     => [
					'deg' => [
						'max' => 360,
						'min' => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'image_icon_heading',
			[
				'label'     => esc_html__('Image Effect', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_filters',
				'selector'  => '{{WRAPPER}} .bdt-ep-advanced-counter img',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label'     => esc_html__('Opacity', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter img' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label'     => esc_html__('Transition Duration', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0.3,
				],
				'range'     => [
					'px' => [
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter img' => 'transition-duration: {{SIZE}}s',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			[
				'label' => esc_html__('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label'     => esc_html__('Icon Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'icon_hover_background',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon:after',
			]
		);

		$this->add_control(
			'icon_effect',
			[
				'label'        => esc_html__('Effect', 'bdthemes-element-pack'),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'bdt-icon-effect-',
				'default'      => 'none',
				'options'      => [
					'none' => esc_html__('None', 'bdthemes-element-pack'),
					'a'    => esc_html__('Effect A', 'bdthemes-element-pack'),
					'b'    => esc_html__('Effect B', 'bdthemes-element-pack'),
					'c'    => esc_html__('Effect C', 'bdthemes-element-pack'),
					'd'    => esc_html__('Effect D', 'bdthemes-element-pack'),
					'e'    => esc_html__('Effect E', 'bdthemes-element-pack'),
				],
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label'     => esc_html__('Border Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'icon_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'icon_hover_radius',
			[
				'label'      => esc_html__('Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_hover_shadow',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon',
			]
		);

		$this->add_control(
			'icon_hover_rotate',
			[
				'label'     => esc_html__('Rotate', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'deg',
				],
				'range'     => [
					'deg' => [
						'max' => 360,
						'min' => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon i'   => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon img' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'icon_hover_background_rotate',
			[
				'label'     => esc_html__('Background Rotate', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'deg',
				],
				'range'     => [
					'deg' => [
						'max' => 360,
						'min' => -360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'image_icon_hover_heading',
			[
				'label'     => esc_html__('Image Effect', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_filters_hover',
				'selector'  => '{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon img',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_opacity_hover',
			[
				'label'     => esc_html__('Opacity', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-icon img' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_counter_number',
			[
				'label' => esc_html__('Counter Number', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_counter_number_style');

		$this->start_controls_tab(
			'tab_counter_number_style_normal',
			[
				'label' => esc_html__('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_responsive_control(
			'counter_number_bottom_space',
			[
				'label'     => esc_html__('Spacing', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_number_margin',
			[
				'label'      => esc_html__('Margin', 'bdthemes-element-pack') . BDTEP_NC,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'counter_number_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'counter_number_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter-number',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_counter_number_style_hover',
			[
				'label' => esc_html__('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'counter_number_color_hover',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-number' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'counter_number_typography_hover',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-number',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content_text',
			[
				'label' => esc_html__('Counter Text', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_content_text_style');

		$this->start_controls_tab(
			'tab_content_text_style_normal',
			[
				'label' => esc_html__('Normal', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'content_text_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_text_margin',
			[
				'label'      => esc_html__('Margin', 'bdthemes-element-pack') . BDTEP_NC,
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_text_typography',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter-text',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_content_text_style_hover',
			[
				'label' => esc_html__('Hover', 'bdthemes-element-pack'),
			]
		);

		$this->add_control(
			'content_text_color_hover',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-content .bdt-ep-advanced-counter-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_text_typography_hover',
				'selector' => '{{WRAPPER}} .bdt-ep-advanced-counter:hover .bdt-ep-advanced-counter-content .bdt-ep-advanced-counter-text',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_counter_number_separator',
			[
				'label'     => esc_html__('Separator', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'counter_number_separator_type',
			[
				'label'   => esc_html__('Separator Type', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'line',
				'options' => [
					'line'        => esc_html__('Line', 'bdthemes-element-pack'),
					'bloomstar'   => esc_html__('Bloomstar', 'bdthemes-element-pack'),
					'bobbleaf'    => esc_html__('Bobbleaf', 'bdthemes-element-pack'),
					'demaxa'      => esc_html__('Demaxa', 'bdthemes-element-pack'),
					'fill-circle' => esc_html__('Fill Circle', 'bdthemes-element-pack'),
					'finalio'     => esc_html__('Finalio', 'bdthemes-element-pack'),
					'jemik'       => esc_html__('Jemik', 'bdthemes-element-pack'),
					'leaf-line'   => esc_html__('Leaf Line', 'bdthemes-element-pack'),
					'multinus'    => esc_html__('Multinus', 'bdthemes-element-pack'),
					'rotate-box'  => esc_html__('Rotate Box', 'bdthemes-element-pack'),
					'sarator'     => esc_html__('Sarator', 'bdthemes-element-pack'),
					'separk'      => esc_html__('Separk', 'bdthemes-element-pack'),
					'slash-line'  => esc_html__('Slash Line', 'bdthemes-element-pack'),
					'tripline'    => esc_html__('Tripline', 'bdthemes-element-pack'),
					'vague'       => esc_html__('Vague', 'bdthemes-element-pack'),
					'zigzag-dot'  => esc_html__('Zigzag Dot', 'bdthemes-element-pack'),
					'zozobe'      => esc_html__('Zozobe', 'bdthemes-element-pack'),
				],
				//'render_type' => 'none',
			]
		);

		$this->add_control(
			'counter_number_separator_border_style',
			[
				'label'     => esc_html__('Separator Style', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => [
					'solid'  => esc_html__('Solid', 'bdthemes-element-pack'),
					'dotted' => esc_html__('Dotted', 'bdthemes-element-pack'),
					'dashed' => esc_html__('Dashed', 'bdthemes-element-pack'),
					'groove' => esc_html__('Groove', 'bdthemes-element-pack'),
				],
				'condition' => [
					'counter_number_separator_type' => 'line',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator' => 'border-top-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'counter_number_separator_line_color',
			[
				'label'     => esc_html__('Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'counter_number_separator_type' => 'line',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'counter_number_separator_height',
			[
				'label'     => esc_html__('Height', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 15,
					],
				],
				'condition' => [
					'counter_number_separator_type' => 'line',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'counter_number_separator_width',
			[
				'label'      => esc_html__('Width', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'condition'  => [
					'counter_number_separator_type' => 'line',
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'counter_number_separator_svg_fill_color',
			[
				'label'     => esc_html__('Fill Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'counter_number_separator_type!' => 'line',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator-wrap svg *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'counter_number_separator_svg_stroke_color',
			[
				'label'     => esc_html__('Stroke Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'counter_number_separator_type!' => 'line',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator-wrap svg *' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'counter_number_separator_svg_width',
			[
				'label'      => esc_html__('Width', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'condition'  => [
					'counter_number_separator_type!' => 'line',
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator-wrap > *' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'counter_number_separator_spacing',
			[
				'label'     => esc_html__('Separator Spacing', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-separator-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_indicator',
			[
				'label'     => esc_html__('Indicator', 'bdthemes-element-pack'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'indicator' => 'yes',
				],
			]
		);

		$this->add_control(
			'indicator_style',
			[
				'label'   => esc_html__('Indicator Style', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__('Style 1', 'bdthemes-element-pack'),
					'2' => esc_html__('Style 2', 'bdthemes-element-pack'),
					'3' => esc_html__('Style 3', 'bdthemes-element-pack'),
					'4' => esc_html__('Style 4', 'bdthemes-element-pack'),
					'5' => esc_html__('Style 5', 'bdthemes-element-pack'),
				],
			]
		);

		$this->add_control(
			'indicator_fill_color',
			[
				'label'     => esc_html__('Fill Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-indicator svg *' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'indicator_stroke_color',
			[
				'label'     => esc_html__('Stroke Color', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-indicator svg *' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_additional',
			[
				'label' => esc_html__('Additional', 'bdthemes-element-pack'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__('Content Inner Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_inline_spacing',
			[
				'label'     => esc_html__('Icon Inline Spacing', 'bdthemes-element-pack'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'condition' => [
					'position'    => ['left', 'right'],
					'icon_inline' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-ep-advanced-counter-icon-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render_icon() {
		$settings = $this->get_settings_for_display();

		$has_icon = !empty($settings['selected_icon']);

		$has_image = !empty($settings['image']['url']);

		if (!$has_icon && !empty($settings['selected_icon']['value'])) {
			$has_icon = true;
		}

		?>
		<?php if ('yes' == $settings['show_icon']) : ?>
			<?php if ($has_icon or $has_image) : ?>
				<div class="bdt-ep-advanced-counter-icon-wrap">
					<span class="bdt-ep-advanced-counter-icon">
						<?php if ($has_icon and 'icon' == $settings['icon_type']) { ?>
							<?php Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']); ?>
						<?php } elseif ($has_image and 'image' == $settings['icon_type']) { 
							$thumb_url = Group_Control_Image_Size::get_attachment_image_src($settings['image']['id'], 'thumbnail_size', $settings);
							if (!$thumb_url) {
							printf('<img src="%1$s" alt="%2$s">', esc_url($settings['image']['url']), esc_html($settings['content_text']));
							} else {
								print(wp_get_attachment_image(
									$settings['image']['id'],
									$settings['thumbnail_size_size'],
									false,
									[
										'alt' => esc_html($settings['content_text'])
									]
								));
							}
						} ?>
					</span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

	<?php
	}

	protected function render_icon_heading() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('number', 'class', 'bdt-ep-advanced-counter-number');
		if ('yes' == $settings['icon_inline']) {
			$this->add_render_attribute('icon-heading', 'class', 'bdt-ep-advanced-counter-icon-heading bdt-flex bdt-flex-middle');
		}
		if ('right' == $settings['position']) {
			$this->add_render_attribute('icon-heading', 'class', 'bdt-flex-row-reverse');
		}
	?>
		<div <?php $this->print_render_attribute_string('icon-heading'); ?>>
			<?php $this->render_icon(); ?>
			<div>
				<?php if ($settings['content_number']) : ?>
					<div <?php $this->print_render_attribute_string('number'); ?>>
						<span <?php $this->print_render_attribute_string('content_number'); ?>>
							<?php echo wp_kses($settings['content_number'], element_pack_allow_tags('title')); ?>
						</span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php

	}

	protected function render_heading() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('number', 'class', 'bdt-ep-advanced-counter-number');
	?>

		<?php if ($settings['content_number']) : ?>
			<div <?php $this->print_render_attribute_string('number'); ?>>
				<span class="bdt-count-this" id="bdt-ep-advanced-counter-data-<?php echo esc_attr($this->get_id()); ?>" <?php $this->print_render_attribute_string('content_number'); ?>>
					<?php echo wp_kses($settings['content_number'], element_pack_allow_tags('title')); ?>
				</span>
			</div>
		<?php endif; ?>
	<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('content_text', 'class', 'bdt-ep-advanced-counter-text');
		$this->add_inline_editing_attributes('content_number', 'none');
		$this->add_inline_editing_attributes('content_text');
		$this->add_render_attribute('advanced-counter', 'class', 'bdt-ep-advanced-counter');

		if ($settings['counter_text_inline'] == 'yes') {
			$this->add_render_attribute('content_wrap', 'class', 'bdt-ep-advanced-counter-content bdt-flex-inline bdt-flex-middle');
		} else {
			$this->add_render_attribute('content_wrap', 'class', 'bdt-ep-advanced-counter-content');
		}

		// echo $countStart;
		$this->add_render_attribute(
			[
				'advanced_counter_data' => [
					'data-settings' => [
						wp_json_encode(
							array_filter([
								"id"               => 'bdt-ep-advanced-counter-data-' . $this->get_id(),
								"countStart"       => $settings['count_start'],
								"countNumber"      => $settings['content_number'],
								"language"         => explode(',', $settings['language_input']),
								"decimalPlaces"    => $settings['decimal_places'],
								"duration"         => $settings['duration'],
								"useEasing"        => $settings['use_easing'],
								"useGrouping"      => $settings['use_grouping'],
								"counterSeparator" => $settings['counter_separator'],
								"decimalSymbol"    => $settings['decimal_symbol'],
								"counterPrefix"    => $settings['counter_prefix'],
								"counterSuffix"    => $settings['counter_suffix'],

							])
						),
					],
				],
			]
		);
		// end send unique data
	?>
		<div <?php $this->print_render_attribute_string('advanced-counter'); ?> <?php $this->print_render_attribute_string('advanced_counter_data'); ?>>
			<?php if ('' == $settings['icon_inline']) : ?>
				<?php $this->render_icon(); ?>
			<?php endif; ?>
			<div <?php $this->print_render_attribute_string('content_wrap'); ?>>
				<?php if ('yes' == $settings['icon_inline']) : ?>
					<?php $this->render_icon_heading(); ?>
				<?php else : ?>
					<?php $this->render_heading(); ?>
				<?php endif; ?>
				<?php if ($settings['show_separator']) : ?>
					<?php if ('line' == $settings['counter_number_separator_type']) : ?>
						<div class="bdt-ep-advanced-counter-separator-wrap">
							<div class="bdt-ep-advanced-counter-separator"></div>
						</div>
					<?php elseif ('line' != $settings['counter_number_separator_type']) : ?>
						<div class="bdt-ep-advanced-counter-separator-wrap">
							<?php
							$svg_image = BDTEP_ASSETS_PATH . 'images/divider/' . $settings['counter_number_separator_type'] . '.svg';

							if (file_exists($svg_image)) {
								ob_start();
								include $svg_image;
								$svg_image = ob_get_clean();
								echo wp_kses($svg_image, element_pack_allow_tags('svg'));
							}
							?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if ( $settings['content_text'] ) : ?>
					<<?php echo esc_attr( Utils::get_valid_html_tag( $settings['counter_number_size'] ) ); ?>
						<?php $this->print_render_attribute_string( 'content_text' ); ?>>
						<?php echo wp_kses( $settings['content_text'], element_pack_allow_tags( 'text' ) ); ?>
					</<?php echo esc_attr( Utils::get_valid_html_tag( $settings['counter_number_size'] ) ); ?>>
				<?php endif;
				?>
			</div>
		</div>

		<?php if ($settings['indicator']) : ?>
			<div class="bdt-ep-advanced-counter-indicator bdt-svg-style-<?php echo esc_attr($settings['indicator_style']); ?>">
				<?php echo element_pack_svg_icon('arrow-' . $settings['indicator_style']); ?>
			</div>
		<?php endif; ?>

<?php
	}
}
