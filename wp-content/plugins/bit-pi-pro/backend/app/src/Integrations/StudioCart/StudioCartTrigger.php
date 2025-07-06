<?php

namespace BitApps\PiPro\src\Integrations\StudioCart;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class StudioCartTrigger
{
    private static $actions = [
        'newOrderCreated' => [
            'id'    => 2,
            'title' => 'New Order Created'
        ],
    ];

    public static function pluginActive($option = null)
    {
        if (is_plugin_active('studiocart-pro/studiocart.php')) {
            return $option === 'get_name' ? 'studiocart-pro/studiocart.php' : true;
        }

        if (is_plugin_active('studiocart/studiocart.php')) {
            return $option === 'get_name' ? 'studiocart/studiocart.php' : true;
        }

        return false;
    }

    public static function newOrderCreated($_status, $orderData)
    {
        $flows = FlowService::exists('studioCart', 'orderCompletion');

        if (!$flows) {
            return;
        }

        $data = [];
        foreach ($orderData as $key => $fieldValue) {
            $data[$key] = $fieldValue;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public function getAll()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Studiocart'));
        }

        $scActions = [];

        foreach (self::$actions as $action) {
            $scActions[] = (object) [
                'value' => $action['id'],
                'label' => $action['title'],
            ];
        }

        return Response::success($scActions);
    }
}
