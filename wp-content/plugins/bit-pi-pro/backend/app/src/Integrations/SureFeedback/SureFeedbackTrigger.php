<?php

namespace BitApps\PiPro\src\Integrations\SureFeedback;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use PH\Models\Post;

final class SureFeedbackTrigger
{
    public static function handleInsertComment($comment, $request, $creating)
    {
        if (empty($creating) || !class_exists('PH\Models\Post') || !\function_exists('ph_get_the_title')) {
            return;
        }

        $commentData = \is_object($comment) ? get_object_vars($comment) : $comment;
        $commentData['website_id'] = !empty($commentData['project_id']) ? (int) $commentData['project_id'] : '';
        $commentItemId = self::getCommentItemId($commentData['comment_ID']);

        $formData = [
            'comment'  => self::formatCommentData($commentData, $commentItemId),
            'request'  => $request,
            'creating' => $creating,
        ];

        return self::execute($formData, 'insertComment');
    }

    public static function handleCommentResolved($attr, $value, $comment)
    {
        if ($attr !== 'resolved' || empty($value) || !class_exists('PH\Models\Post') || !\function_exists('ph_get_the_title')) {
            return;
        }

        if (\is_object($comment)) {
            $comment = get_object_vars($comment);
        }

        global $wpdb;
        $commentResult = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT  comment_ID FROM {$wpdb->prefix}comments  WHERE comment_post_ID = %d LIMIT 1",
                $comment['ID']
            ),
            ARRAY_A
        );

        $commentData = $comment;
        $commentId = $commentResult['comment_ID'];
        $commentData['website_id'] = get_comment_meta($commentId, 'project_id', true) ?? '';

        $commentItemId = self::getCommentItemId($commentId);
        $formData = self::formatCommentData($commentData, $commentItemId);

        return self::execute($formData, 'commentResolved');
    }

    private static function formatCommentData($commentData, $commentItemId)
    {
        $itemData = [
            'comment_item_id'         => $commentItemId ?? '',
            'comment_item_page_title' => $commentItemId ? get_the_title($commentItemId) : '',
            'comment_item_page_url'   => $commentItemId ? get_post_meta($commentItemId, 'page_url', true) : ''
        ];

        $postId = $commentData['comment_post_ID'] ?? null;

        if (!$postId) {
            return array_merge(
                $commentData,
                $itemData,
                [
                    'project_name'   => '',
                    'commenter_name' => $commentData['comment_author'] ?? '',
                    'project_type'   => '',
                    'action_status'  => '',
                    'project_link'   => ''
                ]
            );
        }

        $postType = get_post_type($postId);
        $projectType = $postType === 'ph-website' ? __('Website', 'bit-pi') : __('Mockup', 'bit-pi');
        $actionStatus = get_post_meta($postId, 'resolved', true) ? __('Resolved', 'bit-pi') : __('Unresolved', 'bit-pi');

        $projectId = Post::get($postId)->parentsIds()['project'] ?? null;
        $projectName = $projectId ? ph_get_the_title($projectId) : '';
        $projectLink = get_the_guid($postId);

        return array_merge(
            $commentData,
            $itemData,
            [
                'project_name'   => $projectName,
                'commenter_name' => $commentData['comment_author'] ?? '',
                'project_type'   => $projectType,
                'action_status'  => $actionStatus,
                'project_link'   => $projectLink
            ]
        );
    }

    private static function getCommentItemId($commentId)
    {
        $id = get_comment_meta($commentId, 'item_id');

        return \is_array($id) && !empty($id) ? (int) array_shift($id) : null;
    }

    private static function execute($formData, $machineSlug)
    {
        $flows = FlowService::exists('SureFeedback', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('\Project_Huddle');
    }
}
