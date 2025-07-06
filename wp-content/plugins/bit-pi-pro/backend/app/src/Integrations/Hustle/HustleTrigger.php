<?php

namespace BitApps\PiPro\src\Integrations\Hustle;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use Hustle_Module_Collection;

final class HustleTrigger
{
    public function getAll()
    {
        if (!is_plugin_active('hustle/opt-in.php') && !is_plugin_active('wordpress-popup/popover.php')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'Hustle'));
        }

        $moduleList = [];
        $modules = Hustle_Module_Collection::instance()->get_all();

        if (!empty($modules)) {
            foreach ($modules as $module) {
                if ($module->module_type === 'social_sharing') {
                    continue;
                }

                if ($module->module_mode === 'informational') {
                    continue;
                }

                $moduleList[] = (object) [
                    'value' => $module->id,
                    'label' => $module->module_name,
                    // 'note'  => \sprintf(__('Runs after user submits email opt-in form of %s (%s)', 'bit-pi'), $module->module_name, $module->module_type)
                ];
            }
        }

        return Response::success($moduleList);
    }

    public static function handleHustleSubmit($entry, $moduleId, $fieldDataArray)
    {
        if (empty($moduleId) || empty($fieldDataArray)) {
            return;
        }

        $flows = FlowService::exists('hustle', 'formSubmit');

        if (empty($flows) || !$flows) {
            return;
        }

        $data = self::prepareFlowData($fieldDataArray);

        if (!empty($data)) {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function prepareFlowData($fieldDataArray)
    {
        if (empty($fieldDataArray)) {
            return false;
        }

        foreach ($fieldDataArray as $item) {
            $data[$item['name']] = \is_array($item['value']) ? implode(',', $item['value']) : $item['value'];
        }

        return $data;
    }
}
