<?php

namespace BitApps\PiPro\Deps\BitApps\WPValidator\Rules;

use BitApps\PiPro\Deps\BitApps\WPValidator\Rule;

class JsonRule extends Rule
{
    private $message = "The :attribute must be a valid JSON string";

    public function validate($value): bool
    {
        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function message()
    {
        return $this->message;
    }
}
