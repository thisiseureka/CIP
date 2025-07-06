<?php

namespace BitApps\PiPro\src\Integrations\CartFlow;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class CartFlowTrigger
{
    public static function pluginActive($option = null)
    {
        if (is_plugin_active('cartflows/cartflows.php')) {
            return $option === 'get_name' ? 'cartflows/cartflows.php' : true;
        }

        return false;
    }

    public static function handleWcOrderCreate($orderId)
    {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            return false;
        }

        $order = wc_get_order($orderId);
        $metaData = get_post_meta($orderId);
        $finalData = [];
        foreach ($metaData as $key => $value) {
            $finalData[ltrim($key, '_')] = $value[0];
        }

        $finalData['order_products'] = self::accessOrderData($order);
        $finalData['order_id'] = $orderId;
        $checkoutPageId = (int) $metaData['_wcf_checkout_id'][0];

        if (!empty($orderId) && $flows = FlowService::exists('cartFlow', 'orderCreateWc')) {
            IntegrationHelper::handleFlowForForm($flows, $finalData, $checkoutPageId);
        }
    }

    public function getAllForms()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'CartFlows'));
        }

        $posts = $this->getCartFlowPosts();

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];
        if ($posts) {
            foreach ($posts as $form) {
                $allForms[] = (object) [
                    'value' => $form->ID,
                    'label' => $form->post_title,
                    // 'post_id' => $form->ID,
                ];
            }
        }

        return Response::success($allForms);
    }

    public static function accessOrderData($order)
    {
        $lineItemsAll = [];
        $count = 0;
        foreach ($order->get_items() as $item) {
            $productId = $item->get_product_id();
            $variationId = $item->get_variation_id();
            // $product = $item->get_product();
            $productName = $item->get_name();
            $quantity = $item->get_quantity();
            $subtotal = $item->get_subtotal();
            $total = $item->get_total();
            $subtotalTax = $item->get_subtotal_tax();
            $taxclass = $item->get_tax_class();
            $taxstat = $item->get_tax_status();
            // $label = 'line_items_';
            ++$count;
            $lineItemsAll['line_items'][] = (object) [
                'product_id'   => $productId,
                'variation_id' => $variationId,
                'product_name' => $productName,
                'quantity'     => $quantity,
                'subtotal'     => $subtotal,
                'total'        => $total,
                'subtotal_tax' => $subtotalTax,
                'tax_class'    => $taxclass,
                'tax_status'   => $taxstat,
            ];
        }

        return $lineItemsAll;
    }

    private function getCartFlowPosts()
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)
                WHERE {$wpdb->posts}.post_status = 'publish' AND ({$wpdb->posts}.post_type = 'cartflows_step') AND {$wpdb->postmeta}.meta_key = 'wcf_fields_billing'"
            )
        );
    }
}
