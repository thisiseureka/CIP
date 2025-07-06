<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\EventsCalendar\EventsCalendarHelper;

Route::group(
    function () {
        Route::get('eventscalendar/get/events', [EventsCalendarHelper::class, 'getAllEvents']);
    }
)->middleware('nonce:admin');
