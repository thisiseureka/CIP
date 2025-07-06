<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WpForo\WpForoHelper;

Route::group(
    function () {
        Route::get('wpforo/get/forums', [WpForoHelper::class, 'getAllForums']);
        Route::get('wpforo/get/topics', [WpForoHelper::class, 'getAllTopics']);
        Route::get('wpforo/get/users', [WpForoHelper::class, 'getAllUsers']);
    }
)->middleware('nonce:admin');
