<?php

namespace BitApps\PiPro\src\Integrations\Kadence;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


class KadenceHelper
{
    public static function extractRecordData($formId, $postId, $fields, $formData)
    {
        return [
            'id'           => $formId,
            'form_post_id' => $postId,
            'fields'       => $fields,
            'formData'     => $formData
        ];
    }

    public static function setFields($formData)
    {
        $allFields = [
            // translators: %s: Form ID
            ['name' => 'id', 'type' => 'text', 'label' => \sprintf(__('Form Id (%s)', 'bit-pi'), $formData['id']), 'value' => $formData['id']],
        ];

        if (!empty($formData['form_post_id'])) {
            // translators: %s: Form Post ID
            $allFields[] = ['name' => 'form_post_id', 'type' => 'text', 'label' => \sprintf(__('Form Post Id (%s)', 'bit-pi'), $formData['form_post_id']), 'value' => $formData['form_post_id']];
        }

        foreach ($formData['fields'] as $key => $field) {
            $value = self::findValueByUniqueID($formData['formData'], $field['uniqueID']) ?? null;

            if ($field['type'] == 'checkbox' && \is_string($value)) {
                $value = explode(',', $value);
            }

            $labelValue = \is_string($value) && \strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value;

            $labelValue = \is_array($labelValue) ? 'array' : (empty($labelValue) ? 'null' : $labelValue);

            $allFields[] = [
                'name'  => "fields.{$key}.value",
                'type'  => $field['type'] == 'checkbox' ? 'array' : $field['type'],
                'label' => $field['label'] . ' (' . $labelValue . ')',
                'value' => $value
            ];
        }

        return $allFields;
    }

    private static function findValueByUniqueID($data, $uniqueID)
    {
        foreach ($data as $item) {
            if (isset($item['uniqueID']) && $item['uniqueID'] == $uniqueID) {
                return $item['value'];
            }
        }
    }
}
