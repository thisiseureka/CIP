<?php

namespace BitApps\PiPro\src\Integrations\LifterLms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class LifterLmsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            LifterLmsTasks::QUIZ_COMPLETED => [
                'hook'          => 'lifterlms_quiz_completed',
                'callback'      => [LifterLmsTrigger::class, 'handleAttemptQuiz'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            LifterLmsTasks::QUIZ_PASSED => [
                'hook'          => 'lifterlms_quiz_passed',
                'callback'      => [LifterLmsTrigger::class, 'handleQuizPass'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            LifterLmsTasks::QUIZ_FAILED => [
                'hook'          => 'lifterlms_quiz_failed',
                'callback'      => [LifterLmsTrigger::class, 'handleQuizFail'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            LifterLmsTasks::LESSON_COMPLETED => [
                'hook'          => 'lifterlms_lesson_completed',
                'callback'      => [LifterLmsTrigger::class, 'handleLessonComplete'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            LifterLmsTasks::COURSE_COMPLETED => [
                'hook'          => 'lifterlms_course_completed',
                'callback'      => [LifterLmsTrigger::class, 'handleCourseComplete'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            LifterLmsTasks::COURSE_ENROLLED => [
                'hook'          => 'llms_user_enrolled_in_course',
                'callback'      => [LifterLmsTrigger::class, 'handleCourseEnroll'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            LifterLmsTasks::COURSE_UNENROLLED => [
                'hook'          => 'llms_user_removed_from_course',
                'callback'      => [LifterLmsTrigger::class, 'handleCourseUnEnroll'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
            LifterLmsTasks::MEMBERSHIP_CANCELLED => [
                'hook'          => 'llms_subscription_cancelled_by_student',
                'callback'      => [LifterLmsTrigger::class, 'handleMembershipCancel'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
        ];
    }
}
