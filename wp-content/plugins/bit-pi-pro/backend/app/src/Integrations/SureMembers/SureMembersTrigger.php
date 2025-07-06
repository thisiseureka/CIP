<?php

namespace BitApps\PiPro\src\Integrations\SureMembers;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Node;
use BitApps\Pi\Services\FlowService;
use BitApps\Pi\src\Flow\FlowExecutor;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class SureMembersTrigger
{
    public static function handleSureMembersAccessGrant($userId, $accessGroupIds)
    {
        if (get_transient('suremembers_after_access_grant')) {
            return;
        }

        if (\count($accessGroupIds) > 1) {
            set_transient('suremembers_after_access_grant', true, 30);
        }

        $flows = FlowService::exists('sureMembers', 'accessGrant');

        if (empty($flows) || !$flows || empty($userId) || empty($accessGroupIds)) {
            return;
        }

        self::flowExecuteByGroupId($flows, $accessGroupIds, $userId);
    }

    public static function handleSureMembersAccessRevoke($userId, $accessGroupIds)
    {
        if (get_transient('suremembers_after_access_revoke')) {
            return;
        }

        if (\count($accessGroupIds) > 1) {
            set_transient('suremembers_after_access_revoke', true, 30);
        }

        $flows = FlowService::exists('sureMembers', 'accessRevoke');

        if (empty($flows) || !$flows || empty($userId) || empty($accessGroupIds)) {
            return;
        }

        self::flowExecuteByGroupId($flows, $accessGroupIds, $userId);
    }

    public static function handleSureMembersGroupUpdated($suremembersPostId)
    {
        $flows = FlowService::exists('sureMembers', 'groupUpdated');

        if (empty($flows) || !$flows || empty($suremembersPostId)) {
            return;
        }

        $data = SureMembersHelper::sureMembersGroupUpdated($suremembersPostId);

        IntegrationHelper::handleFlowForForm($flows, $data, $suremembersPostId, 'group-id');
    }

    private static function flowExecuteByGroupId($flows, $accessGroupIds, $userId)
    {
        foreach ($flows as $flow) {
            $triggerNode = Node::getNodeInfoById($flow->id . '-1', $flow->nodes);

            if (!$triggerNode) {
                continue;
            }

            $nodeHelper = new NodeInfoProvider($triggerNode);

            $configuredGroupId = $nodeHelper->getFieldMapConfigs('group-id.value');

            if (!\in_array($configuredGroupId, $accessGroupIds)) {
                continue;
            }

            $data = SureMembersHelper::sureMembersGrantOrRevoke($userId, $configuredGroupId);

            if (!$data) {
                continue;
            }

            FlowExecutor::execute($flow, $data);
        }
    }
}
