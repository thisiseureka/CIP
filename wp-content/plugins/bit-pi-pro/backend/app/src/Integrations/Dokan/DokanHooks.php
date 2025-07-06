<?php

namespace BitApps\PiPro\src\Integrations\Dokan;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class DokanHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'vendorAdd' => [
                'hook'          => 'dokan_before_create_vendor',
                'callback'      => [DokanTrigger::class, 'handleVendorAdd'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'vendorUpdate' => [
                'hook'          => 'dokan_before_update_vendor',
                'callback'      => [DokanTrigger::class, 'handleVendorUpdate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'vendorDelete' => [
                'hook'          => 'delete_user',
                'callback'      => [DokanTrigger::class, 'handleVendorDelete'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'refundRequest' => [
                'hook'          => 'dokan_refund_request_created',
                'callback'      => [DokanTrigger::class, 'dokanRefundRequest'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'refundApproved' => [
                'hook'          => 'dokan_pro_refund_approved',
                'callback'      => [DokanTrigger::class, 'dokanRefundApproved'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'refundCancelled' => [
                'hook'          => 'dokan_pro_refund_cancelled',
                'callback'      => [DokanTrigger::class, 'dokanRefundCancelled'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'newSellerToVendor' => [
                'hook'          => 'dokan_new_seller_created',
                'callback'      => [DokanTrigger::class, 'dokanUserToVendor'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'withdrawRequest' => [
                'hook'          => 'dokan_after_withdraw_request',
                'callback'      => [DokanTrigger::class, 'dokanWithdrawRequest'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
