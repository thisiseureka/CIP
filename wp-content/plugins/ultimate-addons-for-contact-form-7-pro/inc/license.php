<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once "C4BFEE022.php";

class UltimateAddonsforContactForm7Pro_M4BFEE022 {
	public $plugin_file = __FILE__;
	public $responseObj;
	public $licenseMessage;
	public $showMessage = false;
	public $slug = "ultimate-addons-for-contact-form-7-pro";
	function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'SetAdminStyle' ] );
		$licenseKey = get_option( "UltimateAddonsforContactForm7Pro_lic_Key", "" );
		$liceEmail = get_option( "UltimateAddonsforContactForm7Pro_lic_email", "" );

		C4BFEE022::addOnDelete( function () {
			delete_option( "UltimateAddonsforContactForm7Pro_lic_Key" );
		} );

		add_filter( 'uacf7_checked_license_status', array( $this, 'uacf7_checked_license_status' ), 10, 2 );

		if ( C4BFEE022::CheckWPPlugin( $licenseKey, $liceEmail, $this->licenseMessage, $this->responseObj, UACF7_PRO_PATH . 'ultimate-addons-for-contact-form-7-pro.php' ) ) {

			add_action( 'uacf7_license_info_pro_callback', [ $this, 'ActiveAdminMenu' ], 10 );

			add_action( 'admin_init', array( $this, 'uacf7_pro_license_notice_dismissed' ) );

			// license Checked
			add_action( 'wp_ajax_uacf7_pro_deact_license', array( $this, 'uacf7_pro_deact_license' ), 10, 2 );

			//$this->licenselMessage=$this->mess;

		} else {

			add_action( 'admin_notices', array( $this, 'uacf7_pro_license_notice' ) );

			if ( ! empty( $licenseKey ) && ! empty( $this->licenseMessage ) ) {
				$this->showMessage = true;
			}
			update_option( "UltimateAddonsforContactForm7Pro_lic_Key", "" ) || add_option( "UltimateAddonsforContactForm7Pro_lic_Key", "" );

			add_action( 'wp_ajax_uacf7_pro_act_license', array( $this, 'uacf7_pro_act_license' ), 10, 2 );
			add_action( 'uacf7_license_info_pro_callback', [ $this, 'InactiveMenu' ], 10 );
		}
	}
	
	function SetAdminStyle($screen ) {
		$tf_options_screens = array(
			'ultimate-addons_page_uacf7_license_info',
		);
		wp_register_style( "UltimateAddonsforContactForm7ProLic",  UACF7_PRO_URL . "assets/css/_lic_style.css", 10 );

		if ( in_array( $screen, $tf_options_screens )){
			wp_enqueue_style( "UltimateAddonsforContactForm7ProLic" );
		}
	}

	function ActiveAdminMenu() {
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
								<div class="el-license-container uacf7_el-license-container">
									<ul class="el-license-info">
										<li>
											<div>
												<span class="el-license-info-title"><?php _e( "Status", $this->slug ); ?></span>

												<?php if ( $this->responseObj->is_valid ) : ?>
													<span class="el-license-valid"><?php _e( "Valid", $this->slug ); ?></span>
												<?php else : ?>
													<span class="el-license-valid"><?php _e( "Invalid", $this->slug ); ?></span>
												<?php endif; ?>
											</div>
										</li>

										<li>
											<div>
												<span
													class="el-license-info-title"><?php _e( "License Type", $this->slug ); ?></span>
												<?php echo $this->responseObj->license_title; ?>
											</div>
										</li>

										<li>
											<div>
												<span
													class="el-license-info-title"><?php _e( "License Expired on", $this->slug ); ?></span>
												<?php echo $this->responseObj->expire_date;
												if ( ! empty( $this->responseObj->expire_renew_link ) ) {
													?>
													<?php
												}
												?>
											</div>
										</li>

										<li>
											<div>
												<span
													class="el-license-info-title"><?php _e( "Support Expired on", $this->slug ); ?></span>
												<?php
												echo $this->responseObj->support_end;
												if ( ! empty( $this->responseObj->support_renew_link ) ) {
													?>
													<?php
												}
												?>
											</div>
										</li>
										<li>
											<div>
												<span
													class="el-license-info-title"><?php _e( "Your License Key", $this->slug ); ?></span>
												<span
													class="el-license-key"><?php echo esc_attr( substr( $this->responseObj->license_key, 0, 9 ) . "XXXXXXXX-XXXXXXXX" . substr( $this->responseObj->license_key, -9 ) ); ?></span>
											</div>
										</li>
									</ul>
									<div class="el-license-active-btn">
										<?php submit_button( 'Deactivate' ); ?>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function uacf7_checked_license_status( $response ) {
		if ( empty( $this->responseObj ) || $this->responseObj == NULL ) {
			return false;
		}
		return $this->responseObj;
	}


	function InactiveMenu() {
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
								<label for="UltimateAddonsforContactForm7Pro_lic_Key" class="tf-field-label">
									<?php _e( "License Key", "ultimate-addons-cf7" ); ?></label>

								<span
									class="tf-field-sub-title"><?php _e( "Insert your license key here. You can get it from our Client Portal -> Support -> License keys.", "ultimate-addons-cf7" ); ?></span>

								<div class="tf-fieldset">
									<input type="text" name="UltimateAddonsforContactForm7Pro_lic_Key"
										id="UltimateAddonsforContactForm7Pro_lic_Key" value=""
										placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" />
								</div>
							</div>

							<div class="tf-field tf-field-text license-email" style="width: 100%;">
								<label for="UltimateAddonsforContactForm7Pro_lic_email" class="tf-field-label">
									<?php _e( "License Email ", "ultimate-addons-cf7" ); ?></label>

								<span
									class="tf-field-sub-title"><?php _e( "Please enter the email address you used for purchasing the plugin.", "ultimate-addons-cf7" ); ?></span>

								<div class="tf-fieldset">
									<input type="text" name="UltimateAddonsforContactForm7Pro_lic_email"
										id="UltimateAddonsforContactForm7Pro_lic_email" value="" />
								</div>
							</div>

							<div class="tf-field tf-field-callback" style="width: 100%;">
								<div class="tf-fieldset">
									<div class="uacf7-license-activate">
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

	}

	/*
	 * Ajax license activate
	 */
	public function uacf7_pro_act_license() {

		$license_key = $_POST['license_key'];
		$license_email = $_POST['license_email'];

		update_option( 'UltimateAddonsforContactForm7Pro_lic_Key', $license_key );
		update_option( 'UltimateAddonsforContactForm7Pro_lic_email', $license_email );

		die;
	}

	public function uacf7_pro_deact_license() {
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', '' );
		update_option( 'UltimateAddonsforContactForm7Pro_lic_Key', '' );
		update_option( 'UltimateAddonsforContactForm7Pro_lic_email', '' );
		die;
	}


	/*
	 * Admin notice: Licanse activation error
	 */
	function uacf7_pro_license_notice() {
		$user_id = get_current_user_id();

		if(get_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', true ) == 'closed'  && get_option( 'UltimateAddonsforContactForm7Pro_lic_Key' ) == '' && get_option( 'UltimateAddonsforContactForm7Pro_lic_email' ) == '' ) {
			update_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', '' );
		}

		if ( get_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', true ) != 'closed' ) {
			?>
			<div class="wrap">
				<div class="notice notice-error is-dismissible">
					<p> <strong>
							<?php echo esc_html__( 'Activate UACF7 Pro License.', 'ultimate-addons-cf7' ); ?>
						</strong></p>

					<p><span>Please <a href="<?php echo admin_url( '/admin.php?page=uacf7_license_info' ); ?>">Activate your
								license</a> to enable all functions, receive regular product updates, patches, and compatible
							versions.</span>
					<p>


				</div>
			</div>

			<?php
		}
	}

	/*
	 * Update license notice
	 */
	public function uacf7_pro_license_notice_dismissed() {
		$user_id = get_current_user_id();
		// if ( isset($_GET['uacf7-license-dismissed']) ){

		// }
		if ( get_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', true ) != 'closed' ) {
			update_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', 'closed' );
		}
	}

}

new UltimateAddonsforContactForm7Pro_M4BFEE022();