<?php
namespace ElementPack\Modules\PriceTable\Skins;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

use Elementor\Skin_Base as Elementor_Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Partait extends Elementor_Skin_Base {
	public function get_id() {
		return 'bdt-partait';
	}

	public function get_title() {
		return __( 'Partait', 'bdthemes-element-pack' );
	}

	public function register_partait_style_controls() {
		$this->start_controls_section(
			'section_style_partait',
			[
				'label' => __( 'Partait', 'bdthemes-element-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->parent->get_settings_for_display();

		$this->parent->add_render_attribute(
			[ 
				'table-wrap' => [ 
					'class' => [ 
						'bdt-price-table skin-partait',
					],
					'data-settings' => [ 
						wp_json_encode( [ 
							"id" => $this->parent->get_id(),
							'read_more_toggle' => $settings["read_more_toggle"] ? true : false,
						] ),
					],
				],
			]
		);

		?>
		<div <?php echo $this->parent->get_render_attribute_string( 'table-wrap' ); ?>>

			<div class="bdt-grid bdt-grid-collapse bdt-child-width-1-2@m" data-bdt-grid data-bdt-height-match="target: > div > .bdt-pricing-column">
				<div>
					<div class="bdt-pricing-column">
						<?php
						$this->parent->render_header();
						$this->parent->render_price();
						$this->parent->render_footer();
						?>
					</div>
				</div>

				<div>
					<div class="bdt-pricing-column bdt-price-table-features-list-wrap bdt-flex bdt-flex-middle">
						<?php
						$this->parent->render_features_list();
						?>
					</div>
				</div>
			</div>
			<?php $this->parent->render_ribbon(); ?>
		</div>
		<?php
	}
}

