<?php

namespace BitApps\PiPro\src\Integrations\TutorLms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

final class TutorLmsHelper
{
    public static function getAllQuiz()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Tutor LMS'));
        }

        $quizzes = self::getPosts('tutor_quiz', 'Any Quiz');

        return Response::success($quizzes);
    }

    public static function getAllLesson()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Tutor LMS'));
        }

        $lessons = self::getPosts('lesson', 'Any Lesson');

        return Response::success($lessons);
    }

    public static function getAllCourse()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Tutor LMS'));
        }

        $courses = self::getPosts('courses', 'Any Course');

        return Response::success($courses);
    }

    private static function getPosts($postType, $defaultLabel)
    {
        $allPost = [[
            'label' => $defaultLabel,
            'value' => 'any'
        ],
        ];

        $posts = get_posts(
            [
                'post_type'   => $postType,
                'post_status' => 'publish',
                'numberposts' => -1
            ]
        );

        foreach ($posts as $post) {
            $allPost[] = [
                'label' => $post->post_title,
                'value' => $post->ID,
            ];
        }

        return $allPost;
    }

    private static function isPluginActive()
    {
        return \function_exists('tutor');
    }
}
