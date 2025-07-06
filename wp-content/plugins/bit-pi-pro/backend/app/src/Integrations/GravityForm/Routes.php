<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\GravityForm\GravityFormTrigger;

Route::group(
    function () {
        Route::get('gf/get', [GravityFormTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
