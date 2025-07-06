<?php

namespace BitApps\PiPro\src\Integrations\MailPoet;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use DateTime;
use MailPoet\DI\ContainerWrapper;
use MailPoet\Form\FormsRepository;

final class MailPoetTrigger
{
    public function getAll()
    {
        if (!is_plugin_active('mailpoet/mailpoet.php')) {
            // translators: %s: Plugin name
            return Response::error(\sprintf(__('%s is not installed or activated', 'bit-pi'), 'MailPoet'));
        }

        $formsRepository = ContainerWrapper::getInstance()->get(FormsRepository::class);
        $forms = $formsRepository->findBy(['deletedAt' => null], ['name' => 'asc']);
        $allForms[] = [
            'label' => 'Any form',
            'value' => 'any'
        ];

        if ($forms) {
            foreach ($forms as $form) {
                if ($form->getStatus() === 'enabled') {
                    $allForms[] = (object) [
                        'value' => $form->getId(),
                        'label' => $form->getName(),
                    ];
                }
            }
        }

        return Response::success($allForms);
    }

    public static function handleMailPoetSubmit($data, $segmentIds, $form)
    {
        $formData = [];

        foreach ($data as $key => $item) {
            $keySeparated = explode('_', $key);

            if ($keySeparated[0] === 'cf') {
                $formData[$keySeparated[1]] = \is_array($item) ? self::handleDateField($item) : $item;
            } elseif (\is_array($item)) {
                $formData[$key] = self::handleDateField($item);
            } else {
                $formData[$key] = $item;
            }
        }

        $formId = $form->getId();

        if (!empty($formId) && $flows = FlowService::exists('mailPoet', 'subscriptionSubmit')) {
            IntegrationHelper::handleFlowForForm($flows, $formData, $formId);
        }
    }

    public static function extractColumnData($array, &$result)
    {
        foreach ($array['body'] as $item) {
            if ($item['type'] === 'column' && isset($item['body'])) {
                foreach ($item['body'] as $nestedItem) {
                    if (isset($nestedItem['name'], $nestedItem['id'])) {
                        $result[] = [
                            'name'  => $nestedItem['id'],
                            'type'  => $item['type'],
                            'label' => $nestedItem['name'],
                        ];
                    }

                    if (isset($nestedItem['type']) && $nestedItem['type'] === 'columns') {
                        self::extractColumnData($nestedItem, $result);
                    }
                }
            }
        }
    }

    public static function flattenArray($array)
    {
        $result = [];
        foreach ($array as $item) {
            if (\array_key_exists(0, $item) && \is_array($item[0])) {
                foreach ($item as $itm) {
                    $result[] = $itm;
                }
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    public static function handleDateField($item)
    {
        if (
            \array_key_exists('year', $item)
            && \array_key_exists('month', $item)
            && \array_key_exists('day', $item)
            && (!empty($item['year']) || !empty($item['month']) || !empty($item['day']))
        ) {
            $year = (int) !empty($item['year']) !== 0 ? $item['year'] : date('Y');
            $month = (int) !empty($item['month']) !== 0 ? $item['month'] : 1;
            $day = (int) !empty($item['day']) !== 0 ? $item['day'] : 1;
        } elseif (
            \array_key_exists('year', $item)
            && \array_key_exists('month', $item)
            && (!empty($item['year']) || !empty($item['month']))
        ) {
            $year = (int) !empty($item['year']) !== 0 ? $item['year'] : date('Y');
            $month = (int) !empty($item['month']) !== 0 ? $item['month'] : 1;
            $day = 1;
        } elseif (\array_key_exists('year', $item) && !empty($item['year'])) {
            $year = $item['year'];
            $month = 1;
            $day = 1;
        } elseif (\array_key_exists('month', $item) && !empty($item['month'])) {
            $year = date('Y');
            $month = $item['month'];
            $day = 1;
        }

        if (isset($year, $month, $day)) {
            $date = new DateTime();
            $date->setDate($year, $month, $day);

            return $date->format('Y-m-d');
        }
    }
}
