<?php

namespace BitApps\PiPro\src\Integrations\AvadaForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class AvadaFormsTrigger
{
    public static function formSubmission($formSubmission, $formId)
    {
        $flows = FlowService::exists('avadaForms', 'formSubmission');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, array_merge(['form_id' => $formId], $formSubmission['data']));
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('Fusion_Builder_Form_Helper');
    }
}
