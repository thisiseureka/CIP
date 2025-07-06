<?php

namespace BitApps\PiPro\src\Integrations\Forminator;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use DateTime;
use Forminator_API;

final class ForminatorTrigger
{
    public function getAll()
    {
        if (!class_exists('Forminator')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Forminator'));
        }

        $forms = Forminator_API::get_forms(null, 1, 100);

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

    // forminator didn't return any kind of type of value..
    public static function handleForminatorSubmit($_entry, $formId, $formData)
    {
        $flows = FlowService::exists('forminator', 'formSubmit');

        if (!$flows) {
            return;
        }

        $postId = url_to_postid($_SERVER['HTTP_REFERER']);

        if (!empty($formId)) {
            $data = [];
            if ($postId) {
                $data['post_id'] = $postId;
            }

            foreach ($formData as $fldDetail) {
                if (\is_array($fldDetail['value'])) {
                    if (\array_key_exists('file', $fldDetail['value'])) {
                        $data[$fldDetail['name']] = [$fldDetail['value']['file']['file_path']];
                    } elseif (explode('-', $fldDetail['name'])[0] === 'name') {
                        if ($fldDetail['name']) {
                            $lastDashPosition = strrpos($fldDetail['name'], '-');
                            $index = substr($fldDetail['name'], $lastDashPosition + 1);
                        }

                        foreach ($fldDetail['value'] as $nameKey => $nameVal) {
                            $data[$nameKey . '-' . $index] = $nameVal;
                        }
                    } elseif (explode('-', $fldDetail['name'])[0] === 'address') {
                        if ($fldDetail['name']) {
                            $lastDashPosition = strrpos($fldDetail['name'], '-');
                            $index = substr($fldDetail['name'], $lastDashPosition + 1);
                        }

                        foreach ($fldDetail['value'] as $nameKey => $nameVal) {
                            $data[$nameKey . '-' . $index] = $nameVal;
                        }
                    } else {
                        $val = $fldDetail['value'];
                        if (\array_key_exists('ampm', $val)) {
                            $time = $val['hours'] . ':' . $val['minutes'] . ' ' . $val['ampm'];
                            $data[$fldDetail['name']] = $time;
                        } elseif (\array_key_exists('year', $val)) {
                            $date = $val['year'] . '-' . $val['month'] . '-' . $val['day'];
                            $data[$fldDetail['name']] = $date;
                        } elseif (\array_key_exists('formatting_result', $val)) {
                            $data[$fldDetail['name']] = $fldDetail['value']['formatting_result'];
                        } else {
                            $data[$fldDetail['name']] = $fldDetail['value'];
                        }
                    }
                } elseif (self::isValidDate($fldDetail['value'])) {
                    $dateTmp = new DateTime($fldDetail['value']);
                    $dateFinal = date_format($dateTmp, 'Y-m-d');
                    $data[$fldDetail['name']] = $dateFinal;
                } else {
                    $data[$fldDetail['name']] = $fldDetail['value'];
                }
            }
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $formId);
    }

    public static function isValidDate($date, $format = 'd/m/Y')
    {
        $dateTime = DateTime::createFromFormat($format, $date);

        return $dateTime && $dateTime->format($format) === $date;
    }
}
