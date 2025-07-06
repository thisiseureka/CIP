<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Voxel\VoxelHelper;
use BitApps\PiPro\src\Integrations\Voxel\VoxelTrigger;

Route::group(
    function () {
        Route::get('voxel/get', [VoxelTrigger::class, 'getAll']);
        Route::get('voxel/get/form', [VoxelTrigger::class, 'getAForm']);
        Route::get('voxel/get/post-types', [VoxelHelper::class, 'getAllPostTypes']);
        Route::get('voxel/get/fields', [VoxelTrigger::class, 'fields']);
    }
)->middleware('nonce:admin');
