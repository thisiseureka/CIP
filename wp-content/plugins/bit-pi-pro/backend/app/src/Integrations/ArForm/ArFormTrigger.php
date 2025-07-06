<?php

namespace BitApps\PiPro\src\Integrations\ArForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class ArFormTrigger
{
    private const ARFORMS_PLUGIN_INDEX = 'arforms/arforms.php';

    private const ARFORMS_FORM_BUILDER_PLUGIN_INDEX = 'arforms-form-builder/arforms-form-builder.php';

    public function getForms()
    {
        if (
            !is_plugin_active(self::ARFORMS_FORM_BUILDER_PLUGIN_INDEX)
            || !is_plugin_active(self::ARFORMS_PLUGIN_INDEX)
        ) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'ARForm'));
        }

        $forms = self::getAllForms();

        $forms = array_map(
            fn ($form) => (object) [
                'value' => $form->id,
                'label' => $form->name,
            ],
            $forms
        );

        return Response::success($forms);
    }

    public static function handleSubmit($params, $errors, $form, $itemMetaValues)
    {
        $formId = $form->id;

        $flows = FlowService::exists('arForm', 'arFormEntryExecute');

        if ($flows) {
            return IntegrationHelper::handleFlowForForm($flows, $itemMetaValues, $formId);
        }
    }

    private function getAllForms()
    {
        global $wpdb;

        $forms = $wpdb->get_results($wpdb->prepare("SELECT id,name FROM {$wpdb->prefix}arf_forms WHERE is_template = 0 AND status = 'published'"));

        if (is_wp_error($forms)) {
            return [];
        }

        return $forms;
    }
}
