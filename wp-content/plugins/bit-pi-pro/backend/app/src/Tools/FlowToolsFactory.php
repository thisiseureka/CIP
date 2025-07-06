<?php

namespace BitApps\PiPro\src\Tools;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\Pi\src\Log\LogHandler;
use BitApps\PiPro\src\Tools\Condition\ConditionTool;
use BitApps\PiPro\src\Tools\Condition\DefaultConditionTool;
use BitApps\PiPro\src\Tools\Delay\DelayTool;
use BitApps\PiPro\src\Tools\Iterator\IteratorTool;
use BitApps\PiPro\src\Tools\JsonParser\JsonParserTool;
use BitApps\PiPro\src\Tools\Repeater\RepeaterTool;

/**
 * Factory class for creating and executing flow tools in the automation workflow.
 *
 * This class is responsible for instantiating the appropriate tool class based on
 * the node type and handling the execution flow with proper logging.
 */
class FlowToolsFactory
{
    /**
     * Application slug identifier.
     *
     * @var string
     */
    public const APP_SLUG = 'tools';

    /**
     * Node type identifier for iterator tool.
     *
     * @var string
     */
    private const ITERATOR_TOOL = 'iterator';

    /**
     * Node type identifier for repeater tool.
     *
     * @var string
     */
    private const REPEATER_TOOL = 'repeater';

    /**
     * Creates and returns the appropriate flow tool instance based on node type.
     *
     * @param object $currentNode     Current node object containing node properties
     * @param array  $currentNodeInfo Information about the current node
     * @param int    $flowHistoryId   ID of the current flow execution history
     * @param bool $isTestTool      Flag indicating if the tool is being used for testing
     *
     * @return false|object Returns the instantiated tool object or false if not found
     */
    public static function createFlowTool($currentNode, $currentNodeInfo, $flowHistoryId, $isTestTool = false)
    {
        switch ($currentNode->type) {
            case 'condition-logic':
                return new ConditionTool($currentNode);

            case 'default-condition-logic':
                return new DefaultConditionTool($currentNode);

            case 'delay':
                return new DelayTool(new NodeInfoProvider($currentNodeInfo), $flowHistoryId, $isTestTool);

            case 'iterator':
                return new IteratorTool(new NodeInfoProvider($currentNodeInfo), $flowHistoryId, $isTestTool);

            case 'repeater':
                return new RepeaterTool(new NodeInfoProvider($currentNodeInfo), $flowHistoryId);

            case 'jsonParser':
                return new JsonParserTool(new NodeInfoProvider($currentNodeInfo), $flowHistoryId);

            default:
                return false;
        }
    }

    /**
     * Executes the appropriate tool and logs the execution results.
     *
     * This method creates the tool instance, executes it, logs the results,
     * and returns the appropriate response based on the tool type.
     *
     * @param object $currentNode     Current node object containing node properties
     * @param array  $currentNodeInfo Node information array
     * @param int    $flowHistoryId   ID of the current flow execution history
     *
     * @return mixed Returns either:
     *               - Input data for iterator tool
     *               - Loop configuration array for repeater tool
     *               - Boolean indicating if next node should execute for other tools
     */
    public static function executeToolWithLogging($currentNode, $currentNodeInfo, $flowHistoryId)
    {
        $flowTool = self::createFlowTool($currentNode, $currentNodeInfo, $flowHistoryId);

        $nodeType = $currentNode->type;

        if (!$flowTool) {
            return false;
        }

        $response = $flowTool->execute();


        $nodeId = $response['nodeId'] ?? $currentNode->id;

        if ($response['shouldSaveToLog']) {
            LogHandler::getInstance()->addLog(
                $flowHistoryId,
                $nodeId,
                $response['status'],
                $response['input'],
                $response['output'],
                $response['message'] ?? null,
                $response['details'] ?? []
            );
        }

        if ($nodeType === self::ITERATOR_TOOL) {
            return $response['input'];
        }

        if ($nodeType === self::REPEATER_TOOL) {
            $loopInitializeValue = 1;

            return [
                'start' => $loopInitializeValue,
                'end'   => $response['input']['repeat'] ?? 1,
            ];
        }

        return $response['isNextNodeBlocked'] ?? false;
    }
}
