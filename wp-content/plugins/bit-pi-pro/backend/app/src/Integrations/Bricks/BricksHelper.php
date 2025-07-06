<?php

namespace BitApps\PiPro\src\Integrations\Bricks;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}



class BricksHelper
{
    public static function extractRecordData($record, $form, $files)
    {
        return [
            'id'               => $record['formId'],
            'post_id'          => $record['postId'],
            'form_fields'      => $form['fields'],
            'submitted_fields' => $record,
            'submitted_files'  => $files
        ];
    }

    public static function fetchFlows($formId)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}btcbi_flow
                WHERE status = true 
                AND triggered_entity = %s 
                AND (triggered_entity_id = %s
                OR triggered_entity_id = %s)",
                'Bricks',
                'bricks/form/custom_action',
                $formId
            )
        );
    }

    public static function prepareDataForFlow($fields, $files)
    {
        $data = [];

        foreach ($fields as $key => $value) {
            $fieldId = str_replace('form-field-', '', $key);
            $data[$fieldId] = (\is_array($value) && \count($value) == 1) ? $value[0] : $value;
        }

        foreach ($files as $key => $item) {
            $fieldId = str_replace('form-field-', '', $key);

            if (\is_array($item)) {
                foreach ($item as $file) {
                    if (!isset($file['file'])) {
                        continue;
                    }

                    $data[$fieldId][] = $file['file'];
                }
            } else {
                if (!isset($item['file'])) {
                    continue;
                }

                $data[$fieldId] = $item['file'];
            }
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
        foreach ($formData['form_fields'] as $field) {
            $key = "form-field-{$field['id']}";

            if ($field['type'] == 'radio') {
                $value = \is_array($formData['submitted_fields'][$key]) ? implode(',', $formData['submitted_fields'][$key]) : $formData['submitted_fields'][$key] ?? '';
                $key = "submitted_fields.{$key}";
            } elseif ($field['type'] == 'file') {
                $value = empty($formData['submitted_files'][$key]) ? '' : array_column($formData['submitted_files'][$key], 'file');
                $key = "submitted_files.{$key}";
            } else {
                $value = $formData['submitted_fields'][$key] ?? '';
                $key = "submitted_fields.{$key}";
            }

            $labelValue = \is_string($value) && \strlen($value) > 20 ? substr($value, 0, 20) . '...' : $value;
            $labelValue = \is_string($labelValue) ? $labelValue : $field['type'];

            $allFields[] = [
                'name'  => $key,
                'type'  => $field['type'],
                'label' => $field['label'] . ' (' . $labelValue . ')',
                'value' => $value
            ];
        }

        return $allFields;
    }
}
