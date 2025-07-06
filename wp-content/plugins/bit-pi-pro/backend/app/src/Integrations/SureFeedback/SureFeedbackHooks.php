<?php

namespace BitApps\PiPro\src\Integrations\SureFeedback;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SureFeedbackHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'insertComment' => [
                'hook'          => 'rest_insert_comment',
                'callback'      => [SureFeedbackTrigger::class, 'handleInsertComment'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'commentResolved' => [
                'hook'          => 'ph_website_pre_rest_update_thread_attribute',
                'callback'      => [SureFeedbackTrigger::class, 'handleCommentResolved'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
