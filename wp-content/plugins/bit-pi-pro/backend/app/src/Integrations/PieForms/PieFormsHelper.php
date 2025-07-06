<?php

namespace BitApps\PiPro\src\Integrations\PieForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


class PieFormsHelper
{
    public static function formatFields($formId, $formData)
    {
        if (empty($formData)) {
            return;
        }

        $data = [
            'form_id' => [
                'name'  => 'form_id.value',
                'type'  => 'text',
                'label' => __('Form Id', 'bit-pi') . '(' . $formId . ')',
                'value' => $formId
            ]
        ];

        foreach ($formData as $key => $value) {
            $data[$key] = [
                'name'  => "{$key}.value",
                'type'  => 'text',
                'label' => "{$value['name']} ({$value['value']})",
                'value' => $value['value']
            ];
        }

        return $data;
    }

    public static function isPluginInstalled()
    {
        return \defined('PF_PLUGIN_FILE');
    }
}
