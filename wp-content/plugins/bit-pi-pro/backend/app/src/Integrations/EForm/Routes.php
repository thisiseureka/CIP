<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\EForm\EFormTrigger;

Route::group(
    function () {
        Route::get('wpef/get-forms', [EFormTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
