<?php

namespace BitApps\PiPro\src\Integrations\LearnDash;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class LearnDashTrigger
{
    public static function handleCourseEnroll($userId, $courseId, $access_list, $remove)
    {
        if (!empty($remove)) {
            $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::COURSE_UNENROLL);
        } else {
            $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::COURSE_ENROLL);
        }

        if (!$flows) {
            return;
        }

        $course = get_post($courseId);

        $courseUrl = get_permalink($courseId);

        $resultCourse = [
            'course_id'    => $course->ID,
            'course_title' => $course->post_title,
            'course_url'   => $courseUrl,
        ];

        $user = Utility::getUserInfo($userId);

        $result = $resultCourse + $user;

        IntegrationHelper::handleFlowForForm($flows, $result, $courseId, 'course-id');
    }

    public static function handleLessonCompleted($data)
    {
        $user = $data['user']->data;
        $course = $data['course'];
        $lesson = $data['lesson'];
        if ($course && $user) {
            $courseId = $course->ID;
            $lessonId = $lesson->ID;
            $userId = $user->ID;
        }

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::LESSON_COMPLETED);

        if (!$flows) {
            return;
        }

        $courseUrl = get_permalink($courseId);
        $resultCourse = [
            'course_id'    => $course->ID,
            'course_title' => $course->post_title,
            'course_url'   => $courseUrl,
        ];

        $lessonUrl = get_permalink($lessonId);
        $resultLesson = [
            'lesson_id'    => $lesson->ID,
            'lesson_title' => $lesson->post_title,
            'lesson_url'   => $lessonUrl,
        ];

        $user = Utility::getUserInfo($userId);

        $lessonDataFinal = $resultCourse + $resultLesson + $user;

        IntegrationHelper::handleFlowForForm($flows, $lessonDataFinal, $lessonId, 'lesson-id');
    }

    public static function handleQuizAttempt($data, $user)
    {
        $user = $user->data;
        $course = $data['course'];
        $lesson = $data['lesson'];
        if ($course && $user) {
            $courseId = $course->ID;
            $lessonId = $lesson->ID;
            $userId = $user->ID;
            $quizId = $data['quiz'];
            $score = $data['score'];
            $pass = $data['pass'];
            $totalPoints = $data['total_points'];
            $points = $data['points'];
            $percentage = $data['percentage'];
        }

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::QUIZ_ATTEMPT);

        if (!$flows) {
            return;
        }

        $courseUrl = get_permalink($courseId);

        $resultCourse = [
            'course_id'    => $course->ID,
            'course_title' => $course->post_title,
            'course_url'   => $courseUrl,
        ];

        $lessonUrl = get_permalink($lessonId);

        $resultLesson = [
            'lesson_id'    => $lesson->ID,
            'lesson_title' => $lesson->post_title,
            'lesson_url'   => $lessonUrl,
        ];

        $quizUrl = get_permalink($quizId);

        $quizQueryArgs = [
            'post_type'      => 'sfwd-quiz',
            'post_status'    => 'publish',
            'orderby'        => 'post_title',
            'order'          => 'ASC',
            'posts_per_page' => 1,
            'ID'             => $quizId,
        ];

        $quizList = get_posts($quizQueryArgs);

        $resultQuiz = [
            'quiz_id'      => $quizId,
            'quiz_title'   => $quizList[0]->post_title,
            'quiz_url'     => $quizUrl,
            'score'        => $score,
            'pass'         => $pass,
            'total_points' => $totalPoints,
            'points'       => $points,
            'percentage'   => $percentage,
        ];

        $user = Utility::getUserInfo($userId);

        $quizAttemptDataFinal = $resultCourse + $resultLesson + $resultQuiz + $user;

        IntegrationHelper::handleFlowForForm($flows, $quizAttemptDataFinal, $quizId, 'quiz-id');
    }

    public static function handleTopicCompleted($data)
    {
        if (empty($data)) {
            return;
        }

        $user = $data['user']->data;
        $course = $data['course'];
        $lesson = $data['lesson'];
        $topic = $data['topic'];
        if ($course && $user && $topic) {
            $courseId = $course->ID;
            $lessonId = $lesson->ID;
            $userId = $user->ID;
            $topicId = $topic->ID;
        }

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::TOPIC_COMPLETED);

        if (!$flows) {
            return;
        }

        $courseUrl = get_permalink($courseId);
        $resultCourse = [
            'course_id'    => $course->ID,
            'course_title' => $course->post_title,
            'course_url'   => $courseUrl,
        ];

        $lessonUrl = get_permalink($lessonId);
        $resultLesson = [
            'lesson_id'    => $lesson->ID,
            'lesson_title' => $lesson->post_title,
            'lesson_url'   => $lessonUrl,
        ];

        $topicUrl = get_permalink($topicId);
        $resultTopic = [
            'topic_id'    => $topic->ID,
            'topic_title' => $topic->post_title,
            'topic_url'   => $topicUrl,
        ];

        $user = Utility::getUserInfo($userId);

        $topicDataFinal = $resultCourse + $resultLesson + $resultTopic + $user;

        IntegrationHelper::handleFlowForForm($flows, $topicDataFinal, $topicId, 'topic-id');
    }

    public static function handleCourseCompleted($data)
    {
        $user = $data['user']->data;

        $course = $data['course'];

        if ($course && $user) {
            $courseId = $course->ID;
            $userId = $user->ID;
        }

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::COURSE_COMPLETED);

        if (!$flows) {
            return;
        }

        $courseUrl = get_permalink($courseId);

        $resultCourse = [
            'course_id'    => $course->ID,
            'course_title' => $course->post_title,
            'course_url'   => $courseUrl,
        ];

        $user = Utility::getUserInfo($userId);

        $result = $resultCourse + $user;
        IntegrationHelper::handleFlowForForm($flows, $result, $courseId, 'course-id');
    }

    public static function handleAddedGroup($userId, $groupId)
    {
        if (!$groupId || !$userId) {
            return;
        }

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::GROUP_ADDED);

        if (!$flows) {
            return;
        }

        $group = get_post($groupId);

        $groupUrl = get_permalink($groupId);

        $resultGroup = [
            'group_id'    => $group->ID,
            'group_title' => $group->post_title,
            'group_url'   => $groupUrl,
        ];

        $user = Utility::getUserInfo($userId);

        $groupDataFinal = $resultGroup + $user;

        IntegrationHelper::handleFlowForForm($flows, $groupDataFinal, $groupId, 'group-id');
    }

    public static function handleRemovedGroup($userId, $groupId)
    {
        if (!$groupId || !$userId) {
            return;
        }

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::GROUP_REMOVED);

        if (!$flows) {
            return;
        }

        $group = get_post($groupId);

        $groupUrl = get_permalink($groupId);

        $resultGroup = [
            'group_id'    => $group->ID,
            'group_title' => $group->post_title,
            'group_url'   => $groupUrl,
        ];

        $user = Utility::getUserInfo($userId);

        $groupDataFinal = $resultGroup + $user;

        IntegrationHelper::handleFlowForForm($flows, $groupDataFinal, $groupId, 'group-id');
    }

    public static function handleAssignmentSubmit($assignmentPostId, $assignmentMeta)
    {
        if (!$assignmentPostId || !$assignmentMeta) {
            return;
        }

        $fileName = $assignmentMeta['file_name'];

        $fileLink = $assignmentMeta['file_link'];

        $filePath = $assignmentMeta['file_path'];

        $userId = $assignmentMeta['user_id'];

        $lessonId = $assignmentMeta['lesson_id'];

        $courseId = $assignmentMeta['course_id'];

        $assignmentId = $assignmentPostId;

        $flows = FlowService::exists(LearnDashTasks::APP_SLUG, LearnDashTasks::ASSIGNMENT_SUBMIT);

        if (!$flows) {
            return;
        }

        $course = get_post($courseId);
        $courseUrl = get_permalink($courseId);
        $resultCourse = [
            'course_id'    => $course->ID,
            'course_title' => $course->post_title,
            'course_url'   => $courseUrl,
        ];

        $lesson = get_post($lessonId);
        $lessonUrl = get_permalink($lessonId);
        $resultLesson = [
            'lesson_id'    => $lesson->ID,
            'lesson_title' => $lesson->post_title,
            'lesson_url'   => $lessonUrl,
        ];

        $resultAssignment = [
            'assignment_id' => $assignmentId,
            'file_name'     => $fileName,
            'file_link'     => $fileLink,
            'file_path'     => $filePath,
        ];

        $user = Utility::getUserInfo($userId);

        $assignmentDataFinal = $resultCourse + $resultLesson + $resultAssignment + $user;
        IntegrationHelper::handleFlowForForm($flows, $assignmentDataFinal, $courseId, 'course-id');
    }
}
