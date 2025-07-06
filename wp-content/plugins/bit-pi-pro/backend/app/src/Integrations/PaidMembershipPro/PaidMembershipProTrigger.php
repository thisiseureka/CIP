<?php

namespace BitApps\PiPro\src\Integrations\PaidMembershipPro;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class PaidMembershipProTrigger
{
    public static function isPluginActive()
    {
        return (bool) (is_plugin_active('paid-memberships-pro/paid-memberships-pro.php'));
    }

    public static function afterChangeMembershipLevelByAdmin($levelId, $userId)
    {
        if ($levelId == 0) {
            return;
        }

        global $wpdb;
        $levels = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->pmpro_membership_levels} WHERE id = %d", $levelId));
        $userData = Utility::getUserInfo($userId);
        $finalData = array_merge($userData, (array) $levels[0]);
        $flows = FlowService::exists('paidMembershipPro', 'adminChangesMembershipLevel');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $levelId, 'pro-level-id');
    }

    public static function cancelMembershipLevel($levelId, $userId, $cancelLevel)
    {
        if (absint($levelId) !== 0) {
            return;
        }

        global $wpdb;
        $levels = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->pmpro_membership_levels} WHERE id = %d", $cancelLevel));
        $userData = Utility::getUserInfo($userId);
        $finalData = array_merge($userData, (array) $levels[0]);
        $flows = FlowService::exists('paidMembershipPro', 'cancelMembershipLevel');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $cancelLevel, 'pro-level-id');
    }

    public static function afterCheckout($userId, $morder)
    {
        $user = $morder->getUser();
        $membership = $morder->getMembershipLevel();
        $userId = $user->ID;
        $membershipId = $membership->id;

        global $wpdb;
        $levels = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->pmpro_membership_levels} WHERE id = %d", $membershipId));
        $userData = Utility::getUserInfo($userId);
        $finalData = array_merge($userData, (array) $levels[0]);
        $flows = FlowService::exists('paidMembershipPro', 'membershipLevelOnCheckout');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $membershipId, 'pro-level-id');
    }

    public static function expiryMembershipLevel($userId, $membershipId)
    {
        global $wpdb;
        $levels = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->pmpro_membership_levels} WHERE id = %d", $membershipId));
        $userData = Utility::getUserInfo($userId);
        $finalData = array_merge($userData, (array) $levels[0]);
        $flows = FlowService::exists('paidMembershipPro', 'membershipLevelExpiry');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $membershipId, 'pro-level-id');
    }

    public static function allMemberships()
    {
        global $wpdb;
        $levels = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->pmpro_membership_levels} ORDER BY id ASC"));
        $allLevels = [];
        if ($levels) {
            foreach ($levels as $level) {
                $allLevels[] = [
                    'value' => $level->id,
                    'label' => $level->name,
                ];
            }
        }

        return array_merge(
            $allLevels,
            [
                [
                    'value' => 'any',
                    'label' => __('Any Membership Level', 'bit-pi')
                ],
            ]
        );
    }

    public static function getAllPaidMembershipProLevel()
    {
        if (!self::isPluginActive()) {
            return Response::error(__('Paid Membership Pro plugin is not active', 'bit-pi'));
        }

        $getAllMembership = self::allMemberships();

        return Response::success($getAllMembership);
    }
}
