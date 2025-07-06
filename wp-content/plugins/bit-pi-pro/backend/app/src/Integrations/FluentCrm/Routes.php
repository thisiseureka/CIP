<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\FluentCrm\FluentCrmHelper;

Route::group(
    function () {
        Route::get('get_fluentcrm_tags', [FluentCrmHelper::class, 'getFluentCrmTags']);
        Route::get('get_fluentcrm_lists', [FluentCrmHelper::class, 'getFluentCrmList']);
        Route::get('get_fluentcrm_status', [FluentCrmHelper::class, 'getFluentCrmStatus']);
    }
)->middleware('nonce:admin');
