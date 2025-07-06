<?php

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\FluentForms\FluentFormsHelper;

if (!defined('ABSPATH')) {
    exit;
}

Route::group(
    function () {
        Route::post('fluent-forms/get-forms', [FluentFormsHelper::class, 'getForms']);
    }
)->middleware('nonce', 'isAdmin');
