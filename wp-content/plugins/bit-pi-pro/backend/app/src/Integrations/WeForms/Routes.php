<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WeForms\WeFormsTrigger;

Route::group(
    function () {
        Route::get('weforms/get', [WeFormsTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
