<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\EasyDigitalDownload\EasyDigitalDownloadTrigger;

Route::group(
    function () {
        Route::get('get_edd_all_product', [EasyDigitalDownloadTrigger::class, 'getProduct']);
        Route::get('get_edd_all_discount_code', [EasyDigitalDownloadTrigger::class, 'getDiscount']);
    }
)->middleware('nonce:admin');
