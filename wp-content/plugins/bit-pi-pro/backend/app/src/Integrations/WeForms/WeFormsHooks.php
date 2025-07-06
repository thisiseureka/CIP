<?php

namespace BitApps\PiPro\src\Integrations\WeForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WeFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'weFormsSubmit' => [
                'hook'          => 'weforms_entry_submission',
                'callback'      => [WeFormsTrigger::class, 'handleWeFormsSubmit'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
