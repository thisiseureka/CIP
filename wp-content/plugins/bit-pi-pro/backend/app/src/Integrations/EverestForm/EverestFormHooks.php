<?php

namespace BitApps\PiPro\src\Integrations\EverestForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class EverestFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'submissionSave' => [
                'hook'          => 'everest_forms_complete_entry_save',
                'callback'      => [EverestFormTrigger::class, 'handleSubmission'],
                'priority'      => 10,
                'accepted_args' => 5,
            ],
        ];
    }
}
