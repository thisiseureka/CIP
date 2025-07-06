<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\RestrictContent\RestrictContentTrigger;

Route::group(
    function () {
        Route::get('restrict_content_get_all_levels', [RestrictContentTrigger::class, 'getAllMembership']);
    }
)->middleware('nonce:admin');
