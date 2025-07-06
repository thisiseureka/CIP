<?php

namespace BitApps\PiPro\src\Integrations\EasyDigitalDownload;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use EDD_Payment;

final class EasyDigitalDownloadTrigger
{
    public static function pluginActive()
    {
        return \function_exists('EDD');
    }

    public static function handlePurchaseProduct($paymentId)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'purchaseProduct');

        if (!$flows) {
            return;
        }

        if (!\function_exists('edd_get_payment_meta_cart_details')) {
            return;
        }

        $cartItems = edd_get_payment_meta_cart_details($paymentId);

        if (!class_exists('\EDD_Payment') || empty($cartItems)) {
            return;
        }

        $payment = new EDD_Payment($paymentId);

        foreach ($cartItems as $item) {
            $finalData = [
                'user_id'         => $payment->user_id,
                'first_name'      => $payment->first_name,
                'last_name'       => $payment->last_name,
                'user_email'      => $payment->email,
                'product_name'    => $item['name'],
                'product_id'      => $item['id'],
                'order_item_id'   => $item['order_item_id'],
                'discount_codes'  => $payment->discounts,
                'order_discounts' => $item['discount'],
                'order_subtotal'  => $payment->subtotal,
                'order_total'     => $payment->total,
                'order_tax'       => $payment->tax,
                'payment_method'  => $payment->gateway,
            ];
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $finalData['product_id'], 'product-id');
    }

    public static function handlePurchaseProductDiscountCode($paymentId, $payment)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'purchaseProductDiscount');

        if (!$flows) {
            return;
        }

        $cartItems = edd_get_payment_meta_cart_details($paymentId);

        if (!class_exists('\EDD_Payment') || empty($cartItems)) {
            return;
        }

        $payment = new EDD_Payment($paymentId);
        foreach ($cartItems as $item) {
            $finalData = [
                'user_id'         => $payment->user_id,
                'first_name'      => $payment->first_name,
                'last_name'       => $payment->last_name,
                'user_email'      => $payment->email,
                'product_name'    => $item['name'],
                'product_id'      => $item['id'],
                'order_item_id'   => $item['order_item_id'],
                'discount_codes'  => $payment->discounts,
                'order_discounts' => $item['discount'],
                'order_subtotal'  => $payment->subtotal,
                'order_total'     => $payment->total,
                'order_tax'       => $payment->tax,
                'payment_method'  => $payment->gateway,
                'status'          => $payment->status,
            ];
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $finalData['discount_codes'], 'discount-id');
    }

    public static function handleOrderRefunded($orderId)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'orderRefunded');
        if (!$flows) {
            return;
        }

        $orderDetail = edd_get_payment($orderId);
        $totalDiscount = 0;

        if (empty($orderDetail)) {
            return;
        }

        $paymentId = $orderDetail->ID;
        $userId = edd_get_payment_user_id($paymentId);

        if (!$userId) {
            $userId = wp_get_current_user()->ID;
        }

        $userInfo = Utility::getUserInfo($userId);

        $paymentInfo = [
            'first_name'      => $userInfo['first_name'],
            'last_name'       => $userInfo['last_name'],
            'nickname'        => $userInfo['nickname'],
            'avatar_url'      => $userInfo['avatar_url'],
            'user_email'      => $userInfo['user_email'],
            'discount_codes'  => $orderDetail->discounts,
            'order_discounts' => $totalDiscount,
            'order_subtotal'  => $orderDetail->subtotal,
            'order_total'     => $orderDetail->total,
            'order_tax'       => $orderDetail->tax,
            'payment_method'  => $orderDetail->gateway,
        ];

        IntegrationHelper::handleFlowForForm($flows, $paymentInfo);
    }

    public static function getProduct()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Easy Digital Downloads'));
        }

        $products = EasyDigitalDownloadHelper::allProducts();

        return Response::success($products);
    }

    public static function getDiscount()
    {
        $discounts = EasyDigitalDownloadHelper::allDiscount();

        return Response::success($discounts);
    }

    public static function handleSubscriptionRenew($subscriptionId, $expiration, $subscription, $paymentId)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'subscriptionRenew');

        if (!$flows) {
            return;
        }

        if (! class_exists('\EDD_Payment')) {
            return;
        }

        $payment = new EDD_Payment($paymentId);

        if (empty($payment->cart_details)) {
            return;
        }

        $subscriptionData = EasyDigitalDownloadHelper::getSubscriptionData($payment);

        IntegrationHelper::handleFlowForForm($flows, $subscriptionData);
    }

    public static function licenseActivate($licenseId, $downloadId)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'licenseActivate');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId, $downloadId);

        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }

    public static function licenseDeactivate($licenseId, $downloadId)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'licenseDeactivate');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId, $downloadId);

        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }

    public static function handleLicenseStore($licenseId, $downloadId, $payment)
    {
        $flows = FlowService::exists('easyDigitalDownload', 'licenseKeyCreate');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId, $downloadId, $payment);
        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }

    public static function licenseStatusChangedToActivate($licenseId, $status)
    {
        if ($status !== 'active') {
            return;
        }

        $flows = FlowService::exists('easyDigitalDownload', 'statusChangedToActivate');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId);

        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }

    public static function licenseStatusChangedToInactive($licenseId, $status)
    {
        if ($status !== 'inactive') {
            return;
        }

        $flows = FlowService::exists('easyDigitalDownload', 'statusChangedToInactive');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId);

        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }

    public static function licenseStatusChangedToDisabled($licenseId, $status)
    {
        if ($status !== 'disabled') {
            return;
        }

        $flows = FlowService::exists('easyDigitalDownload', 'statusChangedToDisabled');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId);

        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }

    public static function licenseStatusChangedToExpire($licenseId, $status)
    {
        if ($status !== 'expired') {
            return;
        }

        $flows = FlowService::exists('easyDigitalDownload', 'statusChangedToExpire');

        if (!$flows) {
            return;
        }

        $licenseData = EasyDigitalDownloadHelper::getLicenseData($licenseId);

        IntegrationHelper::handleFlowForForm($flows, $licenseData);
    }
}
