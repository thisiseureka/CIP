<?php

namespace BitApps\PiPro\src\Integrations\Asgaros;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use AsgarosForum;

class AsgarosHelper
{
    public static function userCreatesNewTopicInForumFormatFields($postId, $topicId)
    {
        if (!class_exists('AsgarosForum')) {
            return;
        }

        $forum = new AsgarosForum();

        if (!isset($postId)) {
            return;
        }

        $data = static::setForumFields($forum, $topicId, $postId);

        return self::prepareFetchFormatFields($data);
    }

    public static function userRepliesToTopicInForumFormatFields($postId, $topicId)
    {
        if (!class_exists('AsgarosForum')) {
            return;
        }

        $forum = new AsgarosForum();

        if (!isset($postId)) {
            return;
        }

        $data = static::setForumFields($forum, $topicId, $postId);

        return self::prepareFetchFormatFields($data);
    }

    public static function isPluginInstalled()
    {
        return class_exists('AsgarosForum');
    }

    public static function prepareFetchFormatFields(array $data, $path = '', $formattedData = [])
    {
        foreach ($data as $key => $value) {
            if (ctype_upper($key)) {
                $key = strtolower($key);
            }

            $currentKey = strtolower(preg_replace(['/[^A-Za-z0-9_]/', '/([A-Z])/'], ['', '_$1'], $key));

            $currentPath = $path ? "{$path}_{$currentKey}" : $currentKey;

            if (\is_array($value) || \is_object($value)) {
                $formattedData = static::prepareFetchFormatFields((array) $value, $currentPath, $formattedData);
            } else {
                $labelValue = \is_string($value) && \strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value;
                $label = ucwords(str_replace('_', ' ', $path ? $currentPath : $key));
                $label = preg_replace('/\b(\w+)\s+\1\b/i', '$1', $label) . ' (' . $labelValue . ')';

                $formattedData[$currentPath] = [
                    'name'  => $currentPath . '.value',
                    'type'  => static::getVariableType($value),
                    'label' => $label,
                    'value' => $value,
                ];
            }
        }

        return $formattedData;
    }

    private static function setForumFields($forum, $topicId, $postId)
    {
        $topic = $forum->content->get_topic($topicId);
        $forumId = $topic->parent_id;

        return [
            'topic_id' => $topicId,
            'post_id'  => $postId,
            'forum_id' => $forumId,
            'forum'    => $forum->content->get_forum($forumId),
            'topic'    => $forum->content->get_topic($topicId),
            'post'     => $forum->content->get_post($postId),
            // 'author'   => User::get($authorId),
        ];
    }

    private static function getVariableType($val)
    {
        $types = [
            'boolean'           => 'boolean',
            'integer'           => 'number',
            'double'            => 'number',
            'string'            => 'text',
            'array'             => 'array',
            'resource (closed)' => 'file',
            'NULL'              => 'textarea',
            'unknown type'      => 'unknown'
        ];

        return $types[\gettype($val)] ?? 'text';
    }
}
