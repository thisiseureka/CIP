<?php

namespace BitApps\PiPro\src\Integrations\LearnDash;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

final class LearnDashHelper
{
    public static function getTopics()
    {
        $topicQueryArgs = [
            'post_type'      => 'sfwd-topic',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => -1,
        ];

        $allTopics[] = [
            'value' => 'any',
            'label' => __('Any Topic', 'bit-pi')
        ];

        $topics = get_posts($topicQueryArgs);

        if ($topics) {
            foreach ($topics as $topic) {
                $allTopics[] = [
                    'value' => $topic->ID,
                    'label' => $topic->post_title
                ];
            }
        }

        return Response::success($allTopics);
    }

    public static function getCourses()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'LearnDash LMS'));
        }

        $courseQueryArgs = [
            'post_type'      => 'sfwd-courses',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => -1,
        ];

        $allCourses[] = [
            'value' => 'any',
            'label' => __('Any Course', 'bit-pi')
        ];

        $courses = get_posts($courseQueryArgs);

        if ($courses) {
            foreach ($courses as $course) {
                $allCourses[] = [
                    'value' => $course->ID,
                    'label' => $course->post_title
                ];
            }
        }

        return Response::success($allCourses);
    }

    public static function getLessons()
    {
        $lessonQueryArgs = [
            'post_type'      => 'sfwd-lessons',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => -1,
        ];

        $AllLesson[] = [
            'value' => 'any',
            'label' => __('Any Lesson', 'bit-pi')
        ];

        $lessons = get_posts($lessonQueryArgs);

        if ($lessons) {
            foreach ($lessons as $lesson) {
                $AllLesson[] = [
                    'value' => $lesson->ID,
                    'label' => $lesson->post_title,
                ];
            }
        }

        return Response::success($AllLesson);
    }

    public static function getQuizes()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'LearnDash LMS'));
        }

        $quizes = [];

        $quizQueryArgs = [
            'post_type'      => 'sfwd-quiz',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => -1,
        ];

        $quizes = get_posts($quizQueryArgs);

        $AllQuizes[] = [
            'value' => 'any',
            'label' => __('Any Quiz', 'bit-pi')
        ];

        if ($quizes) {
            foreach ($quizes as $val) {
                $AllQuizes[] = [
                    'value' => $val->ID,
                    'label' => $val->post_title,
                ];
            }
        }

        return Response::success($AllQuizes);
    }

    public static function getGroups()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'LearnDash LMS'));
        }

        $groupsQueryArgs = [
            'post_type'      => 'groups',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => -1,
        ];

        $allGroups[] = [
            'value' => 'any',
            'label' => __('Any Group', 'bit-pi')
        ];

        $groups = get_posts($groupsQueryArgs);

        if ($groups) {
            foreach ($groups as $group) {
                $allGroups[] = [
                    'value' => $group->ID,
                    'label' => $group->post_title,
                ];
            }
        }

        return Response::success($allGroups);
    }

    public function getLessonsByCourse(Request $request)
    {
        $validatedData = $request->validate(
            [
                'courseId' => ['required', 'string',  'sanitize:text'],
            ]
        );

        $courseId = $validatedData['courseId'];

        $allLessons[] = [
            'value' => 'any',
            'label' => __('Any Lesson', 'bit-pi')
        ];

        $lessons = learndash_get_lesson_list($courseId, ['num' => 0]);

        if ($lessons) {
            foreach ($lessons as $lesson) {
                $allLessons[] = [
                    'value' => $lesson->ID,
                    'label' => $lesson->post_title
                ];
            }
        }

        return Response::success($allLessons);
    }

    public function getTopicsByLesson(Request $request)
    {
        $validatedData = $request->validate(
            [
                'lessonId' => ['required', 'string',  'sanitize:text'],
                'courseId' => ['required', 'string',  'sanitize:text'],
            ]
        );

        $allTopics[] = [
            'value' => 'any',
            'label' => __('Any Topic', 'bit-pi')
        ];

        $topics = learndash_get_topic_list($validatedData['lessonId'], $validatedData['courseId']);

        if ($topics) {
            foreach ($topics as $topic) {
                $allTopics[] = [
                    'value' => $topic->ID,
                    'label' => $topic->post_title
                ];
            }
        }

        return Response::success($allTopics);
    }

    private static function isPluginActive()
    {
        if (is_plugin_active(LearnDashTasks::LEARNDASH_PRO_PANEL_PLUGIN_INDEX)) {
            return true;
        }

        if (is_plugin_active(LearnDashTasks::LEARNDASH_PLUGIN_INDEX)) {
            return true;
        }

        return (bool) (is_plugin_active(LearnDashTasks::SFWD_LMS_PLUGIN_INDEX));
    }
}
