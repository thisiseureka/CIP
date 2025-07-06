<?php

namespace BitApps\PiPro\src\Integrations\ArMember;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class ArMemberTrigger
{
    public static function pluginActive()
    {
        return class_exists('ARMember');
    }

    public static function handleRegisterForm($userId, $postData)
    {
        if (\array_key_exists('arm_form_id', $postData) === false) {
            return;
        }

        $formId = $postData['arm_form_id'];
        $flows = FlowService::exists('arMember', 'newUserRegistration');
        if (empty($flows)) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);
        $postData['user_id'] = $userId;
        $postData['nickname'] = $userInfo['nickname'];
        $postData['avatar_url'] = $userInfo['avatar_url'];
        if (!empty($formId) && $flows) {
            IntegrationHelper::handleFlowForForm($flows, $postData);
        }
    }

    public static function handleUpdateUserByForm($userID, $postedData)
    {
        if (\array_key_exists('form_random_key', $postedData) === false) {
            return;
        }

        $formId = str_starts_with($postedData['form_random_key'], '101');
        if (!$formId) {
            return;
        }

        $flows = FlowService::exists('arMember', 'updateUserByForm');
        if (empty($flows)) {
            return;
        }

        $userInfo = Utility::getUserInfo($userID);
        $postedData['user_id'] = $userID;
        $postedData['nickname'] = $userInfo['nickname'];
        $postedData['avatar_url'] = $userInfo['avatar_url'];
        IntegrationHelper::handleFlowForForm($flows, $postedData);
    }

    public static function handleMemberAddByAdmin($userId, $postData)
    {
        if (\array_key_exists('action', $postData) === false) {
            return;
        }

        $formId = $postData['form'];
        if (!$formId) {
            return;
        }

        $flows = FlowService::exists('arMember', 'adminMemberAdd');
        if (empty($flows)) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);
        $postData['user_id'] = $userId;
        $postData['nickname'] = $userInfo['nickname'];
        $postData['avatar_url'] = $userInfo['avatar_url'];
        if ($flows) {
            IntegrationHelper::handleFlowForForm($flows, $postData);
        }
    }

    public static function handleCancelSubscription($userId, $planId)
    {
        $flows = FlowService::exists('arMember', 'cancelSubscription');
        if (empty($flows)) {
            return;
        }

        $finalData = ArMemberHelper::userAndPlanData($userId, $planId);
        if ($flows) {
            IntegrationHelper::handleFlowForForm($flows, $finalData);
        }
    }

    public static function handlePlanChangeAdmin($userId, $planId)
    {
        $flows = FlowService::exists('arMember', 'adminPlanChange');
        if (empty($flows)) {
            return;
        }

        $finalData = ArMemberHelper::userAndPlanData($userId, $planId);
        if ($flows) {
            IntegrationHelper::handleFlowForForm($flows, $finalData);
        }
    }

    // TODO:: Uncomment this function if needed
    // public static function handleRenewSubscriptionPlan($userId, $planId)
    // {
    //     $flows = FlowService::exists('arMember', 'renewSubscriptionPlan');
    //     if (empty($flows)) {
    //         return;
    //     }
    //     $finalData = ArMemberHelper::userAndPlanData($userId, $planId);
    //     if ($flows) {
    //         IntegrationHelper::handleFlowForForm($flows, $finalData);
    //     }
    // }
}
