<?php

/*
 * register star review custom post type
 */
if ( ! function_exists( 'uacf7_register_star_review_cpt' ) ) {
	function uacf7_register_star_review_cpt() {
		register_post_type( 'uacf7_review',
			array(
				'labels' => array(
					'name' => _x( 'UACF7 Review', 'ultimate-addons-cf7' ),
					'singular_name' => _x( 'UACF7 Review', 'ultimate-addons-cf7' ),
					'add_new' => __( 'Add New', 'ultimate-addons-cf7' ),
					'add_new_item' => __( 'Add New Review', 'ultimate-addons-cf7' ),
					'new_item' => __( 'New Review', 'ultimate-addons-cf7' ),
					'edit_item' => __( 'Edit Review', 'ultimate-addons-cf7' ),
					'view_item' => __( 'View Review', 'ultimate-addons-cf7' ),
					'all_items' => __( 'All Review', 'ultimate-addons-cf7' ),
					'search_items' => __( 'Search Review', 'ultimate-addons-cf7' ),
					'not_found' => __( 'No Review found.', 'ultimate-addons-cf7' ),
					'not_found_in_trash' => __( 'No Review found in Trash.', 'ultimate-addons-cf7' ),
				),
				// 'has_archive' => true,    
				'show_in_menu' => false,
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_nav_menus' => false,
				'has_archive' => false,
				'rewrite' => false,
				'supports' => apply_filters( 'uacf7_post_type_supports', array( 'title' ) ),
				'menu_icon' => 'dashicons-format-status'
			)
		);
	}
	add_action( 'init', 'uacf7_register_star_review_cpt' );
}


/*
 * Database menu 
 */
if ( ! function_exists( 'uacf7_star_review_add_db_menu' ) ) {
	function uacf7_star_review_add_db_menu() {
		add_submenu_page(
			'uacf7_settings',
			__( 'Star Review', 'ultimate-addons-cf7' ),
			__( 'Star Review', 'ultimate-addons-cf7' ),
			'manage_options',
			'edit.php?post_type=uacf7_review',
			// array( $this, 'uacf7_create_database_page' ),
		);
	}
	add_action( 'admin_menu', 'uacf7_star_review_add_db_menu', 12 );
}


/*
 * register star review custom post type meta box
 */
add_action( 'add_meta_boxes', function () {

	// add_meta_box( 'uacf7_star_review_metabox', 'UACF7 Review Content', 'uacf7_star_review_meta_option', 'uacf7_review', 'normal', 'high' );
	add_meta_box( 'uacf7_star_review_shortcode_metabox', 'Shortcode', 'uacf7_star_review_shortcode_callback', 'uacf7_review', 'side', 'high' );
} );


/*
 * register star review custom post meta option
 * Need to Remove After working new options 
 */
if ( ! function_exists( 'uacf7_star_review_meta_option' ) ) {
	function uacf7_star_review_meta_option( $post ) {
		ob_start();

		$list_forms = get_posts( array(
			'post_type' => 'wpcf7_contact_form',
			'posts_per_page' => -1
		) );
		$uacf7_review_form_id = get_post_meta( $post->ID, 'uacf7_review_form_id', true ) ? get_post_meta( $post->ID, 'uacf7_review_form_id', true ) : '';
		$uacf7_review_fornt_style = get_post_meta( $post->ID, 'uacf7_review_fornt_style', true ) ? get_post_meta( $post->ID, 'uacf7_review_fornt_style', true ) : '';
		$uacf7_reviewer_name = get_post_meta( $post->ID, 'uacf7_reviewer_name', true ) ? get_post_meta( $post->ID, 'uacf7_reviewer_name', true ) : '';
		$uacf7_reviewer_image = get_post_meta( $post->ID, 'uacf7_reviewer_image', true ) ? get_post_meta( $post->ID, 'uacf7_reviewer_image', true ) : '';
		$uacf7_review_title = get_post_meta( $post->ID, 'uacf7_review_title', true ) ? get_post_meta( $post->ID, 'uacf7_review_title', true ) : '';
		$uacf7_review_rating = get_post_meta( $post->ID, 'uacf7_review_rating', true ) ? get_post_meta( $post->ID, 'uacf7_review_rating', true ) : '';
		$uacf7_review_desc = get_post_meta( $post->ID, 'uacf7_review_desc', true ) ? get_post_meta( $post->ID, 'uacf7_review_desc', true ) : '';
		$uacf7_hide_disable_review = get_post_meta( $post->ID, 'uacf7_hide_disable_review', true ) ? get_post_meta( $post->ID, 'uacf7_hide_disable_review', true ) : '';
		$uacf7_show_review_form = get_post_meta( $post->ID, 'uacf7_show_review_form', true ) ? get_post_meta( $post->ID, 'uacf7_show_review_form', true ) : '';
		$uacf7_review_extra_class = get_post_meta( $post->ID, 'uacf7_review_extra_class', true ) ? get_post_meta( $post->ID, 'uacf7_review_extra_class', true ) : '';
		$uacf7_review_column = get_post_meta( $post->ID, 'uacf7_review_column', true ) ? get_post_meta( $post->ID, 'uacf7_review_column', true ) : '';
		$uacf7_review_text_align = get_post_meta( $post->ID, 'uacf7_review_text_align', true ) ? get_post_meta( $post->ID, 'uacf7_review_text_align', true ) : '';
		$uacf7_review_carousel = get_post_meta( $post->ID, 'uacf7_review_carousel', true ) ? get_post_meta( $post->ID, 'uacf7_review_carousel', true ) : '';
		if ( $uacf7_review_form_id != '' ) {
			$ContactForm = WPCF7_ContactForm::get_instance( $uacf7_review_form_id );
			$form_fields = $ContactForm->scan_form_tags();
		} else {
			$form_fields = [];
		}

		?>
		<div class="wrap uacf7-admin-cont uacf7-review-meta-option">
			<!--Tab buttons start-->
			<div class="uacf7-tab">
				<a class="tablinks active" onclick="uacf7_settings_tab(event, 'uacf7_review_content')">
					<?php echo esc_html__( 'Content', 'ultimate-addons-cf7' ); ?>
				</a>

			</div>
			<!--Tab buttons end-->

			<!--Tab Addons start-->
			<div id="uacf7_review_content" class="uacf7-tabcontent uacf7_review_content" style="display:block">
				<table class="uacf7-option-table">
					<tr>
						<th>
							<p><label for="uacf7_review_form_id">
									<?php echo esc_html__( "Select Form", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_review_form_id" id="uacf7_review_form_id">
									<?php
									echo '<option value="">Select form  </option>';
									foreach ( $list_forms as $form ) {
										$selected = $uacf7_review_form_id == $form->ID ? 'selected' : '';
										echo '<option value="' . esc_attr( $form->ID ) . '"  ' . $selected . ' >' . esc_attr( $form->post_title ) . '  </option>';
									}
									?>
								</select>
							</p>
						</td>
					</tr>
					<!--                     
					<tr>
					  <th>
						<p><label for="uacf7_review_fornt_style"><?php // echo esc_html__("Review Style","ultimate-addons-cf7"); ?></label></p>
					  </th>
					  <td>
						<p>
							<select name="uacf7_review_fornt_style" class="uacf7_review_fornt_style" id="uacf7_review_fornt_style">
								<option value="style-one"> Style One</option>
								
							</select>
						</p>
					  </td>
					</tr> -->

					<tr>
						<th>
							<p><label for="uacf7_reviewer_name">
									<?php echo esc_html__( "Reviewer Name", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_reviewer_name" class="uacf7_reviewer_name" id="uacf7_reviewer_name">
									<option value="style-one"> Select Field</option>
									<?php
									foreach ( $form_fields as $tag ) {
										if ( $tag['type'] != 'submit' ) {
											echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $uacf7_reviewer_name, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
										}
									}
									?>
								</select>
							</p>
						</td>
					</tr>


					<tr>
						<th>
							<p><label for="uacf7_reviewer_image">
									<?php echo esc_html__( "Reviewer Image", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_reviewer_image" class="uacf7_reviewer_image" id="uacf7_reviewer_image">
									<option value="style-one"> Select Field</option>
									<?php
									foreach ( $form_fields as $tag ) {
										if ( $tag['type'] != 'submit' ) {
											echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $uacf7_reviewer_image, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
										}
									}
									?>
								</select>
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_title">
									<?php echo esc_html__( "Review Title", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_review_title" id="uacf7_review_title">
									<option value="style-one"> Select Field</option>
									<?php
									foreach ( $form_fields as $tag ) {
										if ( $tag['type'] != 'submit' ) {
											echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $uacf7_review_title, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
										}
									}
									?>
								</select>
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_rating">
									<?php echo esc_html__( "Review Rating", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_review_rating" id="uacf7_review_rating">
									<option value="style-one"> Select Field</option>
									<?php
									foreach ( $form_fields as $tag ) {
										if ( $tag['type'] != 'submit' ) {
											echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $uacf7_review_rating, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
										}
									}
									?>
								</select>
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_desc">
									<?php echo esc_html__( "Review Desc", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_review_desc" id="uacf7_review_desc">
									<option value="style-one"> Select Field</option>
									<?php
									foreach ( $form_fields as $tag ) {
										if ( $tag['type'] != 'submit' ) {
											echo '<option value="' . esc_attr( $tag['name'] ) . '" ' . selected( $uacf7_review_desc, $tag['name'] ) . '>' . esc_attr( $tag['name'] ) . '</option>';
										}
									}
									?>
								</select>
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_extra_class">
									<?php echo esc_html__( "Extra Class", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<input type="text" id="uacf7_review_extra_class" name="uacf7_review_extra_class"
									value="<?php echo esc_attr_e( $uacf7_review_extra_class ); ?>"
									placeholder="Entra extra class">
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_column">
									<?php echo esc_html__( "Column ", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<select name="uacf7_review_column" id="uacf7_review_column">
									<option <?php if ( $uacf7_review_column == 1 ) {
										echo "selected";
									} ?> value="1"> Select
										Column
									</option>
									<option <?php if ( $uacf7_review_column == 2 ) {
										echo "selected";
									} ?> value="2">2 Column
									</option>
									<option <?php if ( $uacf7_review_column == 3 ) {
										echo "selected";
									} ?> value="3">3 Column
									</option>
									<option <?php if ( $uacf7_review_column == 4 ) {
										echo "selected";
									} ?> value="4">4 Column
									</option>
								</select>
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_text_align">
									<?php echo esc_html__( "Text Align", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<p>
								<input type="radio" <?php if ( $uacf7_review_text_align == 'left' ) {
									echo "checked";
								} ?>
									id="left" name="uacf7_review_text_align" value="left">
								<label for="html">Left</label><br>
								<input type="radio" <?php if ( $uacf7_review_text_align == 'center' ) {
									echo "checked";
								} ?>
									id="center" name="uacf7_review_text_align" value="center">
								<label for="css">Center</label><br>
								<input type="radio" <?php if ( $uacf7_review_text_align == 'right' ) {
									echo "checked";
								} ?>
									id="right" name="uacf7_review_text_align" value="right">
								<label for="javascript">Right</label>
							</p>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_hide_disable_review">
									<?php echo esc_html__( "Disable Auto Publish", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<label class="uacf7-admin-toggle" for="uacf7_hide_disable_review">
								<input type="checkbox" class="uacf7-admin-toggle__input" name="uacf7_hide_disable_review"
									id="uacf7_hide_disable_review" <?php if ( $uacf7_hide_disable_review == true ) {
										echo esc_attr__( 'checked' );
									} ?>>
								<span class="uacf7-admin-toggle-track"><span class="uacf7-admin-toggle-indicator"><span
											class="checkMark"><svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation"
												aria-hidden="true">
												<path
													d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z">
												</path>
											</svg></span></span></span>
							</label>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_show_review_form">
									<?php echo esc_html__( "Show Form", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<label class="uacf7-admin-toggle1" for="uacf7_show_review_form">
								<input type="checkbox" class="uacf7-admin-toggle__input" name="uacf7_show_review_form"
									id="uacf7_show_review_form" <?php if ( $uacf7_show_review_form == true ) {
										echo esc_attr__( 'checked' );
									} ?>>
								<span class="uacf7-admin-toggle-track"><span class="uacf7-admin-toggle-indicator"><span
											class="checkMark"><svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation"
												aria-hidden="true">
												<path
													d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z">
												</path>
											</svg></span></span></span>
							</label>
						</td>
					</tr>

					<tr>
						<th>
							<p><label for="uacf7_review_carousel">
									<?php echo esc_html__( "Carousel", "ultimate-addons-cf7" ); ?>
								</label></p>
						</th>
						<td>
							<label class="uacf7-admin-toggle1" for="uacf7_review_carousel">
								<input type="checkbox" class="uacf7-admin-toggle__input" name="uacf7_review_carousel"
									id="uacf7_review_carousel" <?php if ( $uacf7_review_carousel == true ) {
										echo esc_attr__( 'checked' );
									} ?>>
								<span class="uacf7-admin-toggle-track"><span class="uacf7-admin-toggle-indicator"><span
											class="checkMark"><svg viewBox="0 0 24 24" id="ghq-svg-check" role="presentation"
												aria-hidden="true">
												<path
													d="M9.86 18a1 1 0 01-.73-.32l-4.86-5.17a1.001 1.001 0 011.46-1.37l4.12 4.39 8.41-9.2a1 1 0 111.48 1.34l-9.14 10a1 1 0 01-.73.33h-.01z">
												</path>
											</svg></span></span></span>
							</label>
						</td>
					</tr>

				</table>
			</div>
			<!--Tab Addons end-->
			<!--Tab Addons start-->
		</div>
		<?php
		wp_nonce_field( 'uacf7_star_rating_meta_box_nonce', 'uacf7_star_rating_meta_box_noncename' );
		echo ob_get_clean();
	}
}


/*
 * register star review custom post meta shortcode
 */
if ( ! function_exists( 'uacf7_star_review_shortcode_callback' ) ) {
	//Metabox shortcode
	function uacf7_star_review_shortcode_callback() {
		$uacf7_review = isset( $_GET['post'] ) ? '[uacf7_review id="' . $_GET['post'] . '"]' : '';
		?>
		<input type="text" name="uacf7_review_shortcode" class="uacf7_review_shortcode"
			value="<?php echo esc_attr( $uacf7_review ); ?>" readonly>
		<?php
	}
}


/*
 * Get Form Tag using ajax
 */
if ( ! function_exists( 'uacf7_ajax_star_rating_form_tag' ) ) {
	function uacf7_ajax_star_rating_form_tag() {
		$form_id = $_POST['form_id'];
		$ContactForm = WPCF7_ContactForm::get_instance( $form_id );
		$form_fields = $ContactForm->scan_form_tags();
		$options = '<option value="">Select Field</option>';
		foreach ( $form_fields as $tag ) {
			if ( $tag['type'] != 'submit' ) {
				$options .= '<option value="' . esc_attr( $tag['name'] ) . '" >' . esc_attr( $tag['name'] ) . '</option>';
			}
		}
		echo $options;
		wp_die();
	}
	add_action( 'wp_ajax_uacf7_ajax_star_rating_form_tag', 'uacf7_ajax_star_rating_form_tag' );
	add_action( 'wp_ajax_nopriv_uacf7_ajax_star_rating_form_tag', 'uacf7_ajax_star_rating_form_tag' );
}


/*
 * Change review status in to database addon using ajax
 */
if ( ! function_exists( 'uacf7_ajax_star_rating_is_review' ) ) {
	function uacf7_ajax_star_rating_is_review() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'uacf7_form';
		$id = $_POST['id'];
		$is_checked = $_POST['is_checked'];
		$sql = $wpdb->prepare( "UPDATE $table_name SET is_review= %s WHERE id= %s", $is_checked, $id );
		$wpdb->query( $sql );
		wp_die();
	}
	add_action( 'wp_ajax_uacf7_ajax_star_rating_is_review', 'uacf7_ajax_star_rating_is_review' );
	add_action( 'wp_ajax_nopriv_uacf7_ajax_star_rating_is_review', 'uacf7_ajax_star_rating_is_review' );
}


/*
 * Save post meeta data
 */
if ( ! function_exists( 'uacf7_star_review_save_post' ) ) {
	function uacf7_star_review_save_post( $post_id ) {
		if ( ! isset( $_POST['uacf7_star_rating_meta_box_noncename'] ) || ! wp_verify_nonce( $_POST['uacf7_star_rating_meta_box_noncename'], 'uacf7_star_rating_meta_box_nonce' ) ) {
			return;
		}

		if ( isset( $_POST['uacf7_review_form_id'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_form_id', sanitize_text_field( $_POST['uacf7_review_form_id'] ) );
		}
		if ( isset( $_POST['uacf7_review_fornt_style'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_fornt_style', sanitize_text_field( $_POST['uacf7_review_fornt_style'] ) );
		}
		if ( isset( $_POST['uacf7_reviewer_name'] ) ) {
			update_post_meta( $post_id, 'uacf7_reviewer_name', sanitize_text_field( $_POST['uacf7_reviewer_name'] ) );
		}
		if ( isset( $_POST['uacf7_reviewer_image'] ) ) {
			update_post_meta( $post_id, 'uacf7_reviewer_image', sanitize_text_field( $_POST['uacf7_reviewer_image'] ) );
		}
		if ( isset( $_POST['uacf7_review_title'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_title', sanitize_text_field( $_POST['uacf7_review_title'] ) );
		}
		if ( isset( $_POST['uacf7_review_rating'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_rating', sanitize_text_field( $_POST['uacf7_review_rating'] ) );
		}
		if ( isset( $_POST['uacf7_review_desc'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_desc', sanitize_text_field( $_POST['uacf7_review_desc'] ) );
		}
		if ( isset( $_POST['uacf7_hide_disable_review'] ) ) {
			update_post_meta( $post_id, 'uacf7_hide_disable_review', true );
		} else {
			update_post_meta( $post_id, 'uacf7_hide_disable_review', false );
		}
		if ( isset( $_POST['uacf7_show_review_form'] ) ) {
			update_post_meta( $post_id, 'uacf7_show_review_form', true );
		} else {
			update_post_meta( $post_id, 'uacf7_show_review_form', false );
		}
		if ( isset( $_POST['uacf7_review_extra_class'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_extra_class', sanitize_text_field( $_POST['uacf7_review_extra_class'] ) );
		}
		if ( isset( $_POST['uacf7_review_column'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_column', sanitize_text_field( $_POST['uacf7_review_column'] ) );
		}
		if ( isset( $_POST['uacf7_review_text_align'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_text_align', sanitize_text_field( $_POST['uacf7_review_text_align'] ) );
		}
		if ( isset( $_POST['uacf7_review_custom_css'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_custom_css', $_POST['uacf7_review_custom_css'] );
		}
		if ( isset( $_POST['uacf7_review_carousel'] ) ) {
			update_post_meta( $post_id, 'uacf7_review_carousel', true );
		} else {
			update_post_meta( $post_id, 'uacf7_review_carousel', false );
		}

	}
	add_action( 'save_post', 'uacf7_star_review_save_post' );
}


/*
 * checked review form id status
 */
if ( ! function_exists( 'uacf7_star_review_status' ) ) {
	function uacf7_star_review_status( $status, $id ) {
		global $wpdb;
		$meta_key = 'uacf7_review_form_id';
		$data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $meta_key, $id ), ARRAY_N );
		if ( count( $data ) > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	add_filter( 'uacf7_star_review_status', 'uacf7_star_review_status', 10, 2 );
}

?>