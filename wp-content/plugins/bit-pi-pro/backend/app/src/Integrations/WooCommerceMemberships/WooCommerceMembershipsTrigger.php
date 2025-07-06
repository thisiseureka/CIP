<?php

namespace BitApps\PiPro\src\Integrations\WooCommerceMemberships;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class WooCommerceMembershipsTrigger
{
    public static function membershipPlanAdded($membershipPlan, $data)
    {
        if (!empty($data['is_update'])) {
            return;
        }

        return self::handleMembershipPlanSaved($membershipPlan, $data, 'membershipPlanAdded');
    }

    public static function membershipPlanUpdated($membershipPlan, $data)
    {
        if (empty($data['is_update'])) {
            return;
        }

        return self::handleMembershipPlanSaved($membershipPlan, $data, 'membershipPlanUpdated');
    }

    public static function userMembershipDeleted($userMembership)
    {
        if (empty($userMembership->user_id)) {
            return;
        }

        $data = self::formatMemberShipData($userMembership->plan_id ?? '', $userMembership->user_id, $userMembership);

        return self::execute('userMembershipDeleted', $data);
    }

    public static function membershipUserRoleUpdated($user, $toRole, $fromRole)
    {
        if (empty($user->ID)) {
            return;
        }

        $data = ['user' => Utility::getUserInfo($user->ID), 'to_role' => $toRole, 'from_role' => $fromRole];

        return self::execute('membershipUserRoleUpdated', $data);
    }

    public static function membershipNoteAdded($args)
    {
        if (empty($args) || empty($args['user_membership_id']) || !\function_exists('wc_memberships_get_user_membership')) {
            return;
        }

        $userMembership = wc_memberships_get_user_membership($args['user_membership_id']);
        $membershipPlan = $userMembership->get_plan();

        $extra = ['membership_note' => $args['membership_note'], 'notify' => $args['notify']];
        $data = self::formatMemberShipData($membershipPlan->id ?? '', $userMembership->user_id, $membershipPlan, null, null, $extra);

        return self::execute('membershipNoteAdded', $data);
    }

    public static function userMembershipActivation($userMembership, $wasPaused, $previousStatus)
    {
        if (empty($userMembership) || empty($userMembership->user_id)) {
            return;
        }

        $membershipPlan = $userMembership->get_plan();
        $extra = ['was_paused' => $wasPaused, 'previous_status' => $previousStatus];
        $data = self::formatMemberShipData($membershipPlan->id ?? '', $userMembership->user_id, $membershipPlan, null, null, $extra);

        return self::execute('userMembershipActivation', $data);
    }

    public static function userMembershipPaused($userMembership)
    {
        if (empty($userMembership) || empty($userMembership->user_id)) {
            return;
        }

        $membershipPlan = $userMembership->get_plan();
        $data = self::formatMemberShipData($membershipPlan->id ?? '', $userMembership->user_id, $membershipPlan);

        return self::execute('userMembershipPaused', $data);
    }

    public static function userMembershipTransferred($userMembership, $newOwner, $previousOwner)
    {
        if (empty($userMembership) || empty($userMembership->user_id) || empty($newOwner) || empty($previousOwner)) {
            return;
        }

        $membershipPlan = $userMembership->get_plan();
        $extra = ['new_owner' => Utility::getUserInfo($newOwner->ID), 'previous_owner' => Utility::getUserInfo($previousOwner->ID)];
        $data = self::formatMemberShipData($membershipPlan->id ?? '', $userMembership->user_id, $membershipPlan, null, null, $extra);

        return self::execute('userMembershipTransferred', $data);
    }

    public static function membershipPlanStatusCancelled($userMembership, $oldStatus, $newStatus)
    {
        if ($newStatus !== 'cancelled') {
            return;
        }

        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipPlanStatusCancelled');
    }

    public static function membershipPlanStatusDelayed($userMembership, $oldStatus, $newStatus)
    {
        if ($newStatus !== 'delayed') {
            return;
        }

        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipPlanStatusDelayed');
    }

    public static function membershipPlanStatusComplimentary($userMembership, $oldStatus, $newStatus)
    {
        if ($newStatus !== 'complimentary') {
            return;
        }

        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipPlanStatusComplimentary');
    }

    public static function membershipPlanStatusPaused($userMembership, $oldStatus, $newStatus)
    {
        if ($newStatus !== 'paused') {
            return;
        }

        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipPlanStatusPaused');
    }

    public static function membershipPlanStatusPendingCancellation($userMembership, $oldStatus, $newStatus)
    {
        if ($newStatus !== 'pending') {
            return;
        }

        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipPlanStatusPendingCancellation');
    }

    public static function membershipPlanStatusExpires($userMembership, $oldStatus, $newStatus)
    {
        if ($newStatus !== 'expired') {
            return;
        }

        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipPlanStatusExpires');
    }

    public static function membershipStatusChanged($userMembership, $oldStatus, $newStatus)
    {
        return self::handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, 'membershipStatusChanged');
    }

    private static function handleMembershipPlanSaved($membershipPlan, $data, $machineSlug)
    {
        if (empty($data['user_id']) || empty($data['user_membership_id']) || !\function_exists('wc_memberships_get_user_membership')) {
            return;
        }

        if (empty($membershipPlan)) {
            $userMembership = wc_memberships_get_user_membership($data['user_membership_id']);
            $membershipPlan = $userMembership->get_plan();
        }
        $data = self::formatMemberShipData($membershipPlan->id ?? '', $data['user_id'], $membershipPlan);

        return self::execute($machineSlug, $data);
    }

    private static function handleMembershipStatusChanged($userMembership, $oldStatus, $newStatus, $machineSlug)
    {
        if (empty($userMembership) || !\function_exists('wc_memberships_get_user_membership')) {
            return;
        }

        $membershipPlan = $userMembership->get_plan() ?? wc_memberships_get_user_membership($userMembership);
        $userId = $userMembership->user_id ?? $membershipPlan->user_id ?? '';
        $planId = $userMembership->plan_id ?? $membershipPlan->plan_id ?? '';

        if (empty($userId)) {
            return;
        }

        $data = self::formatMemberShipData($planId, $userId, $membershipPlan, $oldStatus, $newStatus);

        return self::execute($machineSlug, $data);
    }

    private static function formatMemberShipData($planId, $userId, $membershipPlan, $oldStatus = null, $newStatus = null, $extra = [])
    {
        $data = [
            'plan_id'   => $planId,
            'plan_name' => $membershipPlan->name ?? $membershipPlan->plan->name ?? '',
            'plan_slug' => $membershipPlan->slug ?? $membershipPlan->plan->slug ?? '',
            'plan_type' => !empty($planId) ? get_post_meta($planId, '_access_method', true) : '',
        ];

        if (!empty($oldStatus) && !empty($newStatus)) {
            $data += ['old_status' => $oldStatus, 'new_status' => $newStatus];
        }

        return array_merge(['membership' => $data, 'user' => Utility::getUserInfo($userId)], $extra);
    }

    private static function execute($machineSlug, $data)
    {
        $flows = FlowService::exists('wooCommerceMemberships', $machineSlug);

        if (empty($flows)) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('WooCommerce') && class_exists('WC_Memberships_Loader');
    }
}
