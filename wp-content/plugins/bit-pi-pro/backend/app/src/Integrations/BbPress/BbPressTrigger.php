<?php

namespace BitApps\PiPro\src\Integrations\BbPress;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class BbPressTrigger
{
    public static function handleTopicCreated($topicId, $forumId, $anonymousData, $topicAuthor)
    {
        if (empty($topicId)) {
            return;
        }

        $formData = BbPressHelper::formatData($forumId, $topicId, null, $anonymousData, $topicAuthor);

        return self::execute($formData, 'topicCreated');
    }

    public static function handleTopicReplied($replyId, $topicId, $forumId)
    {
        if (empty($replyId) || empty($topicId)) {
            return;
        }

        $formData = BbPressHelper::formatData($forumId, $topicId, $replyId);

        return self::execute($formData, 'topicReplied');
    }

    private static function execute($formData, $machineSlug)
    {
        $flows = FlowService::exists('BbPress', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('bbPress');
    }
}
