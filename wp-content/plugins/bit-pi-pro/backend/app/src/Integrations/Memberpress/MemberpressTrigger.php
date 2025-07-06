<?php

namespace BitApps\PiPro\src\Integrations\Memberpress;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use MeprEvent;

final class MemberpressTrigger
{
    public static function oneTimeMembershipSubscribe(MeprEvent $event)
    {
        $transaction = $event->get_data();

        $product = $transaction->product();

        $productId = $product->ID;

        $userId = absint($transaction->user()->ID);

        if ('lifetime' !== (string) $product->period_type) {
            return;
        }

        $postData = get_post($productId);

        $userData = Utility::getUserInfo($userId);

        $finalData = array_merge((array) $postData, $userData);

        if ($userId && $flows = FlowService::exists(MemberpressTasks::APP_SLUG, MemberpressTasks::ONE_TIME_SUBSCRIPTION)) {
            IntegrationHelper::handleFlowForForm($flows, $finalData, $userId, 'onetime-membership-id');
        }
    }

    public static function recurringMembershipSubscribe(MeprEvent $event)
    {
        $transaction = $event->get_data();

        $product = $transaction->product();

        $productId = $product->ID;

        $userId = absint($transaction->user()->ID);

        if ('lifetime' === (string) $product->period_type) {
            return;
        }

        $postData = get_post($productId);

        $userData = Utility::getUserInfo($userId);

        $finalData = array_merge((array) $postData, $userData);

        if ($userId && $flows = FlowService::exists(MemberpressTasks::APP_SLUG, MemberpressTasks::RECURRING_SUBSCRIPTION)) {
            IntegrationHelper::handleFlowForForm($flows, $finalData, $userId, 'onetime-membership-id');
        }
    }

    public static function membershipSubscribeCancel($oldStatus, $newStatus, $subscription)
    {
        $oldStatus = (string) $oldStatus;

        $newStatus = (string) $newStatus;

        if ($oldStatus === $newStatus && $newStatus !== 'cancelled') {
            return;
        }

        $productId = $subscription->rec->product_id;

        $userId = \intval($subscription->rec->user_id);

        $userData = Utility::getUserInfo($userId);

        $finalData = array_merge((array) $subscription->rec, $userData);

        $flows = FlowService::exists(MemberpressTasks::APP_SLUG, MemberpressTasks::CANCEL_SUBSCRIPTION);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $productId, 'membership-id');
    }

    public static function membershipSubscribePaused($oldStatus, $newStatus, $subscription)
    {
        $newStatuss = (string) $newStatus;

        if ($newStatuss !== 'suspended') {
            return;
        }

        $productId = $subscription->rec->product_id;

        $userId = \intval($subscription->rec->user_id);

        $userData = Utility::getUserInfo($userId);

        $finalData = array_merge((array) $subscription->rec, $userData);

        $flows = FlowService::exists(MemberpressTasks::APP_SLUG, MemberpressTasks::PAUSE_SUBSCRIPTION);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $productId, 'recurring-membership-id');
    }

    public static function membershipSubscribeExpire(MeprEvent $event)
    {
        $transaction = $event->get_data();

        $product = $transaction->product();

        $productId = $product->ID;

        $userId = absint($transaction->user()->ID);

        $postData = get_post($productId);

        $userData = Utility::getUserInfo($userId);

        $finalData = array_merge((array) $postData, $userData);

        $flows = FlowService::exists(MemberpressTasks::APP_SLUG, MemberpressTasks::SUBSCRIPTION_EXPIRED);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $productId, 'membership-id');
    }
}
