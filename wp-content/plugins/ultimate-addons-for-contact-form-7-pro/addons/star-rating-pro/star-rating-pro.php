<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_STAR_RATING_PRO {

	public function __construct() {
		add_action( 'init', array( $this, 'init_plugins_loaded' ), 10, 2 );
		require_once( UACF7_PRO_PATH_ADDONS . '/star-rating-pro/uacf7-metabox/uac7-review-post-meta.php' );

		// Star review post type with metabox
		require 'functions/star-review.php';
	}

	/*
	 * Plugins Lodded
	 */
	public function init_plugins_loaded() {
		
		// Function
		require 'functions/functions.php';

		// if( apply_filters('uacf7_settings', 'uacf7_enable_star_rating') == true) { 

		// Star review shortcode
		require 'functions/shortcode.php';

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_script' ) );
		add_filter( 'uacf7_star_rating_tg_field', array( $this, 'uacf7_star_rating_tg_field_icon' ) );
		add_filter( 'uacf7_star_rating_style_field', array( $this, 'uacf7_star_rating_style_field' ) );
		add_filter( 'uacf7_star_rating_style_pro_feature', array( $this, 'uacf7_star_rating_style_pro_feature' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'uacf7_create_review_database_col' ) );
	}

	//Create Star Review Database column  "is_review"
	public function uacf7_create_review_database_col() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'uacf7_form';

		$charset_collate = $wpdb->get_charset_collate();

		$tableName = $wpdb->prefix . 'leaguemanager_person_status';
		$sql_checked = "SELECT *  FROM information_schema.COLUMNS  WHERE TABLE_SCHEMA = '$wpdb->dbname' AND TABLE_NAME = '$table_name' AND COLUMN_NAME = 'is_review'";

		$checked_status = $wpdb->query( $sql_checked );
		if ( $checked_status != true ) {
			$sql = "ALTER TABLE $table_name 
			MODIFY COLUMN form_date DATETIME NULL,
			ADD is_review VARCHAR(2) DEFAULT 0 NULL AFTER form_value";
			$wpdb->query( $sql );
		}
		// var_dump( $checked_status );
	}


	/*
	 * Enqueue scripts
	 */
	public function enqueue_frontend_script() {
		wp_enqueue_style( 'uacf7-star-rating-style-pro', plugin_dir_url( __FILE__ ) . 'assets/css/star-rating-pro.css' );
		wp_enqueue_style( 'uacf7-owl.carousel.min', plugin_dir_url( __FILE__ ) . 'assets/css/owl.carousel.min.css' );
		wp_enqueue_script( 'jquery.style-10-js', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.style-10.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'star-rating-pro-js', plugin_dir_url( __FILE__ ) . 'assets/js/star-rating-pro.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'uacf7-owl.carousel.min', plugin_dir_url( __FILE__ ) . 'assets/js/owl.carousel.min.js', array( 'jquery' ), false, true );

	}

	/*
	 * Enqueue admin scripts
	 */
	public function enqueue_admin_script() {
		wp_enqueue_style( 'uacf7-star-rating-admin-pro-css', plugin_dir_url( __FILE__ ) . 'assets/css/star-rating-admin-pro.css' );
		wp_enqueue_script( 'star-rating-admin-pro', plugin_dir_url( __FILE__ ) . 'assets/js/star-rating-admin-pro.js', array( 'jquery' ), false, true );

	}

	/*
	 * Tag generator field: Rating field
	 */
	public function uacf7_star_rating_tg_field_icon() {
		?>

		<label for="heart">
			<input type="radio" data-tag-part="option" data-tag-option="icon:" name="icon" id="heart" value="heart" />
			<?php echo esc_html( 'Heart' ); ?>
		</label>

		<label for="thumbs">
			<input type="radio" id="thumbs" data-tag-part="option" data-tag-option="icon:" name="icon" value="thumbs">
			<?php echo esc_html( ' Thumbs Up' ); ?>
		</label>

		<label for="smile">
			<input type="radio" id="smile" data-tag-part="option" data-tag-option="icon:" name="icon" value="smile">
			<?php echo esc_html( ' Smile' ); ?>
		</label>

		<label for="ok">
			<input type="radio" id="ok" data-tag-part="option" data-tag-option="icon:" name="icon" value="ok">
			<?php echo esc_html( ' Ok' ); ?>
		</label>

		<?php do_action( 'uacf7_star_rating_tg_field_icon' ); ?>

		<legend>
			<?php _e( 'Icon Class', 'ultimate-addons-cf7' ); ?>
		</legend>

		<input type="text" data-tag-part="option" data-tag-option="class:" id="tag-generator-panel-text-star-class"
			placeholder="e.g: fa fa-star" />
		<?php
	}

	/*
	 * Tag generator field: Rating Style field
	 */
	public function uacf7_star_rating_style_field() {
		ob_start();
		?>
		<select data-tag-part="value" name="values" id="tag-generator-panel-star-style">
			<option value="default">Default</option>
			<option value="style-one">Style One</option>
			<option value="style-two">Style Two</option>
			<option value="style-three">Style Three</option>
			<option value="style-four">Style Four</option>
			<option value="style-five">Style Five</option>
			<option value="style-six">Style Six</option>
			<option value="style-seven">Style Seven</option>
			<option value="style-eight">Style Eight</option>
			<option value="style-nine">Style Nine</option>
			<option value="style-ten">Style Ten</option>
		</select>

		<?php
		return ob_get_clean();
	}


	/*
	 * Star Rating pro feature
	 */
	public function uacf7_star_rating_style_pro_feature( $default_star_style, $tag ) {

		$validation_error = wpcf7_get_validation_error( $tag->name );

		$class = wpcf7_form_controls_class( $tag->type );

		if ( $validation_error ) {
			$class .= ' wpcf7-not-valid';
		}

		$atts = array();

		$class .= ' uacf7-rating';

		$atts['class'] = $class;

		if ( $tag->is_required() ) {
			$atts['aria-required'] = 'true';
		}
		if ( $validation_error ) {
			$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
				$tag->name
			);
		}

		// rating Style
		$rating_style = $tag->values;

		$atts['aria-invalid'] = $validation_error ? 'true' : 'false';

		$atts = wpcf7_format_atts( $atts );

		$selected = ! empty( $tag->get_option( 'selected', '', true ) ) ? $tag->get_option( 'selected', '', true ) : '5';

		$star1 = ! empty( $tag->get_option( 'star1', '', true ) ) ? $tag->get_option( 'star1', '', true ) : '1';
		$star2 = ! empty( $tag->get_option( 'star2', '', true ) ) ? $tag->get_option( 'star2', '', true ) : '2';
		$star3 = ! empty( $tag->get_option( 'star3', '', true ) ) ? $tag->get_option( 'star3', '', true ) : '3';
		$star4 = ! empty( $tag->get_option( 'star4', '', true ) ) ? $tag->get_option( 'star4', '', true ) : '4';
		$star5 = ! empty( $tag->get_option( 'star5', '', true ) ) ? $tag->get_option( 'star5', '', true ) : '5';

		$rating_icon = '<i class="fas fa-star"></i>';

		if ( function_exists( 'uacf7_rating_icon' ) ) {

			if ( ! empty( uacf7_rating_icon( $tag ) ) ) {
				$rating_icon = uacf7_rating_icon( $tag );
			}

		} else {

			$get_icon = $tag->get_option( 'icon', '', true );

			switch ( $get_icon ) {
				case 'star1':
					$rating_icon = '<i class="far fa-star"></i>';
					break;
				case 'star2':
					$rating_icon = '✪';
					break;
			}
		}

		if ( empty( $rating_style ) || $rating_style[0] == 'default' ) {
			return $default_star_style;
		}

		ob_start();

		$style_class = '';
		if ( $rating_style[0] == 'style-two' ) {
			$style_class = 'style-2';
		} elseif ( $rating_style[0] == 'style-three' ) {
			$style_class = 'style-3';
		} elseif ( $rating_style[0] == 'style-four' ) {
			$style_class = 'style-4';
		} elseif ( $rating_style[0] == 'style-five' ) {
			$style_class = 'style-5';
		} elseif ( $rating_style[0] == 'style-six' ) {
			$style_class = 'style-6';
		} elseif ( $rating_style[0] == 'style-seven' ) {
			$style_class = 'style-7';
		} elseif ( $rating_style[0] == 'style-eight' ) {
			$style_class = 'style-8';
		} elseif ( $rating_style[0] == 'style-nine' ) {
			$style_class = 'style-9';
		} elseif ( $rating_style[0] == 'style-ten' ) {
			$style_class = 'style-10';
		}

		?>
		<div data-name="<?php echo esc_attr( $tag->name ); ?>"
			class="wpcf7-form-control-wrap <?php echo esc_attr( $tag->name ); ?>">
			<div <?php echo $atts; ?>>
				<?php
				if ( $rating_style[0] == 'style-ten' ) {
					?>
					<div class="uacf7-star-ratting <?php echo $style_class; ?>">
						<input type="hidden" data-star1="<?php echo esc_attr( $star1 ); ?>"
							data-star5="<?php echo esc_attr( $star5 ); ?>" data-selected="<?php echo esc_attr( $selected ); ?>"
							name="<?php echo esc_attr( $tag->name ); ?>" value="100">
						<div class="uacf7-star-10" id="<?php echo esc_attr( $tag->name ); ?>"></div>
					</div>
					<?php
				} else {
					if ( $rating_style[0] == 'style-three' || $rating_style[0] == 'style-four' ) { ?>
						<div class="uacf7-star-ratting-wrap <?php echo $style_class; ?>">
							<div class="uacf7-star-ratting-imgoji">
								<div class="uacf7-star-emoji">
									<div class="emoji emoji-0 active" data-star="0">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/<?php echo $style_class; ?>/emoji-1.svg"
											alt="">
									</div>
									<div class="emoji emoji-1" data-star="1">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/<?php echo $style_class; ?>/emoji-1.svg"
											alt="">
									</div>
									<div class=" emoji emoji-2" data-star="2">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/<?php echo $style_class; ?>/emoji-2.svg"
											alt="">
									</div>
									<div class="emoji emoji-3" data-star="3">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/<?php echo $style_class; ?>/emoji-3.svg"
											alt="">
									</div>
									<div class="emoji emoji-4" data-star="4">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/<?php echo $style_class; ?>/emoji-4.svg"
											alt="">
									</div>
									<div class="emoji emoji-5" data-star="5">
										<img src="<?php echo plugin_dir_url( __FILE__ ); ?>assets/img/<?php echo $style_class; ?>/emoji-5.svg"
											alt="">
									</div>
								</div>
							</div>
							<?php

					} ?>
						<div class="uacf7-star-ratting <?php echo $style_class; ?>">
							<?php if ( $rating_style[0] == 'style-six' ) {
								?>
								<label class="uacf7-star uacf7-star-0 uacf7-star-disabled" data-star="0">
									<input <?php echo $atts; ?> type="radio" name="<?php echo esc_attr( $tag->name ); ?>" data-star="1"
										style="display:none" value="0">
									<span class="uacf7-icon"><i class="fa fa-ban"></i> </span>
								</label>
								<?php
							} ?>

							<label class="uacf7-star uacf7-star-1" data-star="1">
								<input <?php echo $atts; ?> type="radio" name="<?php echo esc_attr( $tag->name ); ?>" data-star="1"
									style="display:none" <?php checked( $selected, '1', true ); ?>
									value="<?php echo esc_attr( $star1 ); ?>">
								<span class="uacf7-icon"><?php echo $rating_icon ?> </span>
							</label>
							<label class="uacf7-star uacf7-star-2" data-star="2">
								<input <?php echo $atts; ?> type="radio" name="<?php echo esc_attr( $tag->name ); ?>" data-star="2"
									style="display:none" <?php checked( $selected, '2', true ); ?>
									value="<?php echo esc_attr( $star2 ); ?>">
								<span class="uacf7-icon"><?php echo $rating_icon ?> </span>
							</label>
							<label class="uacf7-star uacf7-star-3 " data-star="3">
								<input <?php echo $atts; ?> type="radio" name="<?php echo esc_attr( $tag->name ); ?>" data-star="3"
									style="display:none" <?php checked( $selected, '3', true ); ?>
									value="<?php echo esc_attr( $star3 ); ?>">
								<span class="uacf7-icon"><?php echo $rating_icon ?> </span>
							</label>
							<label class="uacf7-star uacf7-star-4 " data-star="4">
								<input <?php echo $atts; ?> type="radio" name="<?php echo esc_attr( $tag->name ); ?>" data-star="4"
									style="display:none" <?php checked( $selected, '4', true ); ?>
									value="<?php echo esc_attr( $star4 ); ?>">
								<span class="uacf7-icon"><?php echo $rating_icon ?> </span>
							</label>
							<label class="uacf7-star uacf7-star-5" data-star="5">
								<input <?php echo $atts; ?> type="radio" name="<?php echo esc_attr( $tag->name ); ?>" data-star="5"
									style="display:none" <?php checked( $selected, '5', true ); ?>
									value="<?php echo esc_attr( $star5 ); ?>">
								<span class="uacf7-icon"><?php echo $rating_icon ?> </span>
							</label>
						</div>
						<?php
						if ( $rating_style[0] == 'style-three' || $rating_style[0] == 'style-four' ) {
							echo '</div>';
						}
				} ?>
				</div>
			</div>
			<span>
				<?php
				echo $validation_error; // validation error 
				?>
			</span>

			<?php
			return ob_get_clean();

	}

}

$uacf7_star_rating_pro = new UACF7_STAR_RATING_PRO();

