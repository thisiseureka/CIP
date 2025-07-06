<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Memberpress\MemberpressHelper;

Route::group(
    function () {
        Route::get('get_all_membership', [MemberpressHelper::class, 'getAllMembership']);
        Route::get('get_all_onetime_membership', [MemberpressHelper::class, 'getAllOnetimeMembership']);
        Route::get('get_all_recurring_membership', [MemberpressHelper::class, 'getAllRecurringMembership']);
    }
)->middleware('nonce:admin');
