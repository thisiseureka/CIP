<?php
/*
 * Star Review Shortcode
 */
if ( ! function_exists( 'uacf7_review' ) ) {
	function uacf7_review( $val ) {
		global $wpdb;

		$upload_dir = wp_upload_dir();
		$dir = $upload_dir['baseurl'];
		$replace_dir = '/uacf7-uploads/';
		$post_id = $val['id'];

		$uacf7_review_opt = get_post_meta( $post_id, 'uacf7_review_opt', true );
		$review_metabox = isset( $uacf7_review_opt['review_metabox'] ) ? $uacf7_review_opt['review_metabox'] : array();

		// uacf7_print_r( $review_metabox );


		if ( get_post_status( $post_id ) === FALSE || empty( $review_metabox ) ) {
			return "No review has been found !";

		}

		$uacf7_review_form_id = $review_metabox['uacf7_review_form_id'] ? $review_metabox['uacf7_review_form_id'] : '';
		// $uacf7_review_fornt_style = $review_metabox['uacf7_review_fornt_style'] ? $review_metabox['uacf7_review_fornt_style'] : '';
		// $uacf7_review_fornt_style = get_post_meta( $post_id, 'uacf7_review_fornt_style', true ) ? get_post_meta( $post_id, 'uacf7_review_fornt_style', true ) : '';
		$uacf7_reviewer_name       = $review_metabox['uacf7_reviewer_name'] ? $review_metabox['uacf7_reviewer_name'] : '';
		$uacf7_reviewer_image      = $review_metabox['uacf7_reviewer_image'] ? $review_metabox['uacf7_reviewer_image'] : '';
		$uacf7_review_title        = $review_metabox['uacf7_review_title'] ? $review_metabox['uacf7_review_title'] : '';
		$uacf7_review_rating       = $review_metabox['uacf7_review_rating'] ? $review_metabox['uacf7_review_rating'] : '';
		$uacf7_review_desc         = $review_metabox['uacf7_review_desc'] ? $review_metabox['uacf7_review_desc'] : '';
		$uacf7_hide_disable_review = $review_metabox['uacf7_hide_disable_review'] ? $review_metabox['uacf7_hide_disable_review'] : '';
		$uacf7_show_review_form    = $review_metabox['uacf7_show_review_form'] ? $review_metabox['uacf7_show_review_form'] : '';
		$uacf7_review_extra_class  = $review_metabox['uacf7_review_extra_class'] ? $review_metabox['uacf7_review_extra_class'] : '';
		$uacf7_review_column       = $review_metabox['uacf7_review_column'] ? $review_metabox['uacf7_review_column'] : '';
		$uacf7_review_text_align   = $review_metabox['uacf7_review_text_align'] ? $review_metabox['uacf7_review_text_align'] : '';
		$uacf7_review_carousel     = $review_metabox['uacf7_review_carousel'] ? $review_metabox['uacf7_review_carousel'] : '';

		//Review Enable and On the database Review enable or not check
		$is_review = $uacf7_hide_disable_review == true ? 'AND is_review = 1' : '';
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "uacf7_form WHERE  form_id = %s $is_review", $uacf7_review_form_id ) );


		$column = $uacf7_review_carousel != true && $uacf7_review_column != '' ? $uacf7_review_column : '1';

		$carousel        = $uacf7_review_carousel   == true ? ' owl-carousel ueacf7-review-carousel' : '';
		$carousel_column = $uacf7_review_carousel   == true && $uacf7_review_column != '' ? $uacf7_review_column : '1';
		$text_align      = $uacf7_review_text_align != '' ? ' text-align: ' . $uacf7_review_text_align . ';' : '';
		ob_start();

		?>

		<div class="uacf7-review-wrap <?php echo esc_attr($uacf7_review_extra_class); ?>">
			<div class="uacf7-review-inner <?php echo esc_attr( $carousel ) ?>" data-column="<?php echo esc_attr( $carousel_column ); ?>">
				<?php if ( count( $data ) > 0 ) :
					foreach ( $data as $value ) : ?>
						<?php
						$review_value = json_decode( $value->form_value );
						$reviewer_name = isset( $review_value->$uacf7_reviewer_name ) && ! empty( $review_value->$uacf7_reviewer_name ) ? '<span class="uacf7-review-name"><strong>' . $review_value->$uacf7_reviewer_name . '</strong></span>' : '';
						$reviewer_image = isset( $review_value->$uacf7_reviewer_image ) && ! empty( $review_value->$uacf7_reviewer_image ) ? '<span class="uacf7-review-img"><img src="' . $dir . $review_value->$uacf7_reviewer_image . '" alt=""></span>' : '';
						$review_title = isset( $review_value->$uacf7_review_title ) && ! empty( $review_value->$uacf7_review_title ) ? '<span class="uacf7-review-title"><strong>' . $review_value->$uacf7_review_title . '</strong></span>' : '';

						$review_desc = isset( $review_value->$uacf7_review_desc ) && ! empty( $review_value->$uacf7_review_desc ) ? '<p>' . $review_value->$uacf7_review_desc . '</p>' : '';
						$review_rating = '';
						if ( isset( $review_value->$uacf7_review_rating ) && ! empty( $review_value->$uacf7_review_rating ) ) {
							$rating = $review_value->$uacf7_review_rating;
							$count = 5;
							for ( $x = 1; $x <= $count; $x++ ) {
								if ( $x <= $rating ) {
									$review_rating .= '<span class="active"><i class="fas fa-star"></i></span>';
								} else {
									$review_rating .= '<span ><i class="far fa-star"></i></span> ';
								}
							}

						}
						?>
						<div class="uacf7-single-review uacf7-review-col-<?php echo $column; ?>" style="<?php echo $text_align ?>">
							<div class="uacf7-single-review-wrap">
								<div class="uacf7-review-user">
									<?php echo $reviewer_image; ?>
									<?php echo $reviewer_name; ?>
								</div>
								<div class="uacf7-review-info">
									<div class="uacf7-review-star">
										<?php echo $review_rating ?>
									</div>
									<?php echo $review_title; ?>
								</div>
								<div class="uacf7-review-desc">
									<?php echo $review_desc; ?>
								</div>
							</div>
						</div>
					<?php endforeach; else :
					echo "No review has been found !";
				endif; ?>

			</div>
			<?php if ( isset( $uacf7_show_review_form ) && $uacf7_show_review_form == true ) {
				echo do_shortcode( '[contact-form-7 id="' . $uacf7_review_form_id . '"]' );
			} ?>
		</div>

		<?php
		return ob_get_clean();
	}
	add_shortcode( 'uacf7_review', 'uacf7_review' );
}
?>