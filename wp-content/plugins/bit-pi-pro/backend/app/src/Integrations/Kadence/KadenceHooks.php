<?php

namespace BitApps\PiPro\src\Integrations\Kadence;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class KadenceHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmission' => [
                'hook'          => ['kadence_blocks_form_submission', 'kadence_blocks_advanced_form_submission'],
                'callback'      => [KadenceTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
