<?php

namespace BitApps\PiPro\src\Integrations\PieForms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use BitCode\FI\Core\Util\Helper;

final class PieFormsTrigger
{
    public static function handleFormSubmitted($entryId, $fields, $entry, $formId, $formData)
    {
        $formData = PieFormsHelper::formatFields($formId, $formData);

        $flows = FlowService::exists('pieForms', 'formSubmissionHandled');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
