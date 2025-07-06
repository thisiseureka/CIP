<?php

namespace BitApps\PiPro\src\Integrations\WeForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class WeFormsTrigger
{
    public function getAll()
    {
        if (!\function_exists('weforms')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'WeForms'));
        }

        $forms = weforms()->form->all();

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        if ($forms) {
            foreach ($forms['forms'] as $form) {
                $allForms[] = (object) [
                    'value' => $form->id,
                    'label' => $form->name,
                ];
            }
        }

        return Response::success($allForms);
    }

    public static function handleWeFormsSubmit($entryId, $formId)
    {
        $dataAll = weforms_get_entry_data($entryId);

        foreach ($dataAll['fields'] as $key => $field) {
            if ($field['type'] === 'image_upload' || $field['type'] === 'file_upload') {
                $dataAll['data'][$key] = explode('"', $dataAll['data'][$key])[1];
                // $dataAll['data'][$key] = self::parsedImage($dataAll['data'][$key]);
            }
        }

        $submittedData = $dataAll['data'];

        foreach ($submittedData as $key => $value) {
            $str = "{$key}";
            $pattern = '/name/i';
            $isName = preg_match($pattern, $str);
            if ($isName) {
                unset($submittedData[$key]);
                $nameValues = explode('|', $value);
                if (\count($nameValues) == 2) {
                    $nameOrganized = [
                        'first_name' => $nameValues[0],
                        'last_name'  => $nameValues[1]

                    ];
                } else {
                    $nameOrganized = [
                        'first_name'  => $nameValues[0],
                        'middle_name' => $nameValues[1],
                        'last_name'   => $nameValues[2]
                    ];
                }
            }
        }

        $finalData = array_merge($submittedData, $nameOrganized);
        $flows = FlowService::exists('weForms', 'weFormsSubmit');

        if (!empty($formId) && $flows = FlowService::exists('weForms', 'weFormsSubmit')) {
            IntegrationHelper::handleFlowForForm($flows, $finalData, $formId);
        }
    }
}
