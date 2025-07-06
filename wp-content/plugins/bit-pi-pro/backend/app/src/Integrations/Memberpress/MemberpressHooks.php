<?php

namespace BitApps\PiPro\src\Integrations\Memberpress;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class MemberpressHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            MemberpressTasks::ONE_TIME_SUBSCRIPTION => [
                'hook'          => 'mepr-event-transaction-completed',
                'callback'      => [MemberpressTrigger::class, 'oneTimeMembershipSubscribe'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            MemberpressTasks::RECURRING_SUBSCRIPTION => [
                'hook'          => 'mepr-event-transaction-completed',
                'callback'      => [MemberpressTrigger::class, 'recurringMembershipSubscribe'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            MemberpressTasks::CANCEL_SUBSCRIPTION => [
                'hook'          => 'mepr_subscription_transition_status',
                'callback'      => [MemberpressTrigger::class, 'membershipSubscribeCancel'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            MemberpressTasks::SUBSCRIPTION_EXPIRED => [
                'hook'          => 'mepr-event-transaction-expired',
                'callback'      => [MemberpressTrigger::class, 'membershipSubscribeExpire'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            MemberpressTasks::PAUSE_SUBSCRIPTION => [
                'hook'          => 'mepr_subscription_transition_status',
                'callback'      => [MemberpressTrigger::class, 'membershipSubscribePaused'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
