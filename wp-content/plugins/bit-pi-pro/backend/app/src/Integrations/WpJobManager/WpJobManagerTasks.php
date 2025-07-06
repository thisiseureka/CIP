<?php

namespace BitApps\PiPro\src\Integrations\WpJobManager;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class WpJobManagerTasks
{
    public const WP_JOB_APPLICATION_PLUGIN_INDEX = 'wp-job-manager-applications/wp-job-manager-applications.php';

    public const APP_SLUG = 'wpJobManager';

    public const WP_JOB_RESUME_PLUGIN_INDEX = 'wp-job-manager-resumes/wp-job-manager-resumes.php';

    public const WP_JOB_MANAGER_PLUGIN_INDEX = 'wp-job-manager/wp-job-manager.php';

    public const JOB_PUBLISHED = 'jobPublished';

    public const JOB_FILLED = 'jobFilled';

    public const JOB_NOT_FILLED = 'jobNotFilled';

    public const SPECIFIC_JOB_FILLED = 'specificJobFilled';

    public const SPECIFIC_JOB_NOT_FILLED = 'specificJobNotFilled';

    public const JOB_UPDATED = 'jobUpdated';

    public const APPLICATION_SUBMITTED = 'applicationSubmitted';

    public const APPLICATION_SPECIFIC_TYPE_SUBMITTED = 'applicationSpecificTypeSubmitted';

    public const APPLICATION_STATUS_CHANGED = 'applicationStatusChanged';

    public const JOB_TYPE_APPLICATION_STATUS_CHANGED = 'jobTypeApplicationStatusChanged';

    public const APPLY_WITH_RESUME = 'applyWithResume';
}
