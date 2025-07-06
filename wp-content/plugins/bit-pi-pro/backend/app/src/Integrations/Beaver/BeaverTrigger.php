<?php

namespace BitApps\PiPro\src\Integrations\Beaver;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class BeaverTrigger
{
    public static function beaverContactFormSubmitted($mailto, $subject, $template)
    {
        $flows = FlowService::exists('beaver', 'beaverContactFormSubmission');
        if (!$flows) {
            return;
        }

        $template = str_replace('Name', '|Name', $template);
        $template = str_replace('Email', '|Email', $template);
        $template = str_replace('Phone', '|Phone', $template);
        $template = str_replace('Message', '|Message', $template);

        $filterData = explode('|', $template);
        $filterData = array_map('trim', $filterData);
        $filterData = array_filter($filterData, fn ($value) => $value !== '');

        $data = ['subject' => isset($subject) ? $subject : ''];
        foreach ($filterData as $value) {
            $item = explode(':', $value);
            $data[strtolower($item[0])] = trim($item[1]);
        }

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function beaverSubscribeFormSubmitted($response, $settings, $email, $name)
    {
        $flows = FlowService::exists('beaver', 'beaverSubscribeFormSubmission');
        if (!$flows) {
            return;
        }

        $data = [
            'name'  => isset($name) ? $name : '',
            'email' => isset($email) ? $email : '',
        ];
        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function beaverLoginFormSubmitted($settings, $password, $name)
    {
        $flows = FlowService::exists('beaver', 'beaverLoginFormSubmission');
        if (!$flows) {
            return;
        }

        $data = [
            'name'     => isset($name) ? $name : '',
            'password' => isset($password) ? $password : '',
        ];
        IntegrationHelper::handleFlowForForm($flows, $data);
    }
}
