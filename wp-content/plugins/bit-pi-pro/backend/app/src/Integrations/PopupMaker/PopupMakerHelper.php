<?php

namespace BitApps\PiPro\src\Integrations\PopupMaker;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


class PopupMakerHelper
{
    public static function setFields($formData)
    {
        $fieldsKey = ['name', 'fname', 'lname', 'email', 'consent'];

        if (empty($formData)) {
            return [];
        }

        $allFields = [
            ['name' => 'id', 'type' => 'text', 'label' => __('Popup Id', 'bit-pi'), 'value' => (string) $formData['popup_id']],
        ];

        foreach ($formData as $key => $item) {
            if (\in_array($key, $fieldsKey)) {
                $allFields[] = [
                    'name'  => $key,
                    'type'  => is_email($item) ? 'email' : 'text',
                    'label' => self::getLavelByKey($key),
                    'value' => $item,
                ];
            }
        }

        return $allFields;
    }

    private static function getLavelByKey($key)
    {
        if ($key === 'fname') {
            return 'First Name';
        }

        if ($key === 'lname') {
            return 'Last Name';
        }

        return ucfirst($key);
    }
}
