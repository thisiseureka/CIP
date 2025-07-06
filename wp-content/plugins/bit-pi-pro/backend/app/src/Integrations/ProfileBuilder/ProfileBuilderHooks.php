<?php

namespace BitApps\PiPro\src\Integrations\ProfileBuilder;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class ProfileBuilderHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'userRegistration' => [
                'hook'          => 'wppb_register_success',
                'callback'      => [ProfileBuilderTrigger::class, 'handleUserRegistration'],
                'priority'      => 20,
                'accepted_args' => 3,
            ],
            'userProfileUpdate' => [
                'hook'          => 'wppb_edit_profile_success',
                'callback'      => [ProfileBuilderTrigger::class, 'handleUserProfileUpdate'],
                'priority'      => 20,
                'accepted_args' => 3,
            ],
            'userEmailConfirmation' => [
                'hook'          => 'wppb_activate_user',
                'callback'      => [ProfileBuilderTrigger::class, 'handleUserEmailConfirmation'],
                'priority'      => 20,
                'accepted_args' => 3,
            ],
            'emailSendByProfileBuilder' => [
                'hook'          => 'wppb_after_sending_email',
                'callback'      => [ProfileBuilderTrigger::class, 'handleEmailSendByProfileBuilder'],
                'priority'      => 20,
                'accepted_args' => 6,
            ],
            'userApprovedByAdmin' => [
                'hook'          => 'wppb_after_user_approval',
                'callback'      => [ProfileBuilderTrigger::class, 'handleUserApprovedByAdmin'],
                'priority'      => 20,
                'accepted_args' => 1,
            ],
            'userUnApprovedByAdmin' => [
                'hook'          => 'wppb_after_user_unapproval',
                'callback'      => [ProfileBuilderTrigger::class, 'handleUserUnApprovedByAdmin'],
                'priority'      => 20,
                'accepted_args' => 1,
            ],
        ];
    }
}
