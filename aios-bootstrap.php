<?php
/**	
 * @version 1.0.2
 * WARNING: Please do not delete this file.
 * 
 * This will cause PHP to throw a fatal error and render your site unusable.
 * 
 * To safely delete this file, please check both your .user.ini file and your php.ini file and ensure this file is not set in the auto_prepend_file directive.
 * 
 * Please ask your web hosting provider if you need guidance with executing the aforementioned steps.
 */
// Previously set auto_prepend_file
if (file_exists('/home/brela/public_html/dev93.xyz/cip/wordfence-waf.php')) {
	include_once('/home/brela/public_html/dev93.xyz/cip/wordfence-waf.php');
}
$GLOBALS['aiowps_firewall_rules_path'] = __DIR__.'/wp-content/uploads/aios/firewall-rules/';

$GLOBALS['aiowps_firewall_data'] = array(
	'ABSPATH' => '/home/brela/public_html/dev93.xyz/cip/',
);

// Begin AIOWPSEC Firewall
if (file_exists(__DIR__.'/wp-content/plugins/all-in-one-wp-security-and-firewall/classes/firewall/wp-security-firewall.php')) {
	include_once(__DIR__.'/wp-content/plugins/all-in-one-wp-security-and-firewall/classes/firewall/wp-security-firewall.php');
}
// End AIOWPSEC Firewall
