<?php

namespace BitApps\PiPro\src\Integrations\SendFox;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;
use InvalidArgumentException;

class SendFoxAction implements ActionInterface
{
    public const BASE_URL = 'https://api.sendfox.com/';

    private NodeInfoProvider $nodeInfoProvider;

    private SendFoxContact $contactService;

    private SendFoxCampaign $campaignService;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    public function execute()
    {
        $payload = $this->nodeInfoProvider->getFieldMapData();

        $response = (array) $this->executeSendFoxAction($payload);

        if (empty($response['id'])) {
            return [
                'success'  => false,
                'response' => $response,
                'payload'  => $payload,
            ];
        }

        return [
            'success'  => true,
            'response' => $response,
            'payload'  => $payload,
        ];
    }

    private function executeSendFoxAction($data)
    {
        $connectionId = $this->nodeInfoProvider->getFieldMapConfigs('connection-id.value');

        $accessToken = AuthorizationFactory::getAuthorizationHandler(AuthorizationType::BEARER_TOKEN, $connectionId)->getAccessToken();

        $httpClient = new HttpClient(['headers' => ['Authorization' => $accessToken]]);

        $sendFoxAction = $this->nodeInfoProvider->getMachineSlug();

        $this->contactService = new SendFoxContact($httpClient, static::BASE_URL);

        $this->campaignService = new SendFoxCampaign($httpClient, static::BASE_URL);

        switch ($sendFoxAction) {
            case 'create-contact':
                return $this->contactService->createContact($data);
            case 'contact-list':
                return $this->contactService->contactList($data);
            case 'unsubscribe-email':
                return $this->contactService->unsubscribeEmail($data);
            case 'get-contact-by-email':
                return $this->contactService->getContactByEmail($data);
            case 'get-contact-by-id':
                return $this->contactService->getContactById($data);
            case 'list':
                return $this->campaignService->all();
            default:
                throw new InvalidArgumentException("Unknown action: {$sendFoxAction}");
        }
    }
}
