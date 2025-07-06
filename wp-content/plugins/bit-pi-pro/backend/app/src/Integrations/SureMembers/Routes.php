<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\SureMembers\SureMembersHelper;

Route::group(
    function () {
        Route::get('suremembers/get/groups', [SureMembersHelper::class, 'getSureMembersGroups']);
    }
)->middleware('nonce:admin');
