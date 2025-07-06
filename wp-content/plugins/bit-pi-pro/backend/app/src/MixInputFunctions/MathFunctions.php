<?php

namespace BitApps\PiPro\src\MixInputFunctions;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


final class MathFunctions
{
    public static function average($numbers)
    {
        $numbers = \is_string($numbers) ? explode(',', $numbers) : $numbers;

        return array_sum($numbers) / \count($numbers);
    }

    public static function floor($number)
    {
        if (!is_numeric($number)) {
            return;
        }

        return floor($number);
    }

    public static function min($numbers)
    {
        $numbers = \is_string($numbers) ? explode(',', $numbers) : $numbers;

        return min($numbers);
    }

    public static function max($numbers)
    {
        $numbers = \is_string($numbers) ? explode(',', $numbers) : $numbers;

        return max($numbers);
    }

    public static function absolute($params)
    {
        $number = $params[0] ?? null;
        if (!is_numeric($number)) {
            return;
        }

        return abs($number);
    }

    public static function round($number)
    {
        if (!is_numeric($number)) {
            return;
        }

        return round($number);
    }

    public static function celi($params)
    {
        $number = $params[0] ?? null;
        if (!is_numeric($number)) {
            return;
        }

        return ceil((float) $number);
    }

    // public static function sum($args)
    // {
    //     // $numbers = \is_string($numbers) ? explode(',', $numbers) : $numbers;

    //     // return array_sum($numbers);
    //     $operator = ['+', '-', '*', '/'];
    //     $result = 0;
    //     $op = '';
    //     foreach ($args as $arg) {
    //         if (\in_array($arg, $operator)) {
    //             $op = $arg;

    //             continue;
    //         }
    //         if ($op === '') {
    //             $result = $arg;
    //         } else {
    //             switch ($op) {
    //                 case '+':
    //                     $result += $arg;

    //                     break;
    //                 case '-':
    //                     $result -= $arg;

    //                     break;
    //                 case '*':
    //                     $result *= $arg;

    //                     break;
    //                 case '/':
    //                     $result /= $arg;

    //                     break;
    //             }
    //         }
    //     }

    //     return $result;
    // }

    public static function parseNumber($numbers)
    {
        return \floatval($numbers);
    }

    public static function formatNumber($numbers)
    {
        return number_format($numbers);
    }
}
