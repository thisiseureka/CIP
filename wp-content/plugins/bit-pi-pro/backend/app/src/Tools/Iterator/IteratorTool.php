<?php

namespace BitApps\PiPro\src\Tools\Iterator;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Helpers\MixInputHandler;
use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Model\FlowLog;
use BitApps\Pi\src\DTO\FlowToolResponseDTO;
use BitApps\Pi\src\Flow\GlobalNodeVariables;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\PiPro\src\Tools\FlowToolsFactory;

class IteratorTool
{
    private const MACHINE_SLUG = 'iterator';

    protected $nodeInfoProvider;

    private $flowHistoryId;

    private $isTestRun;

    public function __construct(NodeInfoProvider $nodeInfoProvider, $flowHistoryId, $isTestRun = false)
    {
        $this->nodeInfoProvider = $nodeInfoProvider;
        $this->flowHistoryId = $flowHistoryId;
        $this->isTestRun = $isTestRun;
    }

    public function execute()
    {
        $flowId = $this->nodeInfoProvider->getFlowId();

        $nodeId = $this->nodeInfoProvider->getNodeId();

        $nodeVariableInstance = GlobalNodeVariables::getInstance($this->flowHistoryId, $flowId);

        $iteratorConfig = $this->nodeInfoProvider->getData()['iterator'] ?? [];

        $extractedData = MixInputHandler::replaceMixTagValue($iteratorConfig['value'] ?? '', 'array-first-element');

        $valueType = $iteratorConfig['value'][0]['dType'];

        if (($valueType === 'array_of_obj' && \is_array($extractedData)) && $this->isTestRun) {
            $tempExtractedData = $extractedData;
            $extractedData = [];
            $extractedData[] = $tempExtractedData;
        }

        $totalItemLength = Utility::isMultiDimensionArray($extractedData)
        || $this->isSequentialArray($extractedData)

        ? \count($extractedData) : 0;

        $iteratorStartPosition = 1;

        $iteratorEndPosition = $totalItemLength;

        if (isset($iteratorConfig['start']) && $iteratorConfig['start'] > 1) {
            $iteratorStartPosition = $iteratorConfig['start'];
        }

        if (isset($iteratorConfig['end']) && $iteratorConfig['end'] > 0) {
            $iteratorEndPosition = $iteratorConfig['end'];
        }

        if ($iteratorEndPosition > $totalItemLength) {
            $iteratorEndPosition = $totalItemLength;
        }

        $iteratorResponseData = $this->formatExtractedData($totalItemLength, $extractedData);

        $nodeVariableInstance->setVariables($nodeId, $iteratorResponseData[0] ?? '');

        $nodeVariableInstance->setNodeResponse($nodeId, $iteratorResponseData);

        $inputData = [
            'start' => $iteratorStartPosition,
            'end'   => $iteratorEndPosition,
        ];


        $details = [
            'app_slug'     => FlowToolsFactory::APP_SLUG,
            'machine_slug' => self::MACHINE_SLUG,
        ];

        return FlowToolResponseDTO::create(
            FlowLog::STATUS['SUCCESS'],
            $inputData,
            $iteratorResponseData,
            'Iterator executed successfully',
            $details,
        );
    }

    private function formatExtractedData($totalItemLength, $extractedData)
    {
        if ($totalItemLength > 0) {
            $extractedData = array_map(
                function ($item, $index) use ($totalItemLength) {
                    $item = (array) $item;
                    $item['total_number_of_items'] = $totalItemLength;
                    $item['item_order_position'] = $index;

                    return $item;
                },
                $extractedData,
                array_keys($extractedData)
            );
        } else {
            $item = [
                'total_number_of_items' => 1,
                'item_order_position'   => 0
            ];

            if (!\is_array($extractedData) && !\is_object($extractedData)) {
                $item['value'] = $extractedData;
            }

            $extractedData = [$item];
        }

        return $extractedData;
    }

    private function isSequentialArray($array)
    {
        if (!\is_array($array)) {
            return false;
        }

        return array_keys($array) === range(0, \count($array) - 1);
    }
}
