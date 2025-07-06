<?php
/*
 * Init plugin
 */
add_action( 'init', 'uacf7_product_dropdown_init' );
function uacf7_product_dropdown_init() {

	add_filter( 'uacf7_tag_generator_multiple_select_field', 'uacf7_tag_generator_field_allow_multiple', 10 );
	add_filter( 'uacf7_tag_generator_display_price_field', 'uacf7_tag_generator_display_price_field', 10 );
	add_filter( 'uacf7_tag_generator_product_by_field', 'uacf7_tag_generator_product_by_field', 10 );
	add_filter( 'uacf7_tag_generator_order_by_field', 'uacf7_tag_generator_order_by_field', 10 );
	add_filter( 'uacf7_tag_generator_product_id_field', 'uacf7_tag_generator_product_id_field', 10 );
	add_filter( 'uacf7_tag_generator_product_category_field', 'uacf7_tag_generator_product_dropdown_categories', 10 );
	add_filter( 'uacf7_tag_generator_product_tag_field', 'uacf7_tag_generator_product_dropdown_tags', 10 );
	add_filter( 'uacf7_tag_generator_product_layout_style_by_field', 'uacf7_tag_generator_product_layout_style_by_field', 10 );
	add_filter( 'uacf7_product_dropdown_query', 'uacf7_product_dropdown_query', 10, 3 );
	add_filter( 'uacf7_multiple_attribute', 'uacf7_multiple_attribute', 10 );
	add_filter( 'uacf7_dorpdown_grid', 'uacf7_dorpdown_grid', 10, 8 );
	add_action( 'wp_enqueue_scripts', 'enqueue_wpd_frontend_script' );

	// uacf7 Variation product Quick Views
	add_action( 'wp_ajax_uacf7_wpd_variable_product_quick_view', 'uacf7_wpd_variable_product_quick_view' );
	add_action( 'wp_ajax_nopriv_uacf7_wpd_variable_product_quick_view', 'uacf7_wpd_variable_product_quick_view' );


}

function enqueue_wpd_frontend_script() {
	wp_enqueue_style( 'uacf7-wpd-style', plugin_dir_url( __FILE__ ) . '/assets/css/wpd-style.css' );
	wp_enqueue_style( 'uacf7-select2-style-dropdown', plugin_dir_url( __FILE__ ) . '/assets/css/select2.min.css', [], null, 'all' );

	wp_enqueue_script( 'uacf7-select2-script', plugin_dir_url( __FILE__ ) . '/assets/js/select2.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'uacf7-wpd-script', plugin_dir_url( __FILE__ ) . '/assets/js/wpd-script.js', array( 'jquery' ), null, true );

	wp_localize_script( 'uacf7-wpd-script', 'uacf7_wpd_params',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ),
			'ajax_loader' => plugin_dir_url( __FILE__ ) . '/assets/img/loader.gif',
		)
	);
}



/*
 * Adding multiple attribute field to dropdown tag generator
 */
function uacf7_tag_generator_field_allow_multiple() {
	?>
	<input data-tag-part="option" data-tag-option='multiple' id="tag-generator-panel-select-multiple" type="checkbox" />
	<?php echo esc_attr( __( 'Multiple Product Selection ', 'ultimate-addons-cf7' ) ); ?>

	<?php
}

/*
 * Adding multiple attribute field to dropdown tag generator
 */
function uacf7_tag_generator_display_price_field() {
	?>
	<input data-tag-part="option" data-tag-option='display_price' type="checkbox">
	<?php echo esc_attr( __( 'Display Total of Selected Product Price', 'ultimate-addons-cf7' ) ); ?>
<?php
}

function uacf7_tag_generator_product_by_field() {
	ob_start(); ?>
	<legend>
		<?php echo esc_html( __( 'Show Product By', 'ultimate-addons-cf7' ) ); ?>
	</legend>
	<input id="byID" name="product_by" data-tag-part="option" data-tag-option='product_by:' type="radio" value="id"
		checked />
	<?php echo esc_html( __( ' Product ID', 'ultimate-addons-cf7' ) ); ?>

	<input id="byCategory" name="product_by" data-tag-part="option" data-tag-option='product_by:' type="radio"
		value="category" />
	<?php echo esc_html( __( 'Category', 'ultimate-addons-cf7' ) ); ?>

	<input id="byTag" name="product_by" data-tag-part="option" data-tag-option='product_by:' type="radio" value="tag" />
	<?php echo esc_html( __( 'Tag', 'ultimate-addons-cf7' ) ); ?>
	<?php
	return ob_get_clean();
}

function uacf7_tag_generator_order_by_field() {
	ob_start(); ?>
	<legend>
		<?php echo esc_html( __( 'Product Order By', 'ultimate-addons-cf7' ) ); ?>
	</legend>

	<label for="byDate">
		<input id="byDate" name="order_by" data-tag-part="option" data-tag-option='order_by:' type="radio" value="" checked>
		Date (Default)
	</label>
	<label for="byASC">
		<input id="byASC" name="order_by" data-tag-part="option" data-tag-option='order_by:' type="radio" value="asc">
		ASC
	</label>
	<label for="byDSC">
		<input id="byDSC" name="order_by" data-tag-part="option" data-tag-option='order_by:' type="radio" value="dsc">
		DSC
	</label>
	<?php
	return ob_get_clean();
}

/*
 * Adding category field to dropdown tag generator
 */
if ( ! function_exists( 'uacf7_tag_generator_product_id_field' ) ) {
	function uacf7_tag_generator_product_id_field() {
		ob_start(); ?>

		<legend for="tag-generator-panel-product-id">
			<?php echo esc_html( __( 'Product ID', 'ultimate-addons-cf7' ) ); ?>
		</legend>


		<textarea data-tag-part="value" class="values" name="values" id="tag-generator-panel-product-id" cols="30" rows="10"></textarea>

		<br>
		One ID per line.
		<a href="https://themefic.com/how-to-find-product-id-in-woocommerce" target="_blank">
			Click here
		</a> to learn how to get
		WooCommerce Product ID.

		<div class="uacf7-doc-notice">Confused? Check our Documentation on
			<a href="https://themefic.com/docs/uacf7/free-addons/contact-form-7-woocommerce/" target="_blank">WooCommerce</a>.
		</div>


		<?php
		return ob_get_clean();
	}
}

/*
 * Adding category field to dropdown tag generator
 */
if ( ! function_exists( 'uacf7_tag_generator_product_dropdown_categories' ) ) {

	function uacf7_tag_generator_product_dropdown_categories( $html ) {

		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
			return $html;
		}
		ob_start();
		?>

		<legend for="tag-generator-panel-product-category">
			<?php echo esc_html( __( 'Product Category', 'ultimate-addons-cf7' ) ); ?>
		</legend>

		<div>
			<?php

			$taxonomies = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => true
			) );
			if ( ! empty( array_filter( $taxonomies ) ) ) :
				$output = '<select data-tag-part="value" id="tag-generator-panel-product-category">';
				$output .= '<option value="">All</option>';
				foreach ( $taxonomies as $category ) {
					$output .= '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
				}
				$output .= '</select>';

				echo $output;

			endif;
			?>
		</div>
		</tr>
		<?php
		$html = ob_get_clean();

		return $html;
	}
}

/*
 * Adding tag field to dropdown tag generator
 */
if ( ! function_exists( 'uacf7_tag_generator_product_dropdown_tags' ) ) {

	function uacf7_tag_generator_product_dropdown_tags( $html ) {
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || version_compare( get_option( 'woocommerce_db_version' ), '2.5', '<' ) ) {
			return $html;
		}
		ob_start();
		?>

		<legend for="tag-generator-panel-product-tag">
			<?php echo esc_html( __( 'Product tag', 'ultimate-addons-cf7' ) ); ?>
		</legend>

		<div>
			<?php
			$taxonomies = get_terms( array(
				'taxonomy' => 'product_tag',
				'hide_empty' => true
			) );

			if ( ! empty( array_filter( $taxonomies ) ) ) :
				$output = '<select data-tag-part="value" id="tag-generator-panel-product-tag">';
				$output .= '<option value="">All</option>';
				foreach ( $taxonomies as $tag ) {
					$output .= '<option value="' . esc_attr( $tag->slug ) . '">' . esc_html( $tag->name ) . '</option>';
				}
				$output .= '</select>';

				echo $output;
			endif;
			?>
		</div>

		<?php
		$html = ob_get_clean();

		echo $html;
	}
}

/*
 * Product Layout Style
 */

if ( ! function_exists( 'uacf7_tag_generator_product_layout_style_by_field' ) ) {

	function uacf7_tag_generator_product_layout_style_by_field() {
		ob_start();
		?>
		<legend>
			<?php echo esc_html( __( 'Layout Style', 'ultimate-addons-cf7' ) ); ?>
		</legend>

		<label for="layoutDropdown">
			<input type="radio" id="layoutDropdown" name="layout" data-tag-part="option" data-tag-option='layout:' value="dropdown" checked />
			Dropdown
		</label>

		<label for="layoutGrid">
			<input id="uacf7-select2" name="layout" data-tag-part="option" data-tag-option='layout:' type="radio" value="select2" />
			Select2 Dropdown
		</label>

		<label for="layoutGrid">
            <input id="layoutGrid" name="layout" data-tag-part="option" data-tag-option='layout:' type="radio" value="grid" />
			Grid View
		</label>


		<?php

		$select_layout_style = ob_get_clean();
		echo $select_layout_style;
	}
}


/*
 * Product dropdown query by category
 */
if ( ! function_exists( 'uacf7_product_dropdown_query' ) ) {
	function uacf7_product_dropdown_query( $args, $values, $product_by ) {

		if ( ! empty( array_filter( $values ) ) ) {
			$query_values = array();

			foreach ( $values as $key => $value ) {
				$query_values[] = $value;
			}

			if ( $product_by == 'category' ) {

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'slug',
						'terms' => $query_values,
					),
				);

			} elseif ( $product_by == 'tag' ) {

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_tag',
						'field' => 'slug',
						'terms' => $query_values,
					),
				);

			} elseif ( $product_by == 'id' ) {
				$args['post__in'] = $query_values;
			}

		}

		return $args;
	}
}

/*
 * Adding 'multiple' attribure
 */
function uacf7_multiple_attribute() {
	return 'multiple';
}

/*
 * Adding 'Grid View' attribure
 */
if ( ! function_exists( 'uacf7_dorpdown_grid' ) ) {
	function uacf7_dorpdown_grid( $html, $multiple, $products, $hangover, $default_choice, $tag_name, $validation_error, $display_price ) {
		// Enqueue variation scripts.
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		global $woocommerce;
		$html = '';
		if ( $multiple ) {
			$multiple = "checkbox";
		} else {
			$multiple = "radio";
		}
		$show_total_class = isset( $display_price ) && $display_price == true ? 'uacf7-show-porduct-price' : '';
		$show_total_amount = isset( $display_price ) && $display_price == true ? '<div class="uacf7-product-price"><strong>Total : </strong><span class="uacf7-currency-symbol">' . get_woocommerce_currency_symbol() . '</span> <span class="product_total_amount"> 0 </span></div>' : '';
		while ( $products->have_posts() ) {

			$products->the_post();
			if ( $hangover ) {
				$selected = in_array( get_the_title(), (array) $hangover, true );
			} else {
				$selected = in_array( get_the_title(), (array) $default_choice, true );
			}

			$label = get_the_title();
			$link = get_the_permalink();
			$product = wc_get_product( get_the_id() );
			$product->get_type();

			if ( $product->get_sale_price() != '' ) {
				$sale_price = $product->get_sale_price();
			} elseif ( $product->get_price() != '' ) {
				$sale_price = $product->get_price();
			} else {
				$sale_price = $product->get_regular_price();
			}
			if ( $product->get_regular_price() ) {
				$regular_price = $product->get_regular_price();
			} else {
				$regular_price = '';
			}
			if ( $product->get_type() === 'grouped' ) {
				$regular_price = $product->get_price();
			}

			$variable_price = '';
			if ( $product && $product->is_type( 'variable' ) ) {
				$prices = $product->get_variation_prices();

				// lowest and highest price
				if ( ! empty( $prices['price'] ) ) {
					$min_price = min( $prices['price'] );
					$max_price = max( $prices['price'] );

					if ( $min_price !== $max_price ) {
						$variable_price = wc_price( $min_price ) . " - " . wc_price( $max_price );
					} else {
						$variable_price = wc_price( $min_price );
					}
				}
			}

			$price_value = $variable_price != '' ? get_woocommerce_currency_symbol() . $min_price . " - " . get_woocommerce_currency_symbol() . $max_price : get_woocommerce_currency_symbol() . $regular_price;


			$item_atts = array(
				'value' => get_the_title() . ' - ' . $price_value,
				'id' => 'for_' . get_the_id(),
				'product-id' => get_the_id(),
				'variation-id' => 0,
				'product-type' => $product->get_type(),
				'type' => $multiple,
				'product-price' => $sale_price,
				'name' => $tag_name . ( $multiple ? '[]' : '' ),
			);

			$item_atts = wpcf7_format_atts( $item_atts );



			if ( $variable_price != '' ) {
				$price = '<ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>' . $variable_price . '</bdi></span></ins>';
			} else {
				$price = '<del aria-hidden="true"> ' . wc_price( $regular_price ) . '</del> <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>' . wc_price( $sale_price ) . '</bdi></span></ins>';
			}
			$html .= sprintf( ' <div class="single-product-grid"> 
                             <div class="s-product-img"> 
                                 <div class="img-absulate">
                                 <img src="' . get_the_post_thumbnail_url() . '">
                                     <label for="for_' . get_the_id() . '" class="absulate-hover">
                                         <input %1$s>
                                     </label>
                                 </div>
                             </div>
                             <div class="s-product-content">
                                 <h5><a href="%2$s">%3$s</a></h5>
                                 <span class="price">%4$s</span>
                             </div> 
                         </div>', $item_atts, esc_url( $link ), esc_html( $label ), $price );
		}
		wp_reset_postdata();

		return $html = sprintf(
			'<div class="%1$s %5$s">
				<span class="product-grid wpcf7-form-control-wrap %1$s" data-name="%1$s">%2$s</span>
					%4$s
				<span>%3$s</span> 
				</div>',
			sanitize_html_class( $tag_name ), $html, $validation_error, $show_total_amount, sanitize_html_class( $show_total_class )
		);
	}
}
if ( ! function_exists( 'uacf7_wpd_variable_product_quick_view' ) ) {
	function uacf7_wpd_variable_product_quick_view() {

		global $post, $product, $woocommerce;

		// return 1;
		check_ajax_referer( 'ajax_nonce', 'security' );

		add_action( 'wcqv_product_data', 'woocommerce_template_single_add_to_cart' );

		$product_id = $_POST['product_id'];

		$wiqv_loop = new WP_Query(
			array(
				'post_type' => 'product',
				'p' => $product_id,
			)
		);
		ob_start();
		if ( $wiqv_loop->have_posts() ) :
			while ( $wiqv_loop->have_posts() ) :
				$wiqv_loop->the_post(); ?>
				<?php wc_get_template( 'single-product/add-to-cart/variation.php' ); ?>
				<script>
					jQuery.getScript("<?php echo $woocommerce->plugin_url(); ?>/assets/js/frontend/add-to-cart-variation.min.js");
				</script>
				<?php
				do_action( 'wcqv_product_data' );
			endwhile;
		endif;

		echo ob_get_clean();

		wp_die();
	}
}

