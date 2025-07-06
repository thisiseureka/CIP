<?php

namespace BitApps\PiPro\src\Integrations\GoogleSheet;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Helpers\JSON;

final class GoogleSheetsRow
{
    private const COLUMN_RANGE = '!A:A';

    private const VALUE_INPUT_OPTION = 'USER_ENTERED&insertDataOption=INSERT_ROWS';

    private $http;

    private $baseUrl;

    /**
     * GoogleSheetService constructor.
     *
     * @param $httpClient
     * @param $baseUrl
     */
    public function __construct($httpClient, $baseUrl)
    {
        $this->http = $httpClient;
        $this->baseUrl = $baseUrl;
    }

    public function createRow($configs, $data)
    {
        if (\count($data) === 0) {
            return ['response' => 'No columns added! Please add some columns and map the fields.', 'payload' => ''];
        }

        $spreadsheetsId = empty($configs['spreadsheet-id']['value']) ? '' : $configs['spreadsheet-id']['value'];

        $workSheetName = empty($configs['sheet-title']['value']) ? '' : $configs['sheet-title']['value'];

        $range = $workSheetName . self::COLUMN_RANGE;

        $url = $this->baseUrl . '/spreadsheets/' . $spreadsheetsId . '/values/' . $range . ':append?valueInputOption=' . self::VALUE_INPUT_OPTION;

        $arrLength = max(array_keys($data)) + 1;

        $values = array_fill(0, $arrLength, '');

        foreach ($data as $key => $value) {
            if ($key === -1) {
                continue;
            }

            $values[$key] = $value;
        }

        $payload = [
            'range'          => $range,
            'majorDimension' => 'ROWS',
            'values'         => [$values]
        ];

        $response = $this->http->request($url, 'POST', JSON::encode($payload));

        return ['response' => $response, 'payload' => $payload];
    }

    public function appendOrUpdateRow($configs, $data)
    {
        $spreadsheetsId = empty($configs['spreadsheet-id']['value']) ? '' : $configs['spreadsheet-id']['value'];

        $workSheetName = empty($configs['sheet-title']['value']) ? '' : $configs['sheet-title']['value'];

        $fetchedData = $this->fetchSheetData($spreadsheetsId, $workSheetName);
        $columnToMatch = $this->getColumnToMatchId($configs);

        $rowToUpdate = ['matchedRow' => -1, 'values' => []];
        if (\array_key_exists($columnToMatch, $data) && property_exists($fetchedData, 'values') && \count($fetchedData->values)) {
            $rowToUpdate = $this->getRowToUpdate($fetchedData->values, $columnToMatch, $data[$columnToMatch]);
        }

        if ($rowToUpdate['matchedRow'] > 0) {
            $url = $this->baseUrl . '/spreadsheets/' . $spreadsheetsId . '/values:batchUpdate';
            $payload = $this->prepareDataForUpdate($workSheetName, $rowToUpdate['matchedRow'], $rowToUpdate['values'], $data);
            $response = $this->http->request($url, 'POST', JSON::encode($payload));

            return ['response' => $response, 'payload' => $payload];
        }

        return $this->createRow($configs, $data);
    }

    private function getColumnToMatchId(array $configs)
    {
        return !empty($configs['column-to-match-on']['value']) && strpos($configs['column-to-match-on']['value'], ':') !== false ? explode(':', $configs['column-to-match-on']['value'])[0] : '';
    }

    private function fetchSheetData(string $spreadsheetId, string $workSheetName, string $range = null)
    {
        $cellRange = '';

        if ($range) {
            $cellRange = $range[0] === '!' ? $range : '!' . $range;
        }

        $url = $this->baseUrl . '/spreadsheets/' . $spreadsheetId . '/values/' . urlencode($workSheetName) . $cellRange;

        return $this->http->request(
            $url,
            'GET',
            [
                'valueRenderOption'    => 'FORMATTED_VALUE',
                'dateTimeRenderOption' => 'FORMATTED_STRING'
            ]
        );
    }

    private function getRowToUpdate(array $data, int $columnToMatch, string $valueToMatch): array
    {
        $matchedRow = -1;
        $values = [];
        foreach ($data as $rowId => $rowValues) {
            if (isset($rowValues[$columnToMatch]) && $rowValues[$columnToMatch] === $valueToMatch) {
                $values = $rowValues;
                $matchedRow = $rowId;

                break;
            }
        }

        return ['matchedRow' => $matchedRow, 'values' => $values];
    }

    private function prepareDataForUpdate(string $workSheetName, int $rowId, array $oldData, array $newData): array
    {
        $updatePayload = [];
        $oldLength = \count($oldData);
        $newLength = max(array_keys($newData)) + 1;
        $rowLength = max($oldLength, $newLength);

        for ($columnIndex = 0; $columnIndex < $rowLength; $columnIndex++) {
            $oldValue = isset($oldData[$columnIndex]) ? $oldData[$columnIndex] : '';
            $newValue = isset($newData[$columnIndex]) ? $newData[$columnIndex] : $oldValue;

            if ($oldValue === $newValue) {
                continue;
            }

            $updatePayload[] = [
                'range'  => $workSheetName . '!' . $this->columnIndexToLetter($columnIndex) . ($rowId + 1),
                'values' => [[$newValue]]
            ];
        }

        return ['data' => $updatePayload, 'valueInputOption' => 'USER_ENTERED'];
    }

    private function columnIndexToLetter($columnIndex)
    {
        $columnIndex += 1;
        $letter = '';
        while ($columnIndex > 0) {
            $columnIndex--;
            $letter = \chr($columnIndex % 26 + 65) . $letter;
            $columnIndex = (int) ($columnIndex / 26);
        }

        return $letter;
    }
}
