<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\PiotnetForms\PiotnetFormsTrigger;

Route::group(
    function () {
        Route::get('piotnetforms/get-forms', [PiotnetFormsTrigger::class, 'getAllForms']);
    }
)->middleware('nonce:admin');
