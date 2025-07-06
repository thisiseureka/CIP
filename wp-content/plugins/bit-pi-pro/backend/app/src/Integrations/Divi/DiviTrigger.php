<?php

namespace BitApps\PiPro\src\Integrations\Divi;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class DiviTrigger
{
    public static function isDiviActive()
    {
        global $themename;
        if (empty($themename)) {
            return false;
        }

        $diviThemes = [
            'divi',
            'extra',
            'bloom',
            'monarch',
        ];

        return \in_array(strtolower($themename), $diviThemes);
    }

    public static function handleDiviSubmit($etPbContactFormSubmit, $etContactError, $contactFormInfo)
    {
        $recordData = DiviHelper::extractRecordData($contactFormInfo, $etPbContactFormSubmit);

        $formData = DiviHelper::setFields($recordData);

        $flows = FlowService::exists('divi', 'contactFormSubmit');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
