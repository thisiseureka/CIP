<?php

namespace BitApps\PiPro\src\Integrations\UltimateMember;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class UltimateMemberHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'userLogin' => [
                'hook'          => 'um_user_login',
                'callback'      => [UltimateMemberTrigger::class, 'handleUserLogViaForm'],
                'priority'      => 9,
                'accepted_args' => 1,
            ],
            'userRegistrationComplete' => [
                'hook'          => 'um_registration_complete',
                'callback'      => [UltimateMemberTrigger::class, 'handleUserRegisViaForm'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'userRoleChange' => [
                'hook'          => 'set_user_role',
                'callback'      => [UltimateMemberTrigger::class, 'handleUserRoleChange'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
