<?php
namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class NumericRule extends Rule
{
    private $message = "The :attribute must be a number";

    public function validate($value): bool
    {
        return is_numeric($value);
    }

    public function message()
    {
        return $this->message;
    }
}
