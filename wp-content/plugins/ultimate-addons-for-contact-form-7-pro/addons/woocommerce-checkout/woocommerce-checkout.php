<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
* Init plugin
*/
add_action( 'init', 'uacf7_auto_add_to_cart_init' );
function uacf7_auto_add_to_cart_init(){
     
    // Settings options Update Pro Feature  
    add_action( 'admin_enqueue_scripts', 'uacf7_product_auto_cart_admin_style' ); 
    add_action( 'wp_enqueue_scripts', 'uacf7_auto_cart_enqueue_scripts' );
    // add_filter( 'wpcf7_contact_form_properties', 'uacf7_auto_cart_properties', 10, 2 );
    //Get settings option 
    add_filter( 'uacf7_enable_product_auto_cart', 'uacf7_enable_product_auto_cart_filter' );
  
}

function uacf7_product_auto_cart_admin_style(){
    wp_enqueue_style( 'uacf7-product-auto-cart-pro', plugin_dir_url( __FILE__ ) . 'assets/css/pro-style.css' );
}

function uacf7_enable_product_auto_cart_filter($x){
	if(function_exists('uacf7_checked')){
		return uacf7_checked('uacf7_enable_product_auto_cart');
	}else{
		return '';
	}
}
 

function uacf7_auto_cart_enqueue_scripts() {
	wp_enqueue_script( 'uacf7-auto-cart-scripts', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js', array('jquery'), null, true );
 
    $uacf7_options = get_option( 'uacf7_settings' );

    $product_dropdown = isset($uacf7_options['uacf7_enable_product_dropdown']) ? $uacf7_options['uacf7_enable_product_dropdown'] : false;
    $auto_cart = isset($uacf7_options['uacf7_enable_product_auto_cart']) ? $uacf7_options['uacf7_enable_product_auto_cart'] : false; 
	$checkout_url = '';
	if(function_exists('wc_get_checkout_url')){ 
		$checkout_url = wc_get_checkout_url();
	}

	$cart_url = '';
	if(function_exists('wc_get_cart_url')){ 
		$cart_url = wc_get_cart_url();
	}

	wp_localize_script( 'uacf7-auto-cart-scripts', 'uacf7_pro_object',
		array( 
			'checkout_page' => $checkout_url,
			'cart_page' => $cart_url,
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'product_dropdown' => $product_dropdown,
			'auto_cart' => $auto_cart,
            'redirect_to' => uacf7_product_auto_cart_redirect_to()
		)
	);

}

//Admin scripts
add_action( 'admin_enqueue_scripts', 'uacf7_auto_cart_admin_enqueue_scripts' );
function uacf7_auto_cart_admin_enqueue_scripts() {
	wp_enqueue_style( 'uacf7-auto-cart-style', plugin_dir_url( __FILE__ ) . 'assets/css/styles.css' );
}
 

function uacf7_auto_cart_properties($properties, $cfform) {

    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) { 

        $form = $properties['form'];
        $auto_cart = uacf7_get_form_option( $cfform->id(), 'auto_cart' );
        $uacf7_enable_product_auto_cart = isset($auto_cart['uacf7_enable_product_auto_cart']) ? $auto_cart['uacf7_enable_product_auto_cart'] : false;

        if( $uacf7_enable_product_auto_cart == true ) {

            ob_start();

            $auto_cart = 'uacf7_auto_cart_'.$cfform->id();

            echo '<div class="'.$auto_cart.'">'.$form.'</div>';

            $properties['form'] = ob_get_clean();
        }

    }

    return $properties;
}

//Creating an array to display 'Redirect to' data from all the form
function uacf7_product_auto_cart_redirect_to() {
    $args  = array(
        'post_type'        => 'wpcf7_contact_form',
        'posts_per_page'   => -1,
    );
    $query = new WP_Query( $args );

    $forms = array();

    if ( $query->have_posts() ) :

        while ( $query->have_posts() ) :
            $query->the_post();

            $post_id = get_the_ID();
            $auto_cart = uacf7_get_form_option( $post_id, 'auto_cart' );
            $redirect_to =  isset($auto_cart['uacf7_product_auto_cart_redirect_to']) ? $auto_cart['uacf7_product_auto_cart_redirect_to'] : '';
            
            if( !empty( $redirect_to ) ){
                $forms[ $post_id ] = $redirect_to;
            }
            
        endwhile;
        wp_reset_postdata();
    endif;

    return $forms;
}

/*
Admin menu- Save auto product cart
*/
add_filter( 'uacf7_save_admin_menu', 'uacf7_save_auto_product_cart', 10, 2 );
function uacf7_save_auto_product_cart( $sanitary_values, $input ){
    
    if ( isset( $input['uacf7_enable_product_auto_cart'] ) ) {
        $sanitary_values['uacf7_enable_product_auto_cart'] = $input['uacf7_enable_product_auto_cart'];
    }
    return $sanitary_values;
}

 
/*
* Product add to cart after submiting form by ajax
*/
add_action( 'wp_ajax_uacf7_ajax_add_to_cart_product', 'uacf7_ajax_add_to_cart_product' );
add_action( 'wp_ajax_nopriv_uacf7_ajax_add_to_cart_product', 'uacf7_ajax_add_to_cart_product' );
function uacf7_ajax_add_to_cart_product() {
    
    $product_ids = $_POST['product_ids'];
   
    
    foreach( $product_ids as $key => $data ) :
        $product_id = $data['product_id'];
        $variation_id  = $data['variation_id'];
        $variation_data  = $data['variation_data'];
       
        if($variation_id == 0){
            $product_cart_id = WC()->cart->generate_cart_id( $product_id );

            if( ! WC()->cart->find_product_in_cart( $product_cart_id ) ){ 
                WC()->cart->add_to_cart( $product_id ); 
            }else{
                WC()->cart->add_to_cart( $product_id ); 
            }
        }else{ 
            $variation_data = json_decode(stripslashes($variation_data),true);
         
            $variations = array(); 
            for($i=0;$i<count($variation_data);$i++){
                $variations[str_replace("attribute_pa_", "", $variation_data[$i]['variant_name'])] = $variation_data[$i]['variant_value'];
            }
 
            if(!$cart_item_key){
                WC()->cart->add_to_cart($product_id, 1, $variation_id, $variations);
            }else{
                WC()->cart->add_to_cart($product_id, 1, $variation_id, $variations);
            } 
  
        }
       
        
    endforeach;
    
    die();
}



// uacf7_checkout_order_traking 
add_action( 'uacf7_checkout_order_traking', 'uacf7_checkout_order_traking', 10, 2 ); 
function uacf7_checkout_order_traking($uacf7_db_insert_id, $form_id) {
    $auto_cart = uacf7_get_form_option( $form_id, 'auto_cart' );
    $uacf7_enable_track_order = isset($auto_cart['uacf7_enable_track_order']) ? $auto_cart['uacf7_enable_track_order'] : false;
    $uacf7_enable_product_auto_cart = isset($auto_cart['uacf7_enable_product_auto_cart']) ? $auto_cart['uacf7_enable_product_auto_cart'] : false;
    
    if($uacf7_enable_product_auto_cart == true && $uacf7_enable_track_order == true){ 
         // set cookie for 24 hours
        setcookie( 'uacf7_traking_id', $uacf7_db_insert_id, time() + (86400 * 1), "/" );
    } 

    
}

add_action( 'woocommerce_thankyou', 'uacf7_checkout_woocommerce_order_review', 10 );
function uacf7_checkout_woocommerce_order_review($order_id){
    global $wpdb;
    // // get cookie
    $uacf7_traking_id = isset($_COOKIE['uacf7_traking_id']) ? $_COOKIE['uacf7_traking_id'] : 0;
    
    if(isset($uacf7_traking_id) && $uacf7_traking_id != 0){ 

        // set cookie for 24 hours 
        $form_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."uacf7_form WHERE id = %d", $uacf7_traking_id)); 
        $data = json_decode($form_data->form_value, true);
        $data['order_id'] = $order_id;  
        $wpdb->update( $wpdb->prefix.'uacf7_form', array( 'form_value' => json_encode($data) ), array( 'id' => $uacf7_traking_id ) );
        setcookie( 'uacf7_traking_id', false, time() + (86400 * 1), "/" );
        return true;
    }  
}


function uacf7_post_meta_options_auto_cart_and_checkout_after_submission( $value, $post_id){
  
	$auto_cart = apply_filters('uacf7_post_meta_options_auto_cart_and_checkout_after_submission_pro', $data = array(
			'title'  => __( 'WooCommerce Checkout', 'ultimate-addons-cf7' ),
			'icon'   => 'fa-solid fa-cart-plus',
            'checked_field'   => 'uacf7_enable_product_auto_cart',
			'fields' => array( 
                    'uacf7_range_slider_heading' => array(
                        'id'    => 'uacf7_range_slider_heading',
                        'type'  => 'heading', 
                        'label' => __( 'WooCommerce Checkout Settings', 'ultimate-addons-cf7' ),
                        'subtitle' => sprintf(
                            __( 'Connect your form with WooCommerce. The process: The user selects a product from the dropdown field, submits the form, and is then automatically redirected to the WooCommerce Cart page with the product added to their cart.  See Demo %1s.', 'ultimate-addons-cf7' ),
                             '<a href="https://cf7addons.com/preview/contact-form-7-woocommerce-checkout/" target="_blank" rel="noopener">Example</a>'
                                      )
                          ),
                    'woo_checkout_docs' => array(
                        'id'      => 'woo_checkout_docs',
                        'type'    => 'notice',
                        'style'   => 'success',
                        'content' => sprintf( 
                            __( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
                            '<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-woocommerce-checkout/" target="_blank" rel="noopener">WooCommerce Checkout</a>'
                        )
                    ),

                    'uacf7_enable_product_auto_cart' => array(
                        'id'        => 'uacf7_enable_product_auto_cart',
                        'type'      => 'switch',
                        'label'     => __( ' Enable Woo Checkout', 'ultimate-addons-cf7' ),
                        'subtitle'     => __( 'Please ensure WooCommerce is installed & activated.', 'ultimate-addons-cf7' ),
                        'label_on'  => __( 'Yes', 'ultimate-addons-cf7' ),
                        'label_off' => __( 'No', 'ultimate-addons-cf7' ),
                        'default'   => false
                    ),
                    'product_auto_cart_form_options_heading' => array(
                        'id'        => 'product_auto_cart_form_options_heading',
                        'type'      => 'heading',
                        'label'     => __( 'Product Auto Cart Field Option ', 'ultimate-addons-cf7' ),
                    ),
                    'uacf7_product_auto_cart_redirect_to' => array(
                        'id'        => 'uacf7_product_auto_cart_redirect_to',
                        'type'      => 'radio',
                        'label'     => __( ' Redirect to:', 'ultimate-addons-cf7' ),
                        'subtitle'     => __( 'User will be redirected to this page upon form submission.', 'ultimate-addons-cf7' ),
                        'options'   => array(
                            'cart' => 'Cart Page',
                            'checkout' => 'Checkout Page',
                        )
                    ),
                    'uacf7_enable_track_order' => array(
                        'id'        => 'uacf7_enable_track_order',
                        'type'      => 'switch',
                        'label'     => __( 'Track the order which has been placed using this method.', 'ultimate-addons-cf7' ),
                        'label_on'  => __( 'Yes', 'ultimate-addons-cf7' ),
                        'label_off' => __( 'No', 'ultimate-addons-cf7' ),
                        'default'   => false
                    ), 
 
			)
					

	), $post_id);

	$value['auto_cart'] = $auto_cart; 
	return $value;
} 
add_filter( 'uacf7_post_meta_options', 'uacf7_post_meta_options_auto_cart_and_checkout_after_submission', 25, 2 ); 