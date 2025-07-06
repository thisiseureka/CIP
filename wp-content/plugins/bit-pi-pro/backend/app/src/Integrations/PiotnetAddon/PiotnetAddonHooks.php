<?php

namespace BitApps\PiPro\src\Integrations\PiotnetAddon;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PiotnetAddonHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'handlePiotnetFormSubmissionV2' => [
                'hook'     => 'pafe/form_builder/new_record_v2',
                'callback' => [PiotnetAddonTrigger::class, 'handlePiotnetSubmit'],
                'priority' => 10,
                'args'     => PHP_INT_MAX
            ]
        ];
    }
}
