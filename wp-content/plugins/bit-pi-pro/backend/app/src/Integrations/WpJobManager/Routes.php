<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WpJobManager\WpJobManagerHelper;

Route::noAuth()->group(
    function () {
        Route::get('wpjobmanager/get/job-types', [WpJobManagerHelper::class, 'getAllJobTypes']);
        Route::get('wpjobmanager/get/jobs', [WpJobManagerHelper::class, 'getAllJobs']);
        Route::get('wpjobmanager/get/users', [WpJobManagerHelper::class, 'getAllUsers']);
        Route::get('wpjobmanager/get/application-statuses', [WpJobManagerHelper::class, 'getApplicationStatuses']);
    }
)->middleware('nonce:admin');
