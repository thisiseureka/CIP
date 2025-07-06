<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Hustle\HustleTrigger;

Route::group(
    function () {
        Route::get('hustle/get', [HustleTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
