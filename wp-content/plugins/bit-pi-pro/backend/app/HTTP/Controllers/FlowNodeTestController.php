<?php

namespace BitApps\PiPro\HTTP\Controllers;

use BitApps\Pi\Helpers\Parser;
use BitApps\Pi\Model\Flow;
use BitApps\Pi\Model\FlowNode;
use BitApps\Pi\Services\NodeService;
use BitApps\Pi\src\Flow\FlowExecutor;
use BitApps\Pi\src\Flow\GlobalNodeVariables;
use BitApps\Pi\src\Flow\NodeExecutor;
use BitApps\Pi\src\Flow\NodeInfoProvider;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Tools\FlowToolsFactory;

/**
 * Controller for testing and running flow nodes individually.
 *
 * Handles execution of individual nodes in the flow.
 */
class FlowNodeTestController
{
    /**
     * Execute a specific flow node and return its response.
     *
     * This method validates the input, retrieves the node data,
     * sets up the execution environment, and runs the node based on its type.
     *
     * @param Request $request The incoming HTTP request
     *
     * @return Response Success response with execution results or error response
     */
    private const FLOW_ID_POSITION = 0;

    private const FLOW_TOOLS_APP_SLUG = 'tools';

    private const CONDITION_LOGIC_MACHINE_SLUG = 'condition-logic';

    public function runNode(Request $request)
    {
        $validatedData = $request->validate(
            [
                'node_id' => ['required', 'string', 'sanitize:text'],
            ]
        );

        $nodeId = $validatedData['node_id'];

        $previousNodeId = null;

        if (substr_count($nodeId, '-') >= 2) {
            $previousNodeId = substr(strrchr($nodeId, '-'), 1);
            $nodeId = substr($nodeId, 0, strrpos($nodeId, '-'));
        }

        $flowId = explode('-', $nodeId)[self::FLOW_ID_POSITION];

        $nodeData = FlowNode::where('node_id', $nodeId)->first();

        if (empty($nodeData)) {
            return Response::error('Node not found', 404);
        }

        $appSlug = $nodeData['app_slug'];

        $nodeVariablesInstance = GlobalNodeVariables::getInstance(null, $flowId);

        $variables = $nodeVariablesInstance->getVariables();

        if (!empty($variables)) {
            foreach ($variables as $index => $variable) {
                $nodeVariablesInstance->setNodeResponse($index, Parser::parseArrayStructure($variable));
            }
        }

        if ($appSlug === self::FLOW_TOOLS_APP_SLUG) {
            $nodeInfo = (object) [
                'type' => $nodeData['machine_slug'],
                'id'   => $nodeData['node_id'],
            ];

            if (!empty($previousNodeId)) {
                $nodeInfo->id = $nodeId . '-' . $previousNodeId;
                $nodeInfo->previous = $nodeId;
                $nodeInfo->type = self::CONDITION_LOGIC_MACHINE_SLUG;
            }

            $executionResponse = FlowToolsFactory::createFlowTool($nodeInfo, $nodeData, $flowId, true)->execute();
        } else {
            $appClass = (new NodeExecutor())->doesActionExist($nodeData->app_slug);
            $executionResponse = (new $appClass(new NodeInfoProvider($nodeData)))->execute();
        }

        $output = $executionResponse['output'] ?? [];

        $data = [
            'input'  => $executionResponse['input'],
            'output' => $output
        ];

        // skip saving node variables if the node is a condition logic node

        if ($nodeData['machine_slug'] === 'condition') {
            return Response::success($data);
        }

        if (\in_array($nodeData['machine_slug'], ['repeater', 'iterator'])) {
            NodeService::saveNodeVariables($flowId, $output[0], $nodeId);
        } else {
            NodeService::saveNodeVariables($flowId, $output, $nodeId);
        }


        if ($executionResponse['status'] === 'error') {
            return Response::error($data);
        }

        return Response::success($data);
    }

    /**
     * Get the node data for a specific node ID.
     *
     * @return Response Success response with node data or error response
     */
    public function flowRunWithExistingData(Request $request)
    {
        $validatedData = $request->validate(
            [
                'flow_id' => ['required', 'integer'],
            ]
        );

        $flowId = $validatedData['flow_id'];

        $flow = Flow::select(['id', 'title', 'settings', 'is_active', 'map', 'trigger_type', 'listener_type', 'is_hook_capture'])->where('id', $flowId)->first();

        if (!$flow) {
            return Response::error('Flow does not exist');
        }

        $firstNodeId = $flowId . '-1';

        $nodeData = FlowNode::select(['variables'])->where('node_id', $firstNodeId)->first();

        if (empty($nodeData)) {
            return Response::error('Node not found', 404);
        }

        if (empty($nodeData['variables'])) {
            return Response::error('No existing response found. You have to capture a response first to use this feature', 404);
        }

        $triggerData = Parser::parseArrayStructure($nodeData['variables']);

        FlowExecutor::execute($flow, $triggerData);

        return Response::success($triggerData);
    }
}
