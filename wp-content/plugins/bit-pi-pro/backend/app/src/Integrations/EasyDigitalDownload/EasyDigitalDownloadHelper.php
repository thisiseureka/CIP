<?php

namespace BitApps\PiPro\src\Integrations\EasyDigitalDownload;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use EDD_Customer;
use EDD_Payment;
use EDD_SL_Download;

class EasyDigitalDownloadHelper
{
    public static function allProducts()
    {
        $args = [
            'post_type'      => 'download',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];

        $products = get_posts($args);
        $productsArray[] = [
            'value' => 'any',
            'label' => __('Any Product', 'bit-pi')
        ];
        foreach ($products as $product) {
            $productsArray[] = (object) [
                'value' => $product->ID,
                'label' => $product->post_title,
            ];
        }

        return $productsArray;
    }

    public static function allDiscount()
    {
        $allDiscountCode[] = [
            'value' => 'any',
            'label' => __('Any Discount Code', 'bit-pi')
        ];
        $discountCodes = edd_get_discounts();
        foreach ($discountCodes as $discount) {
            $allDiscountCode[] = (object) [
                'value' => $discount->code,
                'label' => $discount->name,
            ];
        }

        return $allDiscountCode;
    }

    public static function getSubscriptionData(EDD_Payment $payment)
    {
        global $wpdb;

        $purchasedProducts = implode(
            ', ',
            array_map(
                fn($entry) => $entry['name'],
                $payment->cart_details
            )
        );

        $purchasedProductsIds = implode(
            ', ',
            array_map(
                fn($entry) => $entry['id'],
                $payment->cart_details
            )
        );

        $priceId = self::getItemPriceId($payment->cart_details[0]);

        $licensesTable = $wpdb->prefix . 'edd_licenses';

        $licensesResult = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $licensesTable));

        if ($licensesResult === $licensesTable) {
            $licenses = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}edd_licenses WHERE payment_id= %s", $payment->ID));
        }

        $subscriptionData = [];
        $subscriptionData['order_id'] = $payment->ID;
        $subscriptionData['customer_email'] = $payment->email;
        $subscriptionData['customer_id'] = $payment->customer_id;
        $subscriptionData['user_id'] = $payment->user_info['id'];
        $subscriptionData['customer_first_name'] = $payment->first_name;
        $subscriptionData['customer_last_name'] = $payment->last_name;
        $subscriptionData['ordered_items'] = $purchasedProducts;
        $subscriptionData['currency'] = $payment->currency;
        $subscriptionData['status'] = $payment->status;
        $subscriptionData['discount_codes'] = (property_exists($payment, 'discounts')) ? $payment->discounts : 'NA';
        $subscriptionData['order_discounts'] = number_format($payment->order->discount, 2);
        $subscriptionData['order_subtotal'] = number_format($payment->subtotal, 2);
        $subscriptionData['order_tax'] = number_format($payment->tax, 2);
        $subscriptionData['order_total'] = number_format($payment->total, 2);
        $subscriptionData['payment_method'] = $payment->gateway;
        $subscriptionData['purchase_key'] = $payment->key;
        $subscriptionData['ordered_items_ids'] = $purchasedProductsIds;
        $subscriptionData['customer_address'] = $payment->user_info['address'];

        if (! empty($priceId)) {
            $subscriptionData['price_id'] = $priceId;
        }

        if (! empty($licenses)) {
            $subscriptionData['license_key'] = $licenses->license_key;
            $subscriptionData['license_key_expire_date'] = $licenses->expiration;
            $subscriptionData['license_key_status'] = $licenses->status;
        }

        $postIdOption = get_option('cfm-checkout-form');

        if (is_numeric($postIdOption)) {
            $postId = (int) $postIdOption;

            $fields = get_post_meta($postId, 'cfm-form', true);

            if (\is_array($fields) && $fields !== [] && \function_exists('edd_get_order_meta')) {
                foreach ($fields as $field) {
                    $subscriptionData[$field['name']] = edd_get_order_meta($payment->ID, $field['name'], true);
                }
            }
        }

        return $subscriptionData;
    }

    public static function getLicenseData($licenseId, $downloadId = 0, $paymentId = 0)
    {
        if (! \function_exists('edd_software_licensing')) {
            return;
        }

        $license = edd_software_licensing()->get_license($licenseId);

        if (false === $license) {
            return [];
        }

        if (empty($downloadId)) {
            $downloadId = $license->download_id;
        }

        if (empty($paymentId)) {
            $paymentId = $license->payment_id;
        }

        if (! \function_exists('edd_get_payment_customer_id')) {
            return;
        }

        if (! class_exists('EDD_Customer')) {
            return;
        }

        $customerId = edd_get_payment_customer_id($paymentId);

        $priceId = $license->price_id;

        if (empty($customerId)) {
            if (! \function_exists('edd_get_payment_meta_user_info') || ! \function_exists('edd_get_payment_user_email')) {
                return;
            }
            $userInfo = edd_get_payment_meta_user_info($paymentId);
            $customer = new EDD_Customer();
            $customer->email = edd_get_payment_user_email($paymentId);
            $customer->name = $userInfo['first_name'];
        } else {
            $customer = new EDD_Customer($customerId);
        }

        $expiration = null;

        if ($license->is_lifetime) {
            $expiration = 'never';
        } elseif ($license->expiration && is_numeric($license->expiration)) {
            $expiration = $license->expiration;
        }

        if (! class_exists('EDD_SL_Download')) {
            return;
        }

        $download = new EDD_SL_Download($downloadId);

        return [
            'ID'               => $license->ID,
            'key'              => $license->key,
            'customer_email'   => $customer->email,
            'customer_name'    => $customer->name,
            'customer_id'      => $customer->id,
            'user_id'          => $customer->user_id,
            'download_id'      => $downloadId,
            'price_id'         => $priceId,
            'product_name'     => $download->get_name(),
            'activation_limit' => $license->activation_limit,
            'activation_count' => $license->activation_count,
            'activated_urls'   => implode(',', $license->sites),
            'expiration'       => $expiration,
            'is_lifetime'      => $license->is_lifetime ? '1' : '0',
            'status'           => $license->status,
        ];
    }

    private static function getItemPriceId($item = [])
    {
        if (isset($item['item_number'])) {
            return isset($item['item_number']['options']['price_id']) ? $item['item_number']['options']['price_id'] : null;
        }

        return isset($item['options']['price_id']) ? $item['options']['price_id'] : null;
    }
}
