<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WordPress\WordPressHelper;

Route::group(
    function () {
        Route::post('wordpress-core/get-post-types', [WordPressHelper::class, 'getPostTypes']);
        Route::post('wordpress-core/get-posts', [WordPressHelper::class, 'getPosts']);
    }
)->middleware('nonce:admin');
