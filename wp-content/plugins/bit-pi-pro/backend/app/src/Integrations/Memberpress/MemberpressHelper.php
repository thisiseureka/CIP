<?php

namespace BitApps\PiPro\src\Integrations\Memberpress;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

final class MemberpressHelper
{
    public static function isPluginActive()
    {
        return (bool) (is_plugin_active(MemberpressTasks::MEMBERPRESS_PLUGIN_INDEX));
    }

    public static function getAllMembership()
    {
        if (!self::isPluginActive()) {
            return Response::error('Memberpress plugin is not active');
        }

        $allMemberships = [
            [
                'label' => 'Any Membership',
                'value' => 'any'
            ],
        ];

        $memberpressProducts = get_posts(
            [
                'post_type'      => MemberpressTasks::MEMBERPRESS_POST_TYPE,
                'posts_per_page' => 999,
                'post_status'    => 'publish',
                'meta_query'     => [
                    'relation' => 'OR',
                    [
                        'key'     => '_mepr_product_period_type',
                        'value'   => 'lifetime',
                        'compare' => '!=',
                    ],
                    [
                        'key'     => '_mepr_product_period_type',
                        'value'   => 'lifetime',
                        'compare' => '=',
                    ],
                ],
            ]
        );

        foreach ($memberpressProducts as $memberpressProduct) {
            $allMemberships[] = [
                'value' => $memberpressProduct->ID,
                'label' => $memberpressProduct->post_title,
            ];
        }

        return Response::success($allMemberships);
    }

    public static function getAllOnetimeMembership()
    {
        if (!self::isPluginActive()) {
            return Response::error('Memberpress plugin is not active');
        }

        $allOnetimeMemberships = [
            [
                'label' => 'Any One Time Membership',
                'value' => 'any'
            ],
        ];

        $onetimeMemberships = get_posts(
            [
                'post_type'      => MemberpressTasks::MEMBERPRESS_POST_TYPE,
                'posts_per_page' => 20,
                'post_status'    => 'publish',
                'meta_query'     => [
                    [
                        'key'     => '_mepr_product_period_type',
                        'value'   => 'lifetime',
                        'compare' => '=',
                    ],
                ]
            ]
        );

        foreach ($onetimeMemberships as $onetimeMembership) {
            $allOnetimeMemberships[] = [
                'value' => $onetimeMembership->ID,
                'label' => $onetimeMembership->post_title,
            ];
        }

        return Response::success($allOnetimeMemberships);
    }

    public static function getAllRecurringMembership()
    {
        if (!self::isPluginActive()) {
            return Response::error('Memberpress plugin is not active');
        }

        $allRecurringSubscriptions = [
            [
                'label' => 'Any Recurring Membership',
                'value' => 'any'
            ],
        ];

        $recurringSubscriptions = get_posts(
            [
                'post_type'      => MemberpressTasks::MEMBERPRESS_POST_TYPE,
                'posts_per_page' => 20,
                'post_status'    => 'publish',
                'meta_query'     => [
                    [
                        'key'     => '_mepr_product_period_type',
                        'value'   => 'lifetime',
                        'compare' => '!=',
                    ],
                ]
            ]
        );

        foreach ($recurringSubscriptions as $recurringSubscription) {
            $allRecurringSubscriptions[] = [
                'value' => $recurringSubscription->ID,
                'label' => $recurringSubscription->post_title,
            ];
        }

        return Response::success($allRecurringSubscriptions);
    }
}
