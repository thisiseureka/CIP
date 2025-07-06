<?php

namespace BitApps\PiPro\HTTP\Controllers;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Model\Flow;
use BitApps\Pi\Services\FlowTemplateImportService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\Services\AbstractBaseExportImportService;

class FlowExportImportController extends AbstractBaseExportImportService
{
    public function getExportData($flowId)
    {
        return Flow::select(['id', 'is_active', 'map', 'data', 'trigger_type', 'listener_type', 'is_hook_capture'])
            ->with(
                'nodes',
                function ($query) {
                    $query->select(['id', 'flow_id', 'node_id', 'app_slug', 'machine_slug', 'field_mapping', 'data', 'variables']);
                }
            )
            ->findOne(['id' => $flowId]);
    }

    public function import(Request $request)
    {
        $request->validate(
            [
                'flow_id' => ['required', 'integer'],
            ]
        );

        $flowId = $request['flow_id'];

        $file = $request->files()['flow_blueprint'];

        if (!is_file($file['tmp_name']) || $file['type'] !== 'application/json' || $file['error'] !== UPLOAD_ERR_OK) {
            return Response::error(['message' => 'Invalid file type. only json file is allowed.']);
        }

        $data = json_decode(html_entity_decode(file_get_contents($file['tmp_name'])), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::error(['message' => 'Invalid json, please provide a valid json file.']);
        }

        $importService = new FlowTemplateImportService();

        $isImported = $importService->importFlow($flowId, $data);

        if (!$isImported) {
            return Response::error(['message' => 'Flow import failed! make sure that, the json file is valid blueprint.']);
        }

        return Response::success(['message' => 'Flow imported successfully.']);
    }

    public function export(Request $request)
    {
        $request->validate(
            [
                'flow_id' => ['required', 'integer'],
            ]
        );

        $exportData = $this->getExportData($request->flow_id);

        if (!$exportData) {
            return Response::error(['message' => 'Flow export failed!']);
        }

        $this->downloadAsFile($exportData);
    }
}
