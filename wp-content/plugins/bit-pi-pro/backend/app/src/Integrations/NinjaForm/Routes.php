<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\NinjaForm\NinjaFormTrigger;

Route::group(
    function () {
        Route::get('nf/get', [NinjaFormTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
