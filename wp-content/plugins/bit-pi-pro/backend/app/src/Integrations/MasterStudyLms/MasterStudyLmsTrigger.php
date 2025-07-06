<?php

namespace BitApps\PiPro\src\Integrations\MasterStudyLms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Node;
use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class MasterStudyLmsTrigger
{
    public static function pluginActive()
    {
        return (bool) (is_plugin_active('masterstudy-lms-learning-management-system/masterstudy-lms-learning-management-system.php'))

        ;
    }

    public function getAll()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'MasterStudy Lms'));
        }

        $types = [
            __('User Complete a Course', 'bit-pi'),
            __('User Complete a Lesson', 'bit-pi'),
            __('User Enrolled in a Course', 'bit-pi'),
            __('User Passed a Quiz', 'bit-pi'),
            __('User Failed a Quiz', 'bit-pi'),
            __('User Earns a Point', 'bit-pi'),
        ];

        $masterStudyLmsAction = [];

        foreach ($types as $index => $type) {
            $masterStudyLmsAction[] = (object) [
                'value' => $index + 1,
                'label' => $type,
            ];
        }

        return Response::success($masterStudyLmsAction);
    }

    public static function getAllQuizByCourse($data)
    {
        $quizzes = MasterStudyLmsHelper::getAllQuiz($data->course_id);
        if (empty($quizzes)) {
            return Response::error(__('No quiz Found', 'bit-pi'));
        }

        foreach ($quizzes as $value) {
            $allQuiz[] = [
                'value' => $value->ID,
                'label' => $value->post_title,
            ];
        }

        $allQuiz = array_merge([['value' => 'any', 'label' => __('Any Quiz', 'bit-pi')]], $allQuiz);

        return Response::success($allQuiz);
    }

    public static function handleCourseComplete($courseId, $userId)
    {
        $flows = FlowService::exists('masterStudyLms', 'progressUpdated');
        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);

        $courseDetails = MasterStudyLmsHelper::getCourseDetail($courseId);

        $finalData = [
            'user_id'            => $userId,
            'course_id'          => $courseId,
            'course_title'       => $courseDetails[0]->post_title,
            'course_description' => $courseDetails[0]->post_content,
            'first_name'         => $userInfo['first_name'],
            'last_name'          => $userInfo['last_name'],
            'nickname'           => $userInfo['nickname'],
            'avatar_url'         => $userInfo['avatar_url'],
            'user_email'         => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $courseId, 'course-id');
    }

    public static function handlePointScoreCharge($userId, $actionId, $score)
    {
        $flows = FlowService::exists('masterStudyLms', 'friendshipAcceptedScore');

        if (!$flows) {
            return;
        }

        $actions = stm_lms_point_system();
        $action = $actions[$actionId] ?? [];

        if (empty($action)) {
            return;
        }

        $finalData = MasterStudyLmsHelper::prepareFinalData($userId, $action, $score);

        foreach ($flows as $flow) {
            $triggerNode = Node::getNodeInfoById($flow->id . '-1', $flow->nodes);

            if (!$triggerNode) {
                continue;
            }

            $nodeHelper = new NodeInfoProvider($triggerNode);

            $configuredDistributionId = $nodeHelper->getFieldMapConfigs('distribution-id.value');

            if (
                ($actionId == 'user_registered' && $configuredDistributionId == 'user_registered_affiliate')
                || ($actionId == 'course_purchased' && $configuredDistributionId == 'course_purchased_affiliate')
            ) {
                $finalData = MasterStudyLmsHelper::prepareFinalData($userId, $action, $score, $configuredDistributionId, true);
            } elseif ($configuredDistributionId != 'any' && $configuredDistributionId != $actionId) {
                continue;
            }

            IntegrationHelper::handleFlowForForm([$flow], $finalData, $actionId, 'distribution-id');
        }
    }

    public static function handleCourseEnroll($userId, $courseId)
    {
        $flows = FlowService::exists('masterStudyLms', 'addUserCourse');
        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);
        $courseDetails = MasterStudyLmsHelper::getCourseDetail($courseId);

        $finalData = [
            'user_id'            => $userId,
            'course_id'          => $courseId,
            'course_title'       => $courseDetails[0]->post_title,
            'course_description' => $courseDetails[0]->post_content,
            'first_name'         => $userInfo['first_name'],
            'last_name'          => $userInfo['last_name'],
            'nickname'           => $userInfo['nickname'],
            'avatar_url'         => $userInfo['avatar_url'],
            'user_email'         => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $courseId, 'course-id');
    }

    public static function handleLessonComplete($userId, $lessonId)
    {
        $flows = FlowService::exists('masterStudyLms', 'lessonPassed');
        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);
        $lessonDetails = MasterStudyLmsHelper::getLessonDetail($lessonId);

        $finalData = [
            'user_id'            => $userId,
            'lesson_id'          => $lessonId,
            'lesson_title'       => $lessonDetails[0]->post_title,
            'lesson_description' => $lessonDetails[0]->post_content,
            'first_name'         => $userInfo['first_name'],
            'last_name'          => $userInfo['last_name'],
            'nickname'           => $userInfo['nickname'],
            'avatar_url'         => $userInfo['avatar_url'],
            'user_email'         => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $lessonId, 'lesson-id');
    }

    public static function handleQuizComplete($userId, $quizId)
    {
        $flows = FlowService::exists('masterStudyLms', 'quizPassed');
        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);
        $quizDetails = MasterStudyLmsHelper::getQuizDetails($quizId);
        // TODO:: need to check for course
        // $flowDetails = json_decode($flows[0]->flow_details);
        // $selectedCourse = empty($flowDetails->selectedCourse) ? [] : $flowDetails->selectedCourse;

        $courseDetails = MasterStudyLmsHelper::getCourseDetail([]);

        $finalData = [
            'user_id' => $userId,
            // 'course_id'          => $selectedCourse,
            'course_title'       => $courseDetails[0]->post_title,
            'course_description' => $courseDetails[0]->post_content,
            'quiz_id'            => $quizId,
            'quiz_title'         => $quizDetails[0]->post_title,
            'quiz_description'   => $quizDetails[0]->post_content,
            'first_name'         => $userInfo['first_name'],
            'last_name'          => $userInfo['last_name'],
            'nickname'           => $userInfo['nickname'],
            'avatar_url'         => $userInfo['avatar_url'],
            'user_email'         => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $quizId, 'quiz-id');
    }

    public static function handleQuizFailed($userId, $quizId)
    {
        $flows = FlowService::exists('masterStudyLms', 'quizFailed');
        if (!$flows) {
            return;
        }

        $userInfo = Utility::getUserInfo($userId);
        $quizDetails = MasterStudyLmsHelper::getQuizDetails($quizId);

        $flowDetails = json_decode($flows[0]->flow_details);
        $selectedCourse = empty($flowDetails->selectedCourse) ? [] : $flowDetails->selectedCourse;
        $courseDetails = MasterStudyLmsHelper::getCourseDetail($selectedCourse);

        $finalData = [
            'user_id'            => $userId,
            'course_id'          => $selectedCourse,
            'course_title'       => $courseDetails[0]->post_title,
            'course_description' => $courseDetails[0]->post_content,
            'quiz_id'            => $quizId,
            'quiz_title'         => $quizDetails[0]->post_title,
            'quiz_description'   => $quizDetails[0]->post_content,
            'first_name'         => $userInfo['first_name'],
            'last_name'          => $userInfo['last_name'],
            'nickname'           => $userInfo['nickname'],
            'avatar_url'         => $userInfo['avatar_url'],
            'user_email'         => $userInfo['user_email'],
        ];

        IntegrationHelper::handleFlowForForm($flows, $finalData, $quizId, 'quiz-id');
    }

    // when edit course

    public static function getAllCourseEdit()
    {
        $allCourse = MasterStudyLmsHelper::getAllCourse();
        $allCourse = array_merge(
            [
                [
                    'value' => 'any',
                    'label' => __('Any Course', 'bit-pi')
                ],
            ],
            $allCourse
        );

        return Response::success($allCourse);
    }

    public static function getAllDistributionEdit()
    {
        return Response::success(MasterStudyLmsHelper::getAllDistribution());
    }

    public static function getAllLessonEdit()
    {
        $allLesson = MasterStudyLmsHelper::getAllLesson();
        $allLesson = array_merge(
            [
                [
                    'value' => 'any',
                    'label' => __('Any Lesson', 'bit-pi')
                ],
            ],
            $allLesson
        );

        return Response::success($allLesson);
    }
}
