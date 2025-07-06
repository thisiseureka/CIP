<?php

namespace BitApps\PiPro\src\Integrations\SureCart;

use SureCart\Models\Account;
use SureCart\Models\Customer;
use SureCart\Models\Product;
use SureCart\Models\Purchase;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


final class SureCartHelper
{
    public static function mapFields($id)
    {
        if ($id == 1) {
            $fields = [
                'Store Name' => (object) [
                    'fieldKey'  => 'store_name',
                    'fieldName' => __('Store Name', 'bit-pi'),
                ],
                'Store Url' => (object) [
                    'fieldKey'  => 'store_url',
                    'fieldName' => __('Store Url', 'bit-pi'),
                ],
                'Product Id' => (object) [
                    'fieldKey'  => 'product_id',
                    'fieldName' => __('Product Id', 'bit-pi'),
                ],
                'Product Name' => (object) [
                    'fieldKey'  => 'product_name',
                    'fieldName' => __('Product Name', 'bit-pi'),
                ],
                'Product Description' => (object) [
                    'fieldKey'  => 'product_description',
                    'fieldName' => __('Product Description', 'bit-pi'),
                ],
                'Product Thumb' => (object) [
                    'fieldKey'  => 'product_thumb',
                    'fieldName' => __('Product Thumb', 'bit-pi'),
                ],
                'Product Thumb Id' => (object) [
                    'fieldKey'  => 'product_thumb_id',
                    'fieldName' => __('Product Thumb Id', 'bit-pi'),
                ],
                'Product Price Id' => (object) [
                    'fieldKey'  => 'product_price_id',
                    'fieldName' => __('Product Price Id', 'bit-pi'),
                ],
                'Order Number' => (object) [
                    'fieldKey'  => 'order_number',
                    'fieldName' => __('Order Number', 'bit-pi'),
                ],
                'Product Price' => (object) [
                    'fieldKey'  => 'product_price',
                    'fieldName' => __('Product Price', 'bit-pi'),
                ],
                'Product Quantity' => (object) [
                    'fieldKey'  => 'product_quantity',
                    'fieldName' => __('Product Quantity', 'bit-pi'),
                ],
                'Max Price amount' => (object) [
                    'fieldKey'  => 'max_price_amount',
                    'fieldName' => __('Max Price amount', 'bit-pi'),
                ],
                'Min Price amount' => (object) [
                    'fieldKey'  => 'min_price_amount',
                    'fieldName' => __('Min Price amount', 'bit-pi'),
                ],
                'Order Id' => (object) [
                    'fieldKey'  => 'order_id',
                    'fieldName' => __('Order ID', 'bit-pi'),
                ],
                'Order Date' => (object) [
                    'fieldKey'  => 'order_date',
                    'fieldName' => __('Order Date', 'bit-pi'),
                ],
                'Order Status' => (object) [
                    'fieldKey'  => 'order_status',
                    'fieldName' => __('Order Status', 'bit-pi'),
                ],
                'Order Paid Amount' => (object) [
                    'fieldKey'  => 'order_paid_amount',
                    'fieldName' => __('Order Paid Amount', 'bit-pi'),
                ],
                'Payment Currency' => (object) [
                    'fieldKey'  => 'payment_currency',
                    'fieldName' => __('Payment Currency', 'bit-pi'),
                ],
                'Payment Method' => (object) [
                    'fieldKey'  => 'payment_method',
                    'fieldName' => __('Payment Method', 'bit-pi'),
                ],
                'customer_id' => (object) [
                    'fieldKey'  => 'customer_id',
                    'fieldName' => __('Customer Id', 'bit-pi'),
                ],
                'Subscriptions Id' => (object) [
                    'fieldKey'  => 'subscriptions_id',
                    'fieldName' => __('Subscriptions Id', 'bit-pi'),
                ],
            ];
        } elseif ($id == 2 || $id == 3) {
            $fields = [
                'Store Name' => (object) [
                    'fieldKey'  => 'store_name',
                    'fieldName' => __('Store Name', 'bit-pi'),
                ],
                'Store Url' => (object) [
                    'fieldKey'  => 'store_url',
                    'fieldName' => __('Store Url', 'bit-pi'),
                ],
                'Purchase Id' => (object) [
                    'fieldKey'  => 'purchase_id',
                    'fieldName' => __('Purchase Id', 'bit-pi'),
                ],
                'Revoke Date' => (object) [
                    'fieldKey'  => 'revoke_date',
                    'fieldName' => __('Revoke Date', 'bit-pi'),
                ],
                'Customer Id' => (object) [
                    'fieldKey'  => 'customer_id',
                    'fieldName' => __('Customer Id', 'bit-pi'),
                ],
                'Product Id' => (object) [
                    'fieldKey'  => 'product_id',
                    'fieldName' => __('Product Id', 'bit-pi'),
                ],
                'Product Description' => (object) [
                    'fieldKey'  => 'product_description',
                    'fieldName' => __('Product Description', 'bit-pi'),
                ],
                'Product_name' => (object) [
                    'fieldKey'  => 'product_name',
                    'fieldName' => __('Product Name', 'bit-pi'),
                ],
                'Product Image_id' => (object) [
                    'fieldKey'  => 'product_image_id',
                    'fieldName' => __('Product Image Id', 'bit-pi'),
                ],
                'Product Price' => (object) [
                    'fieldKey'  => 'product_price',
                    'fieldName' => __('Product Price', 'bit-pi'),
                ],
                'Product Currency' => (object) [
                    'fieldKey'  => 'product_currency',
                    'fieldName' => __('Product Currency', 'bit-pi'),
                ],
            ];
        }

        return $fields;
    }

    public static function sureCartDataProcess($data)
    {
        $accountDetails = Account::find();
        $product = Product::find($data['product_id']);
        $customer = Customer::find($data['customer_id']);
        $purchaseFinalData = self::purchaseDataProcess($data['id']);

        return [
            'store_name'          => $accountDetails['name'],
            'store_url'           => $accountDetails['url'],
            'product_name'        => $product['name'],
            'product_id'          => $product['id'],
            'product_description' => $product['description'],
            'product_thumb_id'    => $purchaseFinalData['product_thumb_id'],
            'product_thumb'       => $purchaseFinalData['product_thumb'],
            'product_price'       => $product->price,
            'product_price_id'    => $purchaseFinalData['product_price_id'],
            'product_quantity'    => $data['quantity'],
            'max_price_amount'    => $product['metrics']->max_price_amount,
            'min_price_amount'    => $product['metrics']->min_price_amount,
            'order_id'            => $purchaseFinalData['order_id'],
            'payment_currency'    => $accountDetails['currency'],
            'payment_method'      => $purchaseFinalData['payment_method'],
            'subscriptions_id'    => $purchaseFinalData['subscriptions_id'],
            'order_number'        => $purchaseFinalData['order_number'],
            'order_date'          => $purchaseFinalData['order_date'],
            'order_status'        => $purchaseFinalData['order_status'],
            'order_paid_amount'   => $purchaseFinalData['order_paid_amount'],
            'order_subtotal'      => $purchaseFinalData['order_subtotal'],
            'order_total'         => $purchaseFinalData['order_total'],
            'customer_id'         => $customer['id'],
            'customer_name'       => $customer['name'],
            'customer_first_name' => $customer['first_name'],
            'customer_last_name'  => $customer['last_name'],
            'customer_email'      => $customer['email'],
            'customer_phone'      => $customer['phone'],
            'custom_fields'       => $purchaseFinalData['metadata']
        ];
    }

    public static function purchaseDataProcess($id)
    {
        $purchaseData = self::purchaseDetails($id);
        $price = self::getPrice($purchaseData);
        $chekout = $purchaseData->initial_order->checkout;

        return [
            'product'           => $purchaseData->product->name,
            'product_id'        => $purchaseData->product->id,
            'product_thumb_id'  => isset($purchaseData->product->image) ? $purchaseData->product->image : '',
            'product_thumb'     => isset($purchaseData->product->image_url) ? $purchaseData->product->image_url : '',
            'product_price_id'  => isset($price->id) ? $price->id : '',
            'order_id'          => $purchaseData->initial_order->id,
            'subscription_id'   => isset($purchaseData->subscription->id) ? $purchaseData->subscription->id : '',
            'order_number'      => $purchaseData->initial_order->number,
            'order_date'        => date(get_option('date_format', 'F j, Y'), $purchaseData->initial_order->created_at),
            'order_status'      => $purchaseData->initial_order->status,
            'order_paid_amount' => self::formatAmount($chekout->charge->amount),
            'order_subtotal'    => self::formatAmount($chekout->subtotal_amount),
            'order_total'       => self::formatAmount($chekout->total_amount),
            'payment_method'    => isset($chekout->payment_method->processor_type) ? $chekout->payment_method->processor_type : '',
            'metadata'          => empty($chekout->metadata) ? [] : (array) $chekout->metadata
        ];
    }

    public static function purchaseDetails($id)
    {
        return Purchase::with(
            [
                'initial_order',
                'order.checkout',
                'checkout.shipping_address',
                'checkout.payment_method',
                'checkout.discount',
                'discount.coupon',
                'checkout.charge',
                'product',
                'product.downloads',
                'download.media',
                'license.activations',
                'line_items',
                'line_item.price',
                'subscription',
            ]
        )->find($id);
    }

    public static function getPrice($purchaseData)
    {
        if (empty($purchaseData->line_items->data[0])) {
            return;
        }

        $lineItem = $purchaseData->line_items->data[0];

        return $lineItem->price;
    }

    public static function formatAmount($amount)
    {
        return $amount / 100;
    }
}
