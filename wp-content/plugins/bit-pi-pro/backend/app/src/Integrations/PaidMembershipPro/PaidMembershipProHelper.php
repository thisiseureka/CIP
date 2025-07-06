<?php

namespace BitApps\PiPro\src\Integrations\PaidMembershipPro;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class PaidMembershipProHelper
{
    public static function getPaidMembershipProField()
    {
        return [
            'id'              => (object) [
                'fieldKey'  => 'id',
                'fieldName' => __('Membership ID', 'bit-pi')
            ],
            'name'            => (object) [
                'fieldKey'  => 'name',
                'fieldName' => __('Name', 'bit-pi')
            ],
            'description'     => (object) [
                'fieldKey'  => 'description',
                'fieldName' => __('Description', 'bit-pi')
            ],
            'confirmation'    => (object) [
                'fieldKey'  => 'confirmation',
                'fieldName' => __('Confirmation', 'bit-pi')
            ],
            'initial_payment' => (object) [
                'fieldKey'  => 'initial_payment',
                'fieldName' => __('Initial Payment', 'bit-pi')
            ],
            'billing_amount'  => (object) [
                'fieldKey'  => 'billing_amount',
                'fieldName' => __('Billing Amount', 'bit-pi')
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
