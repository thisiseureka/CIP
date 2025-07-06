<?php 

class PDf_Generator_Pro{
    
    public function __construct() {
        
		add_filter( 'uacf7_post_meta_options_pdf_generator_pro', array( $this, 'uacf7_post_meta_options_pdf_generator_pro' ), 11, 2 );
        add_filter('wpcf7_form_elements', array( $this, 'uacf7_form_elements' ), 11, 2);
        add_action('wp_enqueue_scripts', array( $this,'enqueue_cf7_download_script'));

    }

    public function enqueue_cf7_download_script(){

        wp_enqueue_script(
            'uacf7-pdf-download', UACF7_PRO_ADDONS . '/pdf-generator-pro/assets/js/download-pdf.js', array( 'jquery' ), true );
    
        wp_localize_script('uacf7-pdf-download', 'uacf7_pdf_download_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('uacf7-pdf-generator')
        ]);

    }

    public function uacf7_post_meta_options_pdf_generator_pro($data, $post_id){
        $uacf7_options = get_option( 'uacf7_settings' );
		$uacf7_enable_pdf_generator_pro = isset($uacf7_options['uacf7_enable_pdf_generator_field_pro']) ? $uacf7_options['uacf7_enable_pdf_generator_field_pro'] : false;
		if(apply_filters('uacf7_checked_license_status', '') == false || $uacf7_enable_pdf_generator_pro != true){
			return $data;
		}
 
		$data['fields']['uacf7_enable_pdf_form_download']['is_pro'] = false ;

		return $data; 
    }

    public function uacf7_form_elements( $form ) {
        
        $contact_form = WPCF7_ContactForm::get_current();
        $form_id = $contact_form->id(); 
        
        $pdf = uacf7_get_form_option( $form_id, 'pdf_generator' );
        $enable_pdf = isset( $pdf['uacf7_enable_pdf_generator'] ) ? $pdf['uacf7_enable_pdf_generator'] : 0;
        $enable_pdf_download = isset( $pdf['uacf7_enable_pdf_form_download'] ) ? $pdf['uacf7_enable_pdf_form_download'] : 0;
        
        if ( $enable_pdf == true ) {
            
            $form = preg_replace( '/<div class="uacf7-form-(\d+)(.*?)>/i', '<div class="uacf7-form-$1$2 pdf-download="' . esc_attr( $enable_pdf_download ) . '" >', $form );
        }
    
        return $form;
    }

}

new PDf_Generator_Pro();