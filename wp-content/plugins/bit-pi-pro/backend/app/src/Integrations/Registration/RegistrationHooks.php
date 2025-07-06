<?php

namespace BitApps\PiPro\src\Integrations\Registration;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class RegistrationHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'newUserRegistration' => [
                'hook'          => 'user_register',
                'callback'      => [RegistrationTrigger::class, 'userCreate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'updateUserProfile' => [
                'hook'          => 'profile_update',
                'callback'      => [RegistrationTrigger::class, 'profileUpdate'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'userLogin' => [
                'hook'          => 'wp_login',
                'callback'      => [RegistrationTrigger::class, 'wpLogin'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'resetUserPassword' => [
                'hook'          => 'password_reset',
                'callback'      => [RegistrationTrigger::class, 'wpResetPassword'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'deleteUserAccount' => [
                'hook'          => 'delete_user',
                'callback'      => [RegistrationTrigger::class, 'wpUserDeleted'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
