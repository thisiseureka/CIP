<?php

namespace BitApps\PiPro\src\Integrations\Bricks;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class BricksHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'bricksforgeFormSubmit' => [
                'hook'          => 'bricks/form/custom_action',
                'callback'      => [BricksTrigger::class, 'handleBricksSubmit'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
