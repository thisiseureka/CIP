<?php

namespace BitApps\PiPro\src\Integrations\ArForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class ArFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'arFormEntryExecute' => [
                'hook'          => ['arfliteentryexecute', 'arfentryexecute'],
                'callback'      => [ArFormTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
