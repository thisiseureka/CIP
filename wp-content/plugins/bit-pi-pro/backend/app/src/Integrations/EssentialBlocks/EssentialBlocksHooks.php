<?php

namespace BitApps\PiPro\src\Integrations\EssentialBlocks;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class EssentialBlocksHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'formSubmit' => [
                'hook'     => 'eb_form_submit_before_email',
                'callback' => [EssentialBlocksTrigger::class, 'essentialBlocksHandler'],
                'priority' => 10,
                'args'     => PHP_INT_MAX
            ]
        ];
    }
}
