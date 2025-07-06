<?php

namespace BitApps\PiPro\src\Integrations\SureCart;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Config;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use SureCart\Models\Account;
use SureCart\Models\Product;

final class SureCartTrigger
{
    public static function pluginActive()
    {
        return (bool) (is_plugin_active('surecart/surecart.php'));
    }

    public static function getAllProduct()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'SureCart'));
        }

        $allProducts = Product::get();

        $products = [
            [
                'value' => 'any',
                'label' => 'Any Product',
            ],
        ];

        if (is_wp_error($allProducts)) {
            return $products;
        }

        foreach ($allProducts as $product) {
            $products[] = [
                'value' => $product->id,
                'label' => $product->name,
            ];
        }

        return $products;
    }

    public static function sureCartPurchaseProduct($data)
    {
        if (!self::pluginActive()) {
            return Response::error(__('SureCart is not installed or activated', 'bit-pi'));
        }

        $transientKey = Config::VAR_PREFIX . 'surecart_purchase_created_' . $data['id'];

        $finalData = SureCartHelper::sureCartDataProcess($data);

        $flows = FlowService::exists('sureCart', 'purchaseCreated');

        if (!$flows || get_transient($transientKey)) {
            return;
        }

        set_transient($transientKey, true, 5);

        IntegrationHelper::handleFlowForForm($flows, $finalData, $data['product_id'], 'product-id');
    }

    public static function getSureCartAllProducts()
    {
        $allProduct = self::getAllProduct();

        return Response::success($allProduct);
    }

    public static function sureCartPurchaseRevoked($data)
    {
        $accountDetails = Account::find();
        $finalData = [
            'store_name'          => $accountDetails['name'],
            'store_url'           => $accountDetails['url'],
            'purchase_id'         => $data->id,
            'revoke_date'         => $data->revoked_at,
            'customer_id'         => $data->customer,
            'product_id'          => $data->product->id,
            'product_description' => $data->product->description,
            'product_name'        => $data->product->name,
            'product_image_id'    => $data->product->image,
            'product_price'       => ($data->product->prices->data[0]->full_amount) / 100,
            'product_currency'    => $data->product->prices->data[0]->currency,

        ];

        $flows = FlowService::exists('sureCart', 'purchaseRevoked');
        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $data->product->id, 'product-id');
    }

    public static function sureCartPurchaseUnrevoked($data)
    {
        $accountDetails = Account::find();

        $finalData = [
            'store_name'          => $accountDetails['name'],
            'store_url'           => $accountDetails['url'],
            'purchase_id'         => $data->id,
            'revoke_date'         => $data->revoked_at,
            'customer_id'         => $data->customer,
            'product_id'          => $data->product->id,
            'product_description' => $data->product->description,
            'product_name'        => $data->product->name,
            'product_image_id'    => $data->product->image,
            'product_price'       => ($data->product->prices->data[0]->full_amount) / 100,
            'product_currency'    => $data->product->prices->data[0]->currency,
        ];

        $flows = FlowService::exists('sureCart', 'purchaseInvoked');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $data->product->id, 'product-id');
    }
}
