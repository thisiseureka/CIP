<?php

namespace BitApps\PiPro\src\Integrations\Brizy;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;

class BrizyHelper
{
    public static function extractRecordData($fields, $formId)
    {
        return [
            'id'     => $formId,
            'fields' => $fields
        ];
    }

    public static function setFields($formData)
    {
        $id = \is_string($formData['id']) && \strlen($formData['id']) > 20 ? substr($formData['id'], 0, 20) . '...' : $formData['id'];

        $allFields = [
            // translators: %s: Form ID
            ['name' => 'form_id', 'type' => 'text', 'label' => \sprintf(__('Form Id (%s)', 'bit-pi'), $id), 'value' => $formData['id']],
        ];

        foreach ($formData['fields'] as $key => $field) {
            switch ($field->type) {
                case 'checkbox':
                    $allFields[] = [
                        'name'  => $key,
                        'value' => explode(',', $field->value)
                    ];

                    break;

                case 'FileUpload':
                    $allFields[] = [
                        'name'  => $key,
                        'value' => Utility::getFilePath($field->value)
                    ];

                    break;

                default:
                    $allFields[] = [
                        'name'  => $key,
                        'value' => $field->value
                    ];

                    break;
            }
        }

        return $allFields;
    }
}
