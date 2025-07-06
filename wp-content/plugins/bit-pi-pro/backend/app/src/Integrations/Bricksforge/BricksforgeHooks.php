<?php

namespace BitApps\PiPro\src\Integrations\Bricksforge;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class BricksforgeHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'bricksforgeFormSubmit' => [
                'hook'     => 'bricksforge/pro_forms/after_submit',
                'callback' => [BricksforgeTrigger::class, 'handleBricksforgeSubmit'],
                'priority' => 10,
                'args'     => PHP_INT_MAX,
            ],
        ];
    }
}
