<?php

namespace BitApps\PiPro\src\Integrations\GiveWp;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class GiveWpHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'userDonation' => [
                'hook'          => 'give_update_payment_status',
                'callback'      => [GiveWpTrigger::class, 'handleUserDonation'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
