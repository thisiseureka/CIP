<?php

namespace BitApps\PiPro\src\Integrations\Affiliate;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class AffiliateHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'newAffiliateApproval' => [
                'hook'          => 'affwp_set_affiliate_status',
                'callback'      => [AffiliateTrigger::class, 'newAffiliateApproved'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'userAffiliate' => [
                'hook'          => 'affwp_set_affiliate_status',
                'callback'      => [AffiliateTrigger::class, 'userBecomesAffiliate'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'affiliateReferral' => [
                'hook'          => 'affwp_insert_referral',
                'callback'      => [AffiliateTrigger::class, 'affiliateMakesReferral'],
                'priority'      => 20,
                'accepted_args' => 1,
            ],
            'referralPayment' => [
                'hook'          => 'affwp_set_referral_status',
                'callback'      => [AffiliateTrigger::class, 'affiliatesReferralSpecificTypePaid'],
                'priority'      => 99,
                'accepted_args' => 3,
            ],
            'referralRejection' => [
                'hook'          => 'affwp_set_referral_status',
                'callback'      => [AffiliateTrigger::class, 'affiliatesReferralSpecificTypeRejected'],
                'priority'      => 99,
                'accepted_args' => 3,
            ],
        ];
    }
}
