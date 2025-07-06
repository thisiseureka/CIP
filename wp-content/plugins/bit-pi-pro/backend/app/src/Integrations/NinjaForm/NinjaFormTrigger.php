<?php

namespace BitApps\PiPro\src\Integrations\NinjaForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class NinjaFormTrigger
{
    public function getAll()
    {
        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];
        if (\function_exists('Ninja_Forms') && \is_callable('Ninja_Forms')) {
            $forms = Ninja_Forms()->form()->get_forms();
            if ($forms) {
                foreach ($forms as $form) {
                    $allForms[] = (object) [
                        'value' => $form->get_id(),
                        'label' => $form->get_setting('title')
                    ];
                }
            }
        }

        return $allForms;
    }

    public static function afterSubmission($data)
    {
        $entry = [];

        foreach ($data['fields'] as $field) {
            $fieldType = $field['settings']['type'] ?? null;
            $fieldId = $field['id'] ?? null;
            $fieldValue = $field['value'] ?? null;

            if (!$fieldId || $fieldType === 'submit') {
                continue;
            }

            switch ($fieldType) {
                case strpos($fieldType, 'file') !== false ? $fieldType : null:
                    $entry[$fieldId] = \is_array($fieldValue)
                        ? array_column($fieldValue, null)
                        : $fieldValue;

                    break;

                case 'repeater':
                    if (!empty($field['fields']) && \is_array($fieldValue)) {
                        foreach ($fieldValue as $repeatKey => $repeatVal) {
                            $entry[$repeatKey] = $repeatVal['value'] ?? null;
                        }
                        $entry[$fieldId] = $field['fields'];
                    }

                    break;

                default:
                    $entry[$fieldId] = $fieldValue;

                    break;
            }
        }

        $formId = $data['form_id'] ?? null;

        if (!empty($formId) && ($flows = FlowService::exists('ninjaForm', 'processNinjaForm'))) {
            IntegrationHelper::handleFlowForForm($flows, $entry, $formId);
        }
    }
}
