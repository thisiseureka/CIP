<?php

namespace BitApps\PiPro\src\Integrations\NinjaForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class NinjaFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'processNinjaForm' => [
                'hook'          => 'ninja_forms_after_submission',
                'callback'      => [NinjaFormTrigger::class, 'afterSubmission'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
