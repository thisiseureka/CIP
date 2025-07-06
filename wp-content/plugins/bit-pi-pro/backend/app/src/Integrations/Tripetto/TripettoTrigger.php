<?php

namespace BitApps\PiPro\src\Integrations\Tripetto;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class TripettoTrigger
{
    public static $allIndividualFormFields = [];

    public static function isTripettoActive()
    {
        return is_plugin_active('tripetto/plugin.php') || is_plugin_active('tripetto-pro/plugin.php');
    }

    public function getAll()
    {
        if (!self::isTripettoActive()) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Tripetto'));
        }

        $forms = self::isTripettoForm();

        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        if ($forms) {
            foreach ($forms as $form) {
                $allForms[] = (object) [
                    'value' => $form->id,
                    'label' => $form->name,
                ];
            }
        }

        return Response::success($allForms);
    }

    public static function isTripettoForm()
    {
        global $wpdb;

        return $wpdb->get_results($wpdb->prepare("SELECT id, name FROM {$wpdb->prefix}tripetto_forms"));
    }

    public static function getAllFormFields($formId)
    {
        global $wpdb;
        $data = $wpdb->get_results($wpdb->prepare("SELECT definition FROM {$wpdb->prefix}tripetto_forms WHERE id = %d", $formId));

        $data = json_decode($data[0]->definition);

        if (isset($data->clusters)) {
            $clusterData = $data->clusters;
            foreach ($clusterData as $singleCluster) {
                self::getAllDripperFormFields($singleCluster);
            }
        } else {
            $sectionData = $data->sections;
            foreach ($sectionData as $singleSection) {
                self::getAllSectionFormFields($singleSection);
            }
        }

        return self::$allIndividualFormFields;
    }

    public static function getAllDripperFormFields($clusterData)
    {
        foreach ($clusterData as $key => $cluster) {
            if ($key === 'nodes') {
                foreach ($cluster as $field) {
                    self::$allIndividualFormFields[] = (object) [
                        'id'   => $field->id,
                        'name' => $field->name,
                        'type' => $field->slots[0]->type ? $field->slots[0]->type : 'text',
                    ];
                }
            }

            if ($key === 'branches') {
                foreach ($cluster[0]->clusters as $innerClusters) {
                    self::getAllDripperFormFields($innerClusters);
                }
            }
        }
    }

    public static function getAllSectionFormFields($sectionData)
    {
        foreach ($sectionData as $key => $section) {
            if ($key === 'nodes') {
                foreach ($section as $field) {
                    self::$allIndividualFormFields[] = (object) [
                        'id'   => $field->id,
                        'name' => $field->name,
                        'type' => $field->slots[0]->type ? $field->slots[0]->type : 'text',
                    ];
                }
            }

            if ($key === 'branches') {
                foreach ($section[0]->sections as $innerSections) {
                    self::getAllSectionFormFields($innerSections);
                }
            }
        }
    }

    public static function uploadFilePath($reference)
    {
        global $wpdb;
        $data = $wpdb->get_results($wpdb->prepare("SELECT path FROM {$wpdb->prefix}tripetto_attachments WHERE reference = %s", $reference));

        return $data[0]->path;
    }

    public static function handleTripettoSubmit($dataset, $form)
    {
        $formId = $form->id;
        $flows = FlowService::exists('tripetto', 'tripettoFormSubmission');
        if (empty($flows)) {
            return;
        }

        $finalData = [];
        $fieldsData = $dataset->fields;
        foreach ($fieldsData as $field) {
            if ($field->type === 'tripetto-block-file-upload') {
                $finalData[$field->node->id] = self::uploadFilePath($field->reference) . '/' . "{$field->reference}";
            } else {
                $finalData[$field->node->id] = $field->value;
            }
        }

        if ($finalData !== []) {
            IntegrationHelper::handleFlowForForm($flows, $finalData, $formId);
        }
    }
}
