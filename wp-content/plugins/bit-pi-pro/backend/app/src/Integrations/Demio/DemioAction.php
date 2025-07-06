<?php

namespace BitApps\PiPro\src\Integrations\Demio;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class DemioAction implements ActionInterface
{
    private NodeInfoProvider $nodeInfoProvider;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeDemioAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeDemioAction()
    {
        $machineSlug = $this->nodeInfoProvider->getMachineSlug();

        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');
        $eventId = $this->nodeInfoProvider->getFieldMapConfigs('event-id.value');
        $activeStatus = $this->nodeInfoProvider->getFieldMapConfigs('active-status.value');
        $sessionId = $this->nodeInfoProvider->getFieldMapConfigs('session-id.value');
        $dataArr = $this->nodeInfoProvider->getFieldMapRepeaters('row-data.value', false, true, 'demioField', 'value');

        $tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::API_KEY,
            $connectionId
        );
        $autoInfo = $tokenAuthorization->getAuthDetails();
        $header = [
            'accept'       => 'application/json',
            'Api-Secret'   => $autoInfo->value,
            'Api-Key'      => $autoInfo->key,
            'content-type' => 'application/json'
        ];

        $demioEventObj = new DemioEvent($header);

        switch ($machineSlug) {
            case 'registerEvent':
                $dataArr = $this->formatData($eventId, $sessionId, $dataArr);

                return $demioEventObj->createNewContact($dataArr);

            case 'getEvent':
                return $demioEventObj->getEventList();

            case 'getSession':
                return $demioEventObj->getSessionList($eventId);

            case 'infoEvent':
                return $demioEventObj->getEventInfo($eventId, $activeStatus);
        }
    }

    private function formatData($eventId, $sessionId, $data = [])
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if ($key === 'Email') {
                $newData['email'] = $value;

                continue;
            }

            $newData[$key] = $value;
        }

        $newData['id'] = $eventId;
        $newData['date_id'] = $sessionId;

        return $newData;
    }
}
