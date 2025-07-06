<?php

namespace BitApps\PiPro\src\Integrations\AcademyLms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use Academy\Traits\Lessons;
use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class AcademyLmsTrigger
{
    public static function handleCourseEnroll($courseId, $enrollmentId)
    {
        $flows = FlowService::exists('academyLms', 'courseEnroll');

        if (!$flows) {
            return;
        }

        $authorId = get_post_field('post_author', $courseId);

        $authorName = get_the_author_meta('display_name', $authorId);

        $studentId = get_post_field('post_author', $enrollmentId);

        $studentName = get_the_author_meta('display_name', $studentId);

        $resultStudent = [];

        if ($studentId && $studentName) {
            $resultStudent = [
                'student_id'   => $studentId,
                'student_name' => $studentName,
            ];
        }

        $resultCourse = [];

        $course = get_post($courseId);

        $resultCourse = [
            'course_id'     => $course->ID,
            'course_title'  => $course->post_title,
            'course_author' => $authorName,
        ];

        $result = $resultStudent + $resultCourse;

        $courseInfo = get_post_meta($courseId);

        $courseTemp = [];

        foreach ($courseInfo as $key => $val) {
            if (\is_array($val)) {
                $val = maybe_unserialize($val[0]);
            }

            $courseTemp[$key] = $val;
        }

        $result += $courseTemp;
        $result['post_id'] = $enrollmentId;
        IntegrationHelper::handleFlowForForm($flows, $result, $courseId, 'course-id');
    }

    public static function handleQuizAttempt($attempt)
    {
        $flows = FlowService::exists('academyLms', 'quizAttempt');

        $quizId = $attempt->quiz_id;

        $flows = $flows ? self::flowFilter($flows, 'selectedQuiz', $quizId) : false;

        if (!$flows || empty($flow)) {
            return;
        }

        if (get_post_type($quizId) !== 'academy_quiz') {
            return;
        }

        if ($attempt->attempt_status === 'pending') {
            return;
        }

        $attemptDetails = [];

        foreach ($attempt as $key => $val) {
            if (\is_array($val)) {
                $val = maybe_unserialize($val[0]);
            }

            $attemptDetails[$key] = maybe_unserialize($val);
        }

        IntegrationHelper::handleFlowForForm($flows, $attemptDetails, $quizId, 'quiz-id');
    }

    public static function handleQuizTarget($attempt)
    {
        $flows = FlowService::exists('academyLms', 'quizTarget');

        $quizId = $attempt->quiz_id;

        if (!$flows) {
            return;
        }

        if (get_post_type($quizId) !== 'academy_quiz') {
            return;
        }

        if ($attempt->attempt_status === 'pending') {
            return;
        }

        $attemptDetails = [];

        foreach ($attempt as $key => $val) {
            if (\is_array($val)) {
                $val = maybe_unserialize($val[0]);
            }

            $attemptDetails[$key] = maybe_unserialize($val);
        }

        IntegrationHelper::handleFlowForForm($flows, $attemptDetails, $quizId, 'quiz-id');
    }

    public static function handleLessonComplete($topicType, $courseId, $topicId, $userId)
    {
        $flows = FlowService::exists('academyLms', 'lessonComplete');

        if (!$flows) {
            return;
        }

        $topicData = [];

        if ($topicType === 'lesson') {
            $lessonPost = Lessons::get_lesson($topicId);
            $topicData = [
                'lesson_id'          => $lessonPost->ID,
                'lesson_title'       => $lessonPost->lesson_title,
                'lesson_description' => $lessonPost->lesson_content,
                'lesson_status'      => $lessonPost->lesson_status,
            ];
        }

        if ($topicType === 'quiz') {
            $quiz = get_post($topicId);
            $topicData = [
                'quiz_id'          => $quiz->ID,
                'quiz_title'       => $quiz->post_title,
                'quiz_description' => $quiz->post_content,
                'quiz_url'         => $quiz->guid,
            ];
        }

        $user = Utility::getUserInfo($userId);

        $currentUser = [
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'user_email' => $user['user_email'],
            'nickname'   => $user['nickname'],
            'avatar_url' => $user['avatar_url'],
        ];

        $courseData = [];

        $coursePost = get_post($courseId);

        $courseData = [
            'course_id'          => $coursePost->ID,
            'course_title'       => $coursePost->post_title,
            'course_description' => $coursePost->post_content,
            'course_url'         => $coursePost->guid,
        ];

        $lessonDataFinal = $topicData + $courseData + $currentUser;

        $lessonDataFinal['post_id'] = $topicId;

        IntegrationHelper::handleFlowForForm($flows, $lessonDataFinal, $courseId, 'lesson-id');
    }

    public static function handleCourseComplete($courseId)
    {
        $flows = FlowService::exists('academyLms', 'adminCourseComplete');

        if (!$flows) {
            return;
        }

        $coursePost = get_post($courseId);

        $courseData = [
            'course_id'    => $coursePost->ID,
            'course_title' => $coursePost->post_title,
            'course_url'   => $coursePost->guid,
        ];

        $user = Utility::getUserInfo(get_current_user_id());

        $currentUser = [
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'user_email' => $user['user_email'],
            'nickname'   => $user['nickname'],
            'avatar_url' => $user['avatar_url'],
        ];

        $courseDataFinal = $courseData + $currentUser;

        $courseDataFinal['post_id'] = $courseId;

        IntegrationHelper::handleFlowForForm($flows, $courseDataFinal, $courseId, 'course-id');
    }
}
