<?php

namespace BitApps\PiPro\src\Integrations\RestrictContent;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class RestrictContentHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'activateMembershipLevel' => [
                'hook'          => 'rcp_membership_post_activate',
                'callback'      => [RestrictContentTrigger::class, 'purchasesMembershipLevel'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'membershipCancelled' => [
                'hook'          => 'rcp_transition_membership_status_cancelled',
                'callback'      => [RestrictContentTrigger::class, 'membershipStatusCancelled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'membershipExpired' => [
                'hook'          => 'rcp_transition_membership_status_expired',
                'callback'      => [RestrictContentTrigger::class, 'membershipStatusExpired'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
