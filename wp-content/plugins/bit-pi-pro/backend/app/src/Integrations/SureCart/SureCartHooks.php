<?php

namespace BitApps\PiPro\src\Integrations\SureCart;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SureCartHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'purchaseCreated' => [
                'hook'          => 'surecart/purchase_created',
                'callback'      => [SureCartTrigger::class, 'sureCartPurchaseProduct'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'purchaseRevoked' => [
                'hook'          => 'surecart/purchase_revoked',
                'callback'      => [SureCartTrigger::class, 'sureCartPurchaseRevoked'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'purchaseInvoked' => [
                'hook'          => 'surecart/purchase_invoked',
                'callback'      => [SureCartTrigger::class, 'sureCartPurchaseUnrevoked'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
