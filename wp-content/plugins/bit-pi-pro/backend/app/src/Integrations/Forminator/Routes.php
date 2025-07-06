<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Forminator\ForminatorTrigger;

Route::group(
    function () {
        Route::get('forminator/get', [ForminatorTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
