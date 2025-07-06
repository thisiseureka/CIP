<?php

namespace BitApps\PiPro\src\Integrations\WsForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class WsFormTrigger
{
    private const PLUGIN_INDEX = 'ws-form-pro/ws-form.php';

    public static function handleSubmit($form, $submit)
    {
        if (empty($submit) || !isset($submit->form_id)) {
            return;
        }

        $formId = $submit->form_id;

        $flows = FlowService::exists('wsForm', 'wsFormSubmit');

        if (!$flows) {
            return;
        }

        $data = [];

        if (!empty($submit->meta) && \is_array($submit->meta)) {
            foreach ($submit->meta as $key => $fieldValue) {
                if (empty($fieldValue)) {
                    continue;
                }

                if (\is_array($fieldValue) && !\array_key_exists('id', $fieldValue)) {
                    continue;
                }

                $value = wsf_submit_get_value($submit, $key);

                if (!empty($value) && \in_array($fieldValue['type'], ['file', 'signature'])) {
                    $upDir = wp_upload_dir();

                    $files = $value;

                    $value = [];

                    if (\is_array($files)) {
                        foreach ($files as $k => $file) {
                            if (!empty($file['path']) && !\array_key_exists('hash', $file)) {
                                $value[$k] = $upDir['basedir'] . '/' . $file['path'];
                            }
                        }
                    }
                }

                if (isset($fieldValue['type']) && $fieldValue['type'] === 'radio') {
                    $value = \is_array($value) ? reset($value) : $value;
                }

                $data[$key] = $value;
            }
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $formId);
    }

    public function getForms()
    {
        if (!is_plugin_active(self::PLUGIN_INDEX)) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'WS Form Pro'));
        }

        $forms = wsf_form_get_all(true, 'label');

        $forms = array_map(
            fn ($form) => [
                'value' => $form['id'],
                'label' => $form['label'],
            ],
            $forms
        );

        return Response::success($forms);
    }
}
