<?php

namespace BitApps\PiPro\HTTP\Requests;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;

class WebhookIndexRequest extends Request
{
    public function rules()
    {
        return [
            'flowId'  => ['nullable', 'integer'],
            'appSlug' => ['nullable', 'string', 'sanitize:text'],
        ];
    }
}
