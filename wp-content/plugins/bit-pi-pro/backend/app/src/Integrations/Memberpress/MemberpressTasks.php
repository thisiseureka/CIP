<?php

namespace BitApps\PiPro\src\Integrations\Memberpress;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class MemberpressTasks
{
    public const MEMBERPRESS_PLUGIN_INDEX = 'memberpress/memberpress.php';

    public const APP_SLUG = 'memberpress';

    public const ONE_TIME_SUBSCRIPTION = 'oneTimeSubscription';

    public const RECURRING_SUBSCRIPTION = 'recurringSubscription';

    public const CANCEL_SUBSCRIPTION = 'cancelSubscription';

    public const SUBSCRIPTION_EXPIRED = 'subscriptionExpired';

    public const PAUSE_SUBSCRIPTION = 'pauseSubscription';

    public const MEMBERPRESS_POST_TYPE = 'memberpressproduct';
}
