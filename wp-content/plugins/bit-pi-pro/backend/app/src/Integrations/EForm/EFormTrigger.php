<?php

namespace BitApps\PiPro\src\Integrations\EForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use IPT_FSQM_Form_Elements_Data;

final class EFormTrigger
{
    public function getAll()
    {
        if (!self::isActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'eForm'));
        }

        $allForms = [
            [
                'label' => 'Any form',
                'value' => 'any'
            ],
        ];

        $forms = $this->forms();

        if ($forms) {
            foreach ($forms as $form) {
                $allForms[] = (object) [
                    'value' => $form->id,
                    'label' => $form->name
                ];
            }
        }

        return Response::success($allForms);
    }

    public static function forms($formId = null)
    {
        global $wpdb, $ipt_fsqm_info;

        if (\is_null($formId)) {
            // phpcs:ignore
            return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$ipt_fsqm_info['form_table']} ORDER BY id DESC"));
        }

        // phpcs:ignore
        return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$ipt_fsqm_info['form_table']} WHERE id = %d ORDER BY id DESC", $formId));
    }

    public static function handleSubmission($data)
    {
        if (!($data instanceof IPT_FSQM_Form_Elements_Data)) {
            return;
        }

        $flows = FlowService::exists('eForm', 'submissionSuccess');

        $formId = $data->form_id;

        if (empty($formId) || !$flows) {
            return;
        }

        $formData = array_merge(
            EFormHelper::processValues($data, 'pinfo'),
            EFormHelper::processValues($data, 'freetype'),
            EFormHelper::processValues($data, 'mcq')
        );

        $formData = Utility::convertDotKeysToColons($formData);

        IntegrationHelper::handleFlowForForm($flows, $formData, $formId);
    }

    private function isActive()
    {
        global $ipt_fsqm_info;

        return class_exists('IPT_FSQM_Loader') && \is_array($ipt_fsqm_info);
    }
}
