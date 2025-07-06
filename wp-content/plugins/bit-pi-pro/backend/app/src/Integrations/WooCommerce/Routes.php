<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WooCommerce\WooCommerce;

Route::group(
    function () {
        Route::post('woocommerce-core/get-post-types', [WooCommerce::class, 'getPostTypes']);
        Route::post('woocommerce-core/get-posts', [WooCommerce::class, 'getPosts']);
    }
)->middleware('nonce:admin');
