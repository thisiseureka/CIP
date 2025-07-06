<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\SureForms\SureFormsTrigger;

Route::group(
    function () {
        Route::get('sureforms/get', [SureFormsTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
