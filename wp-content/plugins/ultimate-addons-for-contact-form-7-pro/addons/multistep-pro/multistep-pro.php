<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
* Init plugin
*/
add_action( 'init', 'uacf7_multistep_pro_init' );
function uacf7_multistep_pro_init(){
      
	add_action( 'wp_enqueue_scripts', 'uacf7_multistep_enqueue_scripts' );
	add_action( 'after_setup_theme', 'remove_uacf7_multistep_pro_features_demo', 0 );
	add_action( 'uacf7_multistep_pro_features', 'uacf7_multistep_pro_features', 10, 2 );
	add_filter( 'uacf7_multistep_save_pro_feature', 'uacf7_multistep_save_pro_feature', 2, 10 );
	add_filter( 'uacf7_multistep_progressbar_style', 'uacf7_progressbar_style', 10, 2 );
	add_action( 'uacf7_progressbar_image', 'uacf7_progressbar_image', 10, 2 );
	add_action( 'uacf7_multistep_before_form', 'uacf7_multistep_button_inline_styles', 10 );
	add_filter( 'uacf7_form_html', '__return_false' );
	add_filter( 'uacf7_progressbar_html', 'uacf7_progressbar_html_cb', 10, 4 );
}

 
 

//Enqueue scripts
function uacf7_multistep_enqueue_scripts() {
	wp_enqueue_style( 'uacf7-multistep-pro-style', plugin_dir_url( __FILE__ ) . 'assets/multistep-pro-styles.css', array('uacf7-multistep-style') );
	wp_enqueue_script( 'uacf7-multistep-scripts', plugin_dir_url( __FILE__ ) . 'assets/multistep-script.js', array('jquery'), null, true );

	$uacf7_enable_cdn_load_css = uacf7_settings( 'uacf7_enable_cdn_load_css' );

	if ( $uacf7_enable_cdn_load_css == true ) {

		wp_enqueue_style( 'uacf7-fontawesome-4', '//cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css', array(), tf_options_version() );
		wp_enqueue_style( 'uacf7-fontawesome-5', '//cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css', array(), tf_options_version() );
		wp_enqueue_style( 'uacf7-fontawesome-6', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css', array(), tf_options_version() );
		wp_enqueue_style( 'uacf7-remixicon', '//cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css', array(), tf_options_version() );

	} else {

		wp_enqueue_style( 'uacf7-fontawesome-4', UACF7_URL . 'assets/admin/libs/font-awesome/fontawesome4/css/font-awesome.min.css', array(), tf_options_version() );
		wp_enqueue_style( 'uacf7-fontawesome-5', UACF7_URL . 'assets/admin/libs/font-awesome/fontawesome5/css/all.min.css', array(), tf_options_version() );
		wp_enqueue_style( 'uacf7-fontawesome-6', UACF7_URL . 'assets/admin/libs/font-awesome/fontawesome6/css/all.min.css', array(), tf_options_version() );
		wp_enqueue_style( 'uacf7-remixicon', UACF7_URL . 'assets/admin/libs/remixicon/remixicon.css', array(), tf_options_version() );
	}

}

function tf_options_version() {
	return '1.0.0';
}

 
// 
function uacf7_post_meta_options_multistep_pro_callback($data, $post_id){
 
	$data['fields']['uacf7_enable_multistep_scroll']['is_pro'] = false ;
	$data['fields']['uacf7_multistep_next_prev_option']['is_pro'] = false ;
	$data['fields']['uacf7_multistep_button_border_radius']['is_pro'] = false ; 

	foreach($data['fields']['uacf7_progressbar_style']['options'] as $key => $value){
		if(isset($value['is_pro'])){
			$data['fields']['uacf7_progressbar_style']['options'][$key]['is_pro'] = false;
		}
	}
	
	if($post_id != 0){
		// Current Contact Form tags
		$form_current = WPCF7_ContactForm::get_instance($post_id);
                    
		$all_steps = $form_current->scan_form_tags( array('type'=>'uacf7_step_start') );

		$step_count = 1;
		foreach( $all_steps as $step ) { 
			
			$data['fields']['uacf7_multistep_step_'.$step_count.'']['is_pro'] = false;
			
			if($step_count == 1){ 
				$data['fields']['next_btn_'.$step->name.'']['is_pro'] = false;
			}else{
				if( count($all_steps) == $step_count ) {
					$data['fields']['prev_btn_'.$step->name.'']['is_pro'] = false;
				 }else{
					$data['fields']['next_btn_'.$step->name.'']['is_pro'] = false;
					$data['fields']['prev_btn_'.$step->name.'']['is_pro'] = false;
				 }
			}
			
			$data['fields']['uacf7_progressbar_image_'.$step->name.'']['is_pro'] = false;
			$data['fields']['uacf7_progressbar_icon_'.$step->name.'']['is_pro'] = false;
			$data['fields']['desc_title_'.$step->name.'']['is_pro'] = false;
			$data['fields']['step_desc_'.$step->name.'']['is_pro'] = false;
			$data['fields']['step_form_desc_'.$step->name.'']['is_pro'] = false;

			
			$step_count++;
		}
	}

	return $data; 
}
add_filter( 'uacf7_post_meta_options_multistep_pro', 'uacf7_post_meta_options_multistep_pro_callback', 11, 2);



//Removed pro demo fields
function remove_uacf7_multistep_pro_features_demo() {

    remove_action( 'uacf7_multistep_pro_features', 'uacf7_multistep_pro_features_demo', 5, 2 );
}

//Adding new styles
function uacf7_progressbar_style($option, $uacf7_progressbar_style){
    $option = '<option value="style-2"'. selected( $uacf7_progressbar_style, 'style-2', true ) .'>Style 2</option>';
    $option .= '<option value="style-3"'. selected( $uacf7_progressbar_style, 'style-3', true ) .'>Style 3</option>';
    $option .= '<option value="style-4"'. selected( $uacf7_progressbar_style, 'style-4', true ) .'>Style 4</option>';
    $option .= '<option value="style-5"'. selected( $uacf7_progressbar_style, 'style-5', true ) .'>Style 5</option>';
    $option .= '<option value="style-6"'. selected( $uacf7_progressbar_style, 'style-6', true ) .'>Style 6</option>';
    $option .= '<option value="style-7"'. selected( $uacf7_progressbar_style, 'style-7', true ) .'>Style 7</option>';
    $option .= '<option value="style-8"'. selected( $uacf7_progressbar_style, 'style-8', true ) .'>Style 8</option>';
    $option .= '<option value="style-9"'. selected( $uacf7_progressbar_style, 'style-9', true ) .'>Style 9</option>';
    $option .= '<option value="style-10"'. selected( $uacf7_progressbar_style, 'style-10', true ) .'>Style 9</option>';
    return $option;
}

//Added pro fields
function uacf7_multistep_pro_features( $all_steps, $form_id ){
	$meta = uacf7_get_form_option( $form_id, 'multistep' );
    $uacf7_multistep_button_bg = isset($meta['uacf7_multistep_button_bg']) ? $meta['uacf7_multistep_button_bg'] : '';
    $uacf7_multistep_button_color = isset($meta['uacf7_multistep_button_color']) ? $meta['uacf7_multistep_button_color'] : '';
    $uacf7_multistep_button_border_color = isset($meta['uacf7_multistep_button_border_color']) ? $meta['uacf7_multistep_button_border_color'] : '';
    $uacf7_multistep_button_hover_bg = isset($meta['uacf7_multistep_button_hover_bg']) ? $meta['uacf7_multistep_button_hover_bg'] : '';
    $uacf7_multistep_button_hover_color = isset($meta['uacf7_multistep_button_hover_color']) ? $meta['uacf7_multistep_button_hover_color'] : '';
    $uacf7_multistep_button_border_hover_color = isset($meta['uacf7_multistep_button_border_hover_color']) ? $meta['uacf7_multistep_button_border_hover_color'] : '';
    $uacf7_multistep_button_border_radius = isset($meta['uacf7_multistep_button_border_radius']) ? $meta['uacf7_multistep_button_border_radius'] : '';

   
	?>
	<div class="multistep_fields_row col-25">
		<h3>Next and Previous button style</h3>
		
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_bg"><p>Background Color</p></label>
			<input id="uacf7_multistep_button_bg" type="text" name="uacf7_multistep_button_bg" value="<?php echo esc_attr($uacf7_multistep_button_bg); ?>" class="uacf7-color-picker">
		</div>
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_color"><p>Font Color</p></label>
			<input id="uacf7_multistep_button_color" type="text" name="uacf7_multistep_button_color" value="<?php echo esc_attr($uacf7_multistep_button_color); ?>" class="uacf7-color-picker">
		</div>
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_border_color"><p>Border Color</p></label>
			<input id="uacf7_multistep_button_border_color" type="text" name="uacf7_multistep_button_border_color" value="<?php echo esc_attr($uacf7_multistep_button_border_color); ?>" class="uacf7-color-picker">
		</div>
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_hover_bg"><p>Hover Background Color</p></label>
			<input id="uacf7_multistep_button_hover_bg" type="text" name="uacf7_multistep_button_hover_bg" value="<?php echo esc_attr($uacf7_multistep_button_hover_bg); ?>" class="uacf7-color-picker">
		</div>
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_hover_color"><p>Hover Font Color</p></label>
			<input id="uacf7_multistep_button_hover_color" type="text" name="uacf7_multistep_button_hover_color" value="<?php echo esc_attr($uacf7_multistep_button_hover_color); ?>" class="uacf7-color-picker">
		</div>
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_border_color"><p>Hover Border Color</p></label>
			<input id="uacf7_multistep_button_border_hover_color" type="text" name="uacf7_multistep_button_border_hover_color" value="<?php echo esc_attr($uacf7_multistep_button_border_hover_color); ?>" class="uacf7-color-picker">
		</div>
		<div class="multistep_field_column">
			<label for="uacf7_multistep_button_border_radius"><p>Border Radius</p></label>
			<input id="uacf7_multistep_button_border_radius" type="number" name="uacf7_multistep_button_border_radius" value="<?php echo esc_attr($uacf7_multistep_button_border_radius); ?>">
		</div>

	</div>
	
	<?php
    if( empty(array_filter($all_steps)) ) return;
    ?>
    <div class="multistep_fields_row col-50" style="display: flex; flex-direction: column;">
    <?php
    $step_count = 1;
    foreach( $all_steps as $step ) { 
        ?>
        <h3><strong>Step <?php echo $step_count; ?></strong></h3>
        <?php
        if( $step_count == 1 ){
            ?>
            <div>
               <p><label for="<?php echo 'next_btn_'.$step->name; ?>">Change next button text for this Step</label></p>
               <input id="<?php echo 'next_btn_'.$step->name; ?>" type="text" name="<?php echo 'next_btn_'.$step->name; ?>" value="<?php echo esc_html( get_post_meta( $form_id, 'next_btn_'.$step->name, true ) ); ?>" placeholder="<?php echo esc_html__('Next','ultimate-addons-cf7-pro') ?>">
            </div>
            <?php
        } else {
            if( count($all_steps) == $step_count ) {
                ?>
                <div>
                   <p><label for="<?php echo 'prev_btn_'.$step->name; ?>">Change previous button text for this Step</label></p>
                   <input id="<?php echo 'prev_btn_'.$step->name; ?>" type="text" name="<?php echo 'prev_btn_'.$step->name; ?>" value="<?php echo esc_html( get_post_meta( $form_id, 'prev_btn_'.$step->name, true ) ); ?>" placeholder="<?php echo esc_html__('Previous','ultimate-addons-cf7-pro') ?>">
                </div>
                <?php
            } else {
                ?>
                <div class="multistep_fields_row-">
                    <div class="multistep_field_column">
                       <p><label for="<?php echo 'prev_btn_'.$step->name; ?>">Change previous button text for this Step</label></p>
                       <input id="<?php echo 'prev_btn_'.$step->name; ?>" type="text" name="<?php echo 'prev_btn_'.$step->name; ?>" value="<?php echo esc_html( get_post_meta( $form_id, 'prev_btn_'.$step->name, true ) ); ?>" placeholder="<?php echo esc_html__('Previous','ultimate-addons-cf7-pro') ?>">
                    </div>

                    <div class="multistep_field_column">
                       <p><label for="<?php echo 'next_btn_'.$step->name; ?>">Change next button text for this Step</label></p>
                       <input id="<?php echo 'next_btn_'.$step->name; ?>" type="text" name="<?php echo 'next_btn_'.$step->name; ?>" value="<?php echo esc_html( get_post_meta( $form_id, 'next_btn_'.$step->name, true ) ); ?>" placeholder="<?php echo esc_html__('Next','ultimate-addons-cf7-pro') ?>">
                    </div>
                </div>
                <?php
            }

        }
        ?>
        <div class="uacf7_multistep_progressbar_image_row">
           <p><label for="<?php echo esc_attr('uacf7_progressbar_image_'.$step->name); ?>">Add progressbar image for this step</label></p>
           <input class="uacf7_multistep_progressbar_image" id="<?php echo esc_attr('uacf7_progressbar_image_'.$step->name); ?>" type="url" name="<?php echo esc_attr('uacf7_progressbar_image_'.$step->name); ?>" value="<?php echo get_option('uacf7_progressbar_image_'.$step->name); ?>"> <a class="button-primary uacf7_multistep_image_upload" href="#">Add or Upload Image</a>
        </div>
        <div class="multistep_fields_row step-title-description col-50">
            <div class="multistep_field_column">
               <p><label for="<?php echo 'step_desc_'.$step->name; ?>">Step description</label></p>
               <textarea id="<?php echo 'step_desc_'.$step->name; ?>" type="text" name="<?php echo 'step_desc_'.$step->name; ?>" cols="40" rows="3" placeholder="<?php echo esc_html__('Step description','ultimate-addons-cf7-pro') ?>"><?php echo esc_html(get_post_meta($form_id, 'step_desc_'.$step->name, true)); ?></textarea>
            </div>

            <div class="multistep_field_column">
               <p><label for="<?php echo 'desc_title_'.$step->name; ?>">Description title</label></p>
               <input id="<?php echo 'desc_title_'.$step->name; ?>" type="text" name="<?php echo 'desc_title_'.$step->name; ?>" value="<?php echo esc_html(get_post_meta($form_id, 'desc_title_'.$step->name, true)); ?>" placeholder="<?php echo esc_html__('Description title','ultimate-addons-cf7-pro') ?>">
            </div>
        </div>
        <?php
        $step_count++;
    }
    ?>
    </div>
    <?php
}

function uacf7_multistep_button_inline_styles( $post_id ) {
	$meta = uacf7_get_form_option( $post_id, 'multistep' );
	
	$uacf7_multistep_next_prev_option = isset($meta['uacf7_multistep_next_prev_option']) ? $meta['uacf7_multistep_next_prev_option'] : '';
	$uacf7_multistep_button_bg = isset($uacf7_multistep_next_prev_option['uacf7_multistep_button_bg']) ? $uacf7_multistep_next_prev_option['uacf7_multistep_button_bg'] : '';
    $uacf7_multistep_button_color = isset($uacf7_multistep_next_prev_option['uacf7_multistep_button_color']) ? $uacf7_multistep_next_prev_option['uacf7_multistep_button_color'] : '';
    $uacf7_multistep_button_border_color = isset($uacf7_multistep_next_prev_option['uacf7_multistep_button_border_color']) ? $uacf7_multistep_next_prev_option['uacf7_multistep_button_border_color'] : '';
    $uacf7_multistep_button_hover_bg = isset($uacf7_multistep_next_prev_option['uacf7_multistep_button_hover_bg']) ? $uacf7_multistep_next_prev_option['uacf7_multistep_button_hover_bg'] : '';
    $uacf7_multistep_button_hover_color = isset($uacf7_multistep_next_prev_option['uacf7_multistep_button_hover_color']) ? $uacf7_multistep_next_prev_option['uacf7_multistep_button_hover_color'] : '';
    $uacf7_multistep_button_border_hover_color = isset($uacf7_multistep_next_prev_option['uacf7_multistep_button_border_hover_color']) ? $uacf7_multistep_next_prev_option['uacf7_multistep_button_border_hover_color'] : '';
    $uacf7_multistep_button_border_radius = isset($meta['uacf7_multistep_button_border_radius']) ? $meta['uacf7_multistep_button_border_radius'] : '';

	$uacf7_enable_multistep_scroll = isset($meta['uacf7_enable_multistep_scroll']) ? $meta['uacf7_enable_multistep_scroll'] : 0;

	
	wp_localize_script('uacf7-multistep', 'uacf7_multistep_scroll', array( 
		'scroll_top' => $uacf7_enable_multistep_scroll, 
	));
	?>
	<style>
		.uacf7-form-<?= $post_id; ?> .step-content .uacf7-next,
		.uacf7-form-<?= $post_id; ?> .step-content .uacf7-prev
		{
			background-color: <?php echo esc_attr($uacf7_multistep_button_bg); ?>;
			color: <?php echo esc_attr($uacf7_multistep_button_color); ?>;
			border-color: <?php echo esc_attr($uacf7_multistep_button_border_color); ?>;
			border-radius: <?php echo esc_attr($uacf7_multistep_button_border_radius); ?>px;
		}
		.uacf7-form-<?= $post_id; ?> .step-content .uacf7-next:hover,
		.uacf7-form-<?= $post_id; ?> .step-content .uacf7-prev:hover
		{
			background-color: <?php echo esc_attr($uacf7_multistep_button_hover_bg); ?>;
			color: <?php echo esc_attr($uacf7_multistep_button_hover_color); ?>;
			border-color: <?php echo esc_attr($uacf7_multistep_button_border_hover_color); ?>;
		}
	</style>
	<?php
}


/*$cust_num_post = get_option('cust_post_number');

for( $i=1; $i <= $cust_num_post; $i++ )
{

	cust_post_title($i);

}

function cust_post_title($number) {
?>
	<input type="text" name="cust_post_title_<?= $number; ?>"  style="width: 600px;" id="cust_post_title_<?= $number; ?>" value="<?php echo get_option('cust_post_title_'.$number.'') ?>">
<?php
}*/


function uacf7_multistep_save_pro_feature( $f, $form, $all_steps ){
    
    $step_titles = array();
    $step_names = array();
    foreach ($all_steps as $step) {
        $step_titles[] = (is_array($step->values) && !empty($step->values)) ? $step->values[0] : '';

        $step_names[] = !empty($step->name) ? $step->name : '';

        // update_option( 'prev_btn_'.$step->name, $_POST['prev_btn_'.$step->name] );
        // update_option( 'next_btn_'.$step->name, $_POST['next_btn_'.$step->name] );
        update_option( 'uacf7_progressbar_image_'.$step->name, $_POST['uacf7_progressbar_image_'.$step->name] );
        update_option( 'uacf7_progressbar_icon_'.$step->name, $_POST['uacf7_progressbar_icon_'.$step->name] );
        //update_option( 'step_desc_'.$step->name, $_POST['step_desc_'.$step->name] );
        //update_option( 'desc_title_'.$step->name, $_POST['desc_title_'.$step->name] );

		
		update_post_meta( $form->id(), 'prev_btn_'.$step->name, sanitize_text_field($_POST['prev_btn_'.$step->name]) );
		update_post_meta( $form->id(), 'next_btn_'.$step->name, sanitize_text_field($_POST['next_btn_'.$step->name]) );
        
        update_post_meta( $form->id(), 'step_desc_'.$step->name, sanitize_text_field($_POST['step_desc_'.$step->name]) );
        update_post_meta( $form->id(), 'step_desc_'.$step->name, sanitize_text_field($_POST['step_form_desc_'.$step->name]) );
        update_post_meta( $form->id(), 'desc_title_'.$step->name, sanitize_text_field($_POST['desc_title_'.$step->name]) );

    }
	

	update_post_meta( $form->id(), 'uacf7_progressbar_style', sanitize_text_field($_POST['uacf7_progressbar_style']) );

	update_post_meta( $form->id(), 'uacf7_enable_multistep_scroll', sanitize_text_field($_POST['uacf7_enable_multistep_scroll']) );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_bg', $_POST['uacf7_multistep_button_bg'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_color', $_POST['uacf7_multistep_button_color'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_border_color', $_POST['uacf7_multistep_button_border_color'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_hover_bg', $_POST['uacf7_multistep_button_hover_bg'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_hover_color', $_POST['uacf7_multistep_button_hover_color'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_border_hover_color', $_POST['uacf7_multistep_button_border_hover_color'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_button_border_radius', $_POST['uacf7_multistep_button_border_radius'] );
	
    update_post_meta( $form->id(), 'uacf7_multistep_steps', count($all_steps) );

    update_post_meta( $form->id(), 'uacf7_multistep_steps_title', $step_titles );

    update_post_meta( $form->id(), 'uacf7_multistep_steps_names', $step_names );

    update_post_meta( $form->id(), 'uacf7_enable_multistep_next_button', $_POST['uacf7_enable_multistep_next_button'] );

    update_post_meta( $form->id(), 'uacf7_multistep_previus_button', $_POST['uacf7_multistep_previus_button'] );
    
}

function uacf7_progressbar_image($step_id, $form_id) {
	$meta = uacf7_get_form_option( $form_id, 'multistep' ); 
    $uacf7_progressbar_image = isset($meta['uacf7_progressbar_image_'.$step_id]) ? $meta['uacf7_progressbar_image_'.$step_id] : '';
    $uacf7_progressbar_icon = isset($meta['uacf7_progressbar_icon_'.$step_id]) ? $meta['uacf7_progressbar_icon_'.$step_id] : '';
    $uacf7_progressbar_style = isset($meta['uacf7_progressbar_style']) ? $meta['uacf7_progressbar_style'] : '';

	$styles = ['style-7', 'style-8','style-9', 'style-10'];

	if(in_array($uacf7_progressbar_style, $styles)){
		if( $uacf7_progressbar_icon != '' ){
			echo '<i class="'.esc_attr( $uacf7_progressbar_icon ).'"></i>';
		}
	}else{
		if( $uacf7_progressbar_image != '' ){
			echo '<img src="'.esc_url( $uacf7_progressbar_image ).'">';
		}
	}

}

//Another progressbar
//add_filter( 'uacf7_progressbar_step', 'uacf7_progressbar_step_cb', 10, 3 );
function uacf7_progressbar_step_cb($step, $enable_label, $content, $form_id) {
	$meta = uacf7_get_form_option( $form_id, 'multistep' );
	$uacf7_progressbar_style = isset($meta['uacf7_progressbar_style']) ? $meta['uacf7_progressbar_style'] : '';
	
	if($uacf7_progressbar_style == 'style-1'){
		if( $enable_label != true ) {
			return $content;
		}else {
			return $step;
		}
	}
	
}

/*
* Progressbar HTML
*/
function uacf7_progressbar_html_cb( $progressbar, $form, $form_id, $steps ) {  
	ob_start();
	$meta = uacf7_get_form_option( $form_id, 'multistep' );
	$uacf7_progressbar_style = isset($meta['uacf7_progressbar_style']) ? $meta['uacf7_progressbar_style'] : '';
	$uacf7_multistep_progressbar_color_option = isset($meta['uacf7_multistep_progressbar_color_option']) ? $meta['uacf7_multistep_progressbar_color_option'] : ''; 
	
	$uacf7_multistep_progress_line_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_progress_line_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_progress_line_color'] : '';
	$uacf7_multistep_circle_active_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_circle_active_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_circle_active_color'] : '';
	$uacf7_multistep_progressbar_title_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_progressbar_title_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_progressbar_title_color'] : '';
    ?>
    <style>
    <?php if( !empty($uacf7_multistep_progress_line_color) ): ?>
        .progressbar-style-4 .steps-step.step-complete::after,
        .uacf7-multistep-row .steps-form .steps-row::before {
        	background-color: <?php echo esc_attr($uacf7_multistep_progress_line_color); ?>!important;
        }
    <?php endif; ?>
    <?php if(!empty($uacf7_multistep_circle_active_color)): ?>
        .uacf7-multistep-row .steps-form .steps-row .steps-step.step-complete .btn-circle,
        .progressbar-style-4 .steps-form .steps-row .steps-step.step-complete .btn-circle {
        	background-color: <?php echo esc_attr($uacf7_multistep_circle_active_color); ?>!important;
        }
    <?php endif; ?>
    <?php if(!empty($uacf7_multistep_progressbar_title_color)): ?>
    .uacf7-multistep-row .steps-form .steps-row .steps-step p,
	.uacf7-multistep-row .progressbar-style-7 .steps-form .steps-row .steps-step p,
	.uacf7-multistep-row .progressbar-style-10 .steps-form .steps-row .steps-step p,
	.uacf7-multistep-row .progressbar-style-7 .steps-form .steps-row .steps-step span,
	.uacf7-multistep-row .progressbar-style-10 .steps-form .steps-row .steps-step span {
        color: <?php echo esc_attr($uacf7_multistep_progressbar_title_color); ?>;
    }
    <?php endif; ?>
    <?php if($uacf7_progressbar_style == 'style-3'): ?>
    .progressbar-style-3 .step-complete .btn.btn-circle::after {
    	background-image: url('<?php echo plugin_dir_url( __FILE__ ); ?>assets/check.svg');
    }
    <?php endif; ?>
    </style>
	
	<?php 
	 if( $uacf7_progressbar_style == 'style-2' || $uacf7_progressbar_style == 'style-3' || $uacf7_progressbar_style == 'style-6' || $uacf7_progressbar_style == 'style-7' || $uacf7_progressbar_style == 'style-10' ) {
	
			if( $uacf7_progressbar_style == 'style-2' ){
				$progressbar_class = 'progressbar-style-2';
				
			}elseif( $uacf7_progressbar_style == 'style-3' ){
				$progressbar_class = 'progressbar-style-3';
				
			}elseif( $uacf7_progressbar_style == 'style-6' ){
				$progressbar_class = 'progressbar-style-6';

			}elseif( $uacf7_progressbar_style == 'style-7' ){
				$progressbar_class = 'progressbar-style-7';
			}elseif( $uacf7_progressbar_style == 'style-10' ){
				$progressbar_class = 'progressbar-style-10';
			}else {
				$progressbar_class = '';
			}
			$uacf7_multistep_circle_width = isset($meta['uacf7_multistep_circle_width']) ? $meta['uacf7_multistep_circle_width'] : ''; 
			$uacf7_multistep_circle_height =isset($meta['uacf7_multistep_circle_height']) ? $meta['uacf7_multistep_circle_height'] : ''; 

	?>
	
	<style>
	<?php if( !empty($uacf7_multistep_circle_width) ): ?>
	    .uacf7-multistep-row .steps-form .steps-row::before {
        	left: <?php echo (esc_attr($uacf7_multistep_circle_width) / 2) - 1; ?>px;
        }
        .uacf7-multistep-row .steps-form .steps-row .steps-step p {
        	margin-left: <?php echo esc_attr($uacf7_multistep_circle_width) + 10; ?>px;
        }
        .uacf7-multistep-row .progressbar-style-3 .steps-form .steps-row .steps-step p {
        	margin-left: 0;
        	margin-right: <?php echo esc_attr($uacf7_multistep_circle_width) + 10; ?>px;
        }
        .uacf7-multistep-row .progressbar-style-3 .steps-form .steps-row::before {
        	right: <?php echo (esc_attr($uacf7_multistep_circle_width) / 2) - 1; ?>px;
        	left: inherit;
        }
    <?php
    endif;
	
    $all_names = apply_filters('uacf7_multistep_steps_names', array(), $steps);

    $uacf7_multistep_progress_bg_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_progress_bg_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_progress_bg_color'] : '';
    if( !empty($uacf7_multistep_progress_bg_color) ):
    ?>
    .uacf7-multistep-progressbar-wraper {
    	background: <?php echo esc_attr($uacf7_multistep_progress_bg_color); ?>;
    }
	</style>

	<?php
	endif;
	
	$uacf7_multistep_step_title_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_step_title_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_step_title_color'] : '';
    ?>
    <style>
    <?php if( !empty($uacf7_multistep_step_title_color) ): ?>
        .uacf7-multistep-form-wraper .current-step-title .step-title {
        	color: <?php echo esc_attr($uacf7_multistep_step_title_color); ?>;
        }
    <?php endif; ?>
    <?php 
    $uacf7_multistep_progressbar_title_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_progressbar_title_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_progressbar_title_color'] : '';
    $uacf7_multistep_step_description_color = isset($uacf7_multistep_progressbar_color_option['uacf7_multistep_step_description_color']) ? $uacf7_multistep_progressbar_color_option['uacf7_multistep_step_description_color'] : '';
    if( $uacf7_progressbar_style == 'style-6' && !empty($uacf7_multistep_progressbar_title_color) ): ?>
    .current-step-title.progressbar-style-6-title .step-title h3 {
    	color: <?php echo esc_attr($uacf7_multistep_progressbar_title_color); ?> !important;
    }
    <?php endif; 
    if( $uacf7_progressbar_style == 'style-6' && !empty($uacf7_multistep_step_description_color) ): ?>
    .current-step-title.progressbar-style-6-title .step-title p {
    	color: <?php echo esc_attr($uacf7_multistep_step_description_color); ?>;
    }
    <?php endif; ?>
    </style>
	<?php
    if( $uacf7_progressbar_style == 'style-6' ){
       echo '<div class="current-step-title '.esc_attr($progressbar_class).'-title">';
	    
	    $step_num = 1;
	    foreach($all_names as $step_name) {
	        
	        echo '<div class="step-title step-'.$step_num.'" data-form-id="'.esc_attr($form_id).'">';
	        echo '<h3>'.esc_html($meta['desc_title_'.$step_name]).'</h3>';
	        echo '<p>'.esc_html($meta['step_desc_'.$step_name]).'</p>';
	        echo '</div>';
	        
	        $step_num++;
	    }
	    echo '</div>';
    }
    $uacf7_multistep_step_height = isset($meta['uacf7_multistep_step_height']) ? $meta['uacf7_multistep_step_height'] : '';
    $uacf7_enable_multistep_progressbar = isset($meta['uacf7_enable_multistep_progressbar']) ? $meta['uacf7_enable_multistep_progressbar'] : '';
    
    if( $uacf7_multistep_step_height == 'default' ){
        $equal_height = 'full-height';
    }else{
        $equal_height = '';
    }
    ?>
	<div class="uacf7-multistep-row">
	    <?php if( $uacf7_enable_multistep_progressbar == true ): ?>
		<div class="uacf7-multistep-progressbar-wraper <?php echo esc_attr($equal_height); ?> <?php echo esc_attr($progressbar_class); ?>">
			<?php echo $progressbar; ?>
		</div>
		<?php endif; ?>
		<div class="uacf7-multistep-form-wraper" style="<?php if( $uacf7_enable_multistep_progressbar != 'on' ){ echo 'width:100%;padding:0'; } ?>">
		    <?php
		    echo '<div class="current-step-title">';
		     $all_steps = apply_filters('uacf7_multistep_step_title', array(), $steps);
		    $step_count = 1;
				foreach($all_steps as $index => $all_step) {
					$step  = isset($all_step) && !empty($all_step) ? $all_step : 'Step '.$step_count.':'  ;
					echo '<div class="step-title step-'.$step_count.'" data-form-id="'.esc_attr($form_id).'">';
					if($uacf7_progressbar_style == 'style-7' || $uacf7_progressbar_style == 'style-10'){
						echo '<span> Step '.$step_count.'/'.count($all_steps).'</span>';
					}
					echo '<h3>'.$step.'</h3>';
					if($uacf7_progressbar_style == 'style-7' || $uacf7_progressbar_style == 'style-10'){
						echo '<p>'.esc_html($meta['step_form_desc_'.$all_names[$index]]).'</p>';
					}
					echo '</div>';
					$step_count++;
				}
		    echo '</div>';
		    ?>
		    
			<?php echo $form; ?>
		</div>
	</div>
	<?php
	}elseif( $uacf7_progressbar_style == 'style-4' ){
	    $uacf7_multistep_step_title_color = isset($meta['uacf7_multistep_step_title_color']) ? $meta['uacf7_multistep_step_title_color'] : '';
	    ?>
	    <style>
	    <?php if( !empty($uacf7_multistep_step_title_color) ): ?>
	        .uacf7-multistep-form-wraper .current-step-title .step-title {
            	color: <?php echo esc_attr($uacf7_multistep_step_title_color); ?>;
            }
        <?php endif; ?>
	    </style>
	    <div class="progressbar-style-4">
    		<?php echo $progressbar; ?>
    		<div class="uacf7-multistep-form-wraper">
    		    <?php 
    		    $all_steps = apply_filters('uacf7_multistep_step_title', array(), $steps);
    		    echo '<div class="current-step-title">';
    		    $step_count = 1;
    		    foreach($all_steps as $all_step) {
    		        echo '<h3 class="step-title step-'.$step_count.'" data-form-id="'.esc_attr($form_id).'">Step '.$step_count.': '.$all_step.'</h3>';
    		        $step_count++;
    		    }
    		    echo '</div>';
    		    ?>
    		    
    			<?php echo $form; ?>
    		</div>
    	</div>
	    <?php
	}elseif( $uacf7_progressbar_style == 'style-5' ){
	    $uacf7_multistep_progressbar_title_color = isset($meta['uacf7_multistep_progressbar_color_option']['uacf7_multistep_progressbar_title_color']) ? $meta['uacf7_multistep_progressbar_color_option']['uacf7_multistep_progressbar_title_color'] : '';
	    ?>
	    <style>
	    <?php if( !empty($uacf7_multistep_progressbar_title_color) ): ?>
	        .progressbar-style-5 .steps-form .steps-row .steps-step.step-complete p {
            	color: <?php echo esc_attr($uacf7_multistep_progressbar_title_color); ?>;
            }
        <?php endif; ?>
        <?php if( !empty($uacf7_multistep_circle_active_color) ): ?>
        .progressbar-style-5 .steps-form .steps-row .steps-step.step-complete {
        	border-bottom: 2px solid <?php echo esc_attr($uacf7_multistep_circle_active_color); ?>;
        }
        <?php endif; ?>
	    </style>
	    <div class="progressbar-style-5">
    		<?php echo $progressbar; ?>
    		<?php echo $form; ?>
    	</div>
	    <?php
	}elseif( $uacf7_progressbar_style == 'style-8' || $uacf7_progressbar_style == 'style-9' ){
		?>
		<div class="uacf7-multistep-wrapper">

			<?php echo $progressbar; ?>

			<div class="uacf7-multisetp-form">
				<?php echo $form; ?>
			</div>
		</div>
		<?php
	}else {
		echo $progressbar;
		?>
		<div class="uacf7-multisetp-form">
			<?php echo $form; ?>
		</div>
	<?php
	}
	
	return ob_get_clean();
	
}
 

