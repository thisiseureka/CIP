<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\MetaBox\MetaBoxTrigger;

Route::group(
    function () {
        Route::get('metabox/get-forms', [MetaBoxTrigger::class, 'getForms']);
    }
)->middleware('nonce:admin');
