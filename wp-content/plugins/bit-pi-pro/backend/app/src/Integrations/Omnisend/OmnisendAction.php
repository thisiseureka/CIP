<?php

namespace BitApps\PiPro\src\Integrations\Omnisend;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;

class OmnisendAction implements ActionInterface
{
    private NodeInfoProvider $nodeInfoProvider;

    private OmnisendHelper $omnisendApi;

    private $machineSlug;

    private $connectionId;

    private $splitChannels;

    private $status;

    private $dataArr;

    private $eventData;

    private $limitInfo;

    private $afterContactId;

    private $contactId;

    private $offsetId;

    private $sortStatus;

    private $productId;

    private $productData;

    private $tokenAuthorization;

    private $apiKey;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute(): array
    {
        $executedNodeAction = $this->executeOmnisendAction();

        return Utility::formatResponseData(
            $executedNodeAction['status_code'],
            $executedNodeAction['payload'],
            $executedNodeAction['response']
        );
    }

    private function executeMachine(): array
    {
        switch ($this->machineSlug) {
            case 'createSubscriber':
                $this->dataArr = $this->formatData($this->dataArr);

                return $this->omnisendApi->createNewContact($this->dataArr, $this->splitChannels, $this->status);

            case 'listContacts':
                return $this->omnisendApi->getContactsList($this->afterContactId, $this->limitInfo);

            case 'updateContacts':
                $this->dataArr = $this->formatData($this->dataArr);

                return $this->omnisendApi->updateContacts($this->contactId, $this->dataArr);

            case 'getContact':
                return $this->omnisendApi->getContact($this->afterContactId);

            case 'sendCustomerEvent':
                $this->eventData = $this->formatData($this->eventData);

                return $this->omnisendApi->sendCustomerEvent($this->eventData);

            case 'listProduct':
                return $this->omnisendApi->getProductList($this->offsetId, $this->limitInfo, $this->sortStatus);

            case 'getProduct':
                return $this->omnisendApi->getProduct($this->productId);

            case 'deleteProduct':
                return $this->omnisendApi->deleteProduct($this->productId);

            case 'createProduct':
                return $this->omnisendApi->createProduct($this->productData);
        }

        return [];
    }

    private function setNodeInfoProperties()
    {
        $this->machineSlug = $this->nodeInfoProvider->getMachineSlug();

        $this->connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');
        $this->splitChannels = $this->nodeInfoProvider->getFieldMapConfigs('subscriber-type.value');
        $this->status = $this->nodeInfoProvider->getFieldMapConfigs('subscriber-status.value');
        $this->dataArr = $this->nodeInfoProvider->getFieldMapRepeaters('row-data.value', false, true, 'omnisendField', 'value');
        $this->eventData = $this->nodeInfoProvider->getFieldMapRepeaters('event-data.value', false, true, 'omnisendField', 'value');

        $this->limitInfo = $this->nodeInfoProvider->getFieldMapConfigs('limit-info.value');
        $this->afterContactId = $this->nodeInfoProvider->getFieldMapConfigs('after-contactId.value');
        $this->contactId = $this->nodeInfoProvider->getFieldMapConfigs('contact-id.value');
        $this->offsetId = $this->nodeInfoProvider->getFieldMapConfigs('offset-id.value');
        $this->sortStatus = $this->nodeInfoProvider->getFieldMapConfigs('sort-status.value');
        $this->productId = $this->nodeInfoProvider->getFieldMapConfigs('product-id.value');
        $this->productData = $this->nodeInfoProvider->getFieldMapRepeaters('product-data.value', false, true, 'omnisendField', 'value');

        $this->tokenAuthorization = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::API_KEY,
            $this->connectionId
        );
        $this->apiKey = $this->tokenAuthorization->getAccessToken();
    }

    private function executeOmnisendAction(): array
    {
        $this->setNodeInfoProperties();
        $this->omnisendApi = new OmnisendHelper($this->tokenAuthorization, $this->apiKey);

        return $this->executeMachine();
    }

    private function formatData($data = [])
    {
        $newData = [];
        foreach ($data as $key => $value) {
            if ($key === 'Email') {
                $newData['email'] = $value;

                continue;
            }

            $newData[$key] = $value;
        }

        return $newData;
    }
}
