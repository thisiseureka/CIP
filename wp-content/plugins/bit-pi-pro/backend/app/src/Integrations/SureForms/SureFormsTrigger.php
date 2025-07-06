<?php

namespace BitApps\PiPro\src\Integrations\SureForms;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class SureFormsTrigger
{
    public function getAll()
    {
        if (!is_plugin_active('sureforms/sureforms.php')) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'SureForms'));
        }

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        $sureForms = get_posts(
            [
                'posts_per_page' => -1,
                'orderby'        => 'name',
                'order'          => 'asc',
                'post_type'      => 'sureforms_form',
                'post_status'    => 'publish',
            ]
        );

        if (!empty($sureForms)) {
            foreach ($sureForms as $item) {
                $allForms[] = (object) ['value' => (string) $item->ID, 'label' => $item->post_title];
            }
        }

        return Response::success($allForms);
    }

    public static function handleSureFormsSubmit($formSubmitResponse)
    {
        $formId = $formSubmitResponse['form_id'];
        $formData = $formSubmitResponse['data'];

        if (empty($formId) || empty($formData)) {
            return;
        }

        if ($flows = FlowService::exists('sureForms', 'sureFormsSubmission')) {
            IntegrationHelper::handleFlowForForm($flows, $formData, $formId);
        }
    }
}
