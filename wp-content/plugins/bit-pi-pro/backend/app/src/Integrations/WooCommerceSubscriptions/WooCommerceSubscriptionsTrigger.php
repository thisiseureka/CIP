<?php

namespace BitApps\PiPro\src\Integrations\WooCommerceSubscriptions;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use WC_Order;
use WC_Subscription;

final class WooCommerceSubscriptionsTrigger
{
    public static function subscriptionStatusCancelled($subscription)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription);

        self::execute('subscriptionStatusCancelled', $data);
    }

    public static function variableSubscriptionPurchase($subscription)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $lastOrderId = $subscription->get_last_order();

        if (!empty($lastOrderId) && $lastOrderId !== $subscription->get_parent_id()) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription);

        self::execute('variableSubscriptionPurchase', $data);
    }

    public static function renewalPaymentFailed($subscription, $lastOrder)
    {
        if (!self::isPluginInstalled() || !class_exists('\WC_Order')) {
            return;
        }

        if (!$subscription instanceof WC_Subscription || !$lastOrder instanceof WC_Order) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription, ['last_order_id' => $lastOrder->get_id()]);

        self::execute('renewalPaymentFailed', $data);
    }

    public static function subscriptionRenews($subscription, $lastOrder)
    {
        if (!self::isPluginInstalled() || !class_exists('\WC_Order')) {
            return;
        }

        if (!$subscription instanceof WC_Subscription || !$lastOrder instanceof WC_Order) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription, ['last_order_id' => $lastOrder->get_id()]);

        self::execute('subscriptionRenews', $data);
    }

    public static function userSubscribeToProduct($subscription)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $lastOrderId = $subscription->get_last_order();

        if (!empty($lastOrderId) && $lastOrderId !== $subscription->get_parent_id()) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription);

        self::execute('userSubscribeToProduct', $data);
    }

    public static function subscriptionStatusExpired($subscription)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription);

        self::execute('subscriptionStatusExpired', $data);
    }

    public static function subscriptionTrialEnd($subscriptionId)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $subscription = wcs_get_subscription($subscriptionId);

        if (!$subscription instanceof WC_Subscription) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription);

        self::execute('subscriptionTrialEnd', $data);
    }

    public static function subscriptionStatusPending($subscription, $newStatus, $oldStatus)
    {
        if ($newStatus != 'pending-cancel') {
            return;
        }

        return self::handleSubscriptionStatusChanged('subscriptionStatusPending', $subscription, $newStatus, $oldStatus);
    }

    public static function subscriptionStatusOnHold($subscription, $newStatus, $oldStatus)
    {
        if ($newStatus != 'on-hold') {
            return;
        }

        return self::handleSubscriptionStatusChanged('subscriptionStatusOnHold', $subscription, $newStatus, $oldStatus);
    }

    public static function subscriptionRestore($subscription, $newStatus, $oldStatus)
    {
        if ($oldStatus != 'trash') {
            return;
        }

        return self::handleSubscriptionStatusChanged('subscriptionRestore', $subscription, $newStatus, $oldStatus);
    }

    public static function subscriptionStatusActive($subscription, $newStatus, $oldStatus)
    {
        if ($newStatus != 'active') {
            return;
        }

        return self::handleSubscriptionStatusChanged('subscriptionStatusActive', $subscription, $newStatus, $oldStatus);
    }

    public static function subscriptionTrashed($subscriptionId)
    {
        return self::handleSubscriptionDeleted('subscriptionTrashed', $subscriptionId);
    }

    public static function subscriptionDeleted($subscriptionId)
    {
        return self::handleSubscriptionDeleted('subscriptionDeleted', $subscriptionId);
    }

    public static function subscriptionTrialEndDateUpdated($subscription, $dateType, $datetime)
    {
        if ($dateType != 'trial_end') {
            return;
        }

        return self::handleSubscriptionDateUpdated('subscriptionTrialEndDateUpdated', $subscription, $dateType, $datetime);
    }

    public static function subscriptionNextPaymentDateUpdated($subscription, $dateType, $datetime)
    {
        if ($dateType != 'next_payment') {
            return;
        }

        return self::handleSubscriptionDateUpdated('subscriptionNextPaymentDateUpdated', $subscription, $dateType, $datetime);
    }

    public static function subscriptionEndDateUpdated($subscription, $dateType, $datetime)
    {
        if ($dateType != 'end') {
            return;
        }

        return self::handleSubscriptionDateUpdated('subscriptionEndDateUpdated', $subscription, $dateType, $datetime);
    }

    public static function subscriptionTrialEndDateDeleted($subscription, $dateType)
    {
        if ($dateType != 'trial_end') {
            return;
        }

        return self::handleSubscriptionDateUpdated('subscriptionTrialEndDateDeleted', $subscription, $dateType);
    }

    public static function subscriptionNextPaymentDateDeleted($subscription, $dateType)
    {
        if ($dateType != 'next_payment') {
            return;
        }

        return self::handleSubscriptionDateUpdated('subscriptionNextPaymentDateDeleted', $subscription, $dateType);
    }

    public static function subscriptionEndDateDeleted($subscription, $dateType)
    {
        if ($dateType != 'end') {
            return;
        }

        return self::handleSubscriptionDateUpdated('subscriptionEndDateDeleted', $subscription, $dateType);
    }

    public static function subscriptionStatusChanged($subscription, $newStatus, $oldStatus)
    {
        return self::handleSubscriptionStatusChanged('subscriptionStatusChanged', $subscription, $newStatus, $oldStatus);
    }

    public static function subscriptionUnableToUpdateStatus($subscription, $newStatus, $oldStatus)
    {
        return self::handleSubscriptionStatusChanged('subscriptionUnableToUpdateStatus', $subscription, $newStatus, $oldStatus);
    }

    public static function subscriptionDateUpdated($subscription, $dateType, $datetime)
    {
        return self::handleSubscriptionDateUpdated('subscriptionDateUpdated', $subscription, $dateType, $datetime);
    }

    public static function subscriptionDateDeleted($subscription, $dateType)
    {
        return self::handleSubscriptionDateUpdated('subscriptionDateDeleted', $subscription, $dateType);
    }

    private static function handleSubscriptionDeleted($machineSlug, $subscriptionId)
    {
        if (empty($subscriptionId) || !self::isPluginInstalled()) {
            return;
        }

        $subscription = new WC_Subscription($id);
        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription);

        self::execute($machineSlug, $data);
    }

    private static function handleSubscriptionDateUpdated($machineSlug, $subscription, $dateType, $datetime = null)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $additionalData = empty($datetime) ? ['date_type' => $dateType] : ['date_type' => $dateType, 'datetime' => $datetime];
        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription, $additionalData);

        return self::execute($machineSlug, $data);
    }

    private static function handleSubscriptionStatusChanged($machineSlug, $subscription, $newStatus, $oldStatus)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        $data = WooCommerceSubscriptionsHelper::mapSubscriptionData($subscription, ['new_status' => $newStatus, 'old_status' => $oldStatus]);

        return self::execute($machineSlug, $data);
    }

    private static function execute($machineSlug, $data)
    {
        $flows = FlowService::exists('wooCommerceSubscriptions', $machineSlug);
        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('WooCommerce') && class_exists('\WC_Subscription');
    }
}
