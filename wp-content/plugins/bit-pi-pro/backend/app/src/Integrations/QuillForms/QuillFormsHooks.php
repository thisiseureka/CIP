<?php

namespace BitApps\PiPro\src\Integrations\QuillForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class QuillFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmission' => [
                'hook'          => 'quillforms_after_entry_processed',
                'callback'      => [QuillFormsTrigger::class, 'handleFormSubmitted'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
