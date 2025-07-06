<?php

namespace BitApps\PiPro\src\Integrations\JetEngine;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class JetEngineHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'postMetaData' => [
                'hook'          => 'updated_post_meta',
                'callback'      => [JetEngineTrigger::class, 'handlePostMetaUpdate'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
