<?php

namespace BitApps\PiPro\src\Integrations\UltimateMember;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class UltimateMemberTrigger
{
    public static function pluginActive()
    {
        return class_exists('UM');
    }

    public function getLoginForms()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Ultimate Member'));
        }

        $loginForms = UltimateMemberHelper::getAllLoginAndRegistrationForm('login');

        $ultimateMemberAction = [];

        array_map(
            function ($type) {
                $ultimateMemberAction[] = (object) [
                    'value' => $type['id'],
                    'label' => $type['title'],
                ];
            },
            $loginForms
        );

        return Response::success($ultimateMemberAction);
    }

    public function getRegistrationForms()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Ultimate Member'));
        }

        $registrationForms = UltimateMemberHelper::getAllLoginAndRegistrationForm('register');

        $ultimateMemberAction = [];

        array_map(
            function ($type) {
                $ultimateMemberAction[] = (object) [
                    'value' => $type['value'],
                    'label' => $type['label'],
                ];
            },
            $registrationForms
        );

        return Response::success($ultimateMemberAction);
    }

    public static function handleUserLogViaForm($umArgs)
    {
        if (!isset($umArgs['form_id']) || !\function_exists('um_user')) {
            return;
        }

        $userId = um_user('ID');
        $flows = FlowService::exists('ultimateMember', 'userLogin');
        if (empty($flows)) {
            return;
        }

        $finalData = Utility::getUserInfo($userId);
        $finalData['username'] = $umArgs['username'];

        if ($finalData) {
            IntegrationHelper::handleFlowForForm($flows, $finalData);
        }
    }

    public static function handleUserRegisViaForm($userId, $umArgs)
    {
        $flows = FlowService::exists('ultimateMember', 'userRegistrationComplete');
        if (empty($flows)) {
            return;
        }

        if (!empty($umArgs['submitted'])) {
            IntegrationHelper::handleFlowForForm($flows, $umArgs['submitted']);
        }
    }

    public static function handleUserRoleChange($userId, $role)
    {
        $flows = FlowService::exists('ultimateMember', 'userRoleChange');

        if (empty($flows)) {
            return;
        }

        $finalData = Utility::getUserInfo($userId);

        $finalData['role'] = $role;

        IntegrationHelper::handleFlowForForm($flows, $finalData);
    }

    public static function getUMrole()
    {
        $roles = UltimateMemberHelper::getRoles();

        return Response::success($roles);
    }
}
