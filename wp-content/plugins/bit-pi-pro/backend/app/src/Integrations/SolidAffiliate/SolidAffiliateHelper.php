<?php

namespace BitApps\PiPro\src\Integrations\SolidAffiliate;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class SolidAffiliateHelper
{
    public static function getAffiliateField()
    {
        return [
            'user_id'            => (object) [
                'fieldKey'  => 'user_id',
                'fieldName' => __('User Id', 'bit-pi'),
            ],
            'first_name'         => (object) [
                'fieldKey'  => 'first_name',
                'fieldName' => __('First Name', 'bit-pi'),
            ],

            'last_name'          => (object) [
                'fieldKey'  => 'last_name',
                'fieldName' => __('Last Name', 'bit-pi'),
            ],
            'commission_type'    => (object) [
                'fieldKey'  => 'commission_type',
                'fieldName' => __('Commission Type', 'bit-pi'),
            ],
            'commission_rate'    => (object) [
                'fieldKey'  => 'commission_rate',
                'fieldName' => __('Commission Rate', 'bit-pi'),
            ],
            'payment_email'      => (object) [
                'fieldKey'  => 'payment_email',
                'fieldName' => __('Payment Email', 'bit-pi'),
            ],
            'mailchimp_user_id'  => (object) [
                'fieldKey'  => 'mailchimp_user_id',
                'fieldName' => __('Mailchimp User ID', 'bit-pi'),
            ],
            'affiliate_group_id' => (object) [
                'fieldKey'  => 'affiliate_group_id',
                'fieldName' => __('Affiliate Group ID', 'bit-pi'),
            ],
            'registration_notes' => (object) [
                'fieldKey'  => 'registration_notes',
                'fieldName' => __('Registration Notes', 'bit-pi'),
            ],
            'status'             => (object) [
                'fieldKey'  => 'status',
                'fieldName' => __('Status', 'bit-pi'),
            ],
            'created_at'         => (object) [
                'fieldKey'  => 'created_at',
                'fieldName' => __('Created At', 'bit-pi'),
            ],
            'updated_at'         => (object) [
                'fieldKey'  => 'updated_at',
                'fieldName' => __('Updated At', 'bit-pi'),
            ],

        ];
    }

    public static function getReferralAffiliateField()
    {
        return [
            'affiliate_id'      => (object) [
                'fieldKey'  => 'affiliate_id',
                'fieldName' => __('Affiliate ID', 'bit-pi'),
            ],
            'order_amount'      => (object) [
                'fieldKey'  => 'order_amount',
                'fieldName' => __('Order Amount', 'bit-pi'),
            ],
            'commission_amount' => (object) [
                'fieldKey'  => 'commission_amount',
                'fieldName' => __('Commission Amount', 'bit-pi'),
            ],
            'referral_source'   => (object) [
                'fieldKey'  => 'referral_source',
                'fieldName' => __('Referral Source', 'bit-pi'),
            ],
            'visit_id'          => (object) [
                'fieldKey'  => 'visit_id',
                'fieldName' => __('Visit ID', 'bit-pi'),
            ],
            'coupon_id'         => (object) [
                'fieldKey'  => 'coupon_id',
                'fieldName' => __('Coupon ID', 'bit-pi'),
            ],
            'customer_id'       => (object) [
                'fieldKey'  => 'customer_id',
                'fieldName' => __('Customer Id', 'bit-pi'),
            ],
            'referral_type'     => (object) [
                'fieldKey'  => 'referral_type',
                'fieldName' => __('Referral Type', 'bit-pi'),
            ],
            'description'       => (object) [
                'fieldKey'  => 'description',
                'fieldName' => __('Description', 'bit-pi'),
            ],
            'order_source'      => (object) [
                'fieldKey'  => 'order_source',
                'fieldName' => __('Order Source', 'bit-pi'),
            ],
            'order_id'          => (object) [
                'fieldKey'  => 'order_id',
                'fieldName' => __('Order ID', 'bit-pi'),
            ],
            'payout_id'         => (object) [
                'fieldKey'  => 'payout_id',
                'fieldName' => __('Payout ID', 'bit-pi'),
            ],
            'status'            => (object) [
                'fieldKey'  => 'status',
                'fieldName' => __('Status', 'bit-pi'),
            ],
            'created_at'        => (object) [
                'fieldKey'  => 'created_at',
                'fieldName' => __('Created At', 'bit-pi'),
            ],
            'updated_at'        => (object) [
                'fieldKey'  => 'updated_at',
                'fieldName' => __('Updated At', 'bit-pi'),
            ]
        ];
    }
}
