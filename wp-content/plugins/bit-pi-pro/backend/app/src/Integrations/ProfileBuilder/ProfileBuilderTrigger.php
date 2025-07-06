<?php

namespace BitApps\PiPro\src\Integrations\ProfileBuilder;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class ProfileBuilderTrigger
{
    public static function handleUserRegistration($request, $formName, $userId)
    {
        $formData = ['user_id' => $userId, 'form_name' => $formName, 'request' => $request];

        return static::execute($formData, 'userRegistration');
    }

    public static function handleUserProfileUpdate($request, $formName, $userId)
    {
        $formData = ['user_id' => $userId, 'form_name' => $formName, 'request' => $request];

        return static::execute($formData, 'userProfileUpdate');
    }

    public static function handleUserEmailConfirmation($userId, $password, $meta)
    {
        if (empty($userId) || empty($meta)) {
            return;
        }

        $formData = ['user_id' => $userId, 'password' => $password, 'meta' => $meta];

        return static::execute($formData, 'userEmailConfirmation');
    }

    public static function handleEmailSendByProfileBuilder($sent, $to, $subject, $message, $sendEmail, $context)
    {
        if (empty($to)) {
            return;
        }

        $formData = [
            'sent'          => $sent,
            'to'            => $to,
            'subject'       => $subject,
            'message'       => $message,
            'send_email'    => $sendEmail,
            'context'       => $context,
        ];

        return static::execute($formData, 'emailSendByProfileBuilder');
    }

    public static function handleUserApprovedByAdmin($userId)
    {
        if (empty($userId)) {
            return;
        }

        $formData = Utility::getUserInfo($userId);

        return static::execute($formData, 'userApprovedByAdmin');
    }

    public static function handleUserUnApprovedByAdmin($userId)
    {
        if (empty($userId)) {
            return;
        }

        $formData = Utility::getUserInfo($userId);

        return static::execute($formData, 'userUnApprovedByAdmin');
    }

    private static function execute($formData, $machineSlug)
    {
        $flows = FlowService::exists('ProfileBuilder', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return \defined('ProfileBuilder_PLUGIN_FILE');
    }
}
