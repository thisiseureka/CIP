<?php

namespace BitApps\PiPro\src\Integrations\CartFlow;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class CartFlowHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'orderCreateWc' => [
                'hook'          => 'woocommerce_checkout_order_processed',
                'callback'      => [CartFlowTrigger::class, 'handleWcOrderCreate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
