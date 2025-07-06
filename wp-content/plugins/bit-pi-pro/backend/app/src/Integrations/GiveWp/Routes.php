<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\GiveWp\GiveWpTrigger;

Route::group(
    function () {
        Route::get('get_all_donation_form', [GiveWpTrigger::class, 'getDonationForms']);
    }
)->middleware('nonce:admin');
