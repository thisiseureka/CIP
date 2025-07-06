<?php
namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class UppercaseRule extends Rule
{
    private $message = "The :attribute must be in uppercase";

    public function validate($value): bool
    {
        return $value === strtoupper($value);
    }

    public function message()
    {
        return $this->message;
    }
}
