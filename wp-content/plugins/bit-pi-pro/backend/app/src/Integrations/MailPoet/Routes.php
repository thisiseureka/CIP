<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\MailPoet\MailPoetTrigger;

Route::group(
    function () {
        Route::get('mailpoet/get', [MailPoetTrigger::class, 'getAll']);
    }
)->middleware('nonce:admin');
