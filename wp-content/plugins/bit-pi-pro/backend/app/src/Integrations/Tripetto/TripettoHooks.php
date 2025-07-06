<?php

namespace BitApps\PiPro\src\Integrations\Tripetto;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class TripettoHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'tripettoFormSubmission' => [
                'hook'          => 'tripetto_submit',
                'callback'      => [TripettoTrigger::class, 'handleTripettoSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
