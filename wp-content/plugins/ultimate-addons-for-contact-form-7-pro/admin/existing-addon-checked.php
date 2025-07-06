<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Existing - Addons Checked
 * @author Sydur Rahman
 * @param $form
 * @since 1.5.4
 */
class UACF7_PRO_EXISTING_MIGRATION{

    private  $existing_addons = [
        'ultimate-conditional-redirect' => ['UACF7 Addon - Redirection Pro (Conditional Redirect + Tag Support)', 'ultimate-conditional-redirect.php', 'uacf7_enable_redirection_pro'],
        'ultimate-conditional-field-pro' => ['UACF7 Addon - Conditional Field Pro','ultimate-conditional-field-pro.php', 'uacf7_enable_conditional_field_pro' ],
        'ultimate-column-custom-width' => ['UACF7 Addon - Column Custom Width','ultimate-column-custom-width.php', 'uacf7_enable_field_column_pro'],
        'ultimate-global-settings' => ['UACF7 Addon - Global Styler','uacf7-global-settings.php', 'uacf7_enable_uacf7style_global'],
        'ultimate-booking-form' => ['UACF7 Addon - Booking Form','uacf7-booking-form.php', 'uacf7_enable_booking_form'],
        'ultimate-post-submission' => ['UACF7 Addon - Frontend Post Submission','ultimate-post-submission.php', 'uacf7_enable_post_submission'],
        'ultimate-conversational-form' => ['UACF7 Addon - Conversational Form','ultimate-conventional-form.php', 'uacf7_enable_conversational_form'],
        'ultimate-star-rating-pro' => ['UACF7 Addon - Star Rating Pro','ultimate-star-rating-pro.php', 'uacf7_enable_star_rating_pro'],
        'ultimate-range-slider-pro' => ['UACF7 Addon - Range Slider Pro','ultimate-range-slider-pro.php', 'uacf7_enable_range_slider_pro'],
        'ultimate-ip-geolocation' => ['UACF7 Addon - IP Geolocation','ultimate-addons-ip-geolocation.php', 'uacf7_enable_ip_geo_fields'],
        'ultimate-repeater' => ['UACF7 Addon - Repeater Field','uacf7-repeater.php', 'uacf7_enable_repeater_field'],
        'ultimate-product-dropdown' => ['UACF7 Addon - WooCommerce Product Dropdown','ultimate-product-dropdown.php', 'uacf7_enable_product_dropdown_pro'],
        'ultimate-woocommerce-checkout' => ['UACF7 Addon - WooCommerce Checkout','ultimate-woocommerce-checkout.php', 'uacf7_enable_product_auto_cart'],
        'ultimate-multistep-pro' => ['UACF7 Addon - Multistep Pro', 'ultimate-addon-multistep-pro.php', 'uacf7_enable_multistep_pro'],
    ];
    
    private $activate_plugins = [];
    private $migration_status = '';

    public function __construct(){
        add_action('admin_init', array($this, 'init'), 11, 2);
        
    }
    public function init(){

        $this->uacf7_pro_get_existing_addons_activate();   
        $this->migration_status = get_option('uacf7_existing_plugin_status');

        if(apply_filters('uacf7_checked_license_status', '') != false || apply_filters('uacf7_checked_license_status', '') != ''){ 
            if($this->migration_status == 'yes' ){
                add_action('wp_ajax_uacf7_pro_existing_addons_migration', array($this, 'uacf7_pro_existing_addons_migration'), 10, 1);
                add_action('admin_notices', array($this, 'uacf7_pro_existing_addons_checked_notice'), 10, 1);

            }elseif($this->migration_status == 'migrated'){ 
                add_action('admin_notices', array($this, 'uacf7_pro_existing_addons_checked_notice'), 10, 1);

            }else{
                update_option('uacf7_existing_plugin_status', 'done'); 
            }
        } 
    }
    public function uacf7_pro_get_existing_addons_activate(){
        if(is_array($this->existing_addons) && !empty($this->existing_addons)){
            foreach($this->existing_addons as $key => $value){
                if(is_plugin_active($key.'/'.$value[1])){ 
                    update_option('uacf7_existing_plugin_status', 'yes');  
                    
                    $user_id = get_current_user_id();
                    if ( get_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', true ) == 'closed' && get_option("UltimateAddonsforContactForm7Pro_lic_Key","") == '' ) {
                        delete_user_meta( $user_id, 'uacf7_pro_license_notice_dismissed', '' );
                    }
                
                    $this->activate_plugins[$key] = $value;
                }
            } 
        } 
    }
    // admin notice
    public function uacf7_pro_existing_addons_checked_notice(){
       ob_start(); 
       ?>
       <style>
        
            .uacf7-notice-migration-steps {
            display: none;
            visibility: hidden;
            }
            .uacf7-notice-migration-steps.active {
            display: block;
            visibility: visible;
            }
            .uacf7-notice-migration {
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
            }
            .uacf7-notice-migration h4 {
                margin: 0;
                margin-bottom: 15px;
                font-size: 20px;
                font-weight: 700;
                color: #382673;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .uacf7-notice-migration p {
            margin: ;
            font-size: 14px;
            padding: 2px;
            margin: 4px 0;
            font-weight: 500;
            color: #4f4e52;
            }
            .uacf7-notice-migration h5 {
            font-size: 16px;
            font-weight: 700;
            margin: 0; 
            margin-bottom: 12px;
            color: #4f4e52;
            }
            .uacf7-pro-migration-btn{
                padding: 6px 14px;
                text-decoration: none;
                border: 1px solid #382673;
                border-radius: 4px;
                font-size: 14px;
                transition: .4s;
                cursor: pointer;
                background-color: #382673;
                color: #fff;
            }
            .uacf7-pro-migration-btn:hover{
                background-color: #fff;
                color: #382673;
            }
            .uacf7-migrate-btn-wrap {
                display: flex;
                align-items: center;
                
                margin-top: 15px;
            }
            .uacf7-old-addon-list li {
                font-weight: 600;
                color: #382673;
                font-size: ;
            }
       </style>
            <div class="uacf7-notice-migration notice notice-info ">
                 <?php if($this->migration_status != 'migrated'  ): ?>
                    <div class="uacf7-notice-migration-steps step-1 active">
                        <h4><?php echo  _e('Welcome to the new Ultimate Addon for Contact Form 7 Pro experience !', 'ultimate-addons-cf7-pro') ?></h4>
                        
                        <h5>
                            <?php echo  _e('Thank you for using Ultimate Addons for Contact Form 7 Pro. We have made some changes to the plugin. Please read the following carefully.', 'ultimate-addons-cf7-pro') ?>
                        </h5>

                        <p> <?php printf(
                            __('It Looks like you have some of %s addon installed. To move forward you no longer need them in our upcoming version.', 'ultimate-addons-cf7-pro'), 
                            ' <strong>Ultimate Addons for Contact Form 7 Pro </strong>'
                        ); ?>  </p>

                        <p><?php echo  _e('All of Pro Addons are now packaged into a single pro plugins.', 'ultimate-addons-cf7-pro') ?> <a href="<?php echo esc_url('https://themefic.com/uacf7-revamped-plugin-installation-and-options/') ?>"><?php echo  _e('Click Here To learn more.', 'ultimate-addons-cf7-pro') ?></a></p> 
                        <span class="uacf7-migrate-btn-wrap"> 
                            <button class="uacf7-pro-migration-btn uacf7-migration-lets-start"><?php echo  _e("let's start the migration", 'ultimate-addons-cf7-pro') ?></button>
                        </span>
                    </div>
                    <div class="uacf7-notice-migration-steps step-2">
                        <h4><?php echo  _e('Awesome! Here’s what the migration process will do...', 'ultimate-addons-cf7-pro') ?> </h4>
                        <h5><?php echo  _e('Firstly, it will deactivate all active  Ultimate Addon for Contact Form 7 pro addons. Here’s the complete list: ', 'ultimate-addons-cf7-pro') ?></h5>

                        <?php 
                        if(is_array($this->activate_plugins) && !empty($this->activate_plugins)){
                            echo "<ol class='uacf7-old-addon-list'>";
                            foreach($this->activate_plugins as $key => $value){
                               echo "<li id='".$key."'>".$value[0]."</li>";
                            } 
                            echo "</ol>";
                        }
                        ?>
                         <p> <?php printf(
                            __('Once the individual add-ons are deactivated, the corresponding settings will be migrated over to the %s plugin.', 'ultimate-addons-cf7-pro'), 
                            ' <strong>Ultimate Addons for Contact Form 7 Pro </strong>'
                        ); ?>  </p> 

                        <p><?php echo  _e('The individual add-ons will not be deleted automatically. You’ll still find them in your admin Plugins page.', 'ultimate-addons-cf7-pro') ?> </p>
                        <span class="uacf7-migrate-btn-wrap">
                            <button class="uacf7-pro-migration-btn uacf7-start-migrate"> <?php echo  _e("Migration to Pro", 'ultimate-addons-cf7-pro') ?></button>
                        </span>
                    </div> 
                <?php endif; ?>
                <?php if($this->migration_status =='migrated' ): ?>
                    <!-- SVG Success -->
                    
                    <h4><svg style="width: 20px; height: 20px; " viewBox="0 0 24 24">
                        <path fill="#4caf50" d="M12,2C6.5,2 2,6.5 2,12C2,17.5 6.5,22 12,22C17.5,22 22,17.5 22,12C22,6.5 17.5,2 12,2M17,9L11,15L7,11L8.5,9.5L11,12L15.5,7.5L17,9Z" />
                    </svg><?php echo  _e('Success !', 'ultimate-addons-cf7-pro') ?></h4>

             
                    <p> <?php printf(
                        __('Awesome! You have successfully migrated to %s.', 'ultimate-addons-cf7-pro'), 
                        ' <strong>Ultimate Addons for Contact Form 7 Pro </strong>'
                    ); ?> <a href="<?php  echo admin_url('admin.php?page=uacf7_addons') ?>"><?php echo  _e('Click here to review your active addons.', 'ultimate-addons-cf7-pro') ?></a> </p> 

                    <p><?php echo  _e('Now you can delete the individual addons from your', 'ultimate-addons-cf7-pro') ?> <a href="<?php echo admin_url('plugins.php') ?>"><?php echo  _e('plugins page.', 'ultimate-addons-cf7-pro') ?></a> </p>

                <?php 

                    // migration is completely done
                    update_option('uacf7_existing_plugin_status', 'done');   
                    endif; 
                ?>

                  
            </div>
       <?php
       echo ob_get_clean();
    }
    public function uacf7_pro_existing_addons_migration(){
       // noce Validation
        if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'uacf7_pro_nonce')){
            wp_send_json_error(array('message' => 'Invalid Nonce'));
        }
  
        if(is_array($this->existing_addons) && !empty($this->existing_addons) && $this->migration_status != 'done'){
            $uacf7_settings = !empty(get_option( 'uacf7_settings' )) ? get_option( 'uacf7_settings' ) : array();
            foreach($this->existing_addons as $key => $value){
                if(is_plugin_active($key.'/'.$value[1])){
                    deactivate_plugins($key.'/'.$value[1]); 
                    $uacf7_settings[$value[2]] = true;
                }
            }
            update_option('uacf7_settings', $uacf7_settings);
            update_option('uacf7_existing_plugin_status', 'migrated');
        } 
        
    }
  
  
  
}
new UACF7_PRO_EXISTING_MIGRATION();
?>