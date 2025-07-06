<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\FormCraft\FormCraftTrigger;

Route::group(
    function () {
        Route::get('formcraft/get-forms', [FormCraftTrigger::class, 'getAllForms']);
    }
)->middleware('nonce:admin');
