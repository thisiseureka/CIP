<?php

namespace BitApps\PiPro\src\Integrations\SliceWp;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class SliceWpTrigger
{
    public static function pluginActive()
    {
        return (bool) (is_plugin_active('slicewp/index.php'));
    }

    public static function commissionType()
    {
        foreach (slicewp_get_commission_types() as $typeId => $type) {
            $commissionTypes[] = [
                'value' => $typeId,
                'label' => $type['label'],
            ];
        }

        return array_merge($commissionTypes, [['value' => 'any', 'label' => 'Any']]);
    }

    public static function newAffiliateCreated($affiliateId, $affiliateData)
    {
        $userData = Utility::getUserInfo($affiliateData['user_id']);
        $finalData = $affiliateData + $userData + ['affiliate_id' => $affiliateId];

        $flows = FlowService::exists('sliceWp', 'newAffiliate');

        if (!$affiliateData['user_id'] || !$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData);
    }

    public static function userEarnCommission($commissionId, $commissionData)
    {
        $finalData = $commissionData + ['commission_id' => $commissionId];

        $flows = FlowService::exists('sliceWp', 'userEarnsCommission');

        IntegrationHelper::handleFlowForForm($flows, $finalData, $commissionData['type'], 'commission-type-id');
    }

    public static function allCommissionType()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'SliceWp affiliate'));
        }

        $commissionType = self::commissionType();

        return Response::success($commissionType);
    }
}
