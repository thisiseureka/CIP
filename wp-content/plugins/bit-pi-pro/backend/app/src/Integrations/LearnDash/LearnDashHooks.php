<?php

namespace BitApps\PiPro\src\Integrations\LearnDash;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class LearnDashHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            LearnDashTasks::COURSE_ENROLL => [
                'hook'          => 'learndash_update_course_access',
                'callback'      => [LearnDashTrigger::class, 'handleCourseEnroll'],
                'priority'      => 10,
                'accepted_args' => 4,
            ], LearnDashTasks::COURSE_UNENROLL => [
                'hook'          => 'learndash_update_course_access',
                'callback'      => [LearnDashTrigger::class, 'handleCourseEnroll'],
                'priority'      => 10,
                'accepted_args' => 4,
            ],
            LearnDashTasks::COURSE_COMPLETED => [
                'hook'          => 'learndash_course_completed',
                'callback'      => [LearnDashTrigger::class, 'handleCourseCompleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            LearnDashTasks::LESSON_COMPLETED => [
                'hook'          => 'learndash_lesson_completed',
                'callback'      => [LearnDashTrigger::class, 'handleLessonCompleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            LearnDashTasks::TOPIC_COMPLETED => [
                'hook'          => 'learndash_topic_completed',
                'callback'      => [LearnDashTrigger::class, 'handleTopicCompleted'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            LearnDashTasks::QUIZ_ATTEMPT => [
                'hook'          => 'learndash_quiz_submitted',
                'callback'      => [LearnDashTrigger::class, 'handleQuizAttempt'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            LearnDashTasks::GROUP_ADDED => [
                'hook'          => 'ld_added_group_access',
                'callback'      => [LearnDashTrigger::class, 'handleAddedGroup'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            LearnDashTasks::GROUP_REMOVED => [
                'hook'          => 'ld_removed_group_access',
                'callback'      => [LearnDashTrigger::class, 'handleRemovedGroup'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            LearnDashTasks::ASSIGNMENT_SUBMIT => [
                'hook'          => 'learndash_assignment_uploaded',
                'callback'      => [LearnDashTrigger::class, 'handleAssignmentSubmit'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
        ];
    }
}
