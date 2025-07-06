<?php

namespace BitApps\PiPro\src\Integrations\AcademyLms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use Academy\Traits\Lessons;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

final class AcademyLmsHelper
{
    public static function getAllQuiz()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Academy Lms'));
        }

        $allQuiz = [[
            'label' => 'Any Quiz',
            'value' => 'any'
        ],
        ];

        $quizzes = get_posts(
            [
                'post_type'   => 'academy_quiz',
                'post_status' => 'publish',
                'numberposts' => -1
            ]
        );

        if ($quizzes) {
            foreach ($quizzes as $quiz) {
                $allQuiz[] = [
                    'label' => $quiz->post_title,
                    'value' => $quiz->ID,
                ];
            }
        }

        return Response::success($allQuiz);
    }

    public static function getAllLesson()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Academy Lms'));
        }

        $allLesson = [[
            'label' => 'Any Lesson',
            'value' => 'any'
        ],
        ];

        $lessons = Lessons::get_lessons();

        if ($lessons) {
            foreach ($lessons as $lesson) {
                $allLesson[] = [
                    'label' => $lesson->lesson_title,
                    'value' => $lesson->ID,
                ];
            }
        }

        return Response::success($allLesson);
    }

    public static function getAllCourse()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Academy Lms'));
        }

        $allCourse = [[
            'label' => 'Any Course',
            'value' => 'any'
        ],
        ];

        $courses = get_posts(
            [
                'post_type'   => 'academy_courses',
                'post_status' => 'publish',
                'numberposts' => -1
            ]
        );

        if ($courses) {
            foreach ($courses as $course) {
                $allCourse[] = [
                    'label' => $course->post_title,
                    'value' => $course->ID,
                ];
            }
        }

        return Response::success($allCourse);
    }

    private static function isPluginActive()
    {
        return class_exists('Academy');
    }
}
