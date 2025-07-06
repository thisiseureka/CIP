<?php

namespace BitApps\PiPro\src\MixInputFunctions;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


final class ArrFunctions
{
    public static function length($values)
    {
        return \count($values);
    }

    public static function join($values, $separator = ',')
    {
        return implode($separator, $values);
    }

    public static function marge(...$arg)
    {
        return array_merge(...$arg);
    }

    public static function arrFirstElement($values)
    {
        return reset($values);
    }

    public static function arrLastElement($values)
    {
        return end($values);
    }
}
