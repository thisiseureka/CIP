<?php

namespace BitApps\PiPro\src\Integrations\HappyForm;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class HappyFormHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'submissionSuccess' => [
                'hook'          => 'happyforms_submission_success',
                'callback'      => [HappyFormTrigger::class, 'handleSubmmit'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
