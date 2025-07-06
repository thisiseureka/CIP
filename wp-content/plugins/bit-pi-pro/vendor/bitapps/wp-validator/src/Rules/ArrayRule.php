<?php
namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class ArrayRule extends Rule
{
    private $message = "The :attribute must be array";

    public function validate($value): bool
    {
        return is_array($value);
    }

    public function message()
    {
        return $this->message;
    }
}
