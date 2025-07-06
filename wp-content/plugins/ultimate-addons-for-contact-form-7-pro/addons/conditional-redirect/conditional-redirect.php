<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UACF7_Conditional_Redirect {
    
    public function __construct() {
        
        //Initialize
        add_action( 'init', array( $this, 'init' ), 100 ); 
        add_action( 'admin_init', array( $this, 'admin_init' ), 10 ); 
		
		add_filter( 'uacf7_post_meta_options_redirection_pro', array( $this, 'uacf7_post_meta_options_redirection_pro' ), 11, 2 );

		
		 
    }
    
    public function init() { 
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_cr_frontend_script' ) );  
		add_action('wp_ajax_uacf7_global_tag_ajax', array( $this, 'uacf7_global_tag_ajax_cb' ) ); 
		add_action('wp_ajax_nopriv_uacf7_global_tag_ajax', array( $this, 'uacf7_global_tag_ajax_cb' ) ); 
 
 
    }
    public function admin_init() {
		add_filter( 'uacf7_post_meta_options_redirection_pro', array( $this, 'uacf7_post_meta_options_redirection_pro' ), 11, 2 );
 
 
 
    }
 

    public function enqueue_cr_frontend_script() {
        wp_enqueue_script( 'uacf7_conditional_redirect', plugin_dir_url( __FILE__ ) . 'js/uacf7-cr-script.js', array('jquery'), null, true );
        wp_enqueue_script( 'uacf7-global-tag', plugin_dir_url( __FILE__ ) . 'js/global-tag-ajax.js', array('jquery'), null, false );
        
        wp_localize_script( 'uacf7_conditional_redirect', 'uacf7_cr_object', $this->uacf7_cr_get_forms() );
        wp_localize_script( 'uacf7_conditional_redirect', 'uacf7_redirect_type', $this->uacf7_redirect_type() );
        wp_localize_script( 'uacf7_conditional_redirect', 'uacf7_redirect_tag_support', $this->uacf7_redirect_tag_support_form() );
        wp_localize_script( 'uacf7_conditional_redirect', 'uacf7_global_tag',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);
    }

	public function uacf7_post_meta_options_redirection_pro($data, $post_id){  
		$uacf7_options = get_option( 'uacf7_settings' );
		$uacf7_enable_redirection_pro = isset($uacf7_options['uacf7_enable_redirection_pro']) ? $uacf7_options['uacf7_enable_redirection_pro'] : false;
		if(apply_filters('uacf7_checked_license_status', '') == false || $uacf7_enable_redirection_pro != true){
			return $data;
		}
 
		$data['fields']['uacf7_redirect_type']['is_pro'] = false ;
		$data['fields']['uacf7_redirect_tag_support']['is_pro'] = false ; 
		$data['fields']['conditional_redirect']['fields']['uacf7_cr_tn']['options'] = 'uacf7' ; 
		$data['fields']['conditional_redirect']['fields']['uacf7_cr_tn']['query_args'] = array(
			'post_id'      => $post_id, 
			'exclude'      => ['radio-426'], 
		) ; 
	 

		return $data; 
	}
 
 
    public function uacf7_redirect_type() {
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
                
                // $uacf7_redirect = get_post_meta( get_the_ID(), 'uacf7_redirect_type', true );
				$redirection = uacf7_get_form_option(get_the_ID(), 'redirection');
				$uacf7_redirect = isset($redirection['uacf7_redirect_type']) ? $redirection['uacf7_redirect_type'] : false;

                if( !empty($uacf7_redirect) && $uacf7_redirect == true ) {
                
                    $forms[ $post_id ] = $uacf7_redirect;
                
                }
        
    		endwhile;
    		wp_reset_postdata();
    	endif;
    
    	return $forms;
    }

    public function uacf7_redirect_tag_support_form() {
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
                
                // $uacf7_redirect_tag_support = get_post_meta( get_the_ID(), 'uacf7_redirect_tag_support', true );
				$redirection = uacf7_get_form_option(get_the_ID(), 'redirection');
				$uacf7_redirect_tag_support = isset($redirection['uacf7_redirect_tag_support']) ? $redirection['uacf7_redirect_tag_support'] : false; 
                if( !empty($uacf7_redirect_tag_support) && $uacf7_redirect_tag_support == true ) {
                
                    $forms[ $post_id ] = $uacf7_redirect_tag_support;
                
                }
        
    		endwhile;
    		wp_reset_postdata();
    	endif;
    
    	return $forms;
    }

    public function uacf7_cr_get_forms() {
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
                 
				$redirection = uacf7_get_form_option(get_the_ID(), 'redirection');   
				$uacf7_redirect = isset( $redirection['uacf7_redirect_enable']) ? $redirection['uacf7_redirect_enable'] : false;

                if( !empty($uacf7_redirect) && $uacf7_redirect == true ) {
                
                    $forms[ $post_id ] = isset( $redirection['conditional_redirect']) ? $redirection['conditional_redirect'] : array();
                
                }
                
        
    		endwhile;
    		wp_reset_postdata();
    	endif;
    
    	return $forms;
    }

	//Ajax - global tag
	public function uacf7_global_tag_ajax_cb(){

		$nameArr = $_POST["nameAarr"];
		$nameVal = $_POST["nameVal"];
		$redirect_url = $_POST["redirect_url"];

		$redirect_url = str_replace($nameArr, $nameVal, $redirect_url);      
		echo $redirect_url;
		die; 
	}

}

new UACF7_Conditional_Redirect();
