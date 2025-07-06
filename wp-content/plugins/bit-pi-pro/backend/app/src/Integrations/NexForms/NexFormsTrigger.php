<?php

namespace BitApps\PiPro\src\Integrations\NexForms;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class NexFormsTrigger
{
    public static function handleFormSubmitted()
    {
        $formData = static::sanitizePostData($_POST);
        $flows = FlowService::exists('nexForms', 'formSubmission');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }

    private static function sanitizePostData($data)
    {
        if (!is_iterable($data)) {
            return sanitize_text_field($data);
        }

        foreach ($data as $key => $value) {
            $data[$key] = static::sanitizePostData($value);
        }

        return $data;
    }

    // TODO:: need to implement
    private static function isPluginInstalled()
    {
        return class_exists('NEXForms5_Config');
    }
}
