<?php

namespace BitApps\PiPro\src\Integrations\PiotnetAddonForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PiotnetAddonFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'handleAddonFormSubmitV2' => [
                'hook'     => 'pafe/form_builder/new_record_v2',
                'callback' => [PiotnetAddonFormTrigger::class, 'handlePiotnetSubmit'],
                'priority' => 10,
                'args'     => PHP_INT_MAX
            ]
        ];
    }
}
