<?php

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\HTTP\Controllers\WebhookDispatchController;

if (!defined('ABSPATH')) {
    exit;
}

Route::match(['post', 'get'], 'webhook/callback/{trigger_id}', [WebhookDispatchController::class, 'handleWebhook']);
