<?php

namespace BitApps\PiPro\src\Integrations\Asgaros;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class AsgarosHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'userNewTopic' => [
                'hook'          => 'asgarosforum_after_add_topic_submit',
                'callback'      => [AsgarosTrigger::class, 'handleUserCreatesNewTopicInForum'],
                'priority'      => 5,
                'accepted_args' => 2,
            ],
            'userReplyTopic' => [
                'hook'          => 'asgarosforum_after_add_post_submit',
                'callback'      => [AsgarosTrigger::class, 'handleUserRepliesToTopicInForum'],
                'priority'      => 5,
                'accepted_args' => 2,
            ],
        ];
    }
}
