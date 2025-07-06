<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Groundhogg\GroundhoggTrigger;

Route::group(
    function () {
        Route::get('groundhogg/get/tags', [GroundhoggTrigger::class, 'getAllTags']);
    }
)->middleware('nonce:admin');
