<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Formidable\FormidableTrigger;

Route::group(
    function () {
        Route::get('formidable/get', [FormidableTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
