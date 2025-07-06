<?php

namespace BitApps\PiPro\src\Integrations\BbPress;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class BbPressHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'topicCreated' => [
                'hook'          => 'bbp_new_topic',
                'callback'      => [BbPressTrigger::class, 'handleTopicCreated'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
            'topicReplied' => [
                'hook'          => 'bbp_new_reply',
                'callback'      => [BbPressTrigger::class, 'handleTopicReplied'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
