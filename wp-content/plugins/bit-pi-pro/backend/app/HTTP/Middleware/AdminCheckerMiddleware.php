<?php

namespace BitApps\PiPro\HTTP\Middleware;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\Deps\BitApps\WPKit\Utils\Capabilities;

final class AdminCheckerMiddleware
{
    public function handle()
    {
        if (!Capabilities::check('manage_options')) {
            return Response::error('Access Denied: Only administrators are allowed to make this request')->httpStatus(411);
        }

        return true;
    }
}
