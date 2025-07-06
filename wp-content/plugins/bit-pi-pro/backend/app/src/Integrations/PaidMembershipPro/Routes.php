<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\PaidMembershipPro\PaidMembershipProTrigger;

Route::group(
    function () {
        Route::get('get_all_paid_membership_pro_level', [PaidMembershipProTrigger::class, 'getAllPaidMembershipProLevel']);
    }
)->middleware('nonce:admin');
