<?php

namespace BitApps\PiPro\src\Integrations\Paymattic;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use WPPayForm\App\Models\Submission;
use BitApps\Pi\Deps\BitApps\WPKit\Helpers\JSON;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class PaymatticTrigger
{
    public static function paymentFormSubmission($submissionInstance)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        return self::execute('paymentFormSubmission', self::jsonDecode($submissionInstance));
    }

    public static function paymentStatusChanged($submissionId, $newStatus)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        return self::execute(
            'paymentStatusChanged',
            [
                'submission_data' => self::getSubmissionById($submissionId),
                'new_status'      => $newStatus
            ]
        );
    }

    public static function paymentSuccess($submissionInstance, $transactionInstance, $formId, $updateData)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        return self::execute(
            'paymentSuccess',
            [
                'form_id'          => $formId,
                'submission_data'  => self::jsonDecode($submissionInstance),
                'transaction_data' => self::jsonDecode($transactionInstance),
                'update_data'      => $updateData
            ]
        );
    }

    public static function paymentFailed($submissionInstance, $formId, $transactionInstance, $updateData)
    {
        if (!self::isPluginInstalled()) {
            return;
        }

        return self::execute(
            'paymentSuccess',
            [
                'form_id'          => $formId,
                'submission_data'  => self::jsonDecode($submissionInstance),
                'transaction_data' => self::jsonDecode($transactionInstance),
                'update_data'      => $updateData
            ]
        );
    }

    public static function noteCreatedByUser($note)
    {
        if (!self::isPluginInstalled() || empty($note['submission_id'])) {
            return;
        }

        return self::execute(
            'noteCreatedByUser',
            array_merge(
                $note,
                ['submission_data' => self::getSubmissionById($note['submission_id'])]
            )
        );
    }

    private static function execute($machineSlug, $data)
    {
        $flows = FlowService::exists('Paymattic', $machineSlug);

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    private static function getSubmissionById($id)
    {
        $submissionInstance = (new Submission())->getSubmission($id);

        return self::jsonDecode($submissionInstance);
    }

    private static function jsonDecode($data)
    {
        return JSON::decode(JSON::encode($data));
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return \defined('WPPAYFORM_VERSION');
    }
}
