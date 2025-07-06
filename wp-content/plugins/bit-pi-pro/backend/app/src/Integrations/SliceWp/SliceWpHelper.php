<?php

namespace BitApps\PiPro\src\Integrations\SliceWp;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class SliceWpHelper
{
    public static function getSliceWpNewAffiliateField()
    {
        return [
            'affiliate_id'  => (object) [
                'fieldKey'  => 'affiliate_id',
                'fieldName' => __('Affiliate ID', 'bit-pi')
            ],
            'user_id'       => (object) [
                'fieldKey'  => 'user_id',
                'fieldName' => __('User Id', 'bit-pi')
            ],
            'payment_email' => (object) [
                'fieldKey'  => 'payment_email',
                'fieldName' => __('Payment Email', 'bit-pi')
            ],
            'website'       => (object) [
                'fieldKey'  => 'website',
                'fieldName' => __('Website URL', 'bit-pi')
            ],
            'date_created'  => (object) [
                'fieldKey'  => 'date_created',
                'fieldName' => __('Date Created', 'bit-pi')
            ],
            'status'        => (object) [
                'fieldKey'  => 'status',
                'fieldName' => __('Status', 'bit-pi')
            ],
        ];
    }

    public static function getCommissionField()
    {
        return [
            'commission_id' => (object) [
                'fieldKey'  => 'commission_id',
                'fieldName' => __('Commission ID', 'bit-pi')
            ],
            'affiliate_id'  => (object) [
                'fieldKey'  => 'affiliate_id',
                'fieldName' => __('Affiliate ID', 'bit-pi')
            ],
            'date_created'  => (object) [
                'fieldKey'  => 'date_created',
                'fieldName' => __('Date Created', 'bit-pi')
            ],
            'amount'        => (object) [
                'fieldKey'  => 'amount',
                'fieldName' => __('Amount', 'bit-pi')
            ],
            'reference'     => (object) [
                'fieldKey'  => 'reference',
                'fieldName' => __('Reference', 'bit-pi')
            ],
            'origin'        => (object) [
                'fieldKey'  => 'origin',
                'fieldName' => __('Origin', 'bit-pi')
            ],
            'type'          => (object) [
                'fieldKey'  => 'type',
                'fieldName' => __('Type', 'bit-pi')
            ],
            'status'        => (object) [
                'fieldKey'  => 'status',
                'fieldName' => __('Status', 'bit-pi')
            ],
            'currency'      => (object) [
                'fieldKey'  => 'currency',
                'fieldName' => __('Currency', 'bit-pi')
            ],
        ];
    }

    public static function getUserField()
    {
        return [
            'User ID'    => (object) [
                'fieldKey'  => 'user_id',
                'fieldName' => __('User Id', 'bit-pi')
            ],
            'First Name' => (object) [
                'fieldKey'  => 'first_name',
                'fieldName' => __('First Name', 'bit-pi')
            ],
            'Last Name'  => (object) [
                'fieldKey'  => 'last_name',
                'fieldName' => __('Last Name', 'bit-pi')
            ],
            'Nick Name'  => (object) [
                'fieldKey'  => 'nickname',
                'fieldName' => __('Nick Name', 'bit-pi')
            ],
            'Avatar URL' => (object) [
                'fieldKey'  => 'avatar_url',
                'fieldName' => __('Avatar URL', 'bit-pi')
            ],
            'Email'      => (object) [
                'fieldKey'  => 'user_email',
                'fieldName' => __('Email', 'bit-pi')
            ],
        ];
    }
}
