<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Init post submission
*/
add_action( 'init', 'uacf7_post_submission_init' );
function uacf7_post_submission_init(){
    
    //Register text domain
    load_plugin_textdomain( 'ultimate-post-submission', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
     
    //Require functions file
    require_once( 'inc/functions.php' );
    
    //Admin scripts
    add_action( 'admin_enqueue_scripts', 'admin_enqueue_scripts' );
        

    //Load post submission
    uacf7_post_submission_load();  
    
}

/*
* Admin enqueue scripts
*/
function admin_enqueue_scripts() {

    wp_enqueue_style( 'uacf7-post-submission-admin', plugin_dir_url( __FILE__ ) . 'assets/admin-style.css' );
}

/*
* Loaded post submission
*/
function uacf7_post_submission_load() { 
            
    require_once( 'inc/post-submission.php' ); 
         
}
 
 

function uacf7_post_meta_options_post_submission( $value, $post_id){
  
    $post_submission = apply_filters('uacf7_post_meta_options_post_submission_pro', $data = array(
        'title'  => __( 'Post Submission', 'ultimate-addons-cf7' ),
        'icon'   => 'fa-solid fa-arrow-up-from-water-pump',
        'checked_field'   => 'enable_post_submission',
        'fields' => array( 
            'uacf7_post_submission_label' => array(
                'id'    => 'uacf7_post_submission_label',
                'type'  => 'heading', 
                'label' => __( 'Post Submission', 'ultimate-addons-cf7' ),
                'subtitle' => sprintf(
                    __( 'Automatically publish submitted forms as new posts and display them on the front end, with custom field support.  See Demo %1s.', 'ultimate-addons-cf7' ),
                     '<a href="https://cf7addons.com/preview/contact-form-7-to-post-type/" target="_blank" rel="noopener">Example</a>'
                              )
                  ),
            'post_submission_docs' => array(
                'id'      => 'post_submission_docs',
                'type'    => 'notice',
                'style'   => 'success',
                'content' => sprintf( 
                    __( 'Confused? Check our Documentation on  %1s.', 'ultimate-addons-cf7' ),
                    '<a href="https://themefic.com/docs/uacf7/pro-addons/contact-form-7-to-post-type/" target="_blank" rel="noopener">Frontend Post Submission</a>'
                )
            ),
            'enable_post_submission' => array(
                'id'        => 'enable_post_submission',
                'type'      => 'switch',
                'label'     => __( ' Enable Post Submission', 'ultimate-addons-cf7' ),
                'label_on'  => __( 'Yes', 'ultimate-addons-cf7' ),
                'label_off' => __( 'No', 'ultimate-addons-cf7' ),
                'default'   => false,
                'field_width' => 100,
            ),
            'post_submission_form_options_heading' => array(
                'id'        => 'post_submission_form_options_heading',
                'type'      => 'heading',
                'label'     => __( 'Post Submission Option ', 'ultimate-addons-cf7' ),
            ),
            'enable_guest_post' => array(
                'id'        => 'enable_guest_post',
                'type'      => 'switch',
                'label'     => __( ' Enable Guest Post Submission', 'ultimate-addons-cf7' ),
                'label_on'  => __( 'Yes', 'ultimate-addons-cf7' ),
                'label_off' => __( 'No', 'ultimate-addons-cf7' ),
                'default'   => true,
                'field_width' => 100,
            ),
            'post_publish_under' => array(
                'id'        => 'post_publish_under',
                'type'      => 'select',
                'label'     => __( ' Post Publish Under ', 'ultimate-addons-cf7' ),
                'label_off' => __( 'No', 'ultimate-addons-cf7' ),
                'options'   => array(
                    'admin' => 'Admin',
                    'current_user' => 'Current User',
                ),
                'field_width' => 50,
            ),
            'post_submission_post_type' => array(
                'id'        => 'post_submission_post_type',
                'type'      => 'select',
                'label'     => __( ' Select Post Type ', 'ultimate-addons-cf7' ),
                'options'   => 'post_types',
                'field_width' => 50,
             
            ),
            'post_submission_post_status' => array(
                'id'        => 'post_submission_post_status',
                'type'      => 'select',
                'label'     => __( 'Post Status ', 'ultimate-addons-cf7' ),
                'options'   => array(
                    'publish' => 'Publish',
                    'draft' => 'Draft',
                    'pending' => 'Pending'
                ),
                'field_width' => 50,
            ), 
        ),
            

    ), $post_id);

    $value['post_submission'] = $post_submission; 
    return $value;
}  
add_filter( 'uacf7_post_meta_options', 'uacf7_post_meta_options_post_submission', 16, 2 );  