<?php

namespace BitApps\PiPro\src\Integrations\SliceWp;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class SliceWpHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'newAffiliate' => [
                'hook'          => 'slicewp_insert_affiliate',
                'callback'      => [SliceWpTrigger::class, 'newAffiliateCreated'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'userEarnsCommission' => [
                'hook'          => 'slicewp_insert_commission',
                'callback'      => [SliceWpTrigger::class, 'userEarnCommission'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
