<?php

namespace BitApps\PiPro\src\Integrations\AcademyLms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class AcademyLmsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'courseEnroll' => [
                'hook'          => 'academy/course/after_enroll',
                'callback'      => [AcademyLmsTrigger::class, 'handleCourseEnroll'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'quizAttempt' => [
                'hook'          => 'academy_quizzes/api/after_quiz_attempt_finished',
                'callback'      => [AcademyLmsTrigger::class, 'handleQuizAttempt'],
                'priority'      => 10,
                'accepted_args' => 1
            ],
            'lessonComplete' => [
                'hook'          => 'academy/frontend/after_mark_topic_complete',
                'callback'      => [AcademyLmsTrigger::class, 'handleLessonComplete'],
                'priority'      => 10,
                'accepted_args' => 4
            ],
            'adminCourseComplete' => [
                'hook'          => 'academy/admin/course_complete_after',
                'callback'      => [AcademyLmsTrigger::class, 'handleCourseComplete'],
                'priority'      => 10,
                'accepted_args' => 1
            ],
            'quizTarget' => [
                'hook'          => 'academy_quizzes/api/after_quiz_attempt_finished',
                'callback'      => [AcademyLmsTrigger::class, 'handleQuizTarget'],
                'priority'      => 10,
                'accepted_args' => 1
            ]
        ];
    }
}
