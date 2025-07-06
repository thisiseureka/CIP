<?php

namespace BitApps\PiPro\src\Integrations\Formidable;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use FrmEntryValues;
use FrmField;
use FrmFieldsHelper;
use FrmForm;

final class FormidableTrigger
{
    public function getAll()
    {
        if (!\function_exists('load_formidable_forms')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Formidable'));
        }

        $forms = FrmForm::getAll();
        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];
        if ($forms) {
            foreach ($forms as $form) {
                $allForms[] = (object) [
                    'value' => $form->id,
                    'label' => $form->name,
                ];
            }
        }

        return Response::success($allForms);
    }

    public static function fields($form_id)
    {
        $fields = FrmField::get_all_for_form($form_id, '', 'include');
        $field = [];
        if (empty($fields)) {
            wp_send_json_error(__('Form doesn\'t exists any field', 'bit-pi'));
        }

        $visistedKey = [];

        foreach ($fields as $key => $val) {
            if ($val->type === 'name') {
                $field[] = (object) [
                    'name'  => 'first-name',
                    'label' => __('First Name', 'bit-pi'),
                    'type'  => 'name'
                ];
                $field[] = (object) [
                    'name'  => 'middle-name',
                    'label' => __('Middle Name', 'bit-pi'),
                    'type'  => 'name'
                ];
                $field[] = (object) [
                    'name'  => 'last-name',
                    'label' => __('Last Name', 'bit-pi'),
                    'type'  => 'name'
                ];

                continue;
            }

            if ($val->type === 'address') {
                $allFld = $val->default_value;
                $addressKey = $val->field_key;
                foreach ($allFld as $key => $val) {
                    $field[] = (object) [
                        'name'  => $addressKey . '_' . $key,
                        'label' => 'address_' . $key,
                        'type'  => 'address'
                    ];
                }

                continue;
            }

            if ($val->type === 'divider' || $val->type === 'end_divider') {
                $formName = $val->name;
                $fldKey = $val->field_key;
                $cnt = 0;
                $counter = \count($fields);
                for ($i = $key + 1; $i < $counter; ++$i) {
                    $id = $fields[$i]->id;
                    if (isset($fields[$i]->form_name) && $fields[$i]->form_name === $formName) {
                        $field[] = (object) [
                            'name'  => $fldKey . '_' . $id,
                            'label' => $formName . ' ' . $fields[$i]->name,
                            'type'  => $fields[$i]->type
                        ];
                    }

                    ++$cnt;
                    $visistedKey[] = $fields[$i]->field_key;
                }

                continue;
            }

            if (\in_array($val->field_key, $visistedKey)) {
                // continue;
            }

            $field[] = (object) [
                'name'  => $val->field_key,
                'label' => $val->name,
                'type'  => $val->type
            ];
        }

        return $field;
    }

    public static function getFieldsValues($form, $entryId)
    {
        $formFields = [];
        $fields = FrmFieldsHelper::get_form_fields($form->id);
        $entryValues = new FrmEntryValues($entryId);
        $fieldValues = $entryValues->get_field_values();

        foreach ($fields as $field) {
            $key = $field->field_key;

            $val = (isset($fieldValues[$field->id]) ? $fieldValues[$field->id]->get_saved_value() : '');

            if (\is_array($val)) {
                if ($field->type === 'name') {
                    if (\array_key_exists('first', $val) || \array_key_exists('middle', $val) || \array_key_exists('last', $val)) {
                        $formFields['first-name'] = isset($val['first']) ? $val['first'] : '';
                        $formFields['middle-name'] = isset($val['middle']) ? $val['middle'] : '';
                        $formFields['last-name'] = isset($val['last']) ? $val['last'] : '';
                    }
                } elseif ($field->type == 'checkbox' || $field->type == 'file') {
                    $formFields[$key] = $field->type == 'checkbox' && \is_array($val) && \count($val) == 1 ? $val[0] : $val;
                } elseif ($field->type == 'address') {
                    $addressKey = $field->field_key;
                    foreach ($val as $k => $value) {
                        $formFields[$addressKey . '_' . $k] = $value;
                    }
                } elseif ($field->type == 'divider') {
                    $repeaterFld = $field->field_key;
                    global $wpdb;

                    $allDividerFlds = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}frm_item_metas WHERE item_id IN (SELECT id FROM {$wpdb->prefix}frm_items WHERE parent_item_id = {$entryId})");
                    $allItemId = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}frm_items WHERE parent_item_id = {$entryId}");

                    $repeater = [];
                    foreach ($allItemId as $value) {
                        $itemId = $value->id;
                        foreach ($allDividerFlds as $valueTmp) {
                            $fldId = $valueTmp->field_id;
                            if ($valueTmp->item_id == $itemId) {
                                $formFields[$repeaterFld . '_' . $fldId . '_' . $itemId] = $valueTmp->meta_value;
                                $repeater[$itemId][] = (object) [
                                    $fldId => $valueTmp->meta_value
                                ];
                            }
                        }
                    }

                    $formFields[$repeaterFld] = $repeater;
                }

                continue;
            }

            $formFields[$key] = $val;
        }

        return $formFields;
    }

    public static function handleFormidableSubmit($_confMethod, $form, $formOption, $entryId)
    {
        $formId = $form->id;
        $file = self::fields(($formId));
        $fileFlds = [];

        foreach ($file as $fldVal) {
            if ($fldVal->type == 'file') {
                $fileFlds[] = $fldVal->name;
            }
        }

        $formData = self::getFieldsValues($form, $entryId);
        $postId = url_to_postid($_SERVER['HTTP_REFERER']);

        if (!empty($formId)) {
            if ($postId) {
                $formData['post_id'] = $postId;
            }

            foreach ($formData as $key => $val) {
                if (\in_array($key, $fileFlds)) {
                    if (\is_array($val)) {
                        foreach (array_keys($val) as $fileKey) {
                            $tmpData = wp_get_attachment_url($formData[$key][$fileKey]);
                            $formData[$key][$fileKey] = Utility::getFilePath($tmpData);
                        }
                    } else {
                        $tmpData = wp_get_attachment_url($formData[$key]);
                        $formData[$key] = Utility::getFilePath($tmpData);
                    }
                }
            }

            if (!empty($formId) && $flows = FlowService::exists('formidable', 'formSubmit')) {
                IntegrationHelper::handleFlowForForm($flows, $formData, $formId);
            }
        }
    }
}
