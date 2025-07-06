<?php

namespace BitApps\PiPro\src\Integrations\QuillForms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class QuillFormsTrigger
{
    public static function handleFormSubmitted($entry, $formData)
    {
        $data = ['entry' => $entry, 'form_data' => $formData];
        $flows = FlowService::exists('QuillForms', 'formSubmission');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return \defined('QUILLFORMS_PLUGIN_FILE');
    }
}
