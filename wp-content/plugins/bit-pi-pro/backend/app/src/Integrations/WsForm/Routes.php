<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WsForm\WsFormTrigger;

Route::group(
    function () {
        Route::get('wsform/get-forms', [WsFormTrigger::class, 'getForms']);
    }
)->middleware('nonce:admin');
