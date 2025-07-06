<?php

namespace BitApps\PiPro\src\Integrations\GravityForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use GFFormsModel;

final class GravityFormTrigger
{
    public function __construct()
    {
    }

    public function getAll()
    {
        if (!(class_exists('GFFormsModel') && \is_callable('GFFormsModel::get_forms'))) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Gravity Forms'));
        }

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];
        $forms = GFFormsModel::get_forms(1); // param is_active = 1
        if ($forms) {
            foreach ($forms as $form) {
                $allForms[] = (object) [
                    'value' => $form->id,
                    'label' => $form->title
                ];
            }
        }

        return Response::success($allForms);
    }

    public static function gformAfterSubmission($entry, $form)
    {
        $formId = $form['id'];
        if (!empty($formId) && $flows = FlowService::exists('gravityForm', 'formSubmission')) {
            foreach ($form['fields'] as $value) {
                if ($value->type === 'fileupload' && isset($entry[$value->id])) {
                    if ($value->multipleFiles === false) {
                        $entry[$value->id] = Utility::getFilePath($entry[$value->id]);
                    } else {
                        $entry[$value->id] = Utility::getFilePath(json_decode($entry[$value->id], true));
                    }
                }

                if ($value->type === 'checkbox' && \is_array($value->inputs)) {
                    foreach ($value->inputs as $input) {
                        if (isset($entry[$input['id']])) {
                            $entry[$value->id][] = $entry[$input['id']];
                        }
                    }
                }
            }

            $entry = Utility::convertDotKeysToColons($entry);

            $finalData = $entry + ['title' => $form['title']];

            IntegrationHelper::handleFlowForForm($flows, $finalData, $formId);
        }
    }
}
