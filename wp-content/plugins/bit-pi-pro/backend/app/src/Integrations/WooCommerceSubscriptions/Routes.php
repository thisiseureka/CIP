<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\WcSubscriptions\WcSubscriptionsTrigger;

Route::group(
    function () {
        Route::get('wcsubscriptions/get', [WcSubscriptionsTrigger::class, 'getAll']);
        Route::get('wcsubscriptions/get/subscriptions', [WcSubscriptionsTrigger::class, 'getAllSubscriptions']);
        Route::get('wcsubscriptions/get/subscription-products', [WcSubscriptionsTrigger::class, 'getAllSubscriptionsProducts']);
    }
)->middleware('nonce:admin');
