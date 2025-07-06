<?php

namespace BitApps\PiPro\src\Integrations\LearnDash;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class LearnDashTasks
{
    public const LEARNDASH_PLUGIN_INDEX = 'learndash/learndash.php';

    public const APP_SLUG = 'learnDash';

    public const LEARNDASH_PRO_PANEL_PLUGIN_INDEX = 'learndash-propanel/learndash_propanel.php';

    public const SFWD_LMS_PLUGIN_INDEX = 'sfwd-lms/sfwd_lms.php';

    public const COURSE_ENROLL = 'courseEnroll';

    public const COURSE_UNENROLL = 'courseUnEnroll';

    public const COURSE_COMPLETED = 'courseCompleted';

    public const LESSON_COMPLETED = 'lessonCompleted';

    public const TOPIC_COMPLETED = 'topicCompleted';

    public const QUIZ_ATTEMPT = 'quizAttempt';

    public const GROUP_ADDED = 'groupAdded';

    public const GROUP_REMOVED = 'groupRemoved';

    public const ASSIGNMENT_SUBMIT = 'assignmentSubmit';
}
