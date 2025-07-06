<?php

namespace BitApps\PiPro\src\Integrations\RestrictContent;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use RCP_Membership;

final class RestrictContentTrigger
{
    public static function pluginActive($option = null)
    {
        if (is_plugin_active('restrict-content-pro/restrict-content-pro.php')) {
            return $option === 'get_name' ? 'restrict-content-pro/restrict-content-pro.php' : true;
        }

        if (is_plugin_active('restrict-content/restrictcontent.php')) {
            return $option === 'get_name' ? 'restrict-content/restrictcontent.php' : true;
        }

        return false;
    }

    public static function getAllMembership()
    {
        if (!self::pluginActive()) {
            return Response::error(__('Restrict Content is not installed or activated', 'bit-pi'));
        }

        global $wpdb;

        $allLevels = $wpdb->get_results("select id,name from {$wpdb->prefix}restrict_content_pro");

        $organizelevels[] = [

            'value' => 'any',
            'label' => __('Any', 'bit-pi')
        ];

        foreach ($allLevels as $level) {
            $organizelevels[] = [
                'value' => $level->id,
                'label' => $level->name
            ];
        }

        return Response::success($organizelevels);
    }

    public static function purchasesMembershipLevel($membershipId, RCP_Membership $rCPMembership)
    {
        $flows = FlowService::exists('restrictContent', 'activateMembershipLevel');
        if (!$flows) {
            return;
        }

        $userId = $rCPMembership->get_user_id();

        if (!$userId) {
            return;
        }

        $levelId = $rCPMembership->get_object_id();

        $organizedData = [];

        if ($membershipId) {
            $membership = rcp_get_membership($membershipId);
            if ($membership !== false) {
                $organizedData = [
                    'membership_level'             => $membership->get_membership_level_name(),
                    'membership_payment'           => $membership->get_initial_amount(),
                    'membership_recurring_payment' => $membership->get_recurring_amount(),
                ];
            }
        }

        IntegrationHelper::handleFlowForForm($flows, $organizedData, $levelId, 'level-id');
    }

    public static function membershipStatusExpired($_oldStatus, $membershipId)
    {
        $flows = FlowService::exists('restrictContent', 'membershipExpired');
        if (!$flows) {
            return;
        }

        $membership = rcp_get_membership($membershipId);
        $membershipLevel = rcp_get_membership_level($membership->get_object_id());
        $levelId = (string) $membershipLevel->get_id();

        $organizedData = [];

        if ($membershipId) {
            $membership = rcp_get_membership($membershipId);

            if ($membership !== false) {
                $organizedData = [
                    'membership_level'             => $membership->get_membership_level_name(),
                    'membership_payment'           => $membership->get_initial_amount(),
                    'membership_recurring_payment' => $membership->get_recurring_amount(),
                ];
            }
        }

        IntegrationHelper::handleFlowForForm($flows, $organizedData, $levelId, 'level-id');
    }

    public static function membershipStatusCancelled($oldStatus, $membershipId)
    {
        $flows = FlowService::exists('restrictContent', 'membershipCancelled');
        if (!$flows) {
            return;
        }

        $organizedData = [];
        $membership = rcp_get_membership($membershipId);
        $membershipLevel = rcp_get_membership_level($membership->get_object_id());
        $levelId = $membershipLevel->get_id();

        if ($membershipId) {
            $membership = rcp_get_membership($membershipId);

            if ($membership !== false) {
                $organizedData = [
                    'membership_level'             => $membership->get_membership_level_name(),
                    'membership_payment'           => $membership->get_initial_amount(),
                    'membership_recurring_payment' => $membership->get_recurring_amount(),
                ];
            }
        }

        IntegrationHelper::handleFlowForForm($flows, $organizedData, $levelId, 'level-id');
    }

    // protected static function flowFilter($flows, $key, $value)
    // {
    //     $filteredFlows = [];
    //     foreach ($flows as $flow) {
    //         if (is_string($flow->flow_details)) {
    //             $flow->flow_details = json_decode($flow->flow_details);
    //         }
    //         if (!isset($flow->flow_details->$key) || $flow->flow_details->$key === 'any' || $flow->flow_details->$key == $value || $flow->flow_details->$key === '') {
    //             $filteredFlows[] = $flow;
    //         }
    //     }
    //     return $filteredFlows;
    // }
}
