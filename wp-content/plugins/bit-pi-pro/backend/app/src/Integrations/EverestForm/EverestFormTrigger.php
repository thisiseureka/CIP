<?php

namespace BitApps\PiPro\src\Integrations\EverestForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\BTCBI_PRO\Core\Util\DateTimeHelper;
use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class EverestFormTrigger
{
    public static function isActive()
    {
        return \function_exists('evf');
    }

    public function getAll()
    {
        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];
        if (self::isActive()) {
            $forms = $this->forms();
            if ($forms) {
                foreach ($forms as $form) {
                    $allForms[] = (object) [
                        'value' => $form->ID,
                        'label' => $form->post_title
                    ];
                }
            }
        } else {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Everest Forms'));
        }

        return Response::success($allForms);
    }

    public static function forms($formId = null, $onlyData = false)
    {
        $args = [];
        if ($onlyData) {
            $args['content_only'] = true;
        }

        return evf()->form->get($formId, $args);
    }

    public static function processFields($fields)
    {
        $processed = [];
        $fieldToExclude = ['html', 'divider', 'title'];
        foreach ($fields as $index => $field) {
            if (\in_array($field->type, $fieldToExclude)) {
                continue;
            }

            if ($field->type == 'address') {
                $processed = array_merge($processed, self::processAddressField($field, $index));
            } else {
                $processed[] = [
                    'name'  => $index,
                    'type'  => self::fieldType($field->type),
                    'label' => self::processFieldLabel($field),
                ];
            }
        }

        return $processed;
    }

    public static function processFieldLabel($field)
    {
        if (empty($field->label) && !empty($field->placeholder)) {
            return $field->placeholder;
        }

        if (empty($field->label)) {
            return $field->id . ' - ' . $field->type;
        }

        return $field->label;
    }

    public static function processAddressField($field, $index)
    {
        $processed = [];
        $props = ['address1', 'address2', 'city', 'state', 'postal', 'country'];
        foreach ($props as $name) {
            $processed[] = [
                'name'  => $index . '.' . $name,
                'type'  => 'text',
                'label' => $field->{"{$name}_label"},
            ];
        }

        return $processed;
    }

    public static function processValues($entry, $fields, $formData)
    {
        $processedValues = [];

        foreach ($fields as $index => $field) {
            $methodName = 'process' . str_replace(' ', '', ucwords(str_replace('-', ' ', self::fieldType($field['type'])))) . 'FieldValue';
            if (method_exists(new self(), $methodName)) {
                $processedValues = array_merge($processedValues, \call_user_func_array([new self(), $methodName], [$index, $field, $formData]));
            } else {
                $processedValues["{$index}"] = $entry['form_fields'][$index];
            }
        }

        return $processedValues;
    }

    public static function processAddressFieldValue($index, $field)
    {
        $processedValue = [];
        $props = ['address1', 'address2', 'city', 'state', 'postal', 'country'];
        foreach ($props as $name) {
            $processedValue[$index . '.' . $name] = $field[$name];
        }

        return $processedValue;
    }

    public static function processCountryFieldValue($index, $field)
    {
        return ["{$index}" => $field['value']['country_code']];
    }

    public static function processRadioFieldValue($index, $field)
    {
        return ["{$index}" => $field['value_raw']];
    }

    public static function processCheckboxFieldValue($index, $field)
    {
        return ["{$index}" => $field['value_raw']];
    }

    public static function processFileFieldValue($index, $field)
    {
        $processedValue = [];
        if ($field['type'] == 'signature') {
            $processedValue["{$index}"] = $field['value'];
        } else {
            foreach ($field['value_raw'] as $file) {
                $processedValue["{$index}"][] = Utility::getFilePath($file['value']);
            }
        }

        return $processedValue;
    }

    public static function processDateTimeFieldValue($index, $field, $data)
    {
        $processedValue = [];

        $fieldInfo = $data['form_fields'][$index];
        if ($fieldInfo['date_mode'] === 'single') {
            $dateTimeHelper = new DateTimeHelper();
            $dateFormat = $fieldInfo['date_format'];
            $timeFormat = $fieldInfo['time_format'];
            if ($fieldInfo['datetime_format'] == 'date') {
                $dateTimeFormat = $dateFormat;
            } elseif ($fieldInfo['datetime_format'] == 'time') {
                $dateTimeFormat = $timeFormat;
            } else {
                $dateTimeFormat = "{$dateFormat} {$timeFormat}";
            }

            $processedValue[$index] = $dateTimeHelper->getFormated($field['value'], $dateTimeFormat, wp_timezone(), 'Y-m-d\TH:i', null);
        } else {
            $processedValue[$index] = $field['value'];
        }

        return $processedValue;
    }

    public static function handleSubmission($entryId, $fields, $entry, $formId, $formData)
    {
        $processedEntry = self::processValues($entry, $fields, $formData);
        if (!empty($formId) && $flows = FlowService::exists('everestForm', 'submissionSave')) {
            IntegrationHelper::handleFlowForForm($flows, $processedEntry, $formId);
        }
    }

    private static function fieldType($type)
    {
        switch ($type) {
            case 'first-name':
            case 'last-name':
            case 'range-slider':
            case 'payment-quantity':
            case 'payment-total':
            case 'rating':
                return 'text';
            case 'phone':
                return 'tel';
            case 'privacy-policy':
            case 'payment-checkbox':
            case 'payment-multiple':
                return 'checkbox';
            case 'payment-single':
                return 'radio';
            case 'image-upload':
            case 'file-upload':
            case 'signature':
                return 'file';

            default:
                return $type;
        }
    }
}
