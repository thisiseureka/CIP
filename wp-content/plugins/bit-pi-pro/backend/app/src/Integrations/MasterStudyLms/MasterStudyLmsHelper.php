<?php

namespace BitApps\PiPro\src\Integrations\MasterStudyLms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;

class MasterStudyLmsHelper
{
    public static function getAllCourse()
    {
        $allCourse = [];
        $args = [
            'post_type'      => 'stm-courses',
            'posts_per_page' => 999,
            'orderby'        => 'label',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ];
        $courses = get_posts($args);
        foreach ($courses as $value) {
            $allCourse[] = [
                'value' => $value->ID,
                'label' => $value->post_title,
            ];
        }

        return $allCourse;
    }

    public static function getAllQuiz($courseId)
    {
        global $wpdb;

        if ($courseId === 'any') {
            $query = $wpdb->prepare(
                "SELECT ID, post_title, post_content
            FROM {$wpdb->posts}
            WHERE post_type = %s
            ORDER BY post_title ASC",
                'stm-quizzes'
            );
        } else {
            $query = $wpdb->prepare(
                "SELECT p.ID, p.post_title, p.post_content
            FROM {$wpdb->posts} p
            JOIN {$wpdb->prefix}stm_lms_curriculum_materials cm ON p.ID = cm.post_id
            JOIN {$wpdb->prefix}stm_lms_curriculum_sections cs ON cm.section_id = cs.id
            WHERE p.post_type = %s
            AND cs.course_id = %d
            ORDER BY p.post_title ASC",
                'stm-quizzes',
                absint($courseId)
            );
        }

        return $wpdb->get_results($query);
    }

    public static function getCourseDetail($courseId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title,post_content FROM {$wpdb->posts}
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'stm-courses' AND {$wpdb->posts}.ID = %d",
                $courseId
            )
        );
    }

    public static function getQuizDetails($quizId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title,post_content FROM {$wpdb->posts}
                 WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'stm-quizzes' AND {$wpdb->posts}.ID = %d",
                $quizId
            )
        );
    }

    public static function getLessonDetail($lessonId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title,post_content FROM {$wpdb->posts}
        WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'stm-lessons' AND {$wpdb->posts}.ID = %d",
                $lessonId
            )
        );
    }

    public static function getAllLesson()
    {
        $allLesson = [];
        $args = [
            'post_type'      => 'stm-lessons',
            'posts_per_page' => 999,
            'orderby'        => 'label',
            'order'          => 'ASC',
            'post_status'    => 'publish',
        ];
        $lessons = get_posts($args);
        foreach ($lessons as $value) {
            $allLesson[] = [
                'value' => $value->ID,
                'label' => $value->post_title,
            ];
        }

        return $allLesson;
    }

    public static function getAllDistribution()
    {
        return [
            [
                'value' => 'any',
                'label' => __('Any Distribution', 'bit-pi'),
            ],
            [
                'value' => 1,
                'label' => __('Registration', 'bit-pi'),
            ],
            [
                'value' => 2,
                'label' => __('Course purchase', 'bit-pi'),
            ],
            [
                'value' => 3,
                'label' => __('Lesson completion', 'bit-pi'),
            ],
            [
                'value' => 4,
                'label' => __('Passing quiz', 'bit-pi'),
            ],
            [
                'value' => 5,
                'label' => __('Passing quiz with 100%', 'bit-pi'),
            ],
            [
                'value' => 6,
                'label' => __('Passing assignment', 'bit-pi'),
            ],
            [
                'value' => 7,
                'label' => __('Course completion', 'bit-pi'),
            ],
            [
                'value' => 8,
                'label' => __('Joining group', 'bit-pi'),
            ],
            [
                'value' => 9,
                'label' => __('Making friends', 'bit-pi'),
            ],
            [
                'value' => 10,
                'label' => __('User registered (Affiliate)', 'bit-pi'),
            ],
            [
                'value' => 11,
                'label' => __('Course purchased (Affiliate)', 'bit-pi'),
            ],
        ];
    }

    public static function prepareFinalData($userId, $action, $score, $selectedDistribution = null, $isAffiliate = false)
    {
        if ($isAffiliate) {
            $userInfo = Utility::getUserInfo(static::getAffiliateId($userId));
            $distribution = array_column(MasterStudyLmsHelper::getAllDistribution(), 'label', 'value');

            $finalData = [
                'score'        => $score * static::affiliateRate(),
                'distribution' => $distribution[$selectedDistribution]
            ];
        } else {
            $userInfo = Utility::getUserInfo($userId);
            $finalData = [
                'score'        => $score,
                'distribution' => $action['label']
            ];
        }

        return array_merge(
            [
                'first_name' => $userInfo['first_name'],
                'last_name'  => $userInfo['last_name'],
                'nickname'   => $userInfo['nickname'],
                'avatar_url' => $userInfo['avatar_url'],
                'user_email' => $userInfo['user_email'],
                'repeat'     => $action['repeat'],
            ],
            $finalData
        );
    }

    private static function getAffiliateId($userId)
    {
        if ($_COOKIE === []) {
            return get_user_meta($userId, 'affiliate_id', true);
        }

        if (empty($_COOKIE['affiliate_id'])) {
            return get_user_meta($userId, 'affiliate_id', true);
        }

        if (\intval($_COOKIE['affiliate_id'] !== $userId) !== 0) {
            return \intval($_COOKIE['affiliate_id']);
        }

        return get_user_meta($userId, 'affiliate_id', true);
    }

    private static function affiliateRate()
    {
        $options = get_option('stm_lms_point_system_settings', []);

        return (empty($options['affiliate_points_rate']) ? 10 : $options['affiliate_points_rate']) / 100;
    }
}
