<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\HappyForm\HappyFormTrigger;

Route::group(
    function () {
        Route::get('happy/get-forms', [HappyFormTrigger::class, 'getAllForms']);
    }
)->middleware('nonce:admin');
