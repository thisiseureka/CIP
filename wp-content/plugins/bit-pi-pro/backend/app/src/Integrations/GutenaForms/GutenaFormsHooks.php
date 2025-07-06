<?php

namespace BitApps\PiPro\src\Integrations\GutenaForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class GutenaFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'     => 'gutena_forms_submitted_data',
                'callback' => [GutenaFormsTrigger::class, 'handleGutenaFormsSubmit'],
                'priority' => 10,
                'args'     => PHP_INT_MAX
            ]
        ];
    }
}
