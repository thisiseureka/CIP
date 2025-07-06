<?php

namespace BitApps\PiPro\src\Integrations\EForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class EFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'submissionSuccess' => [
                'hook'          => 'ipt_fsqm_hook_save_success',
                'callback'      => [EFormTrigger::class, 'handleSubmission'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
