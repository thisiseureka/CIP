<?php

namespace ElementPack\Modules\HoverVideo\Skins;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use ElementPack\Base\Module_Base;
use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Exit if accessed directly

class Skin_Accordion extends Elementor_Skin_Base {
	protected function _register_controls_actions() {
		parent::_register_controls_actions();

		add_action( 'elementor/element/bdt-hover-video/hover_video/after_section_end', [ $this, 'register_accordion_style_controls' ] );
	}

	public function get_id() {
		return 'accordion';
	}

	public function get_title() {
		return __( 'Accordion', 'bdthemes-element-pack' );
	}

	public function register_accordion_style_controls( Module_Base $widget ) {
		$this->parent = $widget;

		$this->start_controls_section(
			'accordion_mask_content',
			[ 
				'label' => __( 'Divider', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// accr = accordion
		$this->add_control(
			'accr_mask_border_type',
			[ 
				'label'     => __( 'Type', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [ 
					'default' => __( 'Default', 'bdthemes-element-pack' ),
					'solid'   => __( 'Solid', 'bdthemes-element-pack' ),
					'double'  => __( 'Double', 'bdthemes-element-pack' ),
					'dotted'  => __( 'Dotted', 'bdthemes-element-pack' ),
					'dashed'  => __( 'Dashed', 'bdthemes-element-pack' ),
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-hover-video.skin-accordion .bdt-hover-wrapper-list .bdt-hover-mask-list .bdt-hover-mask:not(:first-child)' => 'border-left: {{VALUE}} ',
				],
			]
		);

		$this->add_control(
			'accr_mask_border_width',
			[ 
				'label'     => __( 'Width', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 
					'px' => [ 
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [ 
					'{{WRAPPER}} .bdt-hover-video.skin-accordion .bdt-hover-wrapper-list .bdt-hover-mask-list .bdt-hover-mask:not(:first-child)' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'accr_mask_border_color',
			[ 
				'label'     => __( 'Color', 'bdthemes-element-pack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [ 
					'{{WRAPPER}} .bdt-hover-video.skin-accordion .bdt-hover-wrapper-list .bdt-hover-mask-list .bdt-hover-mask:not(:first-child)' => 'border-color: {{VALUE}}',

				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();
		if ( 'yes' == $settings['video_autoplay'] ) {
			$this->parent->add_render_attribute( 'hover_video_list', 'class', 'hover-video-list autoplay' );
		} else {
			$this->parent->add_render_attribute( 'hover_video_list', 'class', 'hover-video-list' );
		}

		$id = 'accordion-' . $this->parent->get_id();
		$id = $id . '-' . rand( 10, 500 );

		$this->parent->add_render_attribute(
			[ 
				'hover_video_attr' => [ 
					'id'            => $id,
					'class'         => 'bdt-hover-video skin-accordion',
					'data-settings' => [ 
						wp_json_encode( array_filter( [ 
							'id'          => $id,
							'videoReplay' => ( isset( $settings['video_replay'] ) && $settings['video_replay'] == 'yes' ) ? 'yes' : 'no',
							'posterAgain' => ( isset( $settings['poster_show_again'] ) && $settings['poster_show_again'] == 'yes' ) ? 'yes' : 'no'
						] ) ),
					],
				],
			]
		);

		?>
		<div <?php $this->parent->print_render_attribute_string( 'hover_video_attr' ); ?>>
			<span class="hover-video-loader"></span>
			<div class="bdt-hover-wrapper-list">
				<div <?php $this->parent->print_render_attribute_string( 'hover_video_list' ); ?>>
					<?php
					$i = 0;
					foreach ( $settings['hover_video_list'] as $index => $item ) :
						$i++;
						$this->parent->add_render_attribute( 'bdt_hover_video_attr', 'id', $id . '-' . $item['_id'], true );
						$active_class = ( $i == 1 ) ? 'active' : '';
						$this->parent->add_render_attribute( 'bdt_hover_video_attr', 'class', $active_class, true );

						$video_source = '';

						if ( 'hosted_url' == $item['source_type'] ) {
							$video_source = $item['hosted_url']['url'];
						} else {
							$video_source = $item['remote_url']['url'];
						}

						if ( ! empty( $video_source ) ) {
							$this->parent->add_render_attribute( 'bdt_hover_video_attr', 'src', esc_url( $video_source ), true );
						}

						$video_poster = empty( $video_source ) ? BDTEP_ASSETS_URL . 'images/no-video.svg' : $item['hover_video_poster']['url'];

						if ( ! empty( $video_poster ) ) {
							$this->parent->add_render_attribute( 'bdt_hover_video_attr', 'poster', esc_url( $video_poster ), true );
						}
						
						?>
						<video <?php $this->parent->print_render_attribute_string( 'bdt_hover_video_attr' ); ?>
							oncontextmenu="return false;" muted>
						</video>
					<?php endforeach; ?>
				</div>
				<div class="bdt-hover-mask-list">
					<?php
					$i = 0;
					foreach ( $settings['hover_video_list'] as $index => $item ) :
						$i++;
						$this->parent->add_render_attribute( 'bdt_hover_mask_attr', 'class', 'bdt-hover-mask', true );
						$this->parent->add_render_attribute( 'bdt_hover_mask_attr', 'data-id', $id . '-' . $item['_id'], true );
						if ( $i == 1 ) {
							$this->parent->add_render_attribute( 'bdt_hover_mask_attr', 'class', 'active' );
						}

						if ( ! empty( $item['video_wrapper_link']['url'] ) ) {
							$target = $item['video_wrapper_link']['is_external'] ? '_blank' : '_self';
							$this->parent->add_render_attribute( 'bdt_hover_mask_attr', 'onclick', "window.open('" . esc_url( $item['video_wrapper_link']['url'] ) . "', '$target')", true );
						}

						?>
						<div <?php $this->parent->print_render_attribute_string( 'bdt_hover_mask_attr' ); ?>>
							<div class="bdt-hover-mask-text-group">
								<?php if ( $settings['icon_visibility'] == 'yes' ) { ?>
									<span class="bdt-hover-icon">
										<?php
										$has_icon  = ! empty( $item['hover_item_icon'] );
										$has_image = ! empty( $item['hover_selected_image']['url'] );

										if ( $has_icon and 'icon' == $item['hover_item_icon_type'] ) {
											$this->parent->add_render_attribute( 'font-icon', 'class', $item['hover_item_icon'] );
											$this->parent->add_render_attribute( 'font-icon', 'aria-hidden', 'true' );
										} elseif ( $has_image and 'image' == $item['hover_item_icon_type'] ) {
											$this->parent->add_render_attribute( 'image-icon', 'src', $item['hover_selected_image']['url'], true );
											$this->parent->add_render_attribute( 'image-icon', 'alt', $item['hover_video_title'], true );
										}

										if ( ! $has_icon && ! empty( $item['hover_item_icon']['value'] ) ) {
											$has_icon = true;
										}

										?>

										<?php
										if ( $has_icon and 'icon' == $item['hover_item_icon_type'] ) {
											Icons_Manager::render_icon( $item['hover_item_icon'], [ 'aria-hidden' => 'true' ] );
										} elseif ( $has_image and 'image' == $item['hover_item_icon_type'] ) {
											?>
											<img <?php $this->parent->print_render_attribute_string( 'image-icon' ); ?>>
										<?php } ?>
									</span>
								<?php } ?>
								<div class="bdt-hover-text"> <?php echo wp_kses_post( $item['hover_video_title'] ); ?>
								</div>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $settings['progress_visibility'] == 'yes' ) { ?>
				<div class="bdt-hover-bar-list">
					<?php
					$i = 0;
					foreach ( $settings['hover_video_list'] as $index => $item ) :
						$i++;
						// pro = progress
						$this->parent->add_render_attribute( 'bdt_hover_pro_attr', 'class', 'bdt-hover-progress', true );
						$this->parent->add_render_attribute( 'bdt_hover_pro_attr', 'data-id', $id . '-' . $item['_id'], true );
						if ( $i == 1 ) {
							$this->parent->add_render_attribute( 'bdt_hover_pro_attr', 'class', 'active' );
						}
						// echo $i;
		
						?>
						<div class="bdt-hover-bar-wrapper">
							<div class="bdt-hover-bar">
								<div <?php $this->parent->print_render_attribute_string( 'bdt_hover_pro_attr' ); ?>></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php } ?>

		</div>
		<?php
	}
}
