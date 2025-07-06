<?php

namespace BitApps\PiPro\src\Integrations\LifterLms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class LifterLmsTasks
{
    public const LIFTER_LMS_PLUGIN_INDEX = 'lifterlms/lifterlms.php';

    public const APP_SLUG = 'lifterLms';

    public const QUIZ_COMPLETED = 'quizCompleted';

    public const QUIZ_PASSED = 'quizPassed';

    public const QUIZ_FAILED = 'quizFailed';

    public const LESSON_COMPLETED = 'lessonCompleted';

    public const COURSE_COMPLETED = 'courseCompleted';

    public const COURSE_ENROLLED = 'courseEnrolled';

    public const COURSE_UNENROLLED = 'courseUnenrolled';

    public const MEMBERSHIP_CANCELLED = 'membershipCancelled';
}
