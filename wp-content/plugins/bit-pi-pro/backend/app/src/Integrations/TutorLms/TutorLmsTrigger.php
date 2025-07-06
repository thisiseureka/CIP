<?php

namespace BitApps\PiPro\src\Integrations\TutorLms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class TutorLmsTrigger
{
    public static function handleCourseEnroll($courseId, $enrollmentId)
    {
        $flows = FlowService::exists('tutorLms', 'courseEnrollment');

        if (!$flows) {
            return;
        }

        $authorId = get_post_field('post_author', $courseId);

        $authorName = get_the_author_meta('display_name', $authorId);

        $studentId = get_post_field('post_author', $enrollmentId);

        $userData = get_userdata($studentId);

        $resultStudent = [];

        if ($studentId && $userData) {
            $resultStudent = [
                'student_id'         => $studentId,
                'student_name'       => $userData->display_name,
                'student_first_name' => $userData->user_firstname,
                'student_last_name'  => $userData->user_lastname,
                'student_email'      => $userData->user_email,
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

    public static function handleQuizAttempt($attemptId)
    {
        $flows = FlowService::exists('tutorLms', 'quizAttemptEnded');

        $attempt = tutor_utils()->get_attempt($attemptId);

        $quizId = $attempt->quiz_id;

        $flows = self::flowFilter($flows, 'selectedQuiz', $quizId);
        if (!$flows) {
            return;
        }

        if ('tutor_quiz' !== get_post_type($quizId)) {
            return;
        }

        if ('attempt_ended' != $attempt->attempt_status && 'review_required' != $attempt->attempt_status) {
            return;
        }

        $attemptDetails = [];

        $attemptInfo = [];

        foreach ($attempt as $key => $val) {
            if (\is_array($val)) {
                $val = maybe_unserialize($val[0]);
            }
            $attemptDetails[$key] = maybe_unserialize($val);
        }

        if (\array_key_exists('attempt_info', $attemptDetails)) {
            $attemptInfoTmp = $attemptDetails['attempt_info'];

            unset($attemptDetails['attempt_info']);

            foreach ($attemptInfoTmp as $key => $val) {
                $attemptInfo[$key] = maybe_unserialize($val);
            }

            $attemptDetails['passing_grade'] = $attemptInfo['passing_grade'];

            $totalMark = $attemptDetails['total_marks'];

            $earnMark = $attemptDetails['earned_marks'];

            $passGrade = $attemptDetails['passing_grade'];

            $mark = $totalMark * ($passGrade / 100);

            if ('review_required' == $attempt->attempt_status) {
                $attemptDetails['result_status'] = 'Pending';
            } elseif ($earnMark >= $mark) {
                $attemptDetails['result_status'] = 'Passed';
            } else {
                $attemptDetails['result_status'] = 'Failed';
            }
        }

        $attemptDetails['post_id'] = $attemptId;

        IntegrationHelper::handleFlowForForm($flows, $attemptDetails, $quizId, 'quiz-id');
    }

    public static function handleQuizTarget($attemptId)
    {
        $flows = FlowService::exists('tutorLms', 'quizTargetAttempt');

        $attempt = tutor_utils()->get_attempt($attemptId);

        $quizId = $attempt->quiz_id;

        if (!$flows) {
            return;
        }

        if ('tutor_quiz' !== get_post_type($quizId)) {
            return;
        }

        if ('attempt_ended' !== $attempt->attempt_status) {
            return;
        }

        $attemptDetails = [];

        $attemptInfo = [];

        foreach ($attempt as $key => $val) {
            if (\is_array($val)) {
                $val = maybe_unserialize($val[0]);
            }
            $attemptDetails[$key] = maybe_unserialize($val);
        }

        if (\array_key_exists('attempt_info', $attemptDetails)) {
            $attemptInfoTmp = $attemptDetails['attempt_info'];
            unset($attemptDetails['attempt_info']);

            foreach ($attemptInfoTmp as $key => $val) {
                $attemptInfo[$key] = maybe_unserialize($val);
            }

            $attemptDetails['passing_grade'] = $attemptInfo['passing_grade'];

            $totalMark = $attemptDetails['total_marks'];

            $earnMark = $attemptDetails['earned_marks'];

            $passGrade = $attemptDetails['passing_grade'];

            $mark = $totalMark * ($passGrade / 100);

            $attemptDetails['result_status'] = $earnMark >= $mark ? 'Passed' : 'Failed';

            $attemptDetails['post_id'] = $attemptId;

            IntegrationHelper::handleFlowForForm($flows, $attemptDetails, $quizId, 'quiz-id');
        }
    }

    public static function handleLessonComplete($lessonId)
    {
        $flows = FlowService::exists('tutorLms', 'lessonCompleted');

        if (!$flows) {
            return;
        }

        $lessonPost = get_post($lessonId);

        $lessonData = [
            'lesson_id'          => $lessonPost->ID,
            'lesson_title'       => $lessonPost->post_title,
            'lesson_description' => $lessonPost->post_content,
            'lesson_url'         => $lessonPost->guid,
        ];

        $user = Utility::getUserInfo(get_current_user_id());

        $currentUser = [
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'user_email' => $user['user_email'],
            'nickname'   => $user['nickname'],
            'avatar_url' => $user['avatar_url'],
        ];

        $courseData = [];

        $topicPost = get_post($lessonPost->post_parent);

        $topicData = [
            'topic_id'          => $topicPost->ID,
            'topic_title'       => $topicPost->post_title,
            'topic_description' => $topicPost->post_content,
            'topic_url'         => $topicPost->guid,
        ];
        $coursePost = get_post($topicPost->post_parent);

        $courseData = [
            'course_id'          => $coursePost->ID,
            'course_name'        => $coursePost->post_title,
            'course_description' => $coursePost->post_content,
            'course_url'         => $coursePost->guid,
        ];

        $lessonDataFinal = $lessonData + $topicData + $courseData + $currentUser;

        $lessonDataFinal['post_id'] = $lessonId;

        IntegrationHelper::handleFlowForForm($flows, $lessonDataFinal, $lessonId, 'lesson-id');
    }

    public static function handleCourseComplete($courseId)
    {
        $flows = FlowService::exists('tutorLms', 'courseCompleted');

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
