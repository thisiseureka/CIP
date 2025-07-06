<?php

namespace BitApps\PiPro\src\Integrations\WcBookings;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitCode\FI\Core\Util\Helper;
use WC_Customer;

final class WcBookingsHelper
{
    public static function mapBookingData($booking, $bookingId)
    {
        $personCounts = $booking->get_person_counts();
        $personCounts = !empty($personCounts) && \is_array($personCounts) ? array_sum(array_values($personCounts)) : 0;
        $customer = new WC_Customer($booking->get_customer_id());
        $order = $booking->get_order();
        $product = $booking->get_product();

        return array_merge(
            [
                'id'                  => $bookingId,
                'total_person_counts' => $personCounts,
                'all_day'             => $booking->get_all_day(),
                'status'              => $booking->get_status(),
                'parent_id'           => $booking->get_parent_id(),
                'start'               => $booking->get_start(),
                'end'                 => $booking->get_end(),
            ],
            self::setCustomerData($customer),
            self::setBookingProductData($product),
            self::setOrderData($order)
        );
    }

    public static function getFilteredFlows($flows, $entityId, $status)
    {
        return array_filter(
            $flows,
            function ($flow) use ($status) {
                if (\is_string($flow->flow_details)) {
                    $flow->flow_details = json_decode($flow->flow_details);
                }

                $flowDetails = $flow->flow_details;

                return empty($flowDetails->selectedStatus)
                || $flowDetails->selectedStatus === 'any'
                || $flowDetails->selectedStatus == $status;
            }
        );
    }

    public static function getBookingFields()
    {
        $fields = [
            [
                'name'  => 'id',
                'type'  => 'number',
                'label' => __('Booking ID', 'bit-pi')
            ],
            [
                'name'  => 'status',
                'type'  => 'text',
                'label' => __('Booking Status', 'bit-pi')
            ],
            [
                'name'  => 'all_day',
                'type'  => 'text',
                'label' => __('All Day', 'bit-pi')
            ],
            [
                'name'  => 'total_person_counts',
                'type'  => 'text',
                'label' => __('Booking Total Person Counts', 'bit-pi')
            ],
            [
                'name'  => 'parent_id',
                'type'  => 'number',
                'label' => __('Parent Order ID', 'bit-pi')
            ],
            [
                'name'  => 'start',
                'type'  => 'date',
                'label' => __('Booking Start', 'bit-pi')
            ],
            [
                'name'  => 'end',
                'type'  => 'date',
                'label' => __('Booking End', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_id',
                'type'  => 'number',
                'label' => __('Booking Product ID', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_title',
                'type'  => 'text',
                'label' => __('Booking Product Title', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_url',
                'type'  => 'text',
                'label' => __('Booking Product URL', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_sku',
                'type'  => 'text',
                'label' => __('Booking Product SKU', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_price',
                'type'  => 'number',
                'label' => __('Booking Product Price', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_regular_price',
                'type'  => 'number',
                'label' => __('Booking Product Regular Price', 'bit-pi')
            ],
            [
                'name'  => 'booking_product_sale_price',
                'type'  => 'number',
                'label' => __('Booking Product Sale Price', 'bit-pi')
            ],
        ];

        return array_merge($fields, self::customerFields(), self::billingAddress(), self::shippingAddress(), self::getWCOrderFields());
    }

    public static function getWCOrderFields()
    {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $fields = array_merge(self::checkoutBasicFields(), self::getOrderACFFields(), self::getCheckoutCustomFields(), self::getFlexibleCheckoutFields());

        if (\defined('WC_VERSION') && version_compare(WC_VERSION, '8.5.1', '>=')) {
            return array_merge($fields, self::checkoutUpgradeFields());
        }

        return $fields;
    }

    private static function setOrderData($order)
    {
        if (empty($order)) {
            return [];
        }

        $orderId = $order->get_id();

        $data = [
            'order_id'                      => $orderId,
            'order_key'                     => $order->get_order_key(),
            'order_cart_tax'                => $order->get_cart_tax(),
            'order_currency'                => $order->get_currency(),
            'order_discount_tax'            => $order->get_discount_tax(),
            'order_discount_to_display'     => $order->get_discount_to_display(),
            'order_discount_total'          => $order->get_discount_total(),
            'order_shipping_tax'            => $order->get_shipping_tax(),
            'order_shipping_total'          => $order->get_shipping_total(),
            'order_total_tax'               => $order->get_total_tax(),
            'order_total'                   => $order->get_total(),
            'order_total_refunded'          => $order->get_total_refunded(),
            'order_total_shipping_refunded' => $order->get_total_shipping_refunded(),
            'order_total_qty_refunded'      => $order->get_total_qty_refunded(),
            'order_remaining_refund_amount' => $order->get_remaining_refund_amount(),
            'order_status'                  => $order->get_status(),
            'order_shipping_method'         => $order->get_shipping_method(),
            'order_created_via'             => $order->get_created_via(),
            'order_date_created'            => empty($order->get_date_created('edit')) ? 'N/A' : gmdate('Y-m-d H:i:s', $order->get_date_created('edit')->getTimestamp()),
            'order_date_modified'           => empty($order->get_date_modified('edit')) ? 'N/A' : gmdate('Y-m-d H:i:s', $order->get_date_modified('edit')->getTimestamp()),
            'order_date_completed'          => empty($order->get_date_completed('edit')) ? 'N/A' : gmdate('Y-m-d H:i:s', $order->get_date_completed('edit')->getTimestamp()),
            'order_date_paid'               => empty($order->get_date_paid('edit')) ? 'N/A' : gmdate('Y-m-d H:i:s', $order->get_date_paid('edit')->getTimestamp()),
            'order_prices_include_tax'      => $order->get_prices_include_tax(),
            'order_payment_method'          => $order->get_payment_method(),
            'order_payment_method_title'    => $order->get_payment_method_title(),
            'order_checkout_received_url'   => $order->get_checkout_order_received_url(),
            'order_customer_note'           => $order->get_customer_note(),
            'billing_first_name'            => $order->get_billing_first_name(),
            'billing_last_name'             => $order->get_billing_last_name(),
            'billing_company'               => $order->get_billing_company(),
            'billing_address_1'             => $order->get_billing_address_1(),
            'billing_address_2'             => $order->get_billing_address_2(),
            'billing_city'                  => $order->get_billing_city(),
            'billing_postcode'              => $order->get_billing_postcode(),
            'billing_country'               => $order->get_billing_country(),
            'billing_state'                 => $order->get_billing_state(),
            'billing_email'                 => $order->get_billing_email(),
            'billing_phone'                 => $order->get_billing_phone(),
            'shipping_first_name'           => $order->get_shipping_first_name(),
            'shipping_last_name'            => $order->get_shipping_last_name(),
            'shipping_company'              => $order->get_shipping_company(),
            'shipping_address_1'            => $order->get_shipping_address_1(),
            'shipping_address_2'            => $order->get_shipping_address_2(),
            'shipping_city'                 => $order->get_shipping_city(),
            'shipping_postcode'             => $order->get_shipping_postcode(),
            'shipping_country'              => $order->get_shipping_country(),
        ];

        if (\defined('WC_VERSION') && version_compare(WC_VERSION, '8.5.1', '>=')) {
            $data = array_merge($data, self::getCheckoutUpgradeFieldsData($order), self::getACFFieldsData('shop_order', $orderId), self::setFlexibleCheckoutFieldsData($order));
        } else {
            $data = array_merge($data, self::getACFFieldsData('shop_order', $orderId), self::setFlexibleCheckoutFieldsData($order));
        }

        $data['order_line_items'] = wp_json_encode(self::getOrderLineItems($order->get_items()));

        return $data;
    }

    private static function getCheckoutUpgradeFieldsData($order)
    {
        return [
            '_wc_order_attribution_device_type'        => $order->get_meta('_wc_order_attribution_device_type'),
            '_wc_order_attribution_referrer'           => $order->get_meta('_wc_order_attribution_referrer'),
            '_wc_order_attribution_session_count'      => $order->get_meta('_wc_order_attribution_session_count'),
            '_wc_order_attribution_session_entry'      => $order->get_meta('_wc_order_attribution_session_entry'),
            '_wc_order_attribution_session_pages'      => $order->get_meta('_wc_order_attribution_session_pages'),
            '_wc_order_attribution_session_start_time' => $order->get_meta('_wc_order_attribution_session_start_time'),
            '_wc_order_attribution_source_type'        => $order->get_meta('_wc_order_attribution_source_type'),
            '_wc_order_attribution_user_agent'         => $order->get_meta('_wc_order_attribution_user_agent'),
            '_wc_order_attribution_utm_source'         => $order->get_meta('_wc_order_attribution_utm_source'),
        ];
    }

    private static function setFlexibleCheckoutFieldsData($order)
    {
        if (!class_exists('WooCommerce') || !class_exists('Flexible_Checkout_Fields_Plugin')) {
            return [];
        }

        $data = [];
        $orderData = $order->get_data();
        $checkoutFields = WC()->checkout()->get_checkout_fields();

        foreach ($checkoutFields as $groupKey => $group) {
            foreach ($group as $fieldKey => $field) {
                if (!empty($field['custom_field']) && $field['custom_field']) {
                    $fieldKey = $field['name'] ?? $fieldKey;
                    $value = $orderData[$fieldKey] ?? $order->get_meta('_' . $fieldKey) ?? null;

                    if ($groupKey != 'shipping') {
                        $data[$fieldKey] = $value;
                    }
                }
            }
        }

        return $data;
    }

    private static function getOrderLineItems($items)
    {
        $lineItems = [];

        foreach ($items as $item) {
            $productId = $item->get_product_id();
            $product = $item->get_product();

            $itemData = [
                'product_id'         => $productId,
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
                'product_urls'       => get_permalink(wp_get_post_parent_id($productId))
            ];

            $lineItems[] = (object) array_merge($itemData, self::getACFFieldsData('product', $productId));
        }

        return $lineItems;
    }

    private static function getACFFieldsData($type, $id)
    {
        $itemData = [];
        $acfFieldGroups = Helper::acfGetFieldGroups([$type]);

        foreach ($acfFieldGroups as $group) {
            $acfFields = acf_get_fields($group['ID']);

            foreach ($acfFields as $field) {
                $itemData[$field['_name']] = get_post_meta($id, $field['_name'])[0] ?? null;
            }
        }

        return $itemData;
    }

    private static function setBookingProductData($product)
    {
        return [
            'booking_product_id'            => $product->get_id(),
            'booking_product_title'         => $product->get_title(),
            'booking_product_sku'           => $product->get_sku(),
            'booking_product_status'        => $product->get_status(),
            'booking_product_price'         => $product->get_price(),
            'booking_product_regular_price' => $product->get_regular_price(),
            'booking_product_sale_price'    => $product->get_sale_price(),
            'booking_product_url'           => get_permalink($product->get_id()),
        ];
    }

    private static function setCustomerData($customer)
    {
        return [
            'customer_id'        => $customer->get_id(),
            'first_name'         => $customer->get_first_name(),
            'last_name'          => $customer->get_last_name(),
            'email'              => $customer->get_email(),
            'username'           => $customer->get_username(),
            'display_name'       => $customer->get_display_name(),
            'is_paying_customer' => $customer->get_is_paying_customer(),
        ];
    }

    private static function getOrderACFFields()
    {
        if (!class_exists('ACF')) {
            return [];
        }

        $fields = [];
        $acfFieldGroups = Helper::acfGetFieldGroups(['shop_order']);

        foreach ($acfFieldGroups as $group) {
            $acfFields = acf_get_fields($group['ID']);

            foreach ($acfFields as $field) {
                $type = $field['type'] === 'image' ? 'file' : $field['type'];

                $fields[] = (object) [
                    'name'  => $field['_name'],
                    'type'  => $type,
                    'label' => $field['label']
                ];
            }
        }

        return $fields;
    }

    private static function getCheckoutCustomFields()
    {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $fields = [];
        $checkoutFields = WC()->checkout()->get_checkout_fields();

        foreach ($checkoutFields as $group) {
            foreach ($group as $field) {
                if (!empty($field['custom']) && $field['custom']) {
                    $type = isset($field['type']) ? $field['type'] : 'text';
                    $type = $type === 'image' ? 'file' : $type;

                    $fields[] = [
                        'name'  => $field['name'],
                        'type'  => $type,
                        'label' => $field['label']
                    ];
                }
            }
        }

        return $fields;
    }

    private static function getFlexibleCheckoutFields()
    {
        if (!class_exists('WooCommerce') || !class_exists('Flexible_Checkout_Fields_Plugin')) {
            return [];
        }

        $fields = [];
        $checkoutFields = WC()->checkout()->get_checkout_fields();

        foreach ($checkoutFields as $group) {
            foreach ($group as $fieldKey => $field) {
                if (!empty($field['custom_field']) && $field['custom_field']) {
                    $type = isset($field['type']) ? $field['type'] : 'text';
                    $type = $type === 'image' ? 'file' : $type;
                    $fieldKey = $field['name'] ?? $fieldKey;

                    $fields[] = (object) [
                        'name'  => $fieldKey,
                        'type'  => $type,
                        'label' => $field['label']
                    ];
                }
            }
        }

        return $fields;
    }

    private static function customerFields()
    {
        return [
            [
                'name'  => 'customer_id',
                'type'  => 'number',
                'label' => __('Customer Id', 'bit-pi')
            ],
            [
                'name'  => 'first_name',
                'type'  => 'text',
                'label' => __('First Name', 'bit-pi')
            ],
            [
                'name'  => 'last_name',
                'type'  => 'text',
                'label' => __('Last Name', 'bit-pi')
            ],
            [
                'name'  => 'email',
                'type'  => 'text',
                'label' => __('Email', 'bit-pi')
            ],
            [
                'name'  => 'username',
                'type'  => 'text',
                'label' => __('Username', 'bit-pi')
            ],
            [
                'name'  => 'display_name',
                'type'  => 'text',
                'label' => __('Display Name', 'bit-pi')
            ],
            [
                'name'  => 'is_paying_customer',
                'type'  => 'text',
                'label' => __('Is Paying Customer', 'bit-pi')
            ],
        ];
    }

    private static function billingAddress()
    {
        return [
            [
                'name'  => 'billing_first_name',
                'type'  => 'text',
                'label' => __('Billing First Name', 'bit-pi')
            ],
            [
                'name'  => 'billing_last_name',
                'type'  => 'text',
                'label' => __('Billing Last Name', 'bit-pi')
            ],
            [
                'name'  => 'billing_company',
                'type'  => 'text',
                'label' => __('Billing Company', 'bit-pi')
            ],
            [
                'name'  => 'billing_address_1',
                'type'  => 'text',
                'label' => __('Billing Address 1', 'bit-pi')
            ],
            [
                'name'  => 'billing_address_2',
                'type'  => 'text',
                'label' => __('Billing Address 2', 'bit-pi')
            ],
            [
                'name'  => 'billing_city',
                'type'  => 'text',
                'label' => __('Billing City', 'bit-pi')
            ],
            [
                'name'  => 'billing_postcode',
                'type'  => 'number',
                'label' => __('Billing Post Code', 'bit-pi')
            ],
            [
                'name'  => 'billing_country',
                'type'  => 'text',
                'label' => __('Billing Country', 'bit-pi')
            ],
            [
                'name'  => 'billing_state',
                'type'  => 'text',
                'label' => __('Billing State', 'bit-pi')
            ],
            [
                'name'  => 'billing_email',
                'type'  => 'text',
                'label' => __('Billing Email', 'bit-pi')
            ],
            [
                'name'  => 'billing_phone',
                'type'  => 'text',
                'label' => __('Billing Phone', 'bit-pi')
            ],
        ];
    }

    private static function shippingAddress()
    {
        return [
            [
                'name'  => 'shipping_first_name',
                'type'  => 'text',
                'label' => __('Shipping First Name', 'bit-pi')
            ],
            [
                'name'  => 'shipping_last_name',
                'type'  => 'text',
                'label' => __('Shipping Last Name', 'bit-pi')
            ],
            [
                'name'  => 'shipping_company',
                'type'  => 'text',
                'label' => __('Shipping Company', 'bit-pi')
            ],
            [
                'name'  => 'shipping_address_1',
                'type'  => 'text',
                'label' => __('Shipping Address 1', 'bit-pi')
            ],
            [
                'name'  => 'shipping_address_2',
                'type'  => 'text',
                'label' => __('Shipping Address 2', 'bit-pi')
            ],
            [
                'name'  => 'shipping_city',
                'type'  => 'text',
                'label' => __('Shipping City', 'bit-pi')
            ],
            [
                'name'  => 'shipping_postcode',
                'type'  => 'number',
                'label' => __('Shipping Post Code', 'bit-pi')
            ],
            [
                'name'  => 'shipping_country',
                'type'  => 'text',
                'label' => __('Shipping Country', 'bit-pi')
            ],
        ];
    }

    private static function checkoutBasicFields()
    {
        return [
            [
                'name'  => 'order_id',
                'type'  => 'number',
                'label' => __('Order ID', 'bit-pi')
            ],
            [
                'name'  => 'order_key',
                'type'  => 'number',
                'label' => __('Order Key', 'bit-pi')
            ],
            [
                'name'  => 'order_cart_tax',
                'type'  => 'number',
                'label' => __('Order Cart Tax', 'bit-pi')
            ],
            [
                'name'  => 'order_currency',
                'type'  => 'text',
                'label' => __('Order Currency', 'bit-pi')
            ],
            [
                'name'  => 'order_discount_tax',
                'type'  => 'number',
                'label' => __('Order Discount Tax', 'bit-pi')
            ],
            [
                'name'  => 'order_discount_to_display',
                'type'  => 'text',
                'label' => __('Order Discount To Display', 'bit-pi')
            ],
            [
                'name'  => 'order_discount_total',
                'type'  => 'number',
                'label' => __('Order Discount Total', 'bit-pi')
            ],
            [
                'name'  => 'order_shipping_tax',
                'type'  => 'number',
                'label' => __('Order Shipping Tax', 'bit-pi')
            ],
            [
                'name'  => 'order_shipping_total',
                'type'  => 'number',
                'label' => __('Order Shipping Total', 'bit-pi')
            ],
            [
                'name'  => 'order_total_tax',
                'type'  => 'number',
                'label' => __('Order Total Tax', 'bit-pi')
            ],
            [
                'name'  => 'order_total',
                'type'  => 'number',
                'label' => __('Order Total', 'bit-pi')
            ],
            [
                'name'  => 'order_total_refunded',
                'type'  => 'number',
                'label' => __('Order Total Refunded', 'bit-pi')
            ],
            [
                'name'  => 'order_total_shipping_refunded',
                'type'  => 'number',
                'label' => __('Order Total Shipping Refunded', 'bit-pi')
            ],
            [
                'name'  => 'order_total_qty_refunded',
                'type'  => 'number',
                'label' => __('Order Total Qty Refunded', 'bit-pi')
            ],
            [
                'name'  => 'order_remaining_refund_amount',
                'type'  => 'number',
                'label' => __('Order remaining_refund_amount', 'bit-pi')
            ],
            [
                'name'  => 'order_status',
                'type'  => 'text',
                'label' => __('Order Status', 'bit-pi')
            ],
            [
                'name'  => 'order_shipping_method',
                'type'  => 'text',
                'label' => __('Order shipping method', 'bit-pi')
            ],
            [
                'name'  => 'order_created_via',
                'type'  => 'text',
                'label' => __('Order Created Via', 'bit-pi')
            ],
            [
                'name'  => 'order_date_created',
                'type'  => 'date',
                'label' => __('Order Date created', 'bit-pi')
            ],
            [
                'name'  => 'order_date_modified',
                'type'  => 'date',
                'label' => __('Order Date Modified', 'bit-pi')
            ],
            [
                'name'  => 'order_date_completed',
                'type'  => 'date',
                'label' => __('Order Date completed', 'bit-pi')
            ],
            [
                'name'  => 'order_date_paid',
                'type'  => 'date',
                'label' => __('Order Date paid', 'bit-pi')
            ],

            [
                'name'  => 'order_prices_include_tax',
                'type'  => 'number',
                'label' => __('Order Prices Include Tax', 'bit-pi')
            ],
            [
                'name'  => 'order_payment_method',
                'type'  => 'text',
                'label' => __('Order Payment Method', 'bit-pi')
            ],
            [
                'name'  => 'order_payment_method_title',
                'type'  => 'text',
                'label' => __('Order Payment Method Title', 'bit-pi')
            ],
            [
                'name'  => 'order_line_items',
                'type'  => 'text',
                'label' => __('Order Line Items', 'bit-pi')
            ],
            [
                'name'  => 'order_checkout_received_url',
                'type'  => 'text',
                'label' => __('Order Checkout Received URL', 'bit-pi')
            ],
            [
                'name'  => 'order_customer_note',
                'type'  => 'text',
                'label' => __('Order Customer Note', 'bit-pi')
            ],
        ];
    }

    private static function checkoutUpgradeFields()
    {
        return [
            [
                'name'  => '_wc_order_attribution_device_type',
                'type'  => 'text',
                'label' => __('Order Device Type', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_referrer',
                'type'  => 'text',
                'label' => __('Order Referring source', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_session_count',
                'type'  => 'text',
                'label' => __('Order Session Count', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_session_entry',
                'type'  => 'text',
                'label' => __('Order Session Entry', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_session_pages',
                'type'  => 'text',
                'label' => __('Order Session page views', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_session_start_time',
                'type'  => 'text',
                'label' => __('Order Session Start Time', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_source_type',
                'type'  => 'text',
                'label' => __('Order Source Type', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_user_agent',
                'type'  => 'text',
                'label' => __('Order User Agent', 'bit-pi')
            ],
            [
                'name'  => '_wc_order_attribution_utm_source',
                'type'  => 'text',
                'label' => __('Order Origin', 'bit-pi')
            ],
        ];
    }
}
