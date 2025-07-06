<?php

namespace BitApps\PiPro\src\Integrations\WpJobManager;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Node;
use BitApps\Pi\Services\FlowService;
use BitApps\Pi\src\Flow\FlowExecutor;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class WpJobManagerTrigger
{
    public static function handleWPJobManagerJobPublished($newStatus, $oldStatus, $post)
    {
        if (empty($newStatus) || empty($oldStatus) || empty($post)) {
            return false;
        }

        if (isset($post->post_type) && ($post->post_type !== 'job_listing' || $oldStatus === 'publish' || $newStatus !== 'publish')) {
            return false;
        }

        $terms = get_the_terms($post->ID, 'job_listing_type');

        $value = empty($terms) ? 'empty_terms' : $terms[0]->term_id;

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::JOB_PUBLISHED);

        if (empty($flows) || !$flows) {
            return;
        }

        $jobPublishedData = WpJobManagerHelper::formatJobPublishedData($post, $terms);

        foreach ($flows as $flow) {
            $triggerNode = Node::getNodeInfoById($flow->id . '-1', $flow->nodes);

            if (!$triggerNode) {
                continue;
            }

            if ($value) {
                $nodeHelper = new NodeInfoProvider($triggerNode);

                $configuredFormValue = $nodeHelper->getFieldMapConfigs('job-type-id.value');

                if ($configuredFormValue !== 'any' || $value !== 'empty_terms') {
                    $value = (int) $value;

                    $configuredFormValue = (int) $configuredFormValue;

                    if ($configuredFormValue !== $value) {
                        continue;
                    }
                }
            }

            FlowExecutor::execute($flow, $jobPublishedData);
        }
    }

    public static function handleWPJobManagerJobFilled($action, $jobId)
    {
        if (empty($jobId)) {
            return false;
        }

        if ($action !== 'mark_filled') {
            return;
        }

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::JOB_FILLED);

        if (empty($flows) || !$flows) {
            return;
        }

        $jobFilledData = WpJobManagerHelper::formatJobFilledData($jobId);

        if (!empty($jobFilledData)) {
            IntegrationHelper::handleFlowForForm($flows, $jobFilledData, $jobId, 'job-id');
        }
    }

    public static function handleWPJobManagerJobNotFilled($action, $jobId)
    {
        if (empty($jobId)) {
            return false;
        }

        if ($action !== 'mark_not_filled') {
            return;
        }

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::JOB_NOT_FILLED);

        if (empty($flows) || !$flows) {
            return;
        }

        $jobNotFilledData = WpJobManagerHelper::formatJobFilledData($jobId);

        if (!empty($jobNotFilledData)) {
            IntegrationHelper::handleFlowForForm($flows, $jobNotFilledData, $jobId, 'job-id');
        }
    }

    public static function handleSpecificTypeJobFilled($action, $jobId)
    {
        if (empty($jobId)) {
            return false;
        }

        if ($action !== 'mark_filled') {
            return;
        }

        $jobTypes = wpjm_get_the_job_types($jobId);

        if (empty($jobTypes)) {
            return;
        }

        $typeId = $jobTypes[0]->term_id;

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::SPECIFIC_JOB_FILLED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatJobFilledData($jobId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $typeId, 'job-type-id');
        }
    }

    public static function handleSpecificTypeJobNotFilled($action, $jobId)
    {
        if (empty($jobId)) {
            return false;
        }

        if ($action !== 'mark_not_filled') {
            return;
        }

        $jobTypes = wpjm_get_the_job_types($jobId);

        if (empty($jobTypes)) {
            return;
        }

        $typeId = $jobTypes[0]->term_id;

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::SPECIFIC_JOB_NOT_FILLED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatJobFilledData($jobId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $typeId, 'job-type-id');
        }
    }

    public static function handleJobUpdate($jobId)
    {
        if (empty($jobId)) {
            return false;
        }

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::JOB_UPDATED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatJobFilledData($jobId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $jobId, 'job-id');
        }
    }

    public static function handleApplicationSubmit($applicationId, $jobId)
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_APPLICATION_PLUGIN_INDEX)) {
            return;
        }

        if (empty($applicationId) || empty($jobId)) {
            return;
        }

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::APPLICATION_SUBMITTED);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatApplicationData($applicationId, $jobId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $jobId, 'job-id');
        }
    }

    public static function handleApplicationSubmitSpecificType($applicationId, $jobId)
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_APPLICATION_PLUGIN_INDEX)) {
            return;
        }

        if (empty($applicationId) || empty($jobId)) {
            return;
        }

        $jobTypes = wpjm_get_the_job_types($jobId);

        if (empty($jobTypes)) {
            return;
        }

        $typeId = $jobTypes[0]->term_id;

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::APPLICATION_SPECIFIC_TYPE_SUBMITTED);

        if (!$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatApplicationData($applicationId, $jobId);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $typeId, 'job-type-id');
        }
    }

    public static function handleApplicationStatusChange($postId, $postAfter, $postBefore)
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_APPLICATION_PLUGIN_INDEX)) {
            return;
        }

        if (empty($postId) || empty($postAfter) || empty($postBefore)) {
            return;
        }

        if ($postAfter->post_type !== 'job_application' || $postAfter->post_status === $postBefore->post_status) {
            return;
        }

        $newStatus = $postAfter->post_status;

        $oldStatus = $postBefore->post_status;

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::APPLICATION_STATUS_CHANGED);

        if (!$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatApplicationStatusData($postId, $newStatus, $oldStatus);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $newStatus, 'application-status-id', 'string');
        }
    }

    public static function handleJobTypeApplicationStatusChange($postId, $postAfter, $postBefore)
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_APPLICATION_PLUGIN_INDEX)) {
            return;
        }

        if (empty($postId) || empty($postAfter) || empty($postBefore)) {
            return;
        }

        if ($postAfter->post_type !== 'job_application' || $postAfter->post_status === $postBefore->post_status) {
            return;
        }

        $newStatus = $postAfter->post_status;

        $oldStatus = $postBefore->post_status;

        $jobId = $postAfter->post_parent;

        $jobTypes = wpjm_get_the_job_types($jobId);

        if (empty($jobTypes)) {
            return;
        }

        $typeId = $jobTypes[0]->term_id;

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::JOB_TYPE_APPLICATION_STATUS_CHANGED);

        if (!$flows) {
            return;
        }

        $data = WpJobManagerHelper::formatApplicationStatusData($postId, $newStatus, $oldStatus);

        foreach ($flows as $flow) {
            $triggerNode = Node::getNodeInfoById($flow->id . '-1', $flow->nodes);

            if (!$triggerNode) {
                continue;
            }

            $nodeHelper = new NodeInfoProvider($triggerNode);

            $configuredJobId = $nodeHelper->getFieldMapConfigs('job-type-id.value');

            $configuredStatus = $nodeHelper->getFieldMapConfigs('application-status-id.value');

            if ($configuredJobId !== 'any' && (int) $configuredJobId !== (int) $typeId) {
                continue;
            }

            if ($configuredStatus !== 'any' && $configuredStatus !== $newStatus) {
                continue;
            }

            FlowExecutor::execute($flow, $data);
        }
    }

    public static function handleApplyWithResume($userId, $jobId, $resumeId, $applicationMessage)
    {
        if (!is_plugin_active(WpJobManagerTasks::WP_JOB_RESUME_PLUGIN_INDEX)) {
            return;
        }

        if (empty($userId) || empty($jobId) || empty($resumeId)) {
            return;
        }

        $flows = FlowService::exists(WpJobManagerTasks::APP_SLUG, WpJobManagerTasks::APPLY_WITH_RESUME);

        if (empty($flows) || !$flows) {
            return;
        }

        $data = WpJobManagerHelper::handleResumeData($resumeId, $applicationMessage);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data, $jobId, 'job-type-id');
        }
    }
}
