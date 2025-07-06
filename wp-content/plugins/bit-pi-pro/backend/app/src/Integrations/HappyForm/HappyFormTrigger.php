<?php

namespace BitApps\PiPro\src\Integrations\HappyForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use DateTime;

final class HappyFormTrigger
{
    public function getAllForms()
    {
        if (!\function_exists('HappyForms')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Happy Form'));
        }

        $forms = happyforms_get_form_controller()->get();

        if (!$forms) {
            return Response::success('Form not found');
        }

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        foreach ($forms as $form) {
            $allForms[] = (object) [
                'value' => $form['ID'],
                'label' => $form['post_title'],
            ];
        }

        return Response::success($allForms);
    }

    public static function handleSubmmit($submission, $form)
    {
        $data = [];

        $postId = url_to_postid($_SERVER['HTTP_REFERER']);

        $formId = $form['ID'];

        if ($postId) {
            $data['post_id'] = $postId;
        }

        if (!empty($formId)) {
            $formData = $submission;

            foreach ($formData as $key => $val) {
                if (str_contains($key, 'signature')) {
                    $baseUrl = maybe_unserialize($val)['signature_raster_data'];
                    $path = HappyFormHelper::saveImageToHappyFormDir($baseUrl, 'sign');
                    $formData[$key] = $path;
                } elseif (str_contains($key, 'date')) {
                    if (strtotime($val)) {
                        $dateTmp = new DateTime($val);
                        $dateFinal = date_format($dateTmp, 'Y-m-d');
                        $formData[$key] = $dateFinal;
                    }
                } elseif (str_contains($key, 'attachment')) {
                    $attachmentUrlLinks = HappyFormHelper::getAttachementUrlLinks($val);
                    $formData[$key] = Utility::getFilePath($attachmentUrlLinks);
                }
            }

            if (!empty($formId) && $flows = FlowService::exists('happyForm', 'submissionSuccess')) {
                IntegrationHelper::handleFlowForForm($flows, $formData, $formId);
            }
        }
    }
}
