<?php

namespace BitApps\PiPro\src\MixInputFunctions;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class NumberFunctions
{
    public static function toNumber($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return (int) $value;
    }

    private static function checkParamValueExists($params)
    {
        if (isset($params[0]) && !empty($params[0])) {
            return $params[0];
        }

        return false;
    }
}
