<?php

namespace BitApps\PiPro\src\Integrations\BbPress;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Helpers\Utility;

final class BbPressHelper
{
    public static function formatData($forumId, $topicId, $replyId = null, $anonymousData = [], $topicAuthor = null)
    {
        $formData = [
            'topic'          => self::getPostData($topicId, 'topic'),
            'forum'          => self::getPostData($forumId, 'forum'),
            'anonymous_data' => self::getAnonymousData($replyId, $anonymousData),
            'user'           => Utility::getUserInfo(get_current_user_id())
        ];

        if (!empty($replyId)) {
            $formData['topic_reply'] = self::getPostData($replyId, 'reply');
        }

        if (!empty($topicAuthor)) {
            $formData['topic_author'] = Utility::getUserInfo((int) $topicAuthor);
        }

        return $formData;
    }

    private static function getPostData($postId, $type)
    {
        return [
            "{$type}_id"          => $postId,
            "{$type}_title"       => get_the_title($postId) ?? null,
            "{$type}_link"        => get_the_permalink($postId) ?? null,
            "{$type}_description" => get_the_content(null, false, $postId) ?? null,
            "{$type}_status"      => get_post_status($postId) ?? null,
        ];
    }

    private static function getAnonymousData($replyId = null, $anonymousData = [])
    {
        if (empty($anonymousData) && !empty($replyId)) {
            $anonymousData = [
                'bbp_anonymous_name'    => get_post_meta($replyId, '_bbp_anonymous_name', true) ?? null,
                'bbp_anonymous_email'   => get_post_meta($replyId, '_bbp_anonymous_email', true) ?? null,
                'bbp_anonymous_website' => get_post_meta($replyId, '_bbp_anonymous_website', true) ?? null,
            ];
        }

        return $anonymousData;
    }
}
