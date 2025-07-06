<?php

namespace BitApps\PiPro\src\Integrations\FormCraft;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class FormCraftTrigger
{
    private const FORMCRAFT_TABLE_NAME = 'formcraft_3_forms';

    private const PLUGIN_INDEX = 'formcraft3/formcraft-main.php';

    public static function isPluginActive()
    {
        return is_plugin_active(self::PLUGIN_INDEX);
    }

    public function getAllForms()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'FormCraft3'));
        }

        global $wpdb;

        $tableName = $wpdb->prefix . self::FORMCRAFT_TABLE_NAME;

        $forms = $wpdb->get_results("SELECT id,name FROM {$tableName}");

        if (!$forms) {
            return Response::success('Form not found');
        }

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        foreach ($forms as $form) {
            $allForms[] = (object) [
                'value' => $form->id,
                'label' => $form->name,
            ];
        }

        return Response::success($allForms);
    }

    public static function handleSubmit($template, $meta, $content)
    {
        $formId = $template['Form ID'];

        $flows = FlowService::exists('formCraft', 'formSubmit');

        if (!$flows || !$formId) {
            return;
        }

        $finalData = [];

        if (!empty($content)) {
            foreach ($content as $value) {
                $finalData[$value['identifier']] = $value['type'] === 'fileupload' ? $value['url'][0] : $value['value'];
            }
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData, $formId);
    }
}
