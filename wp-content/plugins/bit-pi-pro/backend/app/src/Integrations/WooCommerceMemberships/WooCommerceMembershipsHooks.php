<?php

namespace BitApps\PiPro\src\Integrations\WooCommerceMemberships;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WooCommerceMembershipsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'membershipPlanAdded' => [
                'hook'          => 'wc_memberships_user_membership_saved',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanAdded'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'membershipPlanUpdated' => [
                'hook'          => 'wc_memberships_user_membership_saved',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanUpdated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'userMembershipDeleted' => [
                'hook'          => 'wc_memberships_user_membership_deleted',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'userMembershipDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'membershipUserRoleUpdated' => [
                'hook'          => 'wc_memberships_member_user_role_updated',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipUserRoleUpdated'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipNoteAdded' => [
                'hook'          => 'wc_memberships_new_user_membership_note',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipNoteAdded'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'userMembershipActivation' => [
                'hook'          => 'wc_memberships_user_membership_activated',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'userMembershipActivation'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'userMembershipPaused' => [
                'hook'          => 'wc_memberships_user_membership_paused',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'userMembershipPaused'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'userMembershipTransferred' => [
                'hook'          => 'wc_memberships_user_membership_transferred',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'userMembershipTransferred'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipPlanStatusCancelled' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanStatusCancelled'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipPlanStatusDelayed' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanStatusDelayed'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipPlanStatusComplimentary' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanStatusComplimentary'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipPlanStatusPendingCancellation' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanStatusPendingCancellation'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipPlanStatusPaused' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanStatusPaused'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipPlanStatusExpires' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipPlanStatusExpires'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipStatusChanged' => [
                'hook'          => 'wc_memberships_user_membership_status_changed',
                'callback'      => [WooCommerceMembershipsTrigger::class, 'membershipStatusChanged'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
