<?php

namespace BitApps\PiPro\src\Integrations\GamiPress;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class GamiPressTasks
{
    public const APP_SLUG = 'gamiPress';

    public const GAMIPRESS_PLUGIN_INDEX = 'gamipress/gamipress.php';

    public const USER_EARN_RANK = 'userEarnRank';

    public const AWARD_ACHIEVEMENT = 'awardAchievement';

    public const GAIN_ACHIEVEMENT_TYPE = 'gainAchievementType';

    public const REVOKE_ACHIEVEMENT = 'revokeAchievement';

    public const EARN_POINTS = 'earnPoints';
}
