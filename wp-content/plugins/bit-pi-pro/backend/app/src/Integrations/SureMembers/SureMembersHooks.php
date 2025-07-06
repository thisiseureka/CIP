<?php

namespace BitApps\PiPro\src\Integrations\SureMembers;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SureMembersHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'accessGrant' => [
                'hook'          => 'suremembers_after_access_grant',
                'callback'      => [SureMembersTrigger::class, 'handleSureMembersAccessGrant'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'accessRevoke' => [
                'hook'          => 'suremembers_after_access_revoke',
                'callback'      => [SureMembersTrigger::class, 'handleSureMembersAccessRevoke'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'groupUpdated' => [
                'hook'          => 'suremembers_after_submit_form',
                'callback'      => [SureMembersTrigger::class, 'handleSureMembersGroupUpdated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
