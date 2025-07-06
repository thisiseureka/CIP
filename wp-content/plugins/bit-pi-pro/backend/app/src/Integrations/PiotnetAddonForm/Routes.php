<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\PiotnetAddonForm\PiotnetAddonFormTrigger;

Route::group(
    function () {
        Route::get('piotnetaddonform/get', [PiotnetAddonFormTrigger::class, 'getAllForms']);
    }
)->middleware('nonce:admin');
