<?php

namespace BitApps\PiPro\src\Integrations\SolidAffiliate;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SolidAffiliateHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'newSolidAffiliate' => [
                'hook'          => 'data_model_solid_affiliate_affiliates_save',
                'callback'      => [SolidAffiliateTrigger::class, 'newSolidAffiliateCreated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'newAffiliateReferral' => [
                'hook'          => 'data_model_solid_affiliate_referrals_save',
                'callback'      => [SolidAffiliateTrigger::class, 'newSolidAffiliateReferralCreated'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
