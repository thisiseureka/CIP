<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\CartFlow\CartFlowTrigger;

Route::group(
    function () {
        Route::get('cartflow/get', [CartFlowTrigger::class, 'getAllForms']);
    }
)->middleware('nonce:admin');
