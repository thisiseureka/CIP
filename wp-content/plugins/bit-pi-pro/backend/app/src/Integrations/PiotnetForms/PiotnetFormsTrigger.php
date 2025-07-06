<?php

namespace BitApps\PiPro\src\Integrations\PiotnetForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class PiotnetFormsTrigger
{
    public const PRO_PLUGIN_INDEX = 'piotnetforms-pro/piotnetforms-pro.php';

    public const FREE_PLUGIN_INDEX = 'piotnetforms/piotnetforms.php';

    public static function isPluginActive()
    {
        if (is_plugin_active(self::PRO_PLUGIN_INDEX)) {
            return true;
        }

        return (bool) (is_plugin_active(self::FREE_PLUGIN_INDEX));
    }

    public static function handleSubmit($fields)
    {
        $postId = isset($_REQUEST['post_id']) ? absint($_REQUEST['post_id']) : 0;

        $flows = FlowService::exists('piotnetForms', 'handlePiotnetForm');

        if (!$flows) {
            return;
        }

        $data = [];

        foreach ($fields as $field) {
            if (
                (isset($field['type'])
                 && \in_array($field['type'], ['file', 'signature'], true))
                || (!empty($field['image_upload']))
            ) {
                $field['value'] = Utility::getFilePath($field['value']);
            }

            $data[$field['name']] = $field['value'];
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $postId);
    }

    public function getAllForms()
    {
        if (!self::isPluginActive()) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Piotnet Forms'));
        }

        $forms = $this->getForms();

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        if ($forms) {
            foreach ($forms as $form) {
                $allForms[] = (object) [
                    'value' => $form->ID,
                    'label' => $form->post_title,
                ];
            }
        }

        return Response::success($allForms);
    }

    private function getForms()
    {
        global $wpdb;

        return $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='piotnetforms'");
    }
}
