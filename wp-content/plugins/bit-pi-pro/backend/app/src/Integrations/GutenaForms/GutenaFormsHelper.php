<?php

namespace BitApps\PiPro\src\Integrations\GutenaForms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\BTCBI_PRO\Core\Util\Helper;

class GutenaFormsHelper
{
    public static function prepareDataForFlow($formData, $formIdField)
    {
        $finalData = array_merge($formData, $formIdField);
        $data = [];

        foreach ($finalData as $key => $value) {
            $data[$key] = $value['value'];
        }

        return $data;
    }

    public static function setFields($formData, $formId)
    {
        $allFields = [
            ['name' => 'id', 'type' => 'text', 'label' => __('Form Id', 'bit-pi'), 'value' => $formId],
        ];

        foreach ($formData as $key => $data) {
            $allFields[] = [
                'name'  => $key,
                'type'  => $data['fieldType'],
                'label' => $data['label'],
                'value' => $data['value']
            ];
        }

        return $allFields;
    }
}
