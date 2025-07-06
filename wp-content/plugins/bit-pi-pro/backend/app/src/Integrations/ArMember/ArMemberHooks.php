<?php

namespace BitApps\PiPro\src\Integrations\ArMember;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class ArMemberHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'adminMemberAdd' => [
                'hook'          => 'arm_after_add_new_user',
                'callback'      => [ArMemberTrigger::class, 'handleMemberAddByAdmin'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'updateUserByForm' => [
                'hook'          => 'arm_member_update_meta',
                'callback'      => [ArMemberTrigger::class, 'handleUpdateUserByForm'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'cancelSubscription' => [
                'hook'          => 'arm_cancel_subscription',
                'callback'      => [ArMemberTrigger::class, 'handleCancelSubscription'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'adminPlanChange' => [
                'hook'          => 'arm_after_user_plan_change_by_admin',
                'callback'      => [ArMemberTrigger::class, 'handlePlanChangeAdmin'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'newUserRegistration' => [
                'hook'          => 'arm_after_add_new_user',
                'callback'      => [ArMemberTrigger::class, 'handleRegisterForm'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            // 'arm_after_user_plan_renew' => [
            //     'hook'          => 'arm_after_user_plan_renew',
            //     'callback'      => [ArMemberTrigger::class, 'handleRenewSubscriptionPlan'],
            //     'priority'      => 10,
            //     'accepted_args' => 2,
            // ],
        ];
    }
}
