<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\FluentBooking\FluentBookingTrigger;

Route::group(
    function () {
        Route::get('fluentbooking/get/events', [FluentBookingTrigger::class, 'getEvents']);
    }
)->middleware('nonce:admin');
