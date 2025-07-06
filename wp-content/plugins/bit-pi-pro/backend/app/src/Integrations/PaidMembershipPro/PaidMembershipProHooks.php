<?php

namespace BitApps\PiPro\src\Integrations\PaidMembershipPro;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PaidMembershipProHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'adminChangesMembershipLevel' => [
                'hook'          => 'pmpro_after_change_membership_level',
                'callback'      => [PaidMembershipProTrigger::class, 'afterChangeMembershipLevelByAdmin'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'cancelMembershipLevel' => [
                'hook'          => 'pmpro_after_change_membership_level',
                'callback'      => [PaidMembershipProTrigger::class, 'cancelMembershipLevel'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'membershipLevelOnCheckout' => [
                'hook'          => 'pmpro_after_checkout',
                'callback'      => [PaidMembershipProTrigger::class, 'afterCheckout'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'membershipLevelExpiry' => [
                'hook'          => 'pmpro_membership_post_membership_expiry',
                'callback'      => [PaidMembershipProTrigger::class, 'expiryMembershipLevel'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
