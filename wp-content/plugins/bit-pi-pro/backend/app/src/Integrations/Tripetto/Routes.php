<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Tripetto\TripettoTrigger;

Route::group(
    function () {
        Route::get('tripetto/get', [TripettoTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
