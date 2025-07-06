<?php

namespace BitApps\PiPro\src\Integrations\PieForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class PieFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmissionHandled' => [
                'hook'          => 'pie_forms_complete_entry_save',
                'callback'      => [PieFormsTrigger::class, 'handleFormSubmitted'],
                'priority'      => 10,
                'accepted_args' => 5,
            ],
        ];
    }
}
