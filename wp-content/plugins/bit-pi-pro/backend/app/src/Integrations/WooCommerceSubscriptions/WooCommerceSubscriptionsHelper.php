<?php

namespace BitApps\PiPro\src\Integrations\WooCommerceSubscriptions;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use WC_Customer;

final class WooCommerceSubscriptionsHelper
{
    public static function mapSubscriptionData($subscription, $additionalData = [])
    {
        return array_merge(
            [
                'subscription' => self::getSubscriptionData($subscription),
                'customer'     => self::getCustomerData($subscription->get_user_id()),
                'line_items'   => self::getLineItems($subscription->get_items())
            ],
            $additionalData
        );
    }

    private static function getSubscriptionData($subscription)
    {
        return array_merge(
            [
                'id'                   => $subscription->get_id(),
                'name'                 => 'Subscription #' . $subscription->get_order_number(),
                'status'               => $subscription->get_status(),
                'order_number'         => $subscription->get_order_number(),
                'order_key'            => $subscription->get_order_key(),
                'parent_id'            => $subscription->get_parent_id(),
                'currency'             => $subscription->get_currency(),
                'prices_include_tax'   => $subscription->get_prices_include_tax(),
                'discount_total'       => $subscription->get_discount_total(),
                'discount_tax'         => $subscription->get_discount_tax(),
                'shipping_total'       => $subscription->get_shipping_total(),
                'shipping_tax'         => $subscription->get_shipping_tax(),
                'cart_tax'             => $subscription->get_cart_tax(),
                'total_tax'            => $subscription->get_total_tax(),
                'total'                => $subscription->get_total(),
                'payment_method'       => $subscription->get_payment_method(),
                'payment_method_title' => $subscription->get_payment_method_title(),
                'transaction_id'       => $subscription->get_transaction_id(),
                'customer_note'        => $subscription->get_customer_note(),
                'billing_period'       => $subscription->get_billing_period(),
                'billing_interval'     => $subscription->get_billing_interval(),
                'suspension_count'     => $subscription->get_suspension_count(),
            ],
            self::formatDates($subscription)
        );
    }

    private static function getCustomerData($id)
    {
        $customer = new WC_Customer($id);

        if (empty($customer)) {
            return [];
        }

        return [
            'customer_id'         => $customer->get_id(),
            'first_name'          => $customer->get_first_name(),
            'last_name'           => $customer->get_last_name(),
            'email'               => $customer->get_email(),
            'username'            => $customer->get_username(),
            'display_name'        => $customer->get_display_name(),
            'is_paying_customer'  => $customer->get_is_paying_customer(),
            'billing_first_name'  => $customer->get_billing_first_name(),
            'billing_last_name'   => $customer->get_billing_last_name(),
            'billing_company'     => $customer->get_billing_company(),
            'billing_address_1'   => $customer->get_billing_address_1(),
            'billing_address_2'   => $customer->get_billing_address_2(),
            'billing_city'        => $customer->get_billing_city(),
            'billing_postcode'    => $customer->get_billing_postcode(),
            'billing_country'     => $customer->get_billing_country(),
            'billing_state'       => $customer->get_billing_state(),
            'billing_email'       => $customer->get_billing_email(),
            'billing_phone'       => $customer->get_billing_phone(),
            'shipping_first_name' => $customer->get_shipping_first_name(),
            'shipping_last_name'  => $customer->get_shipping_last_name(),
            'shipping_company'    => $customer->get_shipping_company(),
            'shipping_address_1'  => $customer->get_shipping_address_1(),
            'shipping_address_2'  => $customer->get_shipping_address_2(),
            'shipping_city'       => $customer->get_shipping_city(),
            'shipping_postcode'   => $customer->get_shipping_postcode(),
            'shipping_country'    => $customer->get_shipping_country(),
            'shipping_state'      => $customer->get_shipping_state(),
        ];
    }

    private static function formatDates($subscription)
    {
        $dateCreated = $subscription->get_date_created('edit') ?? null;
        $dateModified = $subscription->get_date_modified('edit') ?? null;

        return [
            'trial_end_date'    => self::checkEmptyDate($subscription->get_date('trial_end')),
            'next_payment_date' => self::checkEmptyDate($subscription->get_date('next_payment')),
            'end_date'          => self::checkEmptyDate($subscription->get_date('end_date')),
            'date_created'      => $dateCreated ? gmdate('Y-m-d H:i:s', $dateCreated->getTimestamp()) : null,
            'date_modified'     => $dateModified ? gmdate('Y-m-d H:i:s', $dateModified->getTimestamp()) : null,
        ];
    }

    private static function checkEmptyDate($date)
    {
        return empty($date) ? 'N/A' : $date;
    }

    private static function getLineItems($items)
    {
        $lineItems = [];
        foreach ($items as $item) {
            $product = $item->get_product();

            $lineItems[] = [
                'product_id'         => $item->get_product_id(),
                'variation_id'       => $item->get_variation_id(),
                'product_name'       => $item->get_name(),
                'quantity'           => $item->get_quantity(),
                'subtotal'           => $item->get_subtotal(),
                'total'              => $item->get_total(),
                'subtotal_tax'       => $item->get_subtotal_tax(),
                'tax_class'          => $item->get_tax_class(),
                'tax_status'         => $item->get_tax_status(),
                'product_sku'        => $product->get_sku(),
                'product_unit_price' => $product->get_price(),
                'product_urls'       => get_permalink(wp_get_post_parent_id($product->get_id()))
            ];
        }

        return $lineItems;
    }
}
