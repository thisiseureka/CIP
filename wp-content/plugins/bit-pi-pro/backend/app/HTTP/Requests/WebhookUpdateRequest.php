<?php

namespace BitApps\PiPro\HTTP\Requests;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;

class WebhookUpdateRequest extends Request
{
    public function rules()
    {
        return [
            'flow_id' => ['required', 'integer'],
        ];
    }
}
