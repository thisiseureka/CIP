<?php

namespace BitApps\PiPro\src\Integrations\FormCraft;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class FormCraftHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'          => 'formcraft_after_save',
                'callback'      => [FormCraftTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
