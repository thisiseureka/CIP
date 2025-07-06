<?php

namespace BitApps\PiPro\src\Integrations\LifterLms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class LifterLmsTrigger
{
    public static function handleAttemptQuiz($userId, $quizId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::QUIZ_COMPLETED);

        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $quizDetail = LifterLmsHelper::getQuizDetail($quizId);

        $finalData = [
            'user_id'    => $userId,
            'quiz_id'    => $quizId,
            'first_name' => $userInfo['first_name'],
            'last_name'  => $userInfo['last_name'],
            'nickname'   => $userInfo['nickname'],
            'avatar_url' => $userInfo['avatar_url'],
            'user_email' => $userInfo['user_email'],
            'quiz_title' => $quizDetail[0]->post_title,
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $quizId, 'quiz-id');
    }

    public static function handleQuizPass($userId, $quizId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::QUIZ_PASSED);

        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $quizDetail = LifterLmsHelper::getQuizDetail($quizId);

        $finalData = [
            'user_id'    => $userId,
            'quiz_id'    => $quizId,
            'first_name' => $userInfo['first_name'],
            'last_name'  => $userInfo['last_name'],
            'nickname'   => $userInfo['nickname'],
            'avatar_url' => $userInfo['avatar_url'],
            'user_email' => $userInfo['user_email'],
            'quiz_title' => $quizDetail[0]->post_title,
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $quizId, 'quiz-id');
    }

    public static function handleQuizFail($userId, $quizId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::QUIZ_FAILED);

        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $quizDetail = LifterLmsHelper::getQuizDetail($quizId);

        $finalData = [
            'user_id'    => $userId,
            'quiz_id'    => $quizId,
            'first_name' => $userInfo['first_name'],
            'last_name'  => $userInfo['last_name'],
            'nickname'   => $userInfo['nickname'],
            'avatar_url' => $userInfo['avatar_url'],
            'user_email' => $userInfo['user_email'],
            'quiz_title' => $quizDetail[0]->post_title,
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $quizId, 'quiz-id');
    }

    public static function handleLessonComplete($userId, $lessonId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::LESSON_COMPLETED);

        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $lessonDetail = LifterLmsHelper::getLessonDetail($lessonId);

        $finalData = [
            'user_id'      => $userId,
            'lesson_id'    => $lessonId,
            'lesson_title' => $lessonDetail[0]->post_title,
            'first_name'   => $userInfo['first_name'],
            'last_name'    => $userInfo['last_name'],
            'nickname'     => $userInfo['nickname'],
            'avatar_url'   => $userInfo['avatar_url'],
            'user_email'   => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $lessonId, 'lesson-id');
    }

    public static function handleCourseComplete($userId, $courseId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::COURSE_COMPLETED);

        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $courseDetail = LifterLmsHelper::getCourseDetail($courseId);

        $finalData = [
            'user_id'      => $userId,
            'course_id'    => $courseId,
            'course_title' => $courseDetail[0]->post_title,
            'first_name'   => $userInfo['first_name'],
            'last_name'    => $userInfo['last_name'],
            'nickname'     => $userInfo['nickname'],
            'avatar_url'   => $userInfo['avatar_url'],
            'user_email'   => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $courseId, 'course-id');
    }

    public static function handleCourseEnroll($userId, $productId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::COURSE_ENROLLED);

        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $courseDetail = LifterLmsHelper::getCourseDetail($productId);

        $finalData = [
            'user_id'      => $userId,
            'course_id'    => $productId,
            'course_title' => $courseDetail[0]->post_title,
            'first_name'   => $userInfo['first_name'],
            'last_name'    => $userInfo['last_name'],
            'nickname'     => $userInfo['nickname'],
            'avatar_url'   => $userInfo['avatar_url'],
            'user_email'   => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $productId, 'course-id');
    }

    public static function handleCourseUnEnroll($studentId, $courseId, $a, $status)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::COURSE_UNENROLLED);

        if (!$flows || empty($courseId) || $status != 'cancelled') {
            return;
        }

        $userInfo = Utility::getUserInfo($studentId);

        $courseDetail = LifterLmsHelper::getCourseDetail($courseId);

        $finalData = [
            'user_id'      => $studentId,
            'course_id'    => $courseId,
            'course_title' => $courseDetail[0]->post_title,
            'first_name'   => $userInfo['first_name'],
            'last_name'    => $userInfo['last_name'],
            'nickname'     => $userInfo['nickname'],
            'avatar_url'   => $userInfo['avatar_url'],
            'user_email'   => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $courseId, 'course-id');
    }

    public static function handleMembershipCancel($data, $userId)
    {
        $flows = FlowService::exists(LifterLmsTasks::APP_SLUG, LifterLmsTasks::MEMBERSHIP_CANCELLED);

        $productId = $data->get('product_id');

        if (!$flows || !$userId || !$productId) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $membershipDetail = LifterLmsHelper::getMembershipDetail($productId);

        $finalData = [
            'user_id'          => $userId,
            'membership_title' => $productId,
            'membership_id'    => $membershipDetail[0]->post_title,
            'first_name'       => $userInfo['first_name'],
            'last_name'        => $userInfo['last_name'],
            'nickname'         => $userInfo['nickname'],
            'avatar_url'       => $userInfo['avatar_url'],
            'user_email'       => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $productId, 'membership-id');
    }
}
