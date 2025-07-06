<?php

namespace BitApps\PiPro\src\Integrations\MetaBox;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class MetaBoxHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'saveMetaBoxData' => [
                'hook'          => 'rwmb_frontend_after_save_post',
                'callback'      => [MetaBoxTrigger::class, 'handleSubmit'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
