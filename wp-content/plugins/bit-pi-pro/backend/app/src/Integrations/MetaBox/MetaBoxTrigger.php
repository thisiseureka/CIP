<?php

namespace BitApps\PiPro\src\Integrations\MetaBox;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class MetaBoxTrigger
{
    public function getForms()
    {
        if (!\function_exists('rwmb_meta') || !\function_exists('mb_frontend_submission_load')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Meta Box'));
        }

        $metaBoxRegistry = rwmb_get_registry('meta_box');

        $forms = array_values($metaBoxRegistry->all());

        array_map(
            function ($form) {
                $form->value = $form->meta_box['id'];
                $form->label = $form->meta_box['title'];
                unset($form->meta_box);
            },
            $forms
        );

        array_unshift($forms, ['value' => 'any', 'label' => 'Any Form']);

        return Response::success($forms);
    }

    public static function getMetaboxFields($form_id)
    {
        if (\function_exists('rwmb_meta')) {
            $meta_box_registry = rwmb_get_registry('meta_box');

            $fileUploadTypes = ['file_upload', 'single_image', 'file'];

            $form = $meta_box_registry->get($form_id);

            $fieldDetails = $form->meta_box['fields'];

            $fields = [];

            foreach ($fieldDetails as $field) {
                if (!empty($field['id']) && $field['type'] !== 'submit') {
                    $fields[] = [
                        'name'  => $field['id'],
                        'type'  => \in_array($field['type'], $fileUploadTypes) ? 'file' : $field['type'],
                        'label' => $field['name'],
                    ];
                }
            }

            return $fields;
        }

        return [];
    }

    public static function handleSubmit($object)
    {
        $flows = FlowService::exists('metaBox', 'saveMetaBoxData');

        if (!$flows) {
            return;
        }

        $formId = $object->config['id'];

        $postId = $object->post_id;

        $fields = self::getMetaboxFields($formId);

        $metaBoxFieldValues = [];

        if ($fields) {
            foreach ($fields as $field) {
                $fieldValues = rwmb_meta($field['name'], [], $postId);

                if (!$fieldValues) {
                    continue;
                }

                if ($field['type'] === 'file') {
                    $metaBoxFieldValues[$field['name']] = [];

                    if (isset($fieldValues['path'])) {
                        $metaBoxFieldValues[$field['name']] = $fieldValues['path'];
                    } elseif (\is_array($fieldValues)) {
                        $metaBoxFieldValues[$field['name']] = array_map(fn ($file) => $file['path'] ?? null, $fieldValues);
                    }
                } else {
                    $metaBoxFieldValues[$field['name']] = $fieldValues;
                }
            }
        }

        $postFieldValues = (array) get_post($postId);

        unset($postFieldValues['ID']);

        $data = array_merge(['id' => $formId], $metaBoxFieldValues, ['post_id' => $postId], $postFieldValues);

        IntegrationHelper::handleFlowForForm($flows, $data, $formId);
    }
}
