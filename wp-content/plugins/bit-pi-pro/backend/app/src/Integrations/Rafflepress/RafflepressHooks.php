<?php

namespace BitApps\PiPro\src\Integrations\Rafflepress;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class RafflepressHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'newRaffleEntry' => [
                'hook'     => 'rafflepress_giveaway_webhooks',
                'callback' => [RafflepressTrigger::class, 'handleNewPersonEntry'],
                'priority' => 10,
                'args'     => 1,
            ],
        ];
    }
}
