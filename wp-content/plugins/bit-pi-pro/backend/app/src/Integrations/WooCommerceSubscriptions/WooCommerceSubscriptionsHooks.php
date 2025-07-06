<?php

namespace BitApps\PiPro\src\Integrations\WooCommerceSubscriptions;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WooCommerceSubscriptionsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'userSubscribeToProduct' => [
                'hook'          => 'woocommerce_subscription_payment_complete',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'userSubscribeToProduct'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'variableSubscriptionPurchase' => [
                'hook'          => 'woocommerce_subscription_payment_complete',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'variableSubscriptionPurchase'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'subscriptionTrialEnd' => [
                'hook'          => 'woocommerce_scheduled_subscription_trial_end',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionTrialEnd'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'subscriptionRenews' => [
                'hook'          => 'woocommerce_subscription_renewal_payment_complete',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionRenews'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'subscriptionStatusCancelled' => [
                'hook'          => 'woocommerce_subscription_status_cancelled',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionStatusCancelled'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'subscriptionStatusPending' => [
                'hook'          => 'woocommerce_subscription_status_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionStatusPending'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionStatusOnHold' => [
                'hook'          => 'woocommerce_subscription_status_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionStatusOnHold'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionRestore' => [
                'hook'          => 'woocommerce_subscription_status_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionRestore'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionStatusActive' => [
                'hook'          => 'woocommerce_subscription_status_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionStatusActive'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionStatusChanged' => [
                'hook'          => 'woocommerce_subscription_status_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionStatusChanged'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionTrashed' => [
                'hook'          => 'woocommerce_subscription_trashed',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionTrashed'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'subscriptionDeleted' => [
                'hook'          => 'woocommerce_subscription_deleted',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionDeleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'subscriptionUnableToUpdateStatus' => [
                'hook'          => 'woocommerce_subscription_unable_to_update_status',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionUnableToUpdateStatus'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionStatusExpired' => [
                'hook'          => 'woocommerce_subscription_status_expired',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionStatusExpired'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'renewalPaymentFailed' => [
                'hook'          => 'woocommerce_subscription_renewal_payment_failed',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'renewalPaymentFailed'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'subscriptionTrialEndDateUpdated' => [
                'hook'          => 'woocommerce_subscription_date_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionTrialEndDateUpdated'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionNextPaymentDateUpdated' => [
                'hook'          => 'woocommerce_subscription_date_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionNextPaymentDateUpdated'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionEndDateUpdated' => [
                'hook'          => 'woocommerce_subscription_date_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionEndDateUpdated'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionDateUpdated' => [
                'hook'          => 'woocommerce_subscription_date_updated',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionDateUpdated'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'subscriptionTrialEndDateDeleted' => [
                'hook'          => 'woocommerce_subscription_date_deleted',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionTrialEndDateDeleted'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'subscriptionNextPaymentDateDeleted' => [
                'hook'          => 'woocommerce_subscription_date_deleted',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionNextPaymentDateDeleted'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'subscriptionEndDateDeleted' => [
                'hook'          => 'woocommerce_subscription_date_deleted',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionEndDateDeleted'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'subscriptionDateDeleted' => [
                'hook'          => 'woocommerce_subscription_date_deleted',
                'callback'      => [WooCommerceSubscriptionsTrigger::class, 'subscriptionDateDeleted'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
