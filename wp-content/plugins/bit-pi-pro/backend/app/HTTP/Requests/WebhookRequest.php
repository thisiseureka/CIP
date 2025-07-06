<?php

namespace BitApps\PiPro\HTTP\Requests;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;

class WebhookRequest extends Request
{
    public function rules()
    {
        return [
            'flow_id'  => ['nullable', 'integer'],
            'title'    => ['required', 'string', 'sanitize:text'],
            'app_slug' => ['required', 'string', 'sanitize:text'],
        ];
    }
}
