<?php

namespace BitApps\PiPro\src\Integrations\GamiPress;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class GamiPressTrigger
{
    public static function handleUserEarnRank($userId, $newRank)
    {
        $flows = FlowService::exists(GamiPressTasks::APP_SLUG, GamiPressTasks::USER_EARN_RANK);

        if (!$flows) {
            return;
        }

        $userData = Utility::getUserInfo($userId);

        $newRankData = [
            'rank_type' => $newRank->post_type,
            'rank'      => $newRank->post_name,
        ];

        $data = array_merge($userData, $newRankData);

        IntegrationHelper::handleFlowForForm($flows, $data, $newRank->post_name, 'rank-id', 'string');
    }

    public static function handleAwardAchievement($userId, $achievementId)
    {
        $flows = FlowService::exists(GamiPressTasks::APP_SLUG, GamiPressTasks::AWARD_ACHIEVEMENT);

        if (!$flows) {
            return;
        }

        global $wpdb;

        $awards = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_name, post_title, post_type FROM {$wpdb->posts} where id = %d",
                $achievementId
            )
        );

        $userData = Utility::getUserInfo($userId);

        $awardData = [
            'achievement_type' => $awards[0]->post_type,
            'award'            => $awards[0]->post_name,
        ];

        $data = array_merge($userData, $awardData);

        IntegrationHelper::handleFlowForForm($flows, $data, $awards[0]->post_name, 'achievement-type');
    }

    public static function handleGainAchievementType($userId, $achievementId)
    {
        $flows = FlowService::exists(GamiPressTasks::APP_SLUG, GamiPressTasks::GAIN_ACHIEVEMENT_TYPE);

        if (!$flows) {
            return;
        }

        $postData = get_post($achievementId);

        $data = [
            'post_id'        => $achievementId,
            'post_title'     => $postData->post_title,
            'post_url'       => get_permalink($achievementId),
            'post_type'      => $postData->post_type,
            'post_author_id' => $postData->post_author,
            // 'post_author_email' => $postData->post_author_email,
            'post_content'   => $postData->post_content,
            'post_parent_id' => $postData->post_parent,
        ];

        IntegrationHelper::handleFlowForForm($flows, $data, $postData->post_type, 'achievement-type');
    }

    public static function handleRevokeAchieve($userId, $achievementId)
    {
        $flows = FlowService::exists(GamiPressTasks::APP_SLUG, GamiPressTasks::REVOKE_ACHIEVEMENT);

        if (!$flows) {
            return;
        }

        $postData = get_post($achievementId);

        $expectedData = get_post($postData->post_parent);

        $data = [
            'post_id'        => $achievementId,
            'post_title'     => empty($expectedData->post_title) ? '' : $expectedData->post_title,
            'post_url'       => get_permalink($achievementId),
            'post_type'      => isset($expectedData->post_type),
            'post_author_id' => isset($expectedData->post_author),
            // 'post_author_email' => $postData->post_author_email,
            'post_content'   => isset($expectedData->post_content),
            'post_parent_id' => isset($expectedData->post_parent),
        ];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function handleEarnPoints($userId, $newPoints, $totalPoints, $adminId, $achievementId, $pointsType)
    {
        $flows = FlowService::exists(GamiPressTasks::APP_SLUG, GamiPressTasks::EARN_POINTS);

        if (!$flows) {
            return;
        }

        $userData = Utility::getUserInfo($userId);

        unset($userData['user_url']);

        $pointData = [
            'total_points' => $totalPoints,
            'new_points'   => $newPoints,
            'points_type'  => $pointsType,
        ];

        $data = array_merge($userData, $pointData);

        IntegrationHelper::handleFlowForForm($flows, $data, $totalPoints, 'achievement-type');
    }
}
