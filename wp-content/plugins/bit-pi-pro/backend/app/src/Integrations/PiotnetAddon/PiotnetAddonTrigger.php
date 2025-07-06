<?php

namespace BitApps\PiPro\src\Integrations\PiotnetAddon;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class PiotnetAddonTrigger
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
        $formId = $formSubmission['form']['id'];

        $flows = FlowService::exists('piotnetAddon', 'handlePiotnetFormSubmissionV2');
        if (!$flows) {
            return;
        }

        $data = [];

        $fields = $formSubmission['fields'];

        foreach ($fields as $key => $field) {
            $data[$key] = $field['value'];
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $formId);
    }

    public function getAllForms()
    {
        if (!self::pluginActive()) {
            // translators: %s: Plugin Version
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Piotnet Addon'));
        }

        $posts = $this->getElementorPosts();

        $piotnetForms = [];
        $piotnetIds = [];
        if ($posts) {
            foreach ($posts as $post) {
                $postMeta = $this->getElementorPostMeta($post->ID);
                $forms = self::getAllFormsFromPostMeta($postMeta);

                foreach ($forms as $form) {
                    // for piotnet addon field
                    if ($form->widgetType != 'pafe-form-builder-field') {
                        continue;
                    }

                    if (\in_array($form->settings->form_id, $piotnetIds)) {
                        continue;
                    }

                    $piotnetIds[] = $form->settings->form_id;
                    $piotnetForms[] = (object) [
                        'value' => $form->settings->form_id,
                        'label' => "Piotnet Forms - {$form->settings->form_id}",
                        // 'post_id' => $post->ID,//need to check
                    ];
                }
            }
        }

        return Response::success($piotnetForms);
    }

    public static function getAllFormsFromPostMeta($postMeta)
    {
        $piotNetForms = [];
        foreach ($postMeta as $widget) {
            foreach ($widget->elements as $elements) {
                foreach ($elements->elements as $element) {
                    if (isset($element->widgetType) && $element->widgetType == 'pafe-form-builder-field') {
                        $piotNetForms[] = $element;
                    }
                }
            }
        }

        return $piotNetForms;
    }

    private function getElementorPosts()
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts}
                LEFT JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)
                WHERE {$wpdb->posts}.post_status = 'publish' 
                AND ({$wpdb->posts}.post_type = 'post' 
                OR {$wpdb->posts}.post_type = 'page' 
                OR {$wpdb->posts}.post_type = 'elementor_library') 
                AND {$wpdb->postmeta}.meta_key = '_elementor_data'"
            )
        );
    }

    private function getElementorPostMeta(int $formId)
    {
        global $wpdb;
        $postMeta = $wpdb->get_results("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id={$formId} AND meta_key='_elementor_data' LIMIT 1");

        return json_decode($postMeta[0]->meta_value);
    }
}
