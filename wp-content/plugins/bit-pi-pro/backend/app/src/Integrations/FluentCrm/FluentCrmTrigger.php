<?php

namespace BitApps\PiPro\src\Integrations\FluentCrm;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Node;
use BitApps\Pi\Services\FlowService;
use BitApps\Pi\src\Flow\FlowExecutor;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class FluentCrmTrigger
{
    public static function handleAddTag($tagIds, $subscriber)
    {
        $flows = FlowService::exists('fluentCrm', 'addTag');

        if (!$flows) {
            return;
        }

        $email = $subscriber->email;

        $data = ['tag_ids' => $tagIds];

        $dataContact = FluentCrmHelper::getContactData($email);

        $data += $dataContact;

        self::executeFlowsByConfigId($flows, $data, $tagIds, 'tag-id');
    }

    public static function handleRemoveTag($tagIds, $subscriber)
    {
        $flows = FlowService::exists('fluentCrm', 'removeTag');

        if (!$flows) {
            return;
        }

        $email = $subscriber->email;

        $data = ['removed_tag_ids' => $tagIds];

        $dataContact = FluentCrmHelper::getContactData($email);

        $data += $dataContact;

        self::executeFlowsByConfigId($flows, $data, $tagIds, 'tag-id');
    }

    public static function handleAddList($listIds, $subscriber)
    {
        $flows = FlowService::exists('fluentCrm', 'addList');

        if (!$flows) {
            return;
        }

        $email = $subscriber->email;

        $data = ['list_ids' => $listIds];

        $dataContact = FluentCrmHelper::getContactData($email);

        $data += $dataContact;

        self::executeFlowsByConfigId($flows, $data, $listIds, 'list-id');
    }

    public static function handleRemoveList($listIds, $subscriber)
    {
        $flows = FlowService::exists('fluentCrm', 'removeList');

        if (!$flows) {
            return;
        }

        $email = $subscriber->email;

        $data = ['remove_list_ids' => $listIds];

        $dataContact = FluentCrmHelper::getContactData($email);

        $data += $dataContact;

        self::executeFlowsByConfigId($flows, $data, $listIds, 'list-id');
    }

    public static function handleChangeStatus($subscriber, $oldStatus)
    {
        $newStatus = $subscriber->status;

        $flows = FlowService::exists('fluentCrm', 'crmStatusUpdated');

        $email = $subscriber->email;

        $data = [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ];

        $dataContact = FluentCrmHelper::getContactData($email);

        $data += $dataContact;

        IntegrationHelper::handleFlowForForm($flows, $data, $newStatus, 'status-id', 'string');
    }

    public static function handleContactCreate($subscriber)
    {
        $flows = FlowService::exists('fluentCrm', 'contactCreate');
        if (!$flows) {
            return;
        }

        $email = $subscriber->email;

        $data = FluentCrmHelper::getContactData($email);

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    private static function executeFlowsByConfigId($flows, $data, $values, $keyName)
    {
        foreach ($flows as $flow) {
            $triggerNode = Node::getNodeInfoById($flow->id . '-1', $flow->nodes);

            if (!$triggerNode) {
                continue;
            }

            $nodeHelper = new NodeInfoProvider($triggerNode);

            $configId = $nodeHelper->getFieldMapConfigs($keyName . '.value');

            if (\is_array($values) && !\in_array($configId, $values) && $configId !== 'any') {
                continue;
            }

            FlowExecutor::execute($flow, $data);
        }
    }
}
