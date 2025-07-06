<?php
namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class IntegerRule extends Rule
{
    private $message = "The :attribute must be an integer";

    public function validate($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public function message()
    {
        return $this->message;
    }

}
