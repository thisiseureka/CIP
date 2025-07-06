<?php

namespace BitApps\PiPro\src\Integrations\GamiPress;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

class GamiPressHelper
{
    public static function isPluginActive()
    {
        return (bool) (is_plugin_active(GamiPressTasks::GAMIPRESS_PLUGIN_INDEX));
    }

    public static function getAllAwardByAchievementType(Request $request)
    {
        global $wpdb;

        $validatedData = $request->validate(
            [
                'achievementType' => ['required', 'string', 'sanitize:text'],
            ]
        );

        $selectAchievementType = $validatedData['achievementType'];

        $allWardTypes = [
            [
                'label' => 'Any award type',
                'value' => 'any'
            ],
        ];

        $awardTypes = $wpdb->get_results(
            $wpdb->prepare("SELECT ID, post_name, post_title, post_type FROM {$wpdb->posts} where post_type like %s AND post_status = %s", [$selectAchievementType, 'publish'])
        );

        if ($awardTypes) {
            foreach ($awardTypes as $awardType) {
                $allWardTypes[] = [
                    'label' => $awardType->post_title,
                    'value' => $awardType->ID,
                ];
            }
        }

        return Response::success($allWardTypes);
    }

    public static function getAllRankByType(Request $request)
    {
        global $wpdb;

        $validatedData = $request->validate(
            [
                'rankType' => ['required', 'string', 'sanitize:text'],
            ]
        );

        $rankType = $validatedData['rankType'];

        $allRankTypes = [
            [
                'label' => 'Any rank type',
                'value' => 'any'
            ],
        ];

        $ranks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_name, post_title, post_type FROM {$wpdb->posts} where post_type like %s AND post_status = %s",
                [$rankType, 'publish']
            )
        );

        if ($ranks) {
            foreach ($ranks as $rank) {
                $allRankTypes[] = [
                    'label' => $rank->post_title,
                    'value' => $rank->post_name,
                ];
            }
        }

        return Response::success($allRankTypes);
    }

    public static function getAllAchievementType()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'GamiPress'));
        }

        $allAchievementTypes = [
            [
                'label' => 'Any achievement type',
                'value' => 'any'
            ],
        ];

        global $wpdb;

        $achievementTypes = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_name, post_title, post_type FROM {$wpdb->posts} WHERE post_type LIKE %s AND post_status = %s ORDER BY post_title ASC",
                ['achievement-type', 'publish']
            )
        );

        if ($achievementTypes) {
            foreach ($achievementTypes as $achievementType) {
                $allAchievementTypes[] = [
                    'label' => $achievementType->post_title,
                    'value' => $achievementType->post_name,
                ];
            }
        }

        return Response::success($allAchievementTypes);
    }

    public static function getAllRankType()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'GamiPress'));
        }

        $allRankTypes = [
            [
                'label' => 'Any rank type',
                'value' => 'any'
            ],
        ];

        global $wpdb;

        $rankTypes = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_name, post_title, post_type FROM {$wpdb->posts} where post_type like %s AND post_status = %s",
                ['rank_type', 'publish']
            )
        );

        if ($rankTypes) {
            foreach ($rankTypes as $rankType) {
                $allRankTypes[] = [
                    'label' => $rankType->post_title,
                    'value' => $rankType->post_name,
                ];
            }
        }

        return Response::success($allRankTypes);
    }
}
