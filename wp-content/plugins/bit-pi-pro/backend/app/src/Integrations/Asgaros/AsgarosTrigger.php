<?php

namespace BitApps\PiPro\src\Integrations\Asgaros;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class AsgarosTrigger
{
    public static function handleUserCreatesNewTopicInForum($postId, $topicId)
    {
        $formData = AsgarosHelper::userCreatesNewTopicInForumFormatFields($postId, $topicId);

        $flows = FlowService::exists('Asgaros', 'userNewTopic');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }

    public static function handleUserRepliesToTopicInForum($postId, $topicId)
    {
        $formData = AsgarosHelper::userRepliesToTopicInForumFormatFields($postId, $topicId);

        $flows = FlowService::exists('Asgaros', 'userReplyTopic');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
