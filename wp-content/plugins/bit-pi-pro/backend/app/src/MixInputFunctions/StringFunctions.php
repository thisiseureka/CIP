<?php

namespace BitApps\PiPro\src\MixInputFunctions;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class StringFunctions
{
    public static function length($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return mb_strlen($value);
    }

    public static function lowercase($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return mb_strtolower($value);
    }

    public static function uppercase($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return mb_strtoupper($value);
    }

    public static function capitalize($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    public static function trim($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return trim($value);
    }

    public static function camelCase($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        // Replace underscores and hyphens with spaces

        $formattedString = str_replace(['-', '_'], ' ', $value);

        // Capitalize each word and remove spaces
        $capitalizedString = ucwords($formattedString);

        // Convert the first character to lowercase
        return lcfirst(str_replace(' ', '', $capitalizedString));
    }

    public static function startCase($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        // Replace underscores or hyphens with spaces
        $processedString = preg_replace('/[^a-zA-Z0-9]+/', ' ', $value);

        // Convert to Start Case
        return ucwords(strtolower($processedString));
    }

    public static function replace($params)
    {
        if (!is_countable($params) && \count($params) !== 3) {
            return false;
        }

        return str_replace($params[1], $params[2], $params[0]);
    }

    public static function stripHtml($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return wp_strip_all_tags($value);
    }

    public static function escapeHtml($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return htmlspecialchars($value);
    }

    public static function md5($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return md5($value);
    }

    public static function subString($params)
    {
        if (!is_countable($params) && \count($params) !== 2) {
            return false;
        }

        return mb_substr($params[0], (int) $params[1], $params[2] ?? null);
    }

    public static function toNumber($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return (int) $value;
    }

    public static function indexOfCharacter($params)
    {
        if (!is_countable($params) && \count($params) !== 2) {
            return false;
        }

        return mb_strpos($params[0], $params[1], (int) $params[2] ?? 0);
    }

    public static function toString($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return (string) $value;
    }

    public static function encodeUrl($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return urlencode($value);
    }

    public static function decodeUrl($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return urldecode($value);
    }

    public static function base64($params)
    {
        $value = self::checkParamValueExists($params);

        if (!$value) {
            return false;
        }

        return base64_encode($value);
    }

    public static function generateHmacHash($encryptionType, $params)
    {
        if (!is_countable($params) && \count($params) !== 2) {
            return false;
        }

        $value = $params[0];

        $secretKey = $params[1];

        return hash_hmac($encryptionType, $value, $secretKey);
    }

    private static function checkParamValueExists($params)
    {
        if (isset($params[0]) && !empty($params[0])) {
            return $params[0];
        }

        return false;
    }
}
