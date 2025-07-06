<?php

namespace BitApps\PiPro\src\Integrations\Groundhogg;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use Groundhogg\Tag;

final class GroundhoggTrigger
{
    public static function isPluginActive()
    {
        return (bool) (is_plugin_active('groundhogg/groundhogg.php'));
    }

    public static function handleGroundhoggSubmit($a, $fieldValues)
    {
        global $wp_rest_server;

        if (!method_exists($wp_rest_server, 'get_raw_data')) {
            return;
        }

        $request = $wp_rest_server->get_raw_data();
        $data = json_decode($request);
        $meta = $data->meta;

        $fieldValues['primary_phone'] = $meta->primary_phone;
        $fieldValues['mobile_phone'] = $meta->mobile_phone;

        if (isset($data->tags)) {
            $fieldValues['tags'] = self::setTagNames($data->tags);
        }

        $flows = FlowService::exists('groundhogg', 'contactCreate');

        if (!$flows) {
            return;
        }

        $data = $fieldValues;

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function tagApplied($tagData, $tagId)
    {
        $data = $tagData['data'];

        $flows = FlowService::exists('groundhogg', 'tagApplied');

        if (!$flows) {
            return;
        }

        if (isset($tagData['tags'])) {
            $data['tags'] = self::setTagNames($tagData['tags']);
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $tagId, 'tag-id');
    }

    public static function tagRemove($tagData, $tagId)
    {
        $data = $tagData['data'];

        $flows = FlowService::exists('groundhogg', 'tagRemove');

        if (!$flows) {
            return;
        }

        if (isset($tagData['tags'])) {
            $data['tags'] = self::setTagNames($tagData['tags']);
        }

        IntegrationHelper::handleFlowForForm($flows, $data, $tagId, 'tag-id');
    }

    public static function getAllFormsFromPostMeta($postMeta)
    {
        $forms = [];
        foreach ($postMeta as $widget) {
            foreach ($widget->elements as $elements) {
                foreach ($elements->elements as $element) {
                    if (isset($element->widgetType) && $element->widgetType == 'form') {
                        $forms[] = $element;
                    }
                }
            }
        }

        return $forms;
    }

    public static function getAllTags()
    {
        if (!self::isPluginActive()) {
            return Response::error(__('Groundhogg plugin is not active', 'bit-pi'));
        }

        $allTag = [
            [
                'value' => 'any',
                'label' => __('Any Tag', 'bit-pi')
            ],
        ];

        if (\function_exists('\Groundhogg\get_db')) {
            $tags = \Groundhogg\get_db('tags')->query(['limit' => 1000]);

            foreach ($tags as $val) {
                $allTag[] = [
                    'value' => $val->tag_id,
                    'label' => $val->tag_name,
                ];
            }
        }

        return Response::success($allTag);
    }

    private static function setTagNames($tagIds)
    {
        if (!class_exists('\Groundhogg\Tag')) {
            return;
        }

        $tag_list = [];
        foreach ($tagIds as $tagId) {
            $tag = new Tag($tagId);
            $tag_list[] = $tag->get_name();
        }

        return implode(',', $tag_list);
    }
}
