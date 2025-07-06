<?php

namespace BitApps\PiPro\src\Integrations\EForm;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\PiPro\Deps\BitApps\WPKit\Helpers\DateTimeHelper;

class EFormHelper
{
    public static function processValues($data, $type)
    {
        $fields = $data->data->{$type};
        $processedValues = [];

        foreach ($fields as $index => $field) {
            $key = "{$field['m_type']}.{$index}";
            $value = '';

            switch ($field['type']) {
                case 'datetime':
                    $value = self::processDateFieldValue($index, $field, $data);

                    break;

                case 'feedback_matrix':
                    $value = $field['rows'];

                    break;

                case 'gps':
                    $value = "{$field['lat']}, {$field['long']}";

                    break;

                case 'upload':
                    $value = self::processUploadFieldValue($index, $field, $data);

                    break;

                case 'address':
                    $processedValues = array_merge($processedValues, self::processAddressFieldValue($index, $field));

                    continue 2; // Skip adding `$key => $value` since `address` handles it separately.

                default:
                    $value = $field['value'] ?? $field['values'] ?? self::processFallbackValue($field);
            }

            $processedValues[$key] = $value;
        }

        return $processedValues;
    }

    /**
     * Handles fallback processing for fields with additional data sources.
     *
     * @param mixed $field
     */
    private static function processFallbackValue($field)
    {
        if (isset($field['options'])) {
            return \is_array($field['options']) && \count($field['options']) === 1 ? $field['options'][0] : $field['options'];
        }

        return $field['rows'] ?? $field['order'] ?? '';
    }

    private static function processAddressFieldValue($index, $field)
    {
        $processedValue = [];
        foreach ($field['values'] as $key => $value) {
            $processedValue["{$field['m_type']}.{$index}.{$key}"] = $value;
        }

        return $processedValue;
    }

    private static function processUploadFieldValue($index, $field, $data)
    {
        $processedValue = [];

        if (!class_exists('IPT_EForm_Form_Elements_Values')) {
            return $processedValue;
        }

        $elementValueHelper = new IPT_EForm_Form_Elements_Values($data->data_id, $data->form_id);

        $elementValueHelper->reassign($data->data_id, $data);

        foreach ($field['id'] as $value) {
            $files = $elementValueHelper->value_upload($data->{$field['m_type']}[$index], $field, 'json', 'label', $value);
            foreach ($files as $file) {
                if (isset($file['guid'])) {
                    $processedValue[] = Utility::getFilePath($file['guid']);
                }
            }
        }

        return $processedValue;
    }

    private static function processDateFieldValue($index, $field, $data)
    {
        // Fetch field information from the data object
        $fieldInfo = $data->{$field['m_type']}[$index] ?? null;

        // Return null if fieldInfo is not found or malformed
        if (!$fieldInfo) {
            return; // or you can handle it differently depending on your needs
        }

        $dateFormat = self::getDateFormat($fieldInfo['settings']['date_format']);

        $timeFormat = self::getTimeFormat($fieldInfo['settings']['time_format']);

        // Combine date and time format
        $dateTimeFormat = "{$dateFormat} {$timeFormat}";

        $dateTimeHelper = new DateTimeHelper();

        // Format the value using the date/time format and timezone
        return $dateTimeHelper->getFormated($field['value'], $dateTimeFormat, wp_timezone(), 'Y-m-d\TH:i', null);
    }

    // Helper method to get the date format
    private static function getDateFormat($fDateFormat)
    {
        switch ($fDateFormat) {
            case 'mm/dd/yy':
                return 'm/d/Y';
            case 'yy-mm-dd':
                return 'Y-m-d';
            case 'dd.mm.yy':
                return 'd.m.Y';
            default:
                return 'd-m-Y'; // Default fallback format
        }
    }

    // Helper method to get the time format
    private static function getTimeFormat($timeFormat)
    {
        return $timeFormat === 'HH:mm:ss' ? 'H:i:s' : 'h:i:s A'; // Handle 24-hour vs 12-hour format
    }
}
