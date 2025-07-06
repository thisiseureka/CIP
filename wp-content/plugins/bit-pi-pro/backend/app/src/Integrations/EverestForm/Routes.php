<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\EverestForm\EverestFormTrigger;

Route::group(
    function () {
        Route::get('evf/get', [EverestFormTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
