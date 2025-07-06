<?php

namespace BitApps\PiPro\src\MixInputFunctions;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class BooleanFunctions
{
    public static function toBoolean($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private static function checkParamValueExists($params)
    {
        if (isset($params[0]) && !empty($params[0])) {
            return $params[0];
        }

        return false;
    }
}
