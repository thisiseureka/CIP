<?php

namespace BitApps\PiPro\HTTP\Controllers;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Model\CustomApp;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\Model\CustomMachine;
use BitApps\PiPro\Services\AbstractBaseExportImportService;

class CustomAppExportImportController extends AbstractBaseExportImportService
{
    public function getExportData($customAppId)
    {
        return CustomApp::select(['id', 'name', 'slug', 'description', 'logo', 'status', 'color'])
            ->with(
                'customMachines',
                function ($query) {
                    $query->select(['id', 'custom_app_id', 'slug', 'name', 'app_type', 'trigger_type', 'config', 'status']);
                }
            )
            ->findOne(['id' => $customAppId]);
    }

    public function import(Request $request)
    {
        $file = $request->files()['flow_blueprint'];

        if (!is_file($file['tmp_name']) || $file['type'] !== 'application/json' || $file['error'] !== UPLOAD_ERR_OK) {
            return Response::error(['message' => 'Invalid file type. only json file is allowed.']);
        }

        $data = json_decode(html_entity_decode(file_get_contents($file['tmp_name'])), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return Response::error(['message' => 'Invalid json, please provide a valid json file.']);
        }

        $isAppAndMachinesSaved = $this->saveCustomAppAndMachine($data);

        if (!$isAppAndMachinesSaved) {
            return Response::error(['message' => 'Custom App import failed! make sure that, the json file is valid blueprint.']);
        }

        return Response::success(['message' => 'Custom App imported successfully.']);
    }

    public function export(Request $request)
    {
        $validatedData = $request->validate(
            [
                'custom_app_id' => ['required', 'integer'],
            ]
        );

        $exportData = $this->getExportData($validatedData['custom_app_id']);

        if (!$exportData) {
            return Response::error(['message' => 'Custom Apps export failed!']);
        }

        $fileName = 'custom-app-' . $exportData->name . '.json';

        $this->downloadAsFile($exportData, $fileName);
    }

    private function saveCustomAppAndMachine($data)
    {
        $customAppExists = CustomApp::findOne(['slug' => $data['slug']]);

        if ($customAppExists) {
            $slug = 'custom-app-' . str_replace('-', '', wp_generate_uuid4());
        } else {
            $slug = $data['slug'];
        }

        $customApp = CustomApp::insert(
            [
                'name'        => $data['name'],
                'slug'        => $slug,
                'description' => $data['description'],
                'logo'        => $data['logo'],
                'status'      => $data['status'],
                'color'       => $data['color'],
            ]
        );

        if (!$customApp) {
            return false;
        }

        $customAppId = $customApp->id;

        $customApp->save();

        $machines = $data['customMachines'];

        if (empty($machines)) {
            return true;
        }

        array_walk(
            $machines,
            function (&$machine) use ($customAppId) {
                unset($machine['id'], $machine['custom_app_id']);

                $machine['custom_app_id'] = $customAppId;

                $machine['config'] = wp_json_encode($machine['config']);
            }
        );


        return CustomMachine::insert($machines);
    }
}
