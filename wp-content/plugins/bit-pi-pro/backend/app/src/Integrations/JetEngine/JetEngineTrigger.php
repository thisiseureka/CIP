<?php

namespace BitApps\PiPro\src\Integrations\JetEngine;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class JetEngineTrigger
{
    public static function handlePostMetaUpdate($_metaId, $postId, $metaKey, $metaValue)
    {
        $postData = get_post($postId);
        $finalData = (array) $postData + ['meta_key' => $metaKey, 'meta_value' => $metaValue];
        $postData = get_post($postId);
        $flows = FlowService::exists('jetEngine', 'postMetaData');

        if (!$flows) {
            return;
        }

        $postType = $postData->post_type;

        IntegrationHelper::handleFlowForForm($flows, $finalData, $postType, 'post-type-id');
    }

    public static function getAllPostTypes()
    {
        $types = array_values(JetEngineHelper::getPostTypes());
        array_unshift($types, ['value' => 'any-post-type', 'label' => __('Any Post Type', 'bit-pi')]);

        return Response::success($types);
    }

    public static function getAllPosts()
    {
        $posts = JetEngineHelper::getPostTitles();
        array_unshift($posts, ['value' => 'any', 'label' => __('Any Post', 'bit-pi')]);

        return Response::success($posts);
    }
}
