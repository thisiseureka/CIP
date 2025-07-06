<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\PiotnetAddon\PiotnetAddonTrigger;

Route::group(
    function () {
        Route::get('piotnetaddon/get', [PiotnetAddonTrigger::class, 'getAllForms']);
    }
)->middleware('nonce:admin');
