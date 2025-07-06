<?php

namespace BitApps\PiPro\src\Integrations\GoogleSheet;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\src\Authorization\AuthorizationFactory;
use BitApps\Pi\src\Authorization\AuthorizationType;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Interfaces\ActionInterface;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;

class GoogleSheetAction implements ActionInterface
{
    public const BASE_URL = 'https://sheets.googleapis.com/v4';

    public const AUTH_TOKEN_URL = 'https://oauth2.googleapis.com/token';

    public const ADD_ROW = 'addRow';

    public const APPEND_OR_UPDATE_ROW = 'appendOrUpdateRow';

    private GoogleSheetsRow $googleSheetRow;

    private NodeInfoProvider $nodeInfoProvider;

    public function __construct(NodeInfoProvider $nodeInfoProvider)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
    }

    /**
     * Execute the action.
     */
    public function execute(): array
    {
        $data = $this->executeSheetAction();

        if (isset($data['response']->spreadsheetId)) {
            return [
                'status' => 'success',
                'output' => $data['response'],
                'input'  => $data['payload'],
            ];
        }

        return [
            'status' => 'error',
            'output' => $data['response'],
            'input'  => $data['payload'],
        ];
    }

    private function executeSheetAction(): array
    {
        $configs = $this->nodeInfoProvider->getFieldMapConfigs();

        $sheetAction = $this->nodeInfoProvider->getMachineSlug();

        $repeaters = $this->nodeInfoProvider->getFieldMapRepeaters('row-data.value', false, false);

        $mappedColumnValue = [];

        foreach ($repeaters as $repeater) {
            $mappedColumnValue[$this->excelColumnToIndex($repeater['column'])] = $repeater['value'];
        }

        $headers = $this->getAuthorizationHeader($configs['connection-id']['value']);

        if (isset($headers['error'])) {
            return $headers;
        }

        $this->googleSheetRow = new GoogleSheetsRow(new HttpClient(['headers' => $headers]), static::BASE_URL);

        if ($sheetAction === self::ADD_ROW) {
            return $this->googleSheetRow->createRow($configs, $mappedColumnValue);
        }

        if ($sheetAction === self::APPEND_OR_UPDATE_ROW) {
            return $this->googleSheetRow->appendOrUpdateRow($configs, $mappedColumnValue);
        }
    }

    private function excelColumnToIndex($column)
    {
        $column = strtoupper($column);

        $length = \strlen($column);

        $index = 0;

        for ($i = 0; $i < $length; ++$i) {
            $index *= 26;
            $index += \ord($column[$i]) - \ord('A') + 1;
        }

        return $index - 1;
    }

    private function getAuthorizationHeader($connectionId): array
    {
        $accessToken = AuthorizationFactory::getAuthorizationHandler(
            AuthorizationType::OAUTH2,
            $connectionId
        )->setRefreshTokenUrl(self::AUTH_TOKEN_URL)->getAccessToken();

        if (!\is_string($accessToken)) {
            return $accessToken;
        }

        return [
            'Authorization' => $accessToken,
            'Content-Type'  => 'application/json'
        ];
    }
}
