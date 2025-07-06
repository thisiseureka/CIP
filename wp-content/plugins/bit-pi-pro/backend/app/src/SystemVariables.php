<?php

namespace BitApps\PiPro\src;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\Flow\GlobalFlow;

class SystemVariables
{
    public static function getSystemVariableValue($key)
    {
        $currentUser = wp_get_current_user();

        switch ($key) {
            case 'user_name':
                return $currentUser->data->user_login ?? '';
            case 'user_email':
                return $currentUser->data->user_email ?? '';
            case 'user_first_name':
                return $currentUser->data->first_name ?? '';
            case 'user_last_name':
                return $currentUser->data->last_name ?? '';
            case 'user_display_name':
                return $currentUser->data->display_name ?? '';
            case 'user_nicename':
                return $currentUser->data->user_nicename ?? '';
            case 'user_id':
                return $currentUser->data->ID ?? '';
            case 'php_version':
                return PHP_VERSION;
            case 'flow_id':
                global $globalFlowId;

                return $globalFlowId ?? '';
            case 'flow_name':
                return GlobalFlow::getFlowFieldValue('title');
            case 'flow_status':
                return GlobalFlow::getFlowFieldValue('is_active');
            case 'wp_version':
                return get_bloginfo('version');
            case 'server_name':
                return isset($_SERVER['SERVER_NAME']) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_NAME'])) : '';
            case 'server_addr':
                return isset($_SERVER['SERVER_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['SERVER_ADDR'])) : '';
            case 'server_protocol':
                return isset($_SERVER['SERVER_PROTOCOL']) ? sanitize_text_field($_SERVER['SERVER_PROTOCOL']) : '';
            case 'site_url':
                return get_bloginfo('url');
            case 'site_name':
                return get_bloginfo('name');
            case 'site_description':
                return get_bloginfo('description');
            case 'site_admin_email':
                return get_bloginfo('admin_email');
            case 'site_language':
                return get_bloginfo('language');
            case 'uuid':
                return uniqid();
            case 'date':
                return gmdate('Y-m-d');
            case 'timestamp':
                return time();
            case 'time':
                return gmdate('H:i:s');
            case 'day_of_week':
                return gmdate('l');
            case 'day_of_month':
                return gmdate('j');
            case 'total_post_count':
                return wp_count_posts()->publish;
            case 'pi':
                return M_PI;
            case 'e':
                return M_E;
            case 'random':
                return mt_rand() / mt_getrandmax();
            default:
        }
    }
}
