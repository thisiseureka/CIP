<?php
/**
 * Plugin Name: Ultimate Addons for Contact Form 7 Pro
 * Plugin URI: https://live.themefic.com/ultimate-cf7/pro
 * Description: Extend the power of Ultimate Addons for Contact Form 7 with Pro. More advanced functions crafted for your Website's needs.
 * Version: 1.8.9
 * Author: Themefic
 * Author URI: https://themefic.com/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: ultimate-addons-cf7-pro
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Class Ultimate_Addons_CF7_PRO
 */
class Ultimate_Addons_CF7_PRO {

	public function __construct() {

		// Added by M Hemel Hasan
		define( 'UACF7_PRO_FILE', __FILE__ );
		define( 'UACF7_PRO_URL', plugin_dir_url( __FILE__ ) );
		define( 'UACF7_PRO_ADDONS', UACF7_PRO_URL . 'addons' );
		define( 'UACF7_PRO_PATH', plugin_dir_path( __FILE__ ) );
		define( 'UACF7_PRO_PATH_ADDONS', UACF7_PRO_PATH . '/addons' );
		define( 'UACF7_PRO_VERSION', '1.8.9' );

		// add_action( 'plugin_loaded', array( $this, 'plugin_loaded' ), 20 );
		add_action( 'admin_init', array( $this, 'admin_init' ), 20 );
		add_action( 'init', array( $this, 'plugin_loaded' ), 9 );

		// activation hook
		register_activation_hook( __FILE__, array( $this, 'uacf7_pro_activation_hook' ) );

		// deactivation hook
		register_deactivation_hook( __FILE__, array( $this, 'uacf7_pro_deactivation_hook' ) );

		// Network admin menu hook for multisite
		// if ( is_multisite() ) {
		// 	$existing_plugin_status = get_option( 'uacf7_existing_plugin_status' );
		// 	if ( file_exists( UACF7_PRO_PATH . "admin/admin-option.php" ) && $existing_plugin_status == 'done' ) {
		// 		require_once UACF7_PRO_PATH . "admin/admin-option.php";
		// 	}
		// }
	}

	public function plugin_loaded() {
		if ( ! class_exists( 'Ultimate_Addons_CF7' ) ) {
			return;
		} else {
			// add_filter( 'wpcf7_load_js', '__return_false' );
			require_once UACF7_PRO_PATH . 'inc/license.php';

			// Init admin Option
			$existing_plugin_status = get_option( 'uacf7_existing_plugin_status' );
			if ( file_exists( UACF7_PRO_PATH . "admin/admin-option.php" ) && $existing_plugin_status == 'done' ) {
				require_once UACF7_PRO_PATH . "admin/admin-option.php";
			}

			if ( file_exists( UACF7_PRO_PATH . "inc/functions.php" ) && class_exists( 'WPCF7' ) && get_option( 'uacf7_version' ) >= '3.3.0' ) {
				require_once UACF7_PRO_PATH . "inc/functions.php";
			}
		}


	}

	public function admin_init() {
		if ( class_exists( 'Ultimate_Addons_CF7' ) && get_option( 'uacf7_version' ) >= '3.3.0' ) {

			// Enqueye Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'uacf7_pro_admin_scripts' ) );

			add_filter( 'tf_license_info_pro_callback', array( $this, 'tf_license_info_callback' ), 10, 2 );

			//options
			do_action( 'uacf7_pro_include_addons' );
		} elseif ( ! class_exists( 'Ultimate_Addons_CF7' ) ) {
			//Pro activated: no
			update_option( 'uacf7_pro_activated', 'no' );
			add_action( 'admin_notices', array( $this, 'uacf7_admin_notice_error' ) );
		}
	}



	public function uacf7_admin_notice_error() {
		?>
		<div class="notice notice-error">
			<p>
				<?php printf(
					__( '%s requires %s to be installed and active. You can install and activate it from %s', 'ultimate-addons-cf7' ),
					'<strong>Ultimate Addons for Contact Form 7 Pro</strong>',
					'<strong>Ultimate Addons For Contact Form 7 (Free)</strong>',
					'<a href="' . admin_url( 'plugin-install.php?s=Ultimate+Addons+For+Contact+Form+7&tab=search&type=term' ) . '">here</a>.'
				); ?>
			</p>
		</div>
		<?php
	}

	public function uacf7_pro_admin_scripts() {
		wp_enqueue_script( 'uacf7-admin-pro-js', UACF7_PRO_URL . 'assets/js/uacf7-admin-pro.js', array( 'jquery' ), UACF7_PRO_VERSION, true );

		wp_localize_script(
			'uacf7-admin-pro-js',
			'uacf7_pro_admin',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'uacf7_pro_nonce' ),
			)
		);

	}

	public function tf_license_info_callback( $value ) {
		ob_start();
		?>
		<div class="tf-setting-dashboard">

			<!-- deshboard-header-include -->
			<?php //echo tf_dashboard_header(); ?>

			<div class="tf-setting-license">
				<div class="tf-setting-license-tabs">
					<ul>
						<li class="active">
							<span>
								<i class="fas fa-key"></i>
								<?php _e( "License Info", "ultimate-addons-cf7" ); ?>
							</span>
						</li>
					</ul>
				</div>
				<div class="tf-setting-license-field">
					<div class="tf-tab-wrapper">
						<div id="license" class="tf-tab-content">
							<div class="tf-field tf-field-callback" style="width: 100%;">
								<div class="tf-fieldset"></div>
							</div>
							<?php
							$licenseKey = '';
							$liceEmail = '';
							?>
							<div class="tf-field tf-field-text" style="width: 100%;">
								<label for="tf_settings[license-key]" class="tf-field-label">
									<?php _e( "License Key", "ultimate-addons-cf7" ); ?></label>

								<span
									class="tf-field-sub-title"><?php _e( "Insert your license key here. You can get it from our Client Portal -> Support -> License keys. ", "ultimate-addons-cf7" ); ?></span>

								<div class="tf-fieldset">
									<input type="text" name="tf_settings[license-key]" id="tf_settings[license-key]" value=""
										placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" />
								</div>
							</div>

							<div class="tf-field tf-field-text" style="width: 100%;">
								<label for="tf_settings[license-email]" class="tf-field-label">
									<?php _e( "License Email ", "ultimate-addons-cf7" ); ?></label>

								<span
									class="tf-field-sub-title"><?php _e( "We will send update news of this product by this email address, don't worry, we hate spam", "ultimate-addons-cf7" ); ?></span>

								<div class="tf-fieldset">
									<input type="text" name="tf_settings[license-email]" id="tf_settings[license-email]"
										value="" />
								</div>
							</div>

							<div class="tf-field tf-field-callback" style="width: 100%;">
								<div class="tf-fieldset">
									<div class="tf-license-activate">
										<p class="submit"><input type="submit" name="submit" id="submit"
												class="button button-primary" value="Activate" /></p>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	// Activation Hooks
	public function uacf7_pro_activation_hook() {
		//Pro activated: yes
		update_option( 'uacf7_pro_activated', 'yes' );
	}
	// Deactivation Hooks
	public function uacf7_pro_deactivation_hook() {
		//Pro activated: no
		delete_option( 'uacf7_pro_activated' );
		delete_option( 'uacf7_existing_plugin_status' );

	}

}
new Ultimate_Addons_CF7_PRO();
