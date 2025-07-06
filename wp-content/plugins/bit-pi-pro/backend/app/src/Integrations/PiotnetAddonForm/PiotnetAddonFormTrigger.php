<?php

namespace BitApps\PiPro\src\Integrations\PiotnetAddonForm;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class PiotnetAddonFormTrigger
{
    public static function pluginActive($option = null)
    {
        if (is_plugin_active('piotnet-addons-for-elementor-pro/piotnet-addons-for-elementor-pro.php')) {
            return $option === 'get_name' ? 'piotnet-addons-for-elementor-pro/piotnet-addons-for-elementor-pro.php' : true;
        }

        if (is_plugin_active('piotnet-addons-for-elementor/piotnet-addons-for-elementor.php')) {
            return $option === 'get_name' ? 'piotnet-addons-for-elementor/piotnet-addons-for-elementor.php' : true;
        }

        return false;
    }

    public static function handlePiotnetSubmit($formSubmission)
    {
        $flows = FlowService::exists('piotnetAddonForm', 'handleAddonFormSubmitV2');
        if (!$flows) {
            return;
        }

        $data = [];
        $fields = $formSubmission['fields'];
        $formId = $formSubmission['form']['id'];

        foreach ($fields as $key => $field) {
            $data[$key] = $field['value'];
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $formId, 'newRecordV2-id');
    }

    public function getAllForms()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Piotnet Addon'));
        }

        $posts = $this->getElementorPosts();

        $piotnetForms = [];
        if ($posts) {
            foreach ($posts as $post) {
                $piotnetForms[] = (object) [
                    'value' => $post->ID,
                    'label' => $post->post_title,
                ];
            }
        }

        return Response::success($piotnetForms);
    }

    public static function getAllFieldsFromPostMeta($postMeta)
    {
        $piotNetFields = [];
        foreach ($postMeta as $widget) {
            self::getFields($widget->elements, $piotNetFields);
        }

        return $piotNetFields;
    }

    private static function getFields($widget, &$piotNetFields)
    {
        foreach ($widget as $elements) {
            if (!empty($elements->elements)) {
                self::getFields($elements->elements, $piotNetFields);
            } elseif (isset($elements->widgetType) && $elements->widgetType == 'pafe-form-builder-field') {
                $piotNetFields[] = $elements;
            }
        }
    }

    private function getElementorPosts()
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)
                WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'pafe-forms' AND {$wpdb->postmeta}.meta_key = '_elementor_data'"
            )
        );
    }
}
