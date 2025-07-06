<?php

namespace BitApps\PiPro\src\Integrations\Forminator;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class ForminatorHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'          => 'forminator_custom_form_submit_before_set_fields',
                'callback'      => [ForminatorTrigger::class, 'handleForminatorSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
