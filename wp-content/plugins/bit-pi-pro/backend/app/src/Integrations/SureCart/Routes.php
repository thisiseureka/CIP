<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\SureCart\SureCartTrigger;

Route::group(
    function () {
        Route::get('get_all_surecart_product', [SureCartTrigger::class, 'getSureCartAllProducts']);
    }
)->middleware('nonce:admin');
