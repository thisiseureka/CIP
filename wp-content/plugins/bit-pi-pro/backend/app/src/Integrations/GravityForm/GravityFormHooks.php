<?php

namespace BitApps\PiPro\src\Integrations\GravityForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class GravityFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmission' => [
                'hook'          => 'gform_after_submission',
                'callback'      => [GravityFormTrigger::class, 'gformAfterSubmission'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
