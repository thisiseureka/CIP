<?php

namespace BitApps\PiPro\src\Integrations\EasyDigitalDownload;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class EasyDigitalDownloadHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'purchaseProduct' => [
                'hook'          => 'edd_complete_purchase',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'handlePurchaseProduct'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'purchaseProductDiscount' => [
                'hook'          => 'edd_complete_purchase',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'handlePurchaseProductDiscountCode'],
                'priority'      => 999,
                'accepted_args' => 3,
            ],
            'orderRefunded' => [
                'hook'          => 'edds_payment_refunded',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'handleOrderRefunded'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'subscriptionRenew' => [
                'hook'          => 'edd_subscription_post_renew',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'handleSubscriptionRenew'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
            'licenseKeyCreate' => [
                'hook'          => 'edd_sl_store_license',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'handleLicenseStore'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'licenseActivate' => [
                'hook'          => 'edd_sl_activate_license',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'licenseActivate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'licenseDeactivate' => [
                'hook'          => 'edd_sl_deactivate_license',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'licenseDeactivate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'statusChangedToActivate' => [
                'hook'          => 'edd_sl_post_set_status',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'licenseStatusChangedToActivate'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'statusChangedToInactive' => [
                'hook'          => 'edd_sl_post_set_status',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'licenseStatusChangedToInactive'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'statusChangedToDisabled' => [
                'hook'          => 'edd_sl_post_set_status',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'licenseStatusChangedToDisabled'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'statusChangedToExpire' => [
                'hook'          => 'edd_sl_post_set_status',
                'callback'      => [EasyDigitalDownloadTrigger::class, 'licenseStatusChangedToExpire'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
