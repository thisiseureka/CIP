<?php

namespace BitApps\PiPro\src\Integrations\FluentForms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class FluentFormsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'submissionInserted' => [
                'hook'          => 'fluentform/submission_inserted',
                'callback'      => [FluentFormsTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
