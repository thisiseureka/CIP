<?php

namespace BitApps\PiPro\src\Integrations\UserFeedback;

use BitApps\Pi\src\Integrations\HookRegisterInterface;

if (!\defined('ABSPATH')) {
    exit;
}

class UserFeedbackHooks implements HookRegisterInterface
{
    public function register(): array
    {
        return [
            'surveyResponse' => [
                'hook'          => 'userfeedback_survey_response',
                'callback'      => [UserFeedbackTrigger::class, 'handleSurveyResponse'],
                'priority'      => 10,
                'accepted_args' => 3,
            ],
        ];
    }
}
