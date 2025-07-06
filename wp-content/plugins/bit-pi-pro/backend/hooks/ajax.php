<?php

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\HTTP\Controllers\CustomAppExportImportController;
use BitApps\PiPro\HTTP\Controllers\CustomMachineController;
use BitApps\PiPro\HTTP\Controllers\FlowExportImportController;
use BitApps\PiPro\HTTP\Controllers\FlowNodeTestController;
use BitApps\PiPro\HTTP\Controllers\WebhookController;
use BitApps\PiPro\Utils\HTTP\Controllers\LicenseController;
use BitApps\PiPro\Utils\HTTP\Controllers\PluginUpdateController;

if (!defined('ABSPATH')) {
    exit;
}

if (!headers_sent()) {
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    header('Access-Control-Allow-Origin: *');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        status_header(200);

        exit;
    }
}
Route::group(
    function () {
        Route::get('custom-machines/{custom_app_id}', [CustomMachineController::class, 'index']);
        Route::post('custom-machines/save', [CustomMachineController::class, 'store']);
        Route::post('custom-machines/{customMachine}/update', [CustomMachineController::class, 'update']);
        Route::post('custom-machines/{customMachine}/delete', [CustomMachineController::class, 'destroy']);
        Route::post('custom-machines/{customMachine}/update-status', [CustomMachineController::class, 'updateStatus']);

        Route::post('webhooks', [WebhookController::class, 'index']);
        Route::post('webhooks/save', [WebhookController::class, 'store']);
        Route::get('webhooks/{webhook}', [WebhookController::class, 'show']);
        Route::post('webhooks/{webhook}/update', [WebhookController::class, 'update']);
        Route::post('webhooks/{webhook}/delete', [WebhookController::class, 'destroy']);

        Route::get('flow-export/{flow_id}', [FlowExportImportController::class, 'export']);
        Route::get('custom-app-export/{custom_app_id}', [CustomAppExportImportController::class, 'export']);
        Route::post('custom-app-import', [CustomAppExportImportController::class, 'import']);
        Route::post('flow-import/{flow_id}', [FlowExportImportController::class, 'import']);
        Route::get('custom-apps/{appSlug}/machines/{machineSlug}?', [CustomMachineController::class, 'machineByAppSlugAndMachineSlug']);
        Route::post('test-run-node', [FlowNodeTestController::class, 'runNode']);
        Route::get('flow-run-existing-data', [FlowNodeTestController::class, 'flowRunWithExistingData']);

        Route::get('update-plugin', [PluginUpdateController::class, 'updatePlugin']);
        Route::post('license/activate', [LicenseController::class, 'activateLicense']);
        Route::post('license/deactivate', [LicenseController::class, 'deactivateLicense']);
        Route::get('license/check', [LicenseController::class, 'checkLicenseStatus']);
        Route::get('plugin/update-check', [PluginUpdateController::class, 'isPluginUpdateAvailable']);
    }
)->middleware('nonce', 'isAdmin');
