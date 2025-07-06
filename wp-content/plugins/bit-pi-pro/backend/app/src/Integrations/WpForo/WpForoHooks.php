<?php

namespace BitApps\PiPro\src\Integrations\WpForo;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class WpForoHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'topicAdded' => [
                'hook'          => 'wpforo_after_add_topic',
                'callback'      => [WpForoTrigger::class, 'handleWPForoTopicAdd'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'postAdded' => [
                'hook'          => 'wpforo_after_add_post',
                'callback'      => [WpForoTrigger::class, 'handleWPForoPostAdd'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'postGetsDownvoted' => [
                'hook'          => 'wpforo_vote',
                'callback'      => [WpForoTrigger::class, 'handleWPForoGetsDownVote'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'postGetsDisliked' => [
                'hook'          => 'wpforo_react_post',
                'callback'      => [WpForoTrigger::class, 'handleWPForoGetsDislike'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'postGotAnswer' => [
                'hook'          => 'wpforo_answer',
                'callback'      => [WpForoTrigger::class, 'handleWPForoGetsAnswer'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],

            'postUpvoted' => [
                'hook'          => 'wpforo_vote',
                'callback'      => [WpForoTrigger::class, 'handleWPForoUpVote'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'postDownvoted' => [
                'hook'          => 'wpforo_vote',
                'callback'      => [WpForoTrigger::class, 'handleWPForoDownVote'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'postLiked' => [
                'hook'          => 'wpforo_react_post',
                'callback'      => [WpForoTrigger::class, 'handleWPForoLike'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'postDisliked' => [
                'hook'          => 'wpforo_react_post',
                'callback'      => [WpForoTrigger::class, 'handleWPForoDislike'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'postGetsUpvoted' => [
                'hook'          => 'wpforo_vote',
                'callback'      => [WpForoTrigger::class, 'handleWPForoGetsUpVote'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'postGetsLiked' => [
                'hook'          => 'wpforo_react_post',
                'callback'      => [WpForoTrigger::class, 'handleWPForoGetsLike'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'postAnswered' => [
                'hook'          => 'wpforo_answer',
                'callback'      => [WpForoTrigger::class, 'handleWPForoAnswer'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
