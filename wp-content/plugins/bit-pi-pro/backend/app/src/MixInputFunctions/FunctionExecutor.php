<?php

namespace BitApps\PiPro\src\MixInputFunctions;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Helpers\Utility;
use BitApps\Pi\src\Flow\GlobalNodeVariables;

class FunctionExecutor
{
    /**
     * Function parseAndExecuteTree
     *
     * @param array $tree
     *
     * @return string
     */
    public static function parseAndExecuteTree($tree)
    {
        $functionExecutorInstance = new self();

        if (\is_array($tree) && !isset($tree['type'])) {
            if ($functionExecutorInstance->isAllStrings($tree)) {
                return implode('', $tree);
            }

            $parsedTree = array_map(fn ($itm) => (new self())->parseAndExecuteTree($itm), $tree);

            return array_map(fn ($itm) => \is_array($itm) ? implode('', $itm) : $itm, $parsedTree);
        }

        $tree = (array) $tree;

        $mixInputType = $tree['type'] ?? '';

        $mixInputValue = $tree['value'] ?? '';

        $functionSlug = $tree['slug'] ?? '';

        $functionArgs = $tree['args'] ?? [];

        if ($mixInputType === 'function') {
            if ($functionExecutorInstance->isAllStrings($functionArgs)) {
                return $functionExecutorInstance->execute($functionSlug, $functionArgs);
            }

            $parsedArgs = $functionExecutorInstance->parseAndExecuteTree($functionArgs);

            return $functionExecutorInstance->execute($functionSlug, $parsedArgs);
        }

        if ($mixInputType === 'variable') {
            $nodeResponseData = GlobalNodeVariables::getInstance()->getAllNodeResponse();

            $platformValues = empty($nodeResponseData[$tree['nodeId']]) ? [] : $nodeResponseData[$tree['nodeId']];

            if (!empty($platformValues)) {
                $pathValue = Utility::getValueFromPath($platformValues, $tree['path']);

                return \is_array($pathValue) ? '' : $pathValue;
            }
        }

        if ($mixInputType === 'string' || $mixInputType === 'operator') {
            return $mixInputValue;
        }
    }

    /**
     * Function check if all array items are string
     *
     * @param array $arr
     *
     * @return bool
     */
    private function isAllStrings($arr)
    {
        return array_reduce($arr, fn ($acc, $itm) => $acc && \is_string($itm), true);
    }

    //

    /**
     * Function execute
     *
     * @param string $name
     * @param array  $params
     *
     * @return string
     */
    private function execute($name, $params)
    {
        switch ($name) {
            case 'uppercase':
                return StringFunctions::uppercase($params);
            case 'lowercase':
                return StringFunctions::lowercase($params);
            case 'capitalize':
                return StringFunctions::capitalize($params);
            case 'trim':
                return StringFunctions::trim($params);
            case 'stripHtml':
                return StringFunctions::stripHtml($params);
            case 'md5':
                return StringFunctions::md5($params);
            case 'sha1':
                return StringFunctions::generateHmacHash('sha1', $params);
            case 'sha224':
                return StringFunctions::generateHmacHash('sha224', $params);
            case 'sha256':
                return StringFunctions::generateHmacHash('sha256', $params);
            case 'sha3-224':
                return StringFunctions::generateHmacHash('sha3-224', $params);
            case 'sha3-256':
                return StringFunctions::generateHmacHash('sha3-256', $params);
            case 'sha3-384':
                return StringFunctions::generateHmacHash('sha3-384', $params);
            case 'sha3-512':
                return StringFunctions::generateHmacHash('sha3-512', $params);
            case 'sha384':
                return StringFunctions::generateHmacHash('sha384', $params);
            case 'sha512':
                return StringFunctions::generateHmacHash('sha512', $params);
            case 'ripemd160':
                return StringFunctions::generateHmacHash('ripemd160', $params);
            case 'snefru256':
                return StringFunctions::generateHmacHash('snefru256', $params);
            case 'escapeHtml':
                return StringFunctions::escapeHtml($params);
            case 'strLength':
                return StringFunctions::length($params);
            case 'replace':
                return StringFunctions::replace($params);
            case 'camelcase':
                return StringFunctions::camelCase($params);
            case 'startcase':
                return StringFunctions::startCase($params);
            case 'indexOf':
                return StringFunctions::indexOfCharacter($params);
            case 'toString':
                return StringFunctions::toString($params);
            case 'toNumber':
                return NumberFunctions::toNumber($params);
            case 'toBoolean':
                return BooleanFunctions::toBoolean($params);
            case 'subString':
                return StringFunctions::subString($params);
            case 'encodeUrl':
                return StringFunctions::encodeUrl($params);
            case 'decodeUrl':
                return StringFunctions::decodeUrl($params);
            case 'base64':
                return StringFunctions::base64($params);
            case 'average':
                return MathFunctions::average($params);
            case 'floor':
                return MathFunctions::floor($params);
            case 'min':
                return MathFunctions::min($params);
            case 'max':
                return MathFunctions::max($params);
            case 'round':
                return MathFunctions::round($params);
            case 'abs':
                return MathFunctions::absolute($params);
            case 'ceil':
                return MathFunctions::celi($params);
                // case 'sum':
                //     return MathFunctions::sum($params);
            case 'arr_length':
                return ArrFunctions::length($params);
            case 'join':
                return ArrFunctions::join($params);
            case 'marge':
                return ArrFunctions::marge($params);
            case 'arr_first_element':
                return ArrFunctions::arrFirstElement($params);
            case 'arr_last_element':
                return ArrFunctions::arrLastElement($params);
            case 'add_days':
                return DateFunctions::addDays($params);
            case 'add_months':
                return DateFunctions::addMonths($params);
            case 'add_years':
                return DateFunctions::addYears($params);
            case 'add_minutes':
                return DateFunctions::addMinutes($params);
            case 'date_formate':
                return DateFunctions::dateFormate($params);

            default:
                return '';
        }
    }
}
