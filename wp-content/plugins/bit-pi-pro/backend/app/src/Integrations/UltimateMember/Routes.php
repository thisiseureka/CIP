<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\UltimateMember\UltimateMemberTrigger;

Route::group(
    function () {
        Route::get('get_um_all_role', [UltimateMemberTrigger::class, 'getUMrole']);

        Route::get('get_login_forms', [UltimateMemberTrigger::class, 'getLoginForms']);

        Route::get('get_registration_forms', [UltimateMemberTrigger::class, 'getRegistrationForms']);
    }
)->middleware('nonce:admin');
