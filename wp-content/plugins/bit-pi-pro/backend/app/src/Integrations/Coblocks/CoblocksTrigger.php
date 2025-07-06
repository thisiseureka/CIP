<?php

namespace BitApps\PiPro\src\Integrations\Coblocks;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

class CoblocksTrigger
{
    public static function handleSubmit($formData, $attributes, $email)
    {
        $flows = FlowService::exists('coblocks', 'formSubmit');

        if (!$flows) {
            return;
        }

        $data = [
            'form-data'  => $formData,
            'attributes' => $attributes,
        ];

        if (!empty($email)) {
            $data['email'] = $email;
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }
}
