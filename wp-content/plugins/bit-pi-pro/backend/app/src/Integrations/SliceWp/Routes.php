<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\SliceWp\SliceWpTrigger;

Route::group(
    function () {
        Route::get('get_allCommissionType', [SliceWpTrigger::class, 'allCommissionType']);
    }
)->middleware('nonce:admin');
