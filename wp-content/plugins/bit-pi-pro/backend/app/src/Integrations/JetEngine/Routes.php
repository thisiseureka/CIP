<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\JetEngine\JetEngineTrigger;

Route::group(
    function () {
        Route::get('get_all_post_types_jet_engine', [JetEngineTrigger::class, 'getAllPostTypes']);
    }
)->middleware('nonce:admin');
