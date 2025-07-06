<?php

namespace BitApps\PiPro\src\Integrations\SolidAffiliate;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class SolidAffiliateTrigger
{
    public static function pluginActive()
    {
        return (bool) (is_plugin_active('solid_affiliate/plugin.php'));
    }

    public static function newSolidAffiliateCreated($affiliate)
    {
        $attributes = $affiliate->__get('attributes');

        $flows = FlowService::exists('solidAffiliate', 'newSolidAffiliate');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $attributes);
    }

    public static function newSolidAffiliateReferralCreated($referralAccepted)
    {
        $affiliateReferralData = $referralAccepted->__get('attributes');

        $flows = FlowService::exists('solidAffiliate', 'newAffiliateReferral');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $affiliateReferralData);
    }
}
