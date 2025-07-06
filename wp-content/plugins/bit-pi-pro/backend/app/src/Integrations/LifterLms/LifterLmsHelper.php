<?php

namespace BitApps\PiPro\src\Integrations\LifterLms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

class LifterLmsHelper
{
    public static function isPluginActive()
    {
        return (bool) (is_plugin_active(LifterLmsTasks::LIFTER_LMS_PLUGIN_INDEX));
    }

    public static function getQuizDetail($quizId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'llms_quiz' AND {$wpdb->posts}.ID = %d",
                $quizId
            )
        );
    }

    public static function getLessonDetail($lessonId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'lesson' AND {$wpdb->posts}.ID = %d",
                $lessonId
            )
        );
    }

    public static function getCourseDetail($courseId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'course' AND {$wpdb->posts}.ID = %d",
                $courseId
            )
        );
    }

    public static function getMembershipDetail($membershipId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
        WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'llms_membership' AND {$wpdb->posts}.ID = %d",
                $membershipId
            )
        );
    }

    public static function getLifterLmsAllQuiz()
    {
        if (!self::isPluginActive()) {
            return Response::error(__('LifterLms is not installed or activated', 'bit-pi'));
        }

        global $wpdb;

        $quizzes = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                    WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'llms_quiz' ORDER BY post_title"
            )
        );

        $allQuiz[] = [
            'value' => 'any',
            'label' => __('Any Quiz', 'bit-pi')
        ];

        if ($quizzes) {
            foreach ($quizzes as $quiz) {
                $allQuiz[] = [
                    'value' => $quiz->ID,
                    'label' => $quiz->post_title
                ];
            }
        }

        return Response::success($allQuiz);
    }

    public static function getLifterLmsAllLesson()
    {
        if (!self::isPluginActive()) {
            return Response::error(__('LifterLms is not installed or activated', 'bit-pi'));
        }

        global $wpdb;

        $lessons = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                    WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'lesson' ORDER BY post_title"
            )
        );

        $allLesson[] = [
            'value' => 'any',
            'label' => __('Any Lesson', 'bit-pi')
        ];

        if ($lessons) {
            foreach ($lessons as $lesson) {
                $allLesson[] = [
                    'value' => $lesson->ID,
                    'label' => $lesson->post_title
                ];
            }
        }

        return Response::success($allLesson);
    }

    public static function getLifterLmsAllCourse()
    {
        if (!self::isPluginActive()) {
            return Response::error(__('LifterLms is not installed or activated', 'bit-pi'));
        }

        $allCourse[] = [
            'value' => 'any',
            'label' => __('Any Course', 'bit-pi')
        ];

        global $wpdb;

        $courses = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'course' ORDER BY post_title"
            )
        );

        if ($courses) {
            foreach ($courses as $course) {
                $allCourse[] = [
                    'value' => $course->ID,
                    'label' => $course->post_title
                ];
            }
        }

        return Response::success($allCourse);
    }

    public static function getLifterLmsAllMembership()
    {
        if (!self::isPluginActive()) {
            return Response::error(__('LifterLms is not installed or activated', 'bit-pi'));
        }

        $allMembership[] = [
            'value' => 'any',
            'label' => __('Any Membership', 'bit-pi')
        ];

        global $wpdb;

        $memberships = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'llms_membership' ORDER BY post_title"
            )
        );

        if ($memberships) {
            foreach ($memberships as $membership) {
                $allMembership[] = [
                    'value' => $membership->ID,
                    'label' => $membership->post_title
                ];
            }
        }

        return Response::success($allMembership);
    }
}
