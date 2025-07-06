<?php

namespace BitApps\PiPro\src\Integrations\MasterStudyLms;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class MasterStudyLmsHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'progressUpdated' => [
                'hook'          => 'stm_lms_progress_updated',
                'callback'      => [MasterStudyLmsTrigger::class, 'handleCourseComplete'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'addUserCourse' => [
                'hook'          => ['course_enrolled', 'add_user_course'],
                'callback'      => [MasterStudyLmsTrigger::class, 'handleCourseEnroll'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'lessonPassed' => [
                'hook'          => 'stm_lms_lesson_passed',
                'callback'      => [MasterStudyLmsTrigger::class, 'handleLessonComplete'],
                'priority'      => 10,
                'accepted_args' => 2,
            ],
            'quizPassed' => [
                'hook'          => 'stm_lms_quiz_passed',
                'callback'      => [MasterStudyLmsTrigger::class, 'handleQuizComplete'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'quizFailed' => [
                'hook'          => 'stm_lms_quiz_failed',
                'callback'      => [MasterStudyLmsTrigger::class, 'handleQuizFailed'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
            'friendshipAcceptedScore' => [
                'hook' => [
                    'stm_lms_score_charge_user_registered',
                    'stm_lms_score_charge_course_purchased',
                    'stm_lms_score_charge_lesson_passed',
                    'stm_lms_score_charge_quiz_passed',
                    'stm_lms_score_charge_perfect_quiz',
                    'stm_lms_score_charge_certificate_received',
                    'stm_lms_score_charge_assignment_passed',
                    'stm_lms_score_charge_group_joined',
                    'stm_lms_score_charge_friends_friendship_accepted',
                ],
                'callback'      => [MasterStudyLmsTrigger::class, 'handlePointScoreCharge'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
