<?php

namespace BitApps\PiPro\src\Integrations\PiotnetForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PiotnetFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'handlePiotnetForm' => [
                'hook'     => 'piotnetforms/form_builder/new_record',
                'callback' => [PiotnetFormsTrigger::class, 'handleSubmit'],
                'priority' => 10,
                'args'     => PHP_INT_MAX
            ]
        ];
    }
}
