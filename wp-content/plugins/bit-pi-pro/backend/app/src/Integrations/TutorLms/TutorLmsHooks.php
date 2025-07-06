<?php

namespace BitApps\PiPro\src\Integrations\TutorLms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class TutorLmsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'courseEnrollment' => [
                'hook'          => 'tutor_after_enroll',
                'callback'      => [TutorLmsTrigger::class, 'handleCourseEnroll'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'quizAttemptEnded' => [
                'hook'          => 'tutor_quiz/attempt_ended',
                'callback'      => [TutorLmsTrigger::class, 'handleQuizAttempt'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'quizTargetAttempt' => [
                'hook'          => 'tutor_quiz/attempt_ended',
                'callback'      => [TutorLmsTrigger::class, 'handleQuizTarget'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'lessonCompleted' => [
                'hook'          => 'tutor_lesson_completed_after',
                'callback'      => [TutorLmsTrigger::class, 'handleLessonComplete'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
            'courseCompleted' => [
                'hook'          => 'tutor_course_complete_after',
                'callback'      => [TutorLmsTrigger::class, 'handleCourseComplete'],
                'priority'      => 10,
                'accepted_args' => 1,
            ],
        ];
    }
}
