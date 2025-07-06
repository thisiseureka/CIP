<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\Affiliate\AffiliateTrigger;

Route::group(
    function () {
        Route::get('affiliate_get_all_type', [AffiliateTrigger::class, 'affiliateGetAllType']);
    }
)->middleware('nonce:admin');
