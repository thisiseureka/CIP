<?php

namespace BitApps\PiPro\src\Integrations\UserFeedback;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}

use UserFeedback_Survey;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class UserFeedbackTrigger
{
    public static function handleSurveyResponse($surveyId, $responseId, $request)
    {
        if (empty($surveyId) || empty($responseId) || !class_exists('UserFeedback_Survey')) {
            return;
        }

        $survey = UserFeedback_Survey::get_by('id', $surveyId);

        if (empty($survey) || empty($survey->questions)) {
            return;
        }

        $questions = array_column($survey->questions, 'title', 'id');
        $request['answers'] = array_map(
            function ($answer) use ($questions) {
                $questionId = $answer['question_id'] ?? null;
                $answer['question_title'] = $questions[$questionId] ?? '';

                return $answer;
            },
            $request['answers']
        );

        $formData = ['survey_id' => $surveyId, 'response_id' => $responseId, 'request' => $request];
        $flows = FlowService::exists('UserFeedback', 'surveyResponse');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('UserFeedback_Base');
    }
}
