<?php

namespace BitApps\PiPro\src\Integrations\FluentForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;

class FluentFormsHelper
{
    public function getForms()
    {
        if (!\function_exists('wpFluent')) {
            return Response::error('Fluent Form is not installed or activated');
        }

        $defaultOptions = [
            [
                'label' => 'Any form',
                'value' => 'any'
            ],
        ];

        $fluentForms = wpFluent()->table('fluentform_forms')->select('id as value', 'title as label')->get();

        $allForms = array_merge($defaultOptions, $fluentForms);

        return Response::success($allForms);
    }
}
