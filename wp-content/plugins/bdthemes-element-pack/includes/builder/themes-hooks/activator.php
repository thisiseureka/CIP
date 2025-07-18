<?php

namespace ElementPack\Builder;

defined( 'ABSPATH' ) || exit;

use ElementPack\Includes\Builder\Meta;

class Activator {
	public static $instance = null;

	protected $templates;
	public $header_template;
	public $footer_template;

	public $single_template;

	protected $current_theme;
	protected $current_template;

	protected $post_type = 'bdt-template-builder';

	public function __construct() {
		add_action( 'wp', array( $this, 'hooks' ) );
	}

	public function hooks() {
		$this->current_template = basename( get_page_template_slug() );
		if ( $this->current_template == 'elementor_canvas' ) {
			return;
		}

		$this->current_theme = get_template();
		switch ( $this->current_theme ) {
			case 'astra':
				new Themes_Hooks\Astra( self::template_ids() );
				break;

			case 'neve':
				new Themes_Hooks\Neve( self::template_ids() );
				break;

			case 'generatepress':
			case 'generatepress-child':
				new Themes_Hooks\Generatepress( self::template_ids() );
				break;

			case 'oceanwp':
			case 'oceanwp-child':
				new Themes_Hooks\Oceanwp( self::template_ids() );
				break;

			case 'bb-theme':
			case 'bb-theme-child':
				new Themes_Hooks\Bbtheme( self::template_ids() );
				break;

			case 'genesis':
			case 'genesis-child':
				new Themes_Hooks\Genesis( self::template_ids() );
				break;

			default:
				new Themes_Hooks\Themes_Support( self::template_ids() );
				break;
		}
	}

	public static function template_ids() {
		$cached = wp_cache_get( 'bdthemes_template_builder_template_ids' );
		if ( false !== $cached ) {
			return $cached;
		}

		$instance = self::instance();
		$instance->the_filter();

		$ids = array(
			$instance->header_template,
			$instance->footer_template,
			$instance->single_template,
		);

		wp_cache_set( 'bdthemes_template_builder_template_ids', $ids );
		return $ids;
	}

	protected function the_filter() {
		$arg = array(
			'posts_per_page' => -1,
			'orderby'        => 'id',
			'order'          => 'DESC',
			'post_status'    => 'publish',
			'post_type'      => $this->post_type,
			'meta_query'     => array(
				array(
					'key'     => '_bdthemes_builder_template_type',
					'value'   => array( 'themes|header', 'themes|footer', 'post|single', 'post|archive' ),
					'compare' => 'IN', // Use 'IN' to match multiple values
				)
			),
		);

		$this->templates = get_posts( $arg );

		// more conditions can be triggered at once
		// don't use switch case
		// may impliment and callable by dynamic class in future

		// entire site
		if ( ! is_admin() ) {
			$filters = [ [ 
				'key'   => 'condition_a',
				'value' => 'entire_site',
			] ];
			$this->get_header_footer( $filters );
		}

		// all archive
		if ( is_archive() ) {
			$filters = [ [ 
				'key'   => 'condition_a',
				'value' => 'archive',
			] ];
			$this->get_header_footer( $filters );
		}

		// all singular
		if ( is_page() || is_single() || is_404() ) {
			$filters = [ 
				[ 
					'key'   => 'condition_a',
					'value' => 'singular',
				],
				[ 
					'key'   => 'condition_singular',
					'value' => 'all',
				]
			];
			$this->get_header_footer( $filters );
		}

		// all pages, all posts, 404 page
		if ( is_page() ) {
			$filters = [ 
				[ 
					'key'   => 'condition_a',
					'value' => 'singular',
				],
				[ 
					'key'   => 'condition_singular',
					'value' => 'all_pages',
				]
			];
			$this->get_header_footer( $filters );
		} elseif ( is_single() ) {
			$filters = [ 
				[ 
					'key'   => 'condition_a',
					'value' => 'singular',
				],
				[ 
					'key'   => 'condition_singular',
					'value' => 'all_posts',
				]
			];
			$this->get_header_footer( $filters );
		} elseif ( is_404() ) {
			$filters = [ 
				[ 
					'key'   => 'condition_a',
					'value' => 'singular',
				],
				[ 
					'key'   => 'condition_singular',
					'value' => '404page',
				]
			];
			$this->get_header_footer( $filters );
		}

		// singular selective
		if ( is_page() || is_single() ) {
			$filters = [ 
				[ 
					'key'   => 'condition_a',
					'value' => 'singular',
				],
				[ 
					'key'   => 'condition_singular',
					'value' => 'selective',
				],
				[ 
					'key'   => 'condition_singular_id',
					'value' => get_the_ID(),
				]
			];
			$this->get_header_footer( $filters );
		}

		// homepage
		if ( is_home() || is_front_page() ) {
			$filters = [ 
				[ 
					'key'   => 'condition_a',
					'value' => 'singular',
				],
				[ 
					'key'   => 'condition_singular',
					'value' => 'front_page',
				]
			];
			$this->get_header_footer( $filters );
		}

	}

	protected function get_header_footer( $filters ) {
		$template_id = array();

		if ( $this->templates != null ) {
			foreach ( $this->templates as $template ) {
				$template    = $this->dish( $template );
				$match_found = true;

				$meta = get_post_meta( $template['ID'] );


				$templateType        = isset( $meta[ Meta::TEMPLATE_TYPE ][0] ) ? $meta[ Meta::TEMPLATE_TYPE ][0] : '';
				$enabled_template_id = strtolower( Meta::TEMPLATE_ID . $templateType );

				$enabledTemplate = get_option( $enabled_template_id . '__' . $template['ID'] );

				/**
				 * Old Template Support
				 */
				if ( ! $enabledTemplate ) {
					$enabledTemplate = get_option( Meta::TEMPLATE_ID . $templateType );
				}
				/**
				 * /Old Template Support
				 */

				/**
				 * WPML Language Check
				 */
				if ( defined( 'ICL_LANGUAGE_CODE' ) ) :
					$current_lang = apply_filters( 'wpml_post_language_details', null, $template['ID'] );

					if ( ! empty( $current_lang ) && ! $current_lang['different_language'] && ( $current_lang['language_code'] == ICL_LANGUAGE_CODE ) ) :
						$template_id[ $template['type'] ] = $template['ID'];
					endif;
				endif;

				foreach ( $filters as $filter ) {

					if ( 'condition_singular_id' == $filter['key'] ) {
						$ids = explode( ',', $template[ $filter['key'] ] );
						if ( ! in_array( $filter['value'], $ids ) ) {
							$match_found = false;
						}
					} elseif ( $template[ $filter['key'] ] != $filter['value'] ) {
						$match_found = false;
					}
					if ( $filter['key'] == 'condition_a' && $template[ $filter['key'] ] == 'singular' && count( $filters ) < 2 ) {
						$match_found = false;
					}
				}

				if ( ! $enabledTemplate ) {
					$match_found = false;
				}

				if ( $match_found == true ) {
					if ( $template['type'] == 'themes|header' ) {
						$this->header_template = isset( $template_id['themes|header'] ) ? $template_id['themes|header'] : $template['ID'];
					}

					if ( $template['type'] == 'themes|footer' ) {
						$this->footer_template = isset( $template_id['themes|footer'] ) ? $template_id['themes|footer'] : $template['ID'];
					}
				}
				if ( $template['type'] == 'post|single' ) {
					$this->single_template = isset( $template_id['post|single'] ) ? $template_id['post|single'] : $template['ID'];
				}


			}
		}
	}

	protected function dish( $post ) {
		if ( $post != null ) {
			return array_merge(
				(array) $post,
				array(
					'type'                  => get_post_meta( $post->ID, '_bdthemes_builder_template_type', true ),
					'condition_a'           => get_post_meta( $post->ID, '_bdthemes_builder_template_condition_a', true ),
					'condition_singular'    => get_post_meta( $post->ID, '_bdthemes_builder_template_condition_singular', true ),
					'condition_singular_id' => get_post_meta( $post->ID, '_bdthemes_builder_template_condition_singular_id', true ),
				)
			);
		}
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

Activator::instance();
