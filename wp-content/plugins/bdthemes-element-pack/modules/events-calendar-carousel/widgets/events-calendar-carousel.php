<?php

namespace ElementPack\Modules\EventsCalendarCarousel\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use ElementPack\Base\Module_Base;
use ElementPack\Traits\Global_Swiper_Controls;

use ElementPack\Modules\EventsCalendarCarousel\Skins;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

/**
 * Class Post Slider
 */
class Events_Calendar_Carousel extends Module_Base
{

    use Global_Swiper_Controls;

    public $_query = null;

    public function get_name()
    {
        return 'bdt-event-carousel';
    }

    public function get_title()
    {
        return BDTEP . __('Events Calendar Carousel', 'bdthemes-element-pack');
    }

    public function get_icon()
    {
        return 'bdt-wi-events-calendar-carousel';
    }

    public function get_categories()
    {
        return ['element-pack'];
    }

    public function get_keywords()
    {
        return ['events', 'carousel', 'calendar', 'the'];
    }

    public function get_style_depends()
    {
        if ($this->ep_is_edit_mode()) {
            return ['swiper', 'ep-styles'];
        } else {
            return ['swiper', 'ep-events-calendar-carousel', 'ep-font'];
        }
    }

    public function get_script_depends()
    {
        if ($this->ep_is_edit_mode()) {
            return ['swiper', 'ep-scripts'];
        } else {
            return ['swiper', 'ep-events-calendar-carousel'];
        }
    }

    public function get_custom_help_url()
    {
        return 'https://youtu.be/_ZPPBmKmGGg';
    }

    public function register_skins()
    {
        $this->add_skin(new Skins\Skin_Fable($this));
        $this->add_skin(new Skins\Skin_Altra($this));
    }

    public function get_query()
    {
        return $this->_query;
    }

	public function has_widget_inner_wrapper(): bool {
        return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
    }
	protected function is_dynamic_content(): bool {
		return false;
	}
    
    public function register_controls()
    {

        // Layout Section
        $this->start_controls_section(
            'section_content_layout',
            [
                'label' => __('Layout', 'bdthemes-element-pack'),
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => esc_html__('Column Gap', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 35,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => __('Show Image', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'bdthemes-element-pack'),
                'type' => Controls_Manager::NUMBER,
                'default' => 15,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => __('Show Meta', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    '_skin!' => 'altra',
                ],
            ]
        );

        $this->add_control(
            'show_meta_cost',
            [
                'label' => __('Show Cost', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_website',
            [
                'label' => __('Show Website', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_location',
            [
                'label' => __('Show Location', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_more_btn',
            [
                'label' => __('Show More Button', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    '_skin' => 'fable',
                ],
            ]
        );

        $this->add_control(
            'anchor_link',
            [
                'label' => __('Anchor Link', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'match_height',
            [
                'label' => __('Item Match Height', 'bdthemes-element-pack') . BDTEP_NC,
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    '_skin' => '',
                ],
            ]
        );

        $this->add_control(
            'skin_match_height',
            [
                'label' => __('Item Match Height', 'bdthemes-element-pack') . BDTEP_NC,
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    '_skin' => ['altra', 'fable'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_image',
            [
                'label' => __('Image', 'bdthemes-element-pack'),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'label' => esc_html__('Image Size', 'bdthemes-element-pack'),
                'exclude' => ['custom'],
                'default' => 'medium',
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Image Width', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image' => 'width: {{SIZE}}{{UNIT}};margin-left: auto;margin-right: auto;',
                ],
                'condition' => [
                    'show_image' => 'yes',
                    '_skin!' => 'altra',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => __('Image Ratio', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image' => 'padding-bottom: calc( {{SIZE}} * 100% ); top: 0; left: 0; right: 0; bottom: 0;',
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image:after' => 'content: "{{SIZE}}"; position: absolute; color: transparent;',
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image img' => 'height: 100%; width: auto; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); font-size: {{SIZE}}; object-fit: cover;',
                ],
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_query',
            [
                'label' => __('Query', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source',
            [
                'label' => _x('Source', 'Posts Query Control', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Show All', 'bdthemes-element-pack'),
                    'by_name' => esc_html__('Manual Selection', 'bdthemes-element-pack'),
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'event_categories',
            [
                'label' => esc_html__('Categories', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT2,
                'options' => element_pack_get_terms('tribe_events_cat'),
                'default' => [],
                'label_block' => true,
                'multiple' => true,
                'condition' => [
                    'source' => 'by_name',
                ],
            ]
        );

        $this->add_control(
            'start_date',
            [
                'label' => esc_html__('Start Date', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Any Time', 'bdthemes-element-pack'),
                    'now' => esc_html__('Now', 'bdthemes-element-pack'),
                    'today' => esc_html__('Today', 'bdthemes-element-pack'),
                    'last month' => esc_html__('Last Month', 'bdthemes-element-pack'),
                    'custom' => esc_html__('Custom', 'bdthemes-element-pack'),
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'custom_start_date',
            [
                'label' => esc_html__('Custom Start Date', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DATE_TIME,
                'condition' => [
                    'start_date' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'end_date',
            [
                'label' => esc_html__('End Date', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Any Time', 'bdthemes-element-pack'),
                    'now' => esc_html__('Now', 'bdthemes-element-pack'),
                    'today' => esc_html__('Today', 'bdthemes-element-pack'),
                    'next month' => esc_html__('Last Month', 'bdthemes-element-pack'),
                    'custom' => esc_html__('Custom', 'bdthemes-element-pack'),
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'custom_end_date',
            [
                'label' => esc_html__('Custom End Date', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DATE_TIME,
                'condition' => [
                    'end_date' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => esc_html__('Limit', 'bdthemes-element-pack'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Order by', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT,
                'default' => 'event_date',
                'options' => [
                    'event_date' => esc_html__('Event Date', 'bdthemes-element-pack'),
                    'title' => esc_html__('Title', 'bdthemes-element-pack'),
                    'category' => esc_html__('Category', 'bdthemes-element-pack'),
                    'rand' => esc_html__('Random', 'bdthemes-element-pack'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__('Descending', 'bdthemes-element-pack'),
                    'ASC' => esc_html__('Ascending', 'bdthemes-element-pack'),
                ],
            ]
        );

        $this->end_controls_section();

        //Navigation Controls
        $this->start_controls_section(
            'section_content_navigation',
            [
                'label' => __('Navigation', 'bdthemes-element-pack'),
            ]
        );

        //Global Navigation Controls
        $this->register_navigation_controls();

        $this->end_controls_section();

        //Global Carousel Settings Controls
        $this->register_carousel_settings_controls();

        // Style Section
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => __('Items', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Content Padding', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    '_skin!' => ['fable'],
                ],
            ]
        );

        $this->add_control(
            'item_content_background',
            [
                'label' => __('Content Background', 'bdthemes-element-pack') . BDTEP_NC,
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-fable .bdt-event-content' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin' => ['fable'],
                ],
            ]
        );

        $this->add_control(
            'item_hover_before_style_background',
            [
                'label' => __('Hover Style', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item:before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin!' => ['fable', 'altra'],
                ],
            ]
        );

        $this->add_control(
            'item_hover_before_style_radius',
            [
                'label' => __('Border Radius', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    '_skin!' => ['fable', 'altra'],
                ],
            ]
        );

        $this->start_controls_tabs('tabs_item_style');

        $this->start_controls_tab(
            'tab_item_normal',
            [
                'label' => __('Normal', 'bdthemes-element-pack'),
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label' => __('Background', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                // 'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .skin-default .bdt-event-item, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .skin-default .bdt-event-item, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __('Border', 'bdthemes-element-pack'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .skin-default .bdt-event-item, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .skin-default .bdt-event-item, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner, {{WRAPPER}} .bdt-event-calendar .swiper-carousel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_opacity',
            [
                'label' => esc_html__('Opacity', 'bdthemes-element-pack') . BDTEP_NC,
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            [
                'label' => __('Hover', 'bdthemes-element-pack'),
            ]
        );

        $this->add_control(
            'item_hover_background',
            [
                'label' => __('Background', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-default .bdt-event-item:hover, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_hover_border_color',
            [
                'label' => __('Border Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'item_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skin-default .bdt-event-item:hover, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_hover_shadow',
                'selector' => '{{WRAPPER}} .skin-default .bdt-event-item:hover, {{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover',
            ]
        );

        $this->add_responsive_control(
            'item_shadow_padding',
            [
                'label' => __('Match Padding', 'bdthemes-element-pack'),
                'description' => __('You have to add padding for matching overlaping hover shadow', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 1,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-carousel' => 'padding: {{SIZE}}{{UNIT}}; margin: 0 -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_hover_opacity',
            [
                'label' => esc_html__('Opacity', 'bdthemes-element-pack') . BDTEP_NC,
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_active',
            [
                'label' => __('Active', 'bdthemes-element-pack') . BDTEP_NC,
            ]
        );

        $this->add_control(
            'item_active_background',
            [
                'label' => __('Background', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-default .bdt-event-item.swiper-slide-active, {{WRAPPER}} .bdt-event-calendar .bdt-event-item.swiper-slide-active .bdt-event-item-inner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_active_border_color',
            [
                'label' => __('Border Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'item_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skin-default .bdt-event-item.swiper-slide-active, {{WRAPPER}} .bdt-event-calendar .bdt-event-item.swiper-slide-active .bdt-event-item-inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_active_shadow',
                'selector' => '{{WRAPPER}} .skin-default .bdt-event-item.swiper-slide-active, {{WRAPPER}} .bdt-event-calendar .bdt-event-item.swiper-slide-active .bdt-event-item-inner',
            ]
        );

        $this->add_control(
            'item_active_opacity',
            [
                'label' => esc_html__('Opacity', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'step' => 0.1,
                        'max' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item.swiper-slide-active' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_image' => ['yes'],
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __('Padding', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => __('Margin', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => __('Image Radius', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => __('Opacity (%)', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_control(
            'image_hover_opacity',
            [
                'label' => __('Hover Opacity (%)', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item:hover .bdt-event-image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Hover Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-title-wrap',
            ]
        );

        $this->add_control(
            'title_separator_color',
            [
                'label' => esc_html__('Separator Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-intro .bdt-event-title-wrap, {{WRAPPER}} .bdt-event-calendar .bdt-event-intro' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin!' => 'fable',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__('Spacing', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-intro, {{WRAPPER}} .skin-fable .bdt-event-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_date',
            [
                'label' => esc_html__('Date', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_date' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'day_color',
            [
                'label' => esc_html__('Day Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-date a .bdt-event-day' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'day_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-date a .bdt-event-day',
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Month Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-date a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin!' => ['fable'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-date',
                'condition' => [
                    '_skin!' => ['fable'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_excerpt',
            [
                'label' => esc_html__('Excerpt', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_excerpt' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-excerpt',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => esc_html__('Meta', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta' => ['yes'],
                    '_skin!' => 'altra',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-meta .bdt-event-price a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_meta_cost' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'meta_icon_color',
            [
                'label' => esc_html__('Icon Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-meta .bdt-address-website-icon a, {{WRAPPER}} .skin-fable .bdt-event-meta .bdt-more-icon a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_meta_more_btn' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'meta_icon_border_color',
            [
                'label' => esc_html__('Icon Border Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-fable .bdt-event-meta .bdt-more-icon a' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin!' => [''],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-meta a',
            ]
        );

        $this->add_responsive_control(
            'meta_padding',
            [
                'label' => __('Meta Padding', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'meta_border_top_color',
            [
                'label' => esc_html__('Border Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-meta' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_meta_price',
            [
                'label' => esc_html__('Meta Price', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta_cost' => ['yes'],
                    '_skin' => 'altra',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_meta_price_style');

        $this->start_controls_tab(
            'tab_meta_price_normal',
            [
                'label' => __('Normal', 'bdthemes-element-pack'),
            ]
        );

        $this->add_control(
            'meta_price_icon_color',
            [
                'label' => esc_html__('Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a svg *' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_price_icon_background_color',
            [
                'label' => esc_html__('Background Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'meta_price_border',
                'label' => __('Border', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a',
            ]
        );

        $this->add_control(
            'meta_price_border_radius',
            [
                'label' => __('Border Radius', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_price_padding',
            [
                'label' => __('Padding', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_price_icon_size',
            [
                'label' => __('Icon Size', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_price_hover',
            [
                'label' => __('Hover', 'bdthemes-element-pack'),
            ]
        );

        $this->add_control(
            'meta_price_hover_color',
            [
                'label' => esc_html__('Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a .bdt-price-amount' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover .bdt-event-price a svg *' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_price_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover .bdt-event-price a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_border_hover_color',
            [
                'label' => esc_html__('Border Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover .bdt-event-price a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_price_padding_right',
            [
                'label' => __('Match Padding', 'bdthemes-element-pack'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-event-item-inner:hover .bdt-event-price a' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_price_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-event-calendar .bdt-event-price a .bdt-price-amount',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_address_website',
            [
                'label' => esc_html__('Address', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    '_skin!' => ['fable'],
                ],
            ]
        );

        $this->add_control(
            'address_website_icon_color',
            [
                'label' => esc_html__('Icon Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-fable .bdt-address-website-icon a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'address_website_icon_hover_color',
            [
                'label' => esc_html__('Icon Hover Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-event-calendar .bdt-address-website-icon a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin' => ['altra'],
                ],
            ]
        );

        $this->add_control(
            'address_website_icon_background_color',
            [
                'label' => esc_html__('Background Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-fable .bdt-address-website-icon a' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin!' => ['altra'],
                ],
            ]
        );

        $this->add_control(
            'address_website_icon_border_color',
            [
                'label' => esc_html__('Border Color', 'bdthemes-element-pack'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skin-fable .bdt-address-website-icon a' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    '_skin!' => ['altra'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'address_website_typography',
                'label' => esc_html__('Typography', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .skin-fable .bdt-address-website-icon a',
            ]
        );

        $this->add_responsive_control(
            'address_website_padding',
            [
                'label' => __('Padding', 'bdthemes-element-pack'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .skin-fable .bdt-address-website-icon a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    '_skin!' => ['altra'],
                ],
            ]
        );

        $this->end_controls_section();

        //Navigation Style
        $this->start_controls_section(
            'section_style_navigation',
            [
                'label' => __('Navigation', 'bdthemes-element-pack'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'navigation',
                            'operator' => '!=',
                            'value' => 'none',
                        ],
                        [
                            'name' => 'show_scrollbar',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ]
        );

        //Global Navigation Style Controls
        $this->register_navigation_style_controls('swiper-carousel');

        $this->end_controls_section();
    }

    public function render()
    {

        $settings = $this->get_settings_for_display();

        global $post;

        $start_date = ('custom' == $settings['start_date']) ? $settings['custom_start_date'] : $settings['start_date'];
        $end_date = ('custom' == $settings['end_date']) ? $settings['custom_end_date'] : $settings['end_date'];

        $query_args = array_filter([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'eventDisplay' => ('custom' == $settings['start_date'] or 'custom' == $settings['end_date']) ? 'custom' : 'all',
            'posts_per_page' => $settings['limit'],
            //'tag'          => 'donor-program', // or whatever the tag name is
        ]);

        if ('by_name' === $settings['source'] and !empty($settings['event_categories'])) {
            // $query_args['tax_query'] = [
            // 	'taxonomy' => 'tribe_events_cat',
            // 	'field'    => 'slug',
            // 	'terms'    => $settings['event_categories']
            // ];
            $query_args['event_category']    = $settings['event_categories'];
        }

        $query_args = tribe_get_events($query_args);

        $this->render_header();

        if(!empty($query_args)){
			foreach ($query_args as $post) {
				$this->render_loop_item($post);
			}
		} else {
			echo '<div class="bdt-alert bdt-alert-warning">'.esc_html__('No events!', 'bdthemes-element-pack').'</div>';
		}

        $this->render_footer();

        wp_reset_postdata();
    }

    public function render_image()
    {
        $settings = $this->get_settings_for_display();

        if (!$this->get_settings('show_image')) {
            return;
        }

        $settings['image'] = [
            'id' => get_post_thumbnail_id(),
        ];

        $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'image');
        $placeholder_image_src = Utils::get_placeholder_image_src();

        if (!$image_html) {
            $image_html = '<img src="' . esc_url($placeholder_image_src) . '" alt="' . get_the_title() . '">';
        }

        ?>

		<div class="bdt-event-image bdt-background-cover">
			<a href="<?php echo ($settings['anchor_link'] == 'yes') ? esc_url(the_permalink()) : 'javascript:void(0);'; ?>" title="<?php echo esc_html(get_the_title()); ?>">
				<img src="<?php echo esc_url(wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size'])); ?>" alt="<?php echo esc_html(get_the_title()); ?>">
			</a>
		</div>
	<?php
}

    public function render_title()
    {
        $settings = $this->get_settings_for_display();
        if (!$this->get_settings('show_title')) {
            return;
        }

        ?>

		<h3 class="bdt-event-title-wrap">
			<a href="<?php echo ($settings['anchor_link'] == 'yes') ? esc_url(get_permalink()) : 'javascript:void(0);'; ?>" class="bdt-event-title">
				<?php the_title()?>
			</a>
		</h3>
	<?php
}

    public function render_date()
    {
        if (!$this->get_settings('show_date')) {
            return;
        }

        $start_datetime = tribe_get_start_date();
        $end_datetime = tribe_get_end_date();

        $event_day = tribe_get_start_date(null, false, 'j');
        $event_month = tribe_get_start_date(null, false, 'M');

        ?>
		<span class="bdt-event-date">
			<a href="javascript:void(0);" title="<?php esc_html_e('Start Date:', 'bdthemes-element-pack');
        echo esc_html($start_datetime);?>  - <?php esc_html_e('End Date:', 'bdthemes-element-pack');
        echo esc_html($end_datetime);?>">
				<span class="bdt-event-day">
					<?php echo esc_html(str_pad($event_day, 2, '0', STR_PAD_LEFT)); ?>
				</span>
				<span>
					<?php echo esc_html($event_month); ?>
				</span>
			</a>
		</span>
	<?php
}

    public function render_excerpt($post)
    {
        if (!$this->get_settings('show_excerpt')) {
            return;
        }

        ?>
		<div class="bdt-event-excerpt">
			<?php

        if (!$post->post_excerpt) {
            echo wp_kses_post(strip_shortcodes(wp_trim_words($post->post_content, $this->get_settings('excerpt_length'))));
        } else {
            echo wp_kses_post(strip_shortcodes(wp_trim_words($post->post_excerpt, $this->get_settings('excerpt_length'))));
        }

        ?>
		</div>
	<?php

    }

    public function render_meta()
    {
        $settings = $this->get_settings_for_display();
        if (!$this->get_settings('show_meta')) {
            return;
        }

        $cost = ($settings['show_meta_cost']) ? tribe_get_formatted_cost() : '';

        $address = ($settings['show_meta_location']) ? tribe_address_exists() : '';

        $website = ($settings['show_meta_website']) ? tribe_get_event_website_url() : '';

        ?>

		<?php if (!empty($cost) or $address or !empty($website)): ?>
			<div class="bdt-event-meta bdt-grid">

				<?php if (!empty($cost)): ?>
					<div class="bdt-width-auto bdt-padding-remove">
						<div class="bdt-event-price">
							<a href="javascript:void(0);"><?php esc_html_e('Cost:', 'bdthemes-element-pack');?></a>
							<a href="javascript:void(0);"><?php echo esc_html($cost); ?></a>
						</div>
					</div>
				<?php endif;?>

				<?php if (!empty($website) or $address): ?>
					<div class="bdt-width-expand bdt-text-right">
						<div class="bdt-address-website-icon">

							<?php if (!empty($website)): ?>
								<a href="<?php echo esc_url($website); ?>" target="_blank" class="ep-icon-earth" aria-hidden="true"></a>
							<?php endif;?>

							<?php if ($address): ?>
								<a href="javascript:void(0);" bdt-tooltip="<?php echo esc_html(tribe_get_full_address()); ?>" class="ep-icon-location" aria-hidden="true"></a>
							<?php endif;?>

						</div>

					</div>
				<?php endif;?>

			</div>
		<?php endif;?>

	<?php
}

    public function render_header($skin_name = 'default')
    {
        $settings = $this->get_settings_for_display();

        //Global Function
        $this->render_swiper_header_attribute('event-carousel');

        $this->add_render_attribute('carousel', 'class', ['bdt-event-carousel', 'bdt-event-calendar', 'skin-' . $skin_name]);

        $this->add_render_attribute('event-carousel-wrapper', 'class', 'swiper-wrapper');

        if ('yes' == $settings['match_height']) {
            $this->add_render_attribute('event-carousel', 'bdt-height-match', 'target: > div > div > .bdt-event-content');
        }

        if ('yes' == $settings['skin_match_height']) {
            $this->add_render_attribute('event-carousel', 'bdt-height-match', 'target: > div > div > div > .bdt-event-content');
        }

        ?>
		<div <?php $this->print_render_attribute_string('carousel'); ?>>
			<div <?php $this->print_render_attribute_string('swiper'); ?>>
				<div <?php $this->print_render_attribute_string('event-carousel-wrapper'); ?>>
				<?php
}

    public function render_loop_item($post)
    {
        $settings = $this->get_settings_for_display();

        ?>
					<div class="bdt-event-item swiper-slide">

						<?php $this->render_image();?>

						<div class="bdt-event-content">
							<div class="bdt-event-intro">

								<?php $this->render_date();?>

								<?php $this->render_title();?>

							</div>

							<?php $this->render_excerpt($post);?>

						</div>

						<?php $this->render_meta();?>

					</div>
			<?php
}
}
