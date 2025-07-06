<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Includes Addons file 
if(file_exists(UACF7_PRO_PATH . "admin/existing-addon-checked.php") && get_option('uacf7_existing_plugin_status') != 'done' && UACF7_PRO_VERSION >= '1.6.0'){ 
    require_once UACF7_PRO_PATH .  "admin/existing-addon-checked.php"; 
}

// Includes Addons file 
if(file_exists(UACF7_PRO_PATH . "addons/addons.php")){ 
    require_once UACF7_PRO_PATH .  "addons/addons.php"; 
}