<?php

namespace BitApps\PiPro\src\Integrations\Divi;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}



class DiviHelper
{
    public static function extractRecordData($record, $etPbContactFormSubmit)
    {
        return [
            'id'      => $record['contact_form_unique_id'],
            'post_id' => $record['post_id'],
            'fields'  => $etPbContactFormSubmit
        ];
    }

    public static function prepareDataForFlow($fields)
    {
        $data = [];
        foreach ($fields as $key => $field) {
            $data[$key] = $field['value'];
        }

        return $data;
    }

    public static function setFields($formData)
    {
        $id = \is_string($formData['id']) && \strlen($formData['id']) > 20 ? substr($formData['id'], 0, 20) . '...' : $formData['id'];
        $allFields = [
            // translators: %s: Form ID
            ['name' => 'id', 'type' => 'text', 'label' => \sprintf(__('Form Id (%s)', 'bit-pi'), $id), 'value' => $formData['id']],
            // translators: %s: Post ID
            ['name' => 'post_id', 'type' => 'text', 'label' => \sprintf(__('Post Id (%s)', 'bit-pi'), $formData['post_id']), 'value' => $formData['post_id']],
        ];

        // Process fields data
        foreach ($formData['fields'] as $key => $field) {
            $labelValue = \is_string($field['value']) && \strlen($field['value']) > 20 ? substr($field['value'], 0, 20) . '...' : $field['value'];
            $labelValue = \is_array($labelValue) ? 'array' : $labelValue;

            $allFields[] = [
                'name'  => "fields.{$key}.value",
                'type'  => 'text',
                'label' => $field['label'] . ' (' . $labelValue . ')',
                'value' => $field['value']
            ];
        }

        return $allFields;
    }
}
