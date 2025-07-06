<?php

namespace BitApps\PiPro\src\Integrations\GamiPress;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class GamiPressHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            GamiPressTasks::USER_EARN_RANK => [
                'hook'          => 'gamipress_update_user_rank',
                'callback'      => [GamiPressTrigger::class, 'handleUserEarnRank'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            GamiPressTasks::GAIN_ACHIEVEMENT_TYPE => [
                'hook'          => 'gamipress_award_achievement',
                'callback'      => [GamiPressTrigger::class, 'handleGainAchievementType'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            GamiPressTasks::REVOKE_ACHIEVEMENT => [
                'hook'          => 'gamipress_revoke_achievement_to_user',
                'callback'      => [GamiPressTrigger::class, 'handleRevokeAchieve'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            GamiPressTasks::EARN_POINTS => [
                'hook'          => 'gamipress_update_user_points',
                'callback'      => [GamiPressTrigger::class, 'handleEarnPoints'],
                'priority'      => 10,
                'accepted_args' => 6,
            ],
            GamiPressTasks::AWARD_ACHIEVEMENT => [
                'hook'          => 'gamipress_award_achievement',
                'callback'      => [GamiPressTrigger::class, 'handleAwardAchievement'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
