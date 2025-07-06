<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\ArForm\ArFormTrigger;

Route::group(
    function () {
        Route::get('arform/get-forms', [ArFormTrigger::class, 'getForms']);
    }
)->middleware('nonce:admin');
