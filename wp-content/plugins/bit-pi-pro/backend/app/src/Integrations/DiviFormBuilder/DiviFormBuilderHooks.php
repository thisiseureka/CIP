<?php

namespace BitApps\PiPro\src\Integrations\DiviFormBuilder;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class DiviFormBuilderHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formBuilderSubmit' => [
                'hook'     => 'df_after_process',
                'callback' => [DiviFormBuilderTrigger::class, 'handleDiviFormBuilderSubmit'],
                'priority' => 10,
                'args'     => PHP_INT_MAX
            ]
        ];
    }
}
