<?php

namespace BitApps\PiPro\src\Integrations\Registration;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class RegistrationTrigger
{
    public static function userCreate()
    {
        $newUserData = \func_get_args()[1];

        $userCreateFlow = FlowService::exists('registration', 'newUserRegistration');

        if ($userCreateFlow) {
            IntegrationHelper::handleFlowForForm($userCreateFlow, $newUserData);
        }
    }

    public static function profileUpdate()
    {
        $userdata = \func_get_args()[2];

        $userUpdateFlow = FlowService::exists('registration', 'updateUserProfile');

        if ($userUpdateFlow) {
            IntegrationHelper::handleFlowForForm($userUpdateFlow, $userdata);
        }
    }

    public static function wpLogin($userId, $data)
    {
        $userLoginFlow = FlowService::exists('registration', 'userLogin');

        if ($userLoginFlow) {
            $user = [];

            if (isset($data->data)) {
                $user['user_id'] = $userId;
                $user['user_login'] = $data->data->user_login;
                $user['user_email'] = $data->data->user_email;
                $user['user_url'] = $data->data->user_url;
                $user['nickname'] = $data->data->user_nicename;
                $user['display_name'] = $data->data->display_name;
            }

            IntegrationHelper::handleFlowForForm($userLoginFlow, $user);
        }
    }

    public static function wpResetPassword($data)
    {
        $userResetPassFlow = FlowService::exists('registration', 'resetUserPassword');

        if ($userResetPassFlow) {
            $user = [];
            if (isset($data->data)) {
                $user['user_id'] = $data->data->ID;
                $user['user_login'] = $data->data->user_login;
                $user['user_email'] = $data->data->user_email;
                $user['user_url'] = $data->data->user_url;
                $user['nickname'] = $data->data->user_nicename;
                $user['display_name'] = $data->data->display_name;
            }

            IntegrationHelper::handleFlowForForm($userResetPassFlow, $user);
        }
    }

    public static function wpUserDeleted()
    {
        $data = \func_get_args()[2];

        $userDeleteFlow = FlowService::exists('registration', 'deleteUserAccount');

        if ($userDeleteFlow) {
            $user = [];
            if (isset($data->data)) {
                $user['user_id'] = $data->data->ID;
                $user['user_login'] = $data->data->user_login;
                $user['user_email'] = $data->data->user_email;
                $user['user_url'] = $data->data->user_url;
                $user['nickname'] = $data->data->user_nicename;
                $user['display_name'] = $data->data->display_name;
            }

            IntegrationHelper::handleFlowForForm($userDeleteFlow, $user);
        }
    }
}
