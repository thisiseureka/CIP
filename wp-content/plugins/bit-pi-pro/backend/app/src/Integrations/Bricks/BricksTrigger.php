<?php

namespace BitApps\PiPro\src\Integrations\Bricks;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class BricksTrigger
{
    public static function isPluginActive()
    {
        return wp_get_theme()->get_template() === 'bricks';
    }

    // TODO: check if machine name is correct

    public static function handleBricksSubmit($form)
    {
        $fields = $form->get_fields();

        $settings = $form->get_settings();

        $files = $form->get_uploaded_files();

        $recordData = BricksHelper::extractRecordData($fields, $settings, $files);

        $formData = BricksHelper::setFields($recordData);

        $flows = FlowService::exists('bricks', 'bricksforgeFormSubmit');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $formData);
    }
}
